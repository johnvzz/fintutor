<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentFormRequest;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = User::where('role', 'student')
            ->latest()
            ->paginate(10);

        return view('tutor.student_list', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tutor.student_create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StudentFormRequest $request)
    {
        $input_data = $request->validated();

        $input_data['role'] = 'student';
        $input_data['password'] = Str::random(10);

        $user = User::create($input_data);

        // Stores data to student model        
        $student_data = [
            'user_id' => $user->id,
            'dob' => $request->filled('dob')
                ? Carbon::createFromFormat('d-m-Y', $request->dob)->format('Y-m-d')
                : null,
            'grade' => $input_data['grade'],
            'school' => $request->school,
            'parent_name' => $input_data['parent_name'],
            'parent_phone' => $input_data['parent_phone'],
        ];

        Student::create($student_data);

        return redirect()
            ->route('tutor.students.index')
            ->with('success', 'Student created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::with('student')->findOrFail($id);

        return view('tutor.student_detail', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::with('student')->findOrFail($id);

        return view('tutor.student_edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StudentFormRequest $request, User $student)
    {
        $input_data = $request->validated();

        $student->update($input_data);

        // Updates Student model data    
        $studentProfile = $student->student;

        $profile_data = [
            'dob' => $request->filled('dob')
                ? Carbon::createFromFormat('d-m-Y', $request->dob)->format('Y-m-d')
                : null,
            'grade' => $input_data['grade'],
            'school' => $request->school,
            'parent_name' => $input_data['parent_name'],
            'parent_phone' => $input_data['parent_phone'],
        ];

        Student::updateOrCreate(
            ['user_id' => $student->id],
            $profile_data
        );

        return redirect()
            ->route('tutor.students.index')
            ->with('success', 'Student details updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
