<div class="col-sm-12">
    <div class="">
        <div class="academic_yearinfo text-center">

            <small>Academic Year : <strong>{{ @$current_academic_year_info->year }}</strong>,</small>
            <small>Start Date : <strong>{{ @$current_academic_year_info->start_date }}</strong>,</small>
            <small>End Date : <strong>{{ @$current_academic_year_info->end_date }}</strong></small>

        </div>
        @foreach (@$calender as $year=> $attendance)
        <div class="attendance_year text-center">{{ @$year }} - Academic Year Attendance</div>
        <div class="table_scroll">
            <table class="attendance_table table-responsive">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>7</th><th>8</th><th>9</th><th>10</th><th>11</th><th>12</th><th>13</th><th>14</th><th>15</th><th>16</th><th>17</th><th>18</th><th>19</th><th>20</th><th>21</th><th>22</th><th>23</th><th>24</th><th>25</th><th>26</th><th>27</th><th>28</th><th>29</th><th>30</th><th>31</th>                                                    
                    </tr>
                </thead>
                <tbody>
    
                   @foreach ($attendance as$month =>$dates )
                   <tr><td>{{ @$month }}</td>
                    
                    @foreach ($dates as $singledate )
                    @if ($singledate['weekend'] == 0)
                        @if (isset($singledate['attendance']) && sizeof($singledate['attendance']))
                            @if ($singledate['attendance'][@$data->id]['present'] == 0)
                            <td class="absent">A</td>
                            @elseif ($singledate['attendance'][@$data->id]['present'] == 1)
                            <td class="present">P</td>
                            @else
                            <td class="late">L</td>
                            @endif
                    
                        @else
                        <td class="ini-bg-secondary">N/A</td>  
                        @endif

                    @else

                    @if (isset($singledate['attendance']) && sizeof($singledate['attendance']))
                    @if ($singledate['attendance'][@$data->id]['present'] == 0)
                      <td class="absent">W/A</td>
                    @elseif ($singledate['attendance'][@$data->id]['present'] == 1)
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
            
        @endforeach
       
    </div>
</div>