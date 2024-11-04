@extends('layout::admin.master')

@section('title', 'Payroll Report')
@section('style')
    <!-- Datatables -->
    @include('layout::admin.head.list_head')
    <link rel="stylesheet" href="{{ asset('assets/backend/css/attendance.css') }}">
    <style>
        .table-div table {
            width: 100% !important;
        }

        .error {
            display: none;
        }

        /* .map-class{
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
            }
            .map-class li{
                list-style-type:none;
            } */
    </style>
@endsection
@section('body')
    {{ Form::open(['role' => 'form', 'route' => ['hostelreport'], 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'trasnportreport-form', 'novalidate' => 'novalidate']) }}
    <div class="card">
        <div class="card-body">
            <div class="card-title btn_style">
                <h4 class="mb-0">TuckShop Report</h4>

            </div>
            <hr />

            <div class="row purchase_overview" style="width: 60%;margin:auto;display:none">



                <div class="col-12 col-lg-4">
                    <div class="card radius-15 bg-sunset">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <h2 class="mb-0 text-white"><span id="purchase_product">0</span> <i
                                            class="bx bxs-up-arrow-alt font-14 text-white"></i> </h2>
                                </div>
                                <div class="ms-auto font-35 text-white"><i class='bx bx-female'></i>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-white">Total Purchase Product</p>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="card radius-15 bg-success">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <h2 class="mb-0 text-white"><span id="purchase_amount">0</span> <i
                                            class="bx bxs-up-arrow-alt font-14 text-white"></i> </h2>
                                </div>
                                <div class="ms-auto font-35 text-white"><i class='bx bx-female'></i>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-white">Total Purchase Amount</p>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

              <div class="row sales_overview" style="width: 60%;margin:auto;display:none">



                <div class="col-12 col-lg-4">
                    <div class="card radius-15 bg-sunset">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <h2 class="mb-0 text-white"><span id="sales_customer">0</span> <i
                                            class="bx bxs-up-arrow-alt font-14 text-white"></i> </h2>
                                </div>
                                <div class="ms-auto font-35 text-white"><i class='bx bx-female'></i>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-white">Total Number of Customer</p>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="card radius-15 bg-success">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <h2 class="mb-0 text-white"><span id="sales_product">0</span> <i
                                            class="bx bxs-up-arrow-alt font-14 text-white"></i> </h2>
                                </div>
                                <div class="ms-auto font-35 text-white"><i class='bx bx-female'></i>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-white">Total Sales Product</p>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="card radius-15 bg-success">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <h2 class="mb-0 text-white"><span id="sales_amount">0</span> <i
                                            class="bx bxs-up-arrow-alt font-14 text-white"></i> </h2>
                                </div>
                                <div class="ms-auto font-35 text-white"><i class='bx bx-female'></i>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-white">Total Sales Amount</p>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion" id="accordionExample">
                <div class="accordion-item">
                    <h1 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Get TuckShop Report
                        </button>
                    </h1>
                    <div id="collapseOne" class=" atnaccodrdian accordion-collapse collapse show"
                        aria-labelledby="headingOne" data-bs-parent="#accordionExample" style="">
                        <div class="accordion-body">
                            <div class="row">

                                <div class="col-xs-12 col-sm-4 col-md-3">
                                    <div class="item form-group">
                                        <label class="control-label margin__bottom" for="status">Academic Year <span
                                                class="required">*</span>
                                        </label>
                                        <div class="feild">
                                            @if (@$layout == 'edit')
                                                <input type="hidden" name="academic_year"
                                                    value="{{ @$data->academic_year }}" />
                                                <input type="hidden" name="class_id" value="{{ @$data->class_id }}" />
                                                <input type="hidden" name="section_id" value="{{ @$data->section_id }}" />
                                                <input type="hidden" name="term_id" value="{{ @$data->term_id }}" />
                                            @endif
                                            {{ Form::select('academic_year', @$academicyears, Configurations::getCurrentAcademicyear(), [
                                                'id' => 'academic',
                                                'class' => @$layout == 'edit' ? ' form-control' : 'single-select form-control',
                                                'required' => 'required',
                                                'placeholder' => 'Select Academic year',
                                                @$layout == 'edit' ? 'disabled' : '',
                                            ]) }}
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-3 mb-4">
                                    <div class="item form-group">
                                        <label for="exam_term" class="mb-2">Exam Term <span>*</span></label>


                                        {{ Form::select('academic_term', @$examterms, @$data->term_id ? @$data->term_id : @$current_academic_term, [
                                            'id' => 'examterm',
                                            'class' => 'single-select form-control',
                                            'required' => 'required',
                                            'placeholder' => 'Select Exam Term',
                                            @$layout == 'edit' ? 'disabled' : '',
                                        ]) }}
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-3">
                                    <div class="item form-group">
                                        <label class="control-label margin__bottom" for="status">Service<span
                                                class="required">*</span>
                                        </label>
                                        <div class="feild">

                                            {{ Form::select('service', [1 => 'Purchase', 2 => 'Sales'], null, [
                                                'id' => 'service',
                                                'class' => @$layout == 'edit' ? ' form-control' : 'single-select form-control',
                                                'required' => 'required',
                                                'placeholder' => 'Select Service',
                                                @$layout == 'edit' ? 'disabled' : '',
                                            ]) }}
                                        </div>
                                    </div>

                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-3">
                                    <div class="item form-group">
                                        <label class="control-label margin__bottom" for="status">Select Report Type <span
                                                class="required">*</span>
                                        </label>
                                        <div class="feild">
                                            <div class="feild">
                                                {{ Form::select('report_type', Configurations::REPORTTYPE, @$type, [
                                                    'id' => 'report_type',
                                                    'class' => 'single-select form-control',
                                                    'required' => 'required',
                                                    'placeholder' => 'select report type',
                                                ]) }}
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-xs-12 col-sm-4 col-md-3 weekly" style="display: none;">
                                    <div class="item form-group">
                                        <label class="control-label margin__bottom" for="status">Start Date <span
                                                class="required">*</span>
                                        </label>
                                        <div class="feild">
                                            <div class="feild">
                                                {{ Form::text('start_date', @$start_date, [
                                                    'id' => 'start_date',
                                                    'class' => ' form-control datepicker_academic_start startdate',
                                                    'placeholder' => 'select start date',
                                                ]) }}
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-3 weekly" style="display: none;">
                                    <div class="item form-group">
                                        <label class="control-label margin__bottom" for="status">End Date <span
                                                class="required">*</span>
                                        </label>
                                        <div class="feild">
                                            <div class="feild">
                                                {{ Form::text('end_date', @$end_date, [
                                                    'id' => 'end_date',
                                                    'class' => ' form-control datepicker_academic_start enddate',
                                                    'placeholder' => 'select end date',
                                                ]) }}
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-xs-12 col-sm-4 col-md-3 daily" style="display: none;">
                                    <div class="item form-group">
                                        <label class="control-label margin__bottom" for="status">Select Day <span
                                                class="required">*</span>
                                        </label>
                                        <div class="feild">
                                            <div class="feild">
                                                {{ Form::text('day', @$day, [
                                                    'id' => 'day',
                                                    'class' => ' form-control datepicker_academic_start day',
                                                    'placeholder' => 'select day',
                                                ]) }}
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-xs-12 col-sm-4 col-md-3 monthly" style="display: none;">
                                    <div class="item form-group">
                                        <label class="control-label margin__bottom" for="status">Select Month <span
                                                class="required">*</span>
                                        </label>
                                        <div class="feild">
                                            <div class="feild">
                                                {{ Form::text('month', @$month, [
                                                    'id' => 'month',
                                                    'class' => ' form-control month-picker month',
                                                    'placeholder' => 'select month',
                                                ]) }}
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-xs-12 col-sm-4 col-md-3 yearly" style="display: none;">
                                    <div class="item form-group">
                                        <label class="control-label margin__bottom" for="status">Select Year <span
                                                class="required">*</span>
                                        </label>
                                        <div class="feild">
                                            <div class="feild">
                                                {{ Form::text('year', @$year, [
                                                    'id' => 'year',
                                                    'class' => ' form-control year-picker year',
                                                    'placeholder' => 'select year',
                                                ]) }}
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div id="sales_feilds" style="display: none">
                                
                                <div class="row">

                                

                                <div class="col-xs-12 col-sm-4 col-md-3">
                                    <div class="item form-group">
                                        <label class="control-label margin__bottom" for="status">Select Payment Type <span
                                                class="required"></span>
                                        </label>
                                        <div class="feild">
                                            <div class="feild">
                                                {{ Form::select('payment_type', Configurations::PAYMENTYPE, @$type, [
                                                    'id' => 'payment_type',
                                                    'class' => 'single-select form-control',
                                                    'required' => 'required',
                                                    'placeholder' => 'select payment type',
                                                ]) }}
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-xs-12 col-sm-4 col-md-3">
                                    <div class="item form-group">
                                        <label class="control-label margin__bottom" for="status">Select Delivery Status <span
                                                class="required"></span>
                                        </label>
                                        <div class="feild">
                                            <div class="feild">
                                                {{ Form::select('delivery_status', Configurations::DELIVRY, @$type, [
                                                    'id' => 'delivery_status',
                                                    'class' => 'single-select form-control',
                                                    'required' => 'required',
                                                    'placeholder' => 'select delivery status',
                                                ]) }}
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-3">
                                    <div class="item form-group">
                                        <label class="control-label margin__bottom" for="status">Select Payment Status <span
                                                class="required"></span>
                                        </label>
                                        <div class="feild">
                                            <div class="feild">
                                                {{ Form::select('payment_status', Configurations::PAYMENTSTATUS, @$type, [
                                                    'id' => 'payment_status',
                                                    'class' => 'single-select form-control',
                                                    'required' => 'required',
                                                    'placeholder' => 'select payment status',
                                                ]) }}
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                </div>

                                 </div>

                            </div>







                            {{-- <div class="col-xs-12 col-sm-4 col-md-3">
                                        <div class="item form-group">
                                        <label class="control-label margin__bottom" for="status">Select Month <span class="required">*</span>
                                                </label>
                                                <div class="feild">
                                                    {{Form::text('month',@$data->month,array('id'=>"month_years",'class'=>"form-control month-picker-pay col-md-7 col-xs-12" ,
                                                'placeholder'=>"Select Month",'required'=>"required"))}}
                                                </div>
                                        </div>
                               
                                    </div> --}}


                            <div class="col-md-2 ">
                                <button type="button" id="tuckshop__report"
                                    class="btn btn-primary  add_btn att_btn w-100" name="daily"> <i class="fa fa-plus"
                                        name="daily"></i>&nbsp;&nbsp;Get Report</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>


        </div>


        <div class="card-body purchasereport">
           
        </div>

    </div>
    </div>
    {{ Form::close() }}




@endsection

@section('scripts')
    <script type="module">
        $('#datatable-buttons-tuckshop-purchase').DataTable();

        function notify_script(title, text, type, hide) {
            new PNotify({
                title: title,
                text: text,
                type: type,
                hide: hide,
                styling: 'fontawesome'
            })
        }


        window.sectionurl = '{{ route('section.index') }}';
        window.classurl = '{{ route('schooltype.index') }}'
        window.payrollreport = '{{ route('payrollreport') }}'
        window.payrolltotalamount = '{{ route('payrolltotalamount') }}'
        window.usersurl = '{{ route('user.index') }}'
        window.tuckshopreport = '{{ route('tuckshopreport') }}'
        AttendanceConfig.AttendanceInit(notify_script);
        ReportConfig.ReportInit(notify_script, 'tuckshop');
        AcademicConfig.Leaveinit(notify_script);
    </script>
@endsection
@section('script')
    <script>
        window.statuschange = '{{ route('transportroute_action_from_admin') }}';
    </script>

@endsection
