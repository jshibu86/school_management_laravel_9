            
<tbody>   
           @if($students)
                @php $index = 1; @endphp <!-- Initialize index before the loop -->
                @foreach($students as $student)
                    <tr>
                        <td>{{ $index++ }}</td> <!-- Increment index for each row -->
                        <td><img src="{{ asset($student->image ? $student->image : 'assets/images/default.jpg') }}" class="img-fluid stu_profile" width="50px"/></td>
                        <td>{{ $student->reg_no }}</td>
                        <td>{{ $student->first_name }}</td>
                        @php
                            $mark_report = DB::table('mark_report')
                                ->where(['student_id' => $student->id, 'academic_year' => $academic_year, 'term_id' => $term])
                                ->select('average','is_promotion')
                                ->first();
                        
                            if ($mark_report) {
                                $avg = $mark_report->average; // Access the 'average' attribute of the object
                                $promotion = ($mark_report->is_promotion == 1) ? "Promoted" : "Repeated" ;
                              
                               
                            } else {
                                $avg = "NA";
                                $promotion = "NA";
                            }
                        
                            // Initialize variables outside of the loop
                            if ($avg !== "NA") {
                                $report = DB::table('mark_report')
                                ->where(['academic_year' => $academic_year, 'term_id' => $term])
                                ->get();
                                $rank =
                                     $report
                                        ->where("average", ">", $avg)
                                        ->count() + 1;
                                // dd($rank);
                            } else {
                                $rank = null;
                            }
                              $position = $rank;
                           
                          
                        @endphp
                    
                    
                        <td>{{ $avg }}</td>
                        <td>@if($position !== null)
                            {{ Configurations::ordinal($position) }}
                            @else
                           
                             @endif
                        </td>
                        @php
                        if($promotion !== "NA"){
                             $color = ($promotion == "Promoted") ? "green" : "red";
                             $style = "color:".$color;
                        }
                        else{
                            $style = " ";
                        }
                        @endphp
                        <td style="{{ @$style }}">{{ $promotion }}</td>
                        <td>
                            <button class="editbutton btn btn-default viewroute" id="{{ $student->id }}" 
                                onclick="ReportConfig.getStudentsMarkinfo({{ $student->id }}, {{ $academic_year }}, {{ @$position }}, {{ $term }})"  title="view Member card">
                                <i class="fa fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            @endif
     </tbody>
       
     <style>
        .paginate_button{
           padding: unset !important; 
            margin-left: unset !important;
       } 
     </style> 

<script>
 
   window.student_overall_report_info = "{{ route('grade_student_report_view') }}"
   ReportConfig.getStudentsMarkinfo(id,academic_year,position,term);
</script>