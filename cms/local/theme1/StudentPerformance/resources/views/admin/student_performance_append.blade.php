@if(isset($stud_perform_id))
        @php
           $stud_perform_data = DB::table('studentperformance_data')->where('student_performance_id',$stud_perform_id)->get();
        @endphp
    <thead>
        <tr>
            <th>No</th>
            <th>Student Name</th>
            <th>Academic</th>
            <th>Attendance</th>
            @if(@$student_performances)
                @foreach(@$student_performances as $stud_perform)
                <th>{{ $stud_perform }}</th>
                @endforeach 
            @endif
            <th>Overall Average</th>
        </tr>
    </thead>
    <tbody>
    @foreach($students as $student)
            @php
                $data = $stud_perform_data->where('student_id',$student->id)->first();
            @endphp
            <tr>
                <td>{{$loop->index+1}}</td>
                <td>{{ $student->first_name }}
                    <input type="hidden" name="student_id[]" value="{{ $student->id }}"></td>
                    @php
                        $perform_data = DB::table('studentperformance')->where('id',$stud_perform_id)->first();
                        $dateString = $perform_data->month_year;
                        $dateParts = explode(" ", $dateString);

                        $monthName = $dateParts[0]; // March
                        $year = $dateParts[1]; // 2024

                        // Convert month name to month number
                        $monthNumber = date('m', strtotime($monthName));

                        $start_date = date('m/d/Y', mktime(0, 0, 0, $monthNumber, 1, $year));
                        $end_date = date('m/d/Y', mktime(0, 0, 0, $monthNumber + 1, 0, $year));


                        $exams = DB::table('exam')
                            ->whereBetween('exam_date', [$start_date,$end_date])
                            ->where(['academic_year'=>$perform_data->academic_year,'exam_term'=>$perform_data->term_id,'class_id'=>$perform_data->class_id,'section_id'=>$perform_data->section_id])
                            ->get();
                        $attendance = DB::table('attendance')->where('attendance_month',$monthName)->where('attendance_year', $year)
                            ->where(['academic_year'=>$perform_data->academic_year,'academic_term'=>$perform_data->term_id,'class_id'=>$perform_data->class_id,'section_id'=>$perform_data->section_id])
                            ->get();  
                            $exam_ids = $exams->pluck('id');
                    $attendance_ids = $attendance->pluck('id');

                    //attendance calculation
                    $attendance_stud = DB::table('attendance_students')->whereIn('attendance_id',$attendance_ids)->where(['student_id'=> $student->id,"attendance"=>1])->count();
                    $total_attendance = $attendance_ids->count(); 
                    if ($total_attendance !== 0 && $attendance_stud !== 0) {
                        $attendance_percentage = round(($attendance_stud / $total_attendance) * 100) ;
                    } else {
                        $attendance_percentage = "0";
                    }
                    
                    //academic calculation
                    $max_mark = $exams->pluck('max_mark');
                    $total = $max_mark->sum();
                    // Calculate offline exam marks
                    $offline_marks = DB::table('offline_exam_mark')
                        ->whereIn('exam_id', $exam_ids)
                        ->where('student_id', $student->id)
                        ->sum('score');

                    // Calculate online exam marks
                    $online_marks = DB::table('online_exam')
                        ->whereIn('exam_id', $exam_ids)
                        ->where('student_id', $student->id)
                        ->sum('total_marks');
                
                    $marks = $offline_marks + $online_marks;
                    
                    if ($total !== 0 && $marks !== 0) {
                        $percentage = round(($marks / $total) * 100) ;
                    } else {
                        $percentage = "0";
                    }      
                    @endphp   
                <td>{{ @$percentage }} %
                    <input type="hidden" value="{{ @$data->academic }}" name="academic[]" id="academic" class="in_class studenttotalcalculate{{ $student->id }}"
                        onkeyup="StudentPerformance.prventMaxvalue(this,{{ $student->id }})" data-id="{{@$student->id}}">
                </td>
                <td>{{ @$attendance_percentage }} %
                        <input type="hidden" value="{{ $data->attendance }}" name="attendance[]" id="attendance" class="in_class1 studenttotalcalculate{{ $student->id }}"
                        onkeyup="StudentPerformance.prventMaxvalue(this,{{ $student->id }})" data-id="{{@$student->id}}">
                </td>
                    @if(@$student_performances)
                        @foreach(@$student_performances as $stud_perform)
                            @php
                            if($stud_perform == "Discipline and Compliance"){
                                $student_perform = $data->disciple_compliance ?? 0;
                            }
                            else{
                                $student_perform = $data->sport_event ?? 0; 
                            }
                            @endphp
                            <td>
                                <div class="percent-input-container">
                                    <input type="number" value="{{ $student_perform }}" class="form-control in_class studenttotalcalculate{{ $student->id }}" name="{{ $stud_perform }}[]"  id="{{$loop->index+1 }}" 
                                    onkeyup="StudentPerformance.prventMaxvalue(this,{{ $student->id }})" data-id="{{@$student->id}}">
                                </div>    
                            </td>
                        @endforeach
                    @endif
                    <td class="text-center">
                        <input type="hidden" name="student_performance[]" class="in_class2 studenttotal{{@$student->id}}" value="{{ $data->overall_average }}" id="student_performance"> 
                    
                        <div class="student_performance_chart{{ @$student->id }} text-center"></div>
                    </td>
            </tr>
    @endforeach
    </tbody>
