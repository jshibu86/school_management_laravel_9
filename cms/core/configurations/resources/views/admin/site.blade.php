@extends('layout::admin.master')

@section('title', 'site configuration')
@section('style')
    {!! Cms::style('theme/vendors-old/switchery/dist/switchery.min.css') !!}
    {!! Cms::style('theme/vendors-old/select2/select2.css') !!}
    <style>
        /* .item .alert{
                                                                        display: none
                                                                    } */
        /* .item.bad .alert {
                                                                    left: 30% !important;
                                                                    bottom: -27px !important;
                                                                    right: 0 !important;
                                                                    
                                                                    }        */
        .item .alert {
            position: absolute !important;
        }
    </style>
@endsection
@section('body')
    <div id="site-configurations">
        {{ Form::open(['role' => 'form', 'route' => ['admin_site_configuration_save'], 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'module-form', 'novalidate' => 'novalidate']) }}
        <div class="box-header with-border mar-bottom20">
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', ['type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_btn', 'value' => 'save', 'class' => 'btn btn-success btn-sm m-1  px-3']) }}

            {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset', 'class' => 'btn btn-danger btn-sm m-1  px-3']) }}

            @include('layout::admin.breadcrump', ['route' => 'Site Informations'])
        </div>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Site Information</h5>
                <hr />
                <div class="col-xs-12">
                
                    {{--  --}}
                    <div class="accordion accordion-flush" id="accordionFlushExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingOne">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseOne" aria-expanded="false"
                                    aria-controls="flush-collapseOne">
                                    General Settings
                                </button>
                            </h2>
                           
                            <div id="flush-collapseOne" class="accordion-collapse collapse"
                                aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="school_name">School Name
                                                    <span class="required">*</span>
                                                </label>
                                                <div class="feild">
                                                    {{ Form::text('school_name', @$data->school_name, [
                                                        'id' => 'school_name',
                                                        'class' => 'form-control col-md-7 col-xs-12',
                                                        'placeholder' => 'Enter School Name ',
                                                        'required' => 'required',
                                                    ]) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="school_email">School Email
                                                    <span class="required">*</span>
                                                </label>
                                                <div class="">
                                                    {{ Form::email('school_email', @$data->school_email, [
                                                        'id' => 'school_email',
                                                        'class' => 'form-control col-md-7 col-xs-12',
                                                        'placeholder' => 'Enter School Email ',
                                                        'required' => 'required',
                                                    ]) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="school_phone">School
                                                    Primary Phone <span class="required">*</span>
                                                </label>
                                                <div class="">
                                                    {{ Form::text('school_phone', @$data->school_phone, [
                                                        'id' => 'school_phone',
                                                        'class' => 'form-control col-md-7 col-xs-12',
                                                        'placeholder' => 'Enter School PhoneNumber ',
                                                        'required' => 'required',
                                                    ]) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="school_landline">School
                                                    Landline Number <span class="required">(if available)</span>
                                                </label>
                                                <div class="">
                                                    {{ Form::text('school_landline', @$data->school_landline, [
                                                        'id' => 'school_landline',
                                                        'class' => 'form-control col-md-7 col-xs-12',
                                                        'placeholder' => 'Enter School LandlineNumber ',
                                                    ]) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="place">Place <span
                                                        class="required">*</span>
                                                </label>
                                                <div class="">
                                                    {{ Form::text('place', @$data->place, [
                                                        'id' => 'place',
                                                        'class' => 'form-control col-md-7 col-xs-12',
                                                        'placeholder' => 'Enter Place ',
                                                        'required' => 'required',
                                                    ]) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="city">City <span
                                                        class="required">*</span>
                                                </label>
                                                <div class="">
                                                    {{ Form::text('city', @$data->city, [
                                                        'id' => 'city',
                                                        'class' => 'form-control col-md-7 col-xs-12',
                                                        'placeholder' => 'Enter School City ',
                                                        'required' => 'required',
                                                    ]) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="post">Post <span
                                                        class="required">*</span>
                                                </label>
                                                <div class="">
                                                    {{ Form::text('post', @$data->post, [
                                                        'id' => 'post',
                                                        'class' => 'form-control col-md-7 col-xs-12',
                                                        'placeholder' => 'Enter Post ',
                                                        'required' => 'required',
                                                    ]) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3 margin__bottom">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="pin_code">Pincode <span
                                                        class="required">*</span>
                                                </label>
                                                <div class="">
                                                    {{ Form::text('pin_code', @$data->pin_code, [
                                                        'id' => 'pin_code',
                                                        'class' => 'form-control col-md-7 col-xs-12',
                                                        'placeholder' => 'Enter Pincode ',
                                                        'required' => 'required',
                                                    ]) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="country">Country <span
                                                        class="required">*</span>
                                                </label>
                                                <div class="">
                                                    {{ Form::text('country', @$data->country, [
                                                        'id' => 'country',
                                                        'class' => 'form-control col-md-7 col-xs-12',
                                                        'placeholder' => 'Enter Country ',
                                                        'required' => 'required',
                                                    ]) }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label for="thumbnail" class="control-label margin__bottom">School
                                                    Logo</label>
                                                <div class="">
                                                    <span class="input-group-btn">
                                                        <input class="form-control thumb" type="file"
                                                            id="imagec_img_imagec" name="imagec" data-id="imagec"
                                                            accept="image/*">

                                                    </span>
                                                    <img id="imagecholder" style="max-height:50px;"
                                                        src="{{ @$data->imagec }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="name">Select
                                                    TimeZone<span class="required">*</span>
                                                </label>
                                                <div>
                                                    {{ Form::select('time_zone', Configurations::TIMEZONES, @$data->time_zone, ['class' => 'form-control', 'placeholder' => 'Select TimeZones', 'required']) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3 margin__bottom">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="school_name"> Weekend
                                                    <span class="required">*</span>
                                                </label>
                                                <div class="feild designation_select_feild">


                                                    {{ Form::select('week_end[]', Configurations::WEEKENDS, @$data->week_end, [
                                                        'id' => 'wweekend',
                                                        'class' => 'form-control multiple-select',
                                                        'required' => 'required',
                                                        'multiple' => true,
                                                    ]) }}

                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label for="thumbnail" class="control-label margin__bottom">School
                                                    Icon</label>
                                                <div class="">
                                                    <span class="input-group-btn">
                                                        <input class="form-control thumb" type="file"
                                                            id="imagec_img_schoolicon" name="schoolicon"
                                                            data-id="schoolicon" accept="image/*">

                                                    </span>
                                                    <input type="hidden" name="old_schoolicon"
                                                        value="{{ @$data->schoolicon }}" />
                                                    <input type="hidden" name="old_imagec"
                                                        value="{{ @$data->imagec }}" />
                                                    <img id="schooliconholder" style="max-height:50px;"
                                                        src="{{ @$data->schoolicon }}">

                                                    {{-- <span class="back_to remove" id="remove_img_schoolicon" data-id="schoolicon" data-class="schoolicon">X</span> --}}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="country">Currency Symbol
                                                    <span class="required">*</span>
                                                </label>
                                                <div class="">
                                                    {{ Form::text('currency_symbol', @$data->currency_symbol ? @$data->currency_symbol : '₦', [
                                                        'id' => 'currency_symbol',
                                                        'class' => 'form-control col-md-7 col-xs-12',
                                                        'placeholder' => 'Enter Currency Symbol ',
                                                        'required' => 'required',
                                                    ]) }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="country">Currency Name
                                                    <span class="required">*</span>
                                                </label>
                                                <div class="">
                                                    {{ Form::text('currency_name', @$data->currency_name ? @$data->currency_name : 'Nigerian Naira', [
                                                        'id' => 'currency_name',
                                                        'class' => 'form-control col-md-7 col-xs-12',
                                                        'placeholder' => 'Enter Currency Name ',
                                                        'required' => 'required',
                                                    ]) }}
                                                </div>
                                            </div>
                                        </div>



                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseTwo" aria-expanded="false"
                                    aria-controls="flush-collapseTwo">
                                    Institute Details
                                </button>
                            </h2>
                            <div id="flush-collapseTwo" class="accordion-collapse collapse"
                                aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">
                                    <div class="col-xs-12">

                                        <div class="row">
                                            <div class="col-xs-12 col-sm-4 col-md-3">
                                                <div class="item form-group">
                                                    <label class="control-label margin__bottom" for="name">Select
                                                        Academic Year<span class="required">*</span>
                                                    </label>
                                                    <div>
                                                        {{ Form::select('academic_year', @$academic_years, @$data->academic_year, ['class' => 'form-control termacademicyear', 'placeholder' => 'Select Academic Year']) }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-4 col-md-3">
                                                <div class="item form-group">
                                                    <label class="control-label margin__bottom" for="name">Select
                                                        Academic Term<span class="required">*</span>
                                                    </label>
                                                    <div>
                                                        {{ Form::select('academic_term', @$academic_terms, @$data->academic_term, ['class' => 'form-control', 'placeholder' => 'Select Academic Term']) }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-4 col-md-3">
                                                <div class="item form-group">
                                                    <label class="control-label margin__bottom" for="name">Select
                                                        Attendance Type<span class="required">*</span>
                                                    </label>
                                                    <div>
                                                        {{ Form::select('attendance_type', Configurations::ATTENDANCETYPES, @$data->attendance_type, ['class' => 'form-control', 'placeholder' => 'Select Attendance Type']) }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-4 col-md-3">
                                                <div class="item form-group">
                                                    <label class="control-label margin__bottom" for="school_type">School
                                                        Type <span class="required">(primary/highschool)</span>
                                                    </label>
                                                    <div class="">
                                                        {{ Form::text('school_type', @$data->country, [
                                                            'id' => 'school_type',
                                                            'class' => 'form-control col-md-7 col-xs-12',
                                                            'placeholder' => 'Enter school_type ',
                                                            'required' => 'required',
                                                        ]) }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-4 col-md-3">
                                                <div class="item form-group">
                                                    <label class="control-label margin__bottom" for="school_start">School
                                                        Start Time <span class="required">*</span>
                                                    </label>
                                                    <div class="">
                                                        {{ Form::text('school_start', @$data->school_start, [
                                                            'id' => 'timepicker_daystart',
                                                            'class' => 'form-control col-md-7 col-xs-12',
                                                            'placeholder' => 'Enter school_start ',
                                                            'required' => 'required',
                                                        ]) }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-4 col-md-3">
                                                <div class="item form-group">
                                                    <label class="control-label margin__bottom" for="school_end">School
                                                        End Time <span class="required">*</span>
                                                    </label>
                                                    <div class="">
                                                        {{ Form::text('school_end', @$data->school_end, [
                                                            'id' => 'timepicker_dayend',
                                                            'class' => 'form-control col-md-7 col-xs-12 ',
                                                            'placeholder' => 'Enter school_end ',
                                                            'required' => 'required',
                                                        ]) }}
                                                    </div>
                                                </div>
                                            </div>




                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseThree" aria-expanded="false"
                                    aria-controls="flush-collapseThree">
                                    Timetable Configuration
                                </button>
                            </h2>
                            <div id="flush-collapseThree" class="accordion-collapse collapse"
                                aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">
                                    <div class="col-xs-12 ">

                                        <div class="row">


                                            <div class="col-xs-12 col-sm-4 col-md-3">
                                                <div class="item form-group">
                                                    <label class="control-label margin__bottom" for="school_start">Select
                                                        Break BackgroundColor <span class="required">*</span>
                                                    </label>
                                                    <div class="">
                                                        {{ Form::text('break_color', @$data->break_color, [
                                                            'id' => 'break_colorpicker',
                                                            'class' => 'form-control col-md-7 col-xs-12',
                                                            'placeholder' => 'Select break color ',
                                                            'required' => 'required',
                                                            'readonly',
                                                        ]) }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-4 col-md-3">
                                                <div class="item form-group">
                                                    <label class="control-label margin__bottom" for="school_start">Select
                                                        Break Textcolor <span class="required">*</span>
                                                    </label>
                                                    <div class="">
                                                        {{ Form::text('text_color', @$data->text_color, [
                                                            'id' => 'text_colorpicker',
                                                            'class' => 'form-control col-md-7 col-xs-12',
                                                            'placeholder' => 'Select break text color ',
                                                            'required' => 'required',
                                                            'readonly',
                                                        ]) }}
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingLibrary">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseLibrary" aria-expanded="false"
                                    aria-controls="flush-collapseLibrary">
                                    Library Configuration
                                </button>
                            </h2>
                            <div id="flush-collapseLibrary" class="accordion-collapse collapse"
                                aria-labelledby="flush-headingLibrary" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">
                                    <div class="col-xs-12 ">

                                        <div class="row">


                                            <div class="col-xs-12 col-sm-4 col-md-3">
                                                <div class="item form-group">
                                                    <label class="control-label margin__bottom"
                                                        for="library_subscription">Library Subscription Fee <span
                                                            class="required"></span>
                                                    </label>
                                                    <div class="">
                                                        {{ Form::text('library_subscription', @$data->library_subscription ?? 0, [
                                                            'id' => 'sub',
                                                            'class' => 'form-control col-md-7 col-xs-12 ',
                                                            'placeholder' => 'Enter library subscription ',
                                                        ]) }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-sm-4 col-md-3">
                                                <div class="item form-group">
                                                    <label class="control-label margin__bottom"
                                                        for="library_subscription">Library Subscription Compulsary <span
                                                            class="required"></span>
                                                    </label>
                                                    <div>
                                                        <label class="switch">
                                                            <input type="checkbox" id="library_required"
                                                                class="toggle-class"
                                                                {{ @$data->library_compulsary ? 'checked' : '' }}
                                                                name="library_compulsary">
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>

                                                </div>
                                            </div>


                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>




                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingFour">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseFour" aria-expanded="false"
                                    aria-controls="flush-collapseFour">
                                    Exam Mark Distribution
                                </button>
                            </h2>
                            <div id="flush-collapseFour" class="accordion-collapse collapse"
                                aria-labelledby="flush-headingFour" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">
                                    <div class="col-xs-12 ">



                                        <div class="row">


                                            <div class="col-xs-12 col-sm-4 col-md-3">
                                                <div class="item form-group">
                                                    <label class="control-label margin__bottom" for="school_start">Select
                                                        Default Grade System<span class="required">*</span>
                                                    </label>

                                                    <div class="">
                                                        {{ Form::select('grade_system', @$grade, @$markdata->grade_system, ['class' => 'form-control', 'placeholder' => 'Select Grade Type']) }}
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-sm-4 col-md-3">
                                                <label class="control-label margin__bottom" for="school_start">Promotion
                                                    %<span class="required">*</span>
                                                </label>

                                                <input type="number" value="{{ @$data->promotion_percentage }}"
                                                    name="promotion_percentage" class="form-control"
                                                    placeholder="Enter The Promotion %" />

                                            </div>

                                            <div class="col-xs-12 col-sm-4 col-md-3">

                                                <label class="control-label margin__bottom" for="school_start">Select
                                                    Promotion % Type<span class="required">*</span>
                                                </label>
                                                <div class="">
                                                    {{ Form::select('promotion_type', [0 => 'Cumulative', 1 => 'Third Term'], @$data->promotion_type, ['class' => 'form-control', 'placeholder' => 'Select Promotion Type']) }}
                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-sm-4 col-md-8 margin__bottom mt-2">

                                                <div class="item form-group">
                                                    <label class="control-label margin__bottom" for="school_start">Select
                                                        Mark Distribution (100 Mark)<span class="required">*</span>
                                                    </label>

                                                    <div class="row">
                                                        @forelse (@$distribution_types as $type)
                                                            <div class="col-md-3">
                                                                <div class="item form-group">
                                                                    @if(isset($markdata))
                                                                        <input type="checkbox" name="distributiontype[]"
                                                                            value="{{ $type->id }} "
                                                                            {{ in_array($type->id, $markdata->mark_distribution) ? 'checked' : '' }} />
                                                                    @else
                                                                        <input type="checkbox" name="distributiontype[]"
                                                                            value="{{ $type->id }} "
                                                                            />
                                                                    @endif
                                                                    <label class="control-label margin__bottom"
                                                                        for="school_start">{{ $type->distribution_name }}
                                                                        ({{ $type->mark }})
                                                                    </label>

                                                                </div>
                                                            </div>

                                                        @empty

                                                            <p>No Distribution Types Available</p>
                                                        @endforelse
                                                    </div>

                                                </div>


                                            </div>

                                            <div class="row">

                                                <div class="col-md-6">

                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom"
                                                            for="school_start">Mark Report Message<span
                                                                class="required">*</span>
                                                        </label>

                                                        <span class="input-group-btn">
                                                            @include('layout::widget.ckeditor', [
                                                                'name' => 'mark_report_message',
                                                                'id' => 'mark_report_message',
                                                                'content' => @$data->mark_report_message,
                                                            ])
                                                        </span>



                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="col-md-6">

                                                            <div class="col-md-6">

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="accordion-item border-top">
                                <h2 class="accordion-header" id="flush-headingFive">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#flush-collapseFive" aria-expanded="false"
                                        aria-controls="flush-collapseFive">
                                        Fee Structure Setup
                                    </button>
                                </h2>
                                <div id="flush-collapseFive" class="accordion-collapse collapse"
                                    aria-labelledby="flush-headingFive" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">
                                        <div class="col-xs-12">

                                            <div class="row">
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom"
                                                            for="school_start">Select Default Fee Structure<span
                                                                class="required">*</span>
                                                        </label>

                                                        <div class="">
                                                            {{ Form::select('payment_type', Configurations::FEEPAYMENTTYPES, @$feedata->payment_type, ['class' => 'form-control', 'placeholder' => 'Select Fee Structure']) }}
                                                        </div>

                                                    </div>
                                                </div>
                                                <p class="text-danger payment_type_status"></p>
                                            </div>

                                            <div class="row mt-4">
                                                @if (@$feedata->payment_type == 1)
                                                    <div class="terms__due">
                                                    @else
                                                        <div class="terms__due" style="display:none">
                                                @endif



                                                <label class="control-label margin__bottom term_due_label"
                                                    for="status">Please Provide
                                                    Terms Due Dates<span class="required">*</span>
                                                </label>

                                                <div class="terms__lists row">
                                                    @if (sizeof(@$terms_due))
                                                        @php
                                                            $length = sizeof(@$terms_due);
                                                        @endphp
                                                        @foreach (@$terms_due as $key => $due)
                                                            <div class="col-xs-12 col-sm-4 col-md-4">
                                                                <div class="item form-group input-group mb-3"> <span
                                                                        class="input-group-text">{{ $due['termname'] }}</span>
                                                                    <span class="input-group-text">
                                                                        {{ $key === $length - 1 ? '40%' : '30%' }}

                                                                    </span>
                                                                    <input type="hidden"
                                                                        name="due_term_dates[{{ $due['id'] }}][per]"
                                                                        value=" {{ $due['per'] }}" />
                                                                    <input
                                                                        name="due_term_dates[{{ $due['id'] }}][date]"
                                                                        type="date" required class="form-control"
                                                                        value="{{ $due['date'] }}"
                                                                        aria-label="Dollar amount (with dot and two decimal places)">
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <div class="row term_div_append"></div>
                                                        @foreach ($academic_terms_data as $terms)
                                                            <div class="col-xs-12 col-sm-4 col-md-4 term_div">
                                                                <div class="item form-group input-group mb-3"> <span
                                                                        class="input-group-text">{{ $terms->exam_term_name }}</span>
                                                                    <input type="hidden"
                                                                        name="due_term_dates[{{ $terms->id }}][id]"
                                                                        value = "{{ $terms->id }}">
                                                                    <input type="number"
                                                                        name="due_term_dates[{{ $terms->id }}][per]"
                                                                        required class="form-control" />
                                                                    <input
                                                                        name="due_term_dates[{{ $terms->id }}][date]"
                                                                        type="text" readonly class="form-control"
                                                                        value="{{ $terms->to_date }}"
                                                                        aria-label="Dollar amount (with dot and two decimal places)">
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif

                                                </div>

                                            </div>
                                            <div class="item form-group mb-3">
                                                <label for="fees_reminder_text" class="form-label">Fees reminder Message
                                                    <span>*</span></label><br>
                                                @php
                                                    if (
                                                        is_object($data) &&
                                                        property_exists($data, 'fees_reminder_text') &&
                                                        $data->fees_reminder_text
                                                    ) {
                                                        $reminder_text = $data->fees_reminder_text;
                                                    } else {
                                                        $reminder_text =
                                                            'We want you to know that when you click the confirm button, an instant reminder message will be sent to all parents and students who have not paid the school fee for this time via email and push notification mobile.';
                                                    }
                                                @endphp
                                                @include('layout::widget.ckeditor', [
                                                    'name' => 'fees_reminder_text',
                                                    'id' => 'fees_reminder_text',
                                                    'class' => 'w-50',
                                                    'content' => @$reminder_text
                                                        ? @$reminder_text
                                                        : old('homework_description'),
                                                ])
                                            </div>
                                            {{-- <div class="month_due" style="display:none">
                                    <div class="col-xs-12 col-sm-4 col-md-6">
                                         <label class="control-label margin__bottom" for="status">Please Provide Due Date of Every Month<span class="required">*</span>
                                        </label>
                                        <div class="item form-group input-group mb-3"> <span class="input-group-text">Monthly</span>
                                               
                                                <input type="date" class="form-control" aria-label="Dollar amount (with dot and two decimal places)">
                                        </div>
                                    </div>
                                </div> --}}



                                            <div class="full_due" style="display:none">
                                                <div class="col-xs-12 col-sm-4 col-md-6">
                                                    <label class="control-label margin__bottom" for="status">Please
                                                        Provide Due Date<span class="required">*</span>
                                                    </label>
                                                    <div class="item form-group input-group mb-3"> <span
                                                            class="input-group-text">Full Payment</span>
                                                        <span class="input-group-text">100%</span>
                                                        <input type="date" name="full_pay_due" class="form-control"
                                                            aria-label="Dollar amount (with dot and two decimal places)">
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingpayroll">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#flush-collapsepayroll" aria-expanded="false"
                                aria-controls="flush-collapsepayroll">
                                Payroll Deduction
                            </button>
                        </h2>
                        <div id="flush-collapsepayroll" class="accordion-collapse collapse"
                            aria-labelledby="flush-headingpayroll" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">
                                <div class="col-xs-12">

                                    <div class="row">



                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="school_type">Tax <span
                                                        class="required">(%)</span>
                                                </label>
                                                <div class="">
                                                    {{ Form::text('payroll_tax', @$data->payroll_tax, [
                                                        'id' => 'payroll_tax',
                                                        'class' => 'form-control col-md-7 col-xs-12',
                                                        'placeholder' => 'Enter payroll tax ',
                                                    ]) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="school_start">Employer
                                                    Pension<span class="required">(%)</span>
                                                </label>
                                                <div class="">
                                                    {{ Form::text('employer_pension', @$data->employer_pension, [
                                                        'id' => '',
                                                        'class' => 'form-control col-md-7 col-xs-12',
                                                        'placeholder' => 'Enter Employer Pension ',
                                                    ]) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="school_end">Employee
                                                    Pension <span class="required">(%)</span>
                                                </label>
                                                <div class="">
                                                    {{ Form::text('employee_pension', @$data->employee_pension, [
                                                        'id' => '',
                                                        'class' => 'form-control col-md-7 col-xs-12 ',
                                                        'placeholder' => 'Enter Employee Pension ',
                                                    ]) }}
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-4 col-md-3 mt-3">
                                            <div class="item form-group">

                                                <label class="switch">
                                                    <input type="checkbox" id="tax" class="toggle-class"
                                                        {{ @$data->tax_enable ? 'checked' : '' }} name="tax_enable">
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3 mt-3">
                                            <div class="item form-group">
                                                <label class="switch">
                                                    <input type="checkbox" id="employer_pension"
                                                        {{ @$data->employer_pension_enable ? 'checked' : '' }}
                                                        class="toggle-class" name="employer_pension_enable">
                                                    <span class="slider round"></span>
                                                </label>

                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3 mt-3">
                                            <div class="item form-group">

                                                <label class="switch">
                                                    <input type="checkbox" id="employee_pension"
                                                        {{ @$data->employee_pension_enable ? 'checked' : '' }}
                                                        class="toggle-class" name="employee_pension_enable">
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>


                    {{-- 
                     --}}
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingIdcard">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#flush-collapseIdcard" aria-expanded="false"
                                aria-controls="flush-collapseThree">
                                Student ID Card Configuration
                            </button>
                        </h2>
                        <div id="flush-collapseIdcard" class="accordion-collapse collapse"
                            aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">
                                <div class="col-xs-12 ">

                                    <div class="row">


                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="school_start">Header
                                                    Color <span class="required">*</span>
                                                </label>
                                                <div class="">
                                                    {{ Form::text('id_card_header', @$data->id_card_header, [
                                                        'id' => 'id_card_headerpicker',
                                                        'class' => 'form-control col-md-7 col-xs-12',
                                                        'placeholder' => 'Select Id card color ',
                                                        'required' => 'required',
                                                        'readonly',
                                                    ]) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="school_start">Select ID
                                                    Card Feilds<span class="required">*</span>
                                                </label>

                                                <div class="">
                                                    {{ Form::select('id_card_feilds[]', Configurations::IDCARDFEILDS, @$data->id_card_feilds, ['class' => 'form-control', 'multiple']) }}
                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="school_start">ID Card
                                                    Templates<span class="required">*</span>
                                                </label>

                                                <div class="">
                                                    {{ Form::select('id_card_templates[]', Configurations::IDCARDTEMPLATES, @$data->id_card_templates ? @$data->id_card_templates : [1], ['class' => 'form-control', 'multiple']) }}
                                                </div>

                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    {{--  --}}

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingfeereceiptbill">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#flush-collapsefeebill" aria-expanded="false"
                                aria-controls="flush-collapsefeebill">
                                Receipt Configuration
                            </button>
                        </h2>
                        <div id="flush-collapsefeebill" class="accordion-collapse collapse"
                            aria-labelledby="flush-headingpfeebill" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">

                                <div class="row">

                                    <div class="col-xs-12 col-sm-4 col-md-3">
                                        <div class="item form-group">
                                            <label class="control-label margin__bottom" for="school_start">Fee Receipt
                                                Color <span class="required">*</span>
                                            </label>
                                            <div class="">
                                                {{ Form::text('receipt_color', @$data->receipt_color, [
                                                    'id' => 'fee_receipt_headerpicker',
                                                    'class' => 'form-control col-md-7 col-xs-12',
                                                    'placeholder' => 'Select receipt header color ',
                                                    'required' => 'required',
                                                    'readonly',
                                                ]) }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-4 col-md-3">
                                        <div class="item form-group">
                                            <label class="control-label margin__bottom" for="school_start">Salary Receipt
                                                Color <span class="required">*</span>
                                            </label>
                                            <div class="">
                                                {{ Form::text('salary_receipt_color', @$data->salary_receipt_color, [
                                                    'id' => 'salary_receipt_headerpicker',
                                                    'class' => 'form-control col-md-7 col-xs-12',
                                                    'placeholder' => 'Select receipt header color ',
                                                    'required' => 'required',
                                                    'readonly',
                                                ]) }}
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingstudperformance">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#flush-collapsestudperformance" aria-expanded="false"
                                aria-controls="flush-collapsestudperformance">
                                Student Performances
                            </button>
                        </h2>
                        <div id="flush-collapsestudperformance" class="accordion-collapse collapse"
                            aria-labelledby="flush-headingstudperformance" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">
                                <div class="row">

                                    <div class="col-xs-12 col-sm-4 col-md-3 margin__bottom">
                                        <div class="item form-group  gap-4">
                                            <label class="control-label margin__bottom" for="school_name"> Student
                                                Performances Report
                                                <span class="required">*</span>
                                            </label>
                                            <div class="feild designation_select_feild">


                                                {{ Form::select(
                                                    'student_performances_report',
                                                    Configurations::STUDENTPERFORMANCESREPORT,
                                                    @$data->student_performances_report,
                                                    [
                                                        'id' => 'student_performances_report',
                                                        'class' => 'form-control single-select',
                                                        'required' => 'required',
                                                        'placeholder' => 'select type',
                                                    ],
                                                ) }}

                                            </div>


                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 col-md-3 margin__bottom auto_date_col">
                                        <div class="item form-group  gap-4">
                                            <label class="control-label margin__bottom" for="school_name"> Date
                                                <span class="required">*</span>
                                            </label>
                                            <div class="feild ">
                                                {{ Form::date('auto_date', @$data->auto_date, [
                                                    'id' => 'auto_date',
                                                    'class' => ' form-control datepicauto_dateker_academic_start',
                                                    'placeholder' => 'select date',
                                                ]) }}
                                            </div>


                                        </div>
                                    </div>


                                </div>
                                <div class="row stud_perform_row">
                                    <div class="col-xs-12 col-sm-4 col-md-3 mt-3">
                                        <div class="item form-group">
                                            <label>Discipline and Compliance</label>
                                            <label class="switch">
                                                <input type="checkbox" id="tax" class="toggle-class discipline_and_compliance"
                                                    {{ @$data->discipline_and_compliance ? 'checked' : '' }}
                                                    name="discipline_and_compliance">
                                                <span class="slider round "></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 col-md-3 mt-3">
                                        <div class="item form-group">
                                            <label>Sports and Event</label>
                                            <label class="switch">
                                                <input type="checkbox" id="sports_and_event"
                                                    {{ @$data->sports_and_event ? 'checked' : '' }} class="toggle-class sports_and_event"
                                                    name="sports_and_event">
                                                <span class="slider round"></span>
                                            </label>

                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-4 col-md-3 mt-3">
                                        <div class="item form-group">
                                            @php
                                                if (isset($data->poor_recomendation_text)) {
                                                    $text1 = $data->poor_recomendation_text;
                                                } else {
                                                    $text1 = 'please you need to practice good displine';
                                                }
                                            @endphp
                                            <label>Recomendation Text For Poor<span>*</span></label>
                                            <textarea name="poor_recomendation_text" id="poor_recomendation_text"
                                                cols="30" rows="3" class="form-control">{{ $text1 }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 col-md-3 mt-3">
                                        <div class="item form-group">
                                            @php
                                                if (isset($data->avarage_recomendation_text)) {
                                                    $text1 = $data->avarage_recomendation_text;
                                                } else {
                                                    $text1 = 'please you need to practice good displine';
                                                }
                                            @endphp
                                            <label>Recomendation Text For Avarage<span>*</span></label>
                                            <textarea name="avarage_recomendation_text" id="avarage_recomendation_text"
                                                cols="30" rows="3" class="form-control">{{ $text1 }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 col-md-3 mt-3">
                                        @php
                                            if (isset($data->good_recomendation_text)) {
                                                $text2 = $data->good_recomendation_text;
                                            } else {
                                                $text2 = 'please you need to particpate in more sports or events';
                                            }
                                        @endphp
                                        <div class="item form-group">
                                            <label>Recomendation Text For Good<span>*</span></label>
                                            <textarea name="good_recomendation_text" id="good_recomendation_text" cols="30"
                                                rows="3" class="form-control">{{ $text2 }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 col-md-3 mt-3">
                                        @php
                                            if (isset($data->execellent_recomendation_text)) {
                                                $text3 = $data->execellent_recomendation_text;
                                            } else {
                                                $text3 = 'please come to school regular';
                                            }
                                        @endphp
                                        <div class="item form-group">
                                            <label>Recomendation Text For Excellent <span>*</span></label>
                                            <textarea name="execellent_recomendation_text" id="execellent_recomendation_text" cols="30" rows="3"
                                                class="form-control">{{ $text3 }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                  

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headinggmailrole">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#flush-collapsegmailrole" aria-expanded="false"
                                aria-controls="flush-collapseThree">
                                Gmail Role Configuration
                            </button>
                        </h2>
                        <div id="flush-collapsegmailrole" class="accordion-collapse collapse"
                            aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">
                                <div class="col-xs-12 role_receptiants">
                                    @if (@$data->gmail_role_configurations)
                                        @foreach (@$data->gmail_role_configurations as $data)
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-4 col-md-3 mt-3">
                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom"
                                                            for="school_start">Role <span class="required">*</span>
                                                        </label>

                                                        <div class="">
                                                            {{ Form::select('role_id[]', @$roles, @$data->role_id, ['class' => 'role_id form-control single-select', 'placeholder' => 'Select Role']) }}
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="col-xs-12 col-sm-4 col-md-3 mt-3">
                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom"
                                                            for="school_start">Receptients <span class="required">*</span>
                                                        </label>

                                                        <div class="">
                                                            {{ Form::select('receptiants[]', @$receptiants, @$data->receptiants, ['class' => 'form-control receptiants multiple-select', 'multiple' => 'multiple', 'placeholder' => 'Select Receptiants']) }}
                                                        </div>

                                                    </div>
                                                </div>

                                            </div>
                                        @endforeach
                                    @else
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-4 col-md-3">
                                                <div class="item form-group">
                                                    <label class="control-label margin__bottom" for="school_start">Role
                                                        <span class="required">*</span>
                                                    </label>

                                                    <div class="">
                                                        {{ Form::select('role_id[]', @$roles, @$data->role_id, ['class' => 'role_id form-control single-select', 'placeholder' => 'Select Role']) }}
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-sm-4 col-md-3">
                                                <div class="item form-group">
                                                    <label class="control-label margin__bottom"
                                                        for="school_start">Receptients <span class="required">*</span>
                                                    </label>

                                                    <div class="">
                                                        {{ Form::select('receptiants[]', @$receptiants, @$data->receptiants, ['class' => 'form-control receptiants multiple-select', 'multiple' => 'multiple', 'placeholder' => 'Select Receptiants']) }}
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                    @endif
                                </div>
                                <button type="button" class="btn btn-primary role_add mt-2">Add</button>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-4 col-md-3 mt-5">
                                        <div class="item form-group">
                                            <label class="control-label margin__bottom" for="school_start">Eligible Roles
                                                For Create Group<span class="required">*</span>
                                            </label>

                                            <div class="">
                                                {{ Form::select('eligible_role_types[]', @$receptiants, @$eligible_role_types, ['class' => 'form-control eligible_role_types multiple-select', 'multiple' => 'multiple', 'placeholder' => 'Select Roles']) }}
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingadmissionform">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#flush-collapseadmissionform" aria-expanded="false"
                                aria-controls="flush-collapsefour">
                                Admission Form Configuration
                            </button>
                        </h2>
                        <div id="flush-collapseadmissionform" class="accordion-collapse collapse"
                            aria-labelledby="flush-headingfour" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">
                                <div class="form-group">
                                    <label for="admission_text">Admission Message Text <span
                                            class="required">*</span></label>

                                    @include('layout::widget.ckeditor', [
                                        'name' => 'onboard_sucess_message',
                                        'id' => 'onboard_sucess_message',
                                        'class' => 'w-50',
                                        'content' => old('onboard_success_message', $onboardSuccessMessage),
                                    ])
                                    
                                    <div class="col-xs-14 col-sm-6 col-md-4 mt-4">
                                        <div class="item form-group">
                                            <label>Is Admission Exam Required:</label>
                                            <label class="switch">
                                                <input type="checkbox" id="admissionexamstatus" class="toggle-class"
                                                {{ $admission_exam_status ? 'checked' : '' }}
                                                    name="admission_exam_status">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div> 
                                    <div class="col-xs-16 col-sm-8 col-md-8 col-lg-6 mt-4">
                                        <div class="item form-group">
                                            <label>Enable to email the admission exam marks:</label>
                                            <label class="switch">
                                                <input type="checkbox" id="emailexamscores" class="toggle-class"
                                                {{ $emailexamscores ? 'checked' : '' }}
                                                    name="emailexamscores">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div> 
                                   
                                                    
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>


        </div>



     
    </div>
    </div>

    {{ Form::close() }}
    </div>
@endsection

@section('script_link')
    {!! Cms::script('theme/vendors-old/select2/select2.min.js') !!}
    {!! Cms::script('theme/vendors-old/switchery/dist/switchery.min.js') !!}
    <!-- validator -->
    <!-- validator -->

    {!! Cms::script('theme/vendors/validator/validator.js') !!}
    {!! Cms::script('theme/vendors/validator/validator_form.js') !!}

    {!! Cms::script('theme/vendors-old/laravel-filemanager/js/lfm.js') !!}
    <script type="module">
        AcademicYearConfig.AcademicyearInit();
        FeeConfig.Feeinit();
    </script>
    <script>
        $(document).ready(function() {
            $('.role_id').each(function() {
                let id = $(this).val();
                let row = $(this).closest('.row');
                row.find('.receptiants').attr('name', 'receptiants' + id + '[]');
            });
            let value = $("#student_performances_report").val();
            if(value == 1){
                $(".discipline_and_compliance").trigger(":checked",false);
                $(".sports_and_event").trigger(":checked",false);
                $(".stud_perform_row").hide();
            }
            else if(value == 2){
                $(".discipline_and_compliance").trigger(":checked",true);
                $(".sports_and_event").trigger(":checked",true);
                $(".stud_perform_row").show();
            }
            else{
                $(".discipline_and_compliance").trigger(":checked",false);
                $(".sports_and_event").trigger(":checked",false);
                $(".stud_perform_row").hide();
            }
        })
        $('.role_id').on("change", function() {
            let id = $(this).val();
            let row = $(this).closest('.row');
            row.find('.receptiants').attr('name', 'receptiants' + id + '[]');
        });

        $("#student_performances_report").on("change",function(){
            let value = $(this).val();
            if(value == 1){
                $(".discipline_and_compliance").prop("checked",false);
                $(".sports_and_event").prop("checked",false);
                $(".stud_perform_row").hide();
            }
            else{
                $(".discipline_and_compliance").prop("checked",true);
                $(".sports_and_event").prop("checked",true);
                $(".stud_perform_row").show();
            }
        });

        window.role_types = '{{ route('role_types') }}';
        $('.role_add').on('click', function() {
            let url = window.role_types;
            if (url) {
                axios
                    .get(url)
                    .then((response) => {
                        console.log(response);
                        if (response.data.view) {
                            $(".role_receptiants").append(response.data.view);

                        } else {
                            console.error("Invalid response data:", response);
                        }
                    })
                    .catch((error) => {
                        console.error("Error fetching student report:", error);
                    });
            } else {
                console.error("Invalid URL:", url);
            }
        })
    </script>
    <script>
        Window.history = '{{ route('history_check') }}';
        Window.academic_year_terms = '{{ route('academic_year_config_terms') }}';
        $("documnt").ready(function() {
            $('select').select2();
            $('.lfm').filemanager('{{ isset($type) ? $type : 'image' }}');
            var type = $('#student_performances_report').val();
            if (type == 1) {
                $('.auto_date_col').show();
            } else {
                $('.auto_date_col').hide();
            }

            $('#student_performances_report').on('change', function() {
                let type = $(this).val();
                if (type == 1) {
                    $('.auto_date_col').show();
                } else {
                    $('.auto_date_col').hide();
                }
            });
            $('select[name="academic_year"]').on('change', function() {
                let academic_year = $('select[name="academic_year"]').val();
                let url = Window.academic_year_terms + "?academic_year=" + academic_year;
                if (url) {
                    axios
                        .get(url)
                        .then((response) => {
                            console.log(response);
                            if (response.data) {
                                $('select[name="academic_term"]')
                                    .empty()
                                    .prepend('<option selected=""></option>')
                                    .select2({
                                        allowClear: true,
                                        placeholder: "Select academic_term...",
                                        data: response.data.academic_terms,
                                    });
                                var terms = response.data.terms;
                                $('.term_div').empty();
                                console.log(terms);
                                terms.forEach(term => {
                                    var termHtml = `<div class="col-xs-12 col-sm-4 col-md-4">
                                <div class="item form-group input-group mb-3">
                                    <span class="input-group-text">${term.exam_term_name}</span>
                                    <input type="hidden" name="due_term_dates[${term.id}][id]" value="${term.id}">
                                    <input type="number" name="due_term_dates[${term.id}][per]" required class="form-control"/>
                                    <input name="due_term_dates[${term.id}][date]" type="text" readonly class="form-control" value="${term.to_date}" aria-label="Dollar amount (with dot and two decimal places)">
                                </div></div>`;
                                    $('.term_div_append').append(termHtml);
                                    console.log(termHtml);
                                });



                            } else {
                                console.error(
                                    "Invalid response data or missing name property:",
                                    response.data
                                );
                            }
                        });
                }
            });
            $('select[name="payment_type"]').on('change', function() {
                let academic_year = $('select[name="academic_year"]').val();
                let url = Window.history + "?academic_year=" + academic_year;
                if (url) {
                    axios
                        .get(url)
                        .then((response) => {
                            console.log(response);
                            if (response.data.message) {
                                let status = response.data.message;
                                $('.payment_type_status').html(status);
                                if ($(this).val() != response.data.payment_type) {
                                    $(this).val(response.data.payment_type).trigger("change");
                                    return false;
                                    var type = response.data.payment_type;
                                    if (type == 1) {
                                        $('.term_due_label').show();
                                    } else {
                                        $('.term_due_label').hide();
                                    }
                                }

                            } else {
                                console.error(
                                    "Invalid response data or missing name property:",
                                    response.data
                                );
                            }
                        })
                        .catch((error) => {
                            let status = error;
                            console.log(status);
                            // notify_script("Error", status, "error", true);
                        });
                }
            });
        });
    </script>

@endsection
