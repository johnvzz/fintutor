@extends('layouts.innerbase')

@section('title', 'Edit Student')

@section('innercontent')
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Create Student</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="app-content">
            <div class="container-fluid">
                <div class="row g-4">

                    <!-- Quick Example -->
                    <div class="col-md-6">
                        <div class="card card-primary card-outline mb-4">
                            <div class="card-header">
                                <div class="card-title">Create Student</div>
                            </div>
                            <form action="{{ route('tutor.students.update', $user->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name', $user->name) }}" />
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email address</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email', $user->email) }}" />
                                    </div>
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Phone</label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                            id="phone" name="phone" value="{{ old('phone', $user->phone) }}" />
                                    </div>
                                    <div class="mb-3">
                                        <label for="dob" class="form-label">Date of Birth</label>
                                        <input type="text"
                                            class="form-control datepickr @error('dob') is-invalid @enderror" id="dob"
                                            name="dob"
                                            value="{{ old('dob', $user->student?->dob?->format('d-m-Y')) }}" />
                                    </div>
                                    <div class="mb-3">
                                        <label for="grade" class="form-label">Grade</label>
                                        <input type="text" class="form-control @error('grade') is-invalid @enderror"
                                            id="grade" name="grade"
                                            value="{{ old('grade', $user->student?->grade) }}" />
                                    </div>
                                    <div class="mb-3">
                                        <label for="school" class="form-label">School</label>
                                        <input type="text" class="form-control" id="school" name="school"
                                            value="{{ old('school', $user->student?->school) }}" />
                                    </div>
                                    <div class="mb-3">
                                        <label for="parent_name" class="form-label">Parent Name</label>
                                        <input type="text"
                                            class="form-control @error('parent_name') is-invalid @enderror" id="parent_name"
                                            name="parent_name"
                                            value="{{ old('parent_name', $user->student?->parent_name) }}" />
                                    </div>
                                    <div class="mb-3">
                                        <label for="parent_phone" class="form-label">Parent Phone</label>
                                        <input type="text"
                                            class="form-control @error('parent_phone') is-invalid @enderror"
                                            id="parent_phone" name="parent_phone"
                                            value="{{ old('parent_phone', $user->student?->parent_phone) }}" />
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>
@endsection
