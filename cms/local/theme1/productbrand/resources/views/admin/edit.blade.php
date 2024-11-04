@extends('layout::admin.master')

@section('title','productbrand')
@section('style')


@endsection
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('productbrand.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'productbrand-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('productbrand.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
        <div class="box-header with-border mar-bottom20">
           
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_productbrand' , 'class' => 'btn btn-success btn-sm m-1  px-3 ')) }}

            @if (@$layout == "create")  

            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save-Continue', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat_continue' , 'value' => 'Edit_wallet' , 'class' => 'btn btn-dark btn-sm m-1  px-3')) }}

            @endif
           

           

             <a class="btn btn-info btn-sm m-1  px-3" href="{{route('productbrand.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

              {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }}

              

             
        </div>
           @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit Product Brand" : "Create Product Brand"])

             <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{$layout == "edit" ?"Edit Product Brand" : "Create Product Brand"}}</h5>
                    <hr/>
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="status">Category <span class="required">*</span>
                                    </label>
                                    <div class="feild">
                                        <div class="feild">
                                            {{ Form::select('category_id',@$categories,@$data->category_id ,
                                            array('id'=>'category_id','class' => 'single-select form-control','required' => 'required' )) }}
                                        </div>
                                    </div>
                                </div>
                                   
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                       <label class="control-label margin__bottom" for="status">Brand Name <span class="required">*</span>
                                       </label>
                                       <div class="feild">
                                           {{Form::text('brand_name',@$data->brand_name,array('id'=>"brand_name",'class'=>"form-control col-md-7 col-xs-12" ,
                                          'placeholder'=>"American Tourister",'required'=>"required"))}}
                                       </div>
                                   </div>
                               </div>
                               <div class="col-xs-12 col-sm-4 col-md-3">
                                   <div class="item form-group">
                                          <label class="control-label margin__bottom" for="status">Brand Description <span class="required"></span>
                                          </label>
                                          <div class="feild">
                                           <textarea class="form-control" id="inputAddress2" placeholder="Description..." rows="3" name="brand_desc">{{ old("brand_desc") ? old("brand_desc") : @$data->brand_desc }}</textarea>
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
