@extends('layout::admin.master2')

@section('title', 'academicyear')
@section('style')
    <style>
        h1,


        .header1 {
            text-align: center;
            margin-top: 50px;
        }

        .header2 {
            text-align: center;
            margin-bottom: 50px;
        }

        input {
            padding: 10px;
            width: 100%;
            font-size: 17px;

            border: 1px solid #aaaaaa;
        }

        strong {
            display: block;
            text-align: center;
            margin-bottom: 20px;
        }

        /* Mark input boxes that gets an error on validation: */
        input.invalid {
            background-color: #ffdddd;
        }

        /* Hide all steps by default: */
        .tab {
            display: none;
        }

        button {
            background-color: #04AA6D;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            font-size: 17px;

            cursor: pointer;
        }

        button:hover {
            opacity: 0.8;
        }

        #prevBtn {
            background-color: #bbbbbb;
            margin-right: 10px;
        }

        /* Make circles that indicate the steps of the form: */
        .step {
            height: 15px;
            width: 15px;
            margin: 0 2px;
            background-color: #bbbbbb;
            border: none;
            border-radius: 50%;
            display: inline-block;
            opacity: 0.5;
        }

        .step.active {
            opacity: 1;
        }

        /* Mark the steps that are finished and valid: */
        .step.finish {
            background-color: #04AA6D;
        }

        .rowdata {
            justify-content: center;
            padding-bottom: 260px;
        }

        .rowdata1 {
            justify-content: center;
            padding-bottom: 40px;
        }

        .bottom_line {
            padding-bottom: 30px;
        }

        .button_list {

            width: 90%;
            padding-bottom: 30px;
        }

        .button_list_2 {

            width: 74%;
            margin: auto;
            padding-bottom: 30px;

        }



        .line1 {
            border: none;
            border-top: 1px double #333;
            width: 83%;
            color: #4a4f4d;
            overflow: visible;
            text-align: center;
            height: 4px;
            margin: auto;

        }

        .btnnext {
            background: #7F01BA;
        }

        .btnback {
            background: #ffffff;

        }

        .btnadd {
            background: #ffffff;
            color: #7F01BA;
            height: 40px;
            border-left: solid 1px;
            border-right: solid 1px;
            border-bottom: solid 1px;
            border-top: solid 1px;
            border-radius: 10px;
        }
    </style>
