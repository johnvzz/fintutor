@extends('layouts.base', ['bodyClass' => 'layout-fixed sidebar-expand-lg bg-body-tertiary'])

@section('content')
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
        @include('layouts.navbar')

        @include('layouts.sidebar')

        @yield('innercontent')

        @include('layouts.footer')
    </div>
    <!--end::App Wrapper-->
@endsection