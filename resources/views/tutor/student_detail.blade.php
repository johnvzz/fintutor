@extends('layouts.innerbase')

@section('title', 'Student')

@section('innercontent')
    <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6">
                <h3 class="mb-0">Student</h3>
              </div>              
            </div>
          </div>
        </div>
        <div class="app-content">
          <div class="container-fluid">
            <div class="row g-4">
              <div class="col-lg-8">
                <div class="card">
                  <div class="card-header d-flex align-items-center">                   
                    <h3 class="card-title mb-0">Basic Detail</h3>
                  </div>
                  <div class="card-body">
                    <div class="row row-cols-1 row-cols-sm-2 g-3">
                    
                      <div class="col">
                        <div class="text-muted small">Name</div>
                        <div class="fw-medium">{{$user->name }}</div>
                      </div>

                      <div class="col">
                        <div class="text-muted small">Email</div>
                        <div class="fw-medium">{{$user->email }}</div>
                      </div>

                      <div class="col">
                        <div class="text-muted small">Phone</div>
                        <div class="fw-medium">{{$user->phone }}</div>
                      </div>

                      <div class="col">
                        <div class="text-muted small">Joined On</div>
                        <div class="fw-medium">{{$user->created_at->format('d M Y') }}</div>
                      </div>  

                      <div class="col">
                        <div class="text-muted small">Grade</div>
                        <div class="fw-medium">{{$user->student?->grade }}</div>
                      </div>

                      <div class="col">
                        <div class="text-muted small">School</div>
                        <div class="fw-medium">{{$user->student?->school }}</div>
                      </div>

                      <div class="col">
                        <div class="text-muted small">Parent Name</div>
                        <div class="fw-medium">{{$user->student?->parent_name }}</div>
                      </div>

                      <div class="col">
                        <div class="text-muted small">Parent Phone</div>
                        <div class="fw-medium">{{$user->student?->parent_phone }}</div>
                      </div>
                                                                                     
                    </div>                  
                  </div>
                </div>
              </div>
          
            </div>
          </div>
        </div>
      </main>

@endsection