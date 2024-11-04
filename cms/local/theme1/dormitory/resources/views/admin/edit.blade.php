@extends('layout::admin.master')

@section('title','dormitory')
@section('style')


@endsection
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('dormitory.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'dormitory-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('dormitory.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
        <div class="box-header with-border mar-bottom20">
           
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_dormitory' , 'class' => 'btn btn-success btn-sm m-1  px-3 ')) }}

            @if (@$layout == "create")  

            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save-Continue', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat_continue' , 'value' => 'Edit_wallet' , 'class' => 'btn btn-dark btn-sm m-1  px-3')) }}

            @endif
           

           

             <a class="btn btn-info btn-sm m-1  px-3" href="{{route('dormitory.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

              {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }}

              

             
        </div>
           @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit Hostel" : "Create Hostel"])

             <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{$layout == "edit" ?"Edit Hostel" : "Create Hostel"}}</h5>
                    <hr/>
                    <div class="col-xs-12">
                        <div class="row">

                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                       <label class="control-label margin__bottom" for="status">Hostel Name <span class="required">*</span>
                                       </label>
                                       <div class="feild">
                                           {{Form::text('dormitory_name',@$data->dormitory_name,array('id'=>"dormitory_name",'class'=>"form-control col-md-7 col-xs-12" ,
                                          'placeholder'=>"e.g hostel",'required'=>"required"))}}
                                       </div>
                                   </div>
                               </div>
                        <div class="col-xs-12 col-sm-4 col-md-3">
                          <div class="item form-group">
                           <label class="control-label margin__bottom" for="status">Hostel Type <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{ Form::select('dormitory_type',["boys"=>"Boys","girls"=>"Girls"],@$data->dormitory_type ,
                                    array('id'=>'status','class' => 'single-select form-control','required' => 'required' )) }}
                                </div>
                          </div>
                               
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                                   <label class="control-label margin__bottom" for="status">Hostel Location  <span class="required">*</span>
                                   </label>
                                   <div class="feild">
                                    {{  Form::textarea('dormitory_address', @$data->dormitory_address, [
                                        'class'      => 'form-control',
                                        'rows'       => 3, 
                                        'name'       => 'dormitory_address',
                                        'id'         => 'dormitory_address',
                                       
                                    ])}}
        
                                   </div>
                               </div>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                                   <label class="control-label margin__bottom" for="status">Hostel Description <span class="required">*</span>
                                   </label>
                                   <div class="feild">
                                    {{  Form::textarea('dormitory_description', @$data->dormitory_description, [
                                        'class'      => 'form-control',
                                        'rows'       => 3, 
                                        'name'       => 'dormitory_description',
                                        'id'         => 'dormitory_description',
                                       
                                    ])}}
        
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
