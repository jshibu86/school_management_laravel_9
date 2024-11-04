@extends('layout::admin.master')
@section('title', 'ExamTimetable')
@section('style')
    <!-- Datatables -->
    @include('layout::admin.head.list_head')

    <link rel="stylesheet" href="{{ asset('assets/backend/css/calender.css') }}" />
@endsection
@section('body')
    <div class="x_content">

        {{ Form::open(['role' => 'form', 'route' => ['examtimetable_save'], 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'trasnportreport-form', 'novalidate' => 'novalidate']) }}
        <input type="hidden" name="type1" id="type1" value="{{ @$type1}}" />
        <div class="box-header with-border mar-bottom20">
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', ['type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat', 'value' => 'Edit_examtimetable', 'class' => 'btn btn-success btn-sm m-1  px-3 ']) }}
            <a class="btn btn-info btn-sm m-1  px-3" href="{{ route('examtimetable') }}"><i
                    class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>
        </div>
        <div class="card">
            <div class="card-body">
                {{-- @if (@$type != 'show')
            <h5 class="card-title">Create a new classtimetable</h5>
            <hr/>
            @endif --}}
                <div class="col-xs-12">
                    <div class="row">
                        <div class="blue_bg">

                            @if (@$type != 'show')
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
                                                        <th>
                                                            <h6 class="sub_txt mb-0">{{ $time->start_time }} -
                                                                {{ $time->end_time }}</h6>
                                                        </th>
                                                    @endforeach


                                                </tr>
                                            </thead>

                                            <tbody>

                                                @php
                                                    $temp = 0;
                                                    $count = 0;
                                                @endphp

                                                @foreach (@$days as $key => $day)
                                                    @php
                                                        $week = in_array($day, $getweekend) ? 'weekend' : '';
                                                    @endphp
                                                    <tr>
                                                        <td align="center">
                                                            <h6 class="mb-0 {{ $week }}">
                                                                <p>{{ $key }}</p>
                                                                <p>{{ $day }}</p>
                                                            </h6>
                                                        </td>

                                                        @foreach (@$timing as $timedata)
                                                            @php
                                                                $backgroud = Configurations::getConfig('site')
                                                                    ->break_color
                                                                    ? Configurations::getConfig('site')->break_color
                                                                    : '#FFFCF8';
                                                                $text = Configurations::getConfig('site')->text_color
                                                                    ? Configurations::getConfig('site')->text_color
                                                                    : ' #FFFCF8';
                                                                $border = '#FFFCF8';
                                                            @endphp

                                                            @if ($timedata->period_category !== 'examination')
                                                                @if ($type == 'show')
                                                                    @if ($temp == 0)
                                                                        <td rowspan="{{ sizeof(@$days) }}"
                                                                            style="background-color:{{ $backgroud }};color:{{ $text }}">
                                                                            <div class="break_time">
                                                                                <div class="schedule_box  text-center">

                                                                                    <h6>{{ Configurations::PERIODCATEGORIES[$timedata->period_category] }}
                                                                                    </h6>


                                                                                    {{-- <h6 class="sub_txt">{{ $timedata->break_min }}</h6> --}}
                                                                                </div>
                                                                            </div>

                                                                        </td>
                                                                    @endif
                                                                @else
                                                                    <td>
                                                                        <div class="schedule_box schedule_box_break"
                                                                            style="background-color:{{ $backgroud }};
                                                                    border-bottom:  3px solid {{ $border }};color:{{ $text }}">

                                                                            <h6>{{ Configurations::PERIODCATEGORIES[$timedata->period_category] }}
                                                                            </h6>


                                                                            {{-- <h6 class="sub_txt">{{ $timedata->break_min }}</h6> --}}
                                                                        </div>
                                                                    </td>
                                                                @endif
                                                            @else
                                                                @if (@$type == 'show')
                                                                    @php
                                                                        $getData = Configurations::getCalenderData(
                                                                            $timedata->id,
                                                                            @$data,
                                                                            $key,
                                                                        );
                                                                    @endphp

                                                                    @if ($getData)
                                                                        @php
                                                                            $name = "border-bottom:3px solid $getData->border_color;background-color:$getData->colorcode";
                                                                        @endphp
                                                                        <td style="cursor: pointer">

                                                                            <div class="schedule_box{{ $timedata->id }}{{ str_replace('/', '_', $key) }} schedule_box_{{ @$getData->colorcode }} schedule_box"
                                                                                style="{{ $name }} "
                                                                                id="{{ $timedata->id }}"
                                                                                data-class="{{ 2 }}"
                                                                                data-section="{{ 4 }}">
                                                                                <input type="hidden"
                                                                                    name="sub_id[{{ $key }}][{{ $timedata->id }}][subject]"
                                                                                    id="sub_id{{ @$timedata->id }}{{ str_replace('/', '_', $key) }}" />

                                                                                <input type="hidden"
                                                                                    name="sub_id[{{ $key }}][{{ $timedata->id }}][teacher]"
                                                                                    id="teacher_id{{ @$timedata->id }}{{ str_replace('/', '_', $key) }}" />


                                                                                <input type="hidden"
                                                                                    name="sub_id[{{ $key }}][{{ $timedata->id }}][color]"
                                                                                    id="color_id{{ @$timedata->id }}{{ str_replace('/', '_', $key) }}" />
                                                                                <h6
                                                                                    class="schedule_sub{{ $timedata->id }}{{ str_replace('/', '_', $key) }}">
                                                                                    {{ @$getData->subject->name }}</h6>
                                                                                <h6
                                                                                    class="sub_txt{{ $timedata->id }}{{ str_replace('/', '_', $key) }}">
                                                                                    {{ @$getData->staff->teacher_name ? @$getData->staff->teacher_name : 'Not Assign' }}
                                                                                </h6>
                                                                            </div>
                                                                        </td>
                                                                    @else
                                                                        <td style="cursor: pointer">
                                                                            <div class="schedule_box{{ $timedata->id }}{{ str_replace('/', '_', $key) }} schedule_box"
                                                                                id="{{ $timedata->id }}"
                                                                                data-class="{{ 2 }}"
                                                                                data-section="{{ 4 }}">
                                                                                <input type="hidden"
                                                                                    name="sub_id[{{ $key }}][{{ $timedata->id }}][subject]"
                                                                                    id="sub_id{{ @$timedata->id }}{{ str_replace('/', '_', $key) }}" />

                                                                                <input type="hidden"
                                                                                    name="sub_id[{{ $key }}][{{ $timedata->id }}][teacher]"
                                                                                    id="teacher_id{{ @$timedata->id }}{{ str_replace('/', '_', $key) }}" />


                                                                                <input type="hidden"
                                                                                    name="sub_id[{{ $key }}][{{ $timedata->id }}][color]"
                                                                                    id="color_id{{ @$timedata->id }}{{ str_replace('/', '_', $key) }}" />
                                                                                <h6
                                                                                    class="schedule_sub{{ $timedata->id }}{{ str_replace('/', '_', $key) }}">
                                                                                </h6>
                                                                                <h6
                                                                                    class="sub_txt{{ $timedata->id }}{{ str_replace('/', '_', $key) }}">
                                                                                </h6>
                                                                            </div>
                                                                        </td>
                                                                    @endif
                                                                @elseif(@$type == 'edit')
                                                                    @php
                                                                        $getDataEdit = Configurations::getCalenderData(
                                                                            $timedata->id,
                                                                            @$data,
                                                                            $key,
                                                                        );
                                                                    @endphp

                                                                    @if ($type1 = "update")
                                                                        @php
                                                                        $date = str_replace('/', '_', $key);
                                                                        $table = $data_timetable->where('date', $date)->where('period_id',$timedata->id);
                                                                        $exist = $data_timetable->where('date', $date)->where('period_id',$timedata->id)->isNotEmpty();
                                                                        @endphp
                                                                        @if( $exist)
                                                                            @foreach ($table as $timedata)
                                                                            
                                                                                    @php
                                                                                        $date = str_replace('/', '_', $key);
                                                                                        // Assuming $key is defined somewhere in your code
                                                                                            $subject = ($timedata->subject_names !== null) ? $timedata->subject_names->name : ''; 
                                                                                            $display = ($timedata->subject_names == null) ? "display:none" : "";
                                                                                            $border = ( $timedata->bordercolor !== null) ? "3px solid $timedata->bordercolor " : "";
                                                                                        
                                                                                    @endphp
                                                                                    <td style="cursor: pointer">
                                                                                        
                                                                                        <!-- Adjust IDs and class names as necessary -->
                                                                                        <div class="clearcell" id="clearcell{{ $timedata->period_id }}{{ $date }}" onclick="ExamTimetable.Clearcell('{{ $timedata->period_id }}','{{ $date }}')" style="{{ $display }}" >
                                                                                            <i class="up fa fa-trash text-danger"></i>
                                                                                        </div>
                                                                                        <div class="schedule_box{{ $timedata->period_id }}{{ $date }} schedule_box" id="{{ $timedata->period_id }}" data-class="{{ @$data->class_id }}" data-section="{{ @$data->section_id }}" 
                                                                                            onclick="ExamTimetable.getCalenderPopup({{ $timedata->period_id }},{{ @$data->class_id }},{{ @$data->sec_id }},'{{ $day }}','{{ $date }}')"
                                                                                            style="border-bottom: {{ $border }}; background-color:{{ $timedata->bgcolor }};">
                                                                                            <input type="hidden" name="sub_id[{{ $date }}][{{ $timedata->period_id }}][id]" id="id{{ $timedata->period_id }}{{ $date }}" value="{{ @$timedata->id}}" />
                                                                                            <input type="hidden" name="sub_id[{{ $date }}][{{ $timedata->period_id }}][subject]" id="sub_id{{ $timedata->period_id }}{{ $date }}" value="{{ @$timedata->subject }}" />
                                                                                            <input type="hidden" name="sub_id[{{ $date }}][{{ $timedata->period_id }}][bordercolor]" id="color_id{{ $timedata->period_id }}{{ $date }}" value="{{ @$timedata->bordercolor }}" />
                                                                                            <input type="hidden" name="sub_id[{{ $date }}][{{ $timedata->period_id }}][bgcolor]" id="bgcolor_id{{ $timedata->period_id }}{{ $date }}" value="{{ @$timedata->bgcolor }}" />
                                                                                            <h6 class="schedule_sub{{ $timedata->period_id }}{{ $date }}">{{ @$subject }}</h6>
                                                                                            
                                                                                        </div>
                                                                                    </td>
                                                                            
                                                                            @endforeach
                                                                        @else
                                                                        <td style="cursor: pointer">
                                                                            <div class="clearcell"
                                                                                id="clearcell{{ $timedata->id }}{{ str_replace('/', '_', $key) }}"
                                                                                onclick="ExamTimetable.Clearcell('{{ $timedata->id }}','{{ str_replace('/', '_', $key) }}','add')"
                                                                                style="display:none">
                                                                                <i class="fa fa-trash text-danger"></i>
                                                                            </div>
                                                                            
                                                                            <div class="schedule_box{{ $timedata->id }}{{ str_replace('/', '_', $key) }} schedule_box"
                                                                                id="{{ $timedata->id }}"
                                                                                data-class="{{ 2 }}"
                                                                                data-section="{{ 4 }}"
                                                                                onclick="ExamTimetable.getCalenderPopup({{ $timedata->id }},{{ $class_id }},{{ $section_id }},'{{ $day }}','{{ str_replace('/', '_', $key) }}')">
                                                                                <input type="hidden"
                                                                                    name="sub_id[{{ str_replace('/', '_', $key) }}][{{ $timedata->id }}][subject]"
                                                                                    id="sub_id{{ @$timedata->id }}{{ str_replace('/', '_', $key) }}" />
                                                                                    <input type="hidden"
                                                                                    name="sub_id[{{ str_replace('/', '_', $key) }}][{{ $timedata->id }}][id]"
                                                                                    id="id{{ @$timedata->id }}{{ str_replace('/', '_', $key) }}" value = {{ $timedata->id }} />
                                                                                <input type="hidden"
                                                                                    name="sub_id[{{ str_replace('/', '_', $key) }}][{{ $timedata->id }}][bordercolor]"
                                                                                    id="color_id{{ @$timedata->id }}{{ str_replace('/', '_', $key) }}" />
                                                                                <input type="hidden"
                                                                                    name="sub_id[{{ str_replace('/', '_', $key) }}][{{ $timedata->id }}][bgcolor]"
                                                                                    id="bgcolor_id{{ @$timedata->id }}{{ str_replace('/', '_', $key) }}" />
                                                                                <h6 class="schedule_sub{{ $timedata->id }}{{ str_replace('/', '_', $key) }}"
                                                                                    id ="schedule_sub{{ $timedata->id }}{{ str_replace('/', '_', $key) }}">
                                                                                </h6>
                                                                                <h6
                                                                                    class=" sub_txt{{ $timedata->id }}{{ str_replace('/', '_', $key) }}">
                                                                                </h6>
                                                                            </div>
                                                                        </td>
                                                                        @endif
                                                                      
                                                                            @else
                                                                                @php
                                                                                    $date = str_replace('/', '_', $key);
                                                                                $table = $data_timetable->where('date', $date)->where('period_id',$timedata->id);
                                                                                @endphp
                                                                                @foreach ($table as $timedata)
                                                                                @php
                                                                                    $date = str_replace('/', '_', $key);
                                                                                    // Assuming $key is defined somewhere in your code
                                                                                    $subject = ($timedata->subject_names !== null) ? $timedata->subject_names->name : ''; 
                                                                                    $display = ($timedata->subject_names == null) ? "display:none" : "";
                                                                                    $border = ( $timedata->bordercolor !== null) ? "3px solid $timedata->bordercolor " : "";
                                                                                
                                                                                @endphp
                                                                                <td style="cursor: pointer">
                                                                                
                                                                                    <!-- Adjust IDs and class names as necessary -->
                                                                                    <div class="clearcell" id="clearcell{{ $timedata->period_id }}{{ $date }}" onclick="ExamTimetable.Clearcell('{{ $timedata->period_id }}','{{ $date }}')" style="{{ $display }}" >
                                                                                        <i class="fa fa-trash text-danger"></i>
                                                                                    </div>
                                                                                    <div class="schedule_box{{ $timedata->period_id }}{{ $date }} schedule_box" id="{{ $timedata->period_id }}" data-class="{{ @$data->class_id }}" data-section="{{ @$data->section_id }}" 
                                                                                        onclick="ExamTimetable.getCalenderPopup({{ $timedata->period_id }},{{ @$data->class_id }},{{ @$data->sec_id }},'{{ $day }}','{{ $date }}')"
                                                                                        style="border-bottom: {{ $border }}; background-color:{{ $timedata->bgcolor }};">
                                                                                      
                                                                                     
                                                                                        <input type="hidden" name="sub_id[{{ $date }}][{{ $timedata->period_id }}][id]" id="id{{ $timedata->period_id }}{{ $date }}" value="{{ @$timedata->id}}" />
                                                                                      
                                                                                        <input type="hidden" name="sub_id[{{ $date }}][{{ $timedata->period_id }}][subject]" id="sub_id{{ $timedata->period_id }}{{ $date }}" value="{{ $timedata->subject }}" />
                                                                                        <input type="hidden" name="sub_id[{{ $date }}][{{ $timedata->period_id }}][bordercolor]" id="color_id{{ $timedata->period_id }}{{ $date }}" value="{{ $timedata->bordercolor }}" />
                                                                                        <input type="hidden" name="sub_id[{{ $date }}][{{ $timedata->period_id }}][bgcolor]" id="bgcolor_id{{ $timedata->period_id }}{{ $date }}" value="{{ $timedata->bgcolor }}" />
                                                                                        <h6 class="schedule_sub{{ $timedata->period_id }}{{ $date }}">{{ @$subject }}</h6>
                                                                                    
                                                                                    </div>
                                                                                </td>
                                                                                @endforeach
                                                                        
                                                                            @endif
                                                                
                                                                @else
                                                                    <td style="cursor: pointer">
                                                                        <div class="clearcell"
                                                                            id="clearcell{{ $timedata->id }}{{ str_replace('/', '_', $key) }}"
                                                                            onclick="ExamTimetable.Clearcell('{{ $timedata->id }}','{{ str_replace('/', '_', $key) }}','add')"
                                                                            style="display:none">
                                                                            <i class="fa fa-trash text-danger"></i>
                                                                        </div>
                                                                        
                                                                        <div class="schedule_box{{ $timedata->id }}{{ str_replace('/', '_', $key) }} schedule_box"
                                                                            id="{{ $timedata->id }}"
                                                                            data-class="{{ 2 }}"
                                                                            data-section="{{ 4 }}"
                                                                            onclick="ExamTimetable.getCalenderPopup({{ $timedata->id }},{{ $class_id }},{{ $section_id }},'{{ $day }}','{{ str_replace('/', '_', $key) }}')">
                                                                            <input type="hidden"
                                                                                name="sub_id[{{ str_replace('/', '_', $key) }}][{{ $timedata->id }}][subject]"
                                                                                id="sub_id{{ @$timedata->id }}{{ str_replace('/', '_', $key) }}" />
                                                                             
                                                                            <input type="hidden"
                                                                                name="sub_id[{{ str_replace('/', '_', $key) }}][{{ $timedata->id }}][bordercolor]"
                                                                                id="color_id{{ @$timedata->id }}{{ str_replace('/', '_', $key) }}" />
                                                                            <input type="hidden"
                                                                                name="sub_id[{{ str_replace('/', '_', $key) }}][{{ $timedata->id }}][bgcolor]"
                                                                                id="bgcolor_id{{ @$timedata->id }}{{ str_replace('/', '_', $key) }}" />
                                                                            <h6 class="schedule_sub{{ $timedata->id }}{{ str_replace('/', '_', $key) }}"
                                                                                id ="schedule_sub{{ $timedata->id }}{{ str_replace('/', '_', $key) }}">
                                                                            </h6>
                                                                            <h6
                                                                                class=" sub_txt{{ $timedata->id }}{{ str_replace('/', '_', $key) }}">
                                                                            </h6>
                                                                        </div>
                                                                    </td>
                                                                @endif
                                                            @endif
                                                        @endforeach


                                                    </tr>

                                                    @php
                                                        $temp = $temp + 1;
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
        {{ Form::close() }}
    </div>
    @foreach (@$days as $key => $day)
        @foreach (@$timing as $time_data)
            <div class="modal fade color_modal" id="colorModal{{ $time_data->id }}{{ str_replace('/', '_', $key) }}"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="modal-title" id="exampleModalLabel">
                                <h4 class="mb-4">What period would you like to assign ?</h4>
                                <h6><span
                                        class="assigen_time{{ $time_data->id }}{{ str_replace('/', '_', $key) }}">Friday
                                        ,</span> {{ $time_data->start_time }}- {{ $time_data->end_time }}</h6>
                            </div>
                            <button type="button"
                                class="close closemodel{{ $time_data->id }}{{ str_replace('/', '_', $key) }}"
                                data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body py-4">

                            <form>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="start_time"><b>Subject</b></label>

                                        {{ Form::select('subject_id', @$subjects, @$time_data->subject_id, [
                                            'id' => 'subject_id' . $time_data->id . str_replace('/', '_', $key),
                                            'class' => ' form-control period_select',
                                            'required' => 'required',
                                            'placeholder' => 'Select Subject',
                                        ]) }}

                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <h6><label for="start_time"><b>Assign Color</b></label></h6>

                                        @foreach (Configurations::RCOLORS as $colorcode => $colorname)
                                            @php
                                                $color = 'background-color:' . $colorcode;
                                            @endphp
                                            <span
                                                class="round_colors round_colors{{ $time_data->id }}{{ str_replace('/', '_', $key) }}"
                                                name="{{ $colorname }}" id="{{ $colorcode }}"
                                                style="background-color:{{ $colorcode }}"></span>
                                        @endforeach



                                        <span class="round_colors round_colors{{ $time_data->id }} color_pic"
                                            id="colrpicker{{ $time_data->id }}{{ str_replace('/', '_', $key) }}"></span>

                                        <input type="hidden"
                                            id="colrpick{{ $time_data->id }}{{ str_replace('/', '_', $key) }}" />
                                    </div>
                                </div>

                            </form>

                        </div>
                        <div class="modal-footer pt-4">
                            <button type="button" class="btn close_btn"
                                data-bs-dismiss="modal">&times;&nbsp;&nbsp;Cancel</button>
                            <button type="button"
                                class="btn add_btn_sub assignsubject{{ $time_data->id }}{{ str_replace('/', '_', $key) }}">&plus;&nbsp;&nbsp;Assign
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endforeach
@endsection
@section('scripts')
    <script type="module">
        function notify_script(title, text, type, hide) {
            new PNotify({
                title: title,
                text: text,
                type: type,
                hide: hide,
                styling: 'fontawesome'
            })
        }
        window.termurl = '{{ route('examterm.index') }}';
        window.sectionurl = "{{ route('section.index') }}";
        window.calenderurl = "{{ route('classtimetable.index') }}";
        window.subjectteachers = "{{ route('examtimetable') }}";
        window.depturl = "{{ route('department.index') }}";

        // window.Assignedsubjectteachers="{{ route('subject.index') }}";


        ExamTimetable.Timetableinit(notify_script);
        //ExamTimetable.academicinit(notify_script);
    </script>
    <script>
        $(document).ready(function() {
            function notify_script(title, text, type, hide) {
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
                    notify_script("Error", "Please Type 1 to 7 Number Only");
                    $('#nodays').val("");
                }
            });



        });
    </script>
@endsection
