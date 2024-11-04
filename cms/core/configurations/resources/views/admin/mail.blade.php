@extends('layout::admin.master')

@section('title','Mail Configuration')
@section('style')
    {!! Cms::style("theme/vendors/switchery/dist/switchery.min.css") !!}
    {!!Cms::style('theme/vendors/select2/select2.css')!!}
@endsection

@section('body')
    <div id="site-configurations">
      
        {{ Form::open(array('role' => 'form', 'route'=>array('admin_mail_configuration_save'), 'autocomplete'=>"false", 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'module-form','novalidate' => 'novalidate')) }}
        <div class="box-header with-border mar-bottom20">
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_btn' , 'value' => 'save' , 'class' => 'btn btn-success btn-sm m-1  px-3')) }}

            {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }}
            @include("layout::admin.breadcrump",['route'=> "Mail Configurations"])
        </div>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Mail Configurations</h5>
                <hr/>
                <div class="col-xs-12 col-sm-12 col-md-8">
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">From Mail <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {{Form::text('from_mail',@$data->from_mail,array('id'=>"name",'class'=>"form-control col-md-7 col-xs-12" ,
                            'data-validate-length-range'=>"6",'placeholder'=>"From Mail ",'required'=>"required"))}}
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Mailer <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {{Form::select('from_mailer',$mailer,@$data->from_mailer,array('id'=>"name",'class'=>"form-control col-md-7 col-xs-12" ,
                            'data-validate-length-range'=>"6",'required'=>"required"))}}
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">From Mail Password<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="password" style="display:none;">
                            {{Form::password('from_mail_password',array('id'=>"from_mail_password",'class'=>"form-control col-md-7 col-xs-12" ,
                            'data-validate-length-range'=>"6",'placeholder'=>"From Mail Password","autocomplete"=>"off"))}}
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">From Mail Name <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {{ Form::text('from_mail_name',@$data->from_mail_name,array('id'=>"from_mail_name",'class'=>"form-control col-md-7 col-xs-12" ,
                            'data-validate-length-range'=>"6",'placeholder'=>"From Mail Name",'required'=>"required"))}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <h5 class="card-title">Mailtrap Mail Configurations</h5>
                <hr/>
                <div class="col-xs-12 col-sm-12 col-md-8">
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">From Mail <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {{Form::text('mail_trap_from_mail',@$data->mail_trap_from_mail,array('id'=>"name",'class'=>"form-control col-md-7 col-xs-12" ,
                            'data-validate-length-range'=>"6",'placeholder'=>"From Mail ",'required'=>"required"))}}
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Mailer <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {{Form::select('mail_trap_from_mailer',$mailer,@$data->mail_trap_from_mailer,array('id'=>"name",'class'=>"form-control col-md-7 col-xs-12" ,
                            'data-validate-length-range'=>"6",'required'=>"required"))}}
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">From Mail Password<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="mail_trap_password" style="display:none;">
                            {{Form::password('mail_trap_from_mail_password',array('id'=>"mail_trap_from_mail_password",'class'=>"form-control col-md-7 col-xs-12" ,
                            'data-validate-length-range'=>"6",'placeholder'=>"From Mail Password","autocomplete"=>"off"))}}
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">From Mail Name <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {{ Form::text('mail_trap_from_mail_name',@$data->mail_trap_from_mail_name,array('id'=>"mail_trap_from_mail_name",'class'=>"form-control col-md-7 col-xs-12" ,
                            'data-validate-length-range'=>"6",'placeholder'=>"From Mail Name",'required'=>"required"))}}
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">From Mail User Name <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {{ Form::text('mail_trap_from_mail_username',@$data->mail_trap_from_mail_username,array('id'=>"mail_trap_from_mail_username",'class'=>"form-control col-md-7 col-xs-12" ,
                            'data-validate-length-range'=>"6",'placeholder'=>"From Mail Name",'required'=>"required"))}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        {{Form::close()}}
    </div>
@endsection

@section('script')
    {!!Cms::script('theme/vendors/select2/select2.min.js')!!}
    {!! Cms::script("theme/vendors/switchery/dist/switchery.min.js") !!}
    <!-- validator -->
    {!! Cms::script("theme/vendors/validator/validator.js") !!}
    <script src="/vendor/laravel-filemanager/js/lfm.js"></script>
    <script>
        $("documnt").ready(function() {
            $('select').select2();
            $('#lfm').filemanager('image');
        });
    </script>
@endsection