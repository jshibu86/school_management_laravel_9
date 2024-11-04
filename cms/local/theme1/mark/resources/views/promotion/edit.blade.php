@extends('layout::admin.master')

@section('title','promotion')
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
    .sec-1{
        box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
        padding: 15px;
        border-radius: 15px;
        margin: 1px !important
    }
    .head_arrows{
       position: relative;
        font-size: 21
    }
    .sub_contents{
        display: flex;
        justify-content: space-between
    }
    .switch_icon{
        position: absolute;
        right: 70px
    }

   
   @media only screen and (min-width: 1245px) and (max-width: 1340px)  {
    .content_space{
    padding:0px 6px 0px 2px !important;
    }
}
   @media only screen and (min-width: 1220px) and (max-width: 1245px)  {
    .content_space{
    padding:0px 2px 0px 2px !important;
       }
       .sub_head{
         font-size: 13px
       }
    }
     @media only screen and (max-width:1200px)
     {
        .head_arrows{
             top: -35px;
            left: 35px;
        }
        
     }
   
 </style>
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('promotion.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'mark-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('promotion.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
        <div class="box-header with-border mar-bottom20">
           
           

            {{-- @if (@$layout == "create")  

            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save-Continue', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat_continue' , 'value' => 'Edit_wallet' , 'class' => 'btn btn-dark btn-sm m-1  px-3')) }}

            @endif --}}
           

              {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }}

                
        </div>
           @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Student Promotion" : "Student Promotion"])

        <div class="card">
            @if (@$if_last_term)                
           
                <div class="card-body">
                    <div class="col-xs-12">
                        <div class="row" style="padding: 7px;">
                            <div class="col-md-6">
                                <div class="row_">

                            <h5>Student Promotion  From</h5>
                            <div class="sub_contents">
                                <div>
                                    <p class="sub_head">Select the appropriate Class and Section to Promote from</p>
                                </div>
                                
                                <div class="head_arrows">
                                    <i class="fa fa-long-arrow-right switch_icon" style="top:10px" aria-hidden="true"></i>
                                    <i class="fa fa-long-arrow-left switch_icon" aria-hidden="true"></i>
                                </div>
                            </div>
                            
                           <div class="sec-1 row">

                          
                            <div class="col-md-4 mb-4 content_space">
                                 <div class="item form-group">
                                     <label for="acc_yr" class="mb-2">Academic Year <span>*</span></label>

                                    {{ Form::select('academic_year',@$academic_years,@$data->academic_year ? @$data->academic_year : @$current_academic_year ,
                                    array('id'=>'acyear','class' => 'single-select  form-control academic_year_from','required' => 'required',"placeholder"=>"Select Academic Year",$layout=="edit" ? "disabled" : "" )) }}
                                 </div>
                             </div>

                             
                             <div class="col-md-4 mb-4 content_space">
                                <div class="item form-group">
                             <label class="control-label margin__bottom" for="status">School Type <span class="required">*</span>
                                  </label>
                                  <div class="feild">                           

                                       {{ Form::select('school_type_from',@$school_type_info,@$data->school_type_info ? @$data->school_type_info : @$school_type_infos ,
                                      array('id'=>'school_type','class' =>@$layout =="edit" ? ' form-control': 'single-select form-control' ,'required' => 'required','placeholder'=>"Select School Type",@$layout =="edit"? "disabled" : "" )) }} 
                                        </div>
                            </div>
                         </div> 
                                                     
       
                            <div class="col-md-4 mb-4 content_space">
                                <div class="item form-group">
                                   <label for="exam_term" class="mb-2">Class <span>*</span></label>
                                    {{ Form::select('class_id',@$class_lists,@$data->class_id,
                                      array('id'=>'class_id','class' => 'single-select form-control class_id_from','required' => 'required',"placeholder"=>"Select Class",$layout=="edit" ? "disabled" : "" )) }}
                                 </div>
                            </div>
                            <div class="col-md-4 mb-4 content_space">
                                <div class="item form-group">
                                   <label for="exam_term" class="mb-2">Section <span>*</span></label>


                                  {{ Form::select('section_id',@$section_lists,@$data->section_id ,
                                   array('id'=>'section_id','class' => 'single-select form-control section_id_from','required' => 'required',"placeholder"=>"Select Section" ,$layout=="edit" ? "disabled" : "")) }}
                                 </div>
                            </div>
                            </div>                      
                            
                        </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row_">

                            <h5>Student Promotion To</h5>
                             <p>Select the appropriate Class and Section to Promote to</p>
                           <div class="row sec-1">
                            <div class="col-md-4 mb-4 content_space">
                                 <div class="item form-group">
                                     <label for="acc_yr" class="mb-2">Academic Year <span>*</span></label>

                                    {{ Form::select('academic_year_to',@$academic_years_to,@$data->academic_year ? @$data->academic_year : @$current_academic_year ,
                                    array('id'=>'acyear_to','class' => 'single-select  form-control termacademicyear academic_year_to','required' => 'required',"placeholder"=>"Select Academic Year",$layout=="edit" ? "disabled" : "" )) }}
                                 </div>

                             </div>

                              <div class="col-md-4 mb-4 content_space">
                                   <div class="item form-group">
                                <label class="control-label margin__bottom" for="status">School Type <span class="required">*</span>
                                     </label>
                                     <div class="feild">                           

                                          {{ Form::select('school_type_to',@$school_type_info,@$data->school_type_info ? @$data->school_type_info : @$school_type_infos,
                                         array('id'=>'school_types_to','class' =>@$layout =="edit" ? ' form-control': 'single-select form-control' ,'required' => 'required','placeholder'=>"Select School Type",@$layout =="edit"? "disabled" : "" )) }} 
                                           </div>
                               </div>
                            </div>  
                             

                            <div class="col-md-4 mb-4 content_space">
                                <div class="item form-group">
                                   <label for="exam_term" class="mb-2">Class <span>*</span></label>


                                    {{ Form::select('class_id_to',[],@$data->class_id ,
                                      array('id'=>'class_id_to','class' => 'single-select form-control class_id_to','required' => 'required',"placeholder"=>"Select Class",$layout=="edit" ? "disabled" : "" )) }}
                                 </div>
                            </div>
                            {{-- <div class="col-md-4 mb-4 content_space">
                                <div class="item form-group">
                                   <label for="exam_term" class="mb-2">Section <span>*</span></label>


                                  {{ Form::select('section_id_to',[],@$data->section_id ,
                                   array('id'=>'section_id_to','class' => 'single-select form-control section_id_to','required' => 'required',"placeholder"=>"Select Section" ,$layout=="edit" ? "disabled" : "")) }}
                                 </div>
                            </div> --}}
                            </div>
                         
                           
                           
                        </div>
                            </div>
                        </div>
                        
                    </div>

                   {{-- <div class="col-md-2 mb-4">
                        <div class="item form-group">
                            <label for="exam_term" class="mb-2">Promotion TYpe <span>*</span></label>


                            {{ Form::select('promotion_type',[0=>"Cumulative",1=>"Third Term"],Configurations::getConfig("site")->promotion_type,
                            array('id'=>'promotion_type','class' => 'single-select form-control promotion_type','required' => 'required',"placeholder"=>"Select Promotion Type" ,$layout=="edit" ? "disabled" : "")) }}
                         </div>
                    </div> --}}

                    @if (@$layout != "edit")
                            <div class="col-xs-12 mb-4 " style="margin-left: 20px">
                                <div class="item form-group">
                                  <button type="button" class="btn btn-primary mt-4 getpromotestudent">Get Students For Promotion</button>
                                 </div>
                            </div>
                   @endif
                </div>
            @else
            <div class="card-body">
                <p class="text-danger info_report text-center m-3"> <i class="fa fa-info-circle" ></i> Whoops !! Your System Current Academic Term Set to {{@$examterms[@$current_academic_term] }},Third Term Only Allow Student Promotion Options</p>
                
            </div>
             @endif
        </div>


        <div class="promote_students"></div>

        

                @if (@$layout=="edit")

                @include("mark::admin.includes.markentry",
                ["class" =>
                    $class,

                "acyear" => $acyear,
                "subject" => $subject,
                "department" => $department,
                "term" => $term,
                "students" => $students,
                "markdistribution" => $markdistribution,
                "exam_type" => $exam_type,
                "exam_info" => $exam_info,"type"=>"edit"])

                @else
                <div class="mark_entry_details"></div>

                    
                @endif
           

        
       
       

        {{Form::close()}}
    </div>
    <!-- Button trigger modal -->


        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class=" modal-dialog modal-dialog-centered" style="max-width: 600px">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create New Academic year</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center popup">
                <img src="{{asset("assets/images/Calendar.png")}}" width="200px"/>
                <h4 style="margin-top:10px;font-weight:700">Create a New Academic Year & Terms</h4>
                <p style="font-size:12px">Creating a new academic year alongside its respective term will enhance the organization of
                    your files or records, facilitating easy sorting and filtering for generating reports</p>
                <p class="text-danger" style="font-size: 12px">Your current academic year and term started on 5th, April 2022 and end 20, May 2023, please create a new one</p>
                <a href="{{route("academicyearpopup.create")}}" target="_blank" class="btn btn-primary">Create Academic year</a>
            
            </div>
            {{-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div> --}}
            </div>
        </div>
        </div>
@endsection

@section('script_link')

    <!-- validator -->
    {!! Cms::script("theme/vendors/validator/validator.js") !!}
    {!! Cms::script("theme/vendors/validator/validator_form.js") !!}

@endsection

@section("scripts")
<script type="module">
         $(document).ready(function() {
             $("#exampleModal").modal("show");
         });
       
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
        window.getpromotioninfo="{{ route('promotion.index') }}"
        window.getpromotionstudentinfo="{{ route('getpromotstudents') }}"

        ExamConfig.examinit(notify_script);
        //AcademicYearConfig.AcademicyearInit();
        GeneralConfig.FormsubmitDisabled();
        PromotionConfig.PromotionInit(notify_script);
</script>



@endsection