@else
    <thead>
        <tr>
            <th>No</th>
            <th>Student Name</th>
            <th>Academic</th>
            <th>Attendance</th>
            @if(@$student_performances)
                @foreach(@$student_performances as $stud_perform)
                <th class="noExport">{{ $stud_perform }}</th>
                @endforeach 
            @endif
            <th class="noExport">Overall Average</th>
        </tr>
    </thead>
    {{-- {{ $loop->index+1 }} --}}
    <tbody>
        @foreach (@$students as $student)
            <tr>
                <td>{{$loop->index+1}}</td>
                <td>{{$student->first_name}}
                    <input type="hidden" name="student_id[]" value="{{ $student->id }}">
                </td>
                @php
                if($academic_year && $term_id && $school_type && $period && $date && $class_id && $section_id){
                    if($period == "weekly"){
                        $dates = explode(',', $date);
                        $start_date = trim($dates[0]); 
                        $end_date = trim($dates[1]); 

                        $for_start_date = \DateTime::createFromFormat('m/d/Y', trim($dates[0]))->format('Y-m-d');
                        $for_end_date = \DateTime::createFromFormat('m/d/Y', trim($dates[1]))->format('Y-m-d');
                    
                        $exams = DB::table('exam')
                            ->where([
                                'academic_year' => $academic_year,
                                'exam_term' => $term_id,'class_id'=>$class_id,'section_id'=>$section_id
                            ])
                            ->whereBetween('exam_date', [$start_date,$end_date])
                            ->get();
                        $attendance = DB::table('attendance')->whereBetween('attendance_date', [$for_start_date,$for_end_date])
                            ->where(['academic_year'=>$academic_year,'academic_term'=>$term_id,'class_id'=>$class_id,'section_id'=>$section_id])
                            ->get();    
                    }
                    else{
                        $dateString = $date;
                        $dateParts = explode(" ", $dateString);

                        $monthName = $dateParts[0]; // March
                        $year = $dateParts[1]; // 2024

                        // Convert month name to month number
                        $monthNumber = date('m', strtotime($monthName));

                        $start_date = date('m/d/Y', mktime(0, 0, 0, $monthNumber, 1, $year));
                        $end_date = date('m/d/Y', mktime(0, 0, 0, $monthNumber + 1, 0, $year));


                        $exams = DB::table('exam')
                            ->whereBetween('exam_date', [$start_date,$end_date])
                            ->where(['academic_year'=>$academic_year,'exam_term'=>$term_id,'class_id'=>$class_id,'section_id'=>$section_id])
                            ->get();
                        $attendance = DB::table('attendance')->where('attendance_month',$monthName)->where('attendance_year', $year)
                            ->where(['academic_year'=>$academic_year,'academic_term'=>$term_id,'class_id'=>$class_id,'section_id'=>$section_id])
                            ->get();    
                    }
                
                    $exam_ids = $exams->pluck('id');
                    $attendance_ids = $attendance->pluck('id');

                    //attendance calculation
                    $attendance_stud = DB::table('attendance_students')->whereIn('attendance_id',$attendance_ids)->where(['student_id'=> $student->id,"attendance"=>1])->count();
                    $total_attendance = $attendance_ids->count(); 
                    if ($total_attendance !== 0 && $attendance_stud !== 0) {
                        $attendance_percentage = round(($attendance_stud / $total_attendance) * 100) ;
                    } else {
                        $attendance_percentage = "0";
                    }
                    
                    //academic calculation
                    $max_mark = $exams->pluck('max_mark');
                    $total = $max_mark->sum();
                    // Calculate offline exam marks
                    $offline_marks = DB::table('offline_exam_mark')
                        ->whereIn('exam_id', $exam_ids)
                        ->where('student_id', $student->id)
                        ->sum('score');

                    // Calculate online exam marks
                    $online_marks = DB::table('online_exam')
                        ->whereIn('exam_id', $exam_ids)
                        ->where('student_id', $student->id)
                        ->sum('total_marks');
                
                    $marks = $offline_marks + $online_marks;
                    
                    if ($total !== 0 && $marks !== 0) {
                        $percentage = round(($marks / $total) * 100) ;
                    } else {
                        $percentage = "0";
                    }
                }
                @endphp
                <td>{{ @$percentage }} %
                    <input type="hidden" value="{{ @$percentage }}" name="academic[]" id="{{ @$start_date }}_{{@$end_date}}" class="in_class studenttotalcalculate{{ $student->id }}"
                    onkeyup="StudentPerformance.prventMaxvalue(this,{{ $student->id }})" data-id="{{@$student->id}}">
                </td>
                <td>{{ $attendance_percentage }} %
                    <input type="hidden" value="{{ @$attendance_percentage }}" name="attendance[]" data-temp = {{$date}} id="{{ @$attendance_stud }}" class="in_class1 studenttotalcalculate{{ $student->id }}"
                    onkeyup="StudentPerformance.prventMaxvalue(this,{{ $student->id }})" data-id="{{@$student->id}}">
                </td>
                @if(@$student_performances)
                    @foreach(@$student_performances as $stud_perform)
                        <td>
                            <div class="percent-input-container">
                                <input type="number" placeholder="enter percentage" class="form-control in_class studenttotalcalculate{{ $student->id }}" name="{{ $stud_perform }}[]"  id="{{$loop->index+1 }}" 
                                onkeyup="StudentPerformance.prventMaxvalue(this,{{ $student->id }})" data-id="{{@$student->id}}">
                            </div>
                        </td>
                    
                    @endforeach
                @endif
                <td class="text-center">
                    <input type="hidden" name="student_performance[]" class="studenttotal{{@$student->id}}" value="0" id="student_performance">
                    <div class="student_performance_chart{{ @$student->id }} text-center"></div>
                </td>
            </tr>
        @endforeach
    
    
    </tbody>
@endif
<style>
    .percent-input-container , .percent-input-container2 {
        position: relative;
    }
    .apexcharts-inner {
    position: relative;
    align-self: center !important;
}
    .percent-input-container input[type="text"] ,  .percent-input-container2 input[type="text"] {
       /* Adjust the padding to make space for the "%" symbol */
    }

    .percent-input-container::after , .percent-input-container2::after {
        content: "%";
        position: absolute;
        top: 50%;
        right: 10px; /* Adjust the positioning of the "%" symbol */
        transform: translateY(-50%);
    }
</style>



<script>
     StudentPerformance.StudentPerformanceChart();
     StudentPerformance.prventMaxvalue();
    
</script>

<script>
    $(document).ready(function() {
          // Select all input fields
          $('.in_class1').each(function() {
              // Get the ID and input value
              var id = $(this).attr('id');
              var value = $(this).attr('data-id');
              
              // Call the function for each input field
              StudentPerformance.prventMaxvalue(document.getElementById(id), value);
          });
        
      });
      
    </script>