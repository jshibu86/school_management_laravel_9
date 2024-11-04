@extends('layout::admin.master')

@section('title','contactusmenu')
@section('style')


@endsection
@section('body')
    <div class="x_content">

       
            {{ Form::open(array('role' => 'form', 'route'=>array('contactusmenu.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'classteacher-form','novalidate' => 'novalidate')) }}
       
        <div class="box-header with-border mar-bottom20">
          
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_events_menu' , 'class' => 'btn btn-success btn-sm m-1  px-3')) }}
            {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }}

        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Contact Us Menu</h5>
                <hr/>
                <div class="col-xs-12 mb-3">
                    <h5 class="mb-4">Banner Section:</h5>
                    <!-- <div class="row">
                        <div class="col-md-4">
                            <div class="item form-group">
                                <label class="control-label " for="name">Banner Title <span class="required">*</span>
                                </label>
                                <div class="">
                                    {{Form::text('banner_title',@$data['banner_title'],array('id'=>"name",'class'=>"form-control col-md-7 col-xs-12" ,
                                     'placeholder'=>"e.g Contact Us",'required'=>"required"))}}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="item form-group">
                                <label class="control-label" for="name">Banner Image<span class="required">*</span>
                                </label>
                                <div class="">
                                    <div>
                                        {{ Form::file('banner_image', [
                                            'id' => 'images',
                                            'class' => 'form-control',
                                            'accept' => 'image/*','onchange' => 'LandingpreviewImage(event)'
                                        ]) }}
                                    </div>
                                  
                                    @if(!empty($data['banner_image']))
                                        <div class="mt-2 ms-2" id="landing_preview">
                                            <img src="{{ asset($data['banner_image']) }}" alt="Landing Image" class="img-thumbnail" style="max-width: 200px;">
                                        
                                        </div>
                                   @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="item form-group">
                                <label class="control-label " for="name">Banner Description
                                </label>
                                <div class="">
                                    {{Form::textarea('banner_description',@$data['banner_description'],array('id'=>"name",'class'=>"form-control col-md-7 col-xs-12" ,
                                    'data-validate-length-range'=>"6",'placeholder'=>"e.g India","rows"=>"3"))}}
                                </div>
                            </div>
                        </div>
                       
                        
                    </div> -->
                    <div class="row">
                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                                <label class="control-label margin__bottom" for="country"> Title <span class="required">*</span></label>
                                <input type="text" name="cont_val[]" id="cont_sec3_title" class="form-control col-md-7 col-xs-12" placeholder=""  value="{{$contpage_record['cont_sec1_title1']  ?? ''}}" >                                                   
                                <input type="hidden" name="cont_key[]" value="cont_sec1_title1">   
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="form-group">
                                <label for="admission_text"> Description <span class="required">*</span></label>
                                <textarea name="cont_val[]" id="cont_sec3_desc" cols="30"
                                rows="3" class="form-control">{{$contpage_record['cont_sec1_desc1']  ?? '' }}</textarea>
                                <input type="hidden" name="cont_key[]" value="cont_sec1_desc1">  
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                                <label for="thumbnail" class="control-label margin__bottom">Upload Icon Image</label>
                                <div class="">
                                    <span class="input-group-btn">
                                        <input class="form-control thumb" type="file" id="cont_sec1_image1" name="cont_sec1_image1"
                                            data-id="cont_sec1_image1" accept="image/*" value="" >                                                                    
                                    </span>            
                                    @if(!empty($contpage_record['cont_sec1_image1']))
                                        <div class="mt-2">
                                            <img src="{{ $contpage_record['cont_sec1_image1'] }}" alt=" Image" class="img-thumbnail" style="max-width: 200px;">
                                        </div>             
                                    @endif                                              
                                </div>
                            </div>
                        </div>
                    </div>
                    <h5 class="mt-4">Contact Us Section</h5>
                    <!-- <div class="row">
                        <div class="col-md-12">
                            <div class="item form-group">
                                <label class="control-label " for="name">Location Link <span class="required">*</span>
                                </label>
                                <div class="">
                                    {{Form::url('location_link',@$data['location_link'],array('id'=>"name",'class'=>"form-control col-md-7 col-xs-12" ,
                                     'required'=>"required","aria-describedby"=>"Help"))}}
                                     <div id="Help" class="form-text">Give Your Embbeded Location link.</div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                     <!-- SECTION -3 -->
                     <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingsection3">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#flush-collapsesection3" aria-expanded="false"
                                        aria-controls="flush-collapsefour">
                                        Section - 3[Contactus Page]
                                    </button>
                                </h2>

                                <div id="flush-collapsesection3" class="accordion-collapse collapse"
                                aria-labelledby="flush-headingfour" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">
                                        <div class="row">                                        
                                            <div class="col-xs-12 col-sm-4 col-md-3">
                                                <div class="item form-group">
                                                    <label class="control-label margin__bottom" for="country"> Enter Address Title <span class="required">*</span></label>
                                                    <input type="text" name="cont_val[]" id="cont_sec3_title" class="form-control col-md-7 col-xs-12" placeholder=""  value="{{$contpage_record['cont_sec2_title1']  ?? ''}}" >                                                   
                                                    <input type="hidden" name="cont_key[]" value="cont_sec2_title1">   
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-4 col-md-3">
                                                <div class="form-group">
                                                    <label for="admission_text"> Description <span class="required">*</span></label>
                                                    <textarea name="cont_val[]" id="cont_sec3_desc" cols="30"
                                                    rows="3" class="form-control">{{$contpage_record['cont_sec2_desc1']  ?? '' }}</textarea>
                                                    <input type="hidden" name="cont_key[]" value="cont_sec2_desc1">  
                                                </div>
                                            </div>  
                                            <div class="col-xs-12 col-sm-4 col-md-3">
                                                <div class="item form-group">
                                                    <label class="control-label margin__bottom" for="country"> Enter Working Hours Title<span class="required">*</span></label>
                                                    <input type="text" name="cont_val[]" id="cont_sec3_title" class="form-control col-md-7 col-xs-12" placeholder=""  value="{{$contpage_record['cont_sec2_title2']  ?? ''}}" >                                                   
                                                    <input type="hidden" name="cont_key[]" value="cont_sec2_title2">   
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-4 col-md-3">
                                                <div class="form-group">
                                                    <label for="admission_text"> Description <span class="required">*</span></label>
                                                    <textarea name="cont_val[]" id="cont_sec3_desc" cols="30"
                                                    rows="3" class="form-control">{{$contpage_record['cont_sec2_desc2']  ?? '' }}</textarea>
                                                    <input type="hidden" name="cont_key[]" value="cont_sec2_desc2">  
                                                </div>
                                            </div> 
                                            <div class="col-xs-12 col-sm-4 col-md-3">
                                                <div class="item form-group">
                                                    <label class="control-label margin__bottom" for="country"> Enter Email Id title <span class="required">*</span></label>
                                                    <input type="text" name="cont_val[]" id="cont_sec3_title" class="form-control col-md-7 col-xs-12" placeholder=""  value="{{$contpage_record['cont_sec2_title3']  ?? ''}}" >                                                   
                                                    <input type="hidden" name="cont_key[]" value="cont_sec2_title3">   
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-4 col-md-3">
                                                <div class="form-group">
                                                    <label for="admission_text"> Description <span class="required">*</span></label>
                                                    <textarea name="cont_val[]" id="cont_sec3_desc" cols="30"
                                                    rows="3" class="form-control">{{$contpage_record['cont_sec2_desc3']  ?? '' }}</textarea>
                                                    <input type="hidden" name="cont_key[]" value="cont_sec2_desc3">  
                                                </div>
                                            </div> 
                                            <div class="col-xs-12 col-sm-4 col-md-3">
                                                <div class="item form-group">
                                                    <label class="control-label margin__bottom" for="country"> Enter Contact no title <span class="required">*</span></label>
                                                    <input type="text" name="cont_val[]" id="cont_sec3_title" class="form-control col-md-7 col-xs-12" placeholder=""  value="{{$contpage_record['cont_sec2_title4']  ?? ''}}" >                                                   
                                                    <input type="hidden" name="cont_key[]" value="cont_sec2_title4">   
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-4 col-md-3">
                                                <div class="form-group">
                                                    <label for="admission_text"> Description <span class="required">*</span></label>
                                                    <textarea name="cont_val[]" id="cont_sec3_desc" cols="30"
                                                    rows="3" class="form-control">{{$contpage_record['cont_sec2_desc4']  ?? '' }}</textarea>
                                                    <input type="hidden" name="cont_key[]" value="cont_sec2_desc4">  
                                                </div>
                                            </div>                                                  
                                        </div>                                           
                                    </div>
                                </div>  
                            </div>
                        <!-- END OF SECTION -3 -->  
                </div>
            </div>
        </div>
        
       
       
        {{Form::close()}}
   

        
    </div>

@endsection

@section('scripts')

<script>
    function LandingpreviewImage(event) {
            console.log("its event");
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('landing_preview');
                output.innerhtml = "";
                output.innerHTML = '<img src="' + reader.result + '" class="img-thumbnail" alt="Selected Image" style="max-width: 200px; height: auto;">';
            }
            reader.readAsDataURL(event.target.files[0]);
        }
</script>
@endsection
@section("script_link")

    <!-- validator -->
    {!! Cms::script("theme/vendors/validator/validator.js") !!}

@endsection
