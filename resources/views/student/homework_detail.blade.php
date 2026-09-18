@extends('layouts.innerbase')

@section('title', 'Homework')

@section('innercontent')
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Homework</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="app-content">
            <div class="container-fluid">
                <div class="row g-3">
                    <!-- Sidebar -->
                    <div class="col-md-3">
                        <!-- About details -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">About</h3>
                            </div>
                            <div class="card-body small">
                                <p class="fw-semibold mb-1">
                                    <i class="bi bi-mortarboard me-1 text-secondary" aria-hidden="true"></i>
                                    Topic
                                </p>
                                <p class="text-secondary mb-3">
                                    {{ $session->topic }}
                                </p>
                                <p class="fw-semibold mb-1">
                                    <i class="bi bi-calendar2-event me-1 text-secondary" aria-hidden="true"></i>
                                    Scheduled On
                                </p>
                                <p class="text-secondary mb-3">
                                    {{ $session->scheduled_at->format('d F Y') }}
                                </p>

                                <p class="fw-semibold mb-1">
                                    <i class="bi bi-clock-history me-1 text-secondary" aria-hidden="true"></i>Time
                                </p>
                                <p class="text-secondary mb-3">
                                    {{ \Carbon\Carbon::createFromFormat('H:i', $session->start_time)->format('h:i A') }} -
                                    {{ \Carbon\Carbon::createFromFormat('H:i', $session->end_time)->format('h:i A') }}
                                </p>

                                <p class="fw-semibold mb-1">
                                    <i class="bi bi-person-workspace me-1 text-secondary" aria-hidden="true"></i>
                                    Tutor
                                </p>
                                <p class="text-secondary mb-3">
                                    {{ $session->tutor->name }}
                                </p>

                                <p class="fw-semibold mb-1">
                                    <i class="bi bi-tags me-1 text-secondary" aria-hidden="true"></i>
                                    Status
                                </p>
                                <p class="text-secondary mb-3">
                                    <x-status-badge :status="$session->status" />
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-9">
                        <div class="card">
                            <div class="card-header d-flex align-items-center">
                                <h3 class="card-title mb-0">Homework</h3>
                            </div>
                            <div class="card-body">
                                <div>
                                    <div class="text-secondary">
                                        <ul>
                                            @foreach ($session->objectives as $objective)
                                                <li>{{ $objective }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
