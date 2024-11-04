<link rel="stylesheet" href="{{ asset('assets/backend/css/attendance.css') }}">
<style>
    @media print {
        @page {
            size: landscape;
        }

        .back-button {
            display: none !important;
        }
    }

    .attendance_table {
        width: 100%;
        margin-bottom: 25px;
        margin-top: 25px;
    }

    .attendance_table tr,
    th {
        border: 1px solid #c3c3c3 !important;
    }

    .attendance_table th {
        padding: 10px;
        font-size: 11px;
    }

    .attendance_table tr td {
        text-align: center;
        border: 0.1px solid #ddd;
        padding: 4px 0px;

    }

    .academic_yearinfo {
        box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
        padding: 20px;
        margin-bottom: 20px;
        background-color: #2a3f54;
        color: white;
        text-align: center;
    }

    .attendance_year {

        padding: 10px;
        margin-bottom: 14px;
        background-color: #2a3f54;
        color: white;
    }

    .ini-bg-secondary {
        background-color: rgb(61 109 157);
        color: #c3c3c3
    }

    .absent {
        background-color: red;
        color: white;

    }

    .present {
        background-color: #73b70b;
        color: white
    }

    .weekend {
        background-color: #2a3f54;
        color: white
    }

    .hrattendance {
        max-height: 700px;
        overflow-y: scroll;
    }

    .table_scroll {
        overflow-x: scroll;
    }
</style>


<div class="col-sm-12">
    <div class="">
        <div class="academic_yearinfo text-center">

            <small>Academic Year : <strong>{{ @$current_academic_year_info->year }}</strong>,</small>
            <small>Start Date : <strong>{{ @$current_academic_year_info->start_date }}</strong>,</small>
            <small>End Date : <strong>{{ @$current_academic_year_info->end_date }}</strong></small>

        </div>
        <div>
            {{ Form::button('<i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back', ['class' => 'btn btn-info btn-sm m-1 px-3 back-button', 'onclick' => 'window.location.href="' . route('attendancereport') . '"']) }}
            {{ Form::button('<i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Print', ['class' => 'btn btn-info btn-sm m-1 px-3 back-button', 'id' => 'printButton']) }}
     
        </div>
        <div >
            
        </div>



        {{-- <div class="attendance_year text-center">{{ @$year }} - Academic Year Attendance</div> --}}
        <div class="table_scroll">
            <table class="attendance_table table-responsive">
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>1</th>
                        <th>2</th>
                        <th>3</th>
                        <th>4</th>
                        <th>5</th>
                        <th>6</th>
                        <th>7</th>
                        <th>8</th>
                        <th>9</th>
                        <th>10</th>
                        <th>11</th>
                        <th>12</th>
                        <th>13</th>
                        <th>14</th>
                        <th>15</th>
                        <th>16</th>
                        <th>17</th>
                        <th>18</th>
                        <th>19</th>
                        <th>20</th>
                        <th>21</th>
                        <th>22</th>
                        <th>23</th>
                        <th>24</th>
                        <th>25</th>
                        <th>26</th>
                        <th>27</th>
                        <th>28</th>
                        <th>29</th>
                        <th>30</th>
                        <th>31</th>
                    </tr>
                </thead>
                <tbody>



                    @foreach (@$calender as $student_id => $attendance)
                        <tr>
                            <td>{{ Configurations::GetStudent($student_id) }}</td>
                            @foreach ($attendance as $month => $dates)
                                @if ($dates['is_weekend'] == 0)
                                    @if (isset($dates['attendance']) && sizeof($dates['attendance']))
                                        @if ($dates['attendance'][@$student_id]['present'] == 0)
                                            <td class="absent">A</td>
                                        @elseif ($dates['attendance'][@$student_id]['present'] == 1)
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
                                            <td class="absent">W/A</td>
                                        @elseif ($dates['attendance'][@$student_id]['present'] == 1)
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

<script>
    window.onload = function () {
    window.print();
}
</script>

<script>
    document.getElementById('printButton').addEventListener('click', function() {
        openPrintPreview();
    });

    function openPrintPreview() {
          window.print();
    }
</script>

