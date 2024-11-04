@extends('layout::admin.master')

@section('title', 'exam')
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/backend/css/multistepquestion.css') }}">
    <style>
         .file-select-button {
  background: #dce4ec;
  padding: 0 10px;
  display: inline-block;
  height: 40px;
  line-height: 40px;
}
 .file-select-name {
  line-height: 40px;
  display: inline-block;
  padding: 0 10px;
}




.chooseFile {
  z-index: 100;
  cursor: pointer;
  position: absolute;
 
  top: 0;
  left: 0;
  opacity: 0;
  filter: alpha(opacity=0);
}




        .type_content {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
        }

        .types {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        .hr-time-picker {
            background: #fff !important;
            max-width: 100% !important
        }

        .error {

            color: red;
        }

        .prevbtn {
            position: absolute;
            top: -201px;
            right: -95px;
            background-color: #294dfe;
            color: #fff;
            padding: 4px 0px;
            width: 100px;
        }

       
    </style>
@endsection
@section('body')
    <div class="x_content">

        @if ($layout == 'create')
            {{ Form::open(['role' => 'form', 'route' => ['exam.store'], 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'exam-form', 'novalidate' => 'novalidate']) }}
        @elseif($layout == 'edit')
            {{ Form::open(['role' => 'form', 'route' => ['exam.update', $data->id], 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'exam-form', 'novalidate' => 'novalidate']) }}
        @endif
        <div class="box-header with-border mar-bottom20">

            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', ['type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat', 'value' => 'Edit_exam', 'class' => 'btn btn-success btn-sm m-1  px-3 ']) }}

            <a class="btn btn-info btn-sm m-1  px-3" href="{{ route('exam.index') }}"><i
                    class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

            {{-- {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }} --}}

        </div>
        @include('layout::admin.breadcrump', ['route' => $layout == 'edit' ? 'Edit exam' : 'Create Exam'])

        <div class="card">
            <div class="card-body">
                <div class="container py-5">

                    <form class="msfform pt-5">
                        <input type="hidden" name="hiddenpreview" id="hidden-preview" value="normal" />
                        <div class="msfdiv py-5">

                            <ul id="progressbar" class="examprogressbar">
                                <li class="active" id="step1">Exam Configuration</li>
                                <li id="step2">Timing & Notification</li>
                                <li id="step3">Student</li>
                                <li id="step4">Question</li>
                            </ul>

                            <div class="clearfix"></div>

                            <div class="row justify-content-center">
                                <div class="col-md-9 mt-5">

                                    <fieldset>

                                        <div class="form-card step1">

                                            <div class="row">

                                                <div class="col-md-6 mb-4">
                                                    <div class="item form-group">
                                                        <label for="acc_yr">Academic Year <span>*</span></label>

                                                        {{ Form::select(
                                                            'academic_year',
                                                            @$academic_years,
                                                            @$data->academic_year ? @$data->academic_year : @$current_academic_year,
                                                            [
                                                                'id' => 'acyear',
                                                                'class' => 'single-select  form-control termacademicyear',
                                                                'required' => 'required',
                                                                'placeholder' => 'Select Academic Year',
                                                            ],
                                                        ) }}
                                                    </div>
                                                </div>

                                                <div class="col-md-6 mb-4">
                                                    <div class="item form-group">
                                                        <label for="exam_type">Academic Term <span>*</span></label>

                                                        {{ Form::select('academic_term', @$examterms, @$data->exam_term ? @$data->exam_term : @$current_academic_term, [
                                                            'id' => 'examterm',
                                                            'class' => 'single-select form-control',
                                                            'required' => 'required',
                                                            'placeholder' => 'Select Exam Term',
                                                        ]) }}
                                                    </div>
                                                </div>

                                                <div class="col-md-6 mb-4">
                                                    <div class="item form-group">
                                                        <label for="selc_cls">Class <span>*</span></label>

                                                        {{ Form::select('class_id', @$class_lists, @$data->class_id, [
                                                            'id' => 'class_id',
                                                            'class' => 'single-select form-control',
                                                            'required' => 'required',
                                                            'placeholder' => 'Select Class',
                                                        ]) }}
                                                    </div>
                                                </div>

                                                <div class="col-md-6 mb-4">
                                                    <div class="item form-group ">
                                                        <label for="exam_type">Section <span>*</span></label>

                                                        {{ Form::select('section_id', @$section_lists, @$data->section_id, [
                                                            'id' => 'section_id',
                                                            'class' => 'single-select form-control',
                                                            'required' => 'required',
                                                            'placeholder' => 'Select Section',
                                                        ]) }}
                                                    </div>
                                                </div>

                                            </div>


                                            <div class="row">

                                                <div class="col-md-6 mb-4">
                                                    <div class="item form-group">
                                                        <label for="exam_type">Subject <span>*</span></label>

                                                        {{ Form::select('subject_id', @$subject_lists, @$data->subject_id, [
                                                            'id' => 'subject_id',
                                                            'class' => 'single-select form-control',
                                                            'required' => 'required',
                                                            'placeholder' => 'Select Subject',
                                                        ]) }}
                                                    </div>
                                                </div>

                                                <div class="col-md-6 mb-4">
                                                    <div class="item form-group">
                                                        <label for="examtype">Exam Type <span>*</span></label>

                                                        {{ Form::select('examtype', @$exam_types, @$data->exam_type, [
                                                            'id' => 'examtype',
                                                            'class' => 'single-select form-control',
                                                            'required' => 'required',
                                                            'placeholder' => 'Select Exam Type',
                                                        ]) }}
                                                    </div>
                                                </div>

                                                <div class="col-md-6 mb-4">
                                                    <div class="item form-group">
                                                        <label for="acc_yr">Exam Title <span>*</span></label>

                                                        {{ Form::text('exam_title', @$data->exam_title, [
                                                            'id' => 'exam_title',
                                                            'class' => 'form-control examtitle',
                                                            'required' => 'required',
                                                            'placeholder' => 'Enter Exam Title',
                                                        ]) }}
                                                    </div>

                                                    <div class="mark_title_details"></div>
                                                </div>
                                                <input type="hidden" name="is_homework" id="is_homework" value="0" />
                                                <input type="hidden" name="is_admission" id="is_admission" value="0" />
                                                <div class="col-md-6 mb-4" id="type_of_exam_container">
                                                    <label for="exam_type">Type of Exam <span>*</span></label>
                                                    <select class="form-control proexam" name="type_of_exam"
                                                        id="typ_of_exam" required>
                                                        <option {{ @$data->type_of_exam === 'Offline' ? 'selected' : '' }} 
                                                            value="offline">Offline </option>
                                                        <option {{ @$data->type_of_exam === 'Online'||@$data->type_of_exam === 'online'  ? 'selected' : '' }}
                                                            value="online">Online </option>
                                                    </select>
                                                </div>

                                            </div>


                                            <div class="row">

                                                {{-- <div class="col-md-6 mb-4">
                                                <div class="item form-group">
                                                <label for="selc_cls">Department <span>*</span></label>

                                                    {{ Form::select('department_id',@$department,@$data->department_id ,
                                                     array('id'=>'department_id','class' => 'single-select form-control','required' => 'required',"placeholder"=>"Select Department" )) }}
                                                    </div>
                                            </div> --}}


                                                <div class="col-md-6 mb-4">
                                                    <label for="maxmark">Max Mark <span>*</span></label>

                                                    <div class="item form-group ">
                                                        <input type="text" required value="{{ @$data->max_mark }}"
                                                            id="maxmark" name="max_mark" class="form-control ques_ip"
                                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');"
                                                            placeholder="Max Mark" />
                                                    </div>

                                                </div>

                                                <div class="col-md-6 mb-4">
                                                    <label for="minmark">Min Mark <span>*</span></label>
                                                    <div class="item form-group">
                                                        <input type="text" required value="{{ @$data->min_mark }}"
                                                            id="minmark" name="min_mark" class="form-control ques_ip"
                                                            placeholder="Min Mark" />
                                                    </div>
                                                </div>


                                            </div>

                                            <div class="row">


                                                <div class="col-md-6 mb-4">
                                                    <label for="exam_type">Promotional Exam <span>*</span></label>
                                                    <select class="form-control proexam" name="promotion" id="exam_type">
                                                        <option {{ @$data->promotion === 'Yes' ? 'selected' : '' }}>Yes
                                                        </option>
                                                        <option {{ @$data->promotion === 'No' ? 'selected' : '' }}>No
                                                        </option>
                                                    </select>
                                                </div>



                                                {{-- <div class="like_switch my-4">
                                                <label class="switch" for="stu_examsd">
                                                        <input type="checkbox" id="stu_examsd">
                                                        <span class="slider"></span>
                                                 </label>
                                                 <span class="quote_para ml-2">Enable if you will like to exclude student</span>
                                             </div>

                                              <div id="stu_examstartdate"></div>
                                              <button type="button" id="exam_stu" class="btn addnew"> Add New</button>                                --}}


                                                {{-- <div class="like_switch my-4">
                                                <label class="switch" for="stu_excludecb">
                                                      <input type="checkbox" id="stu_excludecb">
                                                      <span class="slider"></span>
                                                </label>
                                                <span class="quote_para ml-2">Enable if you will like to exclude student</span>
                                            </div>

                                            <div id="stu_exclude"></div>
                                            <button type="button" id="exclude_stu" class="btn addnew"> Add New</button> --}}

                                            </div>


                                        </div>

                                        <input type="button" name="next" class="next action-button btn"
                                            value="Next" />
                                    </fieldset>

                                    <fieldset>

                                        <div class="form-card step2">

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="quote_txt" style="margin-bottom:44px;">
                                                        <h6 class="quote_head">Exam Timing</h6>
                                                        <p class="quote_para">Enter the exam date and time, as well as the
                                                            exam timeline</p>
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="examdate">Start Date <span>*</span></label>

                                                        <div class="item form-group  ">
                                                            <input type="text" required id="exdate"
                                                                class="form-control  ques_ip datepickerwithoutselected"
                                                                name="exam_date" value="{{ @$data->exam_date }}" />
                                                        </div>

                                                        <div class="mb-4" style="margin-top:22px">
                                                            <label for="examtime">Time <span>*</span></label>
                                                            <div class="item form-group  ">
                                                                <input type="time" required
                                                                    class="form-control  ques_ip" name="exam_time"
                                                                    id="exam_time"
                                                                    value="{{ date('H:i', strtotime(@$data->exam_time)) }}" />
                                                            </div>
                                                        </div>
                                                         
                                                        <div class="like_switch my-4">
                                                            <label class="switch" for="stu_examsd">
                                                                <input type="checkbox" id="stu_examsd"  {{ (@$data) ? 'checked' : '' }}>
                                                                <span class="slider"></span>
                                                            </label>
                                                            <span class="quote_para ml-2">Enable if you will like to
                                                                Activate Exam Submission Date and Time</span>
                                                        </div>

                                                        <div id="stu_examstartdate"></div>
                                                        <div id="exsdatediv">
                                                            <label for="examtime">Submission Date </label>
                                                        <input type="text" id="exsdate" 
                                                            class="form-control  ques_ip datepickerwithoutselected exam_sdate_dp"
                                                            name="exam_submission_date" value="{{@$data->exam_date}}" />
                                                        </div>
                                                        
                                                        <div class="mb-4" style="margin-top:22px;display:none"
                                                            id="exstime">
                                                            <label for="examtime">Submission Time </label>
                                                            <div class="item form-group  ">
                                                                <input type="time" 
                                                                    class="form-control  ques_ip"
                                                                    name="exam_submission_time" id="exam_time"
                                                                    value="{{@$data->exam_submission_time}}" />
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="mb-4">

                                                        <label for="examtime">Time Line </label>
                                                        <div class="item form-group  ">
                                                            <div class="hr-time-picker">
                                                                <div class="picked-time-wrapper">
                                                                    <input type="text" class="picked-time"
                                                                        name="timeline" value="{{ @$data->timeline }}">
                                                                       
                                                                </div>
                                                                <div class="pick-time-now">
                                                                    <div class="hours hr-timer">
                                                                        <div class="movable-area">
                                                                            <ul></ul>
                                                                        </div>
                                                                    </div>
                                                                    <div class="minutes hr-timer">
                                                                        <ul></ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="quote_txt mb-4">
                                                        <h6 class="quote_head">Exam Notification</h6>
                                                        <p class="quote_para">Provide the exam notice in order to notify
                                                            the student and parent as soon as the exam date approaches.</p>
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="notifydate">Date <span>*</span></label>
                                                        <input type="text" required id="notifydate"
                                                            class="form-control ques_ip datepickerwithoutselected"
                                                            value="{{ @$data->notification->notify_date }}"
                                                            name="notify_date" />
                                                    </div>

                                                    <div class="mb-4">
                                                        <label for="notifytime">Time <span>*</span></label>
                                                        <input type="time" required id="notify"
                                                            class="form-control ques_ip "
                                                            value="{{ @$data->notification->notify_time }}"
                                                            name="notify_time" />
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="notifymsg">Message <span>*</span></label>
                                                        <textarea rows="6" id="notifymsg" class="form-control ques_ip" name="notify_message">{{ @$data->notification->notify_message }}</textarea>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <input type="button" name="previous" class="previous action-button-previous btn"
                                            value="Back" />
                                        <input type="button" name="next" class="next action-button btn"
                                            value="Next" />
                                    </fieldset>

                                    <fieldset>

                                        <div class="form-card step3">

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="quote_txt">
                                                        <h6 class="quote_head">Student Exclusive</h6>
                                                        <p class="quote_para">You may exclude some students from a specific
                                                            class for academic reasons.</p>
                                                    </div>


                                                    @if (@$layout == 'edit')
                                                        <div class="like_switch my-4">
                                                            <label class="switch" for="stu_excludecb">
                                                                <input type="checkbox" id="stu_excludecb">
                                                                <span class="slider"></span>
                                                            </label>
                                                            <span class="quote_para ml-2">Enable if you will like to
                                                                exclude student</span>
                                                        </div>
                                                        <div id="stu_exclude">
                                                            @if (@$data->exclude_students)
                                                                @foreach (explode(',', @$data->exclude_students) as $id)
                                                                    <div class="mb-4 position-relative stu_box">
                                                                        <select class="form-control exc_stu"
                                                                            id="exam_type" name="exclude_students[]">
                                                                            @foreach ($exclude_students as $key => $student)
                                                                                <option value="{{ $key }}"
                                                                                    {{ $id == $key ? 'selected' : '' }}>
                                                                                    {{ $student }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        <button type="button"
                                                                            class="btn remove_stu">&times;</button>
                                                                    </div>
                                                                @endforeach
                                                            @endif

                                                        </div>
                                                        <button type="button" id="exclude_stu" class="btn addnew"> Add
                                                            New</button>
                                                    @else
                                                        <div class="like_switch my-4">
                                                            <label class="switch" for="stu_excludecb">
                                                                <input type="checkbox" id="stu_excludecb">
                                                                <span class="slider"></span>
                                                            </label>
                                                            <span class="quote_para ml-2">Enable if you will like to
                                                                exclude student</span>
                                                        </div>

                                                        <div id="stu_exclude"></div>
                                                        <button type="button" id="exclude_stu" class="btn addnew"> Add
                                                            New</button>
                                                    @endif


                                                </div>

                                                <div class="col-md-6">
                                                    <div class="quote_txt">
                                                        <h6 class="quote_head">Student Inclusive</h6>
                                                        <p class="quote_para">You may add students from the same class or
                                                            from other classes for academic purposes.</p>
                                                    </div>

                                                    @if (@$layout == 'edit')

                                                        <div class="like_switch my-4">
                                                            <label class="switch" for="stu_includecb">
                                                                <input type="checkbox" id="stu_includecb">
                                                                <span class="slider"></span>
                                                            </label>
                                                            <span class="quote_para ml-2">Enable if you will like to
                                                                include student</span>
                                                        </div>
                                                        <div id="stu_include">
                                                            @if (@$data->include_students)
                                                                @foreach (explode(',', @$data->include_students) as $id)
                                                                    <div class="mb-4 position-relative stu_box">
                                                                        <select class="form-control exc_stu"
                                                                            id="exam_type" name="include_students[]">
                                                                            @foreach ($include_students as $key => $student)
                                                                                <option value="{{ $key }}"
                                                                                    {{ $id == $key ? 'selected' : '' }}>
                                                                                    {{ $student }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        <button type="button"
                                                                            class="btn remove_stu">&times;</button>
                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                        <button type="button" id="include_stu" class="btn addnew"> Add
                                                            New</button>
                                                    @else
                                                        <div class="like_switch my-4">
                                                            <label class="switch" for="stu_includecb">
                                                                <input type="checkbox" id="stu_includecb">
                                                                <span class="slider"></span>
                                                            </label>
                                                            <span class="quote_para ml-2">Enable if you will like to
                                                                include student</span>
                                                        </div>
                                                        <div id="stu_include">

                                                        </div>
                                                        <button type="button" id="include_stu" class="btn addnew"> Add
                                                            New</button>
                                                    @endif
                                                    
                                                </div>                                            
                                            
                                            </div>    
                                            <div class="quote_txt">
                                                <h6 class="quote_head">Exam Results Page</h6>
                                                <p class="quote_para">You may enable or disable on displaying obtained results after the exam</p>
                                            </div>               
                                            <div class="like_switch my-4">
                                                <label class="switch" for="stu_results">
                                                    <input type="checkbox" id="stu_results" name="stu_results" value="1">
                                                    <span class="slider"></span>
                                                </label>
                                                <span class="quote_para ml-2">Enable if students wish to see results after the exam get over.
                                                </span>
                                            </div>                                                                         
                                            
                                        </div>

                                        <input type="button" name="previous" class="previous action-button-previous btn"
                                            value="Back" />
                                        <input type="button" name="next" class="next action-button btn"
                                            value="Next" />
                                    </fieldset>

                                    <fieldset>


                                        <div class="form-card step4">
                                            <div class="row">
                                                <div class="col-12 pl-0 d-flex align-items-center"
                                                    style="margin-right:10px">

                                                    <textarea rows="2" cols="50" placeholder="Exam Instructions.." name="examinstruction"
                                                        class="form-control">{{ @$data->examistruction }}</textarea>


                                                </div>
                                            </div>

                                            <input type="hidden" name="duptype" value="{{ @$type }}" />
                                            @if (@$layout == 'edit' || @$type == 'duplicate')
                                                @php
                                                    $last = 0;
                                                    $last_order = 0;
                                                    $last_sub = 0;
                                                    $last_order_sub = 0;
                                                    $total_mark = 0;
                                                @endphp
                                                <input type="hidden" name="pre_exam_id" value="{{@$data->id}}">
                                                <input type="hidden" name="dup_type" value="{{@$type}}">
                                                @foreach (@$data->sections as $section)
                                                    @php
                                                        $unique_mark = uniqid();
                                                    @endphp
                                                    <div class="row secrow py-4 secrow1" id="{{ $section->id }}section">
                                                        <div class="col-md-12">
                                                            <div class="remove_section">
                                                                <i class="fa fa-times"
                                                                    onclick="ExamConfig.deletesection({{ @$data->id }},{{ @$section->id }})"></i>
                                                            </div>
                                                            <div class="d-flex align-items-end">
                                                                <div class="col-10 pl-0 d-flex align-items-center sectioncol"
                                                                    style="margin-right:10px">
                                                                    <input type="text"
                                                                        id="{{ @$section->id }}sectionname"
                                                                        name="section[section{{ @$section->id }}st][]"
                                                                        required placeholder="Section Name"
                                                                        class="form-control"
                                                                        value="{{ @$section->section_name }}" />

                                                                    <input type="hidden"
                                                                        name="section[section{{ @$section->id }}st][secorder]"
                                                                        value="{{ @$section->section_order }}" />
                                                                    <input type="hidden"
                                                                        name="section[section{{ @$section->id }}st][pre_section_id]"
                                                                        value="{{ @$section->id }}" />    
                                                                </div>

                                                                <div class="col-2 pr-0 sectotalmark">
                                                                    <label for="totalmark"
                                                                        class="text-nowrap secmark_label">Section Total
                                                                        Mark</label><input type="text"
                                                                        id="sectotalmark" class="form-control ques_ip sectotalmarkinput"
                                                                        name="section[section{{ @$section->id }}st][totalmark]"
                                                                        readonly onkeyup="totalvalidate();"
                                                                        value="{{ @$section->section_mark }}" />
                                                                </div>
                                                                @php
                                                                    $total_mark = $total_mark + $section->section_mark;
                                                                @endphp
                                                            </div>

                                                            <div id="qusdiv"
                                                                class="pt-4 1stsectionques{{ @$section->id }}">



                                                                @foreach (@$section->questions as $question)
                                                                    @if (@$question->question_type == 'subquestion')
                                                                        @php
                                                                            $unique_sub = uniqid();
                                                                            $uniqueaddoption_sub = uniqid();
                                                                        @endphp

                                                                        <div class="tab_row mb-3" id="${choosediv}">
                                                                            <div class="row align-items-end">
                                                                                <div class="col-md-12">
                                                                                    <div class="row mt-3"
                                                                                        id=${addoptionrow}>

                                                                                        <div
                                                                                            class="col-md-12 pr-0 d-flex ">
                                                                                            <div
                                                                                                class="col-md-9 pr-0 d-flex">
                                                                                                <div class="w-100">
                                                                                                    <label
                                                                                                        for="${idqus2}"><span><span
                                                                                                                class="inc_span{{ @$section->id }}">{{ $loop->index + 1 }}</span>&nbsp;.&nbsp;</span>Question
                                                                                                    </label>

                                                                                                </div>
                                                                                            </div>
                                                                                            <div
                                                                                                class="col-md-3 text-center">

                                                                                                <div class="row_">
                                                                                                    <div class="col-md-6">
                                                                                                        <label
                                                                                                            for="${idmark2}">Mark</label>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>

                                                                                        </div>

                                                                                        <div id="addoptionrowsub">
                                                                                            @foreach ($question->subquestion as $key => $subquestion)
                                                                                                <div
                                                                                                    class="col-md-12 pr-0 d-flex opt mb-2">

                                                                                                    <div class="col-md-9"
                                                                                                        style="margin-right:10px">
                                                                                                        <div class="w-100"
                                                                                                            style="display:flex">
                                                                                                            <span
                                                                                                                class="mt-2 mr_20 alph_box{{ @$unique_sub }}">{{ chr(65 + @$key) }}</span>

                                                                                                            <textarea name="section[section{{ @$section->id }}st][questions][sub_ques][{{ $question->order }}][]" id="${idqus2}"
                                                                                                                required class="form-control radio_txt">{{ @$subquestion->question }}</textarea>


                                                                                                        </div>
                                                                                                    </div>

                                                                                                    <div class="col-md-2"
                                                                                                        style="margin-right:10px">
                                                                                                        <input
                                                                                                            type="text"
                                                                                                            name="section[section{{ @$section->id }}st][questions][sub_ques][{{ $question->order }}][mark][]"
                                                                                                            id="${idmark2}"
                                                                                                            required
                                                                                                            value="  {{ @$subquestion->mark }}"
                                                                                                            class="form-control ques_ip mark_cls {{ $unique_mark }}" />

                                                                                                    </div>
                                                                                                    <div
                                                                                                        class="col-md-1 removesub">
                                                                                                        <label
                                                                                                            for=""></label>
                                                                                                        <i data-class="{{ $unique_mark }}"
                                                                                                            class="fa fa-times m-2 removeoption ${removeoption}"
                                                                                                            onclick="removeOption(this,'{{ $unique_sub }}','btn{{ $uniqueaddoption_sub }}','subquestion')"></i>
                                                                                                    </div>

                                                                                                </div>
                                                                                                @php
                                                                                                    $last_order_sub =
                                                                                                        $loop->index;
                                                                                                @endphp
                                                                                            @endforeach
                                                                                        </div>

                                                                                    </div>
                                                                                    <div class="row mt-3">
                                                                                        <div class="col-md-12">
                                                                                            <button type="button"
                                                                                                id="btn{{ @$uniqueaddoption_sub }}"
                                                                                                class="btn qus-btns"
                                                                                                data-order="{{ $question->order }}"
                                                                                                data-id="1st"
                                                                                                data-last="{{ $last_order_sub }}"
                                                                                                data-unique={{ @$unique_sub }}
                                                                                                onclick="addOption(this,{{ @$section->id }},this.id,'subquestion')">
                                                                                                Add question</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row align-items-end">
                                                                                <div class="col-md-11"></div>

                                                                                <div class="col-md-1">
                                                                                    <button type="button"
                                                                                        style="margin-top: 23px"
                                                                                        data-class="{{ $unique_mark }}"
                                                                                        data-section="{{ @$section->id }}"
                                                                                        class="remove_ques btn btn-danger"
                                                                                        onclick="ExamConfig.deletequestion({{ @$question->exam_id }},{{ @$question->id }})">
                                                                                        <i class="fa fa-trash"></i>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endif

                                                                    @if (@$question->question_type == 'longquestion')
                                                                        <div class="tab_row mb-3">
                                                                            <div class="row align-items-end">
                                                                                <div class="col-md-9"><label
                                                                                        for="${idqus5}"><span><span
                                                                                                class="inc_span{{ $section->id }}">{{ $loop->index + 1 }}</span>&nbsp;.&nbsp;</span>Question
                                                                                    </label>
                                                                                    <textarea required name="section[section{{ $section->id }}st][questions][longques][{{ $question->order }}][]"
                                                                                        id="${idqus5}" class="form-control ques_ip">{{ $question->question }}</textarea>
                                                                                </div>
                                                                                <div class="col-md-2"><label
                                                                                        for="${idmark5}">Mark</label><input
                                                                                        type="text"
                                                                                        value="{{ $question->mark }}"
                                                                                        name="section[section{{ $section->id }}st][questions][longques][{{ $question->order }}][mark][]"
                                                                                        id="${idmark5}" required
                                                                                        class="form-control ques_ip mark_cls {{ $unique_mark }}" />
                                                                                </div>
                                                                                <div class="col-md-1"><button
                                                                                        data-class="{{ $unique_mark }}"
                                                                                        data-section="{{ @$section->id }}"
                                                                                        type="button"
                                                                                        onclick="ExamConfig.deletequestion({{ @$question->exam_id }},{{ @$question->id }})"
                                                                                        class="remove_ques btn btn-danger"><i
                                                                                            class="fa fa-trash"></i></button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endif

                                                                    @if (@$question->question_type == 'shortquestion')
                                                                        <div class="tab_row mb-3">
                                                                            <div class="row align-items-end">
                                                                                <div class="col-md-9"><label
                                                                                        for="${idqus4}"><span><span
                                                                                                class="inc_span{{ @$section->id }}">{{ $loop->index + 1 }}</span>&nbsp;.&nbsp;</span>Question
                                                                                    </label>
                                                                                    <textarea required name="section[section{{ @$section->id }}st][questions][shortques][{{ $question->order }}][]"
                                                                                        id="${idqus4}" class="form-control ques_ip">{{ @$question->question }}</textarea>
                                                                                </div>
                                                                                <div class="col-md-2"><label
                                                                                        for="${idmark4}">Mark</label><input
                                                                                        value="{{ $question->mark }}"
                                                                                        type="text"
                                                                                        name="section[section{{ @$section->id }}st][questions][shortques][{{ $question->order }}][mark][]"
                                                                                        id="${idmark4}" required
                                                                                        class="form-control ques_ip mark_cls {{ $unique_mark }}" />
                                                                                </div>
                                                                                <div class="col-md-1"><button
                                                                                        data-class="{{ $unique_mark }}"
                                                                                        data-section="{{ @$section->id }}"
                                                                                        onclick="ExamConfig.deletequestion({{ @$question->exam_id }},{{ @$question->id }})"
                                                                                        type="button"
                                                                                        class="remove_ques btn btn-danger"><i
                                                                                            class="fa fa-trash"></i></button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endif

                                                                    @if (@$question->question_type == 'yesorno')
                                                                        <div class="tab_row mb-3">
                                                                            <div class="row">
                                                                                <div class="col-md-9"><label
                                                                                        for=""><span><span
                                                                                                class="inc_span{{ @$section->id }}}">{{ $loop->index + 1 }}</span>&nbsp;.&nbsp;</span>Question
                                                                                    </label>
                                                                                    <textarea name="section[section{{ @$section->id }}st][questions][yesorno][{{ $question->order }}][]" id=""
                                                                                        class="form-control ques_ip">{{ $question->question }}</textarea>
                                                                                    <div class="mt-2"><label
                                                                                            class="mr-3">Answer</label>

                                                                                        @foreach (explode(',', $question->options) as $key => $option)
                                                                                            <div class="d-flex mt-2"><input
                                                                                                    type="radio"
                                                                                                    {{ $key == $question->answer ? 'checked' : '' }}
                                                                                                    name="section[section{{ @$section->id }}st][questions][yesorno][{{ $question->order }}][answer][]"
                                                                                                    value="{{ $key }}"
                                                                                                    id="noans"
                                                                                                    class="radio_cbox" /><label><input
                                                                                                        type="text"
                                                                                                        name="section[section{{ @$section->id }}st][questions][yesorno][{{ $question->order }}][options][]"
                                                                                                        value="{{ $option }}"
                                                                                                        name="section[section{{ @$section->id }}}st][questions][yesorno][{{ $question->order }}][options][]"
                                                                                                        id="yestxtno"
                                                                                                        class="form-control radio_txt" /></label>
                                                                                            </div>
                                                                                        @endforeach




                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-3">
                                                                                    <div class="imagesection">
                                                                                        <input type="file"
                                                                                            style="display: none;"
                                                                                            name="section[section{{ @$section->id }}st][questions][yesorno][{{ $question->order }}][image][]"
                                                                                            id="${fileid}"
                                                                                            placeholder="Choose the file"
                                                                                            class="form-control file_ip imag size_img"
                                                                                            accept=".docx,.pdf,.jpeg,.jpg,.png" />
                                                                                        <input type="hidden"
                                                                                            name="section[section{{ @$section->id }}st][questions][yesorno][{{ $question->order }}][oldimage][]"
                                                                                            value="{{ @$question->attachment }}" />
                                                                                        <label for="${fileid}"
                                                                                            class="imgprev"><img
                                                                                                id="ImgPreview"
                                                                                                src={{ asset(@$question->attachment) }}
                                                                                                for="${fileid}" /></label>
                                                                                         <p class="text-danger error_msg"></p>       
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row align-items-end mt-3">
                                                                                <div class="col-md-9"></div>
                                                                                <div class="col-md-2"><label
                                                                                        for="${idmark6}">Mark</label><input
                                                                                        value="{{ $question->mark }}"
                                                                                        type="text"
                                                                                        name="section[section{{ @$section->id }}st][questions][yesorno][{{ $question->order }}][mark][]"
                                                                                        required id="${idmark6}"
                                                                                        class="form-control mark_cls {{ $unique_mark }}" />
                                                                                </div>
                                                                                <div class="col-md-1"><button
                                                                                        data-class="{{ $unique_mark }}"
                                                                                        data-section="{{ @$section->id }}"
                                                                                        type="button"
                                                                                        onclick="ExamConfig.deletequestion({{ @$question->exam_id }},{{ @$question->id }})"
                                                                                        class="remove_ques btn btn-danger"><i
                                                                                            class="fa fa-trash"></i></button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endif

                                                                    @if (@$question->question_type == 'choosebest')
                                                                        @php
                                                                            $unique = uniqid();
                                                                            $uniqueaddoption = uniqid();
                                                                        @endphp

                                                                        <div class="tab_row mb-3" id="">
                                                                            <div class="row">
                                                                                <div class="col-md-9">
                                                                                    <div class="row">
                                                                                        <div class="col-md-12"><label
                                                                                                for=""><span><span
                                                                                                        class="inc_span{{ @$section->id }}">{{ @$loop->index + 1 }}</span>&nbsp;.&nbsp;</span>Question
                                                                                            </label>
                                                                                            <textarea required name="section[section{{ @$section->id }}st][questions][choose_best][{{ @$question->order }}][]"
                                                                                                id="" class="form-control ques_ip">{{ @$question->question }}</textarea>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div id="addoptionrow">
                                                                                        @foreach (explode(',', @$question->options) as $key => $option)
                                                                                            <div class="row mt-3"
                                                                                                id="">
                                                                                                <div
                                                                                                    class="col-md-12 pr-0 d-flex opt">
                                                                                                    <input type="radio"
                                                                                                        {{ $key == @$question->answer ? 'checked' : '' }}
                                                                                                        name="section[section{{ @$section->id }}st][questions][choose_best][{{ @$question->order }}][answer][]"
                                                                                                        id=""
                                                                                                        value="{{ @$key }}"
                                                                                                        class="radio_cbox" /><span
                                                                                                        class="mt-2 mr_20 alph_box{{ @$unique }}">{{ chr(65 + @$key) }}</span><label
                                                                                                        class="col-10"><input
                                                                                                            type="text"
                                                                                                            name="section[section{{ @$section->id }}st][questions][choose_best][{{ @$question->order }}][options][]"
                                                                                                            value="{{ @$option }}"
                                                                                                            id=""
                                                                                                            class="form-control radio_txt" /></label><i
                                                                                                        class="fa fa-times m-2 removeoption "
                                                                                                        id="removeopt{{ $key }}"
                                                                                                        onclick="removeOption(this,'{{ $unique }}','btn{{ $uniqueaddoption }}','choose')"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                            @php
                                                                                                $last_order =
                                                                                                    $loop->index;
                                                                                            @endphp
                                                                                        @endforeach
                                                                                    </div>


                                                                                    {{-- answer --}}



                                                                                    <div class="row mt-3">
                                                                                        <div class="col-md-12">
                                                                                            <button type="button"
                                                                                                id="btn{{ @$uniqueaddoption }}"
                                                                                                class="btn qus-btns"
                                                                                                data-order="{{ $question->order }}"
                                                                                                data-id="1st"
                                                                                                data-last="{{ $last_order }}"
                                                                                                data-unique={{ @$unique }}
                                                                                                onclick="addOption(this,{{ @$section->id }},this.id,'choose')">
                                                                                                Add Option</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-3">
                                                                                    <div class="row">
                                                                                        <div class="imagesection">
                                                                                            <input type="hidden"
                                                                                                name="section[section{{ @$section->id }}st][questions][choose_best][{{ $question->order }}][oldimage][]"
                                                                                                value="{{ @$question->attachment }}" />
                                                                                            <input type="file"
                                                                                                style="display: none;"
                                                                                                name="section[section{{ @$section->id }}st][questions][choose_best][{{ @$question->order }}][image][]"
                                                                                                id="${fileid}"
                                                                                                placeholder="Choose the file"
                                                                                                class="form-control file_ip imag size_img"
                                                                                                accept=".xlsx,.docx,.pdf,.txt,.jpeg,.gif,.jpg,.png,.mp4" />
                                                                                            <label for="${fileid}"
                                                                                                class="imgprev"><img
                                                                                                    id="ImgPreview"
                                                                                                    src={{ asset(@$question->attachment) }}
                                                                                                    for="${fileid}" /></label>
                                                                                            <p class="text-danger error_msg"></p>       
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row align-items-end mt-3">
                                                                                <div class="col-md-9"></div>
                                                                                <div class="col-md-2"><label
                                                                                        for="${idmark2}">Mark</label><input
                                                                                        type="text"
                                                                                        value="{{ @$question->mark }}"
                                                                                        required
                                                                                        name="section[section{{ @$section->id }}st][questions][choose_best][{{ @$question->order }}][mark][]"
                                                                                        id="${idmark2}"
                                                                                        class="form-control ques_ip mark_cls {{ $unique_mark }}" />
                                                                                </div>
                                                                                <div class="col-md-1"><button
                                                                                        data-class="{{ $unique_mark }}"
                                                                                        data-section="{{ @$section->id }}"
                                                                                        type="button"
                                                                                        onclick="ExamConfig.deletequestion({{ @$question->exam_id }},{{ @$question->id }})"
                                                                                        style="margin-top: 23px;"
                                                                                        class="remove_ques btn btn-danger"><i
                                                                                            class="fa fa-trash"></i></button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endif

                                                                    @if (@$question->question_type == 'fillintheblanks')
                                                                        <div class="tab_row mb-3">
                                                                            <div class="row align-items-end">
                                                                                <div class="col-md-9"><label
                                                                                        for=""><span><span
                                                                                                class="inc_span{{ @$section->id }}">{{ $loop->index + 1 }}</span>&nbsp;.&nbsp;</span>Question
                                                                                    </label>
                                                                                    <textarea name="section[section{{ @$section->id }}st][questions][fillblanks][{{ @$question->order }}][]"
                                                                                        id="" required class="form-control ques_ip">{{ @$question->question }}</textarea>
                                                                                    <label for=""
                                                                                        class="mt-2">Answer</label><input
                                                                                        value="{{ @$question->answer }}"
                                                                                        type="text" required
                                                                                        name="section[section{{ @$section->id }}st][questions][fillblanks][{{ @$question->order }}][answer][]"
                                                                                        id=""
                                                                                        class="form-control ques_ip" />
                                                                                </div>
                                                                                <div class="col-md-2"><label
                                                                                        for="">Mark</label><input
                                                                                        value="{{ @$question->mark }}"
                                                                                        type="text"
                                                                                        name="section[section{{ @$section->id }}st][questions][fillblanks][{{ @$question->order }}][mark][]"
                                                                                        id="" required
                                                                                        class="form-control ques_ip mark_cls {{ $unique_mark }}" />
                                                                                </div>
                                                                                <div class="col-md-1"><button
                                                                                        data-class="{{ $unique_mark }}"
                                                                                        data-section="{{ @$section->id }}"
                                                                                        type="button"
                                                                                        onclick="ExamConfig.deletequestion({{ @$question->exam_id }},{{ @$question->id }})"
                                                                                        class="remove_ques btn btn-danger"><i
                                                                                            class="fa fa-trash"></i></button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endif

                                                                    @if (@$question->question_type == 'homework')
                                                                    <div class="tab_row mb-3">
                                                                        <div class="row">
                                                                            <div class="col-md-9 col-xs-12">
                                                                                <div class="imagesection">
                                                                                    <label for="ddcl"><span><span class="inc_span">{{ $loop->index + 1 }}</span>&nbsp;.&nbsp;</span>Attach File </label><br>
                                                                                    <div class="file-select-button" id="fileName">Choose File</div>
                                                                                    <div class="file-select-name" id="noFile">{{@$question->attachment}}</div> 
                                                                                    <input type="file"  name="section[section{{ @$section->id }}st][questions][homework][{{ $question->order }}][image][]"
                                                                                     id="${fileid}" class="form-control ques_ip placeholder_custom chooseFile tex_img section_id" data-section = "{{ @$section->id }}" data-order = "{{ $question->order }}" style="margin-bottom:10px"  value="{{ asset($question->attachment)}}" accept=".docx,.pdf,.jpeg,.gif,.jpg,.png,"/>
                                                                                    <p class="text-danger error_msg"></p>
                                                                                     <input type="hidden" class="placeholder"
                                                                                        name="section[section{{ @$section->id }}st][questions][homework][{{ $question->order }}][oldimage][]"
                                                                                        value="{{ @$question->attachment }}" />
                                                                                      
                                                                                </div>
                                                                            </div>
                                                                       
                                                                            <div class="col-md-3 col-xs-12 redcol">
                                                                                @if(isset($question->attachment))
                                                                                    @php
                                                                                        $attachment = asset($question->attachment);
                                                                                        $file_extension = pathinfo($attachment, PATHINFO_EXTENSION);
                                                                                    @endphp
                                                                                    @if($file_extension == "jpg" || $file_extension == "png" || $file_extension == "gif")
                                                                                        <a href="{{ asset($question->attachment) }}" target="_blank">
                                                                                            <img src="{{ asset($question->attachment) }}" class="img-fluid file" alt="Image">
                                                                                        </a>
                                                                                    @elseif ($file_extension == 'mp4' || $file_extension == 'avi' || $file_extension == 'mov')  
                                                                                        <img src="{{ asset('assets/sample/images.png') }}" class="img-fluid" alt="Video Thumbnail">
                                                                                    @elseif($file_extension == 'mp3') 
                                                                                        <img src="{{ asset('assets/sample/istockphoto-1244097573-612x612.jpg') }}" class="img-fluid" alt="Audio Thumbnail">
                                                                                    @else
                                                                                    <a href="{{ asset($question->attachment) }}" target="_blank">
                                                                                        <img src="{{ asset('assets/sample/file.jpg') }}"  class="img-fluid file" alt="File Thumbnail">
                                                                                    </a>   
                                                                                    @endif
                                                                                @endif    
                                                                            </div>
                                                                            
                                                                            
                                                                       
                                                                            <div class="col-md-12"><label
                                                                                    for=""><span><span
                                                                                            class="inc_span{{ @$section->id }}}">Descripition
                                                                                </label>
                                                                                <textarea name="section[section{{ @$section->id }}st][questions][homework][{{ $question->order }}][]" id=""
                                                                                    class="form-control ques_ip">{{ $question->question }}</textarea>
                                                                                <div class="mt-2">

                                                                                    @foreach (explode(',', $question->options) as $key => $option)
                                                                                        <div class="d-flex mt-2">
                                                                                           
                                                                                        </div>
                                                                                    @endforeach

                                                                                </div>
                                                                            </div>
                                                                            
                                                                       
                                                                            <div class="col-md-1"><button
                                                                                    data-class="{{ $unique_mark }}"
                                                                                    data-section="{{ @$section->id }}"
                                                                                    type="button"
                                                                                    onclick="ExamConfig.deletequestion({{ @$question->exam_id }},{{ @$question->id }})"
                                                                                    class="remove_ques btn btn-danger"><i
                                                                                        class="fa fa-trash"></i></button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                  
                                                                    @php
                                                                        $last = $loop->index + 1;
                                                                    @endphp
                                                                @endforeach
                                                            </div>
                                                            <input type="hidden" name="type_exam_name" id="type_exam_name" value="{{@$data->type_of_exam}}">
                                                            @if($data)
                                                            @if($data->type_of_exam == "Homework")
                                                                <div class="text-center d-none" id="initialbutton">
                                                                    <button type="button" id="addquestion"
                                                                        class="btn btn-warning text-white">
                                                                        Add Question
                                                                    </button>
                                                                    <button type="button" id="subquestiona"
                                                                        onclick="addQuestion(this,'initialsection-section{{ @$section->id }}',{{ @$section->id }},'subquestion','{{ @$unique_mark }}')"
                                                                        class="btn btn-primary">
                                                                        Sub Question
                                                                    </button>
                                                                </div>

                                                                <div class="text-center " id="homeworksection">
                                                                    <button type="button" id="addquestionHomework1"
                                                                        class="btn btn-warning text-white addquestionHomework">
                                                                        Homework
                                                                    </button>
                                                                </div>
                                                            @else
                                                            <div class="text-center editinitialbutton" id="initialbutton">
                                                                <button type="button" id="addquestion"
                                                                    class="btn btn-warning text-white">
                                                                    Add Question
                                                                </button>
                                                                <button type="button" id="subquestiona"
                                                                    onclick="addQuestion(this,'initialsection-section{{ @$section->id }}',{{ @$section->id }},'subquestion','{{ @$unique_mark }}')"
                                                                    class="btn btn-primary">
                                                                    Sub Question
                                                                </button>
                                                            </div>

                                                            <div class="text-center d-none" id="homeworksection">
                                                                <button type="button" id="addquestionHomework1"
                                                                    class="btn btn-warning text-white addquestionHomewor">
                                                                    Homework
                                                                </button>
                                                            </div>
                                                            @endif
                                                            @endif
                                                            <div class="d-flex justify-content-between mt-4 questionoptions questionoptions1"
                                                                id="initialsection-section{{ @$section->id }}">
                                                                <input type="hidden"
                                                                    name="hiddenorder{{ @$section->id }}"
                                                                    value="{{ @$last }}"
                                                                    id="hiddenorder{{ @$section->id }}" />
                                                                <button type="button" class="btn qus-btns"
                                                                    onclick="addQuestion(this,'initialsection-section{{ @$section->id }}',{{ @$section->id }},'fill',{{ $last }},'{{ $unique_mark }}')"
                                                                    id="fillintheblanks">
                                                                    Fill in the Blanks</button><button type="button"
                                                                    class="btn qus-btns"
                                                                    onclick="addQuestion(this,'initialsection-section{{ @$section->id }}',{{ @$section->id }},'choose',{{ @$last }},'{{ $unique_mark }}')"
                                                                    id="choosethebestans">
                                                                    Choose the Best Answer</button><button type="button"
                                                                    class="btn qus-btns"
                                                                    onclick="addQuestion(this,'initialsection-section{{ @$section->id }}',{{ @$section->id }},'yes',{{ @$last }},'{{ $unique_mark }}')"
                                                                    id="yesornoqus">
                                                                    Yes/No Questions
                                                                </button>
                                                                @if($data)
                                                                    @if($data->type_of_exam == "offline" || $data->type_of_exam == "Offline")
                                                                        <button type="button" class="btn qus-btns"
                                                                            onclick="addQuestion(this,'initialsection-section{{ @$section->id }}',{{ @$section->id }},'short',{{ @$last }},'{{ $unique_mark }}')"
                                                                            id="shortqus">
                                                                            Short Questions</button><button type="button"
                                                                            class="btn qus-btns"
                                                                            onclick="addQuestion(this,'initialsection-section{{ @$section->id }}',{{ @$section->id }},'long',{{ @$last }},'{{ $unique_mark }}')"
                                                                            id="longqus">
                                                                            Long Questions
                                                                        </button>
                                                                    @endif
                                                                @else
                                                                    <button type="button" class="btn qus-btns"
                                                                        onclick="addQuestion(this,'initialsection-section{{ @$section->id }}',{{ @$section->id }},'short',{{ @$last }},'{{ $unique_mark }}')"
                                                                        id="shortqus">
                                                                        Short Questions</button><button type="button"
                                                                        class="btn qus-btns"
                                                                        onclick="addQuestion(this,'initialsection-section{{ @$section->id }}',{{ @$section->id }},'long',{{ @$last }},'{{ $unique_mark }}')"
                                                                        id="longqus">
                                                                        Long Questions
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="row secrow py-4" id="1section">
                                                    <div class="col-md-12">
                                                        <div class="remove_section">
                                                            <i class="fa fa-times"></i>
                                                        </div>
                                                        <div class="d-flex align-items-end">
                                                            <div class="col-10 pl-0 d-flex align-items-center sectioncol"
                                                                style="margin-right:10px">
                                                                <input type="hidden" name="section[section1st][secorder]"
                                                                    value="{{ @$maxsectionorder }}" />
                                                                <input type="text" id="1sectionname"
                                                                    name="section[section1st][]" required
                                                                    placeholder="Section Name" class="form-control" />
                                                            </div>

                                                            <div class="col-2 pr-0 sectotalmark">
                                                                <label for="totalmark"
                                                                    class="text-nowrap secmark_label">Section Total
                                                                    Mark</label><input type="text" id="sectotalmark"
                                                                    class="form-control ques_ip sectotalmarkinput"
                                                                    name="section[section1st][totalmark]" readonly
                                                                    onkeyup="totalvalidate();" />
                                                            </div>
                                                        </div>

                                                        <div id="qusdiv" class="pt-4 1stsectionques"></div>
                                                        <div class="text-center" id="initialbutton">
                                                            <button type="button" id="addquestion"
                                                                class="btn btn-warning text-white">
                                                                Add Questions
                                                            </button>
                                                            <button type="button" id="subquestion"
                                                                class="btn btn-primary">
                                                                Sub Questions
                                                            </button>
                                                        </div>
                                                        <div class="text-center" id="homeworksection">
                                                            <button type="button" id="addquestionHomework1"
                                                                class="btn btn-warning text-white">
                                                                Homework
                                                            </button>
                                                        </div>
                                                        <div class="d-flex justify-content-between mt-4 questionoptions"
                                                            style="display: none !important" id="initialsection">
                                                            <button type="button" class="btn qus-btns"
                                                                id="fillintheblanks">
                                                                Fill in the Blanks</button><button type="button"
                                                                class="btn qus-btns" id="choosethebestans">
                                                                Choose the Best Answer</button><button type="button"
                                                                class="btn qus-btns" id="yesornoqus">
                                                                Yes/No Questions
                                                            </button>
                                                            <button type="button" class="btn qus-btns" id="shortqus">
                                                                Short Questions</button><button type="button"
                                                                class="btn qus-btns" id="longqus">
                                                                Long Questions
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                            @endif


                                            <div id="secdiv" class="pt-3"></div>

                                            <div class="row justify-content-end align-items-end ">
                                                <div class="col-md-9" id="add_section">
                                                    @if (@$layout == 'edit')
                                                        <button type="button" id="addsec" class="btn btn-success"
                                                            data-maxorder="{{ @$maxsectionorder }}">
                                                            Add Section
                                                        </button>
                                                    @else
                                                        <button type="button" id="addsec" class="btn btn-success"
                                                            data-maxorder="{{ @$maxsectionorder }}">
                                                            Add Section
                                                        </button>
                                                    @endif

                                                </div>
                                                <div class="col-md-3 mt-3 total_mark">
                                                    <label for="totalmark">Total Mark</label>
                                                    <input type="text" id="totalmark" readonly
                                                        value="{{ @$total_mark }}" class="form-control ques_ip"
                                                        onkeyup="totalvalidate();"
                                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" />
                                                </div>
                                                <div class="col-md-1"></div>
                                            </div>
                                        </div>
                                        <input type="button" name="previous" class="previous action-button-previous btn"
                                        value="Back" />
                                        {{-- <input type="button" name="previous" class="previous action-button-previous btn"
                                            value="Back" /> --}}


                                        {{ Form::button('<i class="fa fa-eye"></i>&nbsp;Preview', ['type' => 'button', 'id' => 'submit_btn_', 'name' => 'preview', 'value' => 'preview', 'class' => 'btn btn-dark  prevbtn action-button']) }}

                                    </fieldset>

                                </div>

                            </div>

                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    {{ Form::close() }}
@endsection

@section('scripts')



    <script type="module">
        @if (@$layout == 'edit')
            $(".remove_stu").on("click", function() {
                $(this).closest('.stu_box').remove();
            });
        @endif



        function notify_script(title, text, type, hide) {
            new PNotify({
                title: title,
                text: text,
                type: type,
                hide: hide,
                styling: 'bootstrap3'
            })
        }
        window.subjecturl = "{{ route('subject.index') }}";
        window.sectionurl = "{{ route('section.index') }}";
        window.fetchstudents = "{{ route('exam.index') }}"
        window.deletequestion = "{{ route('exam.deletequestion') }}"
        window.deletesection = "{{ route('exam.deletesection') }}"
        window.examtitleexists = "{{ route('examTitleexist') }}"

        ExamConfig.examinit(notify_script);
        AcademicYearConfig.AcademicyearInit();
    </script>

    <script>
        $(document).ready(function() {
         
            $('.file-select-button').click(function() {
                $('.chooseFile').click(); // Trigger click event on file input when button is clicked
            });

            $('.chooseFile').on('change', function (e) {
                var filename = $(this).val().split('\\').pop(); // Get the file name

                if (filename) {
                    var file = e.target.files[0]; // Get the selected file
                    var reader = new FileReader(); // Initialize a FileReader object
                    var input = this; // Store reference to the input element

                    reader.onload = function(e) {
                        var imageUrl = e.target.result; // Get the data URL of the image
                        // Find the closest .row and then the .redcol .file image, then update the src
                        var img = $(input).closest('.row').find('.redcol .file');
                        img.attr('src', imageUrl); // Set the src attribute of the <img> element
                    };

                    reader.readAsDataURL(file); // Read the file as a data URL
                    $(".file-upload").removeClass('active');
                    $("#noFile").text(filename); // Set the file name in the custom input field
                } else {
                    $(".file-upload").addClass('active');
                    $("#noFile").text("No file chosen");
                }
            });

        
       
            $("#homeworksection").hide();
            window.examtype = "offline";
            window.homework = false;
            $('select[name="type_of_exam"]').on("change", function() {
                let val = $(this).val();
                window.examtype = val;

                if (val == "online") {
                    $("#subquestion").hide();
                } else {
                    $("#subquestion").show();
                }
            });
            $('select[name="examtype"]').on("change", function() {
                var selectedText = $(this).find(":selected").text().toLowerCase();
                if(selectedText == "home work" )  {
                    $("#homeworksection").show();
                    $("#initialbutton").hide();
                    $("#type_of_exam_container").hide();
                    $(".prevbtn").hide();
                    $("#add_section").hide();
                    $("#is_homework").val(1);
                    $("#is_admission").val(0);
                    $('.total_mark').hide();
                    $('.remove_section').hide();
                    $('.sectotalmark').hide();
                    $('.sectioncol').css('width', '100%');
                    window.homework = true;
                } 
                else if(selectedText.includes("admission")){
                    window.examtype = "admission";
                    $("#homeworksection").hide();
                    $("#initialbutton").show();
                    $("#type_of_exam_container").hide();
                    $(".prevbtn").show();
                    $("#add_section").show();
                    // $(".shortqus").hide();
                    // $(".longqus").hide();
                    $("#is_admission").val(1);
                    $("#is_homework").val(0);
                    window.homework = false;  
                }
                else {
                    $("#homeworksection").hide();
                    $("#initialbutton").show();
                    $("#type_of_exam_container").show();
                    $(".prevbtn").show();
                    $("#add_section").show();
                    $("#is_homework").val(0);
                    $("#is_admission").val(0);
                    window.homework = false;                                  
                }
            });
           
            var current_fs, next_fs, previous_fs;
            var opacity;
            var current = 1;
            var steps = $("fieldset").length;
            var layouttype = @json(@$layout);
            var examid = @json(@$data->id);

            setProgressBar(current);

            $(".next").click(async function() {

                var form = $("#exam-form");

                form.validate();

                var result = await ExamConfig.CheckExamtitleExist("examtitle", layouttype, examid);


                //form.valid() === true && !result
                if (true) {

                    $(".mark_title_details").html("");
                    current_fs = $(this).closest('fieldset');
                    next_fs = $(this).closest('fieldset').next();

                    $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

                    next_fs.show();
                    current_fs.animate({
                        opacity: 0
                    }, {
                        step: function(now) {
                            opacity = 1 - now;

                            $("#progressbar li").eq($("fieldset").index(current_fs))
                                .addClass("finish");
                            current_fs.css({
                                'display': 'none',
                                'position': 'relative'
                            });
                            next_fs.css({
                                'opacity': opacity
                            });

                        },
                        duration: 500
                    });
                    setProgressBar(++current);
                }


            });

            $(".previous").click(function() {

                current_fs = $(this).parent();
                previous_fs = $(this).parent().prev();

                $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

                previous_fs.show();

                current_fs.animate({
                    opacity: 0
                }, {
                    step: function(now) {
                        opacity = 1 - now;

                        $("#progressbar li").eq($("fieldset").index(previous_fs)).removeClass(
                            "finish");
                        current_fs.css({
                            'display': 'none',
                            'position': 'relative'
                        });
                        previous_fs.css({
                            'opacity': opacity
                        });
                    },
                    duration: 500
                });
                setProgressBar(--current);
            });

            function setProgressBar(curStep) {
                var percent = parseFloat(100 / steps) * curStep;
                percent = percent.toFixed();
                $(".progress-bar").css("width", percent + "%")
            }

            $('input[type="submit"]').click(function() {
                return false;
            })

        });
        $('.addquestionHomework').on(
            "click",
            function() {

                ordercount++;
                var idqus = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);
                var idans = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);
                var idmark = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);
                var element = $("#qusdiv .tab_row").last().find(".section_id");
                var section_id =  element.attr("data-section") ?? 1;
                var order =  element.attr("data-order");
                console.log(section_id,order,element);
                updateIndex = function() {
                    $(".inc_span").each(function(i) {
                        $(this).html(i + 1);
                    });
                };
                $('.total_mark').hide();
                $('.remove_section').hide();
                $(this)
                    .closest(".secrow1")
                    .find("#qusdiv")
                    .append(
                        `<div class="tab_row mb-3"><div class="row align-items-end"><div class="col-md-9"><label for="${idqus}"><span><span class="inc_span"></span>&nbsp;.&nbsp;</span>Attach File </label><input type="file" required name="section[section${section_id}st][questions][homework][${order ? parseInt(order) + 1 : ordercount}][image][]" id="${idans}" class=" form-control ques_ip size-img section_id" data-section = "${section_id}" data-order = "${order ? parseInt(order) + 1 : ordercount}" style="margin-bottom:10px" accept=".docx,.pdf,.jpeg,.gif,.jpg,.png," /><p class="text-danger error_msg"></p><textarea name="section[section${section_id}st][questions][homework][${order ? parseInt(order) + 1 : ordercount}][]" id="${idqus}" required class="form-control ques_ip" placeholder="Enter Description"></textarea>
                </div><div class="col-md-2"><label for="${idmark}"></label></div><div class="col-md-1"><button type="button" class="remove_ques btn btn-danger"><i class="fa fa-trash"></i></button></div></div></div>`
                    );
                updateIndex();
                findTotal();

                $(".remove_ques").on("click", function() {
                    updateIndex = function() {
                        $(" .inc_span").each(function(i) {
                            $(this).html(i + 1);
                        });
                    };
                    $(this).closest(".tab_row").remove();
                    updateIndex();
                    findTotal2();
                });
            }
        );
    </script>


    <script>
        $(document).ready(function() {
            $(".hr-time-picker").hrTimePicker({
                disableColor: "#989c9c", // red, green, #000
                enableColor: "#ff5722", // red, green, #000
                arrowTopSymbol: "&#9650;", // ▲ -- Enter html entity code
                arrowBottomSymbol: "&#9660;" // ▼ -- Enter html entity code
            });
            $('.selectacc_yr').select2({
                placeholder: 'Select Academic Year',
                width: '100%',
                allowHtml: true,
                allowClear: false,
                tags: true,
                closeOnSelect: true,
                selectOnClose: false,
            });

            $('.selectacc_term').select2({
                placeholder: 'Select Academic Term',
                width: '100%',
                allowHtml: true,
                allowClear: false,
                tags: true,
                closeOnSelect: true,
                selectOnClose: false,
            });

            $('.selectexam_type').select2({
                placeholder: 'Select Exam Type',
                width: '100%',
                allowHtml: true,
                allowClear: false,
                tags: true,
                closeOnSelect: true,
                selectOnClose: false,
            });

            $('.select_cls').select2({
                placeholder: 'Select Class',
                width: '100%',
                allowHtml: true,
                allowClear: false,
                tags: true,
                closeOnSelect: true,
                selectOnClose: false,
            });

            $('.selectsec').select2({
                placeholder: 'Select Section',
                width: '100%',
                allowHtml: true,
                allowClear: false,
                tags: true,
                closeOnSelect: true,
                selectOnClose: false,
            });

            $('.selectdep').select2({
                placeholder: 'Select Department',
                width: '100%',
                allowHtml: true,
                allowClear: false,
                tags: true,
                closeOnSelect: true,
                selectOnClose: false,
            });

            $('.selectsub').select2({
                placeholder: 'Select Subject',
                width: '100%',
                allowHtml: true,
                allowClear: false,
                tags: true,
                closeOnSelect: true,
                selectOnClose: false,
            });

            $('.proexam').select2({
                width: '100%',
                allowHtml: true,
                allowClear: false,
                tags: true,
                closeOnSelect: true,
                selectOnClose: false,
            });

        });
    </script>

    <script>
      $(document).ready(function(){
    var check = $("#stu_examsd").is(":checked") ? "1" : "0";
    if (check == "1") {
        $("#exsdatediv").show();
        $("#exsdate").show();
        $("#exstime").show();
    } else {
        $("#exsdatediv").hide();
        $("#exsdate").hide();
        $("#exstime").hide();
    }
});

