@extends('layout::admin.master')

@section('title','fees Collection')
@section('style')

<link rel="stylesheet" href="{{asset('assets/backend/css/fees.css')}}">
<style>

    .modal-dialog {
      display: flex;
      align-items: center;
      min-height: calc(100vh - 30px);
      justify-content: center;
    }
</style>
@endsection
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('fees.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'fees-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('fees.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
        <div class="box-header with-border mar-bottom20">
           
           
           

             <a class="btn btn-info btn-sm m-1  px-3" href="{{route('fees.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

              {{-- {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }} --}}

              

             
        </div>
           @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit Fee payment" : "New Fee Payment"])
            <div class="row">
                <div class="col-md-8">

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Fee Payment {{ @$is_student ? "(".@$student_info->reg_no.")" : "" }}</h5>
                                <hr/>
                                <div class="col-xs-12">
                                    <div class="row">
                                            <div class="col-xs-12 col-sm-4 col-md-6">
                                                <div class="item form-group">
                                                <label class="control-label margin__bottom" for="status">Select Academic Year<span class="required">*</span>
                                                    </label>
                                                    <div class="feild">
                                                        
                                                        {{ Form::select('academic_year',@$info['academicyears'],@$data->academic_year ?@$data->academic_year : @$info['current_academic_year'],
                                                        array('id'=>'timetableacyear','class' => @$layout =="edit" ? " form-control " : 
                                                        "single-select form-control ",'required' => 'required','placeholder'=>"Select Academic Year",@$layout =="edit"? "disabled" : "")) }}
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        @if (!@$is_student)
                                            
                                       
                                        <div class="col-xs-12 col-sm-4 col-md-6">
                                            <div class="item form-group">
                                            <label class="control-label margin__bottom" for="status">Select Class<span class="required">*</span>
                                                </label>
                                                <div class="feild">
                                                    
                                                    {{ Form::select('class_id',@$class_lists,@$data->class_id,
                                                    array('id'=>'class_id','class' => @$layout =="edit" ? " form-control " : 
                                                    "single-select form-control ",'required' => 'required','placeholder'=>"Select Class",@$layout =="edit"? "disabled" : "")) }}
                                                </div>
                                            </div>
                                                
                                        </div>

                                        <div class="col-xs-12 col-sm-4 col-md-6">
                                            <div class="item form-group">
                                            <label class="control-label margin__bottom" for="status">Select Section<span class="required">*</span>
                                                </label>
                                                <div class="feild">
                                                    
                                                    {{ Form::select('section_id',@$sections,@$data->section_id,
                                                    array('id'=>'section_id','class' => @$layout =="edit" ? " form-control " : 
                                                    "single-select form-control ",'required' => 'required','placeholder'=>"Select Section",@$layout =="edit"? "disabled" : "")) }}
                                                </div>
                                            </div>
                                                
                                        </div>

                                        <div class="col-xs-12 col-sm-4 col-md-6">
                                            <div class="item form-group">
                                            <label class="control-label margin__bottom" for="status">Select Student<span class="required">*</span>
                                                </label>
                                                <div class="feild">
                                                    
                                                    {{ Form::select('student_id',@$students,@$data->student_id,
                                                    array('id'=>'student_id','class' => @$layout =="edit" ? " form-control " : 
                                                    "single-select form-control ",'required' => 'required','placeholder'=>"Select Student",@$layout =="edit"? "disabled" : "")) }}
                                                </div>
                                            </div>
                                                
                                        </div>
                                         @endif

                                         @if (@$is_student)
                                            <input type="hidden" name="class_id" value="{{ @$student_info->class_id }}"/> 
                                            <input type="hidden" name="section_id" value="{{ @$student_info->section_id }}"/> 
                                            <input type="hidden" name="student_id" value="{{ @$student_info->id }}"/> 
                                         @endif
                                        <div class="col-xs-12 col-sm-4 col-md-4">
                                            <div class="item form-group">
                                            <button type="button" class="btn btn-primary mt-4 getfeeinfo">Get Fee Info</button>
                                            </div>
                                                
                                        </div>
                                
                                    </div>
                                
                                    <div class="fee_full_information"></div>
                                    <div class="error__feeinfo text-danger text-center"></div>
                                    
                                </div>
                            </div>
                        </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Fee List</h5>
                            <hr/>
                            <div class="col-xs-12">
                                <div class="row fee_list_information">
                                  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
             

        
       
       

        {{Form::close()}}
    </div>
<div class="modal fade" id="view__fees"  aria-hidden="true">
    
    <form id="fee_form">

    
    <div class="modal-dialog modal-lg modal-dialog-centered">
       
        <div class="modal-content" >
            
            <div class="modal-body assigen_parent_body">

                <div class="fees_details">
                   some
                </div>

            </div>
           
        </div>

    
    </div>
    </form>

</div>
@endsection

@section('script_link')

    <!-- validator -->
    {!! Cms::script("theme/vendors/validator/validator.js") !!}
    {!! Cms::script("theme/vendors/validator/validator_form.js") !!}

@endsection

@section("scripts")
<script type="module">

    window.sectionurl="{{ route('section.index') }}";
    window.studentsurl="{{ route('students.index') }}";
     window.feeinfo="{{ route('fees.index') }}";
     window.viewfeepayment="{{ route('payfeepayment') }}";
     window.storefee="{{ route('fees.store') }}";
    function notify_script(title,text,type,hide) {
            new PNotify({
                title: title,
                text: text,
                type: type,
                hide: hide,
                styling: 'bootstrap3'
            })
        }
$(document).ready(function() {
    //$("#view__fees").modal("show");
    var layout=@json(@$layout);
  
  
    FeeConfig.Feeinit(notify_script);
   
});


</script>
@endsection
