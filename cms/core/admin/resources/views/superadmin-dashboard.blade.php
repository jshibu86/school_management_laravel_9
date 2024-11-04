@php
    $theme = Configurations::getCurrentTheme();
@endphp
@section('style')
    <style>
        #signupchart1 {
            display: flex;
            justify-content: center;
            max-height: 50%;
            max-width: 50%;
            margin: 35px auto;
        }

        #signupchart2 {
            display: flex;
            justify-content: center;
            max-height: 50%;
            max-width: 50%;
            margin: 35px auto;
        }

        .flexchart {
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .chart {
            flex: 1;
            margin: 2px;
            max-width: 30%;
            box-sizing: border-box;
        }
    </style>
    @extends('layout::admin.master')

@section('title', 'dashboard')

@section('body')
    <div class="container-fluid">
        <div class="row">
            <div class="card-title btn_style">
                <h4 class="mb-0">Dashboard</h4>
                <!-- section for date select options -->
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="item form-group">
                            {!! Form::date('fromdate', 'default', ['class' => 'form-control', 'placeholder' => 'Select Date']) !!}
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="item form-group">
                            {!! Form::date('enddate', 'default', ['class' => 'form-control', 'placeholder' => 'Select Date']) !!}
                        </div>
                    </div>
                </div>

            </div>
            @if (Session::get('ACTIVE_GROUP') == 'Super Admin')

                @php
                    $cards = [
                        [
                            'title' => 'Total School',
                            'value' => @$schoolcount,
                            'image' => asset('assets/images/school_icon.png'),
                        ],
                        [
                            'title' => 'Total Revenues',
                            'value' => '₦ 0',
                            'image' => asset('assets/images/revenue_icon.png'),
                        ],
                        [
                            'title' => 'Active Subscribers',
                            'value' => @$activecount,
                            'image' => asset('assets/images/active_sub.png'),
                        ],
                        [
                            'title' => 'Expired Subscribers',
                            'value' => @$inactivecount,
                            'image' => asset('assets/images/expire_sub.png'),
                        ],
                    ];
                @endphp
                @foreach ($cards as $card)
                    <div class="col-10 col-lg-3">
                        <div class="card radius-15 overflow-hidden">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="ms-2 font-25">
                                        <span class="rounded-circle p-2 d-inline-block">
                                            <img src="{{ $card['image'] }}" alt="logo"
                                                style="max-width: 30px; max-height: 30px;">
                                        </span>
                                    </div>
                                    <div class="ms-2 font-18">
                                        <p class="feild mb-0 font-weight-bold text-info text-truncate">{{ $card['title'] }}
                                        </p>
                                        <h5 class="mb-0">{{ $card['value'] }}</h5>
                                        <div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

            <div class="col-md-6">
                <div class="card radius-15">
                    <div class="card-body">
                        <div class="" style="padding:20px;border-radius: 13px;">
                            <div id="revenuechart" class="" style="display: flex;justify-content:center;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card radius-15">
                    <div class="card-body">
                        <div class="subjectreport" style="padding:20px;border-radius: 13px;">
                            <div id="signupchart" class="" style="display: flex;justify-content:center;">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card radius-15">
                    <div class="card-body">
                        <div class="feild">
                            <b>Subscription Plan</b>
                        </div>
                        <div class="row" style="padding:0px;border-radius: 1px;">
                            @foreach($plans as $plan)
                            <div class="col-md-4">
                                <div id="subscriptionchart{{$plan->id}}" data-id="{{$plan->id}}" class="flexchart"> </div>
                            </div>
                            @endforeach
                         
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card radius-15">
                    <div class="card-body">
                        <div class="" style="padding:13px;border-radius: 3px;">
                            <div id="balhistorychart" class="" style="display: flex;justify-content:center;">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @section('script')

        <!-- javascript functions located in DashboardConfig.js file -->

        <script>
            window.getPlanList = "{{route('plans_list')}}";
            window.onload = function() {
                DashboardConfig.SignUpChart();
                DashboardConfig.RevenueChart();
                DashboardConfig.SubcriptionChart();
                DashboardConfig.BalanceChart();
            };
        </script>

    @endsection
