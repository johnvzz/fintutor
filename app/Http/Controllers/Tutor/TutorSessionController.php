<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TutorSession;
use App\Http\Requests\PreSessionPlanRequest;
use App\Models\Student;
use App\Services\GeminiService;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class TutorSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sessions = auth()->user()
            ->tutorSessions()
            ->with('student')
            ->latest()
            ->paginate(10);

        return view('tutor.tutor_session_list', compact('sessions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $students = User::where('role', 'student')
            ->get();

        return view('tutor.tutor_session_create', ['students' => $students]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PreSessionPlanRequest $request)
    {
        try {

            $objectives = json_decode($request->objectives);
            $lesson_outline = json_decode($request->lesson_outlines);
            $practice_questions = json_decode($request->practice_questions);

            $input_data = [
                'topic' => $request->topic,
                'scheduled_at' => Carbon::createFromFormat('d-m-Y', $request->date)->format('Y-m-d'),
                'tutor_id' => auth()->user()->id,
                'student_id' => $request->student,
                'status' => 'scheduled',
                'additional_instructions' => $request->additional_instructions,
                'objectives' => $objectives,
                'lesson_outline' => $lesson_outline,
                'practice_questions' => $practice_questions,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time
            ];

            $session = TutorSession::create($input_data);

            return response()->json([
                'message' => 'Tutor session scheduled successfully'
            ]);
        } catch (QueryException $e) {

            return response()->json([
                'message' => 'Unable to schedule tutor session'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TutorSession $session)
    {
        Gate::authorize('view', $session);

        $session->duration = Carbon::parse($session->start_time)
            ->diffInMinutes(Carbon::parse($session->end_time));

        return view('tutor.tutor_session_detail', compact('session'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $session = TutorSession::findOrFail($id);

        Gate::authorize('view', $session);

        $students = User::where('role', 'student')
            ->get();

        return view('tutor.tutor_session_edit', compact('session', 'students'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $session = TutorSession::findOrFail($id);

        Gate::authorize('update', $session);

        $input_data = [
            'topic' => $request->topic,
            'scheduled_at' => Carbon::createFromFormat('d-m-Y', $request->date)->format('Y-m-d'),
            'tutor_id' => auth()->user()->id,
            'student_id' => $request->student,
            'additional_instructions' => $request->additional_instructions,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time
        ];

        if ($request->objectives) {
            $input_data['objectives'] = json_decode($request->objectives);
        }

        if ($request->lesson_outlines) {
            $input_data['lesson_outline'] = json_decode($request->lesson_outlines);
        }

        if ($request->practice_questions) {
            $input_data['practice_questions'] = json_decode($request->practice_questions);
        }

        $session->update($input_data);

        return response()->json([
            'message' => 'Session updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     *  Generates pre-session plan for each session using an AI model. 
     *  Plan includes objectives, lesson outline and practice questions.
     */
    public function generatePreSessionPlan(Request $request, GeminiService $gemini)
    {
        $student_data = Student::find($request->student);

        // Fetch last session data
        $session = TutorSession::where('status', 'ai_reviewed')
            ->where('tutor_id', auth()->user()->id)
            ->where('student_id', $request->student)
            ->latest('scheduled_at')
            ->first();

        $duration = Carbon::parse($request->start_time)
            ->diffInMinutes(Carbon::parse($request->end_time));

        $prompt = "Generate a pre-session plan for a tutor session.

                    Session details:  
                        - Duration :  {$duration} minutes
                        - Topic : {$request->topic}

                    Student details :  
                        - Grade: {$student_data?->grade}                      
                    ";
        if (!empty($student_data->dob)) {
            $prompt .= " - Age: " . Carbon::parse($student_data->dob)->age;
        }

        if (!empty($session?->progress_summary)) {
            $prompt .= " 
                        Previous session summary : 
            
                        {$session->progress_summary} 

                        Use this previous progress summary to adapt new session plan.                        
                        ";
        }


        if ($request->filled('additional_instructions')) {
            $prompt .= " Incorporate the following 
                        additional instructions: {$request->additional_instructions}.";
        }

        $prompt .= " Return valid JSON only with these fields:
                    objectives: array of strings;
                    lesson_outline: array of objects containing duration, topic, and details;
                    practice_questions: array of question strings.
                    Do not include any other fields or text.";

        $response = $gemini->fetchChat($prompt);

        return response()->json($response);
    }

    /**
     *  New session is started by tutor. 
     *  Updates session status as 'inprogress'.
     */
    public function startSession(Request $request)
    {
        try {

            $session = TutorSession::findOrFail($request->tutor_session);

            Gate::authorize('startSession', $session);

            $started_at = date('Y-m-d H:i:s');

            $session->update([
                'status' => 'inprogress',
                'started_at' => $started_at
            ]);

            return response()->json([
                'message' => 'Session started successfully'
            ]);
        } catch (QueryException $e) {

            return response()->json([
                'message' => 'Failed to start session'
            ], 500);
        }
    }

    /**
     *  Display live session screen for an active session.
     */
    public function liveSession(TutorSession $session)
    {
        Gate::authorize('liveSession', $session);

        // Build the session end datetime using the scheduled date and session end time
        $sessionEnd = Carbon::parse($session->scheduled_at->toDateString() . ' ' . $session->end_time);

        /**
         * Check if the session is in progress and the actual session end time has passed.
         *
         * If the session time has expired, force-update the session status to completed.
         */

        if ($session->status === 'inprogress' && now()->gte($sessionEnd)) {

            $session->update([
                'ended_at' => $sessionEnd,
                'status' => 'completed'
            ]);

            // Redirecting to detail page after force completion.
            return redirect()->route('tutor.sessions.show', $session->id);
        }

        $session->duration = Carbon::parse($session->start_time)
            ->diffInMinutes(Carbon::parse($session->end_time));

        // Returns to the live session screen if the session is still within its scheduled time period.
        return view('live_session', compact('session'));
    }

    /**
     *  Saves notes added by tutor during live session. 
     *  Triggered by autosave(debounced) method from fronend.
     */
    public function saveLiveNotes(TutorSession $session, Request $request)
    {
        Gate::authorize('saveLiveNotes', $session);

        try {

            $livenotes = $request->notes;

            $session->update(['live_notes' => $livenotes]);

            return response()->json([
                'message' => 'Notes saved successfully.'
            ]);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Failed to save notes.'
            ], 500);
        }
    }

    /**
     * End tutor session and generate the post-session debrief
     */
    public function endSession(TutorSession $session, Request $request, GeminiService $gemini)
    {
        Gate::authorize('endSession', $session);

        try {

            $ended_at = date('Y-m-d H:i:s');

            $session->update([
                'ended_at' => $ended_at,
                'status' => 'completed'
            ]);

            return response()->json([
                'message' => 'Session closed successfully.'
            ]);
        } catch (QueryException $e) {

            return response()->json([
                'message' => 'Unable to schedule tutor session'
            ], 500);
        }
    }

    /**
     *  Regenerates debrief summary of session
     */
    public function regenerateDebrief(TutorSession $session, Request $request, GeminiService $gemini)
    {
        Gate::authorize('generateDebrief', $session);

        $request->validate([
            'livenotes' => 'required'
        ]);

        try {

            $this->postSessionSummary($session, $gemini, $request->livenotes);

            return response()->json([
                'message' => 'Debrief generated successfully.',
                'isdebriefed' => true
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'message' => $e->getMessage(),
                'isdebriefed' => false
            ], 500);
        }
    }

    /**
     *  Generates session debrief for an ended session using an AI Model
     */
    public function generateSessionDebrief(GeminiService $gemini, string $livenotes)
    {

        // Prompt for session debrief

        $prompt = "Generate a post-session debrief based on the tutor's live notes: {$livenotes}. ";

        $prompt .= "Categorize the debrief into three fields: summary, homework, and next_focus.
        summary should describe what was covered and the student's progress.
        homework should contain any assigned tasks or practice, can return three questions.
        next_focus should describe what should be focused on in the next session.
        For homeworks return as array
        Return only valid JSON with these three key ";

        $response = $gemini->fetchChat($prompt);

        return $response;
    }

    /**
     *  Generates session progress summary based on session debriefs
     */
    public function generateProgressSummary(TutorSession $session, GeminiService $gemini)
    {
        $sessions = TutorSession::where('tutor_id', auth()->user()->id)
            ->where('student_id', $session->student_id)
            ->where('status', 'completed')
            ->where('scheduled_at', '<=', $session->scheduled_at)
            ->pluck('session_summary')
            ->implode(',');

        $prompt = "Generate a progress summary for the student based on the previous and 
                    current session debriefs provided below.
                    Debriefs:
                    {$sessions}
                    Describe the student's overall progress and improvements based on these sessions.
                    Provide the output as a single description.";

        $response = $gemini->fetchChat($prompt);

        if (!isset($response['error'])) {
            $session->update([
                'progress_summary' =>  $response['content']['progress_summary'],
                'status' => 'ai_reviewed'
            ]);
        }
    }

    /**
     *  Method to save session debrief
     */
    private function saveSessionDebrief(TutorSession $session, GeminiService $gemini, string $livenotes)
    {
        $debrief = $this->generateSessionDebrief($gemini, $livenotes);

        if ($debrief['status'] === 'error') {
            throw new \Exception('Failed to generate debrief');
        }

        $session->update([
            'livenotes' => $livenotes,
            'session_summary' => $debrief['content']['summary'],
            'homework' => $debrief['content']['homework'],
            'next_focus' => $debrief['content']['next_focus']
        ]);
    }


    private function postSessionSummary(TutorSession $session, GeminiService $gemini, string $livenotes)
    {
        $this->saveSessionDebrief($session, $gemini, $livenotes);

        $this->generateProgressSummary($session, $gemini);
    }
}
