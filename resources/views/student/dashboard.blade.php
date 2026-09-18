@extends('layouts.innerbase')

@section('title', 'Dashboard')

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
                        <h4 class="mb-0">Welcome, {{ auth()->user()->name }}!</h4>
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
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box">
                            <div class="info-box-content">
                                <span class="info-box-text">Upcoming Sessions</span>
                                <span class="info-box-number">{{ $upcoming_sessions }}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->

                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box">
                            <div class="info-box-content">
                                <span class="info-box-text">Sessions Completed</span>
                                <span class="info-box-number">{{ $completed_sessions }}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->

                    <!-- fix for small devices only -->
                    <!-- <div class="clearfix hidden-md-up"></div> -->

                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box">
                            <div class="info-box-content">
                                <span class="info-box-text">Homeworks</span>
                                <span class="info-box-number">{{ $homework_count }}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
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
