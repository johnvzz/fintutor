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
                <h3 class="mb-0">Manage Students</h3>
              </div> 
              <div class="col-sm-6 text-end">
                  <a href="{{ route('tutor.students.create') }}" class="btn btn-success">
                    <i class="bi bi-plus"></i> Create Student
                  </a>
              </div>             
            </div>
            <!--end::Row-->

            @if(session('success'))
              <div class="alert alert-success">
                {{ session('success') }}
              </div>
            @endif
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
                    <h3 class="card-title">Students</h3>                    
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body p-0">
                    <table class="table">
                      <thead>
                        <tr>                         
                          <th>Name</th>
                          <th>Email</th>
                          <th>Contact</th>
                          <th>Action<th>
                        </tr>
                      </thead>
                      <tbody>
                        @forelse ($students as $student)
                          <tr class="align-middle">                          
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->email }}</td>
                            <td>{{ $student->phone }}</td>
                            <td>
                              <a href="{{ route('tutor.students.edit', ['student' => $student->id]) }}"><i class="bi bi-pen"></i><a/>
                              <a href="{{ route('tutor.students.show', ['student' => $student->id]) }}"><i class="bi bi-eye"></i><a/>
                            </td>
                          </tr>
                        @empty
                          <tr class="align-middle"> 
                            <td colspan="4" class="text-center">No data found</td>
                          </tr>      
                        @endforelse                        
                      </tbody>
                    </table>
                  </div>
                  <!-- /.card-body -->
                  @if($students->hasPages())
                  <div class="card-footer clearfix">
                    {{ $sessions->students() }}
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