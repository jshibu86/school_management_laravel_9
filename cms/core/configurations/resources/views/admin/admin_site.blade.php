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
        })
        $('.role_id').on("change", function() {
            let id = $(this).val();
            let row = $(this).closest('.row');
            row.find('.receptiants').attr('name', 'receptiants' + id + '[]');
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
