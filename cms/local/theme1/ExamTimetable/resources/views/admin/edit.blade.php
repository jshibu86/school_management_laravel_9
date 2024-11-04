@extends('layout::admin.master')
@section('title', 'ExamTimetable')
@section('style')
    <!-- Datatables -->
    @include('layout::admin.head.list_head')
    <style>
        .table-div table {
            width: 100% !important;
        }

        .accordion-button::before {
            flex-shrink: 0;
            width: 1.50rem;
            height: 1.50rem;
            margin-right: 10px;
            content: "";
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512' fill='%237f01ba'%3e%3cpath d='M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm144 276c0 6.6-5.4 12-12 12h-92v92c0 6.6-5.4 12-12 12h-56c-6.6 0-12-5.4-12-12v-92h-92c-6.6 0-12-5.4-12-12v-56c0-6.6 5.4-12 12-12h92v-92c0-6.6 5.4-12 12-12h56c6.6 0 12 5.4 12 12v92h92c6.6 0 12 5.4 12 12v56z'/%3e%3c/svg%3e"
                );
            background-repeat: no-repeat;
            background-size: 1.50rem;
            transition: transform .2s ease-in-out;
        }

        .accordion-button:not(.collapsed)::before {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512' fill='%237f01ba'%3e%3cpath d='M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zM124 296c-6.6 0-12-5.4-12-12v-56c0-6.6 5.4-12 12-12h264c6.6 0 12 5.4 12 12v56c0 6.6-5.4 12-12 12H124z'/%3e%3c/svg%3e");

        }

        .accordion-button::after {
            background-image: unset !important;
        }

        .accordion-button:not(.collapsed)::after {
            background-image: unset !important;
        }
    </style>