$("#stu_examsd").click(function() {
    var check = $("#stu_examsd").is(":checked") ? "1" : "0";
    if (check == "1") {
        $("#exsdatediv").show();
        $("#exsdate").show();
        $("#exstime").show();
    } else {
        $("#exsdatediv").hide();
        $("#exsdate").hide();
        $("#exstime").hide();
    }
});

        $("#exsdate").datepicker({
            numberOfMonths: 1,
            minDate: 0
        });



        // window.onload = function() {

        //     $("#exsdate").hide();
        // };
    </script>

    <script>
        $(document).ready(function() {

            $(document).on("click", "#exclude_stu", function() {
                // @if (@$layout == 'edit')
                // window.students_exclude={!! json_encode(@$exclude_students) !!};
                // window.students_include={!! json_encode(@$include_students) !!};
                // @endif



                if (typeof window.students_exclude === "undefined") {


                    var arr_ = Object.entries({!! json_encode(@$exclude_students) !!}).map((data, i) =>
                        `<option value="${data[0]}">${data[1]}</option>`

                    ).join("");

                } else {
                    var arr_ = window.students_exclude.map((data) =>
                        `<option value="${data?.id}">${data?.text}</option>`

                    ).join("");
                }


                $("#stu_exclude").append(`0
                                            <select class="form-control exc_stu" id="exam_type_" name="exclude_students[]">
                                                ${arr_}
                                            </select>
                                            <button type="button" class="btn remove_stu">&times;</button>
                                        </div>`);

                $(".remove_stu").on("click", function() {
                    $(this).closest('.stu_box').remove();
                });

                $('.exc_stu').select2({
                    placeholder: 'Student',
                    width: '100%',
                    allowHtml: true,
                    allowClear: false,
                    tags: false,
                    closeOnSelect: true,
                    selectOnClose: false,

                });
                $('#stu_exclude b[role="presentation"]').hide();

            });

            $("#stu_excludecb").click(function() {
                if ($(this).is(":checked")) {
                    $("#exclude_stu").show();
                    $("#stu_exclude").show();
                } else {
                    $("#exclude_stu").hide();
                    $("#stu_exclude").hide();
                    $("#stu_exclude div").remove();
                }
            });

        });
    </script>

    <script>
        $(document).ready(function() {

            $(document).on("click", "#include_stu", function() {


                if (typeof window.students_include === "undefined") {


                    var arr = Object.entries({!! json_encode(@$include_students) !!}).map((data, i) =>
                        `<option value="${data[0]}">${data[1]}</option>`

                    ).join("");

                } else {
                    var arr = window.students_include.map((data) =>
                        `<option value="${data?.id}">${data?.text}</option>`

                    ).join("");
                }


                console.log(window.students_exclude, window.students_include);


                $("#stu_include").append(`<div class="mb-4 position-relative stu_box">
                                            <select class="form-control inclu_stu" id="exam_type" name="include_students[]">

                                                ${arr}
                                            </select>
                                            <button type="button" class="btn remove_stu">&times;</button>
                                        </div>`);

                $(".remove_stu").on("click", function() {
                    $(this).closest('.stu_box').remove();
                });

                $('.inclu_stu').select2({
                    placeholder: 'Student',
                    width: '100%',
                    allowHtml: true,
                    allowClear: false,
                    tags: false,
                    closeOnSelect: true,
                    selectOnClose: false,

                });
                $('#stu_include b[role="presentation"]').hide();

            });

            $("#stu_includecb").click(function() {
                if ($(this).is(":checked")) {
                    $("#include_stu").show();
                    $("#stu_include").show();
                } else {
                    $("#include_stu").hide();
                    $("#stu_include").hide();
                    $("#stu_include div").remove();
                }
            });

        });
    </script>

    <script>
        $(document).ready(function() {
            $("#examdate").datepicker({
                numberOfMonths: 1,
                minDate: 0
            });

            $('.examtimeline').select2({
                placeholder: 'Time Line',
                width: '100%',
                allowHtml: true,
                allowClear: false,
                tags: false,
                closeOnSelect: true,
                selectOnClose: false,
            });
            $("#notifydate").datepicker({
                numberOfMonths: 1,
                minDate: 0
            });
            $("#exsdate").datepicker({
                numberOfMonths: 1,
                minDate: 0
            });
          
            var exam_type = $('select[name="examtype"]').find(":selected").text().toLowerCase();
            if(exam_type == "home work"){
               
                    $("#homeworksection").show();
                    $("#initialbutton").hide();
                    $("#type_of_exam_container").hide();
                    $(".prevbtn").hide();
                    $("#add_section").hide();
                    $("#is_homework").val(1);
                    $("#is_admission").val(0);
                    $('.total_mark').hide();
                    $('.remove_section').hide();
                    $('.sectotalmark').hide();
                    $('.sectioncol').css('width', '100%');
                    $('.questionoptions1').removeClass('d-flex');
                    $('.questionoptions1').hide();

            } 

        });
    </script>


    // <!-- Questions script -->

    <script>
        function notify_script(title, text, type, hide) {
            new PNotify({
                title: title,
                text: text,
                type: type,
                hide: hide,
                styling: 'bootstrap3'
            })
        }

        function totalvalidate() {
            console.log("entering")
            var total = parseInt(document.getElementById("totalmark").value);
            var max = parseInt(document.getElementById("maxmark").value);
            if (total > max) {
                notify_script("error", "Total Mark must be lesser than or Equal to Maximum Mark.");
                $(".mark_cls").val(" ");
                $(".sectotalmarkinput").val(" ");
            }
        }



        function findTotal() {
            //   $("input[type='text'].mark_cls").keyup(function() {
            //   console.log("Key Up - " + $(this).attr("id"));
            // });
            $("input[type='text'].mark_cls").keyup(function(e) {

                console.log("finding k");
                var charCode = e.which ? e.which : event.keyCode;
                console.log(charCode);

                var arr = document.getElementsByClassName('mark_cls');
                var tot = 0;
                for (var i = 0; i < arr.length; i++) {
                    if (parseFloat(arr[i].value))
                        tot += parseFloat(arr[i].value);
                }

                var secarr = $(this).closest('.secrow').find('.mark_cls');
                var sectot = 0;
                for (var i = 0; i < secarr.length; i++) {
                    if (parseFloat(secarr[i].value))
                        sectot += parseFloat(secarr[i].value);
                }

                document.getElementById('totalmark').value = tot;
                $(this).closest('.secrow').find('#sectotalmark').val(sectot);
                totalvalidate();
            });
        }
    </script>
    
    <script>
        
        function findTotal() {
            //   $("input[type='text'].mark_cls").keyup(function() {
            //   console.log("Key Up - " + $(this).attr("id"));
            // });
            $("input[type='text'].mark_cls").keyup(function(e) {

                console.log("finding");
                this.value = this.value.replace(/[^0-9]/g, "");
                var charCode = e.which ? e.which : event.keyCode;
                console.log(charCode);
                if (String.fromCharCode(charCode).match(/[^0-9]/g)) {
                    console.log("yes");
                }


                var arr = document.getElementsByClassName('mark_cls');
                var tot = 0;
                for (var i = 0; i < arr.length; i++) {
                    if (parseFloat(arr[i].value))
                        tot += parseFloat(arr[i].value);
                }

                var secarr = $(this).closest('.secrow').find('.mark_cls');
                var sectot = 0;
                for (var i = 0; i < secarr.length; i++) {
                    if (parseFloat(secarr[i].value))
                        sectot += parseFloat(secarr[i].value);
                }

                document.getElementById('totalmark').value = tot;
                $(this).closest('.secrow').find('#sectotalmark').val(sectot);
                totalvalidate();
            });
        }

        function findTotal2() {
            console.log("yeah find total");
            var arr2 = document.getElementsByClassName("mark_cls");

            var tot2 = 0;
            for (var i = 0; i < arr2.length; i++) {
                if (parseFloat(arr2[i].value)) tot2 += parseFloat(arr2[i].value);
            }

            var secarr2 = $(" .mark_cls").closest(".secrow").find(".mark_cls");
            var sectot2 = 0;
            for (var i = 0; i < secarr2.length; i++) {
                if (parseFloat(secarr2[i].value))
                    sectot2 += parseFloat(secarr2[i].value);
            }

            document.getElementById("totalmark").value = tot2;
            $(" .mark_cls").closest(".secrow").find("#sectotalmark").val(sectot2);
            totalvalidate();
        }

        $(document).on("click", "#addquestion", function() {
            $(this).closest(".secrow").find(".questionoptions").show();
            console.log("addquestion");            
            if (window.examtype == "online") {
                $(this).closest(".secrow").find("#shortqus").hide();
                $(this).closest(".secrow").find("#longqus").hide();
            } 
            else if (window.examtype.includes("admission")) {
                $(this).closest(".secrow").find("#shortqus").hide();
                $(this).closest(".secrow").find("#longqus").hide();
            }
            else {
                $(this).closest(".secrow").find("#shortqus").show();
                $(this).closest(".secrow").find("#longqus").show();
            }
        });

        var ordercount = 0;

        @if (@$layout == 'edit' || @$type == 'duplicate')
            function findTotal2edit(unique) {
                console.log("yeah");
                var arr2 = document.getElementsByClassName(`${unique}`);
                console.log(arr2)
                var tot2 = 0;
                for (var i = 0; i < arr2.length; i++) {
                    if (parseFloat(arr2[i].value)) tot2 += parseFloat(arr2[i].value);
                }
                console.log(tot2);
                // return;

                var secarr2 = $(" ." + unique).closest(".secrow").find("." + unique);
                var sectot2 = 0;
                for (var i = 0; i < secarr2.length; i++) {
                    if (parseFloat(secarr2[i].value))
                        sectot2 += parseFloat(secarr2[i].value);
                }

                console.log($(" ." + unique).closest(".secrow").find("#sectotalmark"), sectot2);

                $(" ." + unique).closest(".secrow").find("#sectotalmark").val(sectot2);
                var t = document.querySelectorAll("#sectotalmark");

                var total_mark = 0;
                for (var i = 0; i < t.length; i++) {
                    if (parseFloat(t[i].value))
                        total_mark += parseFloat(t[i].value);
                }
                document.getElementById("totalmark").value = total_mark;
                totalvalidate();
            }

            function removeQuestionData() {
                $(".remove_ques").on("click", function() {

                    var unique = $(this).attr("data-class");
                    var section = $(this).attr("data-section");

                    console.log(unique);
                    $(this).closest(".tab_row").remove();
                    findTotal2edit(unique);

                    updateIndex = function() {
                        $(" .inc_span" + section).each(function(i) {
                            $(this).html(i + 1);
                        });
                    };

                    updateIndex();

                });
            }
            removeQuestionData();



            findTotal();

            function readIMG(input, imgControlName) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $(imgControlName).attr("src", e.target.result);
                        $(imgControlName).css("opacity", 1);
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }

            $(".imag").change(function() {
                var imgControlName = $(this)
                    .closest(".tab_row")
                    .find("#ImgPreview");
                readIMG(this, imgControlName);
            });

            function removeOption(identifier, unique, uniqueaddoption, type) {
                // $(document).on("click", "."+removeoption, function () {

                var unique_mark = $(identifier).attr("data-class");
                var element_data = document.querySelectorAll(".alph_box" + unique);
                var last_num_ = Number(element_data.length) - 2;

                console.log("remove", last_num_);

                console.log();
                document.getElementById(`${uniqueaddoption}`).setAttribute('data-last', last_num_)

                //$(`#btn${uniqueaddoption}`).attr("data-last",last_num);



                updateIndex = function() {


                    $(".alph_box" + unique).each(function(i) {
                        var isLastElement = i == element_data.length - 1;
                        $(this).html(String.fromCharCode(65 + i)); //String.fromCharCode(65 + i)


                        //console.log('last item',element_data.length);

                    });
                };


                $(identifier).closest(".opt").remove();


                updateIndex();

                if (type === "subquestion") {
                    findTotal2edit(unique_mark);
                }



            }

            function addOption(identifier, section_id, uniqueid, type) {

                // $(document).on("click", " #"+addoption, function () {

                $(identifier).data('id')
                var sectionNum = $(identifier).attr("data-id");
                var order = $(identifier).attr("data-order");
                var last = $(identifier).attr("data-last");
                var unique = $(identifier).attr("data-unique");
                console.log(unique);
                var appenddata;
                var optionrow;

                // console.log(last,"option");
                // return;

                var idoptionradionew = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);
                var idoptionnew = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);
                var removeoption = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);

                // updatealph = function () {
                //   $(this).closest('.tab_row .opt:last-child').find('.alph_box').html(String.fromCharCode(66 + Number(last)));
                // }
                console.log(Number(last));
                console.log(65 + (Number(last) + 1), "alpha");


                if (type == "choose")

                {
                    optionrow = "addoptionrow";
                    appenddata = `<div class="col-md-12 d-flex opt mt-2" >
                  <input type="radio" value="${Number(last) + 1}" name="section[section${section_id}st][questions][choose_best][${order}][answer][]" id="${idoptionradionew}" class="radio_cbox" /><span class="mt-2 mr_20 alph_box${unique}">${ String.fromCharCode(65 + (Number(last)+1))}</span>
                  <label class="col-10">
                    <input type="text" name="section[section${section_id}st][questions][choose_best][${order}][options][]" value="New Option" id="${idoptionnew}" class="form-control radio_txt" />
                  </label><i class="fa fa-times m-2 removeoption" onclick="removeOption(this,'${unique}','${uniqueid}')" >
                </div>`

                } else {
                    optionrow = "addoptionrowsub";

                    appenddata = `<div class="col-md-12 pr-0 d-flex opt">
            <div class="col-md-9 mb-2" style="margin-right:10px">
              <div class="w-100" style="display:flex">
                <span class="mt-2 mr_20 alph_box${unique}">${ String.fromCharCode(65 + (Number(last)+1))}</span>
                <textarea name="section[section${section_id}st][questions][sub_ques][${order}][]" id="${idoptionnew}" required class="form-control radio_txt" ></textarea>
               
              </div>
            </div>

            <div class="col-md-2" style="margin-right:10px">
             
                  <input
                    type="text"
                    name="section[section${section_id}st][questions][sub_ques][${order}][mark][]"
                    required
                    id="${idoptionradionew}"
                    class="form-control ques_ip mark_cls"
                  />
                </div>
                <div class="col-md-1 removesub">
                  <label for=""></label>
                  <i class="fa fa-times m-2 removeoption " onclick="removeOption(this,'${unique}','${uniqueid}')"></i>
                </div>
            </div>

          </div>`
                }

                $(identifier)
                    .closest(".tab_row")
                    .find("#" + optionrow).append(appenddata);
                // updatealph();

                // $('.alph_box').closest('.tab_row .opt:last-child').find('.alph_box').html(String.fromCharCode(66 + Number(last)));
                $(identifier).attr("data-last", Number(last) + 1);
                // $(this).attr("data-last",+last+1);
                findTotal();



            }

            function addQuestion(element, id, section_id, type, last, uniqueclass = null) {
                //   Qus 1 script
                console.log(section_id, type, last);
                var element_ = $(`#${id}`);

                var questiondiv = $(`.1stsectionques${section_id}`);

                var hiddenorder = Number($(`#hiddenorder${section_id}`).val()) + 1;

                $(`#hiddenorder${section_id}`).val(hiddenorder);

                switch (type) {
                    case "homework":

                        console.log("homework clicked");
                    case "fill":


                        //ordercount++;
                        var idqus = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var idans = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var idmark = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);

                        updateIndex = function() {
                            $(`.inc_span${section_id}`).each(function(i) {
                                $(this).html(i + 1);
                            });
                        };

                        element_
                            .closest(".secrow")
                            .find("#qusdiv")
                            .append(
                                `<div class="tab_row mb-3"><div class="row align-items-end"><div class="col-md-9"><label for="${idqus}"><span><span class="inc_span${section_id}"></span>&nbsp;.&nbsp;</span>Question </label><textarea name="section[section${section_id}st][questions][fillblanks][${hiddenorder}][]" id="${idqus}" required class="form-control ques_ip" ></textarea>
                <label for="${idans}" class="mt-2">Answer</label><input type="text" required name="section[section${section_id}st][questions][fillblanks][${hiddenorder}][answer][]" id="${idans}" class="form-control ques_ip" /></div><div class="col-md-2"><label for="${idmark}">Mark</label><input type="text" name="section[section${section_id}st][questions][fillblanks][${hiddenorder}][mark][]" id="${idmark}" required class="form-control ques_ip mark_cls ${uniqueclass}" /></div><div class="col-md-1"><button data-class="${uniqueclass}" data-section="${section_id}" type="button" class="remove_ques btn btn-danger"><i class="fa fa-trash"></i></button></div></div></div>`
                            );
                        updateIndex();
                        findTotal();
                        removeQuestionData();
                        // $(".remove_ques").on("click", function () {
                        //   updateIndex = function () {
                        //     $(" .inc_span").each(function (i) {
                        //       $(this).html(i + 1);
                        //     });
                        //   };
                        //   $(this).closest(".tab_row").remove();
                        //   updateIndex();
                        //   findTotal2();
                        // });

                        break;

                    case "choose":

                        // ordercount++;
                        var idqus2 = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var idoptionradioname = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var idoptionradio1 = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var idoptionradio2 = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var idoptionradio3 = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var idoptionradio4 = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var idoption1 = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var idoption2 = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var idoption3 = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var idoption4 = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var idmark2 = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var choosediv = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var fileid = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var addoption = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var addoptionrow = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var removeoption = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);

                        updateIndex = function() {
                            $(`.inc_span${section_id}`).each(function(i) {
                                $(this).html(i + 1);
                            });
                        };

                        var image =
                            "{{ URL::to('/') }}/assets/docs/dummyimage.jpg";

                        element_.closest(".secrow").find("#qusdiv")
                            .append(`<div class="tab_row mb-3" id="${choosediv}"><div class="row"><div class="col-md-9"><div class="row"><div class="col-md-12"><label for="${idqus2}"><span><span class="inc_span${section_id}"></span>&nbsp;.&nbsp;</span>Question </label><textarea required name="section[section${section_id}st][questions][choose_best][${hiddenorder}][]" id="${idqus2}" class="form-control ques_ip" ></textarea></div></div><div class="row mt-3" id=${addoptionrow}><div class="col-md-12 pr-0 d-flex opt" ><input type="radio" name="section[section${section_id}st][questions][choose_best][${hiddenorder}][answer][]" id="${idoptionradio1}" value="0" class="radio_cbox" /><span class="mt-2 mr_20 alph_box">A</span><label class="col-10"><input type="text" name="section[section${section_id}st][questions][choose_best][${hiddenorder}][options][]" value="Option 1" id="${idoption1}" class="form-control radio_txt" /></label><i class="fa fa-times m-2 removeoption ${removeoption}" ></i></div></div><div class="row mt-3">
      											<div class="col-md-12">
      												<button type="button" id=${addoption} class="btn qus-btns" data-order="${hiddenorder}" data-id="1st" data-last="0"> Add Option</button>
      											</div>
      										</div></div><div class="col-md-3"><div class="row"><div class="imagesection">
      					<input type="file" style="display: none;" name="section[section${section_id}st][questions][choose_best][${hiddenorder}][image][]" id="${fileid}" placeholder="Choose the file" class="form-control file_ip imag size_img" accept=".docx,.pdf,.jpeg,.jpg,.png," />
      					<label for="${fileid}" class="imgprev"><img id="ImgPreview" src=${image} for="${fileid}"/></label>
                          <p class="text-danger error_msg"></p>
      					</div></div></div></div><div class="row align-items-end mt-3">
      						<div class="col-md-9"></div><div class="col-md-2"><label for="${idmark2}">Mark</label><input type="text" required name=section[section${section_id}st][questions][choose_best][${hiddenorder}][mark][]" id="${idmark2}" class="form-control ques_ip mark_cls ${uniqueclass}" /></div><div class="col-md-1"><button data-section="${section_id}" data-class="${uniqueclass}" type="button" style="margin-top: 23px;" class="remove_ques btn btn-danger"><i class="fa fa-trash"></i></button></div>
      						</div></div>`);
                        updateIndex();
                        findTotal();

                        function readIMG(input, imgControlName) {
                            if (input.files && input.files[0]) {
                                var reader = new FileReader();
                                reader.onload = function(e) {
                                    $(imgControlName).attr("src", e.target.result);
                                    $(imgControlName).css("opacity", 1);
                                };
                                reader.readAsDataURL(input.files[0]);
                            }
                        }

                        $(".imag").change(function() {
                            var imgControlName = $(this)
                                .closest(".tab_row")
                                .find("#ImgPreview");
                            readIMG(this, imgControlName);
                        });

                        $(document).on("click", "." + removeoption, function() {
                            console.log("remove");

                            var element_data = document.querySelectorAll("#" + addoptionrow + " .alph_box");
                            var last_ = element_data.length - 2;


                            $(`#${addoption}`).attr("data-last", last_);
                            updateIndex = function() {


                                $("#" + addoptionrow + " .alph_box").each(function(i) {
                                    var isLastElement = i == element_data.length - 1;
                                    $(this).html(String.fromCharCode(65 +
                                        i)); //String.fromCharCode(65 + i)


                                    console.log('last item', element_data.length);

                                });
                            };


                            $(this).closest(".opt").remove();


                            updateIndex();
                        });


                        $(document).on("click", " #" + addoption, function() {

                            var sectionNum = $(this).attr("data-id");
                            var order = $(this).attr("data-order");
                            var last = $(this).attr("data-last");

                            var idoptionradionew = Math.random()
                                .toString(36)
                                .replace(/[^a-z]+/g, "")
                                .substr(2, 10);
                            var idoptionnew = Math.random()
                                .toString(36)
                                .replace(/[^a-z]+/g, "")
                                .substr(2, 10);

                            // updatealph = function () {
                            //   $(this).closest('.tab_row .opt:last-child').find('.alph_box').html(String.fromCharCode(66 + Number(last)));
                            // }
                            console.log(Number(last));
                            console.log(65 + (Number(last) + 1), "alpha");

                            $(this)
                                .closest("#" + choosediv + ".tab_row")
                                .find("#" + addoptionrow).append(`<div class="col-md-12 d-flex opt mt-2" >
      												<input type="radio" value="${Number(last) + 1}" name="section[section${section_id}st][questions][choose_best][${order}][answer][]" id="${idoptionradionew}" class="radio_cbox" /><span class="mt-2 mr_20 alph_box">${ String.fromCharCode(65 + (Number(last)+1))}</span>
      												<label class="col-10">
      													<input type="text" name="section[section${section_id}st][questions][choose_best][${order}][options][]" value="New Option" id="${idoptionnew}" class="form-control radio_txt" />
      												</label><i class="fa fa-times m-2 removeoption ${removeoption}" >
      											</div>`);
                            // updatealph();

                            // $('.alph_box').closest('.tab_row .opt:last-child').find('.alph_box').html(String.fromCharCode(66 + Number(last)));
                            $(this).attr("data-last", Number(last) + 1);
                            // $(this).attr("data-last",+last+1);



                        });


                        // $(".remove_ques").on("click", function () {
                        //   updateIndex = function () {
                        //     $(" .inc_span").each(function (i) {
                        //       $(this).html(i + 1);
                        //     });
                        //   };
                        //   $(this).closest(".tab_row").remove();
                        //   updateIndex();
                        //   findTotal2();
                        // });
                        removeQuestionData();
                        break;

                    case "yes":
                        // hiddenorder++;

                        var idqus6 = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var idmark6 = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var radioname = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var fileid = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var image =
                            "{{ URL::to('/') }}/assets/docs/dummyimage.jpg";

                        updateIndex = function() {
                            $(`.inc_span${section_id}`).each(function(i) {
                                $(this).html(i + 1);
                            });
                        };

                        element_
                            .closest(".secrow")
                            .find("#qusdiv")
                            .append(
                                `<div class="tab_row mb-3"><div class="row"><div class="col-md-9"><label for="${idqus6}"><span><span class="inc_span${section_id}"></span>&nbsp;.&nbsp;</span>Question </label><textarea name="section[section${section_id}st][questions][yesorno][${hiddenorder}][]" id="${idqus6}" class="form-control ques_ip" ></textarea><div class="mt-2"><label class="mr-3">Answer</label><div class="d-flex mt-2"><input type="radio" checked value="0" name="section[section${section_id}st][questions][yesorno][${hiddenorder}][answer][]" id="yesans" class="radio_cbox" /><label><input type="text" name="section[section${section_id}st][questions][yesorno][${hiddenorder}][options][]" value="Yes" id="yestxtyes" class="form-control radio_txt" /></label></div><div class="d-flex mt-2"><input type="radio" name="section[section${section_id}st][questions][yesorno][${hiddenorder}][answer][]" value="1" id="noans" class="radio_cbox" /><label><input type="text" name="section[section${section_id}st][questions][yesorno][${hiddenorder}][options][]" value="No" name="section[section${section_id}st][questions][yesorno][${hiddenorder}][options][]" id="yestxtno" class="form-control radio_txt" /></label></div></div></div><div class="col-md-3"><div class="imagesection">
      <input type="file" style="display: none;" name="section[section${section_id}st][questions][yesorno][${hiddenorder}][image][]" id="${fileid}" placeholder="Choose the file" class="form-control file_ip imag size_img" accept=".docx,.pdf,.jpeg,.jpg,.png," />
      <label for="${fileid}" class="imgprev"><img id="ImgPreview" src=${image} for="${fileid}"/></label>
      <p class="text-danger error_msg"></p>
      </div></div></div><div class="row align-items-end mt-3"><div class="col-md-9"></div><div class="col-md-2"><label for="${idmark6}">Mark</label><input type="text" name="section[section${section_id}st][questions][yesorno][${hiddenorder}][mark][]" required id="${idmark6}" class="form-control mark_cls ${uniqueclass}" /></div><div class="col-md-1"><button type="button" data-section="${section_id}" data-class="${uniqueclass}" class="remove_ques btn btn-danger"><i class="fa fa-trash"></i></button></div></div></div>`
                            );
                        updateIndex();
                        findTotal();

                        function readIMG(input, imgControlName) {
                            if (input.files && input.files[0]) {
                                var reader = new FileReader();
                                reader.onload = function(e) {
                                    $(imgControlName).attr("src", e.target.result);
                                    $(imgControlName).css("opacity", 1);
                                };
                                reader.readAsDataURL(input.files[0]);
                            }
                        }

                        $(".imag").change(function() {
                            var imgControlName = $(this)
                                .closest(".tab_row")
                                .find("#ImgPreview");
                            readIMG(this, imgControlName);
                        });

                        // $(".remove_ques").on("click", function () {
                        //   updateIndex = function () {
                        //     $(" .inc_span").each(function (i) {
                        //       $(this).html(i + 1);
                        //     });
                        //   };
                        //   $(this).closest(".tab_row").remove();
                        //   updateIndex();
                        //   findTotal2();
                        // });

                        break;

                    case "short":
                        // hiddenorder++;
                        var idqus4 = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var idans4 = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var idmark4 = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);

                        updateIndex = function() {
                            $(`.inc_span${section_id}`).each(function(i) {
                                $(this).html(i + 1);
                            });
                        };

                        element_
                            .closest(".secrow")
                            .find("#qusdiv")
                            .append(
                                `<div class="tab_row mb-3"><div class="row align-items-end"><div class="col-md-9"><label for="${idqus4}"><span><span class="inc_span${section_id}"></span>&nbsp;.&nbsp;</span>Question </label><textarea required name="section[section${section_id}st][questions][shortques][${hiddenorder}][]" id="${idqus4}" class="form-control ques_ip" ></textarea></div><div class="col-md-2"><label for="${idmark4}">Mark</label><input type="text" name="section[section${section_id}st][questions][shortques][${hiddenorder}][mark][]" id="${idmark4}" required class="form-control ques_ip mark_cls ${uniqueclass}" /></div><div class="col-md-1"><button  data-class="${uniqueclass}" data-section="${section_id}" type="button" class="remove_ques btn btn-danger"><i class="fa fa-trash"></i></button></div></div></div>`
                            );
                        updateIndex();
                        findTotal();

                        // $(".remove_ques").on("click", function () {
                        //   updateIndex = function () {
                        //     $(" .inc_span").each(function (i) {
                        //       $(this).html(i + 1);
                        //     });
                        //   };
                        //   $(this).closest(".tab_row").remove();
                        //   updateIndex();
                        //   findTotal2();
                        // });
                        break;

                    case "long":
                        //ordercount++;
                        var idqus5 = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var idans5 = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var idmark5 = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);

                        updateIndex = function() {
                            $(`.inc_span${section_id}`).each(function(i) {
                                $(this).html(i + 1);
                            });
                        };

                        element_
                            .closest(".secrow")
                            .find("#qusdiv")
                            .append(
                                `<div class="tab_row mb-3"><div class="row align-items-end"><div class="col-md-9"><label for="${idqus5}"><span><span class="inc_span${section_id}"></span>&nbsp;.&nbsp;</span>Question </label><textarea required name="section[section${section_id}st][questions][longques][${hiddenorder}][]" id="${idqus5}" class="form-control ques_ip" ></textarea></div><div class="col-md-2"><label for="${idmark5}">Mark</label><input type="text" name="section[section${section_id}st][questions][longques][${hiddenorder}][mark][]" id="${idmark5}" required class="form-control ques_ip mark_cls ${uniqueclass}" /></div><div class="col-md-1"><button data-class="${uniqueclass}" data-section="${section_id}" type="button" class="remove_ques btn btn-danger"><i class="fa fa-trash"></i></button></div></div></div>`
                            );
                        updateIndex();
                        findTotal();

                        // $(".remove_ques").on("click", function () {
                        //   updateIndex = function () {
                        //     $(" .inc_span").each(function (i) {
                        //       $(this).html(i + 1);
                        //     });
                        //   };
                        //   $(this).closest(".tab_row").remove();
                        //   updateIndex();
                        //   findTotal2();
                        // });
                        break;


                    case "subquestion":
                        console.log("sub enter", last);

                        ordercount++;
                        var idqus2 = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var idoptionradioname = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var idoptionradio1 = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var idoptionradio2 = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var idoptionradio3 = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var idoptionradio4 = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var idoption1 = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var idoption2 = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var idoption3 = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var idoption4 = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var idmark2 = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var choosediv = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var fileid = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var addoption = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);

                        var addoption = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var addoptionrow = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var removeoption = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var removemarkoption = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);


                        updateIndex = function() {
                            $(`.inc_span${section_id}`).each(function(i) {
                                $(this).html(i + 1);
                            });
                        };

                        var image =
                            "{{ URL::to('/') }}/assets/docs/pdf.png";

                        element_.closest(".secrow").find("#qusdiv")
                            .append(`<div class="tab_row mb-3" id="${choosediv}">
                        <div class="row align-items-end">
                          <div class="col-md-12">
                            <div class="row mt-3" id=${addoptionrow}>

                              <div class="col-md-12 pr-0 d-flex ">
                                <div class="col-md-9 pr-0 d-flex">
                                  <div class="w-100">
                                    <label for="${idqus2}"><span><span class="inc_span${section_id}"></span>&nbsp;.&nbsp;</span>Question </label>

                                  </div>
                                </div>
                                <div class="col-md-3 text-center">

                                  <div class="row_">
                                    <div class="col-md-6">
                                      <label for="${idmark2}">Mark</label>
                                    </div>
                                  </div>
                                  </div>

                              </div>
                              <div class="col-md-12 pr-0 d-flex opt mb-2">

                                <div class="col-md-9" style="margin-right:10px">
                                  <div class="w-100" style="display:flex">
                                    <span class="mt-2 mr_20 alph_box">A</span>

                                    <textarea name="section[section${section_id}st][questions][sub_ques][${hiddenorder}][]" id="${idqus2}" required class="form-control radio_txt" ></textarea>

                                    
                                  </div>
                                </div>

                                <div class="col-md-2" style="margin-right:10px">
                                  <input
                                        type="text"
                                        name="section[section${section_id}st][questions][sub_ques][${hiddenorder}][mark][]"
                                        id="${idmark2}"
                                        required
                                        class="form-control ques_ip mark_cls ${last}"
                                      />
                                     
                                </div>
                                <div class="col-md-1 removesub">
                                  <label for=""></label>
                                  <i data-class="${last}" class="fa fa-times m-2 removeoption ${removeoption}"></i>
                                </div>

                              </div>

                            </div>
                            <div class="row mt-3">
                              <div class="col-md-12">
                                <button
                                  type="button"
                                  id=${addoption}
                                  data-last="0"
                                  class="btn qus-btns"
                                >
                                  Add Question
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="row align-items-end">
                          <div class="col-md-11"></div>

                          <div class="col-md-1">
                            <button
                              type="button"
                              style="margin-top: 23px"
                              data-section="${section_id}"
                              data-class="${uniqueclass}"
                              class="remove_ques btn btn-danger"
                            >
                              <i class="fa fa-trash"></i>
                            </button>
                          </div>
                        </div>
                      </div>`);
                        updateIndex();
                        findTotal();


                        function readIMG(input, imgControlName) {
                            if (input.files && input.files[0]) {
                                var reader = new FileReader();
                                reader.onload = function(e) {
                                    $(imgControlName).attr("src", e.target.result);
                                };
                                reader.readAsDataURL(input.files[0]);
                            }
                        }

                        $(".imag").change(function() {
                            var imgControlName = $(this)
                                .closest(".tab_row")
                                .find("#ImgPreview");
                            readIMG(this, imgControlName);
                        });

                        $(document).on("click", "." + removeoption, function() {

                            var dataclass = $(this).attr("data-class");

                            console.log(dataclass, "from");

                            var element_data = document.querySelectorAll("#" + addoptionrow + " .alph_box");
                            var last_ = element_data.length - 2;

                            $(`#${addoption}`).attr("data-last", last_);
                            updateIndex = function() {
                                $("#" + addoptionrow + " .alph_box").each(function(i) {
                                    $(this).html(String.fromCharCode(65 +
                                        i)); //String.fromCharCode(65 + i)
                                });
                            };
                            $(this).closest(".opt").remove();
                            updateIndex();
                            findTotal2edit(dataclass);
                        });

                        $(document).on("click", " #" + addoption, function() {

                            var last_ = $(this).attr("data-last");
                            var idoptionradionew = Math.random()
                                .toString(36)
                                .replace(/[^a-z]+/g, "")
                                .substr(2, 10);
                            var idoptionnew = Math.random()
                                .toString(36)
                                .replace(/[^a-z]+/g, "")
                                .substr(2, 10);

                            $(this)
                                .closest("#" + choosediv + ".tab_row")
                                .find(
                                    "#" + addoptionrow
                                ).append(` <div class="col-md-12 pr-0 d-flex opt">
            <div class="col-md-9 mb-2" style="margin-right:10px">
              <div class="w-100" style="display:flex">
                <span class="mt-2 mr_20 alph_box">${ String.fromCharCode(65 + (Number(last_)+1))}</span>
                <textarea name="section[section${section_id}st][questions][sub_ques][${hiddenorder}][]" id="${idoptionnew}" required class="form-control radio_txt" ></textarea>
               
              </div>
            </div>

            <div class="col-md-2" style="margin-right:10px">
             
                  <input
                    type="text"
                    name="section[section${section_id}st][questions][sub_ques][${hiddenorder}][mark][]"
                    required
                    id="${idoptionradionew}"
                    class="form-control ques_ip mark_cls ${last}"
                  />
                </div>
                <div class="col-md-1 removesub">
                  <label for=""></label>
                  <i data-class="${last}" class="fa fa-times m-2 removeoption ${removeoption}"></i>
                </div>
            </div>

          </div>`);
                            findTotal();
                            $(this).attr("data-last", Number(last_) + 1);
                            // findTotal();
                        });

                        $(".remove_ques").on("click", function() {
                            updateIndex = function() {
                                $(" .inc_span").each(function(i) {
                                    $(this).html(i + 1);
                                });
                            };

                            $(this).closest(".tab_row").remove();
                            updateIndex();
                            findTotal2();
                        });
                        break;
                }



            }
        @endif

        $(document).on(
            "click",
            "#" + "initialbutton" + " #subquestion",
            function() {
                console.log("here,sub");
                ordercount++;
                var idqus2 = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);
                var idoptionradioname = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);
                var idoptionradio1 = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);
                var idoptionradio2 = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);
                var idoptionradio3 = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);
                var idoptionradio4 = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);
                var idoption1 = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);
                var idoption2 = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);
                var idoption3 = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);
                var idoption4 = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);
                var idmark2 = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);
                var choosediv = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);
                var fileid = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);
                var addoption = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);

                var addoption = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);
                var addoptionrow = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);
                var removeoption = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);

                updateIndex = function() {
                    $(".inc_span").each(function(i) {
                        $(this).html(i + 1);
                    });
                };

                var image =
                    "{{ URL::to('/') }}/assets/docs/pdf.png";

                $(this).closest(".secrow").find("#qusdiv")
                    .append(`<div class="tab_row mb-3" id="${choosediv}">
                        <div class="row align-items-end">
                          <div class="col-md-12">
                            <div class="row mt-3" id=${addoptionrow}>

                              <div class="col-md-12 pr-0 d-flex ">
                                <div class="col-md-9 pr-0 d-flex">
                                  <div class="w-100">
                                    <label for="${idqus2}"><span><span class="inc_span"></span>&nbsp;.&nbsp;</span>Question </label>

                                  </div>
                                </div>
                                <div class="col-md-3 text-center">

                                  <div class="row_">
                                    <div class="col-md-6">
                                      <label for="${idmark2}">Mark</label>
                                    </div>
                                  </div>
                                  </div>

                              </div>
                              <div class="col-md-12 pr-0 d-flex opt mb-2">

                                <div class="col-md-9" style="margin-right:10px">
                                  <div class="w-100" style="display:flex">
                                    <span class="mt-2 mr_20 alph_box">A</span>

                                    <textarea name="section[section1st][questions][sub_ques][${ordercount}][]" id="${idqus2}" required class="form-control radio_txt" ></textarea>

                                    
                                  </div>
                                </div>

                                <div class="col-md-2" style="margin-right:10px">
                                  <input
                                        type="text"
                                        name="section[section1st][questions][sub_ques][${ordercount}][mark][]"
                                        id="${idmark2}"
                                        required
                                        class="form-control ques_ip mark_cls"
                                      />
                                     
                                </div>
                                <div class="col-md-1 removesub">
                                  <label for=""></label>
                                  <i class="fa fa-times m-2 removeoption ${removeoption}"></i>
                                </div>

                              </div>

                            </div>
                            <div class="row mt-3">
                              <div class="col-md-12">
                                <button
                                  type="button"
                                  id=${addoption}
                                  data-last="0"
                                  class="btn qus-btns"
                                >
                                  Add Question
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="row align-items-end">
                          <div class="col-md-11"></div>

                          <div class="col-md-1">
                            <button
                              type="button"
                              style="margin-top: 23px"
                              class="remove_ques btn btn-danger"
                            >
                              <i class="fa fa-trash"></i>
                            </button>
                          </div>
                        </div>
                      </div>`);
                updateIndex();
                findTotal();

                function readIMG(input, imgControlName) {
                    if (input.files && input.files[0]) {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            $(imgControlName).attr("src", e.target.result);
                        };
                        reader.readAsDataURL(input.files[0]);
                    }
                }

                $(".imag").change(function() {
                    var imgControlName = $(this)
                        .closest(".tab_row")
                        .find("#ImgPreview");
                    readIMG(this, imgControlName);
                });

                $(document).on("click", "." + removeoption, function() {

                    var element_data = document.querySelectorAll("#" + addoptionrow + " .alph_box");
                    var last_ = element_data.length - 2;

                    $(`#${addoption}`).attr("data-last", last_);
                    updateIndex = function() {
                        $("#" + addoptionrow + " .alph_box").each(function(i) {
                            $(this).html(String.fromCharCode(65 + i)); //String.fromCharCode(65 + i)
                        });
                    };
                    $(this).closest(".opt").remove();
                    updateIndex();
                    findTotal2();
                });

                $(document).on("click", " #" + addoption, function() {

                    console.log("yes optionclicked");
                    var last = $(this).attr("data-last");
                    var idoptionradionew = Math.random()
                        .toString(36)
                        .replace(/[^a-z]+/g, "")
                        .substr(2, 10);
                    var idoptionnew = Math.random()
                        .toString(36)
                        .replace(/[^a-z]+/g, "")
                        .substr(2, 10);

                    $(this)
                        .closest("#" + choosediv + ".tab_row")
                        .find(
                            "#" + addoptionrow
                        ).append(` <div class="col-md-12 pr-0 d-flex opt">
            <div class="col-md-9 mb-2" style="margin-right:10px">
              <div class="w-100" style="display:flex">
                <span class="mt-2 mr_20 alph_box">${ String.fromCharCode(65 + (Number(last)+1))}</span>
                <textarea name="section[section1st][questions][sub_ques][${ordercount}][]" id="${idoptionnew}" required class="form-control radio_txt" ></textarea>
               
              </div>
            </div>

            <div class="col-md-2" style="margin-right:10px">
             
                  <input
                    type="text"
                    name="section[section1st][questions][sub_ques][${ordercount}][mark][]"
                    required
                    id="${idoptionradionew}"
                    class="form-control ques_ip mark_cls"
                  />
                </div>
                <div class="col-md-1 removesub">
                  <label for=""></label>
                  <i class="fa fa-times m-2 removeoption ${removeoption}"></i>
                </div>
            </div>

          </div>`);
                    $(this).attr("data-last", Number(last) + 1);
                    findTotal();
                });

                $(".remove_ques").on("click", function() {
                    updateIndex = function() {
                        $(" .inc_span").each(function(i) {
                            $(this).html(i + 1);
                        });
                    };

                    $(this).closest(".tab_row").remove();
                    updateIndex();
                    findTotal2();
                });
            }
        );

        $(document).on(
            "click",
            "#" + "homeworksection" + " #addquestionHomework1",
            function() {

                ordercount++;
                var idqus = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);
                var idans = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);
                var idmark = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);

                updateIndex = function() {
                    $(".inc_span").each(function(i) {
                        $(this).html(i + 1);
                    });
                };
                $('.total_mark').hide();
                $('.remove_section').hide();
                $(this)
                    .closest(".secrow")
                    .find("#qusdiv")
                    .append(
                        `<div class="tab_row mb-3"><div class="row align-items-end"><div class="col-md-9"><label for="${idqus}"><span><span class="inc_span"></span>&nbsp;.&nbsp;</span>Attach File </label><input type="file" required name="section[section1st][questions][homework][${ordercount}][image][]" id="${idans}" class=" form-control ques_ip size_img tex_img" style="margin-bottom:10px" accept=".docx,.pdf,.jpeg,.jpg,.png,"  /><p class="text-danger error_msg"></p><textarea name="section[section1st][questions][homework][${ordercount}][]" id="${idqus}" required class="form-control ques_ip" placeholder="Enter Description"></textarea>
                </div><div class="col-md-2"><label for="${idmark}"></label></div><div class="col-md-1"><button type="button" class="remove_ques btn btn-danger"><i class="fa fa-trash"></i></button></div></div></div>`
                    );
                updateIndex();
                findTotal();

                $(".remove_ques").on("click", function() {
                    updateIndex = function() {
                        $(" .inc_span").each(function(i) {
                            $(this).html(i + 1);
                        });
                    };
                    $(this).closest(".tab_row").remove();
                    updateIndex();
                    findTotal2();
                });
            }
        );

        //   Qus 1 script
        $(document).on(
            "click",
            "#" + "initialsection" + " #fillintheblanks",
            function() {

                ordercount++;
                var idqus = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);
                var idans = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);
                var idmark = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);

                updateIndex = function() {
                    $(".inc_span").each(function(i) {
                        $(this).html(i + 1);
                    });
                };

                $(this)
                    .closest(".secrow")
                    .find("#qusdiv")
                    .append(
                        `<div class="tab_row mb-3"><div class="row align-items-end"><div class="col-md-9"><label for="${idqus}"><span><span class="inc_span"></span>&nbsp;.&nbsp;</span>Question </label><textarea name="section[section1st][questions][fillblanks][${ordercount}][]" id="${idqus}" required class="form-control ques_ip" ></textarea>
                <label for="${idans}" class="mt-2">Answer</label><input type="text" required name="section[section1st][questions][fillblanks][${ordercount}][answer][]" id="${idans}" class="form-control ques_ip" /></div><div class="col-md-2"><label for="${idmark}">Mark</label><input type="text" name="section[section1st][questions][fillblanks][${ordercount}][mark][]" id="${idmark}" required class="form-control ques_ip mark_cls" /></div><div class="col-md-1"><button type="button" class="remove_ques btn btn-danger"><i class="fa fa-trash"></i></button></div></div></div>`
                    );
                updateIndex();
                findTotal();

                $(".remove_ques").on("click", function() {
                    updateIndex = function() {
                        $(" .inc_span").each(function(i) {
                            $(this).html(i + 1);
                        });
                    };
                    $(this).closest(".tab_row").remove();
                    updateIndex();
                    findTotal2();
                });
            }
        );

        //initial section 2nd question

        // Qus 2 script
        $(document).on(
            "click",
            "#" + "initialsection" + " #choosethebestans",
            function() {

                ordercount++;
                var idqus2 = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);
                var idoptionradioname = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);
                var idoptionradio1 = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);
                var idoptionradio2 = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);
                var idoptionradio3 = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);
                var idoptionradio4 = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);
                var idoption1 = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);
                var idoption2 = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);
                var idoption3 = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);
                var idoption4 = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);
                var idmark2 = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);
                var choosediv = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);
                var fileid = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);
                var addoption = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);
                var addoptionrow = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);
                var removeoption = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);

                updateIndex = function() {
                    $(".inc_span").each(function(i) {
                        $(this).html(i + 1);
                    });
                };

                var image =
                    "{{ URL::to('/') }}/assets/docs/dummyimage.jpg";

                $(this).closest(".secrow").find("#qusdiv")
                    .append(`<div class="tab_row mb-3" id="${choosediv}"><div class="row"><div class="col-md-9"><div class="row"><div class="col-md-12"><label for="${idqus2}"><span><span class="inc_span"></span>&nbsp;.&nbsp;</span>Question </label><textarea required name="section[section1st][questions][choose_best][${ordercount}][]" id="${idqus2}" class="form-control ques_ip" ></textarea></div></div><div class="row mt-3" id=${addoptionrow}><div class="col-md-12 pr-0 d-flex opt" ><input type="radio" name="section[section1st][questions][choose_best][${ordercount}][answer][]" id="${idoptionradio1}" value="0" class="radio_cbox" /><span class="mt-2 mr_20 alph_box">A</span><label class="col-10"><input type="text" name="section[section1st][questions][choose_best][${ordercount}][options][]" value="Option 1" id="${idoption1}" class="form-control radio_txt" /></label><i class="fa fa-times m-2 removeoption ${removeoption}" ></i></div></div><div class="row mt-3">
      											<div class="col-md-12">
      												<button type="button" id=${addoption} class="btn qus-btns" data-order="${ordercount}" data-id="1st" data-last="0"> Add Option</button>
      											</div>
      										</div></div><div class="col-md-3"><div class="row"><div class="imagesection">
      					<input type="file" style="display: none;" name="section[section1st][questions][choose_best][${ordercount}][image][]" id="${fileid}" placeholder="Choose the file" class="form-control file_ip imag size_img" accept=".docx,.pdf,.jpeg,.jpg,.png," />
      					<label for="${fileid}" class="imgprev"><img id="ImgPreview" src=${image} for="${fileid}"/><video src="" controls id="VedioPreview" style="width:215px;display:none" for="${fileid}"></video></label>
                          <p class="text-danger error_msg"></p>
      					</div></div></div></div><div class="row align-items-end mt-3">
      						<div class="col-md-9"></div><div class="col-md-2"><label for="${idmark2}">Mark</label><input type="text" required name=section[section1st][questions][choose_best][${ordercount}][mark][]" id="${idmark2}" class="form-control ques_ip mark_cls" /></div><div class="col-md-1"><button type="button" style="margin-top: 23px;" class="remove_ques btn btn-danger"><i class="fa fa-trash"></i></button></div>
      						</div></div>`);
                updateIndex();
                findTotal();

                function readIMG(input, imgControlName, vedioControlName = null) {
                    if (input.files && input.files[0]) {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            var fileType = input.files[0].type.split('/')[0];
                            if (fileType === 'image') {
                                $(vedioControlName).hide();
                                $(imgControlName).attr("src", e.target.result);
                                $(imgControlName).css("opacity", 1);
                            } else if (fileType === 'video') {
                                $(imgControlName).hide();
                                $(vedioControlName).show();
                                $(vedioControlName).attr("src", e.target.result);
                                $(vedioControlName).attr("controls", "controls");
                            }

                        };
                        reader.readAsDataURL(input.files[0]);
                    }
                }

                $(".imag").change(function() {
                    var imgControlName = $(this)
                        .closest(".tab_row")
                        .find("#ImgPreview");
                    var vedioControlName = $(this)
                        .closest(".tab_row")
                        .find("#VedioPreview");
                    readIMG(this, imgControlName, vedioControlName);
                });

                $(document).on("click", "." + removeoption, function() {
                    console.log("remove");

                    var element_data = document.querySelectorAll("#" + addoptionrow + " .alph_box");
                    var last_ = element_data.length - 2;


                    $(`#${addoption}`).attr("data-last", last_);
                    updateIndex = function() {


                        $("#" + addoptionrow + " .alph_box").each(function(i) {
                            var isLastElement = i == element_data.length - 1;
                            $(this).html(String.fromCharCode(65 + i)); //String.fromCharCode(65 + i)


                            console.log('last item', element_data.length);

                        });
                    };


                    $(this).closest(".opt").remove();


                    updateIndex();
                });


                $(document).on("click", " #" + addoption, function() {

                    var sectionNum = $(this).attr("data-id");
                    var order = $(this).attr("data-order");
                    var last = $(this).attr("data-last");

                    var idoptionradionew = Math.random()
                        .toString(36)
                        .replace(/[^a-z]+/g, "")
                        .substr(2, 10);
                    var idoptionnew = Math.random()
                        .toString(36)
                        .replace(/[^a-z]+/g, "")
                        .substr(2, 10);

                    // updatealph = function () {
                    //   $(this).closest('.tab_row .opt:last-child').find('.alph_box').html(String.fromCharCode(66 + Number(last)));
                    // }
                    console.log(Number(last));
                    console.log(65 + (Number(last) + 1), "alpha");

                    $(this)
                        .closest("#" + choosediv + ".tab_row")
                        .find("#" + addoptionrow).append(`<div class="col-md-12 d-flex opt mt-2" >
      												<input type="radio" value="${Number(last) + 1}" name="section[section${sectionNum}][questions][choose_best][${order}][answer][]" id="${idoptionradionew}" class="radio_cbox" /><span class="mt-2 mr_20 alph_box">${ String.fromCharCode(65 + (Number(last)+1))}</span>
      												<label class="col-10">
      													<input type="text" name="section[section${sectionNum}][questions][choose_best][${order}][options][]" value="New Option" id="${idoptionnew}" class="form-control radio_txt" />
      												</label><i class="fa fa-times m-2 removeoption ${removeoption}" >
      											</div>`);
                    // updatealph();

                    // $('.alph_box').closest('.tab_row .opt:last-child').find('.alph_box').html(String.fromCharCode(66 + Number(last)));
                    $(this).attr("data-last", Number(last) + 1);
                    // $(this).attr("data-last",+last+1);



                });


                $(".remove_ques").on("click", function() {
                    updateIndex = function() {
                        $(" .inc_span").each(function(i) {
                            $(this).html(i + 1);
                        });
                    };
                    $(this).closest(".tab_row").remove();
                    updateIndex();
                    findTotal2();
                });
            }
        );

        //initial section 3rd question

        // Qus 3 script
        $(document).on(
            "click",
            "#" + "initialsection" + " #yesornoqus",
            function() {
                ordercount++;

                var idqus6 = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);
                var idmark6 = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);
                var radioname = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);
                var fileid = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);
                var image =
                    "{{ URL::to('/') }}/assets/docs/dummyimage.jpg";

                updateIndex = function() {
                    $(".inc_span").each(function(i) {
                        $(this).html(i + 1);
                    });
                };

                $(this)
                    .closest(".secrow")
                    .find("#qusdiv")
                    .append(
                        `<div class="tab_row mb-3"><div class="row"><div class="col-md-9"><label for="${idqus6}"><span><span class="inc_span"></span>&nbsp;.&nbsp;</span>Question </label><textarea name="section[section1st][questions][yesorno][${ordercount}][]" id="${idqus6}" class="form-control ques_ip" ></textarea><div class="mt-2"><label class="mr-3">Answer</label><div class="d-flex mt-2"><input type="radio" checked value="0" name="section[section1st][questions][yesorno][${ordercount}][answer][]" id="yesans" class="radio_cbox" /><label><input type="text" name="section[section1st][questions][yesorno][${ordercount}][options][]" value="Yes" id="yestxtyes" class="form-control radio_txt" /></label></div><div class="d-flex mt-2"><input type="radio" name="section[section1st][questions][yesorno][${ordercount}][answer][]" value="1" id="noans" class="radio_cbox" /><label><input type="text" name="section[section1st][questions][yesorno][${ordercount}][options][]" value="No" name="section[section1st][questions][yesorno][${ordercount}][options][]" id="yestxtno" class="form-control radio_txt" /></label></div></div></div><div class="col-md-3"><div class="imagesection">
      					<input type="file" style="display: none;" name="section[section1st][questions][yesorno][${ordercount}][image][]" id="${fileid}" placeholder="Choose the file" class="form-control file_ip imag size_img" accept=".docx,.pdf,.jpeg,.jpg,.png," />
      					<label for="${fileid}" class="imgprev"><img id="ImgPreview" src=${image} for="${fileid}"/></label>
                          <p class="text-danger error_msg"></p>
      					</div></div></div><div class="row align-items-end mt-3"><div class="col-md-9"></div><div class="col-md-2"><label for="${idmark6}">Mark</label><input type="text" name="section[section1st][questions][yesorno][${ordercount}][mark][]" required id="${idmark6}" class="form-control mark_cls" /></div><div class="col-md-1"><button type="button" class="remove_ques btn btn-danger"><i class="fa fa-trash"></i></button></div></div></div>`
                    );
                updateIndex();
                findTotal();

                function readIMG(input, imgControlName) {
                    if (input.files && input.files[0]) {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            $(imgControlName).attr("src", e.target.result);
                            $(imgControlName).css("opacity", 1);
                        };
                        reader.readAsDataURL(input.files[0]);
                    }
                }

                $(".imag").change(function() {
                    var imgControlName = $(this)
                        .closest(".tab_row")
                        .find("#ImgPreview");
                    readIMG(this, imgControlName);
                });

                $(".remove_ques").on("click", function() {
                    updateIndex = function() {
                        $(" .inc_span").each(function(i) {
                            $(this).html(i + 1);
                        });
                    };
                    $(this).closest(".tab_row").remove();
                    updateIndex();
                    findTotal2();
                });
            }
        );

        //initial section 4th question

        // Qus 5 script
        $(document).on(
            "click",
            "#" + "initialsection" + " #shortqus",
            function() {
                ordercount++;
                var idqus4 = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);
                var idans4 = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);
                var idmark4 = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);

                updateIndex = function() {
                    $(".inc_span").each(function(i) {
                        $(this).html(i + 1);
                    });
                };

                $(this)
                    .closest(".secrow")
                    .find("#qusdiv")
                    .append(
                        `<div class="tab_row mb-3"><div class="row align-items-end"><div class="col-md-9"><label for="${idqus4}"><span><span class="inc_span"></span>&nbsp;.&nbsp;</span>Question </label><textarea required name="section[section1st][questions][shortques][${ordercount}][]" id="${idqus4}" class="form-control ques_ip" ></textarea></div><div class="col-md-2"><label for="${idmark4}">Mark</label><input type="text" name="section[section1st][questions][shortques][${ordercount}][mark][]" id="${idmark4}" required class="form-control ques_ip mark_cls" /></div><div class="col-md-1"><button type="button" class="remove_ques btn btn-danger"><i class="fa fa-trash"></i></button></div></div></div>`
                    );
                updateIndex();
                findTotal();

                $(".remove_ques").on("click", function() {
                    updateIndex = function() {
                        $(" .inc_span").each(function(i) {
                            $(this).html(i + 1);
                        });
                    };
                    $(this).closest(".tab_row").remove();
                    updateIndex();
                    findTotal2();
                });
            }
        );

        //initial section 5th question

        $(document).on(
            "click",
            "#" + "initialsection" + " #longqus",
            function() {
                ordercount++;
                var idqus5 = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);
                var idans5 = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);
                var idmark5 = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);

                updateIndex = function() {
                    $(".inc_span").each(function(i) {
                        $(this).html(i + 1);
                    });
                };

                $(this)
                    .closest(".secrow")
                    .find("#qusdiv")
                    .append(
                        `<div class="tab_row mb-3"><div class="row align-items-end"><div class="col-md-9"><label for="${idqus5}"><span><span class="inc_span"></span>&nbsp;.&nbsp;</span>Question </label><textarea required name="section[section1st][questions][longques][${ordercount}][]" id="${idqus5}" class="form-control ques_ip" ></textarea></div><div class="col-md-2"><label for="${idmark5}">Mark</label><input type="text" name="section[section1st][questions][longques][${ordercount}][mark][]" id="${idmark5}" required class="form-control ques_ip mark_cls" /></div><div class="col-md-1"><button type="button" class="remove_ques btn btn-danger"><i class="fa fa-trash"></i></button></div></div></div>`
                    );
                updateIndex();
                findTotal();

                $(".remove_ques").on("click", function() {
                    updateIndex = function() {
                        $(" .inc_span").each(function(i) {
                            $(this).html(i + 1);
                        });
                    };
                    $(this).closest(".tab_row").remove();
                    updateIndex();
                    findTotal2();
                });
            }
        );

        //   end initial

        $(document).ready(function() {
            $(document).on("click", "#addsec", function() {

                var datamax = Number($(this).attr("data-maxorder")) + 1;
                var secmainid = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);
                var secid = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);
                var qusdivid = Math.random()
                    .toString(36)
                    .replace(/[^a-z]+/g, "")
                    .substr(2, 10);

                $("#secdiv").append(
                    `<div class="row secrow py-4" id="${secmainid}"><div class="col-md-12"><div class="remove_section"><i class="fa fa-times"></i></div><div class="d-flex align-items-end"><div class="col-10 pl-0 d-flex align-items-center sectioncol" style="margin-right:10px"><input type="hidden" name="section[section${secmainid}][secorder]"  value="${datamax}" /><input type="text" name="section[section${secmainid}][]" id="${secid}" required placeholder="Section Name" class="form-control" /></div><div class="col-2 pr-0 sectotalmark"><label for="totalmark" class="text-nowrap secmark_label">Section Total Mark</label><input type="text" id="sectotalmark" name="section[section${secmainid}][totalmark]" class="form-control ques_ip sectotalmarkinput" readonly onkeyup="totalvalidate();" /></div></div><div id="qusdiv" class="pt-4 ${qusdivid}"></div> <div class="text-center">
                          <button
                            type="button"
                            id="addquestion"
                            class="btn btn-warning text-white"
                          >
                            Add Question
                          </button>
                          ${window.examtype == "offline" ? ` <button
                                            type="button"
                                            id="subquestion"
                                            class="btn btn-primary"
                                          >
                                            Sub Question
                                          </button>` : ""}
                         
                        </div>
                        <div class="d-flex justify-content-between mt-4 questionoptions" style="display: none !important">
                        <button type="button" class="btn qus-btns" id="fillintheblanks">Fill in the Blanks</button>
                        <button type="button" class="btn qus-btns" id="choosethebestans">Choose the Best Answer</button>
                        <button type="button" class="btn qus-btns" id="yesornoqus">Yes/No Questions</button>
                        <button type="button" class="btn qus-btns" id="shortqus">Short Questions</button>
                        <button type="button" class="btn qus-btns" id="longqus">Long Questions</button>
                        </div></div></div>`
                );


                $(this).attr("data-maxorder", datamax);

                function findTotal2() {
                    var arr2 = document.getElementsByClassName("mark_cls");
                    var tot2 = 0;
                    for (var i = 0; i < arr2.length; i++) {
                        if (parseFloat(arr2[i].value)) tot2 += parseFloat(arr2[i].value);
                    }

                    var secarr2 = $("#" + secmainid + " .mark_cls")
                        .closest(".secrow")
                        .find(".mark_cls");
                    var sectot2 = 0;
                    for (var i = 0; i < secarr2.length; i++) {
                        if (parseFloat(secarr2[i].value))
                            sectot2 += parseFloat(secarr2[i].value);
                    }

                    document.getElementById("totalmark").value = tot2;
                    $("#" + secmainid + " .mark_cls")
                        .closest(".secrow")
                        .find("#sectotalmark")
                        .val(sectot2);
                    totalvalidate();
                }

                $(document).on(
                    "click",
                    "#" + secmainid + " #subquestion",
                    function() {


                        ordercount++;
                        console.log(ordercount, "subquestion");
                        var idqus2 = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var idoptionradioname = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var idoptionradio1 = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var idoptionradio2 = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var idoptionradio3 = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var idoptionradio4 = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var idoption1 = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var idoption2 = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var idoption3 = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var idoption4 = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var idmark2 = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var choosediv = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var fileid = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var addoption = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var addoptionrow = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var removeoption = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);

                        updateIndex = function() {
                            $("#" + secmainid + " .inc_span").each(function(i) {
                                $(this).html(i + 1);
                            });
                        };

                        var image =
                            "{{ URL::to('/') }}/assets/docs/download.png";

                        $(this).closest(".secrow").find("#qusdiv")
                            .append(`<div class="tab_row mb-3" id="${choosediv}">
                        <div class="row align-items-end">
                          <div class="col-md-12">
                            <div class="row mt-3" id=${addoptionrow}>

                              <div class="col-md-12 pr-0 d-flex">
                                <div class="col-md-9 pr-0 d-flex">
                                  <div class="w-100">
                                    <label for="${idqus2}"><span><span class="inc_span"></span>&nbsp;.&nbsp;</span>Question </label>

                                  </div>
                                </div>
                                <div class="col-md-3 text-center">

                                  <div class="row_">
                                    <div class="col-md-6">
                                      <label for="${idmark2}"></label>
                                    </div>
                                  </div>
                                  </div>

                              </div>
                              <div class="col-md-12 pr-0 d-flex opt mb-2">

                                <div class="col-md-9" style="margin-right:10px;">
                                  <div class="w-100" style="display:flex">
                                    <span class="mt-2 mr_20 alph_box">A</span>
                                    <textarea name="section[section${secmainid}][questions][sub_ques][${ordercount}][]" id="${idqus2}" required class="form-control radio_txt" ></textarea>

                                    
                                  </div>
                                </div>

                                <div class="col-md-2">
                                     <input
                                        type="text"
                                        name="section[section${secmainid}][questions][sub_ques][${ordercount}][mark][]"
                                        id="${idmark2}"
                                        required
                                        class="form-control ques_ip mark_cls"
                                      />
                                    </div>
                                    <div class="col-md-1 removesub">
                                      <label for=""></label>
                                      <i class="fa fa-times m-2 removeoption ${removeoption}"></i>
                                </div>

                              </div>

                            </div>
                            <div class="row mt-3">
                              <div class="col-md-12">
                                <button
                                  type="button"
                                  id=${addoption}
                                  data-last="0"
                                  class="btn qus-btns"
                                >
                                  Add Question
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-11"></div>

                          <div class="col-md-1">
                            <button
                              type="button"
                              style="margin-top: 23px"
                              class="remove_ques btn btn-danger"
                            >
                              <i class="fa fa-trash"></i>
                            </button>
                          </div>
                        </div>
                      </div>`);
                        updateIndex();
                        findTotal();

                        function readIMG(input, imgControlName) {
                            if (input.files && input.files[0]) {
                                var reader = new FileReader();
                                reader.onload = function(e) {
                                    $(imgControlName).attr("src", e.target.result);
                                };
                                reader.readAsDataURL(input.files[0]);
                            }
                        }

                        $(".imag").change(function() {
                            var imgControlName = $(this)
                                .closest(".tab_row")
                                .find("#ImgPreview");
                            readIMG(this, imgControlName);
                        });

                        $(document).on("click", "." + removeoption, function() {

                            var element_data = document.querySelectorAll("#" + addoptionrow +
                                " .alph_box");
                            var last_ = element_data.length - 2;

                            $(`#${addoption}`).attr("data-last", last_);
                            updateIndex = function() {
                                $("#" + addoptionrow + " .alph_box").each(function(i) {
                                    $(this).html(String.fromCharCode(65 +
                                        i)); //String.fromCharCode(65 + i)
                                });
                            };
                            $(this).closest(".opt").remove();
                            updateIndex();
                            findTotal2();
                        });

                        $(document).on("click", " #" + addoption, function() {
                            var idoptionradionew = Math.random()
                                .toString(36)
                                .replace(/[^a-z]+/g, "")
                                .substr(2, 10);
                            var last = $(this).attr("data-last");
                            var idoptionnew = Math.random()
                                .toString(36)
                                .replace(/[^a-z]+/g, "")
                                .substr(2, 10);

                            $(this)
                                .closest("#" + choosediv + ".tab_row")
                                .find(
                                    "#" + addoptionrow
                                ).append(` <div class="col-md-12 pr-0 d-flex opt">
            <div class="col-md-9 mb-2" style="margin-right:10px;">
              <div class="w-100" style="display:flex">
                <span class="mt-2 mr_20 alph_box">${ String.fromCharCode(65 + (Number(last)+1))}</span>
                <textarea name="section[section${secmainid}][questions][sub_ques][${ordercount}][]" id="${idoptionnew}" required class="form-control radio_txt" ></textarea>
               
              </div>
            </div>

            <div class="col-md-2">
                  <input
                    type="text"
                    name="section[section${secmainid}][questions][sub_ques][${ordercount}][mark][]"
                    id="${idoptionradionew}"
                    required
                    class="form-control ques_ip mark_cls"
                  />
                </div>
                <div class="col-md-1 removesub">
                  <label for=""></label>
                  <i class="fa fa-times m-2 removeoption ${removeoption}"></i>
               
            </div>

          </div>`);
                            findTotal();
                            $(this).attr("data-last", Number(last) + 1);
                        });

                        $(".remove_ques").on("click", function() {
                            updateIndex = function() {
                                $(" .inc_span").each(function(i) {
                                    $(this).html(i + 1);
                                });
                            };

                            $(this).closest(".tab_row").remove();
                            updateIndex();
                            findTotal2();
                        });
                    }
                );

                // Qus 1 script
                $(document).on(
                    "click",
                    "#" + secmainid + " #fillintheblanks",
                    function() {
                        ordercount++;
                        var idqus = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var idans = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var idmark = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);

                        updateIndex = function() {
                            $("#" + secmainid + " .inc_span").each(function(i) {
                                $(this).html(i + 1);
                            });
                        };

                        $(this)
                            .closest(".secrow")
                            .find("#qusdiv")
                            .append(
                                `<div class="tab_row mb-3"><div class="row align-items-end"><div class="col-md-9"><label for="${idqus}"><span><span class="inc_span"></span>&nbsp;.&nbsp;</span>Question </label><textarea name="section[section${secmainid}][questions][fillblanks][${ordercount}][]" id="${idqus}" required class="form-control ques_ip" ></textarea><label for="${idans}" class="mt-2">Answer</label><input type="text" name="section[section${secmainid}][questions][fillblanks][${ordercount}][answer][]" required id="${idans}" class="form-control ques_ip" /></div><div class="col-md-2"><label for="${idmark}">Mark</label><input type="text" name="section[section${secmainid}][questions][fillblanks][${ordercount}][mark][]" id="${idmark}" required class="form-control ques_ip mark_cls" /></div><div class="col-md-1"><button type="button" class="remove_ques btn btn-danger"><i class="fa fa-trash"></i></button></div></div></div>`
                            );
                        updateIndex();
                        findTotal();

                        $(".remove_ques").on("click", function() {
                            updateIndex = function() {
                                $("#" + secmainid + " .inc_span").each(function(i) {
                                    $(this).html(i + 1);
                                });
                            };
                            $(this).closest(".tab_row").remove();
                            updateIndex();
                            findTotal2();
                        });
                    }
                );

                // Qus 2 script
                $(document).on(
                    "click",
                    "#" + secmainid + " #choosethebestans",
                    function() {
                        ordercount++;
                        var idqus2 = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var idoptionradioname = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var idoptionradio1 = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var idoptionradio2 = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var idoptionradio3 = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var idoptionradio4 = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var idoption1 = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var idoption2 = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var idoption3 = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var idoption4 = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var idmark2 = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var choosediv = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var fileid = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var addoption = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var addoptionrow = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var removeoption = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);

                        updateIndex = function() {
                            $("#" + secmainid + " .inc_span").each(function(i) {
                                $(this).html(i + 1);
                            });
                        };
                        var image =
                            "{{ URL::to('/') }}/assets/docs/dummyimage.jpg";
                        $(this).closest(".secrow").find("#qusdiv")
                            .append(`<div class="tab_row mb-3" id="${choosediv}"><div class="row"><div class="col-md-9"><div class="row"><div class="col-md-12"><label for="${idqus2}"><span><span class="inc_span"></span>&nbsp;.&nbsp;</span>Question </label><textarea name="section[section${secmainid}][questions][choose_best][${ordercount}][]" required id="${idqus2}" class="form-control ques_ip" ></textarea></div></div><div class="row mt-3" id=${addoptionrow}><div class="col-md-12 pr-0 d-flex opt"><input type="radio" name="section[section${secmainid}][questions][choose_best][${ordercount}][answer][]" id="${idoptionradio1}" value="0" class="radio_cbox" /><span class="mt-2 mr_20 alph_box">A</span><label class="col-10"><input type="text" name="section[section${secmainid}][questions][choose_best][${ordercount}][options][]" value="Option 1" id="${idoption1}" class="form-control radio_txt" /></label><i class="fa fa-times m-2 removeoption  ${removeoption}"></i></div></div><div class="row mt-3">
      											<div class="col-md-12">
      												<button type="button" id=${addoption} class="btn qus-btns" data-order="${ordercount}" data-id="${secmainid}" data-last="0"> Add Option</button>
      											</div>
      										</div></div><div class="col-md-3"><div class="row"><div class="imagesection">
      					<input type="file" style="display: none;" name="section[section${secmainid}][questions][choose_best][${ordercount}][image][]" id="${fileid}" placeholder="Choose the file" class="form-control file_ip imag size_img" accept=".docx,.pdf,.jpeg,.jpg,.png," />
      					<label for="${fileid}" class="imgprev"><img id="ImgPreview" src=${image} for="${fileid}"/></label>
                          <p class="text-danger error_msg"></p>
      					</div></div></div></div><div class="row align-items-end">
      						<div class="col-md-9"></div><div class="col-md-2"><label for="${idmark2}">Mark</label><input type="text" required name=section[section${secmainid}][questions][choose_best][${ordercount}][mark][]" id="${idmark2}" class="form-control ques_ip mark_cls" /></div><div class="col-md-1"><button type="button" style="margin-top: 23px;" class="remove_ques btn btn-danger"><i class="fa fa-trash"></i></button></div>
      						</div></div>`);
                        updateIndex();
                        findTotal();

                        function readIMG(input, imgControlName) {
                            if (input.files && input.files[0]) {
                                var reader = new FileReader();
                                reader.onload = function(e) {
                                    $(imgControlName).attr("src", e.target.result);
                                    $(imgControlName).css("opacity", 1);
                                };
                                reader.readAsDataURL(input.files[0]);
                            }
                        }

                        $(".imag").change(function() {
                            var imgControlName = $(this)
                                .closest(".tab_row")
                                .find("#ImgPreview");
                            readIMG(this, imgControlName);
                        });

                        $(document).on(
                            "click",
                            "#" + secmainid + " ." + removeoption,
                            function() {

                                var element_data = document.querySelectorAll("#" + addoptionrow +
                                    " .alph_box");
                                var last_ = element_data.length - 2;


                                $(`#${addoption}`).attr("data-last", last_);
                                updateIndex = function() {
                                    $("#" + addoptionrow + " .alph_box").each(function(i) {
                                        $(this).html(String.fromCharCode(65 +
                                            i)); //String.fromCharCode(65 + i)
                                    });
                                };
                                $(this).closest(".opt").remove();
                                updateIndex();
                            }
                        );

                        $(document).on(
                            "click",
                            "#" + secmainid + " #" + addoption,
                            function() {

                                var sectionNum = $(this).attr("data-id");
                                var order = $(this).attr("data-order");
                                var last = $(this).attr("data-last");
                                console.log(last);
                                var idoptionradionew = Math.random()
                                    .toString(36)
                                    .replace(/[^a-z]+/g, "")
                                    .substr(2, 10);
                                var idoptionnew = Math.random()
                                    .toString(36)
                                    .replace(/[^a-z]+/g, "")
                                    .substr(2, 10);

                                // updatealph = function () {
                                //   $(".alph_box").closest('.tab_row .opt:last-child').find('.alph_box').html(String.fromCharCode(66 + Number(last)));
                                // }

                                console.log(Number(last));
                                console.log(65 + (Number(last) + 1), "alpha");

                                $(this)
                                    .closest("#" + choosediv + ".tab_row")
                                    .find("#" + addoptionrow).append(`<div class="col-md-12 d-flex opt">
      												<input type="radio" value="${Number(last) + 1}" name="section[section${sectionNum}][questions][choose_best][${order}][answer][]" id="${idoptionradionew}" class="radio_cbox" />
                              <span class="mt-2 mr_20 alph_box">${ String.fromCharCode(65 + (Number(last)+1))}</span>
      												<label class="col-10">
      													<input type="text" name="section[section${sectionNum}][questions][choose_best][${order}][options][]" value="New Option" id="${idoptionnew}" class="form-control radio_txt" />
      												</label><i class="fa fa-times m-2 removeoption  ${removeoption}">
      											</div>`);
                                $(this).attr("data-last", Number(last) + 1);

                                //  $(this).closest('.tab_row .opt:last-child').find('.alph_box').html(String.fromCharCode(65 + (Number(last)+1)));
                            }
                        );

                        $(".remove_ques").on("click", function() {
                            updateIndex = function() {
                                $("#" + secmainid + " .inc_span").each(function(i) {
                                    $(this).html(i + 1);
                                });
                            };
                            $(this).closest(".tab_row").remove();
                            updateIndex();
                            findTotal2();
                        });
                    }
                );

                // Qus 3 script
                $(document).on(
                    "click",
                    "#" + secmainid + " #yesornoqus",
                    function() {
                        ordercount++;
                        var idqus6 = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var idmark6 = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var radioname = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);

                        updateIndex = function() {
                            $("#" + secmainid + " .inc_span").each(function(i) {
                                $(this).html(i + 1);
                            });
                        };

                        var fileid = Math.random()
                            .toString(36)
                            .replace(/[^a-z]+/g, "")
                            .substr(2, 10);
                        var image =
                            "{{ URL::to('/') }}/assets/docs/dummyimage.jpg";

                        $(this)
                            .closest(".secrow")
                            .find("#qusdiv")
                            .append(
                                `<div class="tab_row mb-3"><div class="row"><div class="col-md-9"><label for="${idqus6}"><span><span class="inc_span"></span>&nbsp;.&nbsp;</span>Question </label><textarea required name="section[section${secmainid}][questions][yesorno][${ordercount}][]" id="${idqus6}" class="form-control ques_ip" ></textarea><div class="mt-2"><label class="mr-3">Answer</label><div class="d-flex"><input type="radio" checked value="0" name="section[section${secmainid}][questions][yesorno][${ordercount}][answer][]" id="yesans" class="radio_cbox" /><label><input type="text" name="section[section${secmainid}][questions][yesorno][${ordercount}][options][]" value="Yes" id="yestxtyes" class="form-control radio_txt" /></label></div><div class="d-flex"><input type="radio" name="section[section${secmainid}][questions][yesorno][${ordercount}][answer][]" value="1" id="noans" class="radio_cbox" /><label><input type="text" name="section[section${secmainid}][questions][yesorno][${ordercount}][options][]" value="No" name="section[section${secmainid}][questions][yesorno][${ordercount}][options][]" id="yestxtno" class="form-control radio_txt" /></label></div></div></div><div class="col-md-3"><div class="imagesection">
      					<input type="file" style="display: none;" name="section[section${secmainid}][questions][yesorno][${ordercount}][image][]" id="${fileid}" placeholder="Choose the file" class="form-control file_ip imag size_img" accept=".docx,.pdf,.jpeg,.jpg,.png," />
      					<label for="${fileid}" class="imgprev"><img id="ImgPreview" src=${image} for="${fileid}"/></label>
                          <p class="text-danger error_msg"></p>
      					</div></div></div><div class="row"><div class="col-md-9"></div><div class="col-md-2"><label for="${idmark6}">Mark</label><input type="text" name="section[section${secmainid}][questions][yesorno][${ordercount}][mark][]" required id="${idmark6}" class="form-control mark_cls" /></div><div class="col-md-1 mt_31"><button type="button" class="remove_ques btn btn-danger"><i class="fa fa-trash"></i></button></div></div></div>`
                            );
                        updateIndex();
                        findTotal();

                        function readIMG(input, imgControlName) {
                            if (input.files && input.files[0]) {
                                var reader = new FileReader();
                                reader.onload = function(e) {
                                    $(imgControlName).attr("src", e.target.result);
                                    $(imgControlName).css("opacity", 1);
                                };
                                reader.readAsDataURL(input.files[0]);
                            }
                        }

                        $(".imag").change(function() {
                            var imgControlName = $(this)
                                .closest(".tab_row")
                                .find("#ImgPreview");
                            readIMG(this, imgControlName);
                        });

                        $(".remove_ques").on("click", function() {
                            updateIndex = function() {
                                $("#" + secmainid + " .inc_span").each(function(i) {
                                    $(this).html(i + 1);
                                });
                            };
                            $(this).closest(".tab_row").remove();
                            updateIndex();
                            findTotal2();
                        });
                    }
                );

                // Qus 4 script
                $(document).on("click", "#" + secmainid + " #typequs", function() {
                    var idqus3 = Math.random()
                        .toString(36)
                        .replace(/[^a-z]+/g, "")
                        .substr(2, 10);
                    var idans3 = Math.random()
                        .toString(36)
                        .replace(/[^a-z]+/g, "")
                        .substr(2, 10);
                    var fileid = Math.random()
                        .toString(36)
                        .replace(/[^a-z]+/g, "")
                        .substr(2, 10);
                    var idmark3 = Math.random()
                        .toString(36)
                        .replace(/[^a-z]+/g, "")
                        .substr(2, 10);

                    updateIndex = function() {
                        $("#" + secmainid + " .inc_span").each(function(i) {
                            $(this).html(i + 1);
                        });
                    };

                    $(this)
                        .closest(".secrow")
                        .find("#qusdiv")
                        .append(
                            `<div class="tab_row py-3"><div class="row"><div class="col-md-6"><div class="row"><div class="col-md-12"><p class="mb-2"><span><span class="inc_span"></span>&nbsp;.&nbsp;</span>Upload Image</p><input type="file" name="${fileid}" id="${fileid}" placeholder="Choose the file" class="form-control file_ip imag size_img tex_img" accept=".docx,.pdf,.jpeg,.jpg,.png" /><span class="ch_span">Choose the file... Ex:jpeg, gif, png, pdf</span><label for="${fileid}" class="upload_cls btn mt-3">Upload</label> <p class="text-danger error_msg"></p></div><div class="col-md-12 mt-3"><label for="${idqus3}">Add Question</label><input type="text" name="${idqus3}" id="${idqus3}" class="form-control" /></div><div class="col-md-12 mt-3"><label for="${idans3}">Answer</label><textarea rows="5" name="${idans3}" id="${idans3}" class="form-control"></textarea></div></div></div><div class="col-md-5"><label for="preview_3">Image Preview</label><div class="img_box"><img id="ImgPreview" src="" class="preview1" style="display: none;"/></div><div class="mt-3 col-5 float-right pr-0 pl-4"><label for="${idmark3}">Mark</label><input type="text" name="${idmark3}" id="${idmark3}" class="form-control mark_cls" /></div></div><div class="col-md-1 align-self-center"><button type="button" class="remove_ques btn btn-danger"><i class="fa fa-trash"></i></button></div></div></div>`
                        );
                    afterClick();
                    updateIndex();
                    findTotal();

                    $(".remove_ques").on("click", function() {
                        updateIndex = function() {
                            $("#" + secmainid + " .inc_span").each(function(i) {
                                $(this).html(i + 1);
                            });
                        };
                        $(this).closest(".tab_row").remove();
                        updateIndex();
                        findTotal2();
                    });
                });

                // Qus 5 script
                $(document).on("click", "#" + secmainid + " #shortqus", function() {
                    ordercount++;
                    var idqus4 = Math.random()
                        .toString(36)
                        .replace(/[^a-z]+/g, "")
                        .substr(2, 10);
                    var idans4 = Math.random()
                        .toString(36)
                        .replace(/[^a-z]+/g, "")
                        .substr(2, 10);
                    var idmark4 = Math.random()
                        .toString(36)
                        .replace(/[^a-z]+/g, "")
                        .substr(2, 10);

                    updateIndex = function() {
                        $("#" + secmainid + " .inc_span").each(function(i) {
                            $(this).html(i + 1);
                        });
                    };

                    $(this)
                        .closest(".secrow")
                        .find("#qusdiv")
                        .append(
                            `<div class="tab_row mb-3"><div class="row align-items-end"><div class="col-md-9"><label for="${idqus4}"><span><span class="inc_span"></span>&nbsp;.&nbsp;</span>Question </label><textarea name="section[section${secmainid}][questions][shortques][${ordercount}][]" id="${idqus4}" required class="form-control ques_ip" ></textarea></div><div class="col-md-2"><label for="${idmark4}">Mark</label><input type="text" name="section[section${secmainid}][questions][shortques][${ordercount}][mark][]" required id="${idmark4}" class="form-control ques_ip mark_cls" /></div><div class="col-md-1"><button type="button" class="remove_ques btn btn-danger"><i class="fa fa-trash"></i></button></div></div></div>`
                        );
                    updateIndex();
                    findTotal();

                    $(".remove_ques").on("click", function() {
                        updateIndex = function() {
                            $("#" + secmainid + " .inc_span").each(function(i) {
                                $(this).html(i + 1);
                            });
                        };
                        $(this).closest(".tab_row").remove();
                        updateIndex();
                        findTotal2();
                    });
                });

                // Qus 6 script
                $(document).on("click", "#" + secmainid + " #longqus", function() {
                    ordercount++;
                    var idqus5 = Math.random()
                        .toString(36)
                        .replace(/[^a-z]+/g, "")
                        .substr(2, 10);
                    var idans5 = Math.random()
                        .toString(36)
                        .replace(/[^a-z]+/g, "")
                        .substr(2, 10);
                    var idmark5 = Math.random()
                        .toString(36)
                        .replace(/[^a-z]+/g, "")
                        .substr(2, 10);

                    updateIndex = function() {
                        $("#" + secmainid + " .inc_span").each(function(i) {
                            $(this).html(i + 1);
                        });
                    };

                    $(this)
                        .closest(".secrow")
                        .find("#qusdiv")
                        .append(
                            `<div class="tab_row mb-3"><div class="row align-items-end"><div class="col-md-9"><label for="${idqus5}"><span><span class="inc_span"></span>&nbsp;.&nbsp;</span>Question </label><textarea name="section[section${secmainid}][questions][longques][${ordercount}][]" id="${idqus5}" required class="form-control ques_ip" ></textarea></div><div class="col-md-2"><label for="${idmark5}">Mark</label><input type="text" name="section[section${secmainid}][questions][longques][${ordercount}][mark][]" required id="${idmark5}" class="form-control ques_ip mark_cls" /></div><div class="col-md-1"><button type="button" class="remove_ques btn btn-danger"><i class="fa fa-trash"></i></button></div></div></div>`
                        );
                    updateIndex();
                    findTotal();

                    $(".remove_ques").on("click", function() {
                        updateIndex = function() {
                            $("#" + secmainid + " .inc_span").each(function(i) {
                                $(this).html(i + 1);
                            });
                        };
                        $(this).closest(".tab_row").remove();
                        updateIndex();
                        findTotal2();
                    });
                });

                // section remove
                $(".remove_section").on("click", function() {
                    $(this).closest(".secrow").remove();
                    findTotal2();
                });
            });
            $(".remove_section").on("click", function() {
                $(this).closest(".secrow").remove();
                findTotal2();
            });
        });
    </script>

    <script>
        function afterClick() {
            function readIMG(input, imgControlName) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $(imgControlName).attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            $('.imag').change(function() {

                $(this).closest('.tab_row').find('.file_ip').css('color', '#7b7b7b');
                $(this).closest('.tab_row').find('.ch_span').hide();

                var ext = $(this).closest('.tab_row').find(".imag").val().split('.').pop();

                if (ext == 'mp4') {

                    $(this).closest('.tab_row').find(".img_box").html(
                        `<video controls autoplay src="" class="vids"></video>`);

                    var imgControlName = $(this).closest('.tab_row').find('.vids');
                    readIMG(this, imgControlName);
                    $(this).closest('.tab_row').find('.vidssrc').show();

                } else if (ext == "pdf" || ext == "xlsx" || ext == "docx") {

                    $(this).closest('.tab_row').find(".img_box").html(
                        `<img id="ImgPreview" src="" class="preview1" style="display: none;" type="application/pdf"/>`
                    );

                    $(this).closest('.tab_row').find('#ImgPreview').show();
                    var image = $(this).closest('.tab_row').find('.preview1');

                    switch (ext) {
                        case 'pdf':
                            image[0].src = "{{ URL::to('/') }}/assets/docs/pdf.png";
                            break;
                        case 'xlsx':
                            image[0].src = "{{ URL::to('/') }}/assets/docs/xlsx.png";
                            break;
                        case 'docx':
                            image[0].src = "{{ URL::to('/') }}/assets/docs/docimage.png";
                            break;
                    }

                } else {

                    $(this).closest('.tab_row').find(".img_box").html(
                        `<img id="ImgPreview" src="" class="preview1" style="display: none;" type="application/pdf"/>`
                    );

                    var imgControlName = $(this).closest('.tab_row').find('#ImgPreview');
                    readIMG(this, imgControlName);
                    $(this).closest('.tab_row').find('#ImgPreview').show();
                }
            });
        }
    </script>


@endsection



@section('script_link')

    <!-- validator -->
    {!! Cms::script('theme/vendors/validator/validator.js') !!}
    {!! Cms::script('theme/vendors/validator/validator_form.js') !!}

@endsection
