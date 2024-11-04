@extends('layout::admin.master')

@section('title','teacher')
@section('style')
<style>
    .address__check{
        width: 30px;
        height: 18px;
    }
    .communication{
        display: flex;
        align-items: center;

    }
    .communication_label{
        margin-top: 5px;
    }
    .kin .select2-container{
        width: 100%!important;
    }
</style>

@endsection
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('teacher.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'teacher-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('teacher.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
        <div class="box-header with-border mar-bottom20">
            

          
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_teacher' , 'class' => 'btn btn-success btn-sm m-1  px-3')) }}

            <a class="btn btn-info btn-sm m-1  px-3" href="{{route('teacher.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

            {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }}

           

            @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit Teacher" : "Create Teacher"])
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{$layout == "edit" ?"Edit Teacher" : "Create New Teacher"}}</h5>
                <hr/>
                <div class="accordion accordion-flush" id="accordionFlushExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                Teacher Info
                            </button>
                        </h2>
                        <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">
                                <div class="col-xs-12" style="margin-bottom: 7px;">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                                <div class="item form-group">
                                                    <label class="control-label margin__bottom" for="teacher_name"> Staff Name <span class="required">*</span>
                                                    </label>
                                                    <div class="feild">
                                                    {{Form::text('teacher_name',@$data->teacher_name,array('id'=>"teacher_name",'class'=>"form-control col-md-7 col-xs-12" ,
                                                'placeholder'=>"Staff name",'required'=>"required"))}}
                                                    </div>
                                                </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="teacher_name"> Designation <span class="required">*</span>
                                                </label>
                                                <div class="feild">
                                                    {{ Form::select('designation_id',@$designation_list,@$data->designation_id ,
                                                    array('id'=>'designation_id','class' => 'form-control single-select','required' => 'required' ,"placeholder"=>"Select Designation")) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="email"> Email <span class="required">*</span>
                                                </label>
                                                <div class="feild">
                                                {{Form::text('email',@$data->email,array('id'=>"email",'class'=>"form-control col-md-7 col-xs-12" ,
                                            'placeholder'=>"Enter Email",'required'=>"required"))}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="mobile"> Mobile <span class="required">*</span>
                                                </label>
                                                <div class="feild">
                                                {{Form::text('mobile',@$data->mobile,array('id'=>"mobile",'class'=>"form-control col-md-7 col-xs-12" ,
                                            'placeholder'=>"Enter Mobile Number",'required'=>"required"))}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        {{-- //nextrow --}}
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="mobile"> DOB <span class="required">*</span>
                                                </label>
                                                <div class="feild">
                                                {{Form::text('dob', @$data->dob ? \Carbon\Carbon::parse($data->dob)->format('m/d/Y') : '',array('id'=>"dob",'class'=>"form-control col-md-7 col-xs-12 dobdate" ,
                                            'placeholder'=>"dob",'required'=>"required"))}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="gender"> Gender <span class="required">*</span>
                                                </label>
                                                <div class="feild">
                                                    {{ Form::select('gender',@$gender,@$data->gender ,
                                                    array('id'=>'gender_','class' => 'form-control single-select','required' => 'required' )) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="qualification"> Qualification <span class="required">*</span>
                                                </label>
                                                <div class="feild">
                                                {{Form::text('qualification',@$data->qualification,array('id'=>"qualification",'class'=>"form-control col-md-7 col-xs-12" ,
                                            'placeholder'=>"Enter Qualification",'required'=>"required"))}}
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                                <div class="item form-group">
                                                    <label for="thumbnail" class="control-label margin__bottom">Image<span>*</span></label>
                                                    <div class="">
                                                    <span class="input-group-btn">
                                                        <input class="form-control thumb" type="file" id="imagec_img_imagec" name="imagec" data-id="imagec"  accept="image/png, image/jpeg">


                                                       
                                                    </span>
                                                    <img id="imagecholder" style="max-height:50px;" src="{{ @$data->image }}">

                                                    @if (@$layout !="create" && @$data->image)
                                                    <span class="back_to remove" id="remove_img_imagec" data-id="imagec" data-class="imagec" >X</span>
                                                   

                                                    @else
                                                    <span class="back_to remove" id="remove_img_imagec" data-id="imagec" data-class="imagec" style="display:none;">X</span>
                                                   
                                                        
                                                    @endif
                                                    
                                                    
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                        
                                        {{-- //nextrow --}}
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="guardian_name">Parent/Guardian Name <span class="required">*</span>
                                                </label>
                                                <div class="feild">
                                                {{Form::text('guardian_name',@$data->guardian_name,array('id'=>"guardian_name",'class'=>"form-control col-md-7 col-xs-12" ,
                                            'placeholder'=>"guardian name",'required'=>"required"))}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="relation">Relationship <span class="required">*</span>
                                                </label>
                                                <div class="feild">
                                                {{Form::text('relation',@$data->relation,array('id'=>"relation",'class'=>"form-control col-md-7 col-xs-12" ,
                                            'placeholder'=>"relationship",'required'=>"required"))}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="guardian_mobile">Parent/Guardian Mobile <span class="required">*</span>
                                                </label>
                                                <div class="feild">
                                                {{Form::text('guardian_mobile',@$data->guardian_mobile,array('id'=>"guardian_mobile",'class'=>"form-control col-md-7 col-xs-12" ,
                                            'placeholder'=>"guardian mobile",'required'=>"required"))}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="blood_group">Blood Group <span class="required">*</span>
                                                </label>
                                                <div class="feild">
                                                    {{ Form::select('blood_group',@$bloodgroup,@$data->blood_group ,
                                                    array('id'=>'blood_group','class' => 'form-control single-select','required' => 'required',"placeholder"=>"Select Blood group" )) }}
                                              
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                        
                                        {{-- //nextrow --}}
                                     <div class="row">
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="handicapped"> Handicapped
                                                </label>
                                                <div class="feild">
                                                    {{ Form::select('handicapped',['0'=>"No","1"=>"Yes"],@$data->handicapped ,
                                                    array('id'=>'status','class' => 'form-control single-select')) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="maritial_status">Marital Status <span class="required">*</span>
                                                </label>
                                                <div class="feild">
                                                    {{ Form::select('maritial_status',@$maritialstatus,@$data->maritial_status ,
                                                    array('id'=>'maritial_status','class' => 'form-control single-select','required' => 'required',"placeholder"=>"Select Marital Status" )) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="religion_">Religion<span class="required">*</span>
                                                </label>
                                                <div class="feild">
                                                    {{ Form::select('religion',@$religion,@$data->religion ,
                                                    array('id'=>'religion_','class' => 'form-control single-select','required' => 'required',"placeholder"=>"Select Religion" )) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="national_id_number">National Id <span class="required"></span>
                                                </label>
                                                <div class="feild">
                                                {{Form::text('national_id_number',@$data->national_id_number,array('id'=>"national_id_number",'class'=>"form-control col-md-7 col-xs-12" ,
                                            'placeholder'=>"national id number"))}}
                                                </div>
                                            </div>
                                        </div>
                                       
                                        <!-- //status -->
                                        
                                    </div>
                        
                                    {{-- //nextrow --}}
                                    <div class="row">
                                       
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="date_ofjoin">Date of Join <span class="required">*</span>
                                                </label>
                                                <div class="feild">
                                                {{Form::text('date_ofjoin', @$data->date_ofjoin ? \Carbon\Carbon::parse($data->date_ofjoin)->format('m/d/Y') : '',array('id'=>"date_ofjoin",'class'=>"form-control reminderdate col-md-7 col-xs-12" ,
                                            'placeholder'=>"Date of Join",'required'=>"required"))}}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="school_name"> Departments <span class="required">*</span>
                                                </label>
                                                <div class="feild designation_select_feild">

                                                   
                                                {{ Form::select('stu_department[]',@$departments,@$selected_departments ,
                                                 array('id'=>'designation_type','class' => 'form-control multiple-select','required' => 'required',"multiple"=>true)) }}
                                                
                                                </div>
                                                
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    {{-- //second --}}
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                                Work Experience
                            </button>
                        </h2>
                        <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-4 col-md-3">
                                        <div class="item form-group">
                                            <label class="control-label margin__bottom" for="date_ofjoin">Company/School Name <span class="required"></span>
                                            </label>
                                            <div class="feild">
                                            {{Form::text('emp_name',@$data->emp_name,array('id'=>"emp_name",'class'=>"form-control col-md-7 col-xs-12" ,
                                        'placeholder'=>"Company/school Name"))}}
                                            </div>
                                        </div>
                                    </div>
                                   
                                    <div class="col-xs-12 col-sm-4 col-md-3">
                                        <div class="item form-group">
                                            <label class="control-label margin__bottom" for="date_ofjoin">Job Role <span class="required"></span>
                                            </label>
                                            <div class="feild">
                                            {{Form::text('job_role',@$data->job_role,array('id'=>"job_role",'class'=>"form-control col-md-7 col-xs-12" ,
                                        'placeholder'=>"(e.g) Training Staff"))}}
                                            </div>
                                        </div>
                                    </div>
                                   
                                    <div class="col-xs-12 col-sm-4 col-md-3">
                                        <div class="item form-group">
                                            <label class="control-label margin__bottom" for="date_ofjoin">Net Pay <span class="required"></span>
                                            </label>
                                            <div class="feild">
                                            {{Form::number('net_pay',@$data->net_pay,array('id'=>"net_pay",'class'=>"form-control col-md-7 col-xs-12" ,
                                        'placeholder'=>"1000"))}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 col-md-3">
                                        <div class="item form-group">
                                            <label class="control-label margin__bottom" for="location">Job Location <span class="required"></span>
                                            </label>
                                            <div class="feild">
                                            {{Form::text('location',@$data->location,array('id'=>"location",'class'=>"form-control col-md-7 col-xs-12" ,
                                        'placeholder'=>"Nigeria Location"))}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 col-md-3">
                                        <div class="item form-group">
                                            <label class="control-label margin__bottom" for="start_date">Start Date <span class="required"></span>
                                            </label>
                                            <div class="feild">
                                            {{Form::text('start_date',@$data->start_date ? \Carbon\Carbon::parse($data->start_date)->format('m/d/Y') : '',array('id'=>"start_date",'class'=>"form-control col-md-7 col-xs-12 dobdate" ,
                                        'placeholder'=>"start date"))}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 col-md-3">
                                        <div class="item form-group">
                                            <label class="control-label margin__bottom" for="start_date">End Date <span class="required"></span>
                                            </label>
                                            <div class="feild">
                                            {{Form::text('end_date',@$data->end_date ? \Carbon\Carbon::parse($data->end_date)->format('m/d/Y') : '',array('id'=>"end_date",'class'=>"form-control col-md-7 col-xs-12 datepicker_academic_start" ,
                                        'placeholder'=>"end date"))}}
                                            </div>
                                        </div>
                                    </div>
                                   
                                    {{-- <div class="col-xs-12 col-sm-4 col-md-3">
                                        <div class="item form-group">
                                            <label class="control-label margin__bottom" for="date_ofjoin">Date of Releave <span class="required"></span>
                                            </label>
                                            <div class="feild">
                                            {{Form::date('date_ofreleave',@$data->date_ofreleave,array('id'=>"date_ofreleave",'class'=>"form-control col-md-7 col-xs-12" ,
                                        'placeholder'=>"releave date"))}}
                                            </div>
                                        </div>
                                    </div> --}}
                                    <div class="col-xs-12 col-sm-4 col-md-3">
                                        <div class="item form-group">
                                            <label class="control-label margin__bottom" for="exp_years">Total years of experience <span class="required"></span>
                                            </label>
                                            <div class="feild">
                                            {{Form::number('work_exp',@$data->work_exp,array('id'=>"exp_years",'class'=>"form-control col-md-7 col-xs-12" ,
                                        'placeholder'=>"experience"))}}
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-4 col-md-3">
                                        <div class="item form-group">
                                            <label class="control-label margin__bottom" for="reason_forleave">Reason for Releave <span class="required"></span>
                                            </label>
                                            <div class="feild">
                                            {{  Form::textarea('reason_forleave', @$data->reason_forleave, [
                                                'class'      => 'form-control',
                                                'rows'       => 3, 
                                                'name'       => 'reason_forleave',
                                                'id'         => 'reason_forleave',
                                               
                                            ])}}
                                            </div>
                                        </div>
                                    </div>
                                   
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    {{-- //third --}}
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                                Address
                            </button>
                        </h2>
                        <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">
                                <div class="col-xs-12" style="margin-bottom: 7px;">
                                    <div class="row">
                                        {{-- <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="building_name"> Building Name <span class="required">*</span>
                                                </label>
                                                <div class="feild">
                                                {{Form::text('building_name',@$address_communication->building_name,array('id'=>"building_name",'class'=>"form-control col-md-7 col-xs-12" ,
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
                                                <label class="control-label margin__bottom" for="building_name"> House no <span class="required">*</span>
                                                </label>
                                                <div class="feild">
                                                {{Form::text('house_no',@$address_communication->house_no,array('id'=>"house_no",'class'=>"form-control col-md-7 col-xs-12" ,
                                            'placeholder'=>"house no",'required'=>"required"))}}
                                                </div>
                                             </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="building_name"> Street Name <span class="required">*</span>
                                                </label>
                                                <div class="feild">
                                                {{Form::text('street_name',@$address_communication->street_name,array('id'=>"street_name",'class'=>"form-control col-md-7 col-xs-12" ,
                                            'placeholder'=>"street name",'required'=>"required"))}}
                                                </div>
                                            </div>
                                         </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="building_name"> Postal Code <span class="required">*</span>
                                                </label>
                                                <div class="feild">
                                                {{Form::text('postal_code',@$address_communication->postal_code,array('id'=>"postal_code",'class'=>"form-control col-md-7 col-xs-12" ,
                                            'placeholder'=>"postal code",'required'=>"required"))}}
                                                </div>
                                             </div>
                                         </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="building_name"> City <span class="required">*</span>
                                                </label>
                                                <div class="feild">
                                                {{Form::text('province',@$address_communication->province,array('id'=>"province",'class'=>"form-control col-md-7 col-xs-12" ,
                                            'placeholder'=>"city",'required'=>"required"))}}
                                                </div>
                                             </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="country"> Country <span class="required">*</span>
                                                </label>
                                                <div class="feild">
                                                {{Form::text('country',@$address_communication->country,array('id'=>"country",'class'=>"form-control col-md-7 col-xs-12" ,
                                            'placeholder'=>"country",'required'=>"required"))}}
                                                </div>
                                             </div>
                                         </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    {{-- //four --}}
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingfour">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefour" aria-expanded="false" aria-controls="flush-collapsefour">
                                Next of KIN
                            </button>
                        </h2>
                        <div id="flush-collapsefour" class="accordion-collapse collapse" aria-labelledby="flush-headingfour" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">
                                <div class="col-xs-12" style="margin-bottom: 7px;">
            
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="kin_fullname"> Name <span class="required"></span>
                                                </label>
                                                <div class="feild">
                                                {{Form::text('kin_fullname',@$data->kin_fullname,array('id'=>"kin_fullname",'class'=>"form-control col-md-7 col-xs-12" ,
                                            'placeholder'=>"Full name"))}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="kin_relationship"> Relationship <span class="required"></span>
                                                </label>
                                                <div class="feild">
                                                {{Form::text('kin_relationship',@$data->kin_relationship,array('id'=>"kin_relationship",'class'=>"form-control col-md-7 col-xs-12" ,
                                            'placeholder'=>"Relationship"))}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="kin_phonenumber"> Mobile <span class="required"></span>
                                                </label>
                                                <div class="feild">
                                                {{Form::number('kin_phonenumber',@$data->kin_phonenumber,array('id'=>"kin_phonenumber",'class'=>"form-control col-md-7 col-xs-12" ,
                                            'placeholder'=>"Phonenumber"))}}
                                                </div>
                                             </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="kin_email"> Email <span class="required"></span>
                                                </label>
                                                <div class="feild">
                                                {{Form::text('kin_email',@$data->kin_email,array('id'=>"kin_email",'class'=>"form-control col-md-7 col-xs-12" ,
                                            'placeholder'=>"Email"))}}
                                                </div>
                                            </div>
                                         </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="kin_occupation"> Occupation <span class="required"></span>
                                                </label>
                                                <div class="feild">
                                                {{Form::text('kin_occupation',@$data->kin_occupation,array('id'=>"kin_occupation",'class'=>"form-control col-md-7 col-xs-12" ,
                                            'placeholder'=>"occupation"))}}
                                                </div>
                                             </div>
                                         </div>
                                         <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="kin_religion"> Religion <span class="required"></span>
                                                </label>
                                                <div class="feild kin">
                                                    {{ Form::select('kin_religion',@$religion,@$data->kin_religion ,
                                                    array('id'=>'religion_kin','class' => 'form-control single-select',"placeholder"=>"Select Religion" )) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="kin_address">Address <span class="required"></span>
                                                </label>
                                                <div class="feild">
                                                {{  Form::textarea('kin_address', @$data->kin_address, [
                                                    'class'      => 'form-control',
                                                    'rows'       => 4, 
                                                    'name'       => 'kin_address',
                                                    'id'         => 'kin_address',
                                                   
                                                ])}}
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
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseFour">
                            Document Submission
                        </button>
                        </h2>
                        <div id="flush-collapseFour" class="accordion-collapse collapse" aria-labelledby="flush-headingFour" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">

                                
                                <div class="col-xs-12" style="margin-bottom: 7px;">
                                    <div class="row">
                                    
                                        
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label for="thumbnail" class="control-label margin__bottom"> Upload Document 1</label>
                                                <div class="">
                                                <span class="input-group-btn">

                                                    <input class="form-control thumb" type="file" id="birth_certificate_img_birth_certificate" name="birth_certificate" data-id="birth_certificate" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel,.pdf">
                                                       
                                                </span>
                                               

                                                @if (@$layout !="create" && is_array(@$attachements["birth_certificate"]))
                                                
                                                @foreach($attachements['birth_certificate'] as $key =>$Value)
                                                <small id="birth_certificate_small">{{ @$Value}}</small>
                                                <span class="back_to remove remove_" 
                                                data-attach="{{@$key}} " id="remove_img_birth_certificate" data-id="birth_certificate" data-class="birth_certificate">X</span>
                                                @endforeach

                                                @else

                                                <span class="back_to remove" id="remove_img_birth_certificate" style="display:none;" data-id="birth_certificate" data-class="birth_certificate" >X</span>
                                                    
                                                @endif
                                                
                                                
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label for="thumbnail" class="control-label margin__bottom">Upload Document 2</label>
                                                <div class="">
                                                <span class="input-group-btn">
                                                    <input class="form-control thumb" type="file" id="tranfer_certificate_img_tranfer_certificate" name="tranfer_certificate" data-id="tranfer_certificate" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel,.pdf" >
                                                    
                                                </span>
                                               

                                                @if (@$layout !="create" && is_array(@$attachements['tranfer_certificate']))
                                               
                                                @foreach($attachements['tranfer_certificate'] as $key =>$Value)
                                                <small id="tranfer_certificate_small">{{ @$Value}}</small>
                                                <span class="back_to remove remove_" 
                                                data-attach="{{@$key}}" id="remove_img_tranfer_certificate" data-id="tranfer_certificate" data-class="tranfer_certificate">X</span>
                                                @endforeach

                                                @else
                                                <span class="back_to remove" id="remove_img_tranfer_certificate" style="display:none;" data-id="tranfer_certificate" data-class="tranfer_certificate" >X</span>

                                              
                                                    
                                                @endif
                                                
                                                
                                                </div>
                                            </div>
                                        </div>  
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label for="thumbnail" class="control-label margin__bottom">Upload Document 3</label>
                                                <div class="">
                                                <span class="input-group-btn">
                                                    <input class="form-control thumb" type="file" id="mark_sheet_img_mark_sheet" name="mark_sheet" data-id="mark_sheet" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel,.pdf" >

                                                
                                                </span>
                                               

                                                @if (@$layout !="create" && is_array(@$attachements['mark_sheet']))
                                               
                                                @foreach($attachements['mark_sheet'] as $key =>$Value)
                                                <small id="mark_sheet_small">{{ @$Value}}</small>
                                                <span class="back_to remove remove_" 
                                                data-attach="{{@$key}}" id="remove_img_mark_sheet" data-id="mark_sheet" data-class="mark_sheet">X</span>
                                                @endforeach
                                               

                                                @else
                                                <span class="back_to remove" id="remove_img_mark_sheet" style="display:none;" data-id="mark_sheet" data-class="mark_sheet" >X</span>

                                               
                                                    
                                                @endif
                                                
                                                
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label for="thumbnail" class="control-label margin__bottom">Upload Document 4</label>
                                                <div class="">
                                                <span class="input-group-btn">

                                                    <input class="form-control thumb" type="file" id="national_id_certificate_img_national_id_certificate" name="national_id_certificate" data-id="national_id_certificate" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel,.pdf" >


                                                      
                                                </span>
                                               

                                                @if (@$layout !="create" && is_array(@$attachements['national_id_certificate']))
                                              
                                                @foreach($attachements['national_id_certificate'] as $key =>$Value)
                                                <small id="national_id_certificate_small">{{ @$Value}}</small>
                                                <span class="back_to remove remove_" 
                                                data-attach="{{@$key}}" id="remove_img_national_id_certificate" data-id="national_id_certificate" data-class="national_id_certificate">X</span>
                                                @endforeach

                                                @else

                                                <span class="back_to remove" id="remove_img_national_id_certificate" style="display:none;" data-id="national_id_certificate" data-class="national_id_certificate" >X</span>


                                               
                                                    
                                                @endif
                                                
                                                
                                                </div>
                                            </div>
                                        </div>  
                                       
                                    </div>

                                   
                                </div>
                                <div class="col-xs-12 mt-3">
                                    <div class="col-sm-6">
                                      
                                        
                                          
                                          <p class="card-text text-danger panel_alert"><i class="fa fa-hand-o-right text-danger" aria-hidden="true"></i> Upload Respective Student Document </p>
                                          <p class="card-text text-danger panel_alert"><i class="fa fa-hand-o-right text-danger" aria-hidden="true"></i>Maximum filesize <strong>2mb</strong> </p>
                                         
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

      

            
      

        
       
      

       
        
       
       
        {{Form::close()}}
    

        
    </div>

@endsection

@section('script')
<!-- //js -->

<script type="text/javascript">
var error=false;

console.log(error);
@if($errors->any() || $layout=="edit"){
    $(".collapse").removeClass("in");
    $(".collapse").addClass("in");
}
@endif

    $(".collapse")
    .on("show.bs.collapse", function() {
      $(this)
        .parent()
        .find(".down-arrow")
        .addClass("rotate");
    })
    .on("hide.bs.collapse", function() {
      $(this)
        .parent()
        .find(".down-arrow")
        .removeClass("rotate");
    });

</script>
@endsection

@section('scripts')

<script type="module">
    function notify_script(title,text,type,hide) {
            new PNotify({
                title: title,
                text: text,
                type: type,
                hide: hide,
                styling: 'bootstrap3'
            })
        }
        window.deletecontent="{{ route('DeleteAttachmentTeacher') }}";

$(".remove_").click(function(){
            let id = $(this).attr("data-attach");
            let dataid=$(this).attr("data-id");
            console.log(id,"from students");
           AcademicConfig.DeleteContent(id,notify_script)
           $(`#${dataid}_small`).hide();
            $(this).hide();
        
        });
</script>

<script>
   
   $(document).ready(function() {
   console.log("check");
    $('input[type=checkbox][name=address__check]').change(function() {
        if ($(this).is(':checked')) {
            console.log("here");
            //getting value from comminication
            var building_name=document.getElementById("building_name").value;
            var subbuilding_name=document.getElementById("subbuilding_name").value;
            var house_no=document.getElementById("house_no").value;
            var street_name=document.getElementById("street_name").value;
            var postal_code=document.getElementById("postal_code").value;
            var province=document.getElementById("province").value;
            var country=document.getElementById("country").value;

            //assigen value residence
            

            document.getElementById("building_name_res").value=building_name;
            document.getElementById("subbuilding_name_res").value=subbuilding_name;
            document.getElementById("house_no_res").value=house_no;
            document.getElementById("street_name_res").value=street_name;
            document.getElementById("postal_code_res").value=postal_code;
            document.getElementById("province_res").value=province;
            document.getElementById("country_res").value=country;
           
           // alert(`${this.value} is checked`);
        }
        else {
            document.getElementById("building_name_res").value="";
            document.getElementById("subbuilding_name_res").value="";
            document.getElementById("house_no_res").value="";
            document.getElementById("street_name_res").value="";
            document.getElementById("postal_code_res").value="";
            document.getElementById("province_res").value="";
            document.getElementById("country_res").value="";
        }
    });
});
</script>
@endsection

@section("script_link")

    <!-- validator -->

    {!! Cms::script("theme/vendors/validator/validator.js") !!}
    {!! Cms::script("theme/vendors/validator/validator_form.js") !!}
   
   
   
@endsection

   



