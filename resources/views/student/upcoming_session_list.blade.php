@extends('layouts.innerbase')

@section('title', 'Upcoming Session')

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
                        <h3 class="mb-0"> Sessions</h3>
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
                                <h3 class="card-title">Upcoming Sessions</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body p-0">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Topic</th>
                                            <th>Tutor</th>
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
                                                <td>{{ $item->tutor->name }}</td>
                                                <td>
                                                    {{ $item->scheduled_at->format('d-m-Y') }}
                                                </td>
                                                <td>
                                                    {{ \Carbon\Carbon::createFromFormat('H:i', $item->start_time)->format('h:i A') }}
                                                    -
                                                    {{ \Carbon\Carbon::createFromFormat('H:i', $item->end_time)->format('h:i A') }}

                                                </td>
                                                <td><x-status-badge :status="$item->status" /></td>
                                                <td>
                                                    <a href="{{ route('student.upcoming-sessions.show', $item->id) }}">
                                                        <i class="bi bi-eye"></i><a />
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
