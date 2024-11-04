@extends('layout::admin.master')

@section('title','payrool')
@section('style')

@include('layout::admin.head.list_head')
<link rel="stylesheet" href="{{asset('assets/backend/css/attendance.css')}}">
@endsection
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('PayrollSchedule'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'payrool-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('PayrollSchedule',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
        <div class="box-header with-border mar-bottom20">
           
            

              {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }}

              

             
        </div>
           @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit Salery " : "Assign Salery"])

             <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Get Salery Payroll Schedule</h5>
                    <hr/>
                    <div class="col-xs-12">
                        <div class="row">
                        <div class="col-xs-12 col-sm-4 col-md-3">
                          <div class="item form-group">
                           <label class="control-label margin__bottom" for="status">User Group <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{ Form::select('group',@$user_group,@$data->group_id ,
                                    array('id'=>'status','class' => 'user_group_salery single-select form-control','required' => 'required','placeholder' => 'Select User group' )) }}
                                </div>
                          </div>
                               
                        </div>

                        <div class="col-xs-12 col-sm-4 col-md-3">
                          <div class="item form-group">
                           <label class="control-label margin__bottom" for="status">Select Month<span class="required">*</span>
                                </label>
                                <div class="feild">
                                     {{Form::text('month',@$data->month,array('id'=>"month_year",'class'=>"form-control  month-picker col-md-7 col-xs-12" ,
                                   'placeholder'=>"Select Month",'required'=>"required"))}}
                                </div>
                          </div>
                               
                        </div>

                         <div class="col-xs-12 col-sm-4 col-md-3">
                          <div class="item form-group" style="display: flex;gap: 10px;">
                          
                                <div class="feild" style="margin-top: 30px;">
                                    <button type="button" class="btn btn-primary getschedule">Get Schedule</button>
                                </div>
                                <div class="feild exportclass" style="margin-top: 30px;display:none">
                                    <button type="submit" class="btn btn-primary getschedule">Export</button>
                                </div>
                          </div>
                               
                        </div>

                       
                        </div>
                    </div>

                    <div class="col-xs-12 mt-4">
                        <div class="schedule__view" style="overflow: scroll;"></div>
                    </div>


                    

                    
                </div>
            </div>

        
       
       

        {{Form::close()}}
    </div>

@endsection


@section("scripts")
<script type="module">
    window.salerytemplateurl='{{route('payroll.create')}}';
    window.getscheduleurl='{{route('PayrollSchedule')}}';
    FeeConfig.SaleryTemplate();
</script>
@endsection

@section('script_link')

    <!-- validator -->
    {!! Cms::script("theme/vendors/validator/validator.js") !!}
    {!! Cms::script("theme/vendors/validator/validator_form.js") !!}

@endsection
