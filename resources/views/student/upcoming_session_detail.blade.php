@extends('layouts.innerbase')

@section('title', 'Tutor Session - ' . $session->topic)

@section('innercontent')
<main class="app-main">        
    <div class="app-content">
    <div class="container-fluid">
    <div class="row g-4">
        <div class="col-lg-12">
        <div class="card mt-3">
            <div class="card-header d-flex align-items-center">                   
            <h3 class="card-title mb-0">Session Info</h3>
            </div>
            <div class="card-body">
            <div class="row g-3 align-items-stretch">
                <div class="col-sm-8">
                <div class="row row-cols-1 row-cols-sm-2 g-3">                      
                    <div class="col">
                    <div class="text-muted small">Topic</div>
                    <div class="fw-medium">{{ $session->topic }}</div>
                    </div>

                    <div class="col">
                    <div class="text-muted small">Scheduled On</div>
                    <div class="fw-medium">
                        {{ $session->scheduled_at->format('d F Y') }} ,
                        {{ \Carbon\Carbon::createFromFormat('H:i', $session->start_time)->format('h:i A') }} - 
                        {{ \Carbon\Carbon::createFromFormat('H:i', $session->end_time)->format('h:i A') }}
                    </div>
                    </div>

                    <div class="col">
                    <div class="text-muted small">Duration</div>
                    <div class="fw-medium">{{ $session->duration }} min</div>
                    </div>  

                    <div class="col">
                    <div class="text-muted small">Tutor</div>
                    <div class="fw-medium">{{ $session->tutor->name }}</div>
                    </div>                                                                
                </div>
                </div>  

                <div class="col-sm-4">
                <div class="d-flex justify-content-center align-items-center mt-3">
                    <div class="status">
                    {{ $session->status }}
                    </div>
                </div>
                </div>
            </div>

            <hr/>

            <div class="d-flex justify-content-between align-items-start">                      
                @if($session->status === 'inprogress')
                <div>
                    <form method="post" id="sessionStartForm">
                        @csrf
                        <input type="hidden" name="tutor_session" value="{{ $session->id }}" />
                        <button type="submit" class="btn btn-primary">Attend Session</button>
                    </form>
                </div>
                @elseif(in_array($session->status, ['completed', 'ai_reviewed']))
                <div class="text-danger">
                    Session ended on <span class="fw-bold">{{ $session->ended_at->format('d F Y h:i A') }}</span>                                          
                @endif                     
            </div>                  
            </div>
        </div>
        </div>

        <div class="col-lg-12">
        <div class="card mb-3">
            <div class="card-header d-flex align-items-center">                    
            <h3 class="card-title mb-0">Objectives</h3>
            </div>
            <div class="card-body">
            <div class="text-secondary">                        
                <ul>
                    @foreach($session->objectives as $objective)
                        <li>{{ $objective }}</li>
                    @endforeach
                </ul>
            </div>           
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-header d-flex align-items-center">                    
            <h3 class="card-title mb-0">Lesson Outline</h3>
            </div>
            <div class="card-body">                                                              
                <div class="text-secondary">                        
                <ul>                               
                    @foreach($session->lesson_outline as $lesson)
                    <li>
                        <strong>{{ $lesson['duration'] }} - {{ $lesson['topic'] }}</strong>
                        <p>{{ $lesson['details'] }}</p>
                    </li>
                    @endforeach
                </ul> 
                </div>                                                                         
            </div>
        </div>    
        
        <div class="card mb-3">
            <div class="card-header d-flex align-items-center">                    
            <h3 class="card-title mb-0">Practice Questions</h3>
            </div>
            <div class="card-body">                    
            <div class="text-secondary">                        
                <ol>
                    @foreach($session->practice_questions as $question)
                        <li>{{ $question }}</li>
                    @endforeach     
                </ol>
            </div>                          
            </div>
        </div>
    </div>
    </div>
    </div>
</main>     
@endsection