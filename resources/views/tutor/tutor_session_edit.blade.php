@extends('layouts.innerbase')

@section('title', 'Edit Session')

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
            <div class="row">
                <!-- Quick Example -->
                <div class="col-md-6">
                  <div class="card card-primary card-outline mb-4">
                    <div class="card-header">
                      <div class="card-title">Create Tutor Session</div>
                    </div>
                    <form action="{{ route('tutor.sessions.generate-pre-session-plan') }}" method="POST" 
                          id="createSessionForm">
                      @csrf
                      <div class="card-body">
                          <div class="alert-box d-none"></div>
                        <div class="mb-3">
                          <label for="topic" class="form-label">Topic</label>
                          <input
                            type="text"
                            class="form-control @error('topic') is-invalid @enderror"
                            id="topic"
                            name="topic"
                          />                       
                        </div>                      

                          <div class="mb-3">
                              <div class="row">
                                  <div class="col-md-4">
                                      <label for="session_date" class="form-label">Date</label>
                                      <input type="text" 
                                            autocomplete="off" 
                                            class="form-control @error('topic') is-invalid @enderror" 
                                            id="session_date" 
                                            name="date" />
                                  </div>
                                  <div class="col-md-4">
                                      <label for="session_start_time" class="form-label">Start Time</label>
                                      <input type="password" 
                                            class="form-control timepickr @error('start_time') is-invalid @enderror" 
                                              id="session_start_time" name="start_time" />
                                  </div>
                                  <div class="col-md-4">
                                      <label for="session_end_time" class="form-label">End Time</label>
                                      <input type="text" 
                                            class="form-control timepickr @error('end_time') is-invalid @enderror" 
                                              id="session_end_time" name="end_time" />
                                  </div>
                              </div>                        
                          </div>
                        
                          <div class="mb-3">
                              <label for="student" class="form-label">Student</label>
                              <select class="form-select @error('student') is-invalid @enderror" 
                                      id="student" name="student">
                                  <option value="">Select student</option>
                                  @foreach ($students as $item)
                                      <option value="{{ $item->id }}">{{ $item->name }}</option>
                                  @endforeach
                              </select>
                          </div>
                          <div class="mb-3">
                              <label for="additional_instructions" 
                                    class="form-label mb-0">Additional Instructions
                              </label>
                              <div id="aiHelp" class="form-text mt-0">
                                  (Add any specific instructions or focus areas 
                                  based on the student's level, topic, or learning objectives.
                                  AI will use these instructions when generating the session plan.)
                              </div>
                              <textarea class="form-control" 
                                        id="additional_instructions" 
                                        name="additional_instructions" 
                                        aria-describedby="aiHelp"></textarea>                                      
                          </div>    
                      </div>
                      <div class="card-footer prebutton-wrapper text-end">
                        <button type="submit" class="btn btn-primary btn-sm generate-plan">Generate Session Plan</button>                        
                      </div>
                    </form>
                  </div>
                </div>

                <div class="col-md-6 d-none presessionplan-preview">
                  <div class="card">
                        <div class="card-header">
                          <h3 class="card-title">Pre-Session Plan Preview</h3>
                        </div>
                        <div class="card-body">
                          <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="objectives-section">
                              <p class="mb-0 fw-semibold">Objectives</p>
                              <div class="text-secondary objectives-content"></div>
                            </div>                           
                          </div>
                          <hr />
                          <div class="d-flex justify-content-between align-items-start">
                            <div class="outline-section">
                              <p class="mb-0 fw-semibold">Lesson Outline</p>
                              <div class="text-secondary outline-content"></div>
                            </div>                          
                          </div>
                          <hr />
                          <div class="d-flex justify-content-between align-items-start">
                            <div class="questions-section">
                              <p class="mb-0 fw-semibold">Practice Questions</p>
                              <div class="text-secondary questions-content"></div>
                            </div>                           
                          </div>
                        </div>
                      </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>

      <script>
        document.querySelector('#createSessionForm').addEventListener('submit', async (event) => {

          event.preventDefault();      

          const form = document.querySelector('#createSessionForm');          
          const formData = new FormData(form);

          const generateButton = form.querySelector('.generate-plan');
          generateButton.textContent = 'Processing...'
          generateButton.disabled = true;

          try {

            const response = await fetch( 
              "{{ route('tutor.sessions.generate-pre-session-plan') }}", 
              {
                method: 'POST',
                headers: {
                  'Accept': 'application/json'
                },
                body: formData,
              }
            );

            const data = await response.json();                          
              
            if(response.status === 422) {
              generateButton.textContent = 'Generate Session Plan'
              generateButton.disabled = false;
              handleValidationErrors(data);
              return;
            }

            if(!response.ok) {              
              throw new Error(data.message || `Response Status : ${response.status}`);
            }
        
            if(data.content?.objectives) {

              document.querySelector('.prebutton-wrapper').innerHTML = `
                  <button type="submit" class="btn btn-primary btn-sm generate-plan">
                    Regenerate Session Plan
                  </button>
                  <button type="button" class="btn btn-success btn-sm save-schedule">
                    Schedule Session
                  </button>
              `;

              const previewPane = document.querySelector('.presessionplan-preview');

              previewPane.classList.remove('d-none');

              const  objectivesHtml = `
              <ol>
                ${data.content.objectives.map(item => 
                  `<li>${item}</li>`
                ).join('')}
              </ol>`;

              const lessonHtml = `
                <ol>
                  ${data.content.lesson_outline.map(item => `
                  <li>
                    <strong>${item.duration} - ${item.topic}</strong>
                    <p>${item.details}</p>
                  </li>
                  `).join('')}
                </ol>
              `;

              const questionsHtml = `
                <ol>
                ${data.content.practice_questions.map(item => `
                  <li>
                    <p>${item}</p>                    
                  </li>
                `).join('')}
                </ol>
              `;

              previewPane.querySelector('.objectives-content').innerHTML = objectivesHtml;
              previewPane.querySelector('.outline-content').innerHTML = lessonHtml;
              previewPane.querySelector('.questions-content').innerHTML = questionsHtml;

              document.querySelector('.save-schedule').addEventListener(
                'click', 
                async (event) => {
          
                  const saveButton = event.currentTarget;

                  saveButton.textContent = 'Processing...'
                  saveButton.disabled = true;

                  generateButton.textContent = 'Processing...'
                  generateButton.disabled = true;

                  const finalFormData = new FormData(form);

                  finalFormData.append('objectives', JSON.stringify(data.content.objectives));
                  finalFormData.append('lesson_outlines', JSON.stringify(data.content.lesson_outline));
                  finalFormData.append('practice_questions', JSON.stringify(data.content.practice_questions));

                  try {
                    
                    const finalResponse = await fetch(
                      "{{ route('tutor.sessions.store') }}", 
                      {
                        method: 'POST',
                        headers: {
                          'Accept': 'application/json'
                        },
                        body:finalFormData
                      }
                    );

                    const finalData = await finalResponse.json();   
                    
                    saveButton.textContent = 'Schedule Session'
                    saveButton.disabled = false;

                    generateButton.textContent = 'Regenerate Session Plan'
                    generateButton.disabled = false;

                    if(finalResponse.status === 422) {            
                      handleValidationErrors(finalData);
                      return;
                    }

                    if(!finalResponse.ok) {
                      throw new Error(data.message || `Response Status : ${finalResponse.status}`);
                    }
                    
                    alert(finalData.message);
                    location.href = "{{ route('tutor.sessions.index') }}";
                    
                  } catch(error) {
                    saveButton.textContent = 'Schedule Session'
                    saveButton.disabled = false;

                    generateButton.textContent = 'Regenerate Session Plan'
                    generateButton.disabled = false;

                    alert(error.message);
                    console.log(error.message)
                  }
                }
              );            
            }                        
          } catch(error) {
            generateButton.textContent = 'Generate Session Plan'
            generateButton.disabled = false;
            alert(error.message);
            console.log(error.message)
          }
        });

        function handleValidationErrors(data) {
                      
          Object.entries(data.errors).forEach(([field, message]) => {
            const input = document.querySelector(`[name=${field}]`);

            if(field === 'session_time') {
              const alertBox = document.querySelector('.alertbox')
              alertBox.classList.remove('d-none');
              alertBox.innerHTML = `<div class="alert alert-danger">${message}</div>`;
            }
            
            if(input) {
              input.classList.add('is-invalid');
            }                
          });
        }
        
      </script>
@endsection