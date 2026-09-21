@extends('layouts.innerbase')

@section('title', 'Edit Session')

@section('innercontent')
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Tutor Session</h3>
                    </div>
                </div>
                <div class="alertbox d-none"></div>
            </div>
        </div>
        <div class="app-content">
            <div class="container-fluid">
                <div class="row">
                    <!-- Quick Example -->
                    <div class="col-md-6">
                        <div class="card card-primary card-outline mb-4">
                            <div class="card-header">
                                <div class="card-title">Create Tutor Session</div>
                            </div>
                            <form method="POST" id="editSessionForm">
                                @csrf

                                <div class="card-body">
                                    <div class="alert-box d-none"></div>
                                    <div class="mb-3">
                                        <label for="topic" class="form-label">Topic</label>
                                        <input type="text" class="form-control @error('topic') is-invalid @enderror"
                                            id="topic" name="topic" value="{{ old('topic', $session->topic) }}" />
                                    </div>

                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="session_date" class="form-label">Date</label>
                                                <input type="text" autocomplete="off"
                                                    class="form-control @error('topic') is-invalid @enderror"
                                                    id="session_date" name="date"
                                                    value="{{ old('date', $session->scheduled_at->format('d-m-Y')) }}" />
                                            </div>
                                            <div class="col-md-4">
                                                <label for="session_start_time" class="form-label">Start Time</label>
                                                <input type="password"
                                                    class="form-control timepickr @error('start_time') is-invalid @enderror"
                                                    id="session_start_time" name="start_time"
                                                    value="{{ old('start_time', $session->start_time) }}" />
                                            </div>
                                            <div class="col-md-4">
                                                <label for="session_end_time" class="form-label">End Time</label>
                                                <input type="text"
                                                    class="form-control timepickr @error('end_time') is-invalid @enderror"
                                                    id="session_end_time" name="end_time"
                                                    value="{{ old('end_time', $session->end_time) }}" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="student" class="form-label">Student</label>
                                        <select class="form-select @error('student') is-invalid @enderror" id="student"
                                            name="student">
                                            <option value="">Select student</option>
                                            @foreach ($students as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ old('student', $session->student_id) == $item->id ? 'selected' : '' }}>
                                                    {{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="additional_instructions" class="form-label mb-0">Additional Instructions
                                        </label>
                                        <div id="aiHelp" class="form-text mt-0">
                                            (Add any specific instructions or focus areas
                                            based on the student's level, topic, or learning objectives.
                                            AI will use these instructions when generating the session plan.)
                                        </div>
                                        <textarea class="form-control" id="additional_instructions" name="additional_instructions" aria-describedby="aiHelp">{{ old('additional_instructions', $session->additional_instructions) }}</textarea>
                                    </div>
                                </div>
                                <div class="card-footer prebutton-wrapper text-end">
                                    <button type="button" class="btn btn-primary btn-sm regenerate-plan">
                                        Regenerate Session Plan
                                    </button>
                                    <button type="submit" class="btn btn-success btn-sm update-schedule">
                                        Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="col-md-6 presessionplan-preview">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Pre-Session Plan Preview</h3>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="objectives-section">
                                        <p class="mb-0 fw-semibold">Objectives</p>
                                        <div class="text-secondary objectives-content">
                                            <ul>
                                                @foreach ($session->objectives as $objective)
                                                    <li>{{ $objective }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <hr />
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="outline-section">
                                        <p class="mb-0 fw-semibold">Lesson Outline</p>
                                        <div class="text-secondary outline-content">
                                            <ul>
                                                @foreach ($session->lesson_outline as $lesson)
                                                    <li>
                                                        <strong>{{ $lesson['duration'] }} -
                                                            {{ $lesson['topic'] }}</strong>
                                                        <p>{{ $lesson['details'] }}</p>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <hr />
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="questions-section">
                                        <p class="mb-0 fw-semibold">Practice Questions</p>
                                        <div class="text-secondary questions-content">
                                            <ol>
                                                @foreach ($session->practice_questions as $question)
                                                    <li>{{ $question }}</li>
                                                @endforeach
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </main>

    <script>
        const regenerateButton = document.querySelector('.regenerate-plan');
        const updateButton = document.querySelector('.update-schedule');
        const previewPane = document.querySelector('.presessionplan-preview');
        const form = document.querySelector('#editSessionForm');

        let generatedPlan = null;

        regenerateButton.addEventListener('click', async (event) => {

            event.preventDefault();

            regenerateButton.textContent = 'Processing...';
            regenerateButton.disabled = true;

            updateButton.disabled = true;

            const formData = new FormData(form);

            try {
                const response = await fetch(
                    "{{ route('tutor.sessions.generate-pre-session-plan') }}", {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json'
                        },
                        body: formData,
                    }
                );

                const data = await response.json();

                if (response.status === 422) {

                    handleValidationErrors(data);
                    return;
                }

                if (!response.ok) {
                    throw new Error(data.message || `Response Status : ${response.status}`);
                }

                if (data.content?.objectives) {

                    generatedPlan = data.content;

                    const objectivesHtml = `
                      <ol>
                        ${generatedPlan.objectives.map(item => 
                          `<li>${item}</li>`
                        ).join('')}
                      </ol>`;

                    const lessonHtml = `
                      <ol>
                        ${generatedPlan.lesson_outline.map(item => 
                          `<li><strong>${item.duration} - ${item.topic}</strong><p>${item.details}</p></li>`).join('')}
                      </ol>
                    `;

                    const questionsHtml = `
                      <ol>
                      ${generatedPlan.practice_questions.map(item => `<li><p>${item}</p></li>`).join('')}
                      </ol>
                    `;

                    previewPane.querySelector('.objectives-content').innerHTML = objectivesHtml;
                    previewPane.querySelector('.outline-content').innerHTML = lessonHtml;
                    previewPane.querySelector('.questions-content').innerHTML = questionsHtml;
                }
            } catch (error) {

                alert(error.message);
                console.log(error.message)
            } finally {

                updateButton.textContent = 'Save Changes';
                updateButton.disabled = false;

                regenerateButton.textContent = 'Regenerate Session Plan';
                regenerateButton.disabled = false;
            }
        });

        form.addEventListener('submit', async (event) => {

            event.preventDefault();

            updateButton.textContent = 'Processing...'
            updateButton.disabled = true;

            regenerateButton.disabled = true;

            const formData = new FormData(form);

            formData.append('_method', 'PUT');

            if (generatedPlan) {

                formData.append('objectives', JSON.stringify(generatedPlan.objectives));
                formData.append('lesson_outlines', JSON.stringify(generatedPlan.lesson_outline));
                formData.append('practice_questions', JSON.stringify(generatedPlan.practice_questions));
            }

            try {

                const response = await fetch(
                    "{{ route('tutor.sessions.update', $session->id) }}", {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        },
                        body: formData
                    }
                );

                const data = await response.json();

                if (response.status === 422) {
                    handleValidationErrors(data);
                    return;
                }

                if (!response.ok) {
                    throw new Error(data.message ||
                        `Response Status : ${response.status}`);
                }

                alert(data.message);
                location.href = "{{ route('tutor.sessions.index') }}";
            } catch (error) {

                alert(error.message);
                console.log(error.message)
            } finally {

                updateButton.textContent = 'Save Changes';
                updateButton.disabled = false;

                regenerateButton.textContent = 'Regenerate Session Plan';
                regenerateButton.disabled = false;
            }
        });

        function handleValidationErrors(data) {

            Object.entries(data.errors).forEach(([field, message]) => {
                const input = document.querySelector(`[name=${field}]`);

                if (field === 'session_time') {
                    const alertBox = document.querySelector('.alertbox')
                    alertBox.classList.remove('d-none');
                    alertBox.innerHTML = `<div class="alert alert-danger">${message}</div>`;
                    document.querySelector(`[name=start_time]`).classList.add('is-invalid');
                    document.querySelector(`[name=end_time]`).classList.add('is-invalid');
                }

                if (input) {
                    input.classList.add('is-invalid');
                }
            });
        }
    </script>
@endsection
