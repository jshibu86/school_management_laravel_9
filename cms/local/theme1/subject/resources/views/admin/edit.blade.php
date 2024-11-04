@extends('layout::admin.master')

@section('title','subject')
@section('style')


@endsection
@section('body')
    <div class="x_content">
       

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('subject.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'subject-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('subject.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
        <div class="box-header with-border mar-bottom20">
           
           
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_subject' , 'class' => 'btn btn-success btn-sm m-1  px-3')) }}

            
            @if (@$layout == "create")

            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save-Continue', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat_continue' , 'value' => 'Edit_wallet' , 'class' => 'btn btn-dark btn-sm m-1  px-3')) }}

            @endif

            <a class="btn btn-info btn-sm m-1  px-3" href="{{route('subject.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

            {{-- {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }} --}}


           

          
            @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit Subject" : "Create Subject"])
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{$layout == "edit" ?"Edit Subject" : "Create Subject"}}</h5>
                <hr/>
                <div class="col-xs-12">
                    <div class="row">
                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                                <label class="control-label margin__bottom" for="subject_name">Subject Name <span class="required">*</span>
                                </label>
                                <div class="feild">
                                {{Form::text('name',@$data->name,array('id'=>"name",'class'=>"form-control col-md-7 col-xs-12" ,
                                 'placeholder'=>"e.g English",'required'=>"required"))}}
                                </div>
                            </div>
                         </div>
                         <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                                <label class="control-label margin__bottom" for="school_name">Type <span class="required">*</span>
                                </label>
                                <div class="feild">
                                {{ Form::select('type',@$subject_types,@$data->type ,
                                array('id'=>'type','class' => 'form-control single-select','required'=>"required","placeholder"=>"Select Subject Type" )) }}
                                </div>
                            </div>
                        </div>
                           
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="school_name">Class <span class="required">*</span>
                                    </label>
                                    <div class="feild">
                                    {{ Form::select('class_id',@$class_list,@$data->class_id ,
                                    array('id'=>'class_id','class' => 'form-control single-select','required'=>"required","placeholder"=>"Select Class" )) }}
                                    </div>
                                </div>
                            </div>
                            
                            
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="subject_name">Subject Code <span class="required"></span>
                                    </label>
                                    <div class="feild">
                                    {{Form::text('subject_code',@$data->subject_code,array('id'=>"subject_code",'class'=>"form-control col-md-7 col-xs-12" ,
                                    'placeholder'=>"e.g ENG",))}}
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                                <label class="control-label margin__bottom" for="school_name"> Select School Type <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{ Form::select('school_type',@$school_types,@$data->school_type ,
                                     array('id'=>'school_type','class' => 'form-control single-select','required' => 'required','placeholder'=>"Select School Type" )) }}
                                    </div>
                            </div>
                        </div>
                            @if (@$layout=="edit" && $data->department_id)
                                <div class="col-xs-12 col-sm-4 col-md-3 schooldepartment ">
                            @else
                                <div class="col-xs-12 col-sm-4 col-md-3 schooldepartment">  
                            @endif
                            <div class="item form-group">
                                <label class="control-label margin__bottom" for="school_name"> Select Department <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{ Form::select('department_id',@$departments,@$data->department_id ,
                                     array('id'=>'department_id','class' => 'form-control single-select','required' => 'required' )) }}
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                         {{-- <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="school_name"> Academic Year <span class="required">*</span>
                                    </label>
                                    <div class="feild">
                                    {{ Form::select('academic_year',@$academic_years,@$data->academic_year ,
                                    array('id'=>'academic_year','class' => 'form-control' )) }}
                                    </div>
                                </div>
                            </div> --}}
                            
                    </div>
                        
                       
                        <!-- //status -->
                       
                </div>
            </div>
        </div>   
   
       
        
       
       
        {{Form::close()}}
</div>

        
    

@endsection
@section("scripts")
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
    window.department = "{{ route('schooltype.index') }}";
    SubjectConfig.SubjectInit(notify_script);
    </script>
@endsection
@section('script_link')

    <!-- validator -->
    {!! Cms::script("theme/vendors/validator/validator.js") !!}
    {!! Cms::script("theme/vendors/validator/validator_form.js") !!}

@endsection