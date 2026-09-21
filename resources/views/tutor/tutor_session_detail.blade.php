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
                                <div class="row g-3 align-items-stretch">
                                    <div class="col-sm-12">
                                        <div class="row row-cols-1 row-cols-sm-3 g-3">
                                            <div class="col">
                                                <div class="text-muted small">Topic</div>
                                                <div class="fw-medium">{{ $session->topic }}</div>
                                            </div>

                                            <div class="col">
                                                <div class="text-muted small">Scheduled On</div>
                                                <div class="fw-medium">
                                                    {{ $session->scheduled_at->format('d F Y') }}
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="text-muted small">Time</div>
                                                <div class="fw-medium">
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

                                            <div class="col">
                                                <div class="text-muted small">Status</div>
                                                <div><x-status-badge :status="$session->status" /></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr />

                                <div class="d-flex justify-content-between align-items-start">

                                    @if ($session->status === 'scheduled')
                                        <div>
                                            <form method="post" id="sessionStartForm">
                                                @csrf
                                                <input type="hidden" name="tutor_session" value="{{ $session->id }}" />
                                                <button type="submit" class="btn btn-primary start-button">Start
                                                    Session</button>
                                            </form>
                                        </div>
                                    @elseif($session->status === 'inprogress' && empty($session->ended_at))
                                        <div>
                                            <button type="button" class="btn btn-primary btn-endsession">End
                                                Session</button>
                                        </div>
                                    @elseif($session->status === 'completed')
                                        <div class="text-danger">
                                            Session ended on <span
                                                class="fw-bold">{{ $session->ended_at->format('d F Y h:i A') }}</span>
                                        </div>
                                    @elseif(!empty($session->ended_at) && empty($session->session_summary))
                                        <div class="mt-2 text-end">
                                            <button type="button" class="btn btn-success btn-sm"
                                                onclick="generateSummary()">Generate Session Summary</button>
                                        </div>
                                    @endif
                                </div>
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

                    @if (in_array($session->status, ['inprogress', 'completed', 'ai_reviewed']))
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header d-flex align-items-center">
                                    <h3 class="card-title mb-0">Live Notes</h3>
                                </div>
                                <div class="card-body">
                                    <div class="text-secondary">
                                        {{ $session->live_notes }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (in_array($session->status, ['completed', 'ai_reviewed']))
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header d-flex align-items-center">
                                    <h3 class="card-title mb-0">Session Debrief</h3>
                                </div>
                                <div class="card-body">
                                    <div>
                                        <p class="mb-0 fw-semibold">Summary</p>
                                        <div class="text-secondary">
                                            {{ $session->session_summary }}
                                        </div>
                                    </div>
                                    <hr />
                                    <div>
                                        <p class="fw-semibold mb-2">Homework</p>
                                        <div class="text-secondary">
                                            <ol>
                                                @foreach ($session->homework ?? [] as $item)
                                                    <li>{{ $item }}</li>
                                                @endforeach
                                            </ol>
                                        </div>
                                    </div>
                                    <hr />
                                    <div>
                                        <p class="fw-semibold mb-2">Next Focus</p>
                                        <div class="text-secondary">
                                            {{ $session->next_focus }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (in_array($session->status, ['ai_reviewed']))
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header d-flex align-items-center">
                                    <h3 class="card-title mb-0">Session Summary</h3>
                                </div>
                                <div class="card-body">
                                    <div>
                                        <p class="mb-0 fw-semibold">Progress Summary</p>
                                        <div class="text-secondary">
                                            {{ $session->progress_summary }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

    <script>
        document.querySelector('#sessionStartForm').addEventListener('submit', async (event) => {

            event.preventDefault();

            const form = event.currentTarget;
            const formData = new FormData(form);

            const startButton = document.querySelector('.start-button');
            startButton.textContent = 'Processing...';
            startButton.disabled = true;

            try {
                const response = await fetch(
                    "{{ route('tutor.sessions.start-session') }}", {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json'
                        },
                        body: formData
                    }
                );

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || `Response Status : $response.status`);
                }

                location.href = "{{ route('tutor.sessions.livesession', $session->id) }}";
            } catch (error) {
                console.log(error.message);
            }
        });
    </script>
@endsection
