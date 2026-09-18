@extends('layouts.innerbase')

@section('title', 'Profile')

@section('innercontent')
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Profile</h3>
                    </div>
                </div>
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
            </div>
        </div>
        <div class="app-content">
            <div class="container-fluid">
                <div class="row g-3">
                    <div class="col-md-8">
                        <!-- Account -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Account</h3>
                            </div>
                            <div class="card-body">
                                <form class="row g-3" action="{{ route('student.profile.update') }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="col-md-6">
                                        <label class="form-label" for="settings-name"> Full Name </label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="settings-name" name="name" value="{{ old('name', $user->name) }}" />

                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="settings-email"> Email </label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="settings-email" name="email" value="{{ old('email', $user->email) }}" />

                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="settings-phone"> Phone </label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                            id="settings-phone" name="phone" value="{{ old('phone', $user->phone) }}" />

                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="settings-dob"> Date of Birth </label>
                                        <input type="text"
                                            class="form-control datepickr @error('dob') is-invalid @enderror"
                                            id="settings-dob" name="dob"
                                            value="{{ old('dob', $user->student?->dob?->format('d-m-Y')) }}" />
                                        @error('dob')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="settings-grade"> Grade </label>
                                        <input type="text" class="form-control @error('grade') is-invalid @enderror"
                                            id="settings-grade" name="grade"
                                            value="{{ old('grade', $user->student?->grade) }}" />

                                        @error('grade')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="settings-school"> School </label>
                                        <input type="text"
                                            class="form-control datepickr @error('school') is-invalid @enderror"
                                            id="settings-school" name="school"
                                            value="{{ old('school', $user->student?->school) }}" />
                                        @error('school')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="settings-pname"> Parent Name </label>
                                        <input type="text"
                                            class="form-control @error('parent_name') is-invalid @enderror"
                                            id="settings-pname" name="parent_name"
                                            value="{{ old('parent_name', $user->student?->parent_name) }}" />

                                        @error('parent_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="settings-pphone"> Parent Phone </label>
                                        <input type="text"
                                            class="form-control @error('parent_phone') is-invalid @enderror"
                                            id="settings-pphone" name="parent_phone"
                                            value="{{ old('parent_phone', $user->student?->parent_phone) }}" />
                                        @error('parent_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-header">
                                <h3 class="card-title">Password</h3>
                            </div>
                            <div class="card-body">
                                <form class="row g-3" action="{{ route('student.profile.update.password') }}"
                                    method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="col-md-12">
                                        <label class="form-label" for="pwd-current"> Current password </label>
                                        <input type="password"
                                            class="form-control @error('current_password') is-invalid @enderror"
                                            id="pwd-current" name="current_password" />
                                        @error('current_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="pwd-new"> New password </label>
                                        <input type="password"
                                            class="form-control @error('password') is-invalid @enderror" id="pwd-new"
                                            name="password" />
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="pwd-confirm">
                                            Confirm new password
                                        </label>
                                        <input type="password"
                                            class="form-control @error('password') is-invalid @enderror" id="pwd-confirm"
                                            name="password_confirmation" />
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">Update password</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
