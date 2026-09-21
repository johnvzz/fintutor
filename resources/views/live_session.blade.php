@extends('layouts.innerbase')

@section('title', 'Tutor Session - ' . $session->topic)

@section('innercontent')
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Tutor Session</h3>
                    </div>

                </div>
            </div>
        </div>
        <div class="app-content">
            <div class="container-fluid">

                <div class="row g-4">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header d-flex align-items-center">
                                <h3 class="card-title mb-0">Session Info</h3>
                            </div>
                            <div class="card-body">
                                <div class="row row-cols-1 row-cols-sm-2 g-3">

                                    <div class="col">
                                        <div class="text-muted small">Topic</div>
                                        <div class="fw-medium">{{ $session->topic }}</div>
                                    </div>

                                    <div class="col">
                                        <div class="text-muted small">Scheduled On</div>
                                        <div class="fw-medium">
                                            {{ $session->scheduled_at->format('d F Y') }} ,
                                            {{ \Carbon\Carbon::createFromFormat('H:i', $session->start_time)->format('h:i A') }}
                                            -
                                            {{ \Carbon\Carbon::createFromFormat('H:i', $session->end_time)->format('h:i A') }}
                                        </div>
                                    </div>

                                    <div class="col">
                                        <div class="text-muted small">Tutor</div>
                                        <div class="fw-medium">{{ $session->tutor->name }}</div>
                                    </div>
                                    <div class="col">
                                        <div class="text-muted small">Student</div>
                                        <div class="fw-medium">{{ $session->student->name }}</div>
                                    </div>
                                </div>

                                <hr />

                                <div class="d-flex justify-content-between align-items-start timer-wrapper">
                                    @if (empty($session->ended_at))
                                        <div>
                                            Session ends in <span id="sessionTimer"></span>
                                        </div>
                                        <div>
                                            <button type="button" class="btn btn-primary btn-endsession">End
                                                Session</button>
                                        </div>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header d-flex align-items-center">
                                <h3 class="card-title mb-0">Live Notes</h3>
                            </div>
                            <div class="card-body livenote-wrapper">
                                <label for="livenotes" class="form-label">Enter livenotes</label>
                                <div id="aihelp" class="form-text mt-0">
                                    (Add notes during the session for AI-generated summaries.
                                    You can also include homework instructions, next-session focus,
                                    student behavior, and other relevant observations.)
                                </div>
                                <textarea name="livenotes" id="livenotes" class="form-control" placeholder="Enter sessions notes here..."
                                    aria-describedby="aihelp">{{ $session->live_notes }}</textarea>

                                <div class="livenote-info mt-2"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header d-flex align-items-center">
                                <h3 class="card-title mb-0">Pre Session Plan</h3>
                            </div>
                            <div class="card-body">
                                <div>
                                    <p class="mb-0 fw-semibold">Objectives</p>
                                    <div class="text-secondary">
                                        <ul>
                                            @foreach ($session->objectives as $objective)
                                                <li>{{ $objective }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <hr />

                                <div>
                                    <p class="mb-0 fw-semibold">Lesson Outline</p>
                                    <div class="text-secondary">
                                        <ul>
                                            @foreach ($session->lesson_outline as $lesson)
                                                <li>
                                                    <strong>{{ $lesson['duration'] }} - {{ $lesson['topic'] }}</strong>
                                                    <p>{{ $lesson['details'] }}</p>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <hr />

                                <div>
                                    <p class="mb-0 fw-semibold">Practice Questions</p>
                                    <div class="text-secondary">
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
    </main>

    <script>
        async function autosave(content) {

            try {
                const response = await fetch(
                    "{{ route('tutor.sessions.savenotes', $session->id) }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            notes: content
                        })
                    }
                );

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || `Response Status: ${response.status}`);
                }

                const livenoteInfo = document.querySelector('.livenote-info');
                livenoteInfo.innerHTML =
                    `<div class="text-success">
                        <i class="bi bi-check"></i> ${data.message}
                    </div>`;

                setTimeout(() => {
                    livenoteInfo.innerHTML = "";
                }, 2000);

            } catch (error) {
                const livenoteInfo = document.querySelector('.livenote-info');
                livenoteInfo.innerHTML =
                    `<div class="text-danger">
                        <i class="bi bi-exclamation-circle"></i>Failed to save notes.
                    </div>`;

                setTimeout(() => {
                    livenoteInfo.innerHTML = "";
                }, 2000);

                console.error(`Error : ${error.message}`);
            }

        }

        function autosaveHandler(fn) {

            let timer;

            return function(...args) {

                clearTimeout(timer);

                timer = setTimeout(() => {
                    fn(...args);
                }, 1500);
            }
        }

        const saveLivenotes = autosaveHandler(autosave);

        document.querySelector('#livenotes').addEventListener('input', (event) => {
            saveLivenotes(event.target.value);
        });

        const endSessionBtn = document.querySelector('.btn-endsession');

        endSessionBtn && endSessionBtn.addEventListener('click', endSession);

        async function endSession() {

            const confirmed = confirm('Confirm end session? Your live notes will be used to generate session summary');

            if (confirmed) {
                const livenoteInput = document.querySelector('#livenotes');

                try {
                    const response = await fetch(
                        "{{ route('tutor.sessions.endsession', $session->id) }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                livenotes: livenoteInput.value
                            })
                        }
                    );

                    const data = await response.json();

                    if (response.status === 422) {
                        livenoteInput.classList.add('is-invalid');
                        livenoteInput.focus();
                    }

                    if (!response.ok) {
                        throw new Error(data.message || `Response Status: ${response.status}`);
                    }

                    location.href = "{{ route('tutor.sessions.show', $session->id) }}";
                } catch (error) {
                    console.log(error.message);
                }
            }
        }

        console.log(`scheduled_at: {{ $session->scheduled_at->toDateString() }}`);
        const sessionEnd = new Date('{{ $session->scheduled_at->toDateString() }} {{ $session->end_time }}').getTime();
        console.log(`sessionEnd: ${sessionEnd}`);

        let sessionTimer;

        function updateTimer() {

            const remainingSeconds = Math.max(Math.floor((sessionEnd - Date.now()) / 1000), 0);
            console.log(`remainingSeconds: ${remainingSeconds}`);
            const hours = Math.floor(remainingSeconds / 3600); // hours
            const minutes = Math.floor((remainingSeconds % 3600) / 60); // minutes
            const seconds = remainingSeconds % 60;

            const timerWrapper = document.getElementById('sessionTimer');

            if (timerWrapper) {
                timerWrapper.innerHTML =
                    `${String(hours).padStart(2, '0')}:
                ${String(minutes).padStart(2, '0')}:
                ${String(seconds).padStart(2, '0')}`;
            }

            if (remainingSeconds === 0) {

                clearInterval(sessionTimer);

                alert('Session hits the duration and ends.');

                const timerWrapper = document.querySelector('.timer-wrapper');

                if (timerWrapper) {
                    timerWrapper.innerHTML =
                        `<div class="text-danger fw-bold">Session expired. Please review your notes and end session</div>`;
                }

                const livenoteWrapper = document.querySelector('.livenote-wrapper');

                if (livenoteWrapper) {
                    livenoteWrapper.insertAdjacentHTML('beforeend', `<div class="mt-2 text-end">
                                        
                        </div>`);
                }
            }
        }

        // initial call
        updateTimer();

        sessionTimer = setInterval(updateTimer, 1000);


        // Generates summary
        async function generateSummary() {

            try {

                const response = await fetch(
                    "{{ route('tutor.sessions.generatedebrief', $session->id) }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            livenotes: document.querySelector('#livenotes').value
                        })
                    }
                );

                const data = await response.json();

                if (response.status === 422) {
                    livenoteInput.classList.add('is-invalid');
                    livenoteInput.focus();
                }

                if (!response.ok) {
                    throw new Error(data.message || `Response Status: ${response.status}`);
                }

                location.href = '';
            } catch (error) {
                console.error(error.message);
            }
        }
    </script>
@endsection
