@extends('layout::admin.master')

@section('title','productcategory')
@section('style')


@endsection
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('productcategory.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'productcategory-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('productcategory.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
        <div class="box-header with-border mar-bottom20">
           
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_productcategory' , 'class' => 'btn btn-success btn-sm m-1  px-3 ')) }}

            @if (@$layout == "create")  

            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save-Continue', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat_continue' , 'value' => 'Edit_wallet' , 'class' => 'btn btn-dark btn-sm m-1  px-3')) }}

            @endif
           

           

             <a class="btn btn-info btn-sm m-1  px-3" href="{{@$type == 1 ? route('productcategory.index') : route("InventoryCategory")}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

              {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }}

              

             
        </div>
        @php
            $name=@$type == 1 ? " Product Category" : " Inventory Category"
        @endphp
           @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit".$name : "Create".$name])

             <div class="card">
                <input type="hidden" name="category_type" value="{{@$type}}"/>
                <div class="card-body">
                    <h5 class="card-title"> {{@$type == 1 ? "Product Category" : "Inventory Category"}}</h5>
                    <hr/>
                    <div class="col-xs-12">
                        <div class="row">
                        
                        <div class="col-xs-12 col-sm-4 col-md-3">
                         <div class="item form-group">
                                <label class="control-label margin__bottom" for="status">Category Name <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{Form::text('category_name',@$data->category_name,array('id'=>"category_name",'class'=>"form-control col-md-7 col-xs-12" ,
                                   'placeholder'=>"cookies",'required'=>"required"))}}
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                   <label class="control-label margin__bottom" for="status">Category Description <span class="required"></span>
                                   </label>
                                   <div class="feild">
                                    <textarea class="form-control" id="inputAddress2" placeholder="Description..." rows="3" name="category_desc">{{ old("category_desc") ? old("category_desc") : @$data->category_desc }}</textarea>
                                   </div>
                               </div>
                        </div>

                        <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                   <label class="control-label margin__bottom" for="status">Category Image <span class="required"></span>
                                   </label>
                                   <div class="feild">
                                    <input type="file" name="imagec" class="form-control thumb mb-2" accept="images*"/>
                                    <img id="imagecholder" src="{{asset(@$data->category_image)}}" style="max-height: 75px;display:{{@$data ? @$data->category_image ? "" : "none" :"none"}};"><span class="back_to remove" id="remove_img_student" data-id="imagec" data-class="student" style="display: none;">X</span>
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
