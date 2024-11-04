@extends('layout::admin.master')

@section('title', 'subscriptionmanagement-edit')
@section('style')
    @include('layout::admin.head.list_head')
    <link rel="stylesheet" href="{{ asset('assets/backend/css/profile.css') }}">
@endsection

@section('body')
    <div class="container-fluid">
        <div class="row">
            <div class="card-title btn_style">
                <h4 class="mb-0">Subscription Management</h4>
            </div>
        </div>
        @if (Session::get('ACTIVE_GROUP') == 'Super Admin')
            <div class="card radius-15">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-9 col-md-8 col-sm-12">
                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                <li class="nav-item1" role="presentation">
                                    <a class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" href="#pills-home"
                                        role="tab" aria-controls="pills-home" aria-selected="true">Subscription Plan</a>
                                </li>
                                <li class="nav-item2" role="presentation">
                                    <a class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" href="#pills-profile"
                                        role="tab" aria-controls="pills-profile" aria-selected="false">Price Setup</a>
                                </li>
                                <li class="nav-item3" role="presentation">
                                    <a class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" href="#pills-contact"
                                        role="tab" aria-controls="pills-contact" aria-selected="false">Subscription
                                        Setting</a>
                                </li>
                            </ul>
                            <hr />
                        </div>
                    </div>
                    <!-- plan creation setup (Tab-1) -->
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                            aria-labelledby="pills-home-tab">
                            <div class="box-header mar-bottom20">
                                <a href="{{ route('create.newplan') }}" class="btn btn-primary btn-sm m-1 px-3">Create
                                    Plan&nbsp;&nbsp;&nbsp;<i class="fa fa-plus-square"></i></a>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="table-responsive">
                                        <table id="example1" class="table" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Subscription Plan</th>
                                                    <th class="noExport">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- price setup tab content (Tab-2) -->
                        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                            <div class="box-header mar-bottom20">
                                <a href="{{ route('setupplanprice.create') }}" class="btn btn-primary btn-sm m-1 px-3">Setup
                                    Price&nbsp;&nbsp;&nbsp;<i class="fa fa-plus-square"></i></a>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="table-responsive">
                                        <table id="example2" class="table" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Plan Name</th>
                                                    <th>Amount(Term)</th>
                                                    <th>Amount(Session)</th>
                                                    <th>Modules</th>
                                                    <th>Visible</th>
                                                    <th class="noExport">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Subscription Setting(Tab-3) -->
                        <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                            {{ Form::open(['role' => 'form', 'route' => ['subscriptionsetting.store'], 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'schoolmanagement-form', 'novalidate' => 'novalidate']) }}
                            <h6>Extra Days Privilege</h6>
                            <div class="card" style="width: 100%;border: 0.5px solid #a9a9a9;">
                                <div class="card-body">
                                    <div class="card-text">
                                        <div id="addMoreRow" class="row">
                                            <div class="col-xs-6 col-md-3">
                                                <div class="item form-group">
                                                    <label class="control-label">School Name :<span>*</span></label>
                                                    <div class="feild">
                                                        {{ Form::select('school_id[]', ['all' => 'All', 'CSI School' => 'CSI School'], 'default', [
                                                            'class' => 'form-control rounded-pill col-md-7 col-xs-12',
                                                            'placeholder' => 'Select',
                                                        ]) }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-md-3">
                                                <div class="item form-group">
                                                    <label class="control-label">Extra Number of Days:</label>
                                                    <div class="feild">
                                                        {{ Form::text('privilege_days[]', @$data->privilege_days, [
                                                            'id' => 'privilege_days',
                                                            'class' => 'form-control rounded-pill col-md-7 col-xs-12',
                                                            'placeholder' => '30 days',
                                                        ]) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newSchoolRow" class="row">

                                        </div>
                                    </div>
                                    <div class="with-border mar-bottom20">
                                        {{ Form::button('<i class="fa fa-plus-square"></i>&nbsp;&nbsp;&nbsp;Add More', ['type' => 'button', 'id' => 'rowAdder', 'name' => 'rowAdder', 'value' => 'rowAdder', 'class' => 'btn btn-primary btn-sm m-1  px-1']) }}
                                    </div>

                                </div>
                            </div>
                            <div class="card" style="width: 100%;border: 0.5px solid #a9a9a9;">
                                <div class="card-body">
                                    <div class="card-text">
                                        <div class="row">
                                            <div class="col-xs-12 col-md-12">
                                                <div class="item form-group">
                                                    <label class="control-label">Set Number of Days to notify before
                                                        expiration days:</label>
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-md-3">
                                                <div class="feild">
                                                    {{ Form::text('notify_days', @$data->notify_days, [
                                                        'id' => 'notify_days',
                                                        'class' => 'form-control rounded-pill col-md-7 col-xs-12',
                                                        'placeholder' => '30 days',
                                                    ]) }}
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h6>Additional Payment Information</h6>
                            <div class="card" style="width: 100%;border: 0.5px solid #a9a9a9;">
                                <div class="card-body">
                                    <div class="card-text">
                                        <div id="addPayMoreRow" class="row" style="display:none;">
                                            <div class="col-xs-6 col-md-3">
                                                <div class="item form-group">
                                                    <label class="control-label">Payment Information
                                                        :<span>*</span></label>
                                                    <div class="feild">
                                                        {{ Form::text('pay_info[]', @$data->pay_tax, [
                                                            'id' => 'pay_tax',
                                                            'class' => 'form-control rounded-pill col-md-4 col-xs-6',
                                                            'placeholder' => 'tax',
                                                        ]) }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-4 col-md-2">
                                                <div class="item form-group">
                                                    <label class="control-label">Calculation:</label>
                                                    <div class="feild">
                                                        {{ Form::select('pay_info[]', ['add' => 'Add', 'subtract' => 'Subtract'], 'default', [
                                                            'class' => 'form-control rounded-pill col-md-4 col-xs-6',
                                                            'placeholder' => 'Select',
                                                        ]) }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-4 col-md-2">
                                                <div class="item form-group">
                                                    <label class="control-label">Rate:</label>
                                                    <div class="feild">
                                                        {{ Form::select('pay_info[]', ['%' => '%', '%' => '%'], 'default', [
                                                            'class' => 'form-control rounded-pill col-md-4 col-xs-6',
                                                            'placeholder' => '%',
                                                        ]) }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-4 col-md-2">
                                                <div class="item form-group">
                                                    <label class="control-label">Amount:</label>
                                                    <div class="feild">
                                                        {{ Form::select('pay_info[]', ['20' => '20%', '10' => '10%'], 'default', [
                                                            'class' => 'form-control rounded-pill col-md-4 col-xs-6',
                                                        ]) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newPayRow" class="row">

                                        </div>
                                        <div class="with-border mar-bottom20">
                                            {{ Form::button('<i class="fa fa-plus-square"></i>&nbsp;&nbsp;&nbsp;Add More', ['type' => 'button', 'id' => 'rowPayAdder', 'name' => 'rowPayAdder', 'value' => 'rowPayAdder', 'class' => 'btn btn-primary btn-sm m-1  px-1']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>`
                            <h6>Reminder Setting</h6>
                            <div class="card" style="width: 100%;border: 0.5px solid #a9a9a9;">
                                <div class="card-body">
                                    <div class="card-text">
                                        <div class="row">
                                            <div class="col-3">
                                                <div class="nav flex-column nav-pills" id="verticalTab" role="tablist"
                                                    aria-orientation="vertical">
                                                    <a class="nav-link active" id="vertical-tab1" data-bs-toggle="pill"
                                                        href="#soonexpire-tab" role="tab"
                                                        aria-controls="soonexpire-tab" aria-selected="true">Soon
                                                        Expired</a>
                                                    <a class="nav-link" id="vertical-tab2" data-bs-toggle="pill"
                                                        href="#expiredpriv-tab" role="tab"
                                                        aria-controls="expiredpriv-tab"
                                                        aria-selected="false">Expired-Privilege</a>
                                                    <a class="nav-link" id="vertical-tab3" data-bs-toggle="pill"
                                                        href="#expired-tab" role="tab" aria-controls="expired-tab"
                                                        aria-selected="false">Expired</a>
                                                </div>
                                            </div>
                                            <div class="col-9">
                                                <div class="tab-content">
                                                    <div class="tab-pane fade show active" id="soonexpire-tab"
                                                        role="tabpanel" aria-labelledby="vertical-tab1">
                                                        {{ Form::textarea('reminder_setting1', @$data->reminder_setting1, ['id' => 'reminder_setting1', 'rows' => '3', 'class' => 'form-control rounded-pill col-md-7 col-xs-12', 'Placeholder' => 'text']) }}
                                                    </div>
                                                    <div class="tab-pane fade" id="expiredpriv-tab" role="tabpanel"
                                                        aria-labelledby="vertical-tab2">
                                                        {{ Form::textarea('reminder_setting2', @$data->reminder_setting2, ['id' => 'reminder_setting2', 'rows' => '3', 'class' => 'form-control rounded-pill col-md-7 col-xs-12', 'Placeholder' => 'text']) }}
                                                    </div>
                                                    <div class="tab-pane fade" id="expired-tab" role="tabpanel"
                                                        aria-labelledby="vertical-tab3">
                                                        {{ Form::textarea('reminder_setting3', @$data->reminder_setting3, ['id' => 'reminder_setting3', 'rows' => '3', 'class' => 'form-control rounded-pill col-md-7 col-xs-12', 'Placeholder' => 'text']) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box-header with-border mar-bottom20">
                                {{ Form::button('<i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back', ['type' => 'button', 'id' => 'back_btn', 'name' => 'Back', 'value' => 'Back', 'onclick' => 'GeneralConfig.movePlanPreviousTab("#pills-profile-tab")', 'class' => 'btn btn-primary btn-lg m-1  px-3']) }}
                                {{ Form::button('Save Setting &nbsp;&nbsp;&nbsp;<i class="fa fa-plus-square "></i>', ['type' => 'submit', 'id' => 'next_btn', 'name' => 'Save', 'value' => 'Save', 'class' => 'btn btn-primary btn-lg m-1  px-3']) }}
                            </div>
                        </div> <!-- End of tab pane fade  -->

                    </div>
                </div>
                {{ Form::close() }}
        @endif

    </div>

@endsection

@section('script')
    <!-- validator -->
    {!! Cms::script('theme/vendors/validator/validator.js') !!}


    <!-- To fetch MODULE details from database -->
    <script>
        $(document).ready(function() {

            // Add additional fields for privilege info to enter new data
            $('#rowAdder').click(function() {
                var newField = '<div id="addMoreRow" class="row">' +
                    '<div class="col-xs-6 col-md-3">' +
                    '<div class="item form-group">' +
                    '<label class="control-label">' +
                    "School Name :" + '</label>' +
                    '<div class="feild">' +
                    ' {{ Form::select('school_id[]', ['all' => 'All', 'CSI School' => 'CSI School'], 'default', ['class' => 'form-control rounded-pill col-md-7 col-xs-12', 'placeholder' => 'Select']) }}' +
                    '</div>' +
                    '</div>' +
                    '</div>	' +
                    '<div class="col-xs-6 col-md-3">' +
                    '<div class="item form-group">' +
                    '<label class="control-label">' +
                    'Extra Number of Days:' +
                    '</label>' +
                    '<div class="feild">' +
                    '{{ Form::text('privilege_days[]', @$data->privilege_days, ['id' => 'privilege_days', 'class' => 'form-control rounded-pill col-md-7 col-xs-12', 'placeholder' => '30 days']) }}' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '<div class="col-xs-6 col-md-3">' +
                    '<div class="item form-group">' +
                    '<label class="control-label"></label>' +
                    '<div class="feild">    ' +
                    ' {{ Form::button(' <span class="form-check-label text-danger"><i class="fa fa-times"></i></span>', ['type' => 'button', 'id' => 'rowSubtracter', 'name' => 'rowSubtracter', 'class' => 'btn  btn-sm m-1 ']) }} ' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>';
                $('#newSchoolRow').append(newField);

            });


            // Add additional field for payment info
            $('#rowPayAdder').click(function() {
                var newPayField = '<div id="addPayMoreRow" class="row">' +
                    '<div class="col-xs-6 col-md-3">' +
                    '<div class="item form-group">' +
                    '<label class="control-label">Payment Information :<span>*</span></label>' +
                    '<div class="feild">' +
                    '{{ Form::text('pay_info[]', @$data->pay_tax, ['id' => 'pay_tax', 'class' => 'form-control rounded-pill col-md-4 col-xs-6', 'placeholder' => 'tax']) }}' +
                    '</div>' +
                    '</div>' +
                    '</div>	' +
                    '<div class="col-xs-4 col-md-2">' +
                    '<div class="item form-group">' +
                    '<label class="control-label">Calculation:</label>' +
                    '<div class="feild">                                               ' +
                    '{{ Form::select('pay_info[]', ['add' => 'Add', 'subtract' => 'Subtract'], 'default', ['class' => 'form-control rounded-pill col-md-4 col-xs-6', 'placeholder' => 'Select']) }}' +
                    '</div>' +
                    '</div>' +
                    '</div>	' +
                    '<div class="col-xs-4 col-md-2">' +
                    '<div class="item form-group">' +
                    '<label class="control-label">Rate:</label>' +
                    '<div class="feild">' +
                    '{{ Form::select('pay_info[]', ['%' => 'percent', '%' => 'percent'], 'default', ['class' => 'form-control rounded-pill col-md-4 col-xs-6', 'placeholder' => '%']) }}' +
                    '</div>' +
                    '</div>' +
                    '</div>	' +
                    '<div class="col-xs-4 col-md-2">' +
                    '<div class="item form-group">' +
                    '<label class="control-label">Amount:</label>' +
                    '<div class="feild">' +
                    '{{ Form::select('pay_info[]', ['20' => '20%', '10' => '10%'], 'default', ['class' => 'form-control rounded-pill col-md-4 col-xs-6']) }}' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '<div class="col-xs-4 col-md-2">' +
                    '<div class="item form-group">' +
                    '<label class="control-label"></label>' +
                    '<div class="feild"> ' +
                    '{{ Form::button(' <span class="form-check-label text-danger"><i class="fa fa-times"></i></span>', ['type' => 'button', 'id' => 'rowPaySubtracter', 'name' => 'rowPaySubtracter', 'class' => 'btn  btn-sm m-1 ']) }}' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div> ';
                $('#newPayRow').append(newPayField);
            });

            // remove field for new payment info
            $(document).on('click', '#rowPaySubtracter', function() {
                $(this).parents("#addPayMoreRow").remove();
            });

            // remove field for existing payment info
            $(document).on('click', '#existPaySubtracter', function() {
                $(this).parents("#existPayRow").remove();
            });

            // remove field for new reminder info 
            $(document).on('click', '#rowSubtracter', function() {
                $(this).parents("#addMoreRow").remove();
            });

            // remove field for existing reminder info 
            $(document).on('click', '#existSubtracter', function() {
                $(this).parents("#existRow").remove();
            });

            function getColumnConfig(target) {

                if (target === "#pills-home") {
                    return {
                        element: $("#example1"),
                        url: '{{ route('get_plan_list_data') }}',
                        column: [
                            // { data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false, sortable: false },
                            {
                                data: 'rownum',
                                name: 'rownum',
                                searchable: false,
                                sortable: false
                            },
                            {
                                data: 'plan_name',
                                name: 'name',
                                width: '20%',
                                className: 'textcenter'
                            },
                            {
                                data: 'action',
                                name: 'id',
                                searchable: false,
                                sortable: false,
                                className: 'text-right'
                            }
                        ]
                    };
                } else if (target === "#pills-profile") {
                    return {
                        element: $("#example2"),
                        url: '{{ route('get_plan_price_list_data') }}',
                        column: [{
                                data: 'rownum',
                                name: 'rownum',
                                searchable: false,
                                sortable: false
                            },
                            {
                                data: 'plan_name',
                                name: 'plan_name',
                                width: '20%'
                            },
                            {
                                data: 'term_amount',
                                name: 'term_amount',
                                width: '20%'
                            },
                            {
                                data: 'session_amount',
                                name: 'session_amount',
                                width: '20%'
                            },
                            {
                                data: 'module_count',
                                name: 'module_count',
                                width: '20%'
                            },
                            {
                                data: 'visible_status',
                                name: 'visible_status',
                                width: '20%'
                            },
                            {
                                data: 'action',
                                name: 'id',
                                searchable: false,
                                sortable: false,
                                className: 'textcenter'
                            }
                        ]
                    };
                } else if (target === "#pills-contact") {
                    // fetch previously entered data from database using ajax call
                    $.ajax({
                        url: '{{ route('get_plan_setting_data') }}',
                        type: 'GET',
                        success: function(response) {

                            // to list school menu and privilege days           
                            let subscriptionSetting = response.subscription_setting;
                            let schoolSettings = response.subscription_school_setting;
                            if (schoolSettings.length > 0) {
                                $('#addMoreRow').hide();
                            } else {
                                $('#addMoreRow').show();
                            }

                            schoolSettings.forEach(function(setting, index) {
                                let select1 = setting.school_info;
                                let privilegeDays = setting.privilege_days;

                                let schoolSettingHtml = `<div id="existRow" class="row">										                                   
                                    <div class="col-xs-4 col-md-2">
                                        <div class="item form-group">
                                            <label class="control-label">School Name:</label>
                                            <div class="feild">                                               
                                                <select id="school_id[]" name="school_id[]" class="form-control rounded-pill col-md-4 col-xs-6">
                                                    <option value="all"  ${select1 === 'all' ? 'selected' : ''}>All</option>
                                                    <option value="CSI School"  ${select1 === 'CSI School' ? 'selected' : ''}>CSI School</option>
                                                    <option value="Other"  ${select1 === 'Other' ? 'selected' : ''}>Other School</option>                                                    
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-md-3">
                                        <div class="item form-group">
                                            <label class="control-label">Extra Number of Days :<span>*</span></label>
                                            <div class="feild">         
                                                <input type="text" id="privilege_days[]" name="privilege_days[]" value="${privilegeDays}" class="form-control rounded-pill col-md-4 col-xs-6">                                                                                      
                                            </div>
                                        </div>
                                    </div>                                    
                                    <div class="col-xs-4 col-md-2">
                                        <div class="item form-group">
                                            <label class="control-label"></label>
                                                <div class="feild"> 
                                                    {{ Form::button(' <span class="form-check-label text-danger"><i class="fa fa-times"></i></span>', ['type' => 'button', 'id' => 'existSubtracter', 'name' => 'rowSubtracter', 'class' => 'btn  btn-sm m-1 ']) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>                                                
                                `;
                                $('#newSchoolRow').append(schoolSettingHtml);

                            });

                            // for reminder info
                            let reminderInfo = JSON.parse(subscriptionSetting.reminder_info);
                            let {
                                soon_expired,
                                expired_privilege,
                                expired
                            } = reminderInfo;
                            $('#reminder_setting1').val(soon_expired);
                            $('#reminder_setting2').val(expired_privilege);
                            $('#reminder_setting3').val(expired);

                            // for notify info
                            $('#notify_days').val(subscriptionSetting.notify_days);

                            $('#newPayRow').empty();

                            // for existing payment info
                            let paymentInfo = JSON.parse(subscriptionSetting.payment_info);

                            for (let i = 0; i < paymentInfo.length; i += 4) {
                                let textfield = paymentInfo[i];
                                let select1 = paymentInfo[i + 1];
                                let select2 = paymentInfo[i + 2];
                                let select3 = paymentInfo[i + 3];

                                let text1Html = `   <div id="existPayRow" class="row">										
                                    <div class="col-xs-6 col-md-3">
                                        <div class="item form-group">
                                            <label class="control-label">Payment Information :<span>*</span></label>
                                            <div class="feild">         
                                                <input type="text" id="payment_input_${i}" name="pay_info[]" value="${textfield}" class="form-control rounded-pill col-md-4 col-xs-6">                                                                                      
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-4 col-md-2">
                                        <div class="item form-group">
                                            <label class="control-label">Calculation:</label>
                                            <div class="feild">                                               
                                                <select id="payment_select_${i+1}" name="pay_info[]" class="form-control rounded-pill col-md-4 col-xs-6">
                                                    <option value="subtract" ${select1 === 'subtract' ? 'selected' : ''}>Subtract</option>
                                                    <option value="add" ${select1 === 'add' ? 'selected' : ''}>Add</option>
                                                    <option value="" ${select1 === null ? 'selected' : ''}>None</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-4 col-md-2">
                                        <div class="item form-group">
                                            <label class="control-label">Rate:</label>
                                                <div class="feild">                                               
                                                    <select id="payment_select_${i+2}" name="pay_info[]" class="form-control rounded-pill col-md-4 col-xs-6">
                                                        <option value="subtract" ${select2 === '%' ? 'selected' : ''}>%</option>
                                                        <option value="add" ${select2 === 'percent' ? 'selected' : ''}>Percent</option>
                                                        <option value="" ${select2 === null ? 'selected' : ''}>None</option>
                                                    </select>
                                                </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-4 col-md-2">
                                        <div class="item form-group">
                                            <label class="control-label">Amount:</label>
                                                <div class="feild">                                               
                                                    <select id="payment_select_${i+3}" name="pay_info[]" class="form-control rounded-pill col-md-4 col-xs-6">
                                                        <option value="subtract" ${select3 === '10' ? 'selected' : ''}>10</option>
                                                        <option value="add" ${select3 === '20' ? 'selected' : ''}>20</option>
                                                        <option value="" ${select3 === null ? 'selected' : ''}>None</option>
                                                    </select>
                                                </div>
                                        </div>
                                    </div>
                                        <div class="col-xs-4 col-md-2">
                                        <div class="item form-group">
                                            <label class="control-label"></label>
                                                <div class="feild"> 
                                                    {{ Form::button(' <span class="form-check-label text-danger"><i class="fa fa-times"></i></span>', ['type' => 'button', 'id' => 'existPaySubtracter', 'name' => 'rowPaySubtracter', 'class' => 'btn  btn-sm m-1 ']) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>                                                
                                `;
                                $('#newPayRow').append(text1Html);

                            }

                            if (subscriptionSetting.payment_info.length > 0) {
                                $('#addPayMoreRow').hide();
                            } else {
                                $('#addPayMoreRow').show();
                            }

                        },
                        error: function(request, error) {
                            console.log(error);
                            alert("Request: " + JSON.stringify(request));
                        }
                    });
                }
                return null;
            }

            function initializeDataTable(config) {
                var csrf = '{{ csrf_token() }}';
                var options = {
                    button: [{
                            name: "Trash",
                            url: "{{ route('user_action_from_admin', -1) }}"
                        },
                        {
                            name: "Delete",
                            url: "{{ route('user.destroy', 1) }}",
                            method: "DELETE"
                        }
                    ],
                    // Uncomment and modify if needed
                    // order: [[6, "desc"]],
                    // lengthMenu: [[100, 250, 500], [100, 250, 500]]
                };

                // Destroy any existing DataTable instance
                if ($.fn.DataTable.isDataTable(config.element)) {
                    config.element.DataTable().destroy();
                }

                dataTable(config.element, config.url, config.column, csrf, options);
            }

            function displayIndex(target) {

                var config = getColumnConfig(target);
                if (config) {
                    initializeDataTable(config);
                }
            }

            // Display the table on page load if the first tab is active
            if ($('#pills-home').hasClass('active')) {
                displayIndex('#pills-home');
            }

            // Listen for click events on the tab links
            $('a[data-bs-toggle="pill"]').on('click', function(e) {
                var target = $(this).attr("href");
                displayIndex(target);

            });

        });
    </script>

    <script>
        // Destroy any existing DataTable instance
        if ($.fn.DataTable.isDataTable(config.element)) {
            config.element.DataTable().destroy();
        }
    </script>

@endsection
