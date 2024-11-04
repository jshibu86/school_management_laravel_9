@extends('layout::admin.master')

@section('title','payrool')
@section('style')

@include('layout::admin.head.list_head')
<link rel="stylesheet" href="{{asset('assets/backend/css/attendance.css')}}">
@endsection
@section('body')
    <div class="x_content">
        @if (@$type !="payment")
        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('payroll.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'payrool-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('payroll.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif

        @else
        {{ Form::open(array('role' => 'form', 'route'=>array('PayrollMakePayment'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'payrool-form','novalidate' => 'novalidate')) }}

        @endif
      
        <div class="box-header with-border mar-bottom20">
           
             <a class="btn btn-info btn-sm m-1  px-3" href="{{route('payroll.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

              {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }}

              

             
        </div>
        @if (@$type=="payment")
        @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit Payment " : "Make Payroll Payment"])
        @else

        @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit Salery " : "Assign Salery"])
            
        @endif
           

             <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{@$type=="payment" ? "Make Payroll Payment" : "Assign Salery To users"}}</h5>
                    <hr/>
                    <div class="col-xs-12">
                        <div class="row">
                        <div class="col-xs-12 col-sm-4 col-md-3">
                          <div class="item form-group">
                           <label class="control-label margin__bottom" for="status">User Group <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{ Form::select('group',@$user_group,@$data->group_id ,
                                    array('id'=>@$type,'class' => 'user_group_salery single-select form-control','required' => 'required','placeholder' => 'Select User group' )) }}
                                </div>
                          </div>
                               
                        </div>

                        @if (@$type == "payment")
                        <div class="col-xs-12 col-sm-4 col-md-3">
                          <div class="item form-group">
                           <label class="control-label margin__bottom" for="status">Select Month <span class="required">*</span>
                                </label>
                                <div class="feild">
                                     {{Form::text('month',@$data->month,array('id'=>"month_year",'class'=>"form-control month-picker col-md-7 col-xs-12" ,
                                   'placeholder'=>"Select Month",'required'=>"required"))}}
                                </div>
                          </div>
                               
                        </div>
                        @endif

                         
                        
                        </div>
                    </div>

                   


                    @if (@$layout=="edit")


                    {{-- userlists --}}
                    <div class="container_ saleryTemplateUserdata">

                        @include("payrool::admin.includes.salerytemplateuser",["data" => $data, "users_data" => $user_data,'layout'=>@$layout])
        
                    </div>

                    @else

                    {{-- userlists --}}
                    <div class="container_ saleryTemplateUserdata">
                         
        
                    </div>
                        
                    @endif

                    
                </div>
            </div>

        
       
       

        {{Form::close()}}
    </div>

@endsection


@section("scripts")
<script type="module">
    window.salerytemplateurl='{{route('payroll.create')}}'
    FeeConfig.SaleryTemplate();
</script>
@endsection

@section('script_link')

    <!-- validator -->
    {!! Cms::script("theme/vendors/validator/validator.js") !!}
    {!! Cms::script("theme/vendors/validator/validator_form.js") !!}

@endsection
