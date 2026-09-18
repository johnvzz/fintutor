@extends('layouts.base', ['bodyClass' => 'register-page bg-body-secondary'])

@section('title', 'Signup')

@section('content')
<div class="register-box">
      <div class="register-logo">
        <a href="/"><b>{{ config('app.name') }}</b></a>
      </div>
      <!-- /.register-logo -->
      <div class="card">
        <div class="card-body register-card-body">
          <p class="register-box-msg">Register a new membership</p>

          <form action="{{ route('signup.store') }}" method="post">
            @csrf
            <div class="d-flex justify-content-center mb-4">
              <div class="btn-group" role="group" aria-label="Billing period">
                <input type="radio" class="btn-check" name="role" id="role-tutor" value="tutor" checked />
                <label class="btn btn-outline-primary" for="role-tutor">I'm a tutor</label>
                <input type="radio" class="btn-check" name="role" id="role-student" value="student" />
                <label class="btn btn-outline-primary" for="role-student">I'm a student</label>
              </div>
            </div>
            <div class="input-group mb-3">
              <input type="text" class="form-control @error('name') is-invalid @enderror" 
                      placeholder="Full Name" name="name" id="name" value="{{ old('name') }}" />             
            </div>
            <div class="input-group mb-3">
              <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                      placeholder="Phone" name="phone" id="phone" value="{{ old('phone') }}" />              
            </div>
            <div class="input-group mb-3">
              <input type="email" class="form-control @error('email') is-invalid @enderror" 
                    placeholder="Email" name="email" id="email" value="{{ old('email') }}" />             
            </div>
            <div class="input-group mb-3">
              <input type="password" class="form-control @error('password') is-invalid @enderror" 
                      placeholder="Password" name="password" id="password" />              
            </div>
            <!--begin::Row-->
            <div class="row">              
              <!-- /.col -->
              <div class="col-12 d-flex justify-content-end">
                <div class="d-grid gap-2">
                  <button type="submit" class="btn btn-success">Sign Up</button>
                </div>
              </div>
              <!-- /.col -->
            </div>
            <!--end::Row-->
          </form>          

          <p class="mb-0 mt-2">
            <a href="{{ route('login') }}" class="text-center"> Already a member? Sign In </a>
          </p>
        </div>
        <!-- /.register-card-body -->
      </div>
    </div>
    <!-- /.register-box -->
@endsection