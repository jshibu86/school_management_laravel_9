@extends('layout::admin.master')

@section('title','homework')
@section('style')


@endsection
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('homework.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'homework-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('homework.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
        <div class="box-header with-border mar-bottom20">
            
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_homework' , 'class' => 'btn btn-success btn-sm m-1  px-3')) }}

            @if (@$layout == "create")

            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save-Continue', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat_continue' , 'value' => 'Edit_wallet' , 'class' => 'btn btn-dark btn-sm m-1  px-3')) }}

            @endif

            <a class="btn btn-info btn-sm m-1  px-3" href="{{route('homework.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

            {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }}
        </div>
      

            

         
           @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit Homework" : "Create homework"])

             <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Create Homework</h5>
                    <hr/>
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="title">Title <span class="required">*</span>
                                    </label>
                                    <div class="feild">
                                        {{ Form::text('title',@$data->title ,
                                        array('id'=>'title','class' => 'col-md-7 col-xs-12 form-control','placeholder'=>"Enter Title",'required'=>"required")) }}
                                    </div>
                                </div>
                                    
                            </div>
                        <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                <label class="control-label margin__bottom" for="class_id">Class <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{ Form::select('class_id',@$class_list,@$data->class_id ,
                                    array('id'=>'class_id','class' => 'col-md-7 col-xs-12 form-control single-select','placeholder'=>"Select Class",'required'=>"required")) }}
                                </div>
                            </div>
                                
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                                <label class="control-label margin__bottom" for="section_id">Section <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{ Form::select('section_id',@$sections,@$data->section_id ,
                                    array('id'=>'section_id','class' => 'col-md-7 col-xs-12 form-control single-select','required'=>"required")) }}
                                </div>
                            </div>
                                
                        </div>

                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                                <label class="control-label margin__bottom" for="subject_id">Subject <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{ Form::select('subject_id',@$subjects,@$data->subject_id ,
                                    array('id'=>'subject_id','class' => 'col-md-7 col-xs-12 form-control single-select','placeholder'=>"Select Subject",'required'=>"required")) }}
                                </div>
                            </div>
                                
                        </div>

                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                                <label class="control-label margin__bottom" for="dept_id"> Department <span class="required">*</span>
                                </label>
                                <div class="feild">
                                {{ Form::select('dept_id',@$departments,@$data->dept_id ,
                                array('id'=>'dept_id','class' => 'form-control single-select',"placeholder"=>"Select department" )) }}
                                </div>
                            </div>
                        </div>
                       
                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                                <label class="control-label margin__bottom" for="homework_date">Homework Date <span class="required">*</span>
                                </label>
                                <div class="feild">
                                {{Form::text('homework_date',@$data->homework_date,array('id'=>"homework_date",'class'=>"form-control col-md-7 col-xs-12 datepicker" ,
                                'required'=>"required","readonly"))}}
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                                <label class="control-label margin__bottom" for="submission_date">Submission Date <span class="required">*</span>
                                </label>
                                @php
                                    if(@$layout =="create")
                                    {
                                        $class="datepicker";
                                    }else{
                                        $class="datepick";
                                    }
                                @endphp
                                <div class="feild">
                                {{Form::text('submission_date',@$data->submission_date,array('id'=>"submission_date",'class'=>"form-control col-md-7 col-xs-12" ." ".@$class ,
                                'required'=>"required"))}}
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                                <label class="control-label margin__bottom" for="submission_date">Attach File <span class="required"></span>
                                </label>
                                <div class="feild">
                                    <input class="form-control thumb" type="file" id="attachments_img_homework" name="attachments" data-id="homework">
                                    @if (@$data->attachment)
                                        <a href="{{ @$data->attachment }}" target="_blank"><i class="fa fa-file"></i>View Attachfile</a>
                                    @endif
                                   
                                </div>
                            </div>
                        </div>
                        </div>
                    
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-9">
                                <div class="item form-group">
                                    <label for="thumbnail" class="control-label margin__bottom">Description<span>*</span></label>
                                    <div class="">
                                    <span class="input-group-btn">
                                        @include('layout::widget.ckeditor',['name'=>'homework_description','id'=>'schoolicon','content'=>@$data->homework_description ?@$data->homework_description : old("homework_description") ])   
                                    </span>
                                  
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
    window.sectionurl="{{ route('section.index') }}";
    window.subjecturl="{{ route('subject.index') }}";

    @if (Session::get("ACTIVE_GROUP") == "Teacher")
    AcademicConfig.CommonClassSectionSubjects(notify_script,"homework");
    @else
    AcademicConfig.CommonClassSectionSubjects(notify_script);
    @endif
   

   
</script>
@endsection

@section("script_link")

    <!-- validator -->

    {!! Cms::script("theme/vendors/validator/validator.js") !!}
    {!! Cms::script("theme/vendors/validator/validator_form.js") !!}
   
   
   
@endsection
