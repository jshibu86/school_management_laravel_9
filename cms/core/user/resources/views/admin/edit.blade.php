@extends('layout::admin.master')

@section('title','user')
@section('style')


@endsection
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('user.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('user.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
            <div class="box-header with-border mar-bottom20">
                {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_Product' , 'class' => 'btn btn-success btn-sm m-1  px-3')) }}

                @if (@$layout == "create")

                {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save-Continue', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat_continue' , 'value' => 'Edit_wallet' , 'class' => 'btn btn-dark btn-sm m-1  px-3')) }}
    
                @endif

              

                <a class="btn btn-info btn-sm m-1  px-3" href="{{route('user.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

                {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }}
              
            </div>
            @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit User" : "Create User"])

            {{-- <div class="col-xs-12">
                <div class="card text-white alertcard">
                 
                  <div class="card-body alert__body">
                    <h4 class="card-title">User Info</h4>
                   
                  </div>
                </div>
            </div> --}}
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"> {{$layout == "edit" ?"Edit User" : "Create a new User"}}</h5>
                    <hr/>
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <label class="control-label margin__bottom" for="status">User Group <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{ Form::select('group',$group,@$data->group[0]->id ,
                                    array('id'=>'status','class' => 'single-select form-control','required' => 'required' )) }}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <label class="control-label margin__bottom" for="status">Name <span class="required">*</span>
                                </label>

                                <div class="item form-group">
                                <div class="feild">
                                    {{Form::text('name',@$data->name,array('id'=>"name",'class'=>"form-control col-md-7 col-xs-12" ,
                                    'data-validate-length-range'=>"6",'placeholder'=>"both name(s) e.g Jon Doe",'required'=>"required"))}}
                                </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <label class="control-label margin__bottom" for="status">User Name <span class="required">*</span>
                                </label>
                                <div class="item form-group">
                                <div class="feild">
                                    {{Form::text('username',@$data->username,array('id'=>"username",'class'=>"form-control col-md-7 col-xs-12" ,
                                    'data-validate-length-range'=>"6",'placeholder'=>"User name",'required'=>"required"))}}
                                </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <label class="control-label margin__bottom" for="email">Email <span class="required">*</span>
                                </label>
                                <div class="item form-group">
                                <div class="feild">
                                    {{Form::email('email',@$data->email,array('id'=>"email",'class'=>"form-control col-md-7 col-xs-12",
                                    'placeholder'=>"Email",'required'=>"required"))}}
                                </div>
                                </div>
                            </div>
                            
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <label class="control-label margin__bottom" for="email">Mobile <span class="required">*</span>
                                </label>
                                <div class="item form-group">
                                <div class="feild">
                                    {{Form::number('mobile',@$data->mobile,array('id'=>"number",'class'=>"form-control col-md-7 col-xs-12" ,
                                    'data-validate-length'=>"9,15",'placeholder'=>"Mobile Number",'required'=>"required"))}}
                                </div>
                                </div>
                            </div>
                            @if($layout == "edit")
                                <div class="col-xs-12 col-sm-4 col-md-3">
                                    <label class="control-label margin__bottom" for="email">Password 
                                    </label>
                                    <div class="item form-group">
                                    <div class="feild">
                                        {{Form::password('password',array('id'=>"password",'class'=>"form-control col-md-7 col-xs-12" ,
                                        'placeholder'=>"Password"))}}
                                    </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-3">
                                    <label class="control-label margin__bottom" for="email">Repeat Password 
                                    </label>
                                    <div class="item form-group">
                                    <div class="feild">
                                        {{Form::password('password2',array('id'=>"password2",'class'=>"form-control col-md-7 col-xs-12" ,
                                    'placeholder'=>"Password"))}}
                                    </div>
                                    </div>
                                </div>
                            @else
                                <div class="col-xs-12 col-sm-4 col-md-3">
                                    <label class="control-label margin__bottom" for="email">Password <span class="required">*</span>
                                    </label>
                                    <div class="item form-group">
                                    <div class="feild">
                                        {{Form::password('password',array('id'=>"password",'class'=>"form-control col-md-7 col-xs-12" ,
                                        'placeholder'=>"Password",'required'=>"required"))}}
                                    </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-3">
                                    <label class="control-label margin__bottom" for="email">Repeat Password <span class="required">*</span>
                                    </label>
                                    <div class="item form-group">
                                    <div class="feild">
                                        {{Form::password('password2',array('id'=>"password2",'class'=>"form-control col-md-7 col-xs-12" ,
                                    'placeholder'=>"Password",'required'=>"required"))}}
                                    </div>
                                    </div>
                                </div>
                            @endif
                            @if(session("connection") == "central")
                                <div class="col-xs-12 col-sm-4 col-md-3">
                                    <label class="control-label margin__bottom" for="email">Approvel Process <span class="required">*</span>
                                    </label>
                                    <div class="item form-group">
                                        <div class="feild">
                                            {{ Form::select ('approval_process', ["0"=>"No","1"=>"Yes"], @$data->approval_process,
                                        array('id'=>'approval_process', 'class' => 'form-control single-select col-md-7 col-xs-12',)) }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <label class="control-label margin__bottom" for="email">Image <span class="required"></span>
                                </label>
                                <div class="feild">

                                    <div class="mb-3">
										
										<input class="form-control thumb" type="file" id="imagec_img_user" name="imagec" data-id="user">
									</div>
                                    <img id="imagecholder" style="max-height:50px;" src="{{ @$data->images }}">
                                    @if (@$layout !="create" && @$data->images)
                                    <span class="back_to remove" id="remove_img_user" data-id="imagec" data-class="user">X</span>

                                    @else

                                    <span class="back_to remove" id="remove_img_user" style="display:none;" data-id="imagec" data-class="user">X</span>
                                        
                                    @endif
                                   
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
