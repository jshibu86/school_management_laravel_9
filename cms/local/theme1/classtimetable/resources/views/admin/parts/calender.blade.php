@extends('layout::admin.master')
@section('title', 'ExamTimetable')
@section('style')
    <!-- Datatables -->
    @include('layout::admin.head.list_head')

    <link rel="stylesheet" href="{{ asset('assets/backend/css/calender.css') }}" />
@endsection
@section('body')
<div class="box-header with-border mar-bottom20">
    @if(@$type =="edit")
    {{ Form::open(array('role' => 'form', 'route'=>array('timetable_update',@$period_id), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
    @else
      {{ Form::open(array('role' => 'form', 'route'=>array('classtimetable.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
    @endif
    @if(@$type !== "show")
    {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', ['type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat', 'value' => 'Edit_examtimetable', 'class' => 'btn btn-success btn-sm m-1  px-3 ']) }}
    @endif
    @if(@$type == "show")
     <a class="btn btn-info btn-sm m-1  px-3" href="{{ route('classtimetable.index') }}"><i
        class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>
    @endif

            <input type="hidden" name="period_id" value="{{@$period_id}}">
            <input type="hidden" name="section" value="{{@$section}}">
            <input type="hidden" name="no_of_days" value="{{@$no_of_days}}">
</div>
    <div class="card">
        <div class="card-body">
            {{-- @if (@$type !="show")
            <h5 class="card-title">Create a new classtimetable</h5>
            <hr/>
            @endif --}}
            <div class="col-xs-12">
                <div class="row">
                    <div class="blue_bg">

                        @if (@$type !="show")
                            <div class="row bg-white py-4 align-items-center top_box">
                                <div class="col-md-6">
                                    <h5>Schedule classes</h5>
                                    <h6 class="sub_txt">Click on each cell to assign classes.</h6>
                                </div>
                                
                            </div>   
                        @endif

                    
                
                        <div class="row py-3">
                            <div class="col-md-12">
                                <div class="bg-white table-responsive">
                                    <table class="w-100 schedule_table ">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                @foreach (@$timing as $time)
                                                <th><h6 class="sub_txt mb-0">{{ date('h:i a', strtotime($time->from)) }} - {{ date('h:i a', strtotime($time->to)) }}</h6>
                                                <input type="hidden" name="time_from[]" value="{{date('h:i a', strtotime($time->from))}}">
                                                <input type="hidden" name="time_to[]" value="{{date('h:i a', strtotime($time->from))}}">
                                            </th>
                                                @endforeach
                                                
                                                
                                            </tr>
                                        </thead>
                
                                        <tbody>

                                            @php
                                                $temp=0;
                                                $count=0;
                                            @endphp

                                            @foreach ($days as $key=> $day)
                                                <tr>
                                                    <td align="center"><h6 class="mb-0 {{ in_array($key,@$getweekend) ? "weekend" : "" }}">{{ $day }}</h6></td>

                                                    @foreach ($timing as $timedata)
                                                        @php
                                                            $backgroud=Configurations::getConfig("site")->break_color ? Configurations::getConfig("site")->break_color: "#FFFCF8";
                                                            $text=Configurations::getConfig("site")->text_color ? Configurations::getConfig("site")->text_color : " #FFFCF8";
                                                            $border="#FFFCF8";
                                                        @endphp
                                               
                                                        @if ($timedata->type !=0)
                                                            @if ($type =="show")
                                                        
                                                                @if ($temp == 0)
                                                                
                                                                    <td rowspan="{{ sizeof(@$days) }}" style="background-color:{{ $backgroud }};color:{{ $text }}">
                                                                        <div class="break_time">
                                                                            <div class="schedule_box  text-center">
                                                                            
                                                                                <h6>{{ Configurations::BREAK[$timedata->type] }}</h6>
                                                                                
                                                                            
                                                                                {{-- <h6 class="sub_txt">{{ $timedata->break_min }}</h6> --}}
                                                                            </div>
                                                                        </div>
                                                                        
                                                                    </td>
                                                                @endif
                                                            @else
                                                                <td>
                                                                    <div class="schedule_box schedule_box_break" style="background-color:{{ $backgroud }};
                                                                    border-bottom:  3px solid {{ $border }};color:{{ $text }}">
                                                                        
                                                                        <h6>{{ Configurations::BREAK[$timedata->type] }}</h6>
                                                                        
                                                                    
                                                                        {{-- <h6 class="sub_txt">{{ $timedata->break_min }}</h6> --}}
                                                                    </div>
                                                                </td>
                                                            @endif
                                                        
                                                        
                                                        
                                                        
                                                        @else

                                                            @if (@$type=="show")
                                                                @php
                                                                    $getData=Configurations::getCalenderData($timedata->id,@$data,$key);
                                                                @endphp
                                                                
                                                                @if ($getData)

                                                                    @php
                                                                        $name="border-bottom:3px solid $getData->border_color;background-color:$getData->colorcode";
                                                                    @endphp
                                                                    <td style="cursor: pointer">
                                                                        
                                                                        <div class="schedule_box{{ $timedata->id }}{{ $key }} schedule_box_{{ @$getData->colorcode }} schedule_box" style="{{ $name }} " id="{{ $timedata->id }}" data-class="{{ 2 }}" data-section="{{ 4 }}" >
                                                                            <input type="hidden" name="sub_id[{{$key}}][{{$timedata->id}}][subject]" id="sub_id{{ @$timedata->id }}{{ $key }}"  />

                                                                            <input type="hidden" name="sub_id[{{$key}}][{{$timedata->id}}][teacher]" id="teacher_id{{ @$timedata->id }}{{ $key }}" />


                                                                            <input type="hidden" name="sub_id[{{$key}}][{{$timedata->id}}][color]" id="color_id{{ @$timedata->id }}{{ $key }}" />
                                                                            <h6 class="schedule_sub{{ $timedata->id }}{{ $key }}">{{ @$getData->subject->name }}</h6>
                                                                            <h6 class="sub_txt{{ $timedata->id  }}{{ $key }}">{{@$getData->staff->teacher_name ? @$getData->staff->teacher_name : "Not Assign" }}</h6>
                                                                        </div>
                                                                    </td>
                                                                @else
                                                                    <td style="cursor: pointer">
                                                                        <div class="schedule_box{{ $timedata->id }}{{ $key }} schedule_box" id="{{ $timedata->id }}" data-class="{{ 2 }}" data-section="{{ 4 }}">
                                                                            <input type="hidden" name="sub_id[{{$key}}][{{$timedata->id}}][subject]" id="sub_id{{ @$timedata->id }}{{ $key }}" />

                                                                            <input type="hidden" name="sub_id[{{$key}}][{{$timedata->id}}][teacher]" id="teacher_id{{ @$timedata->id }}{{ $key }}" />


                                                                            <input type="hidden" name="sub_id[{{$key}}][{{$timedata->id}}][color]" id="color_id{{ @$timedata->id }}{{ $key }}" />
                                                                            <h6 class="schedule_sub{{ $timedata->id }}{{ $key }}"></h6>
                                                                            <h6 class="sub_txt{{ $timedata->id  }}{{ $key }}"></h6>
                                                                        </div>
                                                                    </td>

                                                                    
                                                                @endif
                                                            
                                                            @elseif(@$type == "edit")

                                                                @php
                                                                    $getDataEdit=Configurations::getCalenderData($timedata->id,@$data,$key);
                                                                @endphp

                                                                @if ($getDataEdit)

                                                                    @php
                                                                        $nameEdit="border-bottom:3px solid $getDataEdit->border_color;background-color:$getDataEdit->colorcode";
                                                                    @endphp
                                                                    <td style="cursor: pointer" class="editsedule_box_td">
                                                                        <div class="clearcell" id="clearcell{{ $timedata->id }}{{ $key }}" onclick="AcademicConfig.Clearcell('{{ $timedata->id }}','{{ $key }}')" style="display: none">
                                                                            <i class="fa fa-trash text-danger" ></i>
                                                                        </div>
                                                                        <div class="editsedule_box" data-key="{{ $key }}" id="{{ $timedata->id }}">
                                                                        
                                                                            <div  style="{{ $nameEdit }}" class="schedule_box{{ $timedata->id }}{{ $key }} schedule_box " data-key="{{ $key }}" id="{{ $timedata->id }}" data-class="{{ @$data->class_id }}" data-section="{{ @$data->section_id }}" onclick="AcademicConfig.getCalenderPopup({{ $timedata->id }},{{ @$data->class_id }},{{ @$data->section_id }},'{{ $day }}','{{ $key }}','{{ @$getDataEdit->subject_id }}','{{ @$getDataEdit->teacher_id }}')">
                                                                                
                                                                                <input type="hidden" name="sub_id[{{$key}}][{{$timedata->id}}][subject]" id="sub_id{{ @$timedata->id }}{{ $key }}" value="{{ @$getDataEdit->subject_id }}" />

                                                                                <input type="hidden" name="sub_id[{{$key}}][{{$timedata->id}}][teacher]" id="teacher_id{{ @$timedata->id }}{{ $key }}" value="{{ @$getDataEdit->teacher_id }}" />


                                                                                <input type="hidden" name="sub_id[{{$key}}][{{$timedata->id}}][bordercolor]" id="color_id{{ @$timedata->id }}{{ $key }}" value="{{ @$getDataEdit->border_color }}" />
                                                                                <input type="hidden" name="sub_id[{{$key}}][{{$timedata->id}}][bgcolor]" id="bgcolor_id{{ @$timedata->id }}{{ $key }}" value="{{ @$getDataEdit->colorcode }}" />
                                                                                <input type="hidden" name="sub_id[{{$key}}][{{$timedata->id}}][timtableid]" id="timetable_id{{ @$timedata->id }}{{ $key }}" value="{{ @$getDataEdit->id }}" />
                                                                                <h6 class="schedule_sub{{ $timedata->id }}{{ $key }}">{{@$getDataEdit->subject->name }}</h6>
                                                                                <h6 class="sub_txt{{ $timedata->id  }}{{ $key }}">{{@$getDataEdit->staff->teacher_name ? @$getDataEdit->staff->teacher_name : "Not Assign" }} </h6>
                                                                            </div>
                                                                    </div>
                                                                    </td>

                                                                @else
                                                                    <td style="cursor: pointer">

                                                                        <div class="clearcell" id="clearcell{{ $timedata->id }}{{ $key }}" onclick="AcademicConfig.Clearcell('{{ $timedata->id }}','{{ $key }}')" style="display:none">
                                                                            <i class="fa fa-trash text-danger" ></i>
                                                                        </div>
                                                                        <div  class="schedule_box{{ $timedata->id }}{{ $key }} schedule_box" id="{{ $timedata->id }}" data-class="{{ @$data->class_id }}" data-section="{{ @$data->section_id }}" onclick="AcademicConfig.getCalenderPopup({{ $timedata->id }},{{ @$data->class_id }},{{ @$data->section_id }},'{{ $day }}','{{ $key }}')">
                                                                            <input type="hidden" name="sub_id[{{$key}}][{{$timedata->id}}][subject]" id="sub_id{{ @$timedata->id }}{{ $key }}" />

                                                                            <input type="hidden" name="sub_id[{{$key}}][{{$timedata->id}}][teacher]" id="teacher_id{{ @$timedata->id }}{{ $key }}" />


                                                                            <input type="hidden" name="sub_id[{{$key}}][{{$timedata->id}}][bordercolor]" id="color_id{{ @$timedata->id }}{{ $key }}" />
                                                                            <input type="hidden" name="sub_id[{{$key}}][{{$timedata->id}}][bgcolor]" id="bgcolor_id{{ @$timedata->id }}{{ $key }}" />
                                                                            <h6 class="schedule_sub{{ $timedata->id }}{{ $key }}"></h6>
                                                                            <h6 class="sub_txt{{ $timedata->id  }}{{ $key }}"></h6>
                                                                        </div>
                                                                    </td>

                                                                    
                                                                @endif
                                                        

                                                            @else
                                                            
                                                                <td style="cursor: pointer">
                                                                    <div class="clearcell" id="clearcell{{ $timedata->id }}{{ $key }}" onclick="AcademicConfig.Clearcell('{{ $timedata->id }}','{{ $key }}','add')" style="display:none">
                                                                        <i class="fa fa-trash text-danger" ></i>
                                                                    </div>
                                                                    @php
                                                                   
                                                                    @endphp
                                                                    <div  class="schedule_box{{ $timedata->id }}{{ $key }} schedule_box" id="{{ $timedata->id }}" data-class="{{ 2 }}" data-section="{{ 4 }}" onclick="AcademicConfig.getCalenderPopup({{ $timedata->id }},{{ $class_id }},{{ $section }},'{{ $day }}','{{ $key }}')">
                                                                       
                                                                        <input type="hidden" name="sub_id[{{$key}}][{{$timedata->id}}][subject]" id="sub_id{{ @$timedata->id }}{{ $key }}" />

                                                                        <input type="hidden" name="sub_id[{{$key}}][{{$timedata->id}}][teacher]" id="teacher_id{{ @$timedata->id }}{{ $key }}" />


                                                                        <input type="hidden" name="sub_id[{{$key}}][{{$timedata->id}}][bordercolor]" id="color_id{{ @$timedata->id }}{{ $key }}" />
                                                                        <input type="hidden" name="sub_id[{{$key}}][{{$timedata->id}}][bgcolor]" id="bgcolor_id{{ @$timedata->id }}{{ $key }}" />
                                                                        <h6 class="schedule_sub{{ $timedata->id }}{{ $key }}"></h6>
                                                                        <h6 class="sub_txt{{ $timedata->id  }}{{ $key }}"></h6>
                                                                    </div>

                                                                </td>
                                                                
                                                            @endif
                                                        
                                                            
                                                        @endif

                                                    
                                                
                                                    @endforeach
                                                    
                                                    
                                                </tr>

                                                @php
                                                $temp=$temp+1;
                                                @endphp
                                            @endforeach
                
                                            <!-- Monday row -->
                                            
                
                                        
                
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
    @foreach (@$days as $key=>$day)
        @foreach (@$timing as $time_data )
            <div class="modal fade color_modal" id="colorModal{{ $time_data->id }}{{ $key }}"  aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="modal-title" id="exampleModalLabel">
                                <h4 class="mb-4">What period would you like to assign ?</h4>
                                <h6><span class="assigen_time{{ $time_data->id }}">Friday ,</span> {{ $time_data->from }}- {{ $time_data->to }}</h6>
                            </div>
                            <button type="button" class="close closemodel{{ $time_data->id }}{{ $key }}" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body py-4">

                            <form>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="start_time"><b>Subject</b></label>

                                        {{ Form::select('subject_id',@$subjects,@$time_data->subject_id ,
                                        array('id'=>'subject_id'.$time_data->id.$key,'class' => ' form-control period_select','required' => 'required','placeholder'=>"Select Subject" )) }}
                                        
                                    </div>
                                    <div class="col-md-6">
                                        <label for="end_time"><b>Staff</b></label>

                                        {{ Form::select('staff_id',[],@$data->class_id ,
                                        array('id'=>'staff_id'.$time_data->id.$key,'class' => ' form-control period_select ','required' => 'required','placeholder'=>"Select Staff" )) }}
                                    
                                        
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <h6><label for="start_time"><b>Assign Color</b></label></h6>

                                        @foreach (Configurations::RCOLORS as $colorcode => $colorname)

                                        @php
                                            $color="background-color:".$colorcode;
                                        @endphp
                                        <span class="round_colors round_colors{{ $time_data->id }}{{ $key }}" name="{{ $colorname }}"  id="{{ $colorcode }}" style="background-color:{{$colorcode}}"></span>
                                        @endforeach
                                        
                                        
                                        
                                        <span class="round_colors round_colors{{ $time_data->id }} color_pic" id="colrpicker{{ $time_data->id }}{{ $key }}" ></span>

                                        <input type="hidden" id="colrpick{{ $time_data->id }}{{ $key }}" />
                                    </div>
                                </div>

                            </form>

                        </div>
                        <div class="modal-footer pt-4">
                            <button type="button" class="btn close_btn" data-bs-dismiss="modal">&times;&nbsp;&nbsp;Cancel</button>
                            <button type="button" class="btn add_btn assignsubject{{ $time_data->id }}{{ $key }}">&plus;&nbsp;&nbsp;Assign </button>
                        </div>
                    </div>
                </div>
            </div> 
        @endforeach
    @endforeach
    {{ Form::close() }}
@endsection

@section('scripts')
    <script type="module">
        function notify_script(title,text,type,hide) {
                new PNotify({
                    title: title,
                    text: text,
                    type: type,
                    hide: hide,
                    styling: 'fontawesome'
                })
            }
        window.termurl='{{route('examterm.index')}}';
        window.calenderurl="{{ route('classtimetable.index') }}";
        window.subjectteachers="{{ route('subject.index') }}";
        window.depturl="{{ route('department.index') }}";

        window.sectionurl = '{{ route('section.index') }}';
        window.classurl = '{{ route('schooltype.index') }}';
        window.getstudentperformanceinfo = "{{ route('studentperformance') }}";
        window.append_new_period = '{{ route('examtimetable') }}';
        window.append_new_periods = '{{ route('classtimetable') }}';
        window.deleteperiod = "{{ route('examtimetable_period_delete') }}"
        // window.Assignedsubjectteachers="{{ route('subject.index') }}";
        // ExamTimetable.ExamTimetableInit(notify_script);
        ClassTimetable.ClassTimetableInit(notify_script);

        AcademicConfig.Timetableinit(notify_script);
        //AcademicConfig.academicinit(notify_script);

    </script>
    <script>
        $(document).ready(function() {
            function notify_script(title,text,type,hide) {
                new PNotify({
                    title: title,
                    text: text,
                    type: type,
                    hide: hide,
                    styling: 'fontawesome'
                })
            }
                $("input[name^=no_days]").on('input', function(event) {
                    this.value = this.value.replace(/[^1-9]/g, '');
                });

                $("#nodays").keyup(function() {
                    if ($('#nodays').val() > 7) {
                        notify_script("Error","Please Type 1 to 7 Number Only");
                        $('#nodays').val("");
                    } 
                });

                

         });

     


    </script>
@endsection

@section('script_link')

    <!-- validator -->
    {!! Cms::script("theme/vendors/validator/validator.js") !!}
    {!! Cms::script("theme/vendors/validator/validator_form.js") !!}

@endsection


