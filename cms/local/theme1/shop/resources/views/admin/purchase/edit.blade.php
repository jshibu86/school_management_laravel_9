@extends('layout::admin.master')

@section('title','purchase order')
@section('style')


@endsection
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('purchase.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'purchase-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('purchase.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
        <div class="box-header with-border mar-bottom20">
           
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_transport' , 'class' => 'btn btn-success btn-sm m-1  px-3')) }}
            @if (@$layout == "create")

            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save-Continue', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat_continue' , 'value' => 'Edit_wallet' , 'class' => 'btn btn-dark btn-sm m-1  px-3')) }}

            @endif

            
            <a class="btn btn-info btn-sm m-1  px-3" href="{{@$type == 1 ? route('purchase.index') : route("InventoryPurchase")}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

            {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }}

              

             
        </div>
           @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit Purchase" : "Create New Purchase Order"])

             <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{@$type == 1 ? "Shop" : "Inventory"}} Purchase Order</h5>
                    <hr/>
                    <div class="col-xs-12">
                        <div class="row">
                            <input type="hidden" name="purchase_type" value="{{@$type}}"/>
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="status">Purchase Date <span class="required">*</span>
                                    </label>
                                    <div class="feild">
                                        {{Form::text('purchase_date',@$data->purchase_date,array('id'=>"purchase_date",'class'=>"form-control col-md-7 col-xs-12 datepicker" ,
                                       'placeholder'=>"Select Date",'required'=>"required","readonly"))}}
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="status">Product Category <span class="required">*</span>
                                    </label>
                                    <div class="feild">
                                        <div class="feild">
                                            {{ Form::select('category_id',@$categories,@$product->category_id ,
                                            array('id'=>'category_id','class' => 'single-select form-control','required' => 'required',"placeholder"=>"select category",$layout == "edit"?"disabled" : "" )) }}
                                        </div>
                                    </div>
                                </div>
                                   
                            </div>

                            @if (@$type == 1)
                                
                           
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="status">Product Brand <span class="required">*</span>
                                    </label>
                                    <div class="feild">
                                        {{ Form::select('brand_id',@$brands,@$product->brand_id ,
                                            array('id'=>'brands','class' => 'single-select form-control','required' => 'required',$layout == "edit"?"disabled" : ""  )) }}
                                    </div>
                                </div>
                                   
                            </div>
                             @endif

                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                 <label class="control-label margin__bottom" for="status">Select Product <span class="required">*</span>
                                      </label>
                                      @if (@$layout == "edit")
                                      <input type="hidden" name="product_id" value="{{ @$data->product_id }}"/>

                                      <div class="feild">
                                        {{ Form::select('product_id',@$products,@$data->product_id ,
                                        array('id'=>'status','class' => 'single-select form-control','required' => 'required' ,"disabled")) }}
                                    </div>
                                      @else

                                      <div class="feild">
                                        {{ Form::select('product_id',@$products,@$data->product_id ,
                                        array('id'=>'status','class' => 'single-select form-control','required' => 'required' )) }}
                                    </div>
                                      @endif
                                      
                                </div>
                                     
                              </div>

                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="status">Purchase Number <span class="required">*</span>
                                    </label>
                                    <div class="feild">
                                        {{Form::text('purchase_no',@$data->purchase_no,array('id'=>"purchase_no",'class'=>"form-control col-md-7 col-xs-12 " ,
                                       'placeholder'=>"Purchase Number",'required'=>"required"))}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="status">Bill Number <span class="required">*</span>
                                    </label>
                                    <div class="feild">
                                        {{Form::text('bill_no',@$data->bill_no,array('id'=>"bill_no",'class'=>"form-control col-md-7 col-xs-12 " ,
                                       'placeholder'=>"Bill Number",'required'=>"required"))}}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="status">Supplier <span class="required"></span>
                                    </label>
                                    <div class="feild">
                                         {{ Form::select('vendor_id',@$vendors,@$data->vendor_id ,
                                            array('id'=>'vendor_id','class' => 'single-select form-control',"placeholder"=>"select Supplier",$layout == "edit"?"disabled" : "" )) }}
                                       
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="status">Quantity <span class="required">*</span>
                                    </label>
                                    <div class="feild">
                                        {{Form::number('quantity',@$data->quantity,array('id'=>"quantity",'class'=>"form-control col-md-7 col-xs-12 " ,
                                       'placeholder'=>"Quantity",'required'=>"required",@$layout=="edit" ?"readonly":""))}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="status">Purchase Price <span class="required">*</span>
                                    </label>
                                    <div class="feild">
                                        {{Form::number('purchase_price',@$data->pur_price,array('id'=>"purchase_price",'class'=>"form-control col-md-7 col-xs-12",
                                       'placeholder'=>"Purchase price",'required'=>"required"))}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="status">Selling Price <span class="required">*</span>
                                    </label>
                                    <div class="feild">
                                        {{Form::number('selling_price',@$data->selling_price,array('id'=>"selling_price",'class'=>"form-control col-md-7 col-xs-12" ,
                                       'placeholder'=>"Selling price",'required'=>"required"))}}
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
    var type=@json(@$type);
 function notify_script(title,text,type,hide) {
            new PNotify({
                title: title,
                text: text,
                type: type,
                hide: hide,
                styling: 'fontawesome'
            })
        }
    window.brandurl='{{route('productbrand.index')}}'
    window.producturl='{{route('purchase.index')}}'
    ProductConfig.ProductConfiginit(notify_script,"purchase",type);
</script>
@endsection


@section('script_link')

    <!-- validator -->
    {!! Cms::script("theme/vendors/validator/validator.js") !!}
    {!! Cms::script("theme/vendors/validator/validator_form.js") !!}

@endsection
