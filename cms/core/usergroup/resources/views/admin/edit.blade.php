@extends('layout::admin.master')

@section('title','user')
@section('style')


@endsection
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('usergroup.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('usergroup.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
            <div class="box-header with-border mar-bottom20">
                {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_Product' , 'class' => 'btn btn-success btn-sm m-1  px-3')) }}

                @if (@$layout == "create")

                {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save-Continue', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat_continue' , 'value' => 'Edit_wallet' , 'class' => 'btn btn-dark btn-sm m-1  px-3')) }}
    
                @endif

                <a class="btn btn-info btn-sm m-1  px-3t" href="{{route('usergroup.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

                {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }}
                @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit User group" : "Create User group"])
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Create User Group</h5>
                    <hr/>
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <label class="control-label margin__bottom" for="name">Group Name <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{Form::text('group',@$data->group,array('id'=>"name",'class'=>"form-control col-md-7 col-xs-12" ,
                                    'placeholder'=>"Group Name",'required'=>"required"))}}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
           
          

            <img id="holder" style="margin-top:15px;max-height:100px;">
       {{Form::close()}}
    </div>
@endsection

@section('script')
    <!-- validator -->
    {!! Cms::script("theme/vendors/validator/validator.js") !!}
    <script src="/vendor/laravel-filemanager/js/lfm.js"></script>
    <script>
        $('#lfm').filemanager('image');
    </script>
    @endsection