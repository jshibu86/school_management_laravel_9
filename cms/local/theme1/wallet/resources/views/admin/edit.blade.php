@extends('layout::admin.master')

@section('title','wallet')
@section('style')
<style>
    .invoice{
        min-height: 20px!important;
    }
</style>

@endsection
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('wallet.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'wallet-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('wallet.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
        <div class="box-header with-border mar-bottom20">
           
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_wallet' , 'class' => 'btn btn-success btn-sm m-1  px-3')) }}
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save-Continue', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat_continue' , 'value' => 'Edit_wallet' , 'class' => 'btn btn-dark btn-sm m-1  px-3')) }}
            <a class="btn btn-info btn-sm m-1  px-3" href="{{route('wallet.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

            {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }}

            

           
           
        </div>
           @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit wallet" : "Create wallet"])

             <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Add Wallet Amount</h5>
                    <hr/>
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                            <label class="control-label margin__bottom" for="status">Parent <span class="required">*</span>
                                    </label>
                                    <div class="feild">
                                        {{ Form::select('parent_id',@$parents,@$data->parent_id ,
                                        array('id'=>'status_p','class' => 'single-select form-control','required' => 'required',"placeholder"=>"Select Parent" )) }}
                                    </div>
                            </div>
                                
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="status">Wallet Type <span class="required">*</span>
                                    </label>
                                    <div class="feild">
                                        {{ Form::select('wallet_type',@$types,@$data->wallet_type ,
                                        array('id'=>'status_','class' => 'single-select form-control','required' => 'required',"placeholder"=>"Select Type" )) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-3 direct" style="{{ @$layout == "edit" && @$data->wallet_type=="direct" ? "":"display:none" }}">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="status">Wallet Amount <span class="required">*</span>
                                    </label>
                                    <div class="feild">
                                        {{ Form::number('wallet_amount',@$data->wallet_amount ,
                                        array('id'=>'status','class' => 'form-control','required' => 'required',"placeholder"=>"Amount" )) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-3 challan" style="{{ @$layout == "edit" && @$data->member_type!="direct" ? "":"display:none" }}">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="status">E-Payment Challan <span class="required">*</span>
                                    </label>
                                    <div class="feild">
                                        <div class="mb-3">
                                            
                                            <input class="form-control" type="file" id="formFile" name="wallet_attachment" required accept=".pdf">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    {{-- print parent details --}}
                        <div id="invoice" style="display: none">
                            <div class="invoice overflow-auto">
                                <div>
                                    <header>
                                        <div class="row">
                                            <div class="col">
                                                <a href="javascript:;">
                                                    <img src="" width="80" alt="" id="father_image">
                                                </a>
                                            </div>
                                            <div class="col company-details">
                                                <h2 class="name">
                                            <a target="_blank" href="javascript:;" id="father_name_details">
                                            Arboshiki
                                            </a>
                                        </h2>
                                        <div class="parent_details">

                                        </div>
                                               
                                            </div>
                                        </div>
                                    </header>
                                </div>
                            </div>
                        </div>

                        
                    </div>
                </div>
            </div>

        
       
       

        {{Form::close()}}
    </div>

@endsection
@section('scripts')

<script type="module">
    function notify_script(title,text,type,hide) {
            new PNotify({
                title: title,
                text: text,
                type: type,
                hide: hide,
                styling: 'fontawesome'
            })
        }
    window.parenturl="{{ route('students.index') }}";
   

    AcademicConfig.Walletinit(notify_script)
</script>
@endsection
@section('script_link')

    <!-- validator -->
    {!! Cms::script("theme/vendors/validator/validator.js") !!}
    {!! Cms::script("theme/vendors/validator/validator_form.js") !!}

@endsection
