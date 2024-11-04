@extends('layout::admin.master')

@section('title','subject mapping')
@section('style')
<style>
    .tab__section{
        background-color: white;
    padding: 15px;
    box-shadow: rgb(0 0 0 / 10%) 0px 1px 3px 0px, rgb(0 0 0 / 6%) 0px 1px 2px 0px;
   
    border-radius: 7px;
    }
    .tabs-left>li.active>a {
    color: #fff !important;
    cursor: default;
    background-color: #2a3f54 !important;
    border: 1px solid #ddd;
    border-bottom-color: transparent;
}
.heading__tab{
    text-align: center;
    margin-bottom: 20px;
}
.heading__tab h4{
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 0px !important;
}
.small__header{
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
}
</style>

@endsection
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('chapter.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left ', 'id' => 'subjectmapping-form','novalidate' => 'novalidate')) }}
       
        @endif

        @if (@$layout=="edit")

        {{ Form::open(array('role' => 'form', 'route'=>array('chapter.update',@$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left ', 'id' => 'subjectmapping-form','novalidate' => 'novalidate')) }}
            
        @endif
        <div class="box-header with-border mar-bottom20">
            
           
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', ['type' => 'submit','class' => 'btn btn-success btn-sm m-1  px-3']) }}

            
            @if (@$layout == "create")

            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save-Continue', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat_continue' , 'value' => 'Edit_wallet' , 'class' => 'btn btn-dark btn-sm m-1  px-3')) }}

            @endif

            <a class="btn btn-info btn-sm m-1  px-3" href="{{route('chapter.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

            {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }}
           

           
          
            @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit Chapter" : "Create New Chapter"])
           
            
        </div>
<div class="card">
    <div class="card-body">
        <div class="card-title">
            <h4>New Chapter</h4>
            <hr/>
            @if (@$layout=="create")
            
       
            <div class="col-xs-12">
                <div class="col-sm-6">
                  <div class="card">
                    <div class="card-body">
                      
                      <p class="card-text text-danger panel_alert"><i class="fa fa-hand-o-right text-danger" aria-hidden="true"></i> Before Creating Chapter Create Corresponding class with Subjects </p>
                    
                      <p class="card-text text-danger panel_alert"><i class="fa fa-hand-o-right text-danger" aria-hidden="true"></i> Create atleast one <a href="{{ route("subject.create") }}">Subject</a> with Class</p>
                      {{-- <a href="{{ route("section.create") }}" class="btn btn-primary">Create Section</a>
                      <a href="{{ route("subject.create") }}" class="btn btn-primary">Create Subject</a> --}}
                    </div>
                  </div>
                </div>
               {{-- //next column  --}}
            </div>
              @endif
             
        <div class="col-xs-12">
            <div class="row">
                
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
                        <label class="control-label margin__bottom" for="school_name">Section <span class="required">*</span>
                        </label>
                        <div class="feild">
                        {{ Form::select('section_id',@$sections,@$data->section_id ,
                        array('id'=>'section_id','class' => 'form-control single-select','required'=>"required","placeholder"=>"Select Section" )) }}
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-3">
                    <div class="item form-group">
                        <label class="control-label margin__bottom" for="subject_id"> Subject <span class="required">*</span>
                        </label>
                        <div class="feild">
                        {{ Form::select('subject_id',@$subjects,@$data->subject_id ,
                        array('id'=>'subject_id','class' => 'form-control single-select',"placeholder"=>"Select subject" )) }}
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
                        <label class="control-label margin__bottom" for="chapter_name">Chapter Name <span class="required">*</span>
                        </label>
                        <div class="">
                            {{Form::text('chapter_name',@$data->chapter_name,array('id'=>"chapter_name",'class'=>"form-control col-md-7 col-xs-12 " ,
                            'placeholder'=>"e.g Chapter 1",'required'=>"required"))}}
                        </div>
                    </div>
                </div>
               
    
                 
               
              
                   
                   
                    
                   
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-9">
                    <div class="item form-group">
                        <label for="thumbnail" class="control-label margin__bottom">Chapter Description</label>
                        <div class="">
                      
                            @include('layout::widget.ckeditor',['name'=>'description','id'=>'schoolicon','content'=>@$data->chapter_description])   
                       
                       
                        </div>
                    </div>
                </div>
            </div>
           
            {{Form::close()}}
               
                <!-- //status -->
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

@section('script_link')

    <!-- validator -->
    {!! Cms::script("theme/vendors/validator/validator.js") !!}

@endsection
