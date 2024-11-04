@extends('layout::admin.master')

@section('title','payrool payment ')
@section('style')

@include('layout::admin.head.list_head')
<link rel="stylesheet" href="{{asset('assets/backend/css/attendance.css')}}">
@endsection
@section('body')
    <div class="x_content">
        @if (@$type == "report")
         {{ Form::open(array('role' => 'form', 'route'=>array('payrollbulkprint'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'payrool-form','novalidate' => 'novalidate')) }}
        @else
        {{ Form::open(array('role' => 'form', 'route'=>array('PaymentHistory'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'payrool-form','novalidate' => 'novalidate')) }} 
        @endif
       

      
      
        <div class="box-header with-border mar-bottom20">
           
             <a class="btn btn-info btn-sm m-1  px-3" href="{{route('PaymentHistory')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

              {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }}
  
             
        </div>
       
        @include("layout::admin.breadcrump",['route'=> @$type == "report" ?"Payroll Bulk Print" : "Payment History"])
       
        
           

             <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{@$type=="report" ? "Payroll Bulk Print" : "Payment History"}}</h5>
                    <input type="hidden" name="type" value="{{@$type}}"/>
                    <hr/>
                    <div class="col-xs-12">
                        <div class="row">
                        <div class="col-xs-12 col-sm-4 col-md-3">
                          <div class="item form-group">
                           <label class="control-label margin__bottom" for="status">User Group <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{ Form::select('group_id',@$user_group,@$group_id ,
                                    array('id'=>"group_id",'class' => 'user_group_salery_history single-select form-control','required' => 'required','placeholder' => 'Select User group' )) }}
                                </div>
                          </div>
                               
                        </div>

                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                            <label class="control-label margin__bottom" for="status">Select Staff<span class="required"></span>
                                    </label>
                                    <div class="feild">
                                       {{ Form::select('member_id[]',[],@$member_id ,
                                    array('id'=>"member_id",'class' => 'user_group_salery_history single-select form-control','required' => 'required','placeholder' => 'Select Staff',"multiple" )) }}
                                    </div>
                            </div>
                       
                        </div>

                      
                        <div class="col-xs-12 col-sm-4 col-md-3">
                          <div class="item form-group">
                           <label class="control-label margin__bottom" for="status">Select Month <span class="required">*</span>
                                </label>
                                <div class="feild">
                                     {{Form::text('month',@$monthyear,array('id'=>"month_year",'class'=>"form-control  month-picker col-md-7 col-xs-12" ,
                                   'placeholder'=>"Select Month",'required'=>"required"))}}
                                </div>
                          </div>
                               
                        </div>

                        <div class="col-xs-12 col-sm-4 col-md-3">
                          <div class="item form-group">
                           
                                <div class="feild">
                                    <button type="submit" class="btn btn-primary mt-4">Get Data</button>
                                </div>
                          </div>
                               
                        </div>
                                               
                        
                        </div>
                    </div>

                                       

                    {{-- userlists --}}
                    <div class="container_ saleryTemplateUserdataHistory">
                        @if (sizeof($users_data))
                             @include("payrool::paymenthistory.paymenthistoryuser",['users_data'=>$users_data])
                        @endif
                       
                    </div>
                        
                   

                    
                </div>
            </div>

        
       
       

        {{Form::close()}}
    </div>

@endsection


@section("scripts")
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

    window.salerytemplateurl='{{route('payroll.create')}}'
    window.getpayrollbulknfo="{{ route('payroll.create') }}"
     window.usersurl='{{route('user.index')}}'
    FeeConfig.Feeinit(notify_script,'payroll');
    FeeConfig.SaleryTemplate();
    AcademicConfig.Leaveinit(notify_script);

</script>
@endsection

@section('script_link')

    <!-- validator -->
    {!! Cms::script("theme/vendors/validator/validator.js") !!}
    {!! Cms::script("theme/vendors/validator/validator_form.js") !!}

@endsection
