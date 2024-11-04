@extends('layout::admin.master')

@section('title','eventsmenu')
@section('style')


@endsection
@section('body')
    <div class="x_content">

       
            {{ Form::open(array('role' => 'form', 'route'=>array('eventsmenu_store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'classteacher-form','novalidate' => 'novalidate')) }}
       
        <div class="box-header with-border mar-bottom20">
          
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_events_menu' , 'class' => 'btn btn-success btn-sm m-1  px-3')) }}
            {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }}

        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Events Menu</h5>
                <hr/>
                <div class="col-xs-12">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="item form-group">
                                <label class="control-label " for="name">Banner Title <span class="required">*</span>
                                </label>
                                <div class="">
                                    {{Form::text('banner_title',@$data['banner_title'],array('id'=>"name",'class'=>"form-control col-md-7 col-xs-12" ,
                                    'data-validate-length-range'=>"6",'placeholder'=>"e.g India",'required'=>"required"))}}
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
                       
                        
                    </div>
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
