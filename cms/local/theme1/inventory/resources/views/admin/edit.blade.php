@extends('layout::admin.master')

@section('title','Inventory Distribution')
@section('style')


@endsection
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('inventory.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'purchase-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('inventory.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
        <div class="box-header with-border mar-bottom20">
           
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_transport' , 'class' => 'btn btn-success btn-sm m-1  px-3')) }}
            @if (@$layout == "create")

            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save-Continue', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat_continue' , 'value' => 'Edit_wallet' , 'class' => 'btn btn-dark btn-sm m-1  px-3')) }}

            @endif

            
            <a class="btn btn-info btn-sm m-1  px-3" href="{{route('inventory.index') }}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

            {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }}

              

             
        </div>
           @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit Inventory Distribution" : "Create New Inventory Distribution"])

             <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Inventory Distribution</h5>
                    <hr/>
                    <div class="col-xs-12">
                        <div class="row">
                            <input type="hidden" name="purchase_type" value="{{@$type}}"/>
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="status">Distribute Date <span class="required">*</span>
                                    </label>
                                    <div class="feild">
                                        {{Form::text('distribute_date',@$data->distribute_date,array('id'=>"distribute_date",'class'=>"form-control col-md-7 col-xs-12 datepicker" ,
                                       'placeholder'=>"Select Date",'required'=>"required","readonly"))}}
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-4 col-md-3">
                                    <div class="item form-group">
                                    <label class="control-label margin__bottom" for="status">Select User Group<span class="required">*</span>
                                        </label>
                                        <div class="feild">
                                            
                                            {{ Form::select('user_group',@$user_group,@$data->user_group_id,
                                            array('id'=>'timetableacyear','class' => @$layout =="edit" ? " form-control " : 
                                            "single-select form-control ",'required' => 'required','placeholder'=>"Select User Group",@$layout =="edit"? "disabled" : "")) }}
                                        </div>
                                    </div>
                                     
                                </div>
                            @if (@$layout == "edit" && @$is_student)
                                <div class="col-xs-12 col-sm-4 col-md-3 classselection">

                            @else
                                <div class="col-xs-12 col-sm-4 col-md-3 classselection d-none">
                            @endif
                            
                             
                                <div class="item form-group">
                                 <label class="control-label margin__bottom" for="status">Select Class<span class="required">*</span>
                                      </label>
                                      <div class="feild">
                                         
                                          {{ Form::select('class_id',@$class_lists,@$user_info->class_id,
                                          array('id'=>'class_id','class' => @$layout =="edit" ? " form-control " : 
                                          "single-select form-control ",'required' => 'required','placeholder'=>"Select Class",@$layout =="edit"? "disabled" : "")) }}
                                      </div>
                                </div>
                                     
                            </div>

                            @if (@$layout == "edit" && @$is_student)
                             <div class="col-xs-12 col-sm-4 col-md-3 sectionselection ">

                            @else
                                <div class="col-xs-12 col-sm-4 col-md-3 sectionselection d-none">
                            @endif


                            
                                <div class="item form-group">
                                 <label class="control-label margin__bottom" for="status">Select Section<span class="required">*</span>
                                      </label>
                                      <div class="feild">
                                         
                                          {{ Form::select('section_id',@$section_lists,@$user_info->section_id,
                                          array('id'=>'section_id','class' => @$layout =="edit" ? " form-control " : 
                                          "single-select form-control ",'required' => 'required','placeholder'=>"Select Section",@$layout =="edit"? "disabled" : "")) }}
                                      </div>
                                </div>
                                     
                            </div>
                             @if (@$layout == "edit" && !@$is_student)
                            <div class="col-xs-12 col-sm-4 col-md-3 select_user ">
                             @else
                                 <div class="col-xs-12 col-sm-4 col-md-3 select_user d-none">
                             @endif
                            
                                <div class="item form-group">
                                 <label class="control-label margin__bottom" for="status">Select User<span class="required">*</span>
                                      </label>
                                      <div class="feild">
                                         
                                          {{ Form::select('member_id[]',@$users,@$selected_users,
                                          array('id'=>'users_select','class' => @$layout =="edit" ? "multiple-select form-control " : 
                                          "multiple-select form-control ",'required' => 'required',@$layout =="edit"? "multiple" : "multiple",@$layout=="edit" ? "disabled" : "")) }}
                                      </div>
                                </div>
                                     
                            </div>
                             @if (@$layout == "edit" && @$is_student)
                              <div class="col-xs-12 col-sm-4 col-md-3 select_student">
                             @else
                              <div class="col-xs-12 col-sm-4 col-md-3 select_student d-none">
                             @endif
                          
                                <div class="item form-group">
                                 <label class="control-label margin__bottom" for="status">Select Student<span class="required">*</span>
                                      </label>
                                      <div class="feild">
                                         
                                          {{ Form::select('student_id[]',@$students,@$selected_students,
                                          array('id'=>'student_select','class' => @$layout =="edit" ? "multiple-select form-control " : 
                                          "multiple-select form-control ",'required' => 'required',@$layout =="edit"? "multiple" : "multiple", @$layout=="edit" ? "disabled" : "")) }}
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
                                            array('id'=>'category_id','class' => 'single-select form-control','required' => 'required',"placeholder"=>"select category",$layout == "edit"?"disabled" : "" )) }}
                                        </div>
                                    </div>
                                </div>
                                   
                            </div>

                           

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
                                    <label class="control-label margin__bottom" for="status">Available Stock <span class="required"></span>
                                    </label>
                                    <div class="feild">
                                        {{Form::number('stock',@$product->product_qty,array('id'=>"stockavailable",'class'=>"form-control col-md-7 col-xs-12 " ,
                                       'placeholder'=>"Stocks Available",'required'=>"required","readonly"))}}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="status">Quantity <span class="required">*</span>
                                    </label>
                                    <div class="feild">
                                        {{Form::number('quantity',@$data->quantity,array('id'=>"salequantity",'class'=>"form-control col-md-7 col-xs-12 " ,
                                       'placeholder'=>"Quantity",'required'=>"required"))}}
                                    </div>
                                </div>
                            </div>

                            
                            {{-- <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="status">Purchase Price <span class="required">*</span>
                                    </label>
                                    <div class="feild">
                                        {{Form::number('purchase_price',@$data->pur_price,array('id'=>"purchase_price",'class'=>"form-control col-md-7 col-xs-12",
                                       'placeholder'=>"Purchase price",'required'=>"required"))}}
                                    </div>
                                </div>
                            </div> --}}
                            {{-- <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="status">Selling Price <span class="required">*</span>
                                    </label>
                                    <div class="feild">
                                        {{Form::number('selling_price',@$data->selling_price,array('id'=>"selling_price",'class'=>"form-control col-md-7 col-xs-12" ,
                                       'placeholder'=>"Selling price",'required'=>"required"))}}
                                    </div>
                                </div>
                            </div> --}}
                      
                       
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
    window.usersurl='{{route('user.index')}}'
    window.sectionurl='{{route('section.index')}}'
    window.studentsurl='{{route('students.index')}}'
    window.checkquantity='{{route('inventory.index')}}'
    ProductConfig.ProductConfiginit(notify_script,"purchase",type);
    FeeConfig.Feeinit(notify_script,"inventory");
</script>
@endsection


@section('script_link')

    <!-- validator -->
    {!! Cms::script("theme/vendors/validator/validator.js") !!}
    {!! Cms::script("theme/vendors/validator/validator_form.js") !!}

@endsection
