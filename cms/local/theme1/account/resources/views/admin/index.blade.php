@extends('layout::admin.master')

@section('title','account')
@section('style')
 <link rel="stylesheet" href="{{asset('assets/backend/css/account.css')}}">
    <!-- Datatables -->
    @include('layout::admin.head.list_head')
    <style>
        .table-div table {
            width: 100% !important;
        }
        .text-right{
            text-align: right;
        }
    </style>
@endsection
@section('body')
<div class="card">
    <div class="card-body">
        
        <section>

            <div class="py-5 bg-white border_r_8">
                <div class="row">
                    <div class="col-12">
                        <nav aria-label="breadcrumb" class="revenue_breadcrumb">
                            
                        </nav>
                    </div>
                   

                    <div class="col-8">
                        <h2 class="heading_style">Revenue Dashboard</h2>
                    </div>
                    <div class="col-4">
                       <div class="right__side">
                        <div>
                            {{-- <h6 class="text_sm">Total Balance</h6>
                            <div class="d-flex align-items-start pt-3" style="gap:15px;">
                                    <img src="{{asset('assets/images/select_icon.png')}}" class="img-fluid icon_style">
                                    <h6 class="text_black">{{Configurations::getConfig('site')->currency_symbol}} 0 </h6>
                                    
                            </div> --}}
                       </div>

                       <div>
                        <form>
                            {{ Form::select('academic_year',@$academic_years,@$selected_academic_year ,
                                    array('id'=>'status_','class' => 'single-select form-control w-auto mr-0 ml-auto fil_select','required' => 'required')) }}
                           
                        </form>
                       </div>
                        </div>
                        
                        
                    </div>
                </div>

                <div class="row py-3">

                    <div class="col-md-3">
                        <a href="{{route("IncomeExpenseCollectionReport",['type'=>"income"])}}">
                        <div class="box_style" style="background-color: #E3EAFC;">
                            <div class="top__content d-flex">
                                <img src="{{asset('assets/images/payments.png')}}" class="img-fluid icon_style mb-5">
                                
                                <div class="bg_gray  mb-5">
                                    <i class="fa fa-eye text-white"></i>
                                    <span class="text-white">View</span>
                                 
                                </div>
                            </div>
                            
                            <h6 class="text_sm">Income</h6>
                            <div class="row">
                                <div class="col-6 pr-1">
                                    <h6 class="text_black">{{Configurations::getConfig('site')->currency_symbol}} {{@$incomeexpense['income']->income}}</h6>
                                </div>
                                <div class="col-6 pl-1 text-right">
                                    <div class="d-inline-block">
                                        <div class="bg_gray">
                                            <img src="{{asset('assets/images/trending_up.png')}}" class="img-fluid icon_sm mr-1">
                                            <span class="text-white">25%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </a>
                    </div>
                   
                    <div class="col-md-3">
                         <a href="{{route("IncomeExpenseCollectionReport",['type'=>"expense"])}}">
                        <div class="box_style" style="background-color: #FFDFDF;">
                            <div class="top__content d-flex">
                            <img src="{{asset("assets/images/calculate.png")}}" class="img-fluid icon_style mb-5">
                            <div class="bg_gray  mb-5">
                                    <i class="fa fa-eye text-white"></i>
                                    <span class="text-white">View</span>
                                 
                            </div>
                            </div>
                            <h6 class="text_sm">Expenses</h6>
                            <div class="row">
                                <div class="col-6 pr-1">
                                    <h6 class="text_black">{{Configurations::getConfig('site')->currency_symbol}} {{@$incomeexpense['expense']->expense}}</h6>
                                </div>
                                <div class="col-6 pl-1 text-right">
                                    <div class="d-inline-block">
                                        <div class="bg_gray">
                                            <img src="{{asset("assets/images/trending_down.png")}}" class="img-fluid icon_sm mr-1">
                                            <span class="text-white">25%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                         </a>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="box_style" style="background-color: #D9F9E7;">
                            <img src="{{asset("assets/images/monetization_on.png")}}" class="img-fluid icon_style mb-5">
                            <h6 class="text_sm">Profit</h6>
                            <div class="row">
                                <div class="col-6 pr-1">
                                    <h6 class="text_black">{{Configurations::getConfig('site')->currency_symbol}} {{@$incomeexpense['profit']->profit}}</h6>
                                </div>
                                <div class="col-6 pl-1 text-right">
                                    <div class="d-inline-block">
                                        <div class="bg_gray">
                                            <img src="{{asset('assets/images/trending_up.png')}}" class="img-fluid icon_sm mr-1">
                                            <span class="text-white">25%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="box_style" style="background-color: #F5F0FF;">
                            <img src="{{asset("assets/images/account_balance.png")}}" class="img-fluid icon_style mb-5">
                            <h6 class="text_sm">Balance</h6>
                            <div class="row">
                                <div class="col-6 pr-1">
                                    <h6 class="text_black">{{Configurations::getConfig('site')->currency_symbol}} {{@$incomeexpense['balance']->balance}}</h6>
                                </div>
                                <div class="col-6 pl-1 text-right">
                                    <div class="d-inline-block">
                                        <div class="bg_gray">
                                            <img src="{{asset('assets/images/trending_up.png')}}" class="img-fluid icon_sm mr-1">
                                            <span class="text-white">25%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row pt-3">

                    <div class="col-9">
                        <div class="chart_box">
                            <div class="d-flex justify-content-between align-items-center px-3 py-2" style="border-bottom: 1px solid #E6E6E8">
                                <h5 class="mb-0 font-weight-bold">Money Flow</h5>
                                <div>
                                    {{-- <span class="fil_values active">Yearly</span>
                                    <span class="fil_values">Quarter</span> --}}
                                    <span class="fil_values active" id="month">Month</span>
                                    <span class="fil_values" id="day">ToDay</span>
                                    <a href="#" class="btn"><i class="fa fa-ellipsis-h"></i></a>
                                </div>
                            </div>
                            <div class="px-3 py-4">
                                <h6 class="text_sm">Total Profit</h6>
                                <div class="d-flex align-items-start pt-3" style="gap:15px;">
                                    <img src="{{asset('assets/images/select_icon.png')}}" class="img-fluid icon_style">
                                    <h6 class="text_black">{{Configurations::getConfig('site')->currency_symbol}} {{@$incomeexpense['profit']->profit}} </h6>
                                    <div class="d-inline-block">
                                        <div class="bg_gray">
                                            <img src="{{asset('assets/images/trending_up.png')}}" class="img-fluid icon_sm mr-1">
                                            <span class="text-white">25%</span>
                                        </div>
                                    </div>
                                </div>
                                <div id="moneychart"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-3">
                        <div class="chart_box">
                            <div class="d-flex justify-content-between align-items-center px-3 py-2" style="border-bottom: 1px solid #E6E6E8">
                                <h5 class="mb-0 font-weight-bold">Income</h5>
                                <a href="#" class="btn"><i class="fa fa-ellipsis-h"></i></a>
                            </div>
                            <div class="px-3 py-4">
                                <div id="expensechart" class="mb-4"></div>
                               
                                @forelse (@$expense_category as $expense )
                                
                              
                                <div class="d-flex justify-content-between">
                                    <h6 class="text_sm">
                                        <span class="round_icon round_icon{{$loop->index}}" style="background-color: #55FFAD;"></span>
                                        <span style="font-size: 14px;">{{$expense['category_name']}}</span></h6>
                                    <h6 style="font-size: 14px;">{{Configurations::getConfig("site")->currency_symbol}} {{$expense['total_amount']}}</h6>
                                </div>
                                
                               
                                
                                @empty
                                   <p>No Income</p> 
                                @endforelse
                                
                               
                                <div class="d-flex justify-content-between pt-4">
                                    <h6><b>Income</b></h6>
                                    <h6><b>{{Configurations::getConfig("site")->currency_symbol}} {{@$incomeexpense['income']->income}} </b></h6>
                                </div>
                            </div>
                        </div>
                        
                    </div>

                </div>
                
            </div>

        </section>
      
    </div>
</div>
@endsection

@section("scripts")
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script type="module">
    window.getdaygraph='{{route('account.index')}}'
    var income=@json(@$income_data);
    var expense=@json(@$expense_data);
    var month=@json(@$months_half);
    var category_name=@json(@$category_name);
    var category_data=@json(@$category_data);
    var total_graph=@json(@$total_graph);
    var profit_data=@json(@$profit_data);
    Account.AccountInit();
    Account.MoneyChat(income,expense,month,profit_data);
    Account.ExpenseChart(category_name,category_data,total_graph);
</script>
@endsection
