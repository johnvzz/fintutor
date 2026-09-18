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
                        <h3 class="mb-0">Welcome, {{ auth()->user()->name }}!</h3>
                    </div>
                </div>
                <!--end::Row-->
            </div>
            <!--end::Container-->
        </div>
        <div class="app-content">
            <!--begin::Container-->
            <div class="container-fluid">
                <!-- Info boxes -->
                <div class="row">

                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box">
                            <div class="info-box-content">
                                <span class="info-box-text">Today</span>
                                <span class="info-box-number">
                                    {{ $todaySessions }}
                                </span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box">
                            <div class="info-box-content">
                                <span class="info-box-text">Upcoming</span>
                                <span class="info-box-number">{{ $upcomingSessions }}</span>
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
                                <span class="info-box-text">Completed</span>
                                <span class="info-box-number">{{ $completedSessions }}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->

            </div>
            <!--end::Container-->
        </div>
        <!--end::App Content-->
    </main>
    <!--end::App Main-->
@endsection
