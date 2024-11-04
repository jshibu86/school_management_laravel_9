@extends('layout::admin.master')

@section('title','Inventory Sale')

@section('style')

<link rel="stylesheet" href="{{asset('assets/plugins/multiselect/example-styles.css')}}">
<link rel="stylesheet" href="{{asset('assets/plugins/multiselect/multiselect.css')}}">
@endsection
<style>
    #fee__items th{
        color: white !important;
        font-weight: 500 !important;
        font-size: 14px !important;
    }
</style>
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('inventory.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'feesetup-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('inventory.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
        <div class="box-header with-border mar-bottom20">
           
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_fees' , 'class' => 'btn btn-success btn-sm m-1  px-3 ')) }}

            @if (@$layout == "create")  

            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save-Continue', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat_continue' , 'value' => 'Edit_wallet' , 'class' => 'btn btn-dark btn-sm m-1  px-3')) }}

            @endif
           

           

             <a class="btn btn-info btn-sm m-1  px-3" href="{{route('inventory.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

              {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }}

              

             
        </div>
           @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit Inventory Sale" : "Create a New Inventory Sale"])

             <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Create Inventory Sale</h5>
                    <hr/>
                   <div class="col-xs-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-xs-12 col-sm-4 col-md-3">
                                    <div class="item form-group">
                                    <label class="control-label margin__bottom" for="status">Select User Group<span class="required">*</span>
                                        </label>
                                        <div class="feild">
                                            
                                            {{ Form::select('user_group',@$user_group,@$data->user_group,
                                            array('id'=>'timetableacyear','class' => @$layout =="edit" ? " form-control " : 
                                            "single-select form-control ",'required' => 'required','placeholder'=>"Select User Group",@$layout =="edit"? "disabled" : "")) }}
                                        </div>
                                    </div>
                                     
                                </div>
                          

                            
                            <div class="col-xs-12 col-sm-4 col-md-3 classselection d-none">
                                <div class="item form-group">
                                 <label class="control-label margin__bottom" for="status">Select Class<span class="required">*</span>
                                      </label>
                                      <div class="feild">
                                         
                                          {{ Form::select('class_id',@$class_lists,@$data->class_id,
                                          array('id'=>'class_id','class' => @$layout =="edit" ? " form-control " : 
                                          "single-select form-control ",'required' => 'required','placeholder'=>"Select Class",@$layout =="edit"? "disabled" : "")) }}
                                      </div>
                                </div>
                                     
                            </div>

                            <div class="col-xs-12 col-sm-4 col-md-3 sectionselection d-none">
                                <div class="item form-group">
                                 <label class="control-label margin__bottom" for="status">Select Section<span class="required">*</span>
                                      </label>
                                      <div class="feild">
                                         
                                          {{ Form::select('section_id',@$section_lists,@$data->section_id,
                                          array('id'=>'section_id','class' => @$layout =="edit" ? " form-control " : 
                                          "single-select form-control ",'required' => 'required','placeholder'=>"Select Section",@$layout =="edit"? "disabled" : "")) }}
                                      </div>
                                </div>
                                     
                            </div>

                           

                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                 <label class="control-label margin__bottom" for="status">Select User<span class="required">*</span>
                                      </label>
                                      <div class="feild">
                                         
                                          {{ Form::select('member_id[]',@$users,@$data->member_id,
                                          array('id'=>'users_select','class' => @$layout =="edit" ? " form-control " : 
                                          " form-control ",'required' => 'required',@$layout =="edit"? "disabled" : "multiple")) }}
                                      </div>
                                </div>
                                     
                            </div>

                                <div class="col-xs-12 col-sm-4 col-md-3">
                                    <div class="item form-group">
                                        <label class="control-label margin__bottom" for="status">Select Category<span class="required">*</span>
                                        </label>
                                      <div class="feild">
                                         
                                          {{ Form::select('category_id',[],@$data->category_id,
                                          array('id'=>'category_id','class' => @$layout =="edit" ? " form-control " : 
                                          "single-select form-control ",'placeholder'=>"Select Category",@$layout =="edit"? "disabled" : "")) }}
                                      </div>
                                    </div>
                                 </div>


                                 <div class="col-xs-12 col-sm-4 col-md-3">
                                    <div class="item form-group">
                                        <label class="control-label margin__bottom" for="status">Select Products<span class="required">*</span>
                                        </label>
                                        <div class="feild">
                                            
                                            {{ Form::select('products',[],null,
                                            array('id'=>'fees_type_select','class' => "form-control",'required' => 'required',"multiple")) }}
                                        </div>
                                    </div>
                                 </div>

                            </div>

                           
                        </div>
                        

                            <div class="row_ mt-3">
                                <table class="table table-striped" id="fee__items">
                                    <thead>
                                         <tr style="background-color: #2a3f54;">
                                        <th>Fee Name</th>
                                        <th>Amount {{ Configurations::getConfig("site")->currency_symbol }}</th>
                                        <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @if (@$layout=="edit")

                                    @foreach (@$data->feelists as$list )
                                        <tr>
                                        <td><input type="hidden" name="fee_name[]" value="{{ $list->fee_name }}" />
                                        <input type="hidden" name="fee_id[]" value="{{ $list->fee_id }}" />
                                        {{ $list->fee_name }}
                                        </td>
                                        <td><div class="item form-group"><input required type="number" name="fee_amount[]" class="form-control fee_amount " readonly value="{{ $list->fee_amount }}" /></div></td>
                                    
                                        <td></td></tr>
                                    @endforeach
                                        
                                    @endif

                                      

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td>Total Amount</td>
                                            @if (@$layout=="edit")
                                            <td>
                                                <input type="hidden" name="total_amount" value="{{ $data->total_amount }}" class="total_amount"/>
                                                <span class="total_amount_text">{{ Configurations::getConfig("site")->currency_symbol }}{{ $data->total_amount }}</span>
                                            </td>
                                            @else
                                             <td>
                                                <input type="hidden" name="total_amount" class="total_amount"/>
                                                <span class="total_amount_text">{{ Configurations::getConfig("site")->currency_symbol }} 0.00</span>
                                            </td>
                                            @endif
                                           
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                    
                                    
                                </table>


                               
                                
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


@section("scripts")
 <script src="{{asset('assets/plugins/multiselect/jquery.multi-select.js')}}"></script>
 <script src="{{asset('assets/plugins/multiselect/scripts/multiselect.min.js')}}"></script>
<script type="module">
$(document).ready(function() {

    function notify_script(title,text,type,hide) {
            new PNotify({
                title: title,
                text: text,
                type: type,
                hide: hide,
                styling: 'fontawesome'
            })
        }
      $("#fees_type_select").multiSelect();
      //$("#users_select").multiselect();
    var layout=@json(@$layout);
    var selectedid=@json(@$selectedid);
    var types=@json(@$feetypes);
    window.SelectedFeeArray=layout==="edit" ? selectedid : [0];
   window.usersurl='{{route('user.index')}}'
    FeeConfig.Feeinit(notify_script,"inventory");
    FeeConfig.FeeSetup(types,"inventory");
   
});


</script>
@endsection
