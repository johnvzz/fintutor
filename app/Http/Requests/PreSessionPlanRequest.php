<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\TutorSession;
use Carbon\Carbon;

class PreSessionPlanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'topic' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date_format:d-m-Y'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'student' => [
                'required',
                'integer',
                Rule::exists('users', 'id')->where(
                    fn($query) => $query->where('role', 'student')
                ),
            ],
            'additional_instructions' => ['nullable', 'string', 'max:2000'],
        ];
    }

    /**
     * Add validation for overlapping sessions after the base rules pass.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $sessionId = $this->route('session');

            $date = Carbon::createFromFormat('d-m-Y', $this->date)->format('Y-m-d');

            $query = TutorSession::where('scheduled_at', $date)
                ->where('start_time', '<', $this->end_time)
                ->where('end_time', '>', $this->start_time)
                ->where(function ($query) {
                    $query->where('tutor_id', $this->user()->getAuthIdentifier())
                        ->orWhere('student_id', $this->student);
                });

            if ($sessionId) {
                $query->where('id', '!=', $sessionId);
            }

            $exists = $query->exists();

            if ($exists) {
                $validator->errors()->add(
                    'session_time',
                    'The selected time overlaps with an existing session. Please choose a different time.'
                );
            }
        });
    }
}