@endsection
@section('body')
    @if ($layout == 'create')
        {{ Form::open(['role' => 'form', 'route' => ['examtimetable_store'], 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'trasnportreport-form', 'novalidate' => 'novalidate']) }}
    @elseif($layout == 'clone')  
    {{ Form::open(['role' => 'form', 'route' => ['examtimetable_store'], 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'trasnportreport-form', 'novalidate' => 'novalidate']) }}  
    @else
        {{ Form::open(['role' => 'form', 'route' => ['ExamTimetable.update', $id], 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'trasnportreport-form', 'novalidate' => 'novalidate']) }}
    @endif
    <div class="box-header with-border mar-bottom20">
        <a class="btn btn-info btn-sm m-1  px-3" href="{{ route('examtimetable') }}"><i
                class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>
    </div>
    <div class="card">
        @if ($layout == 'create')
            <div class="card-body">
                <div class="card-title btn_style">
                    <h4 class="mb-0">Exam Time Table</h4>

                </div>
                <hr />

                <div class="accordion" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button accordion1" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                <span style="font-size: 1.5rem;">Class Details</span>
                            </button>
                        </h2>
                        <div id="collapseOne" class=" atnaccodrdian accordion-collapse collapse show"
                            aria-labelledby="headingOne" data-bs-parent="#accordionExample" style="">
                            <div class="accordion-body">
                                <div class="row">

                                    <div class="col-xs-12 col-sm-4 col-lg-2">
                                        <div class="item form-group">
                                            <label class="control-label margin__bottom" for="status">Academic Year <span
                                                    class="required">*</span>
                                            </label>
                                            <div class="feild">
                                                {{ Form::select('academic_year_grade', @$academicyears, Configurations::getCurrentAcademicyear(), [
                                                    'id' => 'academic',
                                                    'class' => @$layout == 'edit' ? ' form-control' : 'single-select form-control',
                                                    'required' => 'required',
                                                    'placeholder' => 'Select Academic year',
                                                    @$layout == 'edit' ? 'disabled' : '',
                                                ]) }}
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-xs-12 col-sm-4 col-lg-2">
                                        <div class="item form-group">
                                            <label for="exam_term" class="mb-2">Academic Term <span>*</span></label>


                                            {{ Form::select('academic_term', @$examterms, @$data->term_id ? @$data->term_id : @$current_academic_term, [
                                                'id' => 'academic_term',
                                                'class' => 'single-select form-control',
                                                'required' => 'required',
                                                'placeholder' => 'Select Exam Term',
                                                @$layout == 'edit' ? 'disabled' : '',
                                            ]) }}
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 col-lg-2">
                                        <div class="item form-group">
                                            <label class="control-label margin__bottom" for="status">School Type <span
                                                    class="required">*</span>
                                            </label>
                                            <div class="feild">
                                                {{ Form::select('school_type', @$school_type_info, @$school_type_info ? @$data->term_id : @$school_type_info, [
                                                    'id' => 'school_type',
                                                    'class' => 'single-select form-control',
                                                    'required' => 'required',
                                                    'placeholder' => 'Select School Type',
                                                    @$layout == 'edit' ? 'disabled' : '',
                                                ]) }}

                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-xs-12 col-sm-4 col-lg-2">
                                        <div class="item form-group">
                                            <label for="class_id" class="mb-2">Class <span>*</span></label>
                                            {{ Form::select('class_id', @$class_lists, @$data->class_id, [
                                                'id' => 'class_id_grade',
                                                'class' => @$layout == 'edit' ? ' form-control' : 'single-select form-control',
                                                'required' => 'required',
                                                'placeholder' => 'Select Class',
                                                @$layout == 'edit' ? 'disabled' : '',
                                            ]) }}

                                        </div>

                                    </div>

                                    <div class="col-xs-12 col-sm-4 col-lg-2">
                                        <div class="item form-group">
                                            <label for="sec_dep" class="mb-2">Sec/Dep <span>*</span></label>
                                            {{ Form::select('sec_dep', @$section_lists, @$data->class_id, [
                                                'id' => 'sec_dep',
                                                'class' => @$layout == 'edit' ? ' form-control' : 'single-select form-control',
                                                'required' => 'required',
                                                'placeholder' => 'Select Sec/Dep',
                                                @$layout == 'edit' ? 'disabled' : '',
                                            ]) }}

                                        </div>

                                    </div>


                                    <div class="col-xs-12 col-sm-4 col-lg-2 weekly">
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
                                    <div class="col-xs-12 col-sm-10 col-lg-8 weekly">
                                        <div class="item form-group">
                                            <label class="control-label margin__bottom" for="status">End Date <span
                                                    class="required">*</span>
                                            </label>
                                            <div class="feild row">
                                                <div class="feild col-lg-3 col-5">
                                                    {{ Form::text('end_date', @$end_date, [
                                                        'id' => 'end_date',
                                                        'class' => ' form-control datepicker_academic_start enddate',
                                                        'placeholder' => 'select end date',
                                                    ]) }}
                                                </div>
                                                <div class="field col-lg-4 col-5">
                                                    <button type="button"
                                                        class="btn btn-primary add_exam_periods form-control"> <i
                                                            class="fa fa-plus" name="daily"></i>&nbsp;&nbsp;Add Exam
                                                        Periods</button>
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
            <div class="card-body append_card" style="display:none;">
                <div class="accordion" id="accordionExample2">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button accordion2" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseOne">
                                <span style="font-size: 1.5rem;">Add Exam Period</span>
                            </button>
                        </h2>
                        <div id="collapseTwo" class=" atnaccodrdian accordion-collapse collapse show"
                            aria-labelledby="headingOne" data-bs-parent="#accordionExample2" style="">
                            <div class="accordion-body" id="append_div">


                                <div class="row" id="append_row">

                                    <div class="col-xs-12 col-sm-4 col-md-3">
                                        <div class="item form-group">
                                            <label class="control-label margin__bottom" for="distribution_mark">Start Time
                                                <span class="required">*</span></label>
                                            <div class="feild">
                                                {{ Form::time('start_time[]', '', [
                                                    'id' => 'start_time',
                                                    'class' => 'form-control col-md-7 col-xs-12',
                                                    'required' => 'required',
                                                ]) }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-2 col-md-3">
                                        <div class="item form-group">
                                            <label class="control-label margin__bottom" for="status">End Time <span
                                                    class="required">*</span></label>
                                            <div class="feild">
                                                {{ Form::time('end_time[]', '', [
                                                    'id' => 'end_time',
                                                    'class' => 'form-control col-md-7 col-xs-12',
                                                    'required' => 'required',
                                                ]) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 col-md-3">
                                        <div class="item form-group">
                                            <label class="control-label margin__bottom" for="status">Period
                                                Category<span class="required">*</span></label>
                                            <div class="feild">
                                                {{ Form::select(
                                                    'period_category[]',
                                                    @$period_category,
                                                    @$data->$period_category ? @$data->$period_category : @$period_category,
                                                    [
                                                        'id' => 'period_category',
                                                        'class' => @$layout == 'edit' ? ' form-control' : 'single-select form-control',
                                                        'required' => 'required',
                                                        'placeholder' => 'Select period category',
                                                        @$layout == 'edit' ? 'disabled' : '',
                                                    ],
                                                ) }}

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-1">
                                        <button class="btn delete-row"><i class="fa fa-times-circle text-secondary"
                                                style="margin-top: 1em !important; font-size:26px;"></i></button>
                                    </div>
                                </div> <!--row-->

                            </div>
                            <div class="ms-2">
                                <div class="ms-2 mb-2">
                                    <button type="button" class="btn btn-primary" id="add_new_button">Add New</button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @elseif ($layout == 'clone')
            <div class="card-body">
                <div class="card-title btn_style">
                    <h4 class="mb-0">Exam Time Table</h4>

                </div>
                <hr />

                <div class="accordion" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button accordion1" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                <span style="font-size: 1.5rem;">Class Details</span>
                            </button>
                        </h2>
                        <div id="collapseOne" class=" atnaccodrdian accordion-collapse collapse show"
                            aria-labelledby="headingOne" data-bs-parent="#accordionExample" style="">
                            <div class="accordion-body">
                                <div class="row">

                                    <div class="col-xs-12 col-sm-4 col-lg-2">
                                        <div class="item form-group">
                                            <label class="control-label margin__bottom" for="status">Academic Year <span
                                                    class="required">*</span>
                                            </label>
                                            <div class="feild">
                                                {{ Form::select('academic_year_grade', $academicyears, @$exam_period_info->academic_year, [
                                                    'id' => 'academic',
                                                    'class' => @$layout == 'edit' ? ' form-control' : 'single-select form-control',
                                                    'required' => 'required',
                                                    'placeholder' => 'Select Academic year',
                                                    @$layout == 'edit' ? 'disabled' : '',
                                                ]) }}
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-md-2">
                                        <div class="item form-group">
                                            <label for="exam_term" class="mb-2">Academic Term <span>*</span></label>


                                            {{ Form::select('academic_term', $examterms, $exam_period_info->term_id ? $exam_period_info->term_id : @$current_academic_term, [
                                                'id' => 'academic_term',
                                                'class' => 'single-select form-control',
                                                'required' => 'required',
                                                'placeholder' => 'Select Exam Term',
                                                @$layout == 'edit' ? 'disabled' : '',
                                            ]) }}
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 col-lg-2">
                                        <div class="item form-group">
                                            <label class="control-label margin__bottom" for="status">School Type <span
                                                    class="required">*</span>
                                            </label>
                                            <div class="feild">
                                                {{ Form::select('school_type', $school_type_infos, @$exam_period_info->school_type ? @$exam_period_info->school_type : @$school_type_info, [
                                                    'id' => 'school_type',
                                                    'class' => 'single-select form-control',
                                                    'required' => 'required',
                                                    'placeholder' => 'Select School Type',
                                                    @$layout == 'edit' ? 'disabled' : '',
                                                ]) }}

                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-xs-12 col-sm-4 col-lg-2">
                                        <div class="item form-group">
                                            <label for="class_id" class="mb-2">Class <span>*</span></label>
                                            {{ Form::select('class_id', $class_lists, @$exam_period_info->class_id, [
                                                'id' => 'class_id_grade',
                                                'class' => @$layout == 'edit' ? ' form-control' : 'single-select form-control',
                                                'required' => 'required',
                                                'placeholder' => 'Select Class',
                                                @$layout == 'edit' ? 'disabled' : '',
                                            ]) }}

                                        </div>

                                    </div>

                                    <div class="col-xs-12 col-sm-4 col-lg-2">
                                        <div class="item form-group">
                                            <label for="sec_dep" class="mb-2">Sec/Dep <span>*</span></label>
                                            {{ Form::select('sec_dep', $section_lists, @$exam_period_info->sec_id, [
                                                'id' => 'sec_dep',
                                                'class' => @$layout == 'edit' ? ' form-control' : 'single-select form-control',
                                                'required' => 'required',
                                                'placeholder' => 'Select Sec/Dep',
                                                @$layout == 'edit' ? 'disabled' : '',
                                            ]) }}

                                        </div>

                                    </div>


                                    <div class="col-xs-12 col-sm-4 col-lg-2 weekly">
                                        <div class="item form-group">
                                            <label class="control-label margin__bottom" for="status">Start Date <span
                                                    class="required">*</span>
                                            </label>
                                            <div class="feild">
                                                <div class="feild">
                                                    {{ Form::text('start_date', @$exam_period_info->start_date, [
                                                        'id' => 'start_date',
                                                        'class' => ' form-control datepicker_academic_start startdate',
                                                        'placeholder' => 'select start date',
                                                    ]) }}
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-xs-12 col-sm-4 col-md-8 weekly">
                                        <div class="item form-group">
                                            <label class="control-label margin__bottom" for="status">End Date <span
                                                    class="required">*</span>
                                            </label>
                                            <div class="feild row">
                                                <div class="feild col-3">
                                                    {{ Form::text('end_date', @$exam_period_info->end_date, [
                                                        'id' => 'end_date',
                                                        'class' => ' form-control datepicker_academic_start enddate',
                                                        'placeholder' => 'select end date',
                                                    ]) }}
                                                </div>
                                                {{-- <div class="field col-4">
                                                    <button type="button"
                                                        class="btn btn-primary add_exam_periods form-control"> <i
                                                            class="fa fa-plus" name="daily"></i>&nbsp;&nbsp;Add Exam
                                                        Periods</button>
                                                </div> --}}
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="card-title btn_style">
                    <h4 class="mb-0">Update Exam Period</h4>
                    <hr />
                </div>
                <div class="accordion" id="accordionExample2">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button accordion2" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseOne">
                                <span style="font-size: 1.5rem;">Edit Exam Period</span>
                            </button>
                        </h2>
                        <div id="collapseTwo" class=" atnaccodrdian accordion-collapse collapse show"
                            aria-labelledby="headingOne" data-bs-parent="#accordionExample2" style="">
                            <div class="accordion-body" id="append_div">

                                @if ($data)
                                    @foreach ($data as $period)
                                        <div class="row" id="append_row">

                                            <div class="col-xs-12 col-sm-4 col-md-3">
                                                <div class="item form-group">
                                                    <label class="control-label margin__bottom"
                                                        for="distribution_mark">Start Time <span
                                                            class="required">*</span></label>
                                                    <div class="feild">
                                                        {{ Form::time('start_time[]', @$period->start_time, [
                                                            'id' => 'start_time',
                                                            'class' => 'form-control col-md-7 col-xs-12',
                                                            'required' => 'required',
                                                        ]) }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-sm-2 col-md-3">
                                                <div class="item form-group">
                                                    <label class="control-label margin__bottom" for="status">End Time
                                                        <span class="required">*</span></label>
                                                    <div class="feild">
                                                        {{ Form::time('end_time[]', @$period->end_time, [
                                                            'id' => 'end_time',
                                                            'class' => 'form-control col-md-7 col-xs-12',
                                                            'required' => 'required',
                                                        ]) }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-4 col-md-3">
                                                @php
                                                    if (
                                                        $period->period_category &&
                                                        isset(
                                                            Configurations::PERIODCATEGORIES[$period->period_category],
                                                        )
                                                    ) {
                                                        $selected_period = $period->period_category;
                                                    } else {
                                                        $selected_period = null;
                                                    }
                                                @endphp
                                                <div class="item form-group">
                                                    <label class="control-label margin__bottom" for="status">Period
                                                        Category<span class="required">*</span></label>
                                                    <div class="feild">
                                                        {{ Form::select('period_category[]', Configurations::PERIODCATEGORIES, $selected_period, [
                                                            'id' => 'period_category',
                                                            'class' => @$layout == 'edit' ? ' form-control' : 'single-select form-control',
                                                            'required' => 'required',
                                                            'placeholder' => 'Select period category',
                                                        ]) }}

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-1">
                                                <button class="btn delete-row-period"id="{{ $period->id }}"
                                                    type="button"><i class="fa fa-times-circle text-secondary "
                                                        style="margin-top: 1em !important; font-size:26px;"></i></button>
                                            </div>
                                        </div> <!--row-->
                                    @endforeach
                                @endif
                            </div>
                            <div class="ms-2">
                                <div class="ms-2 mb-2">
                                    <button type="button" class="btn btn-primary" id="add_new_button">Add New</button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
       
        
        @else
            <div class="card-body">
                <div class="card-title btn_style">
                    <h4 class="mb-0">Update Exam Period</h4>
                    <hr />
                </div>
                <div class="accordion" id="accordionExample2">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button accordion2" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseOne">
                                <span style="font-size: 1.5rem;">Edit Exam Period</span>
                            </button>
                        </h2>
                        <div id="collapseTwo" class=" atnaccodrdian accordion-collapse collapse show"
                            aria-labelledby="headingOne" data-bs-parent="#accordionExample2" style="">
                            <div class="accordion-body" id="append_div">

                                @if ($data)
                                    @foreach ($data as $period)
                                        <div class="row" id="append_row">

                                            <div class="col-xs-12 col-sm-4 col-md-3">
                                                <div class="item form-group">
                                                    <label class="control-label margin__bottom"
                                                        for="distribution_mark">Start Time <span
                                                            class="required">*</span></label>
                                                    <div class="feild">
                                                        {{ Form::time('start_time[]', @$period->start_time, [
                                                            'id' => 'start_time',
                                                            'class' => 'form-control col-md-7 col-xs-12',
                                                            'required' => 'required',
                                                        ]) }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-sm-2 col-md-3">
                                                <div class="item form-group">
                                                    <label class="control-label margin__bottom" for="status">End Time
                                                        <span class="required">*</span></label>
                                                    <div class="feild">
                                                        {{ Form::time('end_time[]', @$period->end_time, [
                                                            'id' => 'end_time',
                                                            'class' => 'form-control col-md-7 col-xs-12',
                                                            'required' => 'required',
                                                        ]) }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-4 col-md-3">
                                                @php
                                                    if (
                                                        $period->period_category &&
                                                        isset(
                                                            Configurations::PERIODCATEGORIES[$period->period_category],
                                                        )
                                                    ) {
                                                        $selected_period = $period->period_category;
                                                    } else {
                                                        $selected_period = null;
                                                    }
                                                @endphp
                                                <div class="item form-group">
                                                    <label class="control-label margin__bottom" for="status">Period
                                                        Category<span class="required">*</span></label>
                                                    <div class="feild">
                                                        {{ Form::select('period_category[]', Configurations::PERIODCATEGORIES, $selected_period, [
                                                            'id' => 'period_category',
                                                            'class' => @$layout == 'edit' ? ' form-control' : 'single-select form-control',
                                                            'required' => 'required',
                                                            'placeholder' => 'Select period category',
                                                        ]) }}

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-1">
                                                <button class="btn delete-row-period"id="{{ $period->id }}"
                                                    type="button"><i class="fa fa-times-circle text-secondary "
                                                        style="margin-top: 1em !important; font-size:26px;"></i></button>
                                            </div>
                                        </div> <!--row-->
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @php
            $style = $layout == 'create' ? 'display:none;' : '';
        @endphp
        <div class="row justify-content-end btn_submit" style="{{ $style }}">
            <div class="col-md-3 mb-3 me-2">
                <button type="submit" class="btn btn-primary" style="float:inline-end" id="save_continue">
                    Save/Continue
                </button>
            </div>
        </div>
    </div>

    {{ Form::close() }}
    </div>
@endsection
@section('script')

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <!-- FixedColumns CSS -->
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/fixedcolumns/3.3.3/css/fixedColumns.dataTables.min.css">
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <!-- FixedColumns JS -->
    <script src="https://cdn.datatables.net/fixedcolumns/3.3.3/js/dataTables.fixedColumns.min.js"></script>

@endsection

@section('scripts')

    <script type="module">
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
        window.classurl = '{{ route('schooltype.index') }}';
        window.getstudentperformanceinfo = "{{ route('studentperformance') }}";
        window.append_new_period = '{{ route('examtimetable') }}'
        window.deleteperiod = "{{ route('examtimetable_period_delete') }}"
        AttendanceConfig.AttendanceInit(notify_script);
        AcademicConfig.Leaveinit(notify_script);
        //grade -- Class,Section List
        PromotionConfig.PromotionInit(notify_script);
        // ReportConfig.ReportInit(notify_script);
        StudentPerformance.StudentPerformanceInit(notify_script);
        //examtimetableconfig init
        ExamTimetable.ExamTimetableInit(notify_script);
        //grade chart
        Account.AccountInit();

        // window.student_overall_report_info = "{{ route('grade_student_report_view') }}"
        // ReportConfig.getStudentsMarkinfo(id,academic_year,position,term);
    </script>
    <script>
        $('.single-select').select2({
            theme: 'bootstrap4',
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            allowClear: Boolean($(this).data('allow-clear')),
        });
    </script>
    <script>
        window.onload = function() {
            // Code to be executed when the entire page has finished loading

        };
    </script>
    <script>
        $(document).on('click', '.delete-row', function() {
            var index = $(this).data('index');
            $(this).closest('.row').remove();
        });
    </script>



@endsection







@section('script')

    <!-- validator -->
    {!! Cms::script('theme/vendors/validator/validator.js') !!}

@endsection