@endsection
@section('body')
    <style>
        .hide-calendar .ui-datepicker-calendar {
            display: none;
        }
    </style>
    <div class="x_content">
        <div class="card">
            <div class="card-body">
                <form id="regForm" action="{{ route('academicyearpopup.store') }}" method="post">
                    @csrf
                    <!-- One "tab" for each step in the form: -->
                    <div class="tab">
                        <h5 class="header1">Create New Academic year,Set Start</h5>
                        <h5 class="header2">Date and End Date</h5>

                        <div class="col-xs-12">
                            <div class="row rowdata">
                                <div class="col-xs-12 col-sm-4 col-md-2">
                                    <div class="item form-group">
                                        <label class="control-label margin__bottom" for="school_name"> Name <span
                                                class="required">(if any)</span>
                                        </label>
                                        <div class="feild">
                                            {{ Form::text('name', @$data->title, [
                                                'id' => 'academic_name',
                                                'class' => 'form-control col-md-7 col-xs-12',
                                                'data-validate-length-range' => '6',
                                                'placeholder' => 'e.g Some Name',
                                            ]) }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-4 col-md-2">
                                    <div class="item form-group">
                                        <label class="control-label margin__bottom" for="school_name"> Academic Year
                                            From<span class="required">*</span>
                                        </label>
                                        <div class="feild">
                                            {{ Form::text('year_from', @$data->year_from, ['id' => 'year_pick_from', 'class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Select Year ']) }}
                                        </div>
                                    </div>

                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-2">
                                    <div class="item form-group">
                                        <label class="control-label margin__bottom" for="school_name"> Academic Year To
                                            <span class="required">*</span>
                                        </label>
                                        <div class="feild">
                                            {{ Form::text('year_to', @$data->year_to, ['id' => 'year_pick_to', 'class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Select Year ']) }}
                                        </div>
                                    </div>

                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-2">
                                    <div class="item form-group">
                                        <label class="control-label margin__bottom" for="school_name"> Start Date <span
                                                class="required">*</span>
                                        </label>
                                        <div class="feild">
                                            {{ Form::text('start_date', @$data->start_date, ['id' => 'datepicker', 'class' => 'datepicker_academic_end form-control col-md-7 col-xs-12', 'placeholder' => 'Select Date ']) }}
                                        </div>
                                    </div>

                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-2">
                                    <div class="item form-group">
                                        <label class="control-label margin__bottom" for="school_name"> End Date <span
                                                class="required">*</span>
                                        </label>
                                        <div class="feild">
                                            {{ Form::text('end_date', @$data->end_date, ['id' => 'datepicker2', 'class' => 'datepicker_academic_end form-control col-md-7 col-xs-12', 'placeholder' => 'Select Date ']) }}
                                        </div>
                                    </div>

                                </div>
                                <!-- //status -->

                            </div>
                        </div>

                    </div>

                    <div class="tab" id="tab1">
                        <h5 class="header1">Create New Academic Term,Set </h5>
                        <h5 class="header2">Start Date and End Date</h5>
                        <strong id="display_academic"></strong>
                        <p style="text-align: center;font-weight:bold"><span id="start_year"></span> / <span
                                id="end_year"></span></p>
                        <div class="col-xs-12">
                            @foreach ([0, 1, 2] as $data)
                                <div class="row rowdata1" id="rowdata1_id">
                                    <div class="col-xs-12 col-sm-4 col-md-3">
                                        <div class="item form-group">
                                            <label class="control-label margin__bottom" for="school_name"> Term Name <span
                                                    class="required"></span>
                                            </label>
                                            @php
                                                $loop=$loop->index+1;
                                                if($loop == 1)
                                                {
                                                    $text="First";
                                                }elseif ($loop == 2) {
                                                    $text="Second";
                                                }else{
                                                    $text="Third";
                                                }
                                            @endphp
                                           
                                            <div class="feild">
                                                {{ Form::text('termname[]', $text.' Term', [
                                                    'class' => 'form-control col-md-7 col-xs-12',
                                                    'data-validate-length-range' => '6',
                                                    'placeholder' => 'Term Name',
                                                    'required',
                                                ]) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 col-md-3">
                                        <div class="item form-group">
                                            <label class="control-label margin__bottom" for="school_name"> Start Date <span
                                                    class="required">*</span>
                                            </label>
                                            <div class="feild">
                                                {{ Form::text('term_start_date[]', @$data->start_date, ['class' => 'datepicker_term_from form-control col-md-7 col-xs-12', 'placeholder' => 'Select Date ']) }}
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-xs-12 col-sm-4 col-md-3">
                                        <div class="item form-group">
                                            <label class="control-label margin__bottom" for="school_name"> End Date <span
                                                    class="required">*</span>
                                            </label>
                                            <div class="feild">
                                                {{ Form::text('term_end_date[]', @$data->end_date, ['class' => 'datepicker_term_to form-control col-md-7 col-xs-12', 'placeholder' => 'Select Date ']) }}
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            @endforeach

                        </div>

                        <div id="add_data">

                            <div id="add_fields">

                            </div>
                        </div>

                        <div class="button_list_2">
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                    <button type="button" id="addbtn" class="btnadd"><i class='fa fa-plus'></i>Add
                                        Term</button>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="bottom_line">
                        <hr class="line1">
                        </hr>
                    </div>


                    <div class="button_list" style="overflow:auto;">
                        <div style="float:right;">
                            {{-- <a class="btnback btn-sm m-1  px-3"  style="color:#7F01BA;" href="{{route('promotion.create')}}" >&nbsp;&nbsp;&nbsp;Back</a> --}}
                            <button type="button" id="prevBtn" class="" onclick="nextPrev(-1)">Previous</button>
                            <button type="button" id="nextBtn" class="btn btn-primary btnnext"
                                onclick="nextPrev(1)">Next</button>
                        </div>
                    </div>


                </form>
            </div>
            <div style="text-align:center;margin-top:40px;display:none">
                <span class="step"></span>
                <span class="step"></span>
                <span class="step"></span>
                <span class="step"></span>
            </div>
        </div>
    </div>


    </div>

@endsection

@section('script')

    <script>
        $(document).ready(function() {


            $(document).on("click", "#addbtn", function() {

                console.log("OK");

                $("#add_fields").append(`<div class="mb-4 position-relative add_box"> <div class="row rowdata1"><div class="col-xs-12 col-sm-4 col-md-3 ">
                                        <div class="item form-group">
                                            <label class="control-label margin__bottom" for="school_name"> Term Name <span
                                                    class="required"></span>
                                            </label>
                                            <div class="feild">
                                                {{ Form::text('termname[]', @$data->title, [
                                                    'class' => 'form-control col-md-7 col-xs-12',
                                                    'data-validate-length-range' => '6',
                                                    'placeholder' => 'Term Name',
                                                    'required',
                                                ]) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 col-md-3">
                                        <div class="item form-group">
                                            <label class="control-label margin__bottom" for="school_name"> Start Date <span
                                                    class="required">*</span>
                                            </label>
                                            <div class="feild">
                                                {{ Form::text('term_start_date[]', @$data->start_date, ['class' => 'datepicker_term_from form-control col-md-7 col-xs-12', 'placeholder' => 'Select Date ']) }}
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-xs-12 col-sm-4 col-md-3">
                                        <div class="item form-group">
                                            <label class="control-label margin__bottom" for="school_name"> End Date <span
                                                    class="required">*</span>
                                            </label>
                                            <div class="feild">
                                                {{ Form::text('term_end_date[]', @$data->end_date, ['class' => 'datepicker_term_to form-control col-md-7 col-xs-12', 'placeholder' => 'Select Date ']) }}
                                                <button type="button" class="btn remove_box">&times;</button>
                                                </div>
                                        </div>                                       
                                    </div>
                                 
                
                                </div></div></div>`);

                var academic_start_date = document.getElementById("datepicker").value;
                var academic_end_date = document.getElementById("datepicker2").value;

                $(".datepicker_term_from").datepicker({
                    minDate: academic_start_date,
                    maxDate: academic_end_date
                });
                $(".datepicker_term_to").datepicker({
                    minDate: academic_start_date,
                    maxDate: academic_end_date
                });


                $(".remove_box").on("click", function() {

                    $(this).closest('.add_box').remove();
                });

                $('#add_fields b[role="presentation"]').hide();

            });

        });
    </script>
    <script>
        var currentTab = 0; // Current tab is set to be the first tab (0)
        showTab(currentTab); // Display the current tab

        function showTab(n) {
            // This function will display the specified tab of the form...
            var x = document.getElementsByClassName("tab");
            x[n].style.display = "block";
            //... and fix the Previous/Next buttons:
            if (n == 0) {
                document.getElementById("prevBtn").style.display = "none";
            } else {
                document.getElementById("prevBtn").style.display = "inline";
            }
            if (n == (x.length - 1)) {
                document.getElementById("nextBtn").innerHTML = "Submit";
            } else {
                document.getElementById("nextBtn").innerHTML = "Next";
            }
            //... and run a function that will display the correct step indicator:
            fixStepIndicator(n)
        }

        function nextPrev(n) {
            // This function will figure out which tab to display
            var x = document.getElementsByClassName("tab");

            var academic_name = document.getElementById("academic_name").value;
            var academic_start_date = document.getElementById("datepicker").value;
            var academic_end_date = document.getElementById("datepicker2").value;
            var academic_from_year = document.getElementById("year_pick_from").value;
            var academic_to_year = document.getElementById("year_pick_to").value;

            $("#display_academic").html(academic_name);

            $("#start_year").html(academic_from_year);
            $("#end_year").html(academic_to_year);

            $(".datepicker_term_from").datepicker({
                minDate: academic_start_date,
                maxDate: academic_end_date
            });
            $(".datepicker_term_to").datepicker({
                minDate: academic_start_date,
                maxDate: academic_end_date
            });

            console.log(academic_start_date, academic_end_date);
            // Exit the function if any field in the current tab is invalid:
            if (n == 1 && !validateForm()) return false;
            // Hide the current tab:
            x[currentTab].style.display = "none";
            // Increase or decrease the current tab by 1:
            currentTab = currentTab + n;
            // if you have reached the end of the form...
            if (currentTab >= x.length) {
                // ... the form gets submitted:
                document.getElementById("regForm").submit();
                return false;
            }
            // Otherwise, display the correct tab:
            showTab(currentTab);
        }

        function validateForm() {
            // This function deals with validation of the form fields
            var x, y, i, valid = true;
            x = document.getElementsByClassName("tab");
            y = x[currentTab].getElementsByTagName("input");
            // A loop that checks every input field in the current tab:
            for (i = 0; i < y.length; i++) {
                // If a field is empty...
                if (y[i].value == "") {
                    // add an "invalid" class to the field:
                    y[i].className += " invalid";
                    // and set the current valid status to false
                    valid = false;
                }
            }
            // If the valid status is true, mark the step as finished and valid:
            if (valid) {
                document.getElementsByClassName("step")[currentTab].className += " finish";
            }
            return valid; // return the valid status
        }

        function fixStepIndicator(n) {
            // This function removes the "active" class of all steps...
            var i, x = document.getElementsByClassName("step");
            for (i = 0; i < x.length; i++) {
                x[i].className = x[i].className.replace(" active", "");
            }
            //... and adds the "active" class on the current step:
            x[n].className += " active";
        }
    </script>

    <!-- validator -->
    {!! Cms::script('theme/vendors/validator/validator.js') !!}

@endsection
