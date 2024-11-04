@extends('layout::admin.master')

@section('title','dormitory')
@section('style')


@endsection
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('dormitoryroomtype.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'dormitory-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('dormitoryroomtype.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
        <div class="box-header with-border mar-bottom20">
           
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_dormitory' , 'class' => 'btn btn-success btn-sm m-1  px-3 ')) }}

            @if (@$layout == "create")  

            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save-Continue', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat_continue' , 'value' => 'Edit_wallet' , 'class' => 'btn btn-dark btn-sm m-1  px-3')) }}

            @endif
           

           

             <a class="btn btn-info btn-sm m-1  px-3" href="{{route('dormitoryroomtype.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

              {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }}

              

             
        </div>
           @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit Hostel room type" : "Createhostelroom type"])

             <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Create a new hostelroomtype</h5>
                    <hr/>
                    <div class="col-xs-12">
                        <div class="row">
                        
                        <div class="col-xs-12 col-sm-4 col-md-3">
                         <div class="item form-group">
                                <label class="control-label margin__bottom" for="status">Room Type <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{Form::text('room_type',@$data->room_type,array('id'=>"room_type",'class'=>"form-control col-md-7 col-xs-12" ,
                                   'placeholder'=>"e.g single,double",'required'=>"required"))}}
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                                   <label class="control-label margin__bottom" for="status">Room Type Description <span class="required">*</span>
                                   </label>
                                   <div class="feild">
                                    {{  Form::textarea('room_type_description', @$data->room_type_description, [
                                        'class'      => 'form-control',
                                        'rows'       => 3, 
                                        'name'       => 'room_type_description',
                                        'id'         => 'room_type_description',
                                       
                                    ])}}
        
                                   </div>
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
