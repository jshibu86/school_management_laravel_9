@extends('layout::admin.master')

@section('title','markreport')
@section('style')


@endsection
<link rel="stylesheet" href="{{asset('assets/backend/css/attendance.css')}}">
<style>
    /* thead{
        background-color: #212529;
        color: white;
    }
    th{
        color: white !important;
        border:1px solid #ededed !important;
        text-align: center !important;
    } */
</style>
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('mark.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'mark-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('mark.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
        <div class="box-header with-border mar-bottom20">
           
            {{-- {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_mark' , 'class' => 'btn btn-success btn-sm m-1  px-3 ')) }} --}}

            {{-- @if (@$layout == "create")  

            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save-Continue', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat_continue' , 'value' => 'Edit_wallet' , 'class' => 'btn btn-dark btn-sm m-1  px-3')) }}

            @endif --}}
           

           

             {{-- <a class="btn btn-info btn-sm m-1  px-3" href="{{route('mark.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a> --}}

              {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }}

              

             
        </div>
           @include("layout::admin.breadcrump",['route'=> "Get BroadSheetMark Report"])

        <div class="card card-main">
                <div class="card-body">
                    <div class="row">

                        <div class="col-xs-12 col-sm-4 col-md-4 margin__bottom">
                            <div class="item form-group">
                                    <label for="acc_yr" class="mb-2">Academic Year <span>*</span></label>

                                    {{ Form::select('academic_year',@$academic_years,@$data->academic_year ? @$data->academic_year : @$current_academic_year ,
                                    array('id'=>'acyear','class' => 'single-select  form-control termacademicyear','required' => 'required',"placeholder"=>"Select Academic Year",$layout=="edit" ? "disabled" : "" )) }}
                            </div>

                        </div>

                        <div class="col-xs-12 col-sm-4 col-md-4">
                            <div class="item form-group">
                                   <label for="exam_term" class="mb-2">Exam Term <span>*</span></label>


                                    {{ Form::select('academic_term',@$examterms,@$data->term_id ?@$data->term_id : @$current_academic_term,
                                     array('id'=>'examterm','class' => 'single-select form-control','required' => 'required',"placeholder"=>"Select Exam Term",$layout=="edit" ? "disabled" : "" )) }}
                            </div>
                        </div>


                        <div class="col-xs-12 col-sm-4 col-md-4">
                                <div class="item form-group">
                                   <label for="exam_type" class="mb-2">Exam Type <span>*</span></label>


                                    {{ Form::select('exam_type',@$exam_types,@$data->exam_type ,
                                       array('id'=>'examtype','class' => 'single-select form-control','required' => 'required',"placeholder"=>"Select Exam Type",$layout=="edit" ? "disabled" : "" )) }}
                                 </div>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-4">
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

                        <div class="col-xs-12 col-sm-4 col-md-4">
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

                        

                        <div class="col-xs-12 col-sm-4 col-md-4">
                        @if(Session::get("ACTIVE_GROUP") == "Super Admin")
                        <button type="button" class="btn btn-primary mt-4 broadsheet_report_mark" id="get">Generate BroadSheet Report</button>
                        @endif
                        </div>

                       


                        
                    </div>
                </div>
        </div>

                <div class="card">
                    <div class="card-body">
                        <div class="col-xs-12">
                            <div class="get_broadsheet_report_details">
                               
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
        window.getbroadsheetmarkreport="{{ route('broadsheet') }}"
        window.studentsurl="{{ route('students.index') }}";
      
        window.entrymark="{{ route('mark.index') }}"

       // ExamConfig.examinit(notify_script);
        AcademicYearConfig.AcademicyearInit();
        GeneralConfig.FormsubmitDisabled();
        FeeConfig.Feeinit(notify_script);
        ReportConfig.ReportInit(notify_script);
</script>
@endsection
