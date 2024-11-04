<div class="col-sm-12">
    <div class="">
        <div class="academic_yearinfo text-center">

            <small>Academic Year : <strong>{{ @$current_academic_year_info->year }}</strong>,</small>
            <small>Start Date : <strong>{{ @$current_academic_year_info->start_date }}</strong>,</small>
            <small>End Date : <strong>{{ @$current_academic_year_info->end_date }}</strong></small>

        </div>
       
        {{-- <div class="attendance_year text-center">{{ @$year }} - Academic Year Attendance</div> --}}
        <div class="table_scroll">
            <table class="attendance_table table-responsive">
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>7</th><th>8</th><th>9</th><th>10</th><th>11</th><th>12</th><th>13</th><th>14</th><th>15</th><th>16</th><th>17</th><th>18</th><th>19</th><th>20</th><th>21</th><th>22</th><th>23</th><th>24</th><th>25</th><th>26</th><th>27</th><th>28</th><th>29</th><th>30</th><th>31</th>                                                    
                    </tr>
                </thead>
                <tbody>

                           @php
                    $totalpresent = 0;
                    $totalabsent = 0;   
                    $totaldays = 0;   

                    $totalPresentPercentage = 0;
                    $totalAbsentPercentage = 0;

                                     
                   @endphp

                    @foreach (@$calender as $student_id=> $attendance)

                     <tr><td>{{Configurations::GetStudent($student_id)}}</td>
                   @foreach ($attendance as$month =>$dates )

                   @if ($dates['is_weekend'] == 0)
                   @php
                   $totaldays++;                    
                  @endphp
                    @if (isset($dates['attendance']) && sizeof($dates['attendance']))
                    @if ($dates['attendance'][@$student_id]['present'] == 0)
                    @php
                    $totalabsent++;                    
                   @endphp

                    <td class="absent">A</td>
                    @elseif ($dates['attendance'][@$student_id]['present'] == 1)
                    @php
                    $totalpresent++;                    
                   @endphp

                    <td class="present">P</td>
                    @else
                    <td class="late">L</td>
                    @endif
                   
                    @else
                       <td class="ini-bg-secondary">N/A</td>  
                    @endif

                    @else

                    @if (isset($dates['attendance']) && sizeof($dates['attendance']))
                    @if ($dates['attendance'][@$student_id]['present'] == 0)
                    @php
                    $totalabsent++;                    
                   @endphp
                    <td class="absent">W/A</td>
                    @elseif ($dates['attendance'][@$student_id]['present'] == 1)
                    @php
                    $totalpresent++;                    
                   @endphp
                    <td class="present">W/P</td>
                    @else
                    <td class="late">W/L</td>
                    @endif
                    @else
                    <td class="weekend">W</td>
                    @endif
                    @endif                          

                   @endforeach
                    </tr>
                      @endforeach

                      

                </tbody>
                                        
            </table>
        </div>   
        
       

       
    </div>
</div>

      
       
    </div>
</div>