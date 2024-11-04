@extends('layout::admin.master')

@section('title', 'schoolmanagement-edit')
@section('style')
    @include('layout::admin.head.list_head')
    <link rel="stylesheet" href="{{ asset('assets/backend/css/profile.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        .select2-container--bootstrap4 .select2-selection{
            border-radius: 50rem !important;
        }
        .select2-search--dropdown .select2-search__field {
            border-radius: 2.25rem !important;
        }
        .select2-search--dropdown {
            border-radius: 1.25rem !important;
        }

        .select2-container--bootstrap4 .select2-dropdown {
            border-top-right-radius: 1rem !important;
            border-top-left-radius:  1rem !important;
        }
    </style>
@endsection

@section('body')
    <div class="container-fluid">
        <div class="row">
            <div class="card-title btn_style">
                <h4 class="mb-0">School Management</h4>
            </div>
        </div>
        @if (Session::get('ACTIVE_GROUP') == 'Super Admin')
            @if ($layout == 'create')
                {{ Form::open(['role' => 'form', 'route' => ['schoolmanagement.store'], 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'schoolmanagement-form', 'novalidate' => 'novalidate']) }}
            @elseif($layout == 'edit')
                {{ Form::open(['role' => 'form', 'route' => ['schoolmanagement.update', $data->id], 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form', 'novalidate' => 'novalidate']) }}
            @endif

            <div class="card radius-15">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-9 col-md-8 col-sm-12">
                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" href="#pills-home"
                                        role="tab" aria-controls="pills-home" aria-selected="true">School Profile</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" href="#pills-profile"
                                        role="tab" aria-controls="pills-profile" aria-selected="false">Contact
                                        Person</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" href="#pills-contact"
                                        role="tab" aria-controls="pills-contact" aria-selected="false">Payment
                                        Preview</a>
                                </li>
                            </ul>
                        </div>
                        <hr />
                    </div>

                    <div class="tab-content" id="pills-tabContent">
                        <!-- school profile tab content -->
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                            aria-labelledby="pills-home-tab">
                            <div class="parent_box">
                                <div class="col-2">
                                    <div class="item form-group">
                                        <img id="pimage" name="pimage" class="image rounded-circle"
                                            src="{{ @$data->image ? @$data->image : asset('assets/images/staff.jpg') }}"
                                            alt="profile_image"
                                            style="width: 120px;height: 120px; padding: px; margin: 0px; ">
                                        <label for="file" class="btn btn-primary px-1 justify-content-between"><i
                                                class="fa fa-edit"></i> </label>
                                        <input id="file" name="photo" style="visibility:hidden;" type="file"
                                            onchange="document.getElementById('pimage').src = window.URL.createObjectURL(this.files[0])">
                                    </div>
                                </div>
                                <!-- form input text fields-->
                                <div class="col-xs-12" style="margin-bottom: 7px;">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-4">
                                            <div class="item form-group">
                                                <label class="form-check-label mb-2" for="first_name"> School Name
                                                </label> <span class="form-check-label text-danger">*</span>
                                                <div class="feild">
                                                    {{ Form::text('school_name', @$data->school_name, [
                                                        'id' => 'school_name',
                                                        'class' => 'form-control rounded-pill col-md-7 col-xs-12',
                                                        'placeholder' => 'First name',
                                                        'required' => 'required',
                                                    ]) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-4">
                                            <div class="item form-group">
                                                <label class="form-check-label mb-2" for="first_name"> School Name /
                                                    Abbreivation:
                                                </label>
                                                <div class="feild">
                                                    {{ Form::text('school_name_abbr', @$data->school_name, [
                                                        'id' => 'school_name_abbr',
                                                        'class' => 'form-control rounded-pill col-md-7 col-xs-12',
                                                        'placeholder' => 'Enter school abbreviation',
                                                        'required' => 'required',
                                                    ]) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-4">
                                            <div class="item form-group">
                                                <label class="form-check-label mb-2" for="first_name"> Official Email <span class="form-check-label text-danger">*</span>
                                                </label>
                                                <div class="feild">
                                                    {{ Form::text('email', @$data->email, [
                                                        'id' => 'email',
                                                        'class' => 'form-control rounded-pill col-md-7 col-xs-12',
                                                        'placeholder' => 'Enter office email',
                                                        'required' => 'required',
                                                    ]) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-4">
                                            <div class="item form-group">
                                                <label class="form-check-label mb-2" for="first_name"> Official Phone
                                                    Number
                                                </label> <span class="form-check-label text-danger">*</span>
                                                <div class="feild">
                                                    {{ Form::text('phoneno', @$data->phoneno, [
                                                        'id' => 'phoneno',
                                                        'class' => 'form-control rounded-pill col-md-7 col-xs-12',
                                                        'placeholder' => 'Enter phone number',
                                                        'required' => 'required',
                                                    ]) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-4">
                                            <div class="item form-group">
                                                <label class="form-check-label mb-2" for="first_name"> Total Student Count <span class="form-check-label text-danger">*</span>
                                                </label>
                                                <div class="feild">
                                                    {{ Form::text('student_count', @$data->student_count, [
                                                        'id' => 'student_count',
                                                        'class' => 'form-control rounded-pill col-md-7 col-xs-12',
                                                        'placeholder' => 'Enter total students',
                                                        'required' => 'required',
                                                        'required' => 'required',
                                                    ]) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-4">
                                            <div class="item form-group">
                                                <label class="form-check-label mb-2" for="first_name"> Subscription Plan <span class="form-check-label text-danger">*</span>
                                                </label>
                                                <div class="feild">
                                                    {{ Form::select('subscription_plan', @$planList, @$data->plan_id, [
                                                        'id' => 'subscription_plan',
                                                        'class' => 'form-control rounded-pill col-md-7 col-xs-12 single-select',
                                                        'placeholder' => 'Select Plan',
                                                        'required' => 'required',
                                                    ]) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-4">
                                            <div class="item form-group">
                                                <label class="form-check-label mb-2" for="first_name"> Discount 
                                                </label>
                                                <div class="feild">
                                                    {{ Form::text('discount', @$data->discount, [
                                                        'id' => 'discount',
                                                        'class' => 'form-control rounded-pill col-md-7 col-xs-12',
                                                        'placeholder' => 'Enter school address',
                                                        'required' => 'required',
                                                        'placeholder' => '%',
                                                        'required' => 'required',
                                                    ]) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-4">
                                            <div class="item form-group">
                                                <label class="form-check-label mb-2" for="first_name"> Billing Cycle <span class="form-check-label text-danger">*</span>
                                                </label>
                                                <div class="feild">
                                                    {{ Form::select('billing_cycle', ['1' => 'Term', '2' => 'Session'], @$data->billing_id, [
                                                        'id' => 'billing_cycle',
                                                        'class' => 'form-control rounded-pill col-md-7 col-xs-12 single-select',
                                                        'placeholder' => 'Select Cycle',
                                                        'required' => 'required',
                                                    ]) }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-6 col-md-4">
                                            <div class="item form-group">
                                                <label class="form-check-label mb-2" for="first_name"> Permanent Address <span class="form-check-label text-danger">*</span>
                                                </label>
                                                <div class="feild">
                                                    {{ Form::text('school_address', @$data->address, [
                                                        'id' => 'address',
                                                        'class' => 'form-control rounded-pill col-md-7 col-xs-12',
                                                        'placeholder' => 'Enter school address',
                                                        'required' => 'required',
                                                    ]) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-4">
                                            <div class="item form-group">
                                                <label class="form-check-label mb-2" for="first_name"> City <span class="form-check-label text-danger">*</span>
                                                </label>
                                                <div class="feild">
                                                    {{ Form::text('school_city', @$data->city, [
                                                        'id' => 'school_city',
                                                        'class' => 'form-control rounded-pill col-md-7 col-xs-12',
                                                        'placeholder' => 'Enter school city',
                                                        'required' => 'required',
                                                    ]) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-4">
                                            <div class="item form-group">
                                                <label class="form-check-label mb-2" for="first_name"> Postal Code <span class="form-check-label text-danger">*</span>
                                                </label>
                                                <div class="feild">
                                                    {{ Form::text('postal_code', @$data->pincode, [
                                                        'id' => 'postal_code',
                                                        'class' => 'form-control rounded-pill col-md-7 col-xs-12',
                                                        'placeholder' => 'Enter Postal Code',
                                                        'required' => 'required',
                                                    ]) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-4">
                                            <div class="item form-group">
                                                <label class="form-check-label mb-2" for="first_name"> Country <span class="form-check-label text-danger">*</span>
                                                </label>
                                                <div class="feild">
                                                    {{ Form::text('school_country', @$data->country, [
                                                        'id' => 'school_country',
                                                        'class' => 'form-control rounded-pill col-md-7 col-xs-12',
                                                        'placeholder' => 'Enter school country',
                                                        'required' => 'required',
                                                    ]) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box-header with-border mar-bottom20">
                                {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Reset', ['type' => 'reset', 'id' => 'reset_btn', 'name' => 'reset', 'value' => 'Reset', 'class' => 'btn btn-primary btn-lg m-1  px-3']) }}
                                <a href="#" class="btn btn-primary btn-lg m-1 px-3"
                                    onclick="GeneralConfig.moveNextTab('#pills-profile-tab')">Next&nbsp;&nbsp;&nbsp;<i
                                        class="fa fa-arrow-right"></i></a>
                                <!-- {{ Form::button('Next&nbsp;&nbsp;&nbsp;<i class="fa fa-arrow-right"></i>', ['type' => 'button', 'id' => 'next_btn', 'name' => 'Next', 'value' => 'Next', 'class' => 'btn btn-primary btn-lg m-1  px-3', 'onclick' => 'GeneralConfig.moveNextTab("#pills-profile-tab")']) }}                                                                                                                             -->
                            </div>
                        </div>

                        <!-- contact person tab content -->
                        <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                            aria-labelledby="pills-profile-tab">
                            <div class="parent_box" style="padding-left: 50px;">
                                <!-- form input text fields-->
                                <div class="col-xs-12" style="margin-bottom: 7px;">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-4">
                                            <div class="item form-group">
                                                <label class="form-check-label mb-2" for="first_name"> First Name <span class="form-check-label text-danger">*</span>
                                                </label> 
                                                <div class="feild">
                                                    {{ Form::text('first_name', @$cdata->first_name, [
                                                        'id' => 'first_name',
                                                        'class' => 'form-control rounded-pill col-md-7 col-xs-12',
                                                        'placeholder' => 'Enter first name',
                                                    ]) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-4">
                                            <div class="item form-group">
                                                <label class="form-check-label mb-2" for="first_name"> Last Name <span class="form-check-label text-danger">*</span>
                                                </label>
                                                <div class="feild">
                                                    {{ Form::text('last_name', @$cdata->last_name, [
                                                        'id' => 'last_name',
                                                        'class' => 'form-control rounded-pill col-md-7 col-xs-12',
                                                        'placeholder' => 'Enter last name',
                                                    ]) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-4">
                                            <div class="item form-group">
                                                <label class="form-check-label mb-2" for="first_name"> Email <span class="form-check-label text-danger">*</span>
                                                </label>
                                                <div class="feild">
                                                    {{ Form::text('contact_person_email', @$cdata->email, [
                                                        'id' => 'contact_person_email',
                                                        'class' => 'form-control rounded-pill col-md-7 col-xs-12',
                                                        'placeholder' => 'Enter email',
                                                        'required' => 'required',
                                                    ]) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-4 mt-3">
                                            <div class="item form-group">
                                                <label class="form-check-label mb-2" for="first_name"> Phone Number <span class="form-check-label text-danger">*</span>
                                                </label>
                                                <div class="feild">
                                                    {{ Form::text('contact_person_phoneno', @$cdata->phoneno, [
                                                        'id' => 'contact_person_phoneno',
                                                        'class' => 'form-control rounded-pill col-md-7 col-xs-12',
                                                        'placeholder' => 'Enter phone number',
                                                    ]) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-4 mt-3">
                                            <div class="item form-group">
                                                <label class="form-check-label mb-2" for="first_name"> Role
                                                </label>
                                                <div class="feild">
                                                    {{ Form::text('contact_person_role', @$cdata->role, [
                                                        'id' => 'contact_person_role',
                                                        'class' => 'form-control rounded-pill col-md-7 col-xs-12',
                                                        'placeholder' => 'Enter role',
                                                        'required' => 'required',
                                                    ]) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-4 mt-3">
                                            <div class="item form-group">
                                                <label class="form-check-label mb-2" for="first_name"> Gender <span class="form-check-label text-danger">*</span>
                                                </label>
                                                <div class="feild">
                                                    {{ Form::select('contact_person_gender', ['male' => 'Male', 'female' => 'Female'], @$cdata->gender, [
                                                        'class' => 'form-control rounded-pill col-md-7 col-xs-12 single-select',
                                                        'placeholder' => 'Select gender',
                                                    ]) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-4 mt-3">
                                            <div class="item form-group">
                                                <label class="form-check-label mb-2" for="first_name"> Permanent Address <span class="form-check-label text-danger">*</span>
                                                </label>
                                                <div class="feild">
                                                    {{ Form::text('contact_person_address', @$cdata->address, [
                                                        'id' => 'contact_person_address',
                                                        'class' => 'form-control rounded-pill col-md-7 col-xs-12',
                                                        'placeholder' => 'Enter address',
                                                        'required' => 'required',
                                                    ]) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-4 mt-3">
                                            <div class="item form-group">
                                                <label class="form-check-label mb-2" for="first_name"> City <span class="form-check-label text-danger">*</span>
                                                </label>
                                                <div class="feild">
                                                    {{ Form::text('contact_person_city', @$cdata->city, [
                                                        'id' => 'contact_person_city',
                                                        'class' => 'form-control rounded-pill col-md-7 col-xs-12',
                                                        'placeholder' => 'Enter city name',
                                                    ]) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-4 mt-3">
                                            <div class="item form-group">
                                                <label class="form-check-label mb-2" for="first_name"> Postal Code <span class="form-check-label text-danger">*</span>
                                                </label>
                                                <div class="feild">
                                                    {{ Form::text('contact_person_postcode', @$cdata->pincode, [
                                                        'id' => 'contact_person_postcode',
                                                        'class' => 'form-control rounded-pill col-md-7 col-xs-12',
                                                        'placeholder' => 'Enter Postal Code',
                                                        'required' => 'required',
                                                    ]) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-4 mt-3">
                                            <div class="item form-group">
                                                <label class="form-check-label mb-2" for="first_name"> Country <span class="form-check-label text-danger">*</span>
                                                </label>
                                                <div class="feild">
                                                    {{ Form::text('contact_person_country', @$cdata->country, [
                                                        'id' => 'contact_person_country',
                                                        'class' => 'form-control rounded-pill col-md-7 col-xs-12',
                                                        'placeholder' => 'Enter country name',
                                                    ]) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-4 mt-3">
                                            <div class="item form-group">
                                                <label class="form-check-label mb-2" for="first_name"> Domain <span class="form-check-label text-danger">*</span>
                                                </label>
                                                <div class="feild">
                                                    {{ Form::text('domain', @$cdata->domain, [
                                                        'id' => 'domain',
                                                        'class' => 'form-control rounded-pill col-md-7 col-xs-12',
                                                        'placeholder' => 'Enter domain name',
                                                    ]) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 mt-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="db_checked">
                                                <label class="form-check-label" for="db_checked">
                                                 Add Database Username and Password
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 mt-3 db_check_div" style="display:none;">
                                          <div class="row">
                                                <div class="col-xs-12 col-sm-4 col-md-4 mt-3">
                                                    <div class="item form-group">
                                                        <label class="form-check-label mb-2" for="first_name"> Database Username :
                                                        </label>
                                                        <div class="feild">
                                                            {{ Form::text('db_username', @$cdata->db_username, [
                                                                'id' => 'db_username',
                                                                'class' => 'form-control rounded-pill col-md-7 col-xs-12',
                                                                'placeholder' => 'Enter username',
                                                            ]) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-4 col-md-4 mt-3">
                                                    <div class="item form-group">
                                                        <label class="form-check-label mb-2" for="first_name"> Database Password :
                                                        </label>
                                                        <div class="feild">
                                                            <input name="db_password" type="password" value="" class="form-control rounded-pill">
                                                        </div>
                                                    </div>
                                                </div>
                                          </div>
                                           <p class="text-success">*This username and password will taken for your database login Credientials.</p>
                                        <div>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="box-header with-border mar-bottom20">
                                <a href="#" class="btn btn-primary btn-lg m-1 px-3"
                                    onclick="GeneralConfig.movePreviousTab('#pills-home-tab')"><i
                                        class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>
                                <a href="#" class="btn btn-primary btn-lg m-1 px-3"
                                    onclick="GeneralConfig.moveNextTab('#pills-contact-tab')">Next&nbsp;&nbsp;&nbsp;<i
                                        class="fa fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    </div>

                        <!-- Payment preview tab content -->
                        <div class="tab-pane fade" id="pills-contact" role="tabpanel"
                            aria-labelledby="pills-contact-tab">
                            <ul>
                                <li>
                                    <h5>Customer Information</h5>
                                </li>
                                <div class="row mb-4">
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="item form-group">
                                            <label class="form-check-label mb-2" for="first_name"> School Name :
                                            </label><span class="form-check-label text-danger">*</span>
                                            <div class="feild">
                                                {{ Form::text('school_name', @$data->school_name, [
                                                    'id' => 'preview_school_name',
                                                    'class' => 'form-control rounded-pill col-md-7 col-xs-12',
                                                    'readonly' => 'readonly',
                                                ]) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="item form-group">
                                            <label class="form-check-label mb-2" for="first_name"> Official Email :
                                            </label>
                                            <div class="feild">
                                                {{ Form::text('school_email', @$data->email, [
                                                    'id' => 'preview_school_email',
                                                    'class' => 'form-control rounded-pill col-md-7 col-xs-12',
                                                    'readonly' => 'readonly',
                                                ]) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="item form-group">
                                            <label class="form-check-label mb-2" for="first_name"> Official Phone No :
                                            </label>
                                            <div class="feild">
                                                {{ Form::text('school_phoneno', @$data->phoneno, [
                                                    'id' => 'preview_school_phoneno',
                                                    'class' => 'form-control rounded-pill col-md-7 col-xs-12',
                                                    'readonly' => 'readonly',
                                                ]) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="item form-group">
                                            <label class="form-check-label mb-2" for="first_name"> Subscription Plan :
                                            </label>
                                            <div class="feild">
                                                {{ Form::select('preview_subscription_plan', @$planList, @$data->plan_id, [
                                                    'id' => 'preview_subscription_plan',
                                                    'class' => 'form-control rounded-pill col-md-7 col-xs-12',
                                                    'placeholder' => 'Select Plan',
                                                    'disabled'=>'disabled'
                                                ]) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="item form-group">
                                            <label class="form-check-label mb-2" for="first_name"> Billing Cycle :
                                            </label>
                                            <div class="feild">
                                                {{ Form::select('preview_billing_cycle', ['1' => 'Term', '2' => 'Session'], @$data->billing_id, [
                                                    'id' => 'preview_billing_cycle',
                                                    'class' => 'form-control rounded-pill col-md-7 col-xs-12',
                                                    'placeholder' => 'Select Cycle',
                                                   'disabled'=>'disabled',
                                                ]) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="item form-group">
                                            <label class="form-check-label mb-2" for="first_name"> Student Count :
                                            </label>
                                            <div class="feild">
                                                {{ Form::text('student_count', @$data->student_count, [
                                                    'id' => 'preview_student_count',
                                                    'class' => 'form-control rounded-pill col-md-7 col-xs-12',
                                                    'readonly' => 'readonly',
                                                ]) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <li>
                                    <h5>List of Features</h5>
                                </li>
                                <div class="card card-custom-ash">
                                    <div class="card-body">
                                        <div class="card-text">
                                            <!-- For Axios request -->
                                            @if ($layout == 'create')
                                                <div id="module-container" class="row">
                                                    <!-- Module list will be populated by Axios request -->
                                                </div>
                                                <!-- For Laravel request (edit option) -->
                                            @elseif($layout == 'edit')
                                                <div id="module-container" class="row">
                                                    @forelse($moduleList as $moduleId => $moduleName)
                                                        <div class="col-xs-10 col-md-4">
                                                            <div class="item form-group">
                                                                <div class="form-check">
                                                                    <input type="checkbox" name="moduleList[]"
                                                                        class="form-check-input p-2"
                                                                        id="module-{{ $moduleId }}"
                                                                        value="{{ $moduleId }}"
                                                                        @if (in_array($moduleId, $selectedModuleList)) checked @endif>
                                                                    <label class="form-check-label p-2"
                                                                        for="module-{{ $moduleId }}">
                                                                        {{ $moduleName }}
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @empty
                                                        <div class="col-xs-10 col-md-4">
                                                            <div class="item form-group">
                                                                <div class="form-check">
                                                                    <label class="form-check-label p-2"
                                                                        for="defaultCheck1">
                                                                        <span class="form-check-label text-danger">No
                                                                            Modules Found</span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforelse
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <li>
                                    <h5>Payment Information</h5>
                                </li>
                                <div class="card ">
                                    <div class="card-body">
                                        <div class="card-text">
                                            <div class="row">
                                                <div class="col">
                                                    <label class="control-label">Sub Total :</label>
                                                </div>
                                                <div class="col text-center">
                                                    <input type="hidden" id="sub_total_field" name="sub_total">
                                                    <span id="sub-total"></span>
                                                </div>
                                            </div>
                                            <hr />
                                            <div class="row">
                                                <div class="col">
                                                    <label class="control-label">Sales Tax :</label>
                                                </div>
                                                <div class="col text-center">
                                                    <span id="sales-tax"></span>
                                                </div>
                                            </div>
                                            <hr />
                                            <div class="row">
                                                <div class="col">
                                                    <label class="control-label">Training Fee :</label>
                                                </div>
                                                <div class="col text-center">
                                                    <span id="training-fee"></span>
                                                </div>
                                            </div>
                                            <hr />
                                            <div class="row">
                                                <div class="col">
                                                    <label class="control-label">Discount :</label>
                                                </div>
                                                <div class="col text-center">
                                                    <input type="hidden" id="pay_discount_field" name="pay_discount">
                                                    <span id="pay-discount"></span>
                                                </div>
                                            </div>
                                            <hr />
                                            <div class="row">
                                                <div class="col">
                                                    <label class="control-label">Total Due :</label>
                                                </div>
                                                <div class="col text-center">
                                                    <input type="hidden" id="total_due_field" name="total_due">
                                                    <span id="total-due"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </ul>

                            <div class="box-header with-border mar-bottom20">
                                <a href="#" class="btn btn-primary btn-lg m-1 px-3"
                                    onclick="GeneralConfig.movePreviousTab('#pills-profile-tab')"><i
                                        class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>
                                {{ Form::button('Create&nbsp;&nbsp;&nbsp;<i class="fa fa-plus-square "></i>', ['type' => 'submit', 'id' => 'next_btn', 'name' => 'Confirm', 'value' => 'Confirm', 'class' => 'btn btn-primary btn-lg m-1  px-3']) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>

    @endif

    </div>
    {{ Form::close() }}
@endsection

@section('script')

    <!-- validator -->
    {!! Cms::script('theme/vendors/validator/validator.js') !!}

    <script>
        $("#db_checked").on("click",function(){
            console.log("its enter");
            if($(this).is(":checked")){
                $(".db_check_div").show();
            }
            else{
                $(".db_check_div").hide();
                $("#db_username").val("");
                $("#db_password").val("");
            }
        });
        $(document).on('change', '#subscription_plan', function() {

            let plan_id = document.getElementById('subscription_plan').value;
            console.log("Plan ID: " + plan_id);
            var inputData = new FormData();
            inputData.append('plan_id', plan_id);

            axios.post('{{ route('schoolmanagement.filtermodulelist') }}', inputData)
                .then(response => {
                    let modulelist = response.data.moduleList;
                    let filtermoduleslist = response.data.filteredModuleList;

                    // Convert filteredModuleList to array if it's a string
                    if (typeof filtermoduleslist === 'string') {
                        filtermoduleslist = JSON.parse(filtermoduleslist);
                    }

                    let moduleHtml = '';

                    if (modulelist.length > 0) {
                        modulelist.forEach(module => {
                            // Convert module ID and filtermoduleslist items to strings for comparison
                            const moduleIdString = module.id.toString();
                            const isChecked = filtermoduleslist.includes(
                            moduleIdString); // Compare as strings

                            console.log(`Module ID: ${module.id}, Checked: ${isChecked}`);

                            moduleHtml += `
                        <div class="col-xs-10 col-md-4">
                            <div class="item form-group">
                                <div class="form-check">
                                    <input class="form-check-input p-2" type="checkbox" value="${module.id}" id="module_${module.id}" ${isChecked ? 'checked' : ''} ${!isChecked ? 'disabled' : ''}>
                                    <label class="form-check-label p-2" for="module_${module.id}">
                                         ${!isChecked ? '(X)' : ''} ${module.module_name}
                                    </label>
                                </div>
                            </div>
                        </div>
                    `;
                        });
                    } else {
                        // Show message if no modules are found
                        moduleHtml = `
                    <div class="col-xs-10 col-md-4">
                        <div class="item form-group">
                            <div class="form-check">
                                <label class="form-check-label p-2">
                                    <span class="form-check-label text-danger">No Modules Found</span>
                                </label>
                            </div>
                        </div>
                    </div>
                `;
                    }

                    // Insert the generated HTML into the module container
                    $('#module-container').html(moduleHtml);
                })
                .catch(error => {
                    console.error('Error:', error);
                    $('#module-container').html(
                        '<p class="text-danger">Failed to load modules. Please try again.</p>');
                });
        });

        $(document).ready(function(){
            let plan_id = document.getElementById('subscription_plan').value;
            let billing_id = document.getElementById('billing_cycle').value;
            let student_count = document.getElementById('student_count').value;
            let discount = document.getElementById('discount').value;
            if(plan_id && billing_id && student_count){
                discount = parseFloat(discount);
                //alert(discount);
                console.log("billing_cycle ID: " + discount);

                var inputData = new FormData();
                inputData.append('billing_id', billing_id);
                inputData.append('plan_id', plan_id);
                inputData.append('student_count', student_count);
                inputData.append('discount', discount);

                axios.post('{{ route('schoolmanagement.paymentcalculation') }}', inputData)
                    .then(response => {
                        let subTotalData = response.data.subTotal;
                        let totalDue = response.data.totalDue;

                        console.log("Total Due : " + totalDue);

                        // update the text visible
                        document.getElementById('sub-total').innerText = subTotalData;
                        document.getElementById('pay-discount').innerText = discount + "%";
                        document.getElementById('total-due').innerText = totalDue;

                        //assign these values to hidden fields
                        document.getElementById('sub_total_field').value = response.data.formatsubTotal;
                        document.getElementById('pay_discount_field').value = discount;
                        document.getElementById('total_due_field').value = response.data.formattotalDue;


                    })
                    .catch(error => {
                        console.error("Error fetching term amount:", error);
                    });
            }
            if(plan_id){
                console.log("Plan ID: " + plan_id);
                var inputData = new FormData();
                inputData.append('plan_id', plan_id);

                axios.post('{{ route('schoolmanagement.filtermodulelist') }}', inputData)
                    .then(response => {
                        let modulelist = response.data.moduleList;
                        let filtermoduleslist = response.data.filteredModuleList;

                        // Convert filteredModuleList to array if it's a string
                        if (typeof filtermoduleslist === 'string') {
                            filtermoduleslist = JSON.parse(filtermoduleslist);
                        }

                        let moduleHtml = '';

                        if (modulelist.length > 0) {
                            modulelist.forEach(module => {
                                // Convert module ID and filtermoduleslist items to strings for comparison
                                const moduleIdString = module.id.toString();
                                const isChecked = filtermoduleslist.includes(
                                moduleIdString); // Compare as strings

                                console.log(`Module ID: ${module.id}, Checked: ${isChecked}`);

                                moduleHtml += `
                            <div class="col-xs-10 col-md-4">
                                <div class="item form-group">
                                    <div class="form-check">
                                        <input class="form-check-input p-2" type="checkbox" value="${module.id}" id="module_${module.id}" ${isChecked ? 'checked' : ''} ${!isChecked ? 'disabled' : ''}>
                                        <label class="form-check-label p-2" for="module_${module.id}">
                                            ${!isChecked ? '(X)' : ''} ${module.module_name}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        `;
                            });
                        } else {
                            // Show message if no modules are found
                            moduleHtml = `
                        <div class="col-xs-10 col-md-4">
                            <div class="item form-group">
                                <div class="form-check">
                                    <label class="form-check-label p-2">
                                        <span class="form-check-label text-danger">No Modules Found</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    `;
                        }

                        // Insert the generated HTML into the module container
                        $('#module-container').html(moduleHtml);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        $('#module-container').html(
                            '<p class="text-danger">Failed to load modules. Please try again.</p>');
                    });
            }
           
        });


        $(document).on('change', '#billing_cycle', function() {

            let plan_id = document.getElementById('subscription_plan').value;
            let billing_id = document.getElementById('billing_cycle').value;
            let student_count = document.getElementById('student_count').value;
            let discount = document.getElementById('discount').value;
            discount = parseFloat(discount);
            //alert(discount);
            console.log("billing_cycle ID: " + discount);

            var inputData = new FormData();
            inputData.append('billing_id', billing_id);
            inputData.append('plan_id', plan_id);
            inputData.append('student_count', student_count);
            inputData.append('discount', discount);

            axios.post('{{ route('schoolmanagement.paymentcalculation') }}', inputData)
                .then(response => {
                    let subTotalData = response.data.subTotal;
                    let totalDue = response.data.totalDue;

                    console.log("Total Due : " + totalDue);

                    // update the text visible
                    document.getElementById('sub-total').innerText = subTotalData;
                    document.getElementById('pay-discount').innerText = discount + "%";
                    document.getElementById('total-due').innerText = totalDue;

                    //assign these values to hidden fields
                    document.getElementById('sub_total_field').value = response.data.formatsubTotal;
                    document.getElementById('pay_discount_field').value = discount;
                    document.getElementById('total_due_field').value = response.data.formattotalDue;


                })
                .catch(error => {
                    console.error("Error fetching term amount:", error);
                });
        });
    </script>

@endsection
