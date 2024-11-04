@extends('layout::admin.master')

@section('title','visitorbook')
@section('style')


@endsection
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('visitorbook.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'visitorbook-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('visitorbook.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
        <div class="box-header with-border mar-bottom20">
           
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_visitorbook' , 'class' => 'btn btn-success btn-sm m-1  px-3 ')) }}

            @if (@$layout == "create")  

            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save-Continue', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat_continue' , 'value' => 'Edit_wallet' , 'class' => 'btn btn-dark btn-sm m-1  px-3')) }}

            @endif
           

           

             <a class="btn btn-info btn-sm m-1  px-3" href="{{route('visitorbook.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

              {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'clear_button btn btn-danger btn-sm m-1  px-3']) }}

              

             
        </div>
           @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit Visitorbook" : "Create Visitorbook"])

             <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{$layout == "edit" ?"Edit Visitorbook" : "Create Visitorbook"}}</h5>
                    <hr/>
                    <div class="col-xs-12">
                        <div class="row">
                        
                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                                <label class="control-label margin__bottom" for="status">Visitor Name <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{Form::text('visitor_name',@$data->visitor_name,array('id'=>"name",'class'=>"form-control col-md-7 col-xs-12" ,
                                   'placeholder'=>"both name(s) e.g Jon Doe",'required'=>"required"))}}
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                                <label class="control-label margin__bottom" for="status">Visitor Email <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{Form::text('visitor_email',@$data->visitor_email,array('id'=>"name",'class'=>"form-control col-md-7 col-xs-12" ,
                                   'placeholder'=>"joe@gmail.com",'required'=>"required"))}}
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                                <label class="control-label margin__bottom" for="status">Visitor Phone <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{Form::number('visitor_phone',@$data->visitor_phone,array('id'=>"name",'class'=>"form-control col-md-7 col-xs-12" ,
                                   'placeholder'=>"99889999",'required'=>"required"))}}
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                                <label class="control-label margin__bottom" for="status">Visited Date <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{Form::text('visit_date',@$data->visit_date,array('id'=>"datepicker",'class'=>"form-control bg-white col-md-7 col-xs-12 datepicker" ,
                                   'placeholder'=>"date",'required'=>"required","readonly"))}}
                                </div>
                            </div>
                        </div>

                         <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                                <label class="control-label margin__bottom" for="status">Time In <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{Form::text('visit_time_in',@$data->visit_time_in,array('id'=>"timepicker_daystart",'class'=>"form-control col-md-7 col-xs-12" ,
                                   'placeholder'=>"Select Time In",'required'=>"required"))}}
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                                <label class="control-label margin__bottom" for="status">Time Out <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{Form::text('visit_time_out',@$data->visit_time_out,array('id'=>"timepicker_dayend",'class'=>"form-control col-md-7 col-xs-12" ,
                                   'placeholder'=>"Select Time Out",'required'=>"required"))}}
                                </div>
                            </div>
                        </div>
                         <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                                <label class="control-label margin__bottom" for="status">Whom to Meet(Person Name) <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{Form::text('meet_person_name',@$data->meet_person_name,array('id'=>"timepicker_dayend_demo",'class'=>"form-control col-md-7 col-xs-12" ,
                                   'placeholder'=>"Person Name",'required'=>"required"))}}
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                                <label class="control-label margin__bottom" for="status">Reason in Detail <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{ Form::textarea('reason', @$data->reason, [
                                            'class'      => 'form-control',
                                            'rows'       => 5, 
                                            'name'       => 'reason',
                                            'id'         => 'reason',
                                           
                                        ])
                                    }}
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

@section('script_link')

    <!-- validator -->
    {!! Cms::script("theme/vendors/validator/validator.js") !!}
    {!! Cms::script("theme/vendors/validator/validator_form.js") !!}

@endsection
