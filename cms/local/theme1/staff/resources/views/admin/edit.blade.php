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
    .kin .select2-container{
        width: 100%!important;
    }
</style>

@endsection
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('staff.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'teacher-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('staff.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
        <div class="box-header with-border mar-bottom20">
            

          
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_teacher' , 'class' => 'btn btn-success btn-sm m-1  px-3')) }}

            <a class="btn btn-info btn-sm m-1  px-3" href="{{route('staff.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

            {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }}

           

            @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit Staff" : "Create Staff"])
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{$layout == "edit" ?"Edit Staff" : "Create Staff"}}</h5>
                <hr/>
                <div class="accordion accordion-flush" id="accordionFlushExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                Staff Info
                            </button>
                        </h2>
                        <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">
                                <div class="col-xs-12" style="margin-bottom: 7px;">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                                <div class="item form-group">
                                                    <label class="control-label margin__bottom" for="teacher_name"> Staff Name <span class="required">*</span>
                                                    </label>
                                                    <div class="feild">
                                                    {{Form::text('employee_name',@$data->employee_name,array('id'=>"teacher_name",'class'=>"form-control col-md-7 col-xs-12" ,
                                                'placeholder'=>"Staff name",'required'=>"required"))}}
                                                    </div>
                                                </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="teacher_name"> Designation <span class="required">*</span>
                                                </label>
                                                <div class="feild">
                                                    {{ Form::select('designation_id',@$designation_list,@$data->designation_id ,
                                                    array('id'=>'designation_id','class' => 'form-control single-select','required' => 'required' ,"placeholder"=>"Select Designation")) }}
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
                                                <label class="control-label margin__bottom" for="mobile"> DOB <span class="required">*</span>
                                                </label>
                                                <div class="feild">
                                                {{Form::text('dob',@$data->dob,array('id'=>"dob",'class'=>"form-control bg-white col-md-7 col-xs-12 dobdate" ,
                                            'placeholder'=>"dob",'required'=>"required","readonly"))}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="gender"> Gender <span class="required">*</span>
                                                </label>
                                                <div class="feild">
                                                    {{ Form::select('gender',@$gender,@$data->gender ,
                                                    array('id'=>'gender_','class' => 'form-control single-select','required' => 'required' )) }}
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
                                                    <label for="thumbnail" class="control-label margin__bottom">Image<span>*</span></label>
                                                    <div class="">
                                                    <span class="input-group-btn">
                                                        <input class="form-control thumb" type="file" id="imagec_img_imagec" name="imagec" data-id="imagec"  accept="image/png, image/jpeg">


                                                       
                                                    </span>
                                                    <img id="imagecholder" style="max-height:50px;" src="{{ @$data->image }}">

                                                    @if (@$layout !="create" && @$data->image)
                                                    <span class="back_to remove" id="remove_img_imagec" data-id="imagec" data-class="imagec" >X</span>
                                                   

                                                    @else
                                                    <span class="back_to remove" id="remove_img_imagec" data-id="imagec" data-class="imagec" style="display:none;">X</span>
                                                   
                                                        
                                                    @endif
                                                    
                                                    
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                        
                                    
                        
                                        {{-- //nextrow --}}
                                     <div class="row">
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="blood_group">Blood Group <span class="required">*</span>
                                                </label>
                                                <div class="feild">
                                                    {{ Form::select('blood_group',@$bloodgroup,@$data->blood_group ,
                                                    array('id'=>'blood_group','class' => 'form-control single-select','required' => 'required',"placeholder"=>"Select Blood group" )) }}
                                              
                                                </div>
                                            </div>
                                        </div>
                                       
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="maritial_status">Marital Status <span class="required">*</span>
                                                </label>
                                                <div class="feild">
                                                    {{ Form::select('maritial_status',@$maritialstatus,@$data->maritial_status ,
                                                    array('id'=>'maritial_status','class' => 'form-control single-select','required' => 'required',"placeholder"=>"Select Marital Status" )) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="religion_">Religion<span class="required">*</span>
                                                </label>
                                                <div class="feild">
                                                    {{ Form::select('religion',@$religion,@$data->religion ,
                                                    array('id'=>'religion_','class' => 'form-control single-select','required' => 'required',"placeholder"=>"Select Religion" )) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="national_id_number">National Id <span class="required">*</span>
                                                </label>
                                                <div class="feild">
                                                {{Form::text('national_id_number',@$data->national_id_number,array('id'=>"national_id_number",'required' => 'required','class'=>"form-control col-md-7 col-xs-12" ,
                                            'placeholder'=>"national id number"))}}
                                                </div>
                                            </div>
                                        </div>
                                       
                                        <!-- //status -->
                                        
                                    </div>
                        
                                    {{-- //nextrow --}}
                                    <div class="row">
                                       
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
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="date_ofjoin">License No(Drivers Only) <span class="required">*</span>
                                                </label>
                                                <div class="feild">
                                                {{Form::text('license_no',@$data->license_no,array('id'=>"date_ofjoin",'class'=>"form-control col-md-7 col-xs-12" ,'required' => 'required',
                                            'placeholder'=>"License No"))}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="religion_">User Group<span class="required">*</span>
                                                </label>
                                                <div class="feild">
                                                    {{ Form::select('group_id',@$usergroup,@$data->group_id ,
                                                    array('id'=>'religion_','class' => 'form-control single-select','required' => 'required',"placeholder"=>"Select User group" )) }}
                                                </div>
                                            </div>
                                        </div>

                                       
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    
                    {{-- //third --}}
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                                Address
                            </button>
                        </h2>
                        <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">
                                <div class="col-xs-12" style="margin-bottom: 7px;">
                                    <div class="row">
                                        {{-- <div class="col-xs-12 col-sm-4 col-md-3">
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
                                        </div> --}}
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
                                                <label class="control-label margin__bottom" for="building_name"> City <span class="required">*</span>
                                                </label>
                                                <div class="feild">
                                                {{Form::text('province',@$address_communication->province,array('id'=>"province",'class'=>"form-control col-md-7 col-xs-12" ,
                                            'placeholder'=>"city",'required'=>"required"))}}
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
                            </div>
                        </div>
                        
                    </div>
                    {{-- //four --}}
                    

                   
                </div>
            </div>
        </div>

      

            
      

        
       
      

       
        
       
       
        {{Form::close()}}
    

        
    </div>

@endsection

@section('script')
<!-- //js -->

<script type="text/javascript">
var error=false;

console.log(error);
@if($errors->any() || $layout=="edit"){
    $(".collapse").removeClass("in");
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
        window.deletecontent="{{ route('DeleteAttachmentTeacher') }}";

$(".remove_").click(function(){
            let id = $(this).attr("data-attach");
            let dataid=$(this).attr("data-id");
            console.log(id,"from students");
           AcademicConfig.DeleteContent(id,notify_script)
           $(`#${dataid}_small`).hide();
            $(this).hide();
        
        });
</script>

<script>
   
   $(document).ready(function() {
   console.log("check");
    $('input[type=checkbox][name=address__check]').change(function() {
        if ($(this).is(':checked')) {
            console.log("here");
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
    {!! Cms::script("theme/vendors/validator/validator_form.js") !!}
   
   
   
@endsection

   



