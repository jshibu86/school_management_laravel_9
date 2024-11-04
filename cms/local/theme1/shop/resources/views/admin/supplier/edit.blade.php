@extends('layout::admin.master')

@section('title','supplier')
@section('style')


@endsection
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('supplier.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'shop-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('supplier.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
        <div class="box-header with-border mar-bottom20">
           
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_shop' , 'class' => 'btn btn-success btn-sm m-1  px-3')) }}

            @if (@$layout == "create")

            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save-Continue', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat_continue' , 'value' => 'Edit_wallet' , 'class' => 'btn btn-dark btn-sm m-1  px-3')) }}

            @endif

            <a class="btn btn-info btn-sm m-1  px-3g" href="{{@$type == 1 ? route('shop.index') : route('supplier.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

            {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }}

              

             
        </div>
           @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit Supplier" : "Create Supplier"])

             <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{$layout == "edit" ?"Edit Supplier" : "Create Supplier"}}</h5>
                    <hr/>

                    <div class="col-xs-12">
                        <input type="hidden" name="product_type" value="{{@$type}}"/>
                        <div class="accordion accordion-flush" id="accordionFlushExample">
                       
                        <div class="accordion-item">
                          <h2 class="accordion-header" id="flush-headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                             Supplier Information
                            </button>
                          </h2>
                          <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample" style="">
                            <div class="accordion-body">
                                <div class="row">
                                    
                                   
                                    <div class="col-xs-12 col-sm-4 col-md-3">
                                        <div class="item form-group">
                                            <label class="control-label margin__bottom" for="status">Supplier Name <span class="required">*</span>
                                            </label>
                                            <div class="feild">
                                                {{Form::text('supplier_name',@$data->supplier_name,array('id'=>"supplier_name",'class'=>"form-control col-md-7 col-xs-12" ,
                                                'placeholder'=>"Supplier Name",'required'=>"required"))}}
                                            </div>
                                        </div>
                                           
                                    </div>
                                    <div class="col-xs-12 col-sm-4 col-md-3">
                                        <div class="item form-group">
                                            <label class="control-label margin__bottom" for="status">Supplier Email <span class="required">*</span>
                                            </label>
                                            <div class="feild">
                                                {{Form::text('supplier_email',@$data->supplier_email,array('id'=>"supplier_email",'class'=>"form-control col-md-7 col-xs-12" ,
                                                'placeholder'=>"Supplier Email",'required'=>"required"))}}
                                            </div>
                                        </div>
                                           
                                    </div>
                                    <div class="col-xs-12 col-sm-4 col-md-3">
                                        <div class="item form-group">
                                            <label class="control-label margin__bottom" for="status">Supplier Mobile<span class="required">*</span>
                                            </label>
                                            <div class="feild">
                                                {{Form::text('supplier_mobile',@$data->supplier_mobile,array('id'=>"supplier_mobile",'class'=>"form-control col-md-7 col-xs-12" ,
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
                                                   
                                                    <textarea class="form-control" id="inputAddress2" placeholder="Address..." rows="3" name="supplier_address">{{ old("supplier_address") ? old("supplier_address") : @$data->supplier_address }}</textarea>
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

    window.brandurl='{{route('productbrand.index')}}'
    ProductConfig.ProductConfiginit(notify_script,'shop');
</script>
@endsection

@section('script_link')

    <!-- validator -->
    {!! Cms::script("theme/vendors/validator/validator.js") !!}
    {!! Cms::script("theme/vendors/validator/validator_form.js") !!}

@endsection
