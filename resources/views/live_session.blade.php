@extends('layouts.innerbase')

@section('title', 'Tutor Session - ' . $session->topic)

@section('innercontent')
    <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6">
                <h3 class="mb-0">Tutor Session</h3>
              </div>
              
            </div>
          </div>
        </div>
        <div class="app-content">
          <div class="container-fluid">

            <div class="row g-4">
              <div class="col-lg-12">
                <div class="card">
                  <div class="card-header d-flex align-items-center">                   
                    <h3 class="card-title mb-0">Session Info</h3>
                  </div>
                  <div class="card-body">
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
                        <div class="text-muted small">Tutor</div>
                        <div class="fw-medium">{{ $session->tutor->name }}</div>
                      </div>  
                      <div class="col">
                        <div class="text-muted small">Student</div>
                        <div class="fw-medium">{{ $session->student->name }}</div>
                      </div>                                                                
                    </div>

                    <hr/>

                    <div class="d-flex justify-content-between align-items-start">                                           
                      @if(empty($session->ended_at))
                      <div>
                        Session ends in <span id="sessionTimer"></span>
                      </div>
                      <div>
                        <button type="button" class="btn btn-primary btn-endsession">End Session</button>
                      </div>
                      @endif                      
                    </div>
                    
                  </div>
                </div>
              </div>
              <div class="col-lg-12">
                <div class="card">
                  <div class="card-header d-flex align-items-center">                    
                    <h3 class="card-title mb-0">Pre Session Plan</h3>
                  </div>
                  <div class="card-body">
                    <div>
                      <p class="mb-0 fw-semibold">Objectives</p>
                      <div class="text-secondary">                        
                        <ul>
                          @foreach($session->objectives as $objective)
                              <li>{{ $objective }}</li>
                          @endforeach
                        </ul>
                      </div>                            
                    </div>                        
                    <hr />

                    <div>
                      <p class="mb-0 fw-semibold">Lesson Outline</p>
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
                    <hr />

                    <div>
                      <p class="mb-0 fw-semibold">Practice Questions</p>
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
              
            <div class="col-lg-12">
                <div class="card">
                  <div class="card-header d-flex align-items-center">
                    <i class="bi bi-shield-lock fs-4 text-primary me-2" aria-hidden="true"></i>
                    <h3 class="card-title mb-0">Live Notes</h3>
                  </div>
                  <div class="card-body">                   
                        <label for="livenotes" class="form-label">Enter livenotes</label>
                        <div id="aihelp" class="form-text mt-0">
                            (Add notes during the session for AI-generated summaries. 
                                You can also include homework instructions, next-session focus, 
                                student behavior, and other relevant observations.)
                        </div>
                        <textarea name="livenotes" id="livenotes" class="form-control" 
                           placeholder="Enter sessions notes here..." aria-describedby="aihelp">{{ $session->live_notes }}</textarea>
                           
                        @if(!empty($session->ended_at) && empty($session->session_summary))
                        <div class="mt-2 text-end">
                          <button type="button" class="btn btn-success btn-sm" onclick="generateSummary()">Generate Session Summary</button>
                        </div>                        
                        @endif   
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>

    <script>

        async function autosave(content) {
                       
            const response = await fetch(
                "{{ route('tutor.sessions.savenotes', $session->id) }}",
                {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        notes: content
                    })
                }
            );

            const data = await response.json();

            if(!response.ok) {
                throw new Error(data.message || `Response Status: ${response.status}`);
            }            
        }

        function autosaveHandler(fn) {

            let timer;

            return function(...args) { 

                clearTimeout(timer);

                timer = setTimeout(() => {
                    fn(...args);
                }, 1500);
            }
        }

        const saveLivenotes =  autosaveHandler(autosave);

        document.querySelector('#livenotes').addEventListener('input', (event) => {           
            saveLivenotes(event.target.value);
        });

        const endSessionBtn = document.querySelector('.btn-endsession');

        endSessionBtn && endSessionBtn.addEventListener('click', endSession);

        async function endSession() {

          const livenoteInput = document.querySelector('#livenotes');

          try {
                const response = await fetch(
                    "{{ route('tutor.sessions.endsession', $session->id) }}",
                    {
                      method: 'POST',
                      headers: {
                          'Content-Type': 'application/json',
                          'X-CSRF-TOKEN': '{{ csrf_token() }}',
                          'Accept': 'application/json'
                      },
                      body: JSON.stringify({
                          livenotes: livenoteInput.value
                      })
                    }                  
                ) ;

                const data = await response.json();

                if(response.status === 422) {
                  livenoteInput.classList.add('is-invalid');
                  livenoteInput.focus();
                }

                if(!response.ok) {
                    throw new Error(data.message || `Response Status: ${response.status}`);
                }

                if(data.isdebriefed) {
                  location.href = "{{ route('tutor.sessions.livesession', $session->id) }}"
                }
            } catch(error) {
                console.log(error.message);
            }
        }

        const startedAt = new Date('{{ $session->started_at }}').getTime();
        const endedAt = @json($session->ended_at);
        const duration = 300; //Math.floor('{{ $session->duration }}' * 60); // duration in seconds
        let sessionTimer;
         console.log(`duration: ${duration}`);

          console.log(`endedAt: ${endedAt}`);

        function updateTimer() {

            const now = new Date().getTime();
            const elapsedTime = now - startedAt; // in milliseconds

            const elapsedSeconds =  Math.floor(elapsedTime / 1000); // convert to seconds

            const remainingSeconds = Math.max(duration - elapsedSeconds, 0)

            console.log(`totalSeconds: ${totalSeconds}`);

            const hours = Math.floor(remainingSeconds/3600); // hours
            const minutes = Math.floor((remainingSeconds%3600)/60); // minutes
            const seconds = Math.floor(remainingSeconds%60);

            document.getElementById('sessionTimer').innerHTML = 
                `${String(hours).padStart(2, '0')}:
                ${String(minutes).padStart(2, '0')}:
                ${String(seconds).padStart(2, '0')}`;
                
            if(remainingSeconds === 0) { 
              clearInterval(sessionTimer);
              alert('Session hits the duration and ends.');
              endSession();
            }    
        }

        if(!endedAt) {

          updateTimer(); // initial call

          sessionTimer = setInterval(updateTimer, 1000);
        }
        

        async function generateSummary() {

          try {

            const response = await fetch(
              "{{ route('tutor.sessions.generatedebrief', $session->id) }}",
              {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                  livenotes: document.querySelector('#livenotes').value
                })                                                  
              }
            );

            const data = await response.json();
            
            if(response.status === 422) {
                livenoteInput.classList.add('is-invalid');
                livenoteInput.focus();
              }

              if(!response.ok) {
                throw new Error(data.message || `Response Status: ${response.status}`);
              }

              location.href = 
          } catch(error) {
            console.error(error.message);
          }
        }

    </script>
@endsection