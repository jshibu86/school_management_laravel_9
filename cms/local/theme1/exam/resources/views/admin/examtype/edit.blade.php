@extends('layout::admin.master')

@section('title','exam type')
@section('style')


@endsection
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('examtype.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'exam-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('examtype.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
        <div class="box-header with-border mar-bottom20">
           
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_exam' , 'class' => 'btn btn-success btn-sm m-1  px-3 ')) }}

            @if (@$layout == "create")  

            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save-Continue', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat_continue' , 'value' => 'Edit_wallet' , 'class' => 'btn btn-dark btn-sm m-1  px-3')) }}

            @endif
           

           

             <a class="btn btn-info btn-sm m-1  px-3" href="{{route('examtype.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

              {{-- {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }} --}}

              

             
        </div>
           @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit Exam Type" : "Create Exam Type"])

             <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Exam Type</h5>
                    <hr/>
                    <div class="col-xs-12">
                        <div class="row">
                        <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="status">Academic year <span class="required">*</span>
                                    </label>
                                    <div class="feild">
                                        {{ Form::select('academy_year',@$academic_years,@$data->academy_year?@$data->academy_year : @$info['current_academic_year'] ,
                                        array('id'=>'acyear','class' => 'single-select  form-control','required' => 'required',"placeholder"=>"Select Academic Year" )) }}
                                    </div>
                                </div>
                            </div>
                        <div class="col-xs-12 col-sm-4 col-md-3">
                         <div class="item form-group">
                                <label class="control-label margin__bottom" for="status">Exam Type Name <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{Form::text('exam_type_name',@$data->exam_type_name,array('id'=>"name",'class'=>"form-control col-md-7 col-xs-12" ,
                                   'placeholder'=>"Exam Type e.g First Semester",'required'=>"required"))}}
                                </div>
                            </div>
                        </div>

                         <div class="col-xs-12 col-sm-4 col-md-3">
                         <div class="item form-group">
                                <label class="control-label margin__bottom" for="status">Consider Mark Distribution
                                </label>
                                <div class="feild">
                                   <label class="switch">
                                    <input type="checkbox" name="is_promotion" {{ @$data->is_promotion==1 ? "checked" : "" }} class="toggle-class" >
                                    <span class="slider round"></span>
                                     </label>
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

@section('script_link')

    <!-- validator -->
    {!! Cms::script("theme/vendors/validator/validator.js") !!}
    {!! Cms::script("theme/vendors/validator/validator_form.js") !!}

@endsection
