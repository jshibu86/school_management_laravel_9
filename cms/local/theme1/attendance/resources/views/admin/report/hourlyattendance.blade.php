<div class="col-sm-12">
    <div class="">
        <div class="academic_yearinfo text-center">

            <small>Selected Month : <strong>{{ @$month }}</strong>,</small>
            <small>Start Date : <strong>{{ @$start_date }}</strong>,</small>
            <small>End Date : <strong>{{ @$end_date }}</strong></small>
            <br/>
            <small>P - Present</small>,<small>A - Absent</small>,<small>NT - Not Taken Attendance</small>

        </div>

        <div class="hrattendance">
            @foreach (@$calender as $month => $dates)

            @foreach ($dates as $key =>$date)
            <div class="attendance_year text-center">{{ @$date['date'] }} - {{ @$date['dayname'] }}</div>
            @if (sizeof($date['period']))
                
          
            <div class=" table_scroll">
            <table class="attendance_table table-responsive">
                <thead>
                    <tr>
                        <th>#</th>
                        @foreach (@$date['period'] as $period)
                          <th>{{ @$period->from }}-{{ @$period->to }}</th>  
                        @endforeach
                                                                          
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#</td>
                        @foreach (@$date['period'] as $period_data)
    
                        @if ($period_data->type == 1)
    
                        <td>Break</td>
    
                        @else
                        @if (isset($period_data->Timetableperiod[0]))
                        <td>
    
                            @if ($period_data->Timetableperiod[0]->subject)
                            <span>{{$period_data->Timetableperiod[0]->subject ? $period_data->Timetableperiod[0]->subject->name : "" }}</span> 
                            <br/>
                            <span>{{$period_data->Timetableperiod[0]->staff ? $period_data->Timetableperiod[0]->staff->teacher_name : "No Assign" }}</span>
                            <br/>  
    
                            @if ($period_data->Timetableperiod[0]->attendance)
    
                            @if (isset($period_data->Timetableperiod[0]->attendance->attendancestudents[0]) )
                            @php
                                $attn=$period_data->Timetableperiod[0]->attendance->attendancestudents[0]->attendance;
                            @endphp
                            @if ($attn == 0)
                            <span class="badge bg-danger text-white">A</span>
                            @elseif ($attn == 1)
                            <span class="badge bg-success text-white">P</span>
                            @else
                            <span class="badge bg-info text-white">L</span>
                            @endif
                                
                            @endif
                            
                            @else
    
                            <span  class="badge bg-dark text-white">NT</span>
                               
                            @endif
                            @endif
                            
                           
                        </td>
    
                        @else
                        <td>Not Assign</td>
                        @endif
                            
                        @endif
                        
                        @endforeach
                    </tr>
    
                    
    
                   
                    
                </tbody>
                                        
            </table> 
        </div>
    
            @else
            <p>No Records</p>
              @endif    
            @endforeach
            
            @endforeach
          
           
        </div>

       
       
       
    </div>
</div>