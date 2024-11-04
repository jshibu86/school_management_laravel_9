
<div class="card">
    <div class="card-body">
        <div class="attendance_add_items " >
            <div class="att_div bg-white border_r_8 p-3 row">

                <div class="col-lg-12">

                    {{-- <h4>Hourly Attendance</h4> --}}
                        
                    <div class="row align-items-center ip_row border_r_8 py-3 my-4">
                        <div class="col-lg-5">
                            <div class="row align-items-center">
                                <div class="col-lg-2">
                                    <img src="{{ asset("assets/images/clock_icon.png") }}" class="img-fluid">
                                </div>
                                <div class="col-lg-10">
                                    <h5 class="attend_head_text">Add hourly Attendance</h5>
                                    <h6 class="sub_txt font_text">Enter attendance below for the selected class </h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="row align-items-center">
                                <div class="col-lg-2">
                                    <h6 class="font_text">Class :</h6>
                                    <h6 class="font_text"><b>{{ @$class_name }}</b></h6>
                                </div>
                                <div class="col-lg-3">
                                    <h6 class="font_text">Section :</h6>
                                    <h6 class="font_text"><b>{{ @$section_name }}</b></h6>
                                </div>
                                <div class="col-lg-4">
                                    <h6 class="font_text">Academic year :</h6>
                                    <h6 class="font_text"><b>{{ @$acyear }}</b></h6>
                                </div>
                                <div class="col-lg-3">
                                    <h6 class="font_text">Date :</h6>
                                    <h6 class="font_text"><b>{{ @$date }}-({{ @$day }})</b></h6>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <div class="mt-4 blue_bg row">
                <div class="col-lg-12">
                    <div class="row bg-white py-4 align-items-center top_box">
                        <div class="col-lg-6">
                            <h5>Hour Selection</h5>
                            <h6 class="sub_txt">Click on each cell to mark attendance for the day</h6>
                        </div>
                        <div class="col-lg-6">
                            <h6 class="d-flex align-items-center justify-content-end">
                                <i class="fa fa-chevron-circle-left date_arrow"></i>
                                <span>{{ @$daymformat }}</span>
                                <i class="fa fa-chevron-circle-right date_arrow"></i>
                            </h6>
                        </div>
                    </div>

                    <div class="row py-3">
                        <div class="col-lg-12">
                            <div class="bg-white table-responsive pb-4">
                                <table class="w-100 schedule_table">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            @foreach (@$timetablehr as $time)
                                            <th><h6 class="sub_txt mb-0">{{ $time->from }} - {{ $time->to }}</h6></th>
                                            @endforeach
                                           
                                        </tr>
                                    </thead>

                                    <tbody>

                                        <!-- Monday row -->
                                        <tr>
                                            <td align="center"><h6 class="mb-0 px-2">{{ @$day }}</h6></td>

                                            @foreach (@$timetablehr as $timedata)

                                         
                                            @if ($timedata->type !=0)
                                              @php
                                                $type = Configurations::CLASSPERIODCATEGORIES[$timedata->type];
                                              @endphp
                                            <td style="background-color: #FFFCF8;">
												<div class="break_time">
													<div class="schedule_box schedule_box_break text-center">
													
														<h6>{{@$type}}</h6>
														
													
														{{-- <h6 class="sub_txt">{{ $timedata->break_min }}</h6> --}}
													</div>
												</div>
												
											</td>
                                            @else
                                            @if (isset($timedata->Timetableperiod[0]))
                                            @php
                                            $bcolor=$timedata->Timetableperiod[0]->border_color;
                                            $bgcolor=$timedata->Timetableperiod[0]->colorcode;
                                           $name="border-bottom:3px solid  $bcolor;background-color:$bgcolor";
                                            @endphp
                                            <td style="cursor: pointer">
                                                <a href="{{ route("addhourlyattendance",['type'=>"hourly",'acyear'=>$academicyear_id,'class'=>$class_id,'term'=>$term_id,'section'=>$section_id,"date"=>$date,"period"=>$timedata->Timetableperiod[0]->id,"subject"=>$timedata->Timetableperiod[0]->subject_id,"teacher"=>$timedata->Timetableperiod[0]->teacher_id,"hr"=>$timedata->from."-".$timedata->to] ) }}" target="_blank">
                                                    <div class="schedule_box{{ $timedata->Timetableperiod[0]->id }}  schedule_box" style="{{ $name }} " id="{{ $timedata->Timetableperiod[0]->id }}" data-class="{{ 2 }}" data-section="{{ 4 }}" >
                                                    
                                                        <h6 class="schedule_sub{{ $timedata->Timetableperiod[0]->id }}">{{$timedata->Timetableperiod[0]->subject->name }}</h6>
                                                        <h6 class="sub_txt{{ $timedata->id  }}">{{$timedata->Timetableperiod[0]->staff ? $timedata->Timetableperiod[0]->staff->teacher_name : "Not Assign" }}</h6>
                                                        @if ($timedata->Timetableperiod[0]->attendance)
                                                        <span class="badge bg-info text-white">Added</span>
                                                            @else
                                                            <span class="badge bg-danger text-white">Not Added</span>
                                                        @endif
                                                    </div></a>
                                                   
                                                
                                            </td>
                                            @else
                                            <td>
                                                
                                                    <div class="schedule_box schedule_box_grey">
                                                        <h6>Not Assign</h6>
                                                        <h6 class="sub_txt">Not Assign</h6>
                                                    </div>
                                                
                                            </td>
                                            @endif
                                           
                                            @endif

                                            
                                            @endforeach 
                                           
                                           
                                           
                                            
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>