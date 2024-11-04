@extends('layout::admin.master')

@section('title','cmsmenu')
@section('style')
    <!-- Datatables -->
    @include('layout::admin.head.list_head')
    <style>
        .table-div table {
            width: 100% !important;
        }
    </style>
@endsection
@section('body')
<div id="site-configurations">
        {{ Form::open(['role' => 'form', 'route' => ['academicsmenu.store'], 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'module-form', 'novalidate' => 'novalidate']) }}
        <div class="box-header with-border mar-bottom20">
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', ['type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_btn', 'value' => 'save', 'class' => 'btn btn-success btn-sm m-1  px-3']) }}

            {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset', 'class' => 'btn btn-danger btn-sm m-1  px-3']) }}

            @include('layout::admin.breadcrump', ['route' => 'CMS Menu'])
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Academics Page</h5>
                <hr />
                <div class="col-xs-12">

                    {{--  --}}
                    <div class="accordion accordion-flush" id="accordionFlushExample">
                        <!-- SECTION -1 -->
                            <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingimage">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseimage" aria-expanded="false"
                                    aria-controls="flush-collapsefour">
                                    Section - 1[Academics Page]
                                </button>
                            </h2>

                            <div id="flush-collapseimage" class="accordion-collapse collapse"
                            aria-labelledby="flush-headingfour" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">
                                        <div class="row">                                        
                                            <div class="col-xs-12 col-sm-4 col-md-3">
                                                <div class="item form-group">
                                                    <label class="control-label margin__bottom" for="country"> Title <span class="required">*</span></label>
                                                    <input type="text" name="acad_val[]" id="acad_sec1_title1" class="form-control col-md-7 col-xs-12" placeholder=""  value="{{$acad_data['acad_sec1_title1']  ?? ''}}" >                                                    
                                                    <input type="hidden" name="acad_key[]" value="acad_sec1_title1">   
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="form-group">
                                                <label for="admission_text"> Description <span class="required">*</span></label>
                                                <textarea name="acad_val[]" id="acad_sec1_desc1" cols="30"
                                                rows="3" class="form-control">{{$acad_data['acad_sec1_desc1']  ?? ''}}</textarea>
                                                <input type="hidden" name="acad_key[]" value="acad_sec1_desc1">  
                                            </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-4 col-md-3">
                                                <div class="item form-group">
                                                    <label for="thumbnail" class="control-label margin__bottom"> Image</label>
                                                    <div class="">
                                                        <span class="input-group-btn">
                                                            <input class="form-control thumb" type="file" id="acad_sec1_image1" name="acad_sec1_image1"
                                                                data-id="acad_sec1_image1" accept="image/*" value="" >
                                                        </span>      
                                                        @if(!empty($acad_data['acad_sec1_image1']))
                                                        <div class="mt-2">
                                                            <img src="{{$acad_data['acad_sec1_image1']}}" alt="Current Banner Image" class="img-thumbnail" style="max-width: 200px;">
                                                        </div>             
                                                        @endif                                                   
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>  
                            </div>
                        <!-- END OF SECTION -1 -->
                  
                        <!-- SECTION -2 -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingsection2">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#flush-collapsesection2" aria-expanded="false"
                                        aria-controls="flush-collapsefour">
                                        Section - 2 [Academics Page]
                                    </button>
                                </h2>
                                <div id="flush-collapsesection2" class="accordion-collapse collapse"
                                    aria-labelledby="flush-headingfour" data-bs-parent="#accordionFlushExample">
                                        <div class="accordion-body">
                                            <div class="row">                                            
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom" for="country"> Title <span class="required">*</span></label>
                                                        <input type="text" name="acad_val[]" id="acad_sec2_atitle" class="form-control col-md-7 col-xs-12" placeholder=""  value="{{$acad_data['acad_sec2_title1']  ?? ''}}" >                                                   
                                                        <input type="hidden" name="acad_key[]" value="acad_sec2_title1">   
                                                    </div> 
                                                </div>
                                                
                                                
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label for="admission_text"> Description <span class="required">*</span></label>
                                                        <textarea name="acad_val[]" id="acad_sec2_desc" cols="30"
                                                        rows="3" class="form-control">{{$acad_data['acad_sec2_desc1']  ?? ''}}</textarea>
                                                        <input type="hidden" name="acad_key[]" value="acad_sec2_desc1">  
                                                    </div>
                                                </div>

                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                            <label for="thumbnail" class="control-label margin__bottom">Image</label>
                                                            <div class="">
                                                                <span class="input-group-btn">
                                                                    <input class="form-control thumb" type="file" id="acad_sec2_image1" name="acad_sec2_image1"
                                                                        data-id="acad_sec2_image1" accept="image/*" value="" >                                                                    
                                                                </span>        
                                                                @if(!empty($acad_data['acad_sec2_image1']))
                                                            <div class="mt-2">
                                                                <img src="{{$acad_data['acad_sec2_image1']}}" alt="Current Banner Image" class="img-thumbnail" style="max-width: 200px;">
                                                            </div>             
                                                            @endif                                             
                                                            </div>
                                                    </div>
                                                </div>
                                            
                                            <div class="row">                                            
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom" for="country"> Title <span class="required">*</span></label>
                                                        <input type="text" name="acad_val[]" id="acad_sec2_title2" class="form-control col-md-7 col-xs-12" placeholder=""  value="{{$acad_data['acad_sec2_title2']  ?? ''}}" >                                                   
                                                        <input type="hidden" name="acad_key[]" value="acad_sec2_title2">   
                                                    </div> 
                                                </div>
                                                
                                                
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label for="admission_text"> Description <span class="required">*</span></label>
                                                        <textarea name="acad_val[]" id="acad_sec2_desc" cols="30"
                                                        rows="3" class="form-control">{{$acad_data['acad_sec2_desc2']  ?? ''}}</textarea>
                                                        <input type="hidden" name="acad_key[]" value="acad_sec2_desc2">  
                                                    </div>
                                                </div>

                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                            <label for="thumbnail" class="control-label margin__bottom">Image</label>
                                                            <div class="">
                                                                <span class="input-group-btn">
                                                                    <input class="form-control thumb" type="file" id="acad_sec2_image2" name="acad_sec2_image2"
                                                                        data-id="acad_sec2_image2" accept="image/*" value="" >                                                                    
                                                                </span>       
                                                                @if(!empty($acad_data['acad_sec2_image2']))
                                                            <div class="mt-2">
                                                                <img src="{{$acad_data['acad_sec2_image2']}}" alt="Current Banner Image" class="img-thumbnail" style="max-width: 200px;">
                                                            </div>             
                                                            @endif                                             
                                                            </div>                                             
                                                            </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">                                            
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom" for="country"> Title <span class="required">*</span></label>
                                                        <input type="text" name="acad_val[]" id="acad_sec2_title3" class="form-control col-md-7 col-xs-12" placeholder=""  value="{{$acad_data['acad_sec2_title3']  ?? ''}}" >                                                   
                                                        <input type="hidden" name="acad_key[]" value="acad_sec2_title3">   
                                                    </div> 
                                                </div>
                                                
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label for="admission_text"> Description <span class="required">*</span></label>
                                                        <textarea name="acad_val[]" id="acad_sec2_desc3" cols="30"
                                                        rows="3" class="form-control">{{$acad_data['acad_sec2_desc3']  ?? ''}}</textarea>
                                                        <input type="hidden" name="acad_key[]" value="acad_sec2_desc3">  
                                                    </div>
                                                </div>

                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                            <label for="thumbnail" class="control-label margin__bottom">Image</label>
                                                            <div class="">
                                                                <span class="input-group-btn">
                                                                    <input class="form-control thumb" type="file" id="acad_sec2_image3" name="acad_sec2_image3"
                                                                        data-id="acad_sec2_image3" accept="image/*" value="" >                                                                    
                                                                </span>       
                                                                @if(!empty($acad_data['acad_sec2_image3']))
                                                            <div class="mt-2">
                                                                <img src="{{$acad_data['acad_sec2_image3']}}" alt="Current Banner Image" class="img-thumbnail" style="max-width: 200px;">
                                                            </div>             
                                                            @endif                                             
                                                            </div>                                             
                                                            </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">                                            
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom" for="country"> Title <span class="required">*</span></label>
                                                        <input type="text" name="acad_val[]" id="acad_sec2_title2" class="form-control col-md-7 col-xs-12" placeholder=""  value="{{$acad_data['acad_sec2_title4']  ?? ''}}" >                                                   
                                                        <input type="hidden" name="acad_key[]" value="acad_sec2_title4">   
                                                    </div> 
                                                </div>
                                                
                                                
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label for="admission_text"> Description <span class="required">*</span></label>
                                                        <textarea name="acad_val[]" id="acad_sec2_desc" cols="30"
                                                        rows="3" class="form-control">{{$acad_data['acad_sec2_desc4']  ?? ''}}</textarea>
                                                        <input type="hidden" name="acad_key[]" value="acad_sec2_desc4">  
                                                    </div>
                                                </div>

                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                            <label for="thumbnail" class="control-label margin__bottom">Image</label>
                                                            <div class="">
                                                                <span class="input-group-btn">
                                                                    <input class="form-control thumb" type="file" id="acad_sec2_image4" name="acad_sec2_image4"
                                                                        data-id="acad_sec2_image4" accept="image/*" value="" >                                                                    
                                                                </span>       
                                                                @if(!empty($acad_data['acad_sec2_image4']))
                                                            <div class="mt-2">
                                                                <img src="{{$acad_data['acad_sec2_image4']}}" alt="Current Banner Image" class="img-thumbnail" style="max-width: 200px;">
                                                            </div>             
                                                            @endif                                             
                                                            </div>                                             
                                                            </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> 
                                </div>                        
                            </div>                                 
                        <!-- END OF SECTION-2 -->
                        
                        <!-- SECTION -3 -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingsection3">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#flush-collapsesection3" aria-expanded="false"
                                        aria-controls="flush-collapsefour">
                                        Section - 3[Academics Page]
                                    </button>
                                </h2>

                                <div id="flush-collapsesection3" class="accordion-collapse collapse"
                                aria-labelledby="flush-headingfour" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">
                                        <div class="row">                                        
                                            <div class="col-xs-12 col-sm-4 col-md-3">
                                                <div class="item form-group">
                                                    <label class="control-label margin__bottom" for="country"> Title <span class="required">*</span></label>
                                                    <input type="text" name="acad_val[]" id="acad_sec3_title" class="form-control col-md-7 col-xs-12" placeholder=""  value="{{$acad_data['acad_sec3_title1']  ?? ''}}" >                                                   
                                                    <input type="hidden" name="acad_key[]" value="acad_sec3_title1">   
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-4 col-md-3">
                                                <div class="form-group">
                                                    <label for="admission_text"> Description <span class="required">*</span></label>
                                                    <textarea name="acad_val[]" id="acad_sec3_desc" cols="30"
                                                    rows="3" class="form-control">{{$acad_data['acad_sec3_desc1']  ?? '' }}</textarea>
                                                    <input type="hidden" name="acad_key[]" value="acad_sec3_desc1">  
                                                </div>
                                            </div>                                                   
                                        </div>                                           
                                    </div>
                                </div>  
                            </div>
                        <!-- END OF SECTION -3 -->    
                        
                        <!-- SECTION -4 -->
                          <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingsection4">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#flush-collapsesection4" aria-expanded="false"
                                        aria-controls="flush-collapsefour">
                                        Section - 4 [Academics Page]
                                    </button>
                                </h2>
                                <div id="flush-collapsesection4" class="accordion-collapse collapse"
                                    aria-labelledby="flush-headingfour" data-bs-parent="#accordionFlushExample">
                                        <div class="accordion-body">
                                            <div class="row">                                            
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom" for="country"> Title <span class="required">*</span></label>
                                                        <input type="text" name="acad_val[]" id="acad_sec2_atitle" class="form-control col-md-7 col-xs-12" placeholder=""  value="{{$acad_data['acad_sec4_title1']  ?? ''}}" >                                                   
                                                        <input type="hidden" name="acad_key[]" value="acad_sec4_title1">   
                                                    </div> 
                                                </div>
                                                                                                
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label for="admission_text"> Description <span class="required">*</span></label>
                                                        <textarea name="acad_val[]" id="acad_sec2_desc" cols="30"
                                                        rows="3" class="form-control">{{$acad_data['acad_sec4_desc1']  ?? ''}}</textarea>
                                                        <input type="hidden" name="acad_key[]" value="acad_sec4_desc1">  
                                                    </div>
                                                </div>

                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                            <label for="thumbnail" class="control-label margin__bottom">Image</label>
                                                            <div class="">
                                                                <span class="input-group-btn">
                                                                    <input class="form-control thumb" type="file" id="acad_sec4_image1" name="acad_sec4_image1"
                                                                        data-id="acad_sec4_image1" accept="image/*" value="" >                                                                    
                                                                </span>        
                                                                @if(!empty($acad_data['acad_sec4_image1']))
                                                            <div class="mt-2">
                                                                <img src="{{$acad_data['acad_sec4_image1']}}" alt="Current Banner Image" class="img-thumbnail" style="max-width: 200px;">
                                                            </div>             
                                                            @endif                                             
                                                            </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">                                            
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom" for="country"> Title <span class="required">*</span></label>
                                                        <input type="text" name="acad_val[]" id="acad_sec4_title3" class="form-control col-md-7 col-xs-12" placeholder=""  value="{{$acad_data['acad_sec4_title2']  ?? ''}}" >                                                   
                                                        <input type="hidden" name="acad_key[]" value="acad_sec4_title2">   
                                                    </div> 
                                                </div>                                                                                                
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label for="admission_text"> Description <span class="required">*</span></label>
                                                        <textarea name="acad_val[]" id="acad_sec2_desc" cols="30"
                                                        rows="3" class="form-control">{{$acad_data['acad_sec4_desc2']  ?? ''}}</textarea>
                                                        <input type="hidden" name="acad_key[]" value="acad_sec4_desc2">  
                                                    </div>
                                                </div>

                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                            <label for="thumbnail" class="control-label margin__bottom">Image</label>
                                                            <div class="">
                                                                <span class="input-group-btn">
                                                                    <input class="form-control thumb" type="file" id="acad_sec4_image2" name="acad_sec4_image2"
                                                                        data-id="acad_sec4_image2" accept="image/*" value="" >                                                                    
                                                                </span>       
                                                                @if(!empty($acad_data['acad_sec4_image2']))
                                                                <div class="mt-2">
                                                                    <img src="{{$acad_data['acad_sec4_image2']}}" alt="Current Banner Image" class="img-thumbnail" style="max-width: 200px;">
                                                                </div>             
                                                                @endif                                             
                                                            </div>                                                                                                        
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">                                            
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom" for="country"> Title <span class="required">*</span></label>
                                                        <input type="text" name="acad_val[]" id="acad_sec4_title3" class="form-control col-md-7 col-xs-12" placeholder=""  value="{{$acad_data['acad_sec2_title3']  ?? ''}}" >                                                   
                                                        <input type="hidden" name="acad_key[]" value="acad_sec4_title3">   
                                                    </div> 
                                                </div>
                                                
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label for="admission_text"> Description <span class="required">*</span></label>
                                                        <textarea name="acad_val[]" id="acad_sec2_desc3" cols="30"
                                                        rows="3" class="form-control">{{$acad_data['acad_sec4_desc3']  ?? ''}}</textarea>
                                                        <input type="hidden" name="acad_key[]" value="acad_sec4_desc3">  
                                                    </div>
                                                </div>

                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label for="thumbnail" class="control-label margin__bottom">Image</label>
                                                        <div class="">
                                                            <span class="input-group-btn">
                                                                <input class="form-control thumb" type="file" id="acad_sec4_image3" name="acad_sec4_image3"
                                                                    data-id="acad_sec4_image3" accept="image/*" value="" >                                                                    
                                                            </span>       
                                                            @if(!empty($acad_data['acad_sec4_image3']))
                                                        <div class="mt-2">
                                                            <img src="{{$acad_data['acad_sec4_image3']}}" alt="Current Banner Image" class="img-thumbnail" style="max-width: 200px;">
                                                        </div>             
                                                        @endif                                             
                                                    </div>                                             
                                                </div>
                                            </div>
                                            <div class="row">                                            
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom" for="country"> Title <span class="required">*</span></label>
                                                        <input type="text" name="acad_val[]" id="acad_sec2_atitle" class="form-control col-md-7 col-xs-12" placeholder=""  value="{{$acad_data['acad_sec4_title4']  ?? ''}}" >                                                   
                                                        <input type="hidden" name="acad_key[]" value="acad_sec4_title4">   
                                                    </div> 
                                                </div>
                                                                                                
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label for="admission_text"> Description <span class="required">*</span></label>
                                                        <textarea name="acad_val[]" id="acad_sec2_desc" cols="30"
                                                        rows="3" class="form-control">{{$acad_data['acad_sec4_desc4']  ?? ''}}</textarea>
                                                        <input type="hidden" name="acad_key[]" value="acad_sec4_desc4">  
                                                    </div>
                                                </div>

                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                            <label for="thumbnail" class="control-label margin__bottom">Image</label>
                                                            <div class="">
                                                                <span class="input-group-btn">
                                                                    <input class="form-control thumb" type="file" id="acad_sec4_image4" name="acad_sec4_image4"
                                                                        data-id="acad_sec4_image4" accept="image/*" value="" >                                                                    
                                                                </span>        
                                                                @if(!empty($acad_data['acad_sec4_image4']))
                                                            <div class="mt-2">
                                                                <img src="{{$acad_data['acad_sec4_image4']}}" alt="Current Banner Image" class="img-thumbnail" style="max-width: 200px;">
                                                            </div>             
                                                            @endif                                             
                                                            </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                            </div> 
                          </div>                                                                          
                        <!-- END OF SECTION-4 -->                        
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
  

@endsection
