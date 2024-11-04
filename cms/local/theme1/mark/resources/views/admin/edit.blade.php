@extends('layout::admin.master')

@section('title','mark')
@section('style')


@endsection
<link rel="stylesheet" href="{{asset('assets/backend/css/attendance.css')}}">
<style>
    thead{
        background-color: #212529;
        color: white;
    }
    th{
        color: white !important;
        border:1px solid #ededed !important;
        text-align: center !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        top: 60% !important;
        border-color: #343a40 transparent transparent transparent !important;
        border-style: solid;
        border-width: 5px 4px 0 4px;
        width: 0;
        height: 0;
        left: 50%;
        margin-left: -4px;
        margin-top: -2px;
        position: absolute;
    }
    .select2-container .select2-selection--single .select2-selection__rendered{
        font-size: 1rem;
        color: black !important;
   }
   .btn-primary {
    color: #fff;
    background-color: #BD02FF !important;
    border-color: #BD02FF !important;
   }
</style>
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('mark.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'mark-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('mark.update',$data_first->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
        <div class="box-header with-border mar-bottom20">
           
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_mark' , 'class' => 'btn btn-success btn-sm m-1  px-3 ')) }}

            {{-- @if (@$layout == "create")  

            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save-Continue', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat_continue' , 'value' => 'Edit_wallet' , 'class' => 'btn btn-dark btn-sm m-1  px-3')) }}

            @endif --}}
           

           

             <a class="btn btn-info btn-sm m-1  px-3" href="{{route('mark.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

              {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }}

              

             
        </div>
           @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit Mark Entry" : "Create Mark Entry"])
        @if(@$layout=="create")
        <div class="card">
                <div class="card-body">
                    <div class="col-xs-12">
                        <div class="row">
                           
                            <div class="col-md-3 mb-4">
                                 <div class="item form-group">
                                     <label for="acc_yr" class="mb-2">Academic Year <span>*</span></label>

                                    {{ Form::select('academic_year',@$academic_years,@$data->academic_year ? @$data->academic_year : @$current_academic_year ,
                                    array('id'=>'acyear','class' => 'single-select  form-control termacademicyear','required' => 'required',"placeholder"=>"Select Academic Year",$layout=="edit" ? "disabled" : "" )) }}
                                 </div>

                             </div>

                            

                            <div class="col-md-3 mb-4">
                                <div class="item form-group">
                                   <label for="exam_term" class="mb-2">Academic Term <span>*</span></label>


                                    {{ Form::select('academic_term',@$examterms,@$data->term_id ?@$data->term_id : @$current_academic_term,
                                     array('id'=>'examterm','class' => 'single-select form-control','required' => 'required',"placeholder"=>"Select Exam Term",$layout=="edit" ? "disabled" : "" )) }}
                                 </div>
                            </div>
                            <div class="col-md-3 mb-4">
                                <div class="item form-group">
                                   <label for="exam_type" class="mb-2">School Type <span>*</span></label>


                                    {{ Form::select('school_type',@$school_types,@$data->school_type ,
                                       array('id'=>'schooltype','class' => 'single-select form-control','required' => 'required',"placeholder"=>"Select School Type",$layout=="edit" ? "disabled" : "" )) }}
                                 </div>
                            </div>

                            <div class="col-md-3 mb-4">
                                <div class="item form-group">
                                   <label for="exam_term" class="mb-2">Class <span>*</span></label>


                                    {{ Form::select('class_id',@$class_lists,@$data->class_id ,
                                      array('id'=>'class_id','class' => 'single-select form-control','required' => 'required',"placeholder"=>"Select Class",$layout=="edit" ? "disabled" : "" )) }}
                                 </div>
                            </div>
                            <div class="col-md-3 mb-4">
                                <div class="item form-group">
                                   <label for="exam_term" class="mb-2">Sec/Dep <span>*</span></label>


                                  {{ Form::select('section_id',@$section_lists,@$data->section_id ,
                                   array('id'=>'section_id','class' => 'single-select form-control','required' => 'required',"placeholder"=>"Select Section" ,$layout=="edit" ? "disabled" : "")) }}
                                 </div>
                            </div>
                            <div class="col-md-3 mb-4">
                                <div class="item form-group">
                                   <label for="exam_term" class="mb-2">Subject <span>*</span></label>


                                   {{ Form::select('subject_id',@$subject_lists,@$data->subject_id ,
                                    array('id'=>'subject_id','class' => 'single-select form-control markentrysubjectid','required' => 'required',"placeholder"=>"Select Subject" ,$layout=="edit" ? "disabled" : "")) }}
                                 </div>
                            </div>                           
                            
                            <div class="col-md-3 mb-4 attendance_col">
                                <div class="item form-group">
                                   <label for="exam_term" class="mb-2">Attendence <button type="button" class="border-0" style="background: none !important;" 
                                    data-toggle="tooltip" data-placement="top" title="Select the Attendance type was manual or automated">      
                                    <i class="fa fa-info-circle mb-0" style="font-size:16px;"></i>
                                  </button></label>


                                   {{ Form::select('attendence',['1'=>'Automatic','2'=>'Manual'],@$data->exam_entry ,
                                    array('id'=>'attendence','class' => 'single-select form-control','required' => 'required' ,$layout=="edit" ? "disabled" : "")) }}
                                 </div>
                            </div>

                            <div class="col-md-3 mb-4 exam_col">
                                <div class="item form-group">
                                   <label for="exam_term" class="mb-2">Exam Entry <button type="button" class="border-0" style="background: none !important;" 
                                    data-toggle="tooltip" data-placement="top" title="Select the Exam Entry was manual or automated">
                                    <i class="fa fa-info-circle mb-0" style="font-size:16px;"></i>
                                  </button></label>


                                   {{ Form::select('exam_entry',['1'=>'Manual','2'=>'Automatic'],@$data->exam_entry ,
                                    array('id'=>'exam_entry','class' => 'single-select form-control','required' => 'required',"placeholder"=>"Select Exam Entry" ,
                                     $layout=="edit" ? "disabled" : "")) }}
                                 </div>
                            </div>

                          

                            <div style="display:none;" id="auto">
                             
                            </div> 

                            @if (@$layout != "edit")
                            <div class="col-md-3 mb-4">
                                <div class="item form-group">
                                  <button type="button" class="btn btn-primary mt-4 entry_mark form-control">Entry Mark</button>
                                 </div>
                            </div>
                            @endif
                           
                        </div>
                    </div>
                </div>
        </div>
        @endif
                @if (@$layout=="edit")
               
                
                @include("mark::admin.includes.markentry",
                ["class" =>
                    $class,
                "data"=>$data,
                'data_first'=>$data_first,
                "acyear" => $academic_year,
                "subject" => $subject,
                "department" => $department,
                "term" => $academic_term,
                "students" => $students,
                "markdistribution" => $distribution,
                 "type"=>"edit"])
               
                @else
                <div class="mark_entry_details"></div>

                    
                @endif
           

        
       
       

        {{Form::close()}}
    </div>

@endsection

@section('script_link')

    <!-- validator -->
    {!! Cms::script("theme/vendors/validator/validator.js") !!}
    {!! Cms::script("theme/vendors/validator/validator_form.js") !!}

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
        window.subjecturl="{{ route('subject.index') }}";
        window.sectionurl="{{ route('section.index') }}";
        window.fetchstudents="{{ route('exam.index') }}"
        window.deletequestion="{{ route('exam.deletequestion') }}"
        window.deletesection="{{ route('exam.deletesection') }}"
        window.entrymark="{{ route('mark.index') }}"
        window.getexams="{{ route('mark.index') }}"
        window.append = "{{ route('getappend') }}"
        ExamConfig.examinit(notify_script);
        AcademicYearConfig.AcademicyearInit();
        GeneralConfig.FormsubmitDisabled();
</script>

@endsection
