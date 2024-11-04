<div class="accordion" id="accordionExample">
                    <div class="accordion-item">
                        <h1 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Exam Information
                            </button>
                        </h1>
                        <div id="collapseOne" class=" atnaccodrdian accordion-collapse collapse " aria-labelledby="headingOne" data-bs-parent="#accordionExample" style="">
                            <div class="accordion-body">
                                <div class="row">
                                
                                    
                                    <div class="col-xs-12">
                                <div id="collapseOne" class=" atnaccodrdian accordion-collapse collapse " aria-labelledby="headingOne" data-bs-parent="#accordionExample" style="">
                                    <div class="row">
                                        <div class="col-md-6">
                                            
                                        </div>
                                    </div>
                                    <div class="row">
                                    <div class="exam_information w-75 mx-auto text-center">
                                        <input type="hidden" name="exam_id" value="{{ @$exam_info->id }}"/>
                                        <p>Academic Year : {{ str_replace("-","/",@$acyear) }}</p>
                                        @if(isset($exam_type))
                                        <p>Term : {{ @$exam_type }} / {{ @$term }}</p>
                                        @else
                                        <p>Term : {{ @$term }}</p>
                                        @endif
                                        <p>Class&Section :{{ @$class }} </p>
                                        <p>Department : {{ @$department }}</p>
                                        <p>Subject : {{ @$subject }}</p>
                                        @if(isset($exam_info))
                                        <p>Exam Date : {{ @$exam_info->exam_date }} - Exam Time : {{ @$exam_info->exam_time }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            </div>

                                    
                                    
                                    
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                
                
            </div>
             <div class="card">
                <div class="card-body">
                   
                  
                    

                    {{-- add mark students info --}}

                    <div class="col-xs-12">
                        <div class="row">
                           

                            <div class="container">
                            <table class="table table-stripped table-responsive">
                                <thead>
                                    <tr>
                                        <th>Sl.No</th>
                                        <th>Image</th>
                                        <th>Student Name</th>
                                        <th>Reg No</th>
                                        @if (@$type=="create")
                                            @foreach (@$markdistribution as $mark )
                                            <th>{{ $mark->distribution_name }}({{ $mark->mark }})</th>
                                            @endforeach

                                        @else
                                        @php
                                            if($data){
                                                $data_first = $data->pluck('distribution')->first();
                                            }
                                        @endphp
                                        @foreach ($data_first as $key => $mark)
                                        @if (isset($mark['distributionname']) && isset($mark['originalmark']))
                                            <th>{{ $mark['distributionname'] }} ({{ $mark['originalmark'] }})</th>
                                        @else
                                            <th>Invalid array structure</th>
                                        @endif
                                       @endforeach
                                     
                                        @endif
                                       
                                        <th>Total</th>
                                        <th>Is Present</th>
                                    </tr>
                                </thead>

                                @if (@$type=="create")
                                  <tbody>
                                   
                                     @forelse (@$students as $student )
                                 
                                    <tr> 
                                        <td>{{ $loop->index+1 }}</td>
                                        <td><img src="{{ asset(@$student->image ? @$student->image : "assets/images/default.jpg") }}" class="img-fluid stu_profile" width="50px"/></td>
                                        <td>{{ $student->first_name }}</td>
                                        <td>{{ $student->reg_no }}</td>
                                       
                                        @foreach (@$markdistribution as $mark )
                                       <input type="hidden" name="mark[{{ @$student->id }}][{{ $mark->id }}][distributionname]" value="{{ $mark->distribution_name }}"/>
                                       <input type="hidden" name="mark[{{ @$student->id }}][{{ $mark->id }}][originalmark]" value="{{ $mark->mark }}"/>
                                        
                                        <td>
                                            @php
                                            $input=0;
                                                // attendnace automatic doing
                                                $attendance_value = 0; 
                                                $read_only =  "";
                                                if($mark->distribution_name == "Attendance")
                                                {
                                                    $read_only = ($attendance_type == 1) ? "readonly" : "";
                                                    if(@$attendance_type == 1)
                                                    { 
                                                        if(isset($students_attendance_info)){
                                                            if(isset($students_attendance_info[$student->id])){
                                                                $max_mark = $mark->where('distribution_name', 'Attendance')->where('status', '1')->value('mark');
                                                                $attendance = ($total_attendance > 0) ? ($students_attendance_info[$student->id] / $total_attendance) * 100 : 0;
                                                                $attendance_value = ($attendance > 0) ? ($attendance/ $max_mark ): 0;
                                                            }
                                                            else{
                                                                $student_attendance = $attendance_student->where(['student_id'=>$student->id,'attendance_id'=>$attendance_id])->first();
                                                      
                                                                $count =($student_attendance) ? $student_attendance->where('attendance','=','1')->count() : 0;
                                                            
                                                                $max_mark = $mark->where('distribution_name', 'Attendance')->where('status', '1')->value('mark');
                                                                $attendance = ($total_attendance > 0) ? ($count / $total_attendance) * 100 : 0;
                                                                $attendance_value = ($attendance > 0) ? ($attendance/ $max_mark ): 0;
                                                            }
                                                        }
                                                        else{
                                                                $student_attendance = $attendance_student->where(['student_id'=>$student->id,'attendance_id'=>$attendance_id])->first();
                                                            
                                                                $count =($student_attendance) ? $student_attendance->where('attendance','=','1')->count() : 0;
                                                            
                                                                $max_mark = $mark->where('distribution_name', 'Attendance')->where('status', '1')->value('mark');
                                                                $attendance = ($total_attendance > 0) ? ($count / $total_attendance) * 100 : 0;
                                                                $attendance_value = ($attendance > 0) ? ($attendance/ $max_mark ): 0;
                                                        }
                                                         
                                                       
                                                    }
                                                    
                                              
                                                    $input_value = $attendance_value;
                                                }
                                                else if((isset($exam_field)) && ($mark->id == $exam_field)){
                                                    $table = ($exam_status === "Online") ?  DB::table('online_exam') :  DB::table('offline_exam_mark') ;
                                                    $record=  $table->where([
                                                        'student_id' => $student->id,
                                                        'exam_id' => $exam_info->id
                                                         ])->get();
                                                        
                                                       foreach($record as $value){
                                                        if(isset($value->score) || isset($value->total_marks)){
                                                            $input = (($exam_status === "Online") ? $value->total_marks : $value->score) ;
                                                        }
                                                        else{
                                                            $input = 0;
                                                        }
                                                       }
                                                        $input_value = $input;
                                                        // var_dump($exam_info->id);
                                                     }
                                                   else{
                                                     $input_value = 0;
                                                   }  
                                             
                                            @endphp                                                                      
                                         
                                         <input type="number" name="mark[{{ $student->id }}][{{ $mark->id }}][mark]" id="mark_{{ $student->id }}_{{ $mark->id }}" minlength="{{ $mark->mark }}"
                                         oninput="ExamConfig.prventMaxvalue(this, this.value)" class="form-control studenttotalcalculate{{ $student->id }}"
                                         placeholder="{{ $mark->mark }}" value="{{ @$input_value }}" data-id="{{ $student->id }}" {{$read_only}}/>
                                        @endforeach
                                        <td>
                                            <input type="number" name="mark[{{ $student->id }}][total]" value="0" readonly class="form-control studenttotal{{ $student->id }}"/>
                                            {{-- <p class="studenttotaltext{{ $student->id }}">0</p> --}}
                                        </td>
                                        <td>
                                            <label class="switch">
                                                <input type="checkbox" name="mark[{{ @$student->id }}][present]" checked class="toggle-class" >
                                                <span class="slider round"></span>
                                            </label>
                                        </td>
                                    </tr>
                                 
                                     @empty
                                    <p>No Students Found</p>
                                   
                                    @endforelse
                                  
                                </tbody>
                                @else

                                  <tbody>
                                     @forelse (@$data as $student )
                                    
                                    <tr> 
                                        <td>{{ $loop->index+1 }}</td>
                                        <td><img src="{{ asset(@$student->image ? @$student->students->image : "assets/images/default.jpg") }}" class="img-fluid stu_profile" width="50px"/></td>
                                        <td>{{ @$student->students->first_name }}</td>
                                        <td>{{ @$student->students->reg_no }}</td>
                                        @foreach (@$student->distribution as $mark_id =>$mark )
                                       <input type="hidden" name="mark[{{ @$student->students->id }}][{{ $mark_id  }}][distributionname]" value="{{ $mark['distributionname'] }}"/>
                                       <input type="hidden" name="mark[{{ @$student->students->id }}][{{ $mark_id  }}][originalmark]" value="{{ $mark['originalmark'] }}"/>
                                        <td><input type="number" name="mark[{{ @$student->students->id }}][{{ $mark_id  }}][mark]" id="{{ $mark['originalmark'] }}" minlength="{{ $mark['originalmark'] }}"
                                             onkeyup="ExamConfig.prventMaxvalue(this,this.id)" class="form-control studenttotalcalculate{{@$student->students->id}}" 
                                             placeholder="{{ $mark['originalmark'] }}" data-id="{{@$student->students->id}}" value="{{$mark['mark'] ?$mark['mark'] : 0 }}"/></td>
                                        @endforeach
                                        <td>
                                            <input type="hidden" name="mark[{{ @$student->students->id }}][total]"class="studenttotal{{@$student->students->id}}" value="{{@$student->total_mark}}"/>
                                            <p class="studenttotaltext{{$student->students->id}}">{{@$student->total_mark}}</p>
                                        </td>
                                        <td>
                                            <label class="switch">
                                                <input type="checkbox" name="mark[{{ @$student->students->id }}][present]" {{@$student->is_present ? "checked" : ""}} class="toggle-class">
                                                <span class="slider round"></span>
                                            </label>
                                        </td>
                                    </tr>
                                   
                                     @empty
                                    <p>No Students Found</p>
                                    @endforelse
                                </tbody>
                                    
                                @endif

                              
                            </table>
                            </div>
                                
                           
                        </div>
                    </div>
                </div>
            </div>

              <script>
              $(document).ready(function() {
                    // Select all input fields
                    $('input[type="number"]').each(function() {
                        // Get the ID and input value
                        var id = $(this).attr('id');
                        var value = $(this).val();
                        
                        // Call the function for each input field
                        ExamConfig.prventMaxvalue(document.getElementById(id), value);
                    });
                });
              </script>
          