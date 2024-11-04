@extends('layout::admin.master')

@section('title','classteacher')
@section('style')


@endsection
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('classteacher.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'classteacher-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('classteacher.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
        <div class="box-header with-border mar-bottom20">
          
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Assign', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_classteacher' , 'class' => 'btn btn-success btn-sm m-1  px-3')) }}
           
            <a class="btn btn-info btn-sm m-1  px-3" href="{{route('classteacher.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>
            {{-- {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }} --}}

           

            
            @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit Class Teacher" : "Create Class Teacher"])
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Assign Class Teacher</h5>
                <hr/>
                <div class="col-xs-12">
                    <div class="row">
                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                                <label class="control-label margin__bottom" for="school_name"> Select Academicyear <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    @php
                                        if(isset($data)){
                                            $academic_year = $data->academic_year;
                                        }
                                        else{
                                            $academic_year = $current_academic_year;
                                        }
                                    @endphp
                                    {{ Form::select('academic_year',@$academic_years,@$academic_year ,
                                    array('id'=>'academic_year','class' => 'form-control single-select','required' => 'required' ,"placeholder"=>"Select Academic year")) }}
                                </div>
                            </div>
                    </div>
                        <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="school_name"> Select Class <span class="required">*</span>
                                    </label>
                                    <div class="feild">
                                        {{ Form::select('class_id',@$class_lists,@$data->class_id ,
                                        array('id'=>'class_id','class' => 'form-control single-select','required' => 'required',"placeholder"=>"Select Class" )) }}
                                    </div>
                                </div>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                                <label class="control-label margin__bottom" for="school_name"> Select Section/Department <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{ Form::select('section_id',@$section_list,@$data->section_id ,
                                    array('id'=>'section_id','class' => 'form-control single-select sectionelement','required' => 'required',"placeholder"=>"Select Section" )) }}
                                </div>
                            </div>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-3">
                        <div class="item form-group">
                            <label class="control-label margin__bottom" for="school_name"> Select Teacher <span class="required">*</span>
                            </label>
                            <div class="feild">
                                {{ Form::select('teacher_id',@$teacher_list,@$data->teacher_id ,
                                array('id'=>'teacher_id','class' => 'form-control single-select teacherelement','required' => 'required',"placeholder"=>"Select Teacher" )) }}
                            </div>
                        </div>
                </div>
            </div>
        </div>
   
            
            
            
        </div>
        </div>
        
       
       
        {{Form::close()}}
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
                styling: 'bootstrap3'
            })
        }
    window.sectionurl="{{ route('section.index') }}";
    window.teacherurl="{{ route('teacher.index') }}";
    window.checkassign="{{ route('classteacher.index') }}";

    AcademicConfig.academicinit(notify_script)
</script>
@endsection
@section("script_link")

    <!-- validator -->
    {!! Cms::script("theme/vendors/validator/validator.js") !!}

@endsection
