@extends('layouts.innerbase')

@section('title', 'Homework')

@section('innercontent')
    <style>
        .homework-content {
            font-size: 0.975em;
        }

        .view-button {
            font-size: 0.75em !important;
            padding: 0.035rem 0.75rem !important;
        }
    </style>
    <!--begin::App Main-->
    <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
            <!--begin::Container-->
            <div class="container-fluid">
                <!--begin::Row-->
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Homework</h3>
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
                <div class="row g-4 mb-4">
                    @forelse($homeworks as $item)
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="fw-bold fs-12">{{ $item->topic }}</div>
                                    <small>{{ $item->scheduled_at->format('d F Y') }}</small>
                                    <div class="homework-content my-2">
                                        {{ Str::words($item->homework[0], 8) }}
                                    </div>
                                    <a href="{{ route('student.homeworks.show', $item->id) }}"
                                        class="btn btn-outline-primary rounded-pill float-end view-button">View</a>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    @empty
                        <div class="col-md-12 text-center mt-3">
                            <div class="alert alert-primary p-5">
                                <i class="bi bi-mortarboard"></i> No homework assigned yet.
                            </div>
                        </div>
                    @endforelse
                </div>
                <!--end::Row-->

            </div>
            <!--end::Container-->
        </div>
        <!--end::App Content-->
    </main>
    <!--end::App Main-->
@endsection
