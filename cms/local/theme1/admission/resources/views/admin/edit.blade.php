@extends('layout::admin.master')

@section('title', 'students')
@section('style')

    <style>
        .address__check {
            width: 30px;
            height: 18px;
        }

        .communication {
            display: flex;
            align-items: center;
            gap: 7px;

        }

        .communication_label {
            margin-top: 5px;
        }

        .parent_id {
            display: none;
        }

        .select_ip .select2 {
            width: 100% !important;
            text-align: left;
        }

        .freligion .select2 {
            width: 100% !important;
        }

        .cen_sec {
            /* display: flex; */
            justify-content: center;
        }

        #sibilings__check {
            width: 20px;
            height: 20px;
        }

        .siblings__text {
            font-size: 15px;
            font-weight: bold;
        }

        .parent__Details {
            margin-top: 20px;
            display: none
        }

        .parent__Details span {
            font-weight: 800;
        }
    </style>

@endsection
@section('body')
    <div class="x_content">
        @if ($layout == 'create')
            {{ Form::open(['role' => 'form', 'route' => ['admission.store'], 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'teacher-form', 'novalidate' => 'novalidate']) }}
        @elseif($layout == 'edit')
            {{ Form::open(['role' => 'form', 'route' => ['admission.update', $data->id], 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form', 'novalidate' => 'novalidate']) }}
        @endif
        <div class="box-header with-border mar-bottom20">
            @if ($layout == 'create')
                {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', ['type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat', 'value' => 'Edit_teacher', 'class' => 'btn btn-success btn-sm m-1  px-3']) }}
            @else
                {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Reject', [ 'type' => 'button', 'id' => 'reject_modal', 'name' => 'reject', 'value' => 'reject_student', 'class' => 'btn btn-danger btn-sm m-1  px-3 reject_modal' ,'data-bs-toggle' => 'modal','data-bs-target' => '#exampleModal']) }}
                
                {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Onboard', ['type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat', 'value' => 'create_teacher', 'class' => 'btn btn-success btn-sm m-1  px-3']) }}
            @endif

            <a class="btn btn-info btn-sm m-1  px-3" href="{{ route('admission.index') }}"><i
                    class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>


            @include('layout::admin.breadcrump', [
                'route' => $layout == 'edit' ? 'On Board Student' : 'Create Admission',
            ])
        </div>
        <div class="alert alert-success" role="alert">
            Please Complete the onboarding process by filling the remaining fields to complete the student admission process,Then click on the onboard button above to confirm the student admission or reject button to deny student admission 
        </div>
        @if(!empty($admissionExamResults))
            <div class="alert alert-info" role="alert">
                <div class="row">
                    <strong><h5 class="card-title text-center">Admission Exam Results</h5></strong>
                    <div class="col-sm-2">        
                        <h6>Total Questions : {{ @$admissionExamResults['onlineexam']['total_questions'] }} </h6>            
                    </div>
                    <div class="col-sm-3">        
                        <h6>Attempt Questions : {{ @$admissionExamResults['onlineexam']['total_answered'] }}</h6>
                    </div>
                    <!-- <div class="col-sm-2">        
                        <h6>Correctly Answered : {{ @$admissionExamResults['onlineexam']['total_correct'] }}</h6>
                    </div> -->
                    <div class="col-sm-2">        
                        <h6>Marks Scored : {{ @$admissionExamResults['onlineexam']['total_marks'] }}</h6>
                    </div>      
                    <div class="col-sm-2">        
                        <h6>Result : {{ @$admissionExamResults['onlineexam']['total_marks']>= @$admissionExamResults['exam']['min_mark'] ? "Pass" : "Fail"}}</h6>
                    </div>          
                </div>
            </div>
        @endif
                <div class="card">
                    <div class="card-body">
                        @if ($layout == 'create')
                            <h5 class="card-title">Create Student Admission</h5>
                        @else
                            <h5 class="card-title">On Board Student</h5>
                        @endif
                        <hr />
                        <div class="accordion accordion-flush" id="accordionFlushExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                        Student Info
                                    </button>
                                </h2>
                                <div id="flush-collapseOne" class="accordion-collapse collapse " aria-labelledby="flush-headingOne"
                                    data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">
                                        <div class="col-xs-12" style="margin-bottom: 7px;">
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom" for="first_name"> First Name
                                                            <span class="required">*</span>
                                                        </label>
                                                        <input type="hidden" name="reject_admission" class="reject_admission" id="student_id" value="{{@$data->id}}">
                                                        <div class="feild">
                                                            {{ Form::text('first_name', @$data->first_name, [
                                                                'id' => 'first_name',
                                                                'class' => 'form-control col-md-7 col-xs-12',
                                                                'placeholder' => 'First name',
                                                                'required' => 'required',
                                                            ]) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom" for="last_name"> Last Name <span
                                                                class="required">*</span>
                                                        </label>
                                                        <div class="feild">
                                                            {{ Form::text('last_name', @$data->last_name, [
                                                                'id' => 'last_name',
                                                                'class' => 'form-control col-md-7 col-xs-12',
                                                                'placeholder' => 'Last name',
                                                                'required' => 'required',
                                                            ]) }}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom" for="email"> Email <span
                                                                class="required">*</span>
                                                        </label>
                                                        <div class="feild">
                                                            {{ Form::text('email', @$data->email, [
                                                                'id' => 'email',
                                                                'class' => 'form-control col-md-7 col-xs-12',
                                                                'placeholder' => 'Enter Email',
                                                                'required' => 'required',
                                                            ]) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom" for="mobile"> Mobile <span
                                                                class="required">*</span>
                                                        </label>
                                                        <div class="feild">
                                                            {{ Form::text('mobile', @$data->mobile, [
                                                                'id' => 'mobile',
                                                                'class' => 'form-control col-md-7 col-xs-12',
                                                                'placeholder' => 'Enter Mobile Number',
                                                                'required' => 'required',
                                                            ]) }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                {{-- //nextrow --}}
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom" for="mobile">DOB <span
                                                                class="required">*</span>
                                                        </label>
                                                        <div class="feild">
                                                            {{ Form::date('dob', @$data->dob, [
                                                                'id' => 'dob',
                                                                'class' => '',
                                                                'placeholder' => 'dob',
                                                                'required' => 'required',
                                                            ]) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom" for="gender"> Gender <span
                                                                class="required">*</span>
                                                        </label>
                                                        <div class="feild">
                                                            {{ Form::select('gender', @$gender, @$data->gender, [
                                                                'id' => 'status_g',
                                                                'class' => 'single-select form-control select2',
                                                                'required' => 'required',
                                                            ]) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom" for="blood_group">Blood Group
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="feild">
                                                            {{ Form::select('blood_group', @$bloodgroup, @$data->blood_group, [
                                                                'id' => 'blood_group',
                                                                'class' => 'single-select form-control',
                                                                'required' => 'required',
                                                                'placeholder' => 'Select Blood group',                                                        
                                                            ]) }}

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label for="thumbnail" class="control-label margin__bottom">Image<span
                                                                class="required">*</span></label>
                                                        <div class="">
                                                            <input class="form-control thumb" type="file" id="imagec_img_student"
                                                                name="imagec" data-id="student" accept="image/png, image/jpeg"
                                                                value="{{ @$data->image }}">

                                                            <img id="imagecholder" style="max-height:50px;"
                                                                src="{{ @$data->image }}">

                                                            @if (@$layout != 'create' && @$data->image)
                                                                <span class="back_to remove" id="remove_img_student"
                                                                    data-id="imagec" data-class="student">X</span>
                                                            @else
                                                                <span class="back_to remove" id="remove_img_student"
                                                                    style="display:none;" data-id="imagec"
                                                                    data-class="student">X</span>
                                                            @endif


                                                        </div>
                                                    </div>
                                                </div>
                                            </div>



                                            {{-- //nextrow --}}
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom" for="handicapped"> Handicapped<span
                                                        class="required">*</span>
                                                        </label>
                                                        <div class="feild">
                                                            {{ Form::select('handicapped', ['0' => 'No', '1' => 'Yes'], @$data->handicapped, [
                                                                'id' => 'status',
                                                                'class' => 'form-control single-select',
                                                                'required' => 'required',
                                                            ]) }}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom"
                                                            for="national_id_number">National Id <span class="required">*</span>
                                                        </label>
                                                        <div class="feild">
                                                            {{ Form::text('national_id_number', @$data->national_id_number, [
                                                                'id' => 'national_id_number',
                                                                'class' => 'form-control col-md-7 col-xs-12',
                                                                'placeholder' => 'national id number',
                                                                'required' => 'required',
                                                            ]) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom" for="religion_">Religion<span
                                                                class="required">*</span>
                                                        </label>
                                                        <div class="feild">
                                                            {{ Form::select('religion', @$religion, isset($data->religion) ? @$data->religion : '', [
                                                                'id' => 'religion_',
                                                                'class' => 'form-control single-select',
                                                                'required' => 'required',
                                                                'placeholder' => 'Select Religion',
                                                            ]) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom" for="admission_date">Admission
                                                            Date <span class="required">*</span>
                                                        </label>
                                                        <div class="feild">
                                                            {{ Form::text('admission_date', @$data->admission_date, [
                                                                'id' => 'admission_date dateid',
                                                                'class' => 'form-control col-md-7 col-xs-12 datepicker',
                                                                'placeholder' => 'admission_date',
                                                                'required' => 'required',
                                                            ]) }}
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- //status -->

                                            </div>

                                            {{-- //nextrow --}}
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom" for="transportation"> Need
                                                            Transportation <span class="required">*</span>
                                                        </label>
                                                        <div class="feild">
                                                            {{ Form::select('transportation', [0 => 'No', 1 => 'Yes'], @$data->transportation, [
                                                                'id' => 'needtransportation',
                                                                'class' => 'single-select form-control',
                                                                'required' => 'required',
                                                            ]) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom" for="transportation_zone">
                                                            Transportation Zones <span class="required"></span>
                                                        </label>
                                                        <div class="feild">
                                                            {{ Form::select('transportation_zone', @$transport_zones, @$data->transportation_zone, [
                                                                'id' => 'transportation_zone',
                                                                'class' => 'form-control single-select',
                                                                'placeholder' => 'Available Zones',                                                        
                                                            ]) }}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom"
                                                            for="national_id_number">Previous Institute Mark % <span class="required"></span>
                                                        </label>
                                                        <div class="feild">
                                                            {{ Form::text('previous_ins_percentage', @$data->previous_ins_percentage, [
                                                                'id' => 'previous_ins_percentage',
                                                                'class' => 'form-control col-md-7 col-xs-12',
                                                                'placeholder' => '% marks',                                                       
                                                            ]) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom"
                                                            for="national_id_number">Passport Number<span class="required"></span>
                                                        </label>
                                                        <div class="feild">
                                                            {{ Form::text('passport_no', @$data->passport_no, [
                                                                'id' => 'passport_no',
                                                                'class' => 'form-control col-md-7 col-xs-12',
                                                                'placeholder' => 'Passport no',
                                                                
                                                            ]) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom" for="student_type"> Student
                                                            Type <span class="required">*</span>
                                                        </label>
                                                        <div class="feild">
                                                            {{ Form::select('student_type', @$student_types, @$data->student_type, [
                                                                'id' => 'student_type',
                                                                'class' => 'single-select form-control select2',
                                                                'required' => 'required',                                                        
                                                            ]) }}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom" for="departments"> Department <span class="required">*</span>
                                                        </label>
                                                        
                                                        <div class="feild">
                                                            {{ Form::select('departments', @$departments, @$data->selected_department_id, [
                                                                'id' => 'student_type',
                                                                'class' => 'single-select form-control select2',
                                                                'required' => 'required',                                                        
                                                            ]) }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- //nextrow --}}
                                        

                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <h4>Academic Info</h4>

                                                </div>
                                            </div>

                                            <div class="row">
                                                @if (@$layout == 'edit')
                                                    <input type="hidden" name="class_id" value="{{ @$data->class_id }}" />
                                                    <input type="hidden" name="section_id" value="{{ @$data->section_id }}" />
                                                    <input type="hidden" name="academic_year"
                                                        value="{{ @$data->academic_year }}" />
                                                @endif
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom" for="academic_year"> Academic
                                                            Year <span class="required">*</span>
                                                        </label>
                                                        <div class="feild ss">
                                                            {{ Form::select('academic_year', @$academic_years, @$data->academic_year, [
                                                                'id' => 'academic_year',
                                                                'class' => 'single-select form-control select2',
                                                                'required' => 'required',
                                                            ]) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom" for="transportation"> Class
                                                            <span class="required">*</span>
                                                        </label>
                                                        
                                                        <div class="feild">
                                                            {{ Form::select('class_id', @$class_lists, @$selected_class_lists, [
                                                                'id' => 'transportation',
                                                                'class' => 'form-control single-select',
                                                                'required' => 'required',
                                                                'placeholder' => 'Select class',
                                                                'required' => 'required',
                                                            ]) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom" for="section_id"> Section<span
                                                                class="required">*</span>
                                                        </label>
                                                        
                                                        <div class="feild select_ip"> 
                                                            {{ Form::select('section_id', @$section_lists, isset($selected_Section) ? @$selected_Section : '', [
                                                                'id' => 'section_id',
                                                                'class' => 'form-control single-select',
                                                                'required' => 'required',
                                                                'placeholder' => 'Select section',
                                                                'required' => 'required',
                                                            ]) }}
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom" for="school_name"> Department <span class="required">*</span>
                                                        </label>
                                                        <div class="feild designation_select_feild">
                                                        {{ Form::select('stu_department',@$selected_department,@$data->stu_department ,
                                                        array('id'=>'designation_type','class' => 'form-control single-select','required' => 'required',"placeholder"=>"Select Department",@$layout=="edit" ?"disabled" :"" )) }}
                                                        </div>
                                                        
                                                    </div>
                                                </div> --}}

                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom" for="father_name"> Roll Number
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="feild">
                                                            {{ Form::text('roll_no', @$data->roll_no, [
                                                                'id' => 'roll',
                                                                'class' => 'form-control col-md-7 col-xs-12',
                                                                'placeholder' => 'Roll Number',
                                                                'required' => 'required',
                                                            ]) }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingscholarship">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#flush-collapsescholarship" aria-expanded="false"
                                        aria-controls="flush-collapsescholarship">
                                        Student Scholarship
                                    </button>
                                </h2>
                                <div id="flush-collapsescholarship" class="accordion-collapse collapse"
                                    aria-labelledby="flush-headingscholarship" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-4 col-md-3">
                                                <div class="item form-group">
                                                    <label class="control-label margin__bottom" for="father_name"> Scholarship
                                                        <span class="required"></span>
                                                    </label>
                                                    <div class="feild">
                                                        {{ Form::text('scholarship', @$data->scholarship, [
                                                            'id' => 'scholarship',
                                                            'class' => 'form-control col-md-7 col-xs-12',
                                                            'placeholder' => '%',
                                                        ]) }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-sm-4 col-md-3">
                                                <div class="item form-group">
                                                    <label class="control-label margin__bottom" for="father_name"> Scholarship
                                                        Description<span class="required"></span>
                                                    </label>
                                                    <div class="feild">
                                                        {{ Form::textarea('scholarship_note', @$data->scholarship_note, [
                                                            'class' => 'form-control',
                                                            'rows' => 4,
                                                            'name' => 'scholarship_note',
                                                            'id' => 'scholarship_note',
                                                        ]) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#flush-collapseTwo" aria-expanded="false"
                                        aria-controls="flush-collapseTwo">
                                        Parent / Guardian Info
                                    </button>
                                </h2>
                                <div id="flush-collapseTwo" class="accordion-collapse collapse"
                                    aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">
                                        @if (@$layout == 'create')
                                            {{-- <div class="col-xs-12" style="margin-top: 20px;">
                                            <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-body">
                                                
                                                <p class="card-text text-danger panel_alert"><i class="fa fa-hand-o-right text-danger" aria-hidden="true"></i> In this school Already This have Siblings Studying </p>
                                                <p class="card-text text-danger panel_alert"><i class="fa fa-hand-o-right text-danger" aria-hidden="true"></i>Please Check Below Checkbox Select Parents</p>
                                                
                                                
                                                </div>
                                            </div>
                                            </div>
                                        
                                        </div> --}}
                                            <div class="col-xs-12" style="margin-top: 10px;">

                                                <div class="communication" style="margin-bottom: 20px">

                                                    <label class="form-check-label communication_label siblings__text"
                                                        for="flexCheckDefault">
                                                        Kindly Click here for Existing Parent and select the appropriate parent
                                                        representing the child above
                                                    </label>
                                                    <input class="form-check-input sibilings__check" type="checkbox"
                                                        value="1" id="sibilings__check" name="sibilings__check">
                                                </div>

                                            </div>

                                            <div class="col-xs-12 parent__select"
                                                style="margin-bottom: 7px;margin-top:7px;display:none;">
                                                <div class="row ">

                                                    <div class="col-xs-12 col-sm-12 col-md-3">
                                                        <div class="item form-group">

                                                            <div class="feild ">
                                                                {{ Form::select('parent_id', @$parent_lists, null, [
                                                                    'id' => 'parent_id',
                                                                    'class' => 'form-control single-select ',
                                                                    'placeholder' => 'select parent Details',
                                                                ]) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="parent__Details">


                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-4">
                                                        <div class="item form-group">

                                                            <div class="feild ">
                                                                <label class="control-label margin__bottom"
                                                                    for="father_name">Parent Name : <span
                                                                        id="father_name_details"></span>
                                                                </label>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-12 col-md-4">
                                                        <div class="item form-group">

                                                            <div class="feild ">
                                                                <label class="control-label margin__bottom" for="email"> Email
                                                                    : <span id="father_email_details">

                                                                    </span>
                                                                </label>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-12 col-md-4">
                                                        <div class="item form-group">

                                                            <div class="feild ">
                                                                <label class="control-label margin__bottom" for="mobile"> Mobile
                                                                    : <span id="father_mobile_details"></span>
                                                                </label>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif


                                        <div class="col-xs-12 parent__info" style="margin-bottom: 7px;">
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom" for="father_name">
                                                            Parent/Guardian Name <span class="required">*</span>
                                                        </label>
                                                        <div class="feild">
                                                            {{ Form::text('father_name', @$data->parent_name, [
                                                                'id' => 'fathername',
                                                                'class' => 'form-control col-md-7 col-xs-12',
                                                                'placeholder' => 'father name',
                                                                'required' => 'required',
                                                            ]) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom" for="father_name"> Email <span
                                                                class="required">*</span>
                                                        </label>
                                                        <div class="feild">
                                                            {{ Form::text('father_email', @$data->parent_email, [
                                                                'id' => 'father_email',
                                                                'class' => 'form-control col-md-7 col-xs-12',
                                                                'placeholder' => 'father email',
                                                                'required' => 'required',
                                                            ]) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom" for="father_name"> Mobile
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="feild">
                                                            {{ Form::text('father_mobile', @$data->parent_mobile, [
                                                                'id' => 'father_mobile',
                                                                'class' => 'form-control col-md-7 col-xs-12',
                                                                'placeholder' => 'father mobile',
                                                                'required' => 'required',
                                                            ]) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom" for="father_name"> Occupation
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="feild">
                                                            {{ Form::text('father_occupation', @$data->parent->father_occupation, [
                                                                'id' => 'occupation',
                                                                'class' => 'form-control col-md-7 col-xs-12',
                                                                'placeholder' => 'father occupation',
                                                                'required' => 'required',
                                                            ]) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom" for="fathernat_id"> National
                                                            Id <span class="required">*</span>
                                                        </label>
                                                        <div class="feild">
                                                            {{ Form::text('fathernat_id', @$data->parent->fathernat_id, [
                                                                'id' => 'fathernatid',
                                                                'class' => 'form-control col-md-7 col-xs-12',
                                                                'placeholder' => 'father  National Id',
                                                                'required' => 'required',
                                                            ]) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom" for="religion_">Religion<span
                                                                class="required">*</span>
                                                        </label>
                                
                                                        <div class="feild freligion">
                                                            {{ Form::select('father_religion', @$religion, isset($data->religion) ? @$data->religion : '', [
                                                                'id' => 'religion_father',
                                                                'class' => 'form-control single-select',
                                                                'required' => 'required',
                                                                'placeholder' => 'Select Religion',
                                                            ]) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label for="thumbnail" class="control-label margin__bottom">Image <span
                                                                class="required">*</span></label>
                                                        <div class="">
                                                            <input class="form-control thumb" type="file"
                                                                id="father_image_img_father_image" name="father_image"
                                                                data-id="father_image" accept="image/png, image/jpeg">

                                                            <img id="father_imageholder" style="max-height:50px;"
                                                                src="{{ @$data->parent->father_image }}">

                                        

                                                        </div>
                                                    </div>
                                                </div>

                                            </div>



                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#flush-collapseThree" aria-expanded="false"
                                        aria-controls="flush-collapseThree">
                                        Address
                                    </button>
                                </h2>
                                <div id="flush-collapseThree" class="accordion-collapse collapse"
                                    aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">
                                        <div class="col-xs-12" style="margin-bottom: 7px;">
                                            <div class="row">
                                                {{-- <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom" for="building_name"> Building Name <span class="required">*</span>
                                                        </label>
                                                        <div class="feild">
                                                        {{Form::text('building_name',@$data->house_no,array('id'=>"building_name",'class'=>"form-control col-md-7 col-xs-12" ,
                                                    'placeholder'=>"building name",'required'=>"required"))}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom" for="building_name"> Subbuilding Name <span class="required">*</span>
                                                        </label>
                                                        <div class="feild">
                                                        {{Form::text('subbuilding_name',@$address_communication->subbuilding_name,array('id'=>"subbuilding_name",'class'=>"form-control col-md-7 col-xs-12" ,
                                                    'placeholder'=>"subbuilding name",'required'=>"required"))}}
                                                        </div>
                                                    </div>
                                                </div> --}}
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom" for="building_name"> House No
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="feild">
                                                            {{ Form::text('house_no', @$data->house_no, [
                                                                'id' => 'house_no',
                                                                'class' => 'form-control col-md-7 col-xs-12',
                                                                'placeholder' => 'house no',
                                                                'required' => 'required',
                                                            ]) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom" for="building_name"> Street
                                                            Name <span class="required">*</span>
                                                        </label>
                                                        <div class="feild">
                                                            {{ Form::text('street_name', @$data->street, [
                                                                'id' => 'street_name',
                                                                'class' => 'form-control col-md-7 col-xs-12',
                                                                'placeholder' => 'street name',
                                                                'required' => 'required',
                                                            ]) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom" for="building_name"> Postal
                                                            Code <span class="required">*</span>
                                                        </label>
                                                        <div class="feild">
                                                            {{ Form::text('postal_code', @$data->postal_code, [
                                                                'id' => 'postal_code',
                                                                'class' => 'form-control col-md-7 col-xs-12',
                                                                'placeholder' => 'postal code',
                                                                'required' => 'required',
                                                            ]) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom" for="building_name"> City
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="feild">
                                                            {{ Form::text('province', @$data->city, [
                                                                'id' => 'province',
                                                                'class' => 'form-control col-md-7 col-xs-12',
                                                                'placeholder' => 'province',
                                                                'required' => 'required',
                                                            ]) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom" for="country"> Country <span
                                                                class="required">*</span>
                                                        </label>
                                                        <div class="feild">
                                                            {{ Form::text('country', @$data->country, [
                                                                'id' => 'country',
                                                                'class' => 'form-control col-md-7 col-xs-12',
                                                                'placeholder' => 'country',
                                                                'required' => 'required',
                                                            ]) }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingFour">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#flush-collapseFour" aria-expanded="false"
                                        aria-controls="flush-collapseFour">
                                        Document Submission
                                    </button>
                                </h2>
                                <div id="flush-collapseFour" class="accordion-collapse collapse"
                                    aria-labelledby="flush-headingFour" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">


                                        <div class="col-xs-12" style="margin-bottom: 7px;">
                                            <div class="row">

                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label for="thumbnail" class="control-label margin__bottom"> Upload
                                                            Document 1 <span class="required">*</span> </label>
                                                        <div class="">
                                                            <span class="input-group-btn">
                                                                <input class="form-control thumb" type="file"
                                                                    id="birth_certificate_img_birth_certificate"
                                                                    name="birth_certificate" data-id="birth_certificate"
                                                                    accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel,.pdf">
                                                            </span>
                                                                
                                                            @if (isset($selected_stu_document_upload1))                                                        
                                                                <small id="birth_certificate_small"> <a href="{{ @$selected_stu_document_upload1 }}" target="_blank">View PDF</a>                                                        </small>
                                                                <span class="back_to remove remove_"
                                                                data-attach="{{ @$key }}"
                                                                id="remove_img_birth_certificate"
                                                                data-id="birth_certificate"
                                                                data-class="birth_certificate">X</span>                                                        
                                                            @else
                                                                <span class="back_to remove" id="remove_img_birth_certificate"
                                                                    style="display:none;" data-id="birth_certificate"
                                                                    data-class="birth_certificate">X</span>
                                                            @endif

                                                            @if (@$layout != 'create' && is_array(@$attachements['birth_certificate']))
                                                                @foreach ($attachements['birth_certificate'] as $key => $value)                                                           
                                                                        <span class="back_to remove remove_" data-attach="{{ $key }}" id="remove_img_birth_certificate" data-id="birth_certificate" data-class="birth_certificate">X</span>
                                                                @endforeach
                                                                @else
                                                                    <span class="back_to remove" id="remove_img_birth_certificate" style="display:none;" data-id="birth_certificate" data-class="birth_certificate">X</span>
                                                        @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label for="thumbnail" class="control-label margin__bottom">Upload
                                                            Document 2<span class="required">*</span></label>
                                                        <div class="">
                                                            <span class="input-group-btn">
                                                                <input class="form-control thumb" type="file"
                                                                    id="tranfer_certificate_img_tranfer_certificate"
                                                                    name="tranfer_certificate" data-id="tranfer_certificate"
                                                                    accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel,.pdf">

                                                            </span>


                                                            <!-- @if (@$layout != 'create' && is_array(@$attachements['tranfer_certificate']))

                                                                @foreach ($attachements['tranfer_certificate'] as $key => $Value)
                                                                    <small
                                                                        id="tranfer_certificate_small">{{ @$Value }}</small>
                                                                    <span class="back_to remove remove_"
                                                                        data-attach="{{ @$key }}"
                                                                        id="remove_img_tranfer_certificate"
                                                                        data-id="tranfer_certificate"
                                                                        data-class="tranfer_certificate">X</span>
                                                                @endforeach
                                                            @else
                                                                <span class="back_to remove" id="remove_img_tranfer_certificate"
                                                                    style="display:none;" data-id="tranfer_certificate"
                                                                    data-class="tranfer_certificate">X</span>
                                                            @endif -->

                                                            @if (isset($selected_stu_document_upload2))                                                        
                                                                <small id="birth_certificate_small"> <a href="{{ @$selected_stu_document_upload2 }}" target="_blank">View PDF</a>                                                        </small>
                                                                <span class="back_to remove remove_"
                                                                data-attach="{{ @$key }}"
                                                                id="remove_img_birth_certificate"
                                                                data-id="birth_certificate"
                                                                data-class="birth_certificate">X</span>                                                        
                                                            @else
                                                                <span class="back_to remove" id="remove_img_birth_certificate"
                                                                    style="display:none;" data-id="birth_certificate"
                                                                    data-class="birth_certificate">X</span>
                                                            @endif


                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label for="thumbnail" class="control-label margin__bottom">Upload
                                                            Document 3<span class="required">*</span></label></label>
                                                        <div class="">
                                                            <span class="input-group-btn">
                                                                <input class="form-control thumb" type="file"
                                                                    id="mark_sheet_img_mark_sheet" name="mark_sheet"
                                                                    data-id="mark_sheet"
                                                                    accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel,.pdf">
                                                            </span>


                                                            @if (@$layout != 'create' && is_array(@$attachements['mark_sheet']))

                                                                @foreach ($attachements['mark_sheet'] as $key => $Value)
                                                                    <small id="mark_sheet_small">{{ @$Value }}</small>
                                                                    <span class="back_to remove remove_"
                                                                        data-attach="{{ @$key }}"
                                                                        id="remove_img_mark_sheet" data-id="mark_sheet"
                                                                        data-class="mark_sheet">X</span>
                                                                @endforeach
                                                            @else
                                                                <span class="back_to remove" id="remove_img_mark_sheet"
                                                                    style="display:none;" data-id="mark_sheet"
                                                                    data-class="mark_sheet">X</span>
                                                            @endif


                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label for="thumbnail" class="control-label margin__bottom">Upload
                                                            Document 4 <span class="required">*</span></label>
                                                        <div class="">
                                                            <span class="input-group-btn">

                                                                <input class="form-control thumb" type="file"
                                                                    id="national_id_certificate_img_national_id_certificate"
                                                                    name="national_id_certificate"
                                                                    data-id="national_id_certificate"
                                                                    accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel,.pdf">

                                                            </span>


                                                            @if (@$layout != 'create' && is_array(@$attachements['national_id_certificate']))

                                                                @foreach ($attachements['national_id_certificate'] as $key => $Value)
                                                                    <small
                                                                        id="national_id_certificate_small">{{ @$Value }}</small>
                                                                    <span class="back_to remove remove_"
                                                                        data-attach="{{ @$key }}"
                                                                        id="remove_img_national_id_certificate"
                                                                        data-id="national_id_certificate"
                                                                        data-class="national_id_certificate">X</span>
                                                                @endforeach
                                                            @else
                                                                <span class="back_to remove"
                                                                    id="remove_img_national_id_certificate" style="display:none;"
                                                                    data-id="national_id_certificate"
                                                                    data-class="national_id_certificate">X</span>

                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                        <div class="col-xs-12 mt-3">
                                            <div class="col-sm-6">
                                                <p class="card-text text-danger panel_alert"><i
                                                        class="fa fa-hand-o-right text-danger" aria-hidden="true"></i> Upload
                                                    Respective Student Document </p>
                                                <p class="card-text text-danger panel_alert"><i
                                                        class="fa fa-hand-o-right text-danger" aria-hidden="true"></i>Maximum
                                                    filesize <strong>2mb</strong> </p>

                                                {{-- <a href="{{ route("section.create") }}" class="btn btn-primary">Create Section</a>
                                                <a href="{{ route("subject.create") }}" class="btn btn-primary">Create Subject</a> --}}


                                            </div>
                                            {{-- //next column  --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <form id="rejectionForm" method="POST" action="{{ route('admission.reject') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Student Admission Form - Rejection </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <div class="form-group">
                    <label for="admission_text">Rejection Message Text <span class="required">*</span></label>

                    @include('layout::widget.ckeditor', [
                        'name' => 'rejection_text',
                        'id' => 'rejection_text',
                        'class' => 'w-80',
                        'content' => '',
                    ])

                    {{-- <textarea name="rejection_text" id="rejection_text" cols="80"
                        rows="3" class="form-control">Admission text</textarea> --}}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" id="reject_button" class="btn btn-primary reject_button">Reject</button>
            </div>
            </form>
            </div>
        </div>
        </div>
        {{ Form::close() }}
    </div>

 
@endsection


@section('scripts')

    <script type="module">
        Testing.demo();

        function notify_script(title, text, type, hide) {
            new PNotify({
                title: title,
                text: text,
                type: type,
                hide: hide,
                styling: 'bootstrap3'
            })
        }
        window.sectionurl = "{{ route('section.index') }}";
        window.depturl = "{{ route('department.index') }}";
        window.parenturl = "{{ route('students.index') }}";
        window.deletecontent = "{{ route('DeleteAttachment') }}";

        $(".remove_").click(function() {
            let id = $(this).attr("data-attach");
            let dataid = $(this).attr("data-id");
            console.log(id, "from students");
            AcademicConfig.DeleteContent(id, notify_script)
            $(`#${dataid}_small`).hide();
            $(this).hide();

        });


        AcademicConfig.studentinit(notify_script)
    </script>
    <script>
        $(document).ready(function() {
            var date = new Date();
            var day = ("0" + date.getDate()).slice(-2);
            var month = ("0" + (date.getMonth() + 1)).slice(-2);
            var today = (day) + "/" + (month) + "/" + date.getFullYear();

            // var today = date.getFullYear() + "-" + (month) + "-" + (day);
            console.log(today);
            $('#dateid').val(today);
            console.log("check");
            Testing.demo();
            $('input[type=checkbox][name=address_check]').change(function() {
                if ($(this).is(':checked')) {
                    console.log("here");
                    //getting value from comminication
                    // var building_name=document.getElementById("building_name").value;
                    // var subbuilding_name=document.getElementById("subbuilding_name").value;
                    var house_no = document.getElementById("house_no").value;
                    var street_name = document.getElementById("street_name").value;
                    var postal_code = document.getElementById("postal_code").value;
                    var province = document.getElementById("province").value;
                    var country = document.getElementById("country").value;

                    //assigen value residence

                    document.getElementById("house_no_res").value = house_no;
                    document.getElementById("street_name_res").value = street_name;
                    document.getElementById("postal_code_res").value = postal_code;
                    document.getElementById("province_res").value = province;
                    document.getElementById("country_res").value = country;

                } else {
                    document.getElementById("house_no_res").value = "";
                    document.getElementById("street_name_res").value = "";
                    document.getElementById("postal_code_res").value = "";
                    document.getElementById("province_res").value = "";
                    document.getElementById("country_res").value = "";
                }
            });

            $('input[type=checkbox][name=sibilings__check]').change(function() {

                if ($(this).is(':checked')) {
                    console.log("jai");
                    $(".parent__select").show();
                    $(".parent__info").hide();
                } else {
                    $(".parent__select").hide();
                    $(".parent__info").show();
                }
            });

            $('input[type=checkbox][name=sibilings__check]').change(function() {
                if ($(this).is(':checked')) {
                    console.log("todo");
                } else {
                    $(".parent__Details").hide();
                    document.getElementById("house_no").value = "";
                    document.getElementById("street_name").value = "";
                    document.getElementById("postal_code").value = "";
                    document.getElementById("province").value = "";
                    document.getElementById("country").value = "";
                    document.getElementById("sibilings__check").checked = false;
                    $('#parent_id').val(null).trigger('change');
                }
            });
        });
    </script>
    
    <script>    
        $(document).on('click', '.reject_button', function() { 
            
            function notify_script(title, text, type, hide) {
                new PNotify({
                    title: title,
                    text: text,
                    type: type,
                    hide: hide,
                    styling: 'bootstrap3'
                })
            }
            var rejectionText = CKEDITOR.instances.rejection_text.getData();                        
            console.log("ckeditor msg"+rejectionText);

            let student_id = document.getElementById("student_id").value;
            console.log("student_id : "+student_id);
            

            // Create a form data object
            var formData = new FormData();
            formData.append('rejection_text', rejectionText);
            formData.append('student_id', student_id);
          
            axios.post('{{ route('admission.reject') }}', formData)              
                    .then((response) => {
                        if (response.data.success) {
                        //alert('Admission form rejected successfully!');    
                        notify_script(
                                            "Success",
                                            "Selected Student is rejected sucessfully",
                                            "success",
                                            true
                                        );
                        setTimeout(() => { location.replace("/administrator/admission"); }, 1000); 

                    } else {
                        alert('Failed to reject the admission form. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });           
        });


    </script>

@endsection

@section('script_link')

    <!-- validator -->

    {!! Cms::script('theme/vendors/validator/validator.js') !!}
    {!! Cms::script('theme/vendors/validator/validator_form.js') !!}

@endsection
