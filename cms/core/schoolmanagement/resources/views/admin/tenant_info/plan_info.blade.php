@extends('layout::admin.master')

@section('title','Subscription Plan Informations')
@section('style')
@include('layout::admin.head.list_head')
    <link rel="stylesheet" href="{{asset('assets/backend/css/profile.css')}}">
   
@endsection

@section('body')
<div class="box-header with-border mar-bottom20">
    <a class="btn btn-info btn-sm m-1  px-3" href="{{ route('tenant_info.index') }}"><i
            class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>
</div>
    <div class="card card-custom-ash">
    
        <div class="card-body"> 
            <h3 class="mb-2">Plan Informations</h3>                                       
            <div class="card-text">
                <div class="row">
                    <div class="col-md-12 mb-2">
                        <p class="text-center h5"><span>Plan Name:</span> <span class="fw-bold ms-2">{{@$planinfo->plan_name}}</span></p>
                    </div>
                    <div class="col-md-6">
                        @if(@$planinfo->plan_type = "Term")
                          <p class="text-center"><span>Term Amount:</span> <span class="fw-bold ms-2">{{@$planinfo->plan_price_info->term_amount}}</span></p>
                        @else 
                          <p class="text-center"><span>Session Amount:</span> <span class="fw-bold ms-2">{{@$planinfo->plan_price_info->session_amount}}</span></p>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <p class="text-center"><span>Bill Amount:</span> <span class="fw-bold ms-2">{{@$school_profile->plan_payment->bill_amount}}</span></p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-center"><span>Discount(%):</span> <span class="fw-bold ms-2">{{@$school_profile->plan_payment->discount}}</span></p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-center"><span>Due Amount:</span> <span class="fw-bold ms-2">{{@$school_profile->plan_payment->due_amount}}</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>           

    <div class="card card-custom-ash">
    
        <div class="card-body"> 
            <h3 class="mb-3">Plan Features</h3>                                       
            <div class="card-text">                                                                                 
                <div class="row">										
                @forelse($moduleList as $moduleListKey=>$moduleListValue)
                        <div class="col-xs-10 col-md-4">
                            <div class="item form-group">
                                <div class="form-check">
                                    @if(in_array($moduleListKey,$moduleIds))
                                       <i class='bx bx-check text-success me-2' style="font-size:16px; "></i>
                                    @else
                                       <i class='bx bx-x text-danger me-2' style="font-size:16px; "></i>
                                    @endif
                                        <label class="form-check-label p-2" for="defaultCheck1">
                                        {{ $moduleListValue }}
                                        </label>
                                </div>
                            </div>
                        </div>
                @empty
                <div class="col-xs-10 col-md-4">
                    <div class="item form-group">
                        <div class="form-check">                                                        
                            <label class="form-check-label p-2" for="defaultCheck1">                                                            
                                <span class="form-check-label text-danger">No Modules Found</span>
                            </label>
                        </div>
                    </div>
                </div>
                @endforelse                                                                                                           
                </div>                                                   								                                           
            </div>                                                                                               
        </div>
    </div>   
@endsection
@section('script')
@endsection