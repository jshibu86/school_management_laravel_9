@extends('layout::admin.master')

@section('title','shop')
@section('style')


@endsection
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('shop.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'shop-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('shop.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
        <div class="box-header with-border mar-bottom20">
           
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_shop' , 'class' => 'btn btn-success btn-sm m-1  px-3')) }}

            @if (@$layout == "create")

            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save-Continue', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat_continue' , 'value' => 'Edit_wallet' , 'class' => 'btn btn-dark btn-sm m-1  px-3')) }}

            @endif

            <a class="btn btn-info btn-sm m-1  px-3g" href="{{@$type == 1 ? route('shop.index') : route('InventoryProduct')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

            {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }}

              

             
        </div>
           @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit Product" : "Create Product"])

             <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{$layout == "edit" ?"Edit Product" : "Create Product"}}</h5>
                    <hr/>

                    <div class="col-xs-12">
                        <input type="hidden" name="product_type" value="{{@$type}}"/>
                        <div class="accordion accordion-flush" id="accordionFlushExample">
                        <div class="accordion-item">
                          <h2 class="accordion-header" id="flush-headingOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                             Product Information
                            </button>
                          </h2>
                          <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample" style="">
                            <div class="accordion-body">
                              
                              <div class="row">
                                <div class="col-xs-12 col-sm-4 col-md-3">
                                    <div class="item form-group">
                                        <label class="control-label margin__bottom" for="status">Product Name <span class="required">*</span>
                                        </label>
                                        <div class="feild">
                                            {{Form::text('product_name',@$data->product_name,array('id'=>"product_name",'class'=>"form-control col-md-7 col-xs-12" ,
                                            'placeholder'=>"Product Name",'required'=>"required"))}}
                                        </div>
                                    </div>
                                       
                                </div>

                                <div class="col-xs-12 col-sm-4 col-md-3">
                                    <div class="item form-group">
                                        <label class="control-label margin__bottom" for="status">Product Category <span class="required">*</span>
                                        </label>
                                        <div class="feild">
                                            <div class="feild">
                                                {{ Form::select('category_id',@$categories,@$data->category_id ,
                                                array('id'=>'category_id','class' => 'single-select form-control','required' => 'required',"placeholder"=>"select category" )) }}
                                            </div>
                                        </div>
                                    </div>
                                       
                                </div>
                                @if (@$type==1)
                                    <div class="col-xs-12 col-sm-4 col-md-3">
                                    <div class="item form-group">
                                        <label class="control-label margin__bottom" for="status">Product Brand <span class="required">*</span>
                                        </label>
                                        <div class="feild">
                                            {{ Form::select('brand_id',@$brands,@$data->brand_id ,
                                                array('id'=>'brands','class' => 'single-select form-control','required' => 'required' )) }}
                                        </div>
                                    </div>
                                       
                                </div>
                                @endif
                                
                                <div class="col-xs-12 col-sm-4 col-md-3">
                                    <div class="item form-group">
                                        <label class="control-label margin__bottom" for="status">SKU <span class="required">*</span>
                                        </label>
                                        <div class="feild">
                                            {{Form::text('product_sku',@$data->product_sku,array('id'=>"product_sku",'class'=>"form-control col-md-7 col-xs-12" ,
                                            'placeholder'=>"3456",'required'=>"required"))}}
                                        </div>
                                    </div>
                                       
                                </div>
                                
                               
                                <div class="col-xs-12 col-sm-4 col-md-3">
                                    <div class="item form-group">
                                        <label class="control-label margin__bottom" for="status">Product Price <span class="required">*</span>
                                        </label>
                                        <div class="feild">
                                            {{Form::number('selling_price',@$data->selling_price,array('id'=>"selling_price",'class'=>"form-control col-md-7 col-xs-12" ,
                                            'placeholder'=>"Product Price",'required'=>"required"))}}
                                        </div>
                                    </div>
                                       
                                </div>

                                <div class="col-xs-12 col-sm-4 col-md-3">
                                    <div class="item form-group">
                                        <label class="control-label margin__bottom" for="status">Product Quantity <span class="required">*</span>
                                        </label>
                                        <div class="feild">
                                            {{Form::number('product_qty',@$data->product_qty,array('id'=>"product_qty",'class'=>"form-control col-md-7 col-xs-12" ,
                                            'required'=>"required"))}}
                                        </div>
                                    </div>
                                       
                                </div>
    
                                <div class="col-xs-12 col-sm-4 col-md-3">
                                    <div class="item form-group">
                                        <label class="control-label margin__bottom" for="status">Product {{@$type==1 ? "Image" : "Sample Image"}} <span class="required">*</span>
                                        </label>
                                        <div class="feild">
                                            {{-- <div class="mb-3">
                                               
                                                <input class="form-control" type="file" id="formFile" name="product_thambnail" accept="image/*">

                                                @if ($layout=="edit")

                                                <img src="{{ asset(@$data->product_thambnail) }}" style="max-width:50px;" alt="image"/>
                                                    
                                                @endif
                                            </div> --}}
                                            <div class="mb-3">
                                                <input class="form-control thumb mb-2" type="file" id="imagec_img_student" name="imagec"  accept="image/png, image/jpeg,image/jpg">
                                                <img id="imagecholder" src="{{asset(@$data->product_thambnail)}}" style="max-height: 75px;display:{{@$data ? @$data->product_thambnail ? "" : "none" : "none"}}"><span class="back_to remove" id="remove_img_student" data-id="imagec" data-class="student" style="display: none;">X</span>
                                            </div>
                                        </div>
                                    </div>
                                       
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-3">
                                    <div class="item form-group">
                                        <label class="control-label margin__bottom" for="status">Product Description <span class="required">*</span>
                                        </label>
                                        <div class="feild">
                                            <div class="mb-3">
                                               
                                                <textarea class="form-control" id="inputAddress2" placeholder="Description..." rows="3" name="short_descp">{{ old("short_descp") ? old("short_descp") : @$data->short_descp }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                       
                                </div>

                                <div class="col-xs-12 col-sm-4 col-md-3">
                                    <div class="item form-group">
                                        <label class="control-label margin__bottom" for="status">Is recommended<span class="required"></span>
                                        </label>
                                         <div class="feild">
                                        <label class="switch">
                                            <input type="checkbox" name="is_recommended" id="re" {{@$data->is_recommended ==1 ? "checked" : ""}} class="toggle-class">
                                            <span class="slider round"></span>
                                        </label>
                                         </div>
                                    </div>
                                </div>

                                
                        
                            </div>
                            </div>
                          </div>
                        </div>
                        <div class="accordion-item">
                          <h2 class="accordion-header" id="flush-headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                             Supplier Information
                            </button>
                          </h2>
                          <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample" style="">
                            <div class="accordion-body">
                                <div class="row justify-content-center mb-4">
                                    <div class="col-md-4">
                                        <label class="control-label margin__bottom" for="status">Select Supplier <span class="required">*</span>
                                        </label>
                                        <div class="feild">
                                            {{ Form::select('supplier_id',$suppliers,@$data->supplier->id ,
                                            array('id'=>'supplier_id','class' => 'single-select form-control','required' => 'required','placeholder'=>'select supplier' )) }}
                                        </div>
                                        <p class="hint text-success" style="display:none;">*Enter The New Supplier Details.</p>
                                    </div>
                                </div>
                                <div class="row supplier_row" style="display:{{$layout == "edit" ? "" : "none"}}">
                                    <div class="col-xs-12 col-sm-4 col-md-3">
                                        <div class="item form-group">
                                            <label class="control-label margin__bottom" for="status">Supplier Name <span class="required">*</span>
                                            </label>
                                            <div class="feild">
                                                {{Form::text('supplier_name',@$data->supplier->supplier_name,array('id'=>"supplier_name",'class'=>"form-control col-md-7 col-xs-12" ,
                                                'placeholder'=>"Supplier Name",'required'=>"required"))}}
                                            </div>
                                        </div>
                                           
                                    </div>
                                    <div class="col-xs-12 col-sm-4 col-md-3">
                                        <div class="item form-group">
                                            <label class="control-label margin__bottom" for="status">Supplier Email <span class="required">*</span>
                                            </label>
                                            <div class="feild">
                                                {{Form::text('supplier_email',@$data->supplier->supplier_email,array('id'=>"supplier_email",'class'=>"form-control col-md-7 col-xs-12" ,
                                                'placeholder'=>"Supplier Email",'required'=>"required"))}}
                                            </div>
                                        </div>
                                           
                                    </div>
                                    <div class="col-xs-12 col-sm-4 col-md-3">
                                        <div class="item form-group">
                                            <label class="control-label margin__bottom" for="status">Supplier Mobile<span class="required">*</span>
                                            </label>
                                            <div class="feild">
                                                {{Form::text('supplier_mobile',@$data->supplier->supplier_mobile,array('id'=>"supplier_mobile",'class'=>"form-control col-md-7 col-xs-12" ,
                                                'placeholder'=>"Supplier Mopbile",'required'=>"required"))}}
                                            </div>
                                        </div>
                                           
                                    </div>
                                    <div class="col-xs-12 col-sm-4 col-md-3">
                                        <div class="item form-group">
                                            <label class="control-label margin__bottom" for="status">Supplier Address <span class="required">*</span>
                                            </label>
                                            <div class="feild">
                                                <div class="mb-3">
                                                   
                                                    <textarea class="form-control supplier_address" id="inputAddress2" placeholder="Address..." rows="3" name="supplier_address">{{ old("supplier_address") ? old("supplier_address") : @$data->supplier->supplier_address }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                           
                                    </div>
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

@section("scripts")
<script type="module">

    //var type=@json(@$type) == 2 ? "inventory_add" ? null

    

function notify_script(title,text,type,hide) {
            new PNotify({
                title: title,
                text: text,
                type: type,
                hide: hide,
                styling: 'fontawesome'
            })
        }

    window.brandurl='{{route('productbrand.index')}}';
    window.supplier_info = '{{route('shop.index')}}';
    ProductConfig.ProductConfiginit(notify_script,'shop');
</script>
@endsection

@section('script_link')

    <!-- validator -->
    {!! Cms::script("theme/vendors/validator/validator.js") !!}
    {!! Cms::script("theme/vendors/validator/validator_form.js") !!}

@endsection
