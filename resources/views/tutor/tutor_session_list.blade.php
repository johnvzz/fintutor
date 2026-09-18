@extends('layouts.innerbase')

@section('title', 'Tutor Session')

@section('innercontent')
    <!--begin::App Main-->
    <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
            <!--begin::Container-->
            <div class="container-fluid">
                <!--begin::Row-->
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Tutor Sessions</h3>
                    </div>
                    <div class="col-sm-6 text-end">
                        <a href="{{ route('tutor.sessions.create') }}" class="btn btn-success">
                            <i class="bi bi-plus"></i> Create Session
                        </a>
                    </div>
                </div>
                <!--end::Row-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::App Content Header-->
        <!--begin::App Content-->
        <div class="app-content">
            <!--begin::Container-->
            <div class="container-fluid">
                <!--begin::Row-->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h3 class="card-title">Tutor Sessions</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body p-0">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Topic</th>
                                            <th>Student</th>
                                            <th>Scheduled On</th>
                                            <th>Time</th>
                                            <th>Status</th>
                                            <th>Action
                                            <th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($sessions as $item)
                                            <tr class="align-middle">
                                                <td>{{ $item->topic }}</td>
                                                <td>{{ $item->student->name }}</td>
                                                <td>{{ $item->scheduled_at->format('d-m-Y') }}</td>
                                                <td>
                                                    {{ \Carbon\Carbon::createFromFormat('H:i', $item->start_time)->format('h:i A') }}
                                                    -
                                                    {{ \Carbon\Carbon::createFromFormat('H:i', $item->end_time)->format('h:i A') }}
                                                </td>
                                                <td>
                                                    <x-status-badge :status="$item->status" />
                                                </td>
                                                <td>
                                                    <a href="{{ route('tutor.sessions.edit', ['session' => $item->id]) }}">
                                                        <i class="bi bi-pen"></i>
                                                    </a>
                                                    <a href="{{ route('tutor.sessions.show', ['session' => $item->id]) }}">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr class="align-middle">
                                                <td colspan="5" class="text-center">No data found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                            @if ($sessions->hasPages())
                                <div class="card-footer clearfix">
                                    {{ $sessions->links() }}
                                </div>
                            @endif
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!--end::Row-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::App Content-->
    </main>
    <!--end::App Main-->
@endsection
