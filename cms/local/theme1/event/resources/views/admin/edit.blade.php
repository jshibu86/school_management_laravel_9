@extends('layout::admin.master')

@section('title','event')
@section('style')


@endsection
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('event.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'event-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('event.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
        <div class="box-header with-border mar-bottom20">
           
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_event' , 'class' => 'btn btn-success btn-sm m-1  px-3 ')) }}

            @if (@$layout == "create")  

            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save-Continue', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat_continue' , 'value' => 'Edit_wallet' , 'class' => 'btn btn-dark btn-sm m-1  px-3')) }}

            @endif
           

           

             <a class="btn btn-info btn-sm m-1  px-3" href="{{route('event.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

              {{-- {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }} --}}

              

             
        </div>
           @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit Event" : "Create Event"])

             <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{$layout == "edit" ?"Edit Event" : "Create New Event"}}</h5>
                    <hr/>
                    <div class="col-xs-12">
                        <div class="row">
                        
                        <div class="col-xs-12 col-sm-4 col-md-3">
                         <div class="item form-group">
                                <label class="control-label margin__bottom" for="status">Event Name <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{Form::text('event_name',@$data->event_name,array('id'=>"event_name",'class'=>"form-control col-md-7 col-xs-12" ,
                                   'placeholder'=>"Sports Event",'required'=>"required"))}}
                                </div>
                            </div>
                        </div>
                       
                         <div class="col-xs-12 col-sm-4 col-md-3">
                         <div class="item form-group">
                                <label class="control-label margin__bottom" for="status">Event Date <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{Form::text('event_date',@$data->event_date ? \Carbon\Carbon::parse($data->event_date)->format('m/d/Y') : '',array('id'=>"event_date",'class'=>"form-control datepickerwithoutselected col-md-7 col-xs-12" ,
                                   'placeholder'=>"Sports Event",'required'=>"required"))}}
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group"><label for="thumbnail" class="control-label margin__bottom">Image</label>
                                <div class="">
                                    <input class="form-control thumb mb-2" type="file" id="imagec_img_student" name="imagec" data-id="student" accept="image/png, image/jpeg">
                                    <img id="imagecholder" src="{{asset(@$data->event_image)}}" style="max-height: 75px;"><span class="back_to remove" id="remove_img_student" data-id="imagec" data-class="student" style="display: none;">X</span>
                                </div></div></div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="item form-group">
                                   <label class="control-label margin__bottom" for="status">Event Description <span class="required">*</span>
                                   </label>
                                   <div class="feild">
                                       {{Form::textarea('description',@$data->description,array('id'=>"description",'class'=>"form-control col-md-7 col-xs-12" ,
                                      'placeholder'=>"Event description",'rows'=>3,'required'=>"required"))}}
                                   </div>
                               </div>
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
