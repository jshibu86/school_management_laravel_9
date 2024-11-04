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
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_teacher' , 'class' => 'mybuttn btn btn-sm btn-dafault pull-right')) }}

            <a class="btn btn-default btn-sm pull-right btn-right-spacing" href="{{route('teacher.index')}}" ><i class="glyphicon glyphicon-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

            {{ Form::button('<i class="glyphicon glyphicon-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'mybuttn btn btn-sm btn-dafault pull-right btn-right-spacing']) }}
        </div>

       

        <div class="col-xs-12">
            <div class="bs-example">
                <div class="panel-group" id="accordion">
    
                    <div class="panel panel-default">
                        <div class="panel-heading alertcard ">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">Teacher Info <span class="down-arrow"></span></a>
                            </h4>
                        </div>
                        <div id="collapseThree" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="col-xs-12" style="margin-bottom: 7px;">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                                <div class="item form-group">
                                                    <label class="control-label margin__bottom" for="teacher_name"> Teacher Name <span class="required">*</span>
                                                    </label>
                                                    <div class="feild">
                                                    {{Form::text('teacher_name',@$data->teacher_name,array('id'=>"teacher_name",'class'=>"form-control col-md-7 col-xs-12" ,
                                                'placeholder'=>"e.g joe",'required'=>"required"))}}
                                                    </div>
                                                </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="teacher_name"> Designation <span class="required">*</span>
                                                </label>
                                                <div class="feild">
                                                    {{ Form::select('designation_id',@$designation_list,@$data->designation_id ,
                                                    array('id'=>'designation_id','class' => 'form-control','required' => 'required' ,"placeholder"=>"Select Designation")) }}
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
                                                <label class="control-label margin__bottom" for="mobile"> Dob <span class="required">*</span>
                                                </label>
                                                <div class="feild">
                                                {{Form::date('dob',@$data->dob,array('id'=>"dob",'class'=>"form-control col-md-7 col-xs-12" ,
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
                                                    array('id'=>'status','class' => 'form-control','required' => 'required' )) }}
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
                                                    <label for="thumbnail" class="control-label margin__bottom">Image</label>
                                                    <div class="">
                                                    <span class="input-group-btn">
                                                        @include('layout::widget.image',['name'=>'imagec','id'=>'image','value'=>@$data->image])   
                                                    </span>
                                                    <img id="imagecholder" style="max-height:50px;" src="{{ @$data->image }}">
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
                                                <label class="control-label margin__bottom" for="relation">Relation <span class="required">*</span>
                                                </label>
                                                <div class="feild">
                                                {{Form::text('relation',@$data->relation,array('id'=>"relation",'class'=>"form-control col-md-7 col-xs-12" ,
                                            'placeholder'=>"relation",'required'=>"required"))}}
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
                                                {{Form::text('blood_group',@$data->blood_group,array('id'=>"blood_group",'class'=>"form-control col-md-7 col-xs-12" ,
                                            'placeholder'=>"bloodgroup",'required'=>"required"))}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                        
                                        {{-- //nextrow --}}
                                     <div class="row">
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="handicapped">Handicapped <span class="required"></span>
                                                </label>
                                                <div class="feild">
                                                    <input class="form-check-input address__check" type="checkbox" id="handicapped" name="handicapped">
                                                </div>
                                               
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="maritial_status">Maritial Status <span class="required">*</span>
                                                </label>
                                                <div class="feild">
                                                    {{ Form::select('maritial_status',@$maritialstatus,@$data->maritial_status ,
                                                    array('id'=>'maritial_status','class' => 'form-control','required' => 'required',"placeholder"=>"Select Maritial Status" )) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="national_id_number">National Id <span class="required">*</span>
                                                </label>
                                                <div class="feild">
                                                {{Form::text('national_id_number',@$data->national_id_number,array('id'=>"national_id_number",'class'=>"form-control col-md-7 col-xs-12" ,
                                            'placeholder'=>"national id number",'required'=>"required"))}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="date_ofjoin">Date of Join <span class="required">*</span>
                                                </label>
                                                <div class="feild">
                                                {{Form::date('date_ofjoin',@$data->date_ofjoin,array('id'=>"date_ofjoin",'class'=>"form-control col-md-7 col-xs-12" ,
                                            'placeholder'=>"national id number",'required'=>"required"))}}
                                                </div>
                                            </div>
                                        </div>
                                        <!-- //status -->
                                        
                                    </div>
                        
                                    {{-- //nextrow --}}
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="date_ofjoin">Work Experience <span class="required"></span>
                                                </label>
                                                <div class="feild">
                                                {{Form::text('work_exp',@$data->work_exp,array('id'=>"work_exp",'class'=>"form-control col-md-7 col-xs-12" ,
                                            'placeholder'=>"national id number"))}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="date_ofjoin">Work ExperienceDetail <span class="required"></span>
                                                </label>
                                                <div class="feild">
                                                {{  Form::textarea('work_expdetail', @$data->work_expdetail, [
                                                    'class'      => 'form-control',
                                                    'rows'       => 3, 
                                                    'name'       => 'work_expdetail',
                                                    'id'         => 'work_expdetail',
                                                   
                                                ])}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="date_ofjoin">Date of Releave <span class="required">*</span>
                                                </label>
                                                <div class="feild">
                                                {{Form::date('date_ofreleave',@$data->date_ofreleave,array('id'=>"date_ofreleave",'class'=>"form-control col-md-7 col-xs-12" ,
                                            'placeholder'=>"releave date"))}}
                                                </div>
                                            </div>
                                        </div>
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
                    </div>
                </div>
            </div>
        </div>

            <div class="col-xs-12">
                <div class="card text-white alertcard">
                
                <div class="card-body alert__body">
                    <h4 class="card-title">Teacher Info</h4>
                
                </div>
                </div>
            </div>
        <div class="col-xs-12" style="margin-bottom: 7px;">
            <div class="row">
                <div class="col-xs-12 col-sm-4 col-md-3">
                        <div class="item form-group">
                            <label class="control-label margin__bottom" for="teacher_name"> Teacher Name <span class="required">*</span>
                            </label>
                            <div class="feild">
                            {{Form::text('teacher_name',@$data->teacher_name,array('id'=>"teacher_name",'class'=>"form-control col-md-7 col-xs-12" ,
                        'placeholder'=>"e.g joe",'required'=>"required"))}}
                            </div>
                        </div>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-3">
                    <div class="item form-group">
                        <label class="control-label margin__bottom" for="teacher_name"> Designation <span class="required">*</span>
                        </label>
                        <div class="feild">
                            {{ Form::select('designation_id',@$designation_list,@$data->designation_id ,
                            array('id'=>'designation_id','class' => 'form-control','required' => 'required' ,"placeholder"=>"Select Designation")) }}
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
                        <label class="control-label margin__bottom" for="mobile"> Dob <span class="required">*</span>
                        </label>
                        <div class="feild">
                        {{Form::date('dob',@$data->dob,array('id'=>"dob",'class'=>"form-control col-md-7 col-xs-12" ,
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
                            array('id'=>'status','class' => 'form-control','required' => 'required' )) }}
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
                            <label for="thumbnail" class="control-label margin__bottom">Image</label>
                            <div class="">
                            <span class="input-group-btn">
                                @include('layout::widget.image',['name'=>'imagec','id'=>'image','value'=>@$data->image])   
                            </span>
                            <img id="imagecholder" style="max-height:50px;" src="{{ @$data->image }}">
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
                        <label class="control-label margin__bottom" for="relation">Relation <span class="required">*</span>
                        </label>
                        <div class="feild">
                        {{Form::text('relation',@$data->relation,array('id'=>"relation",'class'=>"form-control col-md-7 col-xs-12" ,
                    'placeholder'=>"relation",'required'=>"required"))}}
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
                        {{Form::text('blood_group',@$data->blood_group,array('id'=>"blood_group",'class'=>"form-control col-md-7 col-xs-12" ,
                    'placeholder'=>"bloodgroup",'required'=>"required"))}}
                        </div>
                    </div>
                </div>
            </div>

                {{-- //nextrow --}}
             <div class="row">
                <div class="col-xs-12 col-sm-4 col-md-3">
                    <div class="item form-group">
                        <label class="control-label margin__bottom" for="handicapped">Handicapped <span class="required"></span>
                        </label>
                        <div class="feild">
                            <input class="form-check-input address__check" type="checkbox" id="handicapped" name="handicapped">
                        </div>
                       
                    </div>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-3">
                    <div class="item form-group">
                        <label class="control-label margin__bottom" for="maritial_status">Maritial Status <span class="required">*</span>
                        </label>
                        <div class="feild">
                            {{ Form::select('maritial_status',@$maritialstatus,@$data->maritial_status ,
                            array('id'=>'maritial_status','class' => 'form-control','required' => 'required',"placeholder"=>"Select Maritial Status" )) }}
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-3">
                    <div class="item form-group">
                        <label class="control-label margin__bottom" for="national_id_number">National Id <span class="required">*</span>
                        </label>
                        <div class="feild">
                        {{Form::text('national_id_number',@$data->national_id_number,array('id'=>"national_id_number",'class'=>"form-control col-md-7 col-xs-12" ,
                    'placeholder'=>"national id number",'required'=>"required"))}}
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-3">
                    <div class="item form-group">
                        <label class="control-label margin__bottom" for="date_ofjoin">Date of Join <span class="required">*</span>
                        </label>
                        <div class="feild">
                        {{Form::date('date_ofjoin',@$data->date_ofjoin,array('id'=>"date_ofjoin",'class'=>"form-control col-md-7 col-xs-12" ,
                    'placeholder'=>"national id number",'required'=>"required"))}}
                        </div>
                    </div>
                </div>
                <!-- //status -->
                
            </div>

            {{-- //nextrow --}}
            <div class="row">
                <div class="col-xs-12 col-sm-4 col-md-3">
                    <div class="item form-group">
                        <label class="control-label margin__bottom" for="date_ofjoin">Work Experience <span class="required"></span>
                        </label>
                        <div class="feild">
                        {{Form::text('work_exp',@$data->work_exp,array('id'=>"work_exp",'class'=>"form-control col-md-7 col-xs-12" ,
                    'placeholder'=>"national id number"))}}
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-3">
                    <div class="item form-group">
                        <label class="control-label margin__bottom" for="date_ofjoin">Work ExperienceDetail <span class="required"></span>
                        </label>
                        <div class="feild">
                        {{  Form::textarea('work_expdetail', @$data->work_expdetail, [
                            'class'      => 'form-control',
                            'rows'       => 3, 
                            'name'       => 'work_expdetail',
                            'id'         => 'work_expdetail',
                           
                        ])}}
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-3">
                    <div class="item form-group">
                        <label class="control-label margin__bottom" for="date_ofjoin">Date of Releave <span class="required">*</span>
                        </label>
                        <div class="feild">
                        {{Form::date('date_ofreleave',@$data->date_ofreleave,array('id'=>"date_ofreleave",'class'=>"form-control col-md-7 col-xs-12" ,
                    'placeholder'=>"releave date"))}}
                        </div>
                    </div>
                </div>
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

        <div class="col-xs-12">
            <div class="card text-white alertcard">
             
              <div class="card-body alert__body">
                <h4 class="card-title">Address for Communication</h4>
               
              </div>
            </div>
        </div>

        <div class="col-xs-12" style="margin-bottom: 7px;">
            <div class="row">
                <div class="col-xs-12 col-sm-4 col-md-3">
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
                </div>
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
                        <label class="control-label margin__bottom" for="building_name"> Province <span class="required">*</span>
                        </label>
                        <div class="feild">
                        {{Form::text('province',@$address_communication->province,array('id'=>"province",'class'=>"form-control col-md-7 col-xs-12" ,
                    'placeholder'=>"province",'required'=>"required"))}}
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
        <div class="col-xs-12">
            <div class="card text-white alertcard">
             
              <div class="card-body alert__body">
                <h4 class="card-title">Address for Residence</h4>
               
              </div>
            </div>
        </div>

        <div class="col-xs-12">
            
                <div class="form-check communication">
                    
                    <label class="form-check-label communication_label" for="flexCheckDefault">
                      Same as Address for Communication
                    </label>
                    <input class="form-check-input address__check" type="checkbox" value="1" id="address__check" name="address__check">
                  </div>
            
        </div>

        <div class="col-xs-12" style="margin-bottom: 7px;">
            
            <div class="row">
                <div class="col-xs-12 col-sm-4 col-md-3">
                    <div class="item form-group">
                        <label class="control-label margin__bottom" for="building_name_res"> Building Name <span class="required">*</span>
                        </label>
                        <div class="feild">
                        {{Form::text('building_name_res',@$address_residence->building_name,array('id'=>"building_name_res",'class'=>"form-control col-md-7 col-xs-12" ,
                    'placeholder'=>"building name residence"))}}
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-3">
                    <div class="item form-group">
                        <label class="control-label margin__bottom" for="subbuilding_name_res"> Subbuilding Name <span class="required">*</span>
                        </label>
                        <div class="feild">
                        {{Form::text('subbuilding_name_res',@$address_residence->subbuilding_name,array('id'=>"subbuilding_name_res",'class'=>"form-control col-md-7 col-xs-12" ,
                    'placeholder'=>"subbuilding name residence"))}}
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-3">
                    <div class="item form-group">
                        <label class="control-label margin__bottom" for="house_no_res"> House no <span class="required">*</span>
                        </label>
                        <div class="feild">
                        {{Form::text('house_no_res',@$address_residence->house_no,array('id'=>"house_no_res",'class'=>"form-control col-md-7 col-xs-12" ,
                    'placeholder'=>"house no residence"))}}
                        </div>
                     </div>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-3">
                    <div class="item form-group">
                        <label class="control-label margin__bottom" for="street_name_res"> Street Name <span class="required">*</span>
                        </label>
                        <div class="feild">
                        {{Form::text('street_name_res',@$address_residence->street_name,array('id'=>"street_name_res",'class'=>"form-control col-md-7 col-xs-12" ,
                    'placeholder'=>"street name residence"))}}
                        </div>
                    </div>
                 </div>
                <div class="col-xs-12 col-sm-4 col-md-3">
                    <div class="item form-group">
                        <label class="control-label margin__bottom" for="postal_code_res"> Postal Code <span class="required">*</span>
                        </label>
                        <div class="feild">
                        {{Form::text('postal_code_res',@$address_residence->postal_code,array('id'=>"postal_code_res",'class'=>"form-control col-md-7 col-xs-12" ,
                    'placeholder'=>"postal code residence"))}}
                        </div>
                     </div>
                 </div>
                <div class="col-xs-12 col-sm-4 col-md-3">
                    <div class="item form-group">
                        <label class="control-label margin__bottom" for="province_res"> Province <span class="required">*</span>
                        </label>
                        <div class="feild">
                        {{Form::text('province_res',@$address_residence->province,array('id'=>"province_res",'class'=>"form-control col-md-7 col-xs-12" ,
                    'placeholder'=>"province"))}}
                        </div>
                     </div>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-3">
                    <div class="item form-group">
                        <label class="control-label margin__bottom" for="country_res"> Country <span class="required">*</span>
                        </label>
                        <div class="feild">
                        {{Form::text('country_res',@$address_residence->country,array('id'=>"country_res",'class'=>"form-control col-md-7 col-xs-12" ,
                    'placeholder'=>"country residence"))}}
                        </div>
                     </div>
                 </div>
            </div>

        </div>
       
        
       
       
        {{Form::close()}}
    

        
    </div>

@endsection

@section('script_link')
<!-- //js -->

<script type="text/javascript">
var error=false;
console.log(error);
@if($errors->any()){
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

<script>
   
   $(document).ready(function() {
   
    $('input[type=checkbox][name=address__check]').change(function() {
        if ($(this).is(':checked')) {
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
@endsection


