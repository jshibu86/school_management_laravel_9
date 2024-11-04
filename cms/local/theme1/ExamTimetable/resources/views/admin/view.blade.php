@extends('layout::admin.master')

@section('title','mark')
@section('style')
    @include('layout::admin.head.list_head')

    <link rel="stylesheet" href="{{asset('assets/backend/css/calender.css')}}"/>
@endsection

@section('body')
    <div class="x_content">
        <div class="box-header with-border mar-bottom20">
            <a class="btn btn-info btn-sm m-1  px-3" href="{{route('ExamTimetable.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>
        </div>
        <div class="card">   
            <div class="card-body">
                <h1 class="accordion-header" id="headingOne">               
                    Exam Time Table       
                </h1>
                <div class="row">
                    <div class="exam_information w-75 mx-auto text-center">          
                        <p>Academic Year : <span class="fw-bold">{{ str_replace("-", "/", @$academic_year) }}</span></p>
                        <p>Term : <span class="fw-bold">{{ @$term }}</span></p>
                        <p>School Name : <span class="fw-bold">{{ @$school_type }}</span></p>
                        <p>Class&Section : <span class="fw-bold">{{ @$class }}.{{ @$section }}</span></p>                                      
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-xs-12">
                    <div class="row">
                        <div class="blue_bg">
                            <div class="row py-3">
                                <div class="col-md-12">
                                    <div class="bg-white table-responsive">
                                        <table class="w-100 schedule_table ">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    @foreach (@$exam_period_mapping as $time)
                                                    <th><h6 class="sub_txt mb-0">{{ date('h:i a', strtotime($time->start_time)) }} - {{ date('h:i a', strtotime($time->end_time)) }}</h6></th>
                                                    @endforeach
                                                    
                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                             
                                                @foreach (@$days as $key=> $day)
                                                    @php
                                                        $week = in_array($day, $weekend) ? "weekend" : "";
                                                    @endphp   
                                                    <tr>
                                                        <td align="center">
                                                            <h6 class="mb-0 {{ $week }}">
                                                                <p>{{ $key }}</p>
                                                                <p>{{ $day }}</p>
                                                        </h6>
                                                        </td>

                                                        @foreach (@$exam_period_mapping as $timedata)
                                                            @php
                                                                $backgroud=Configurations::getConfig("site")->break_color ? Configurations::getConfig("site")->break_color: "#FFFCF8";
                                                                $text=Configurations::getConfig("site")->text_color ? Configurations::getConfig("site")->text_color : " #FFFCF8";
                                                                $border="#FFFCF8";
                                                            @endphp
                                                        
                                                            @if ($timedata->period_category !=="examination")
                                                                <td>
                                                                    <div class="schedule_box schedule_box_break" style="background-color:{{ $backgroud }};
                                                                    border-bottom:  3px solid {{ $border }};color:{{ $text }}">
                                                                
                                                                        <h6>{{ Configurations::PERIODCATEGORIES[$timedata->period_category] }}</h6>
                                                                        
                                                                    
                                                                        {{-- <h6 class="sub_txt">{{ $timedata->break_min }}</h6> --}}
                                                                    </div>
                                                                </td>
                                                            @else
                                                                @php
                                                                    $date = str_replace('/', '_', $key); 
                                                                    $exam_time_table = DB::table('examtimetable')->where(['period_id'=>$timedata->id,'date'=>$date])->get();
                                                                @endphp
                                                                @foreach($exam_time_table as $table)
                                                                    <td style="cursor: pointer">
                                                                          @php
                                                                              $border = ($table->bordercolor !== null) ? "3px solid  $table->bordercolor " : "" ;
                                                                          @endphp
                                                                        <div  class=" schedule_box" id="{{ $timedata->id }}" data-class="{{ 2 }}" data-section="{{ 4 }}" style="border-bottom: {{  $border }} ; background-color:{{ $table->bgcolor }};">
                                            
                                                                            @php
                                                                            
                                                                                $subject = DB::table('subject')->where('id',$table->subject)->pluck('name')->first();
                                                                               
                                                                            @endphp
                                                                            <h6 class="sub_txt{{ $timedata->id  }}">{{ $subject}}</h6>
                                                                        </div>
                                                                    </td>
                                                                @endforeach
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
                </div>           
            </div>
        </div>
    </div>        
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
    window.sectionurl="{{ route('section.index') }}";
    window.calenderurl="{{ route('classtimetable.index') }}";
    window.subjectteachers="{{ route('examtimetable') }}";
    window.depturl="{{ route('department.index') }}";
    
    // window.Assignedsubjectteachers="{{ route('subject.index') }}";
   

    ExamTimetable.Timetableinit(notify_script);
    //ExamTimetable.academicinit(notify_script);

</script>
@endsection