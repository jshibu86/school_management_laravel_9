@extends('layout::admin.master')

@section('title','Academic Term')
@section('style')
    <style>

        .tab {
            display: none;
        }  
        .btnadd1{
            display: none;
        }
      
       .rowdata1 {
            padding-top: 30px;
            justify-content: center;
            padding-bottom: 40px;
        }

         .button_list_2 {

            width: 74%;
            margin: auto;
            padding-bottom: 30px;

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
        .datepicker_term_from{
            background:#ffffff !important;
        }
        .datepicker_term_to{
            background:#ffffff !important;
        }


    </style>
@endsection

@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('examterm.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'exam-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('examterm.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
        <div class="box-header with-border mar-bottom20">
           
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_exam' , 'class' => 'btn btn-success btn-sm m-1  px-3 submit_btn')) }}

            @if (@$layout == "create")  

            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save-Continue', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat_continue' , 'value' => 'Edit_wallet' , 'class' => 'btn btn-dark btn-sm m-1  px-3 submit_btn')) }}

            @endif
                    

             <a class="btn btn-info btn-sm m-1  px-3" href="{{route('examterm.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

              {{-- {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }} --}}
              
           </div>
           @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit Academic Term" : "Create Academic Term"])

             
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Academic Term</h5>
        <hr/>
       
      <div class="col-xs-12">
            <div class="row">
                @if($layout == "edit")

                <div class="col-xs-12 col-sm-4 col-md-3 mx-auto"> <!-- Adjusted column classes -->
                    <div class="item form-group">
                        <label class="control-label margin__bottom" for="status">Academic year <span class="required">*</span></label>
                        <div class="field">
                           {{ Form::select('academic_year',@$academic_years,@$data->academicyear,
                                 array('id'=>'acyear','class' => ' form-control','required' => 'required',"placeholder"=>"Select Academic Year" ,'disabled' => 'disabled')) }}
                        </div>
                    </div>
                </div>  
                       
                @else

              <div class="col-xs-12 col-sm-4 col-md-3 mx-auto"> <!-- Adjusted column classes -->
                    <div class="item form-group">
                        <label class="control-label margin__bottom" for="status">Academic year <span class="required">*</span></label>
                        <div class="field">
                           {{ Form::select('academic_year',@$academic_years,'',
                                 array('id'=>'acyear','class' => 'single-select form-control','required' => 'required',"placeholder"=>"Select Academic Year" )) }}
                        </div>
                    </div>
                </div>

                 @endif

            
            </div>    
           </div>
            
           {{-- <div class="tab" id="tab1">
            <div class="col-xs-12"> 
               --}}
                @if($datavalue)
                    @foreach ($datavalue as $exam) 
                        
                        <div class="row rowdata1" id="rowdata1_id">
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="school_name"> Term Name <span
                                            class="required"></span>
                                    </label>
                                                                
                                    <div class="feild">
                                                                                
                                            {{ Form::text('existtermname', $exam->exam_term_name, 
                                            array('id'=>'terms',
                                            'class' => 'form-control col-md-7 col-xs-12',
                                                'data-validate-length-range' => '6',
                                                'placeholder' => 'Term Name',
                                                'required', )) }}                                                                                 

                                    </div>
                                </div>
                            </div> 
    
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="school_name"> Start Date <span
                                            class="required">*</span>
                                    </label>
                                    <div class="feild">                                
                                    

                                    {{ Form::text('existterm_start_date',$exam->from_date ? $exam->from_date : null, 
                                        array(
                                        'class' => 'datepicker_term_from form-control col-md-7 col-xs-12',
                                        'placeholder' => 'Select Date ','readonly')) }}  
                                    </div>
                                </div>
                            </div> 
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="school_name"> End Date <span
                                            class="required">*</span>
                                    </label>
                                    <div class="feild">
                                    
                                        {{ Form::text('existterm_end_date',$exam->to_date ? $exam->to_date : null,
                                        array('class' => 'datepicker_term_to form-control col-md-7 col-xs-12',
                                        'placeholder' => 'Select Date ','readonly')) }}
                                    </div>
                                </div>

                            </div> 
                        </div> 
                    @endforeach 

                     <div id="add_data 1">

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


                @else
                <div class="tab" id="tab1">
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

                                            {{ Form::text('termname[]', $text.' Term',
                                                array('id'=>'terms',
                                                'class' => 'form-control col-md-7 col-xs-12 ',
                                                    'data-validate-length-range' => '6',
                                                    'placeholder' => 'Term Name',
                                                    'required'=>"required", )) }}                                 

                                            </div>
                                        </div>
                                    </div> 

                                    <div class="col-xs-12 col-sm-4 col-md-3">
                                        <div class="item form-group">
                                            <label class="control-label margin__bottom" for="school_name"> Start Date <span
                                                    class="required">*</span>
                                            </label>
                                            <div class="feild">
                                            
                                                {{ Form::text('term_start_date[]','',  
                                                array(
                                                'class' => 'datepicker_term_from form-control col-md-7 col-xs-12',
                                                'placeholder' => 'Select Date ', 'required'=>"required",'readonly')) }}                        
                                                
                                            </div>
                                        </div>

                                    </div> 

                                    <div class="col-xs-12 col-sm-4 col-md-3">
                                        <div class="item form-group">
                                            <label class="control-label margin__bottom" for="school_name"> End Date <span
                                                    class="required">*</span>
                                            </label>
                                            <div class="feild">
                                            
                                                {{ Form::text('term_end_date[]','',  
                                            array(
                                            'class' => 'datepicker_term_to form-control col-md-7 col-xs-12',
                                                'placeholder' => 'Select Date ', 'required'=>"required",'readonly')) }}                               


                                            </div>
                                        </div>

                                    </div>
                                </div>
                            @endforeach              
                    </div>
                </div>               
                     
                @endif 
                  
        {{-- </div>
    </div>     --}}
    
                
                <div id="add_data">

                    <div id="add_fields">

                    </div>
                </div>

                <div class="button_list_2">
                    <div class="col-xs-12 col-sm-4 col-md-3">
                        <div class="item form-group btnadd1">
                            <button type="button" id="addbtn" class="btnadd"><i class='fa fa-plus'></i>Add
                                Term</button>
                        </div>
                    </div>
                </div>             
                   

        {{Form::close()}}
    </div>
</div>
</div>

@endsection

@section('script')

<script>
    $(document).ready(function() {
      
        console.log("OK sssssssssssss");

  
    });
    </script>

    <script>



    $(document).ready(function() {     

        console.log("OK PICKER");

        var academic_start_date ;                
        var academic_end_date;                              
                           
        $(document).on("click", "#addbtn", function () {
            console.log("OK");
          

            $(
                "#add_fields"
            ).append(`<div class="mb-4 position-relative add_box"> <div class="row rowdata1"><div class="col-xs-12 col-sm-4 col-md-3 ">
                                    <div class="item form-group">
                                        <label class="control-label margin__bottom" for="school_name"> Term Name <span
                                                class="required"></span>
                                        </label>
                                        <div class="feild">
                                            {{ Form::text('termname[]',@$data->title, [
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
                                            {{ Form::text('term_start_date[]',@$data->start_date,['class' => 'datepicker_term_from form-control col-md-7 col-xs-12', 'placeholder' => 'Select Date ','readonly']) }}
                                        </div>
                                    </div>

                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-3">
                                    <div class="item form-group">
                                        <label class="control-label margin__bottom" for="school_name"> End Date <span
                                                class="required">*</span>
                                        </label>
                                        <div class="feild">
                                            {{ Form::text('term_end_date[]',@$data->end_date, ['class' => 'datepicker_term_to form-control col-md-7 col-xs-12', 'placeholder' => 'Select Date ','readonly']) }}
                                            <button type="button" class="btn remove_box">&times;</button>
                                            </div>
                                    </div>                                       
                                </div>
                             
            
                            </div></div></div>`);
                                        
              academic_start_date = $('.datepicker_term_from').datepicker('option', 'minDate');                
              academic_end_date =  $('.datepicker_term_to').datepicker('option', 'maxDate');
          

                $(".datepicker_term_from").first().datepicker({
                        minDate: academic_start_date,
                        maxDate: academic_end_date,
                        changeMonth: true,
                        changeYear: true,
                        onSelect: function (selected) {
                            
                        } 
                        });

                    $(".datepicker_term_to").first().datepicker({
                            minDate: academic_start_date,
                            maxDate: academic_end_date,
                            changeMonth: true,
                            changeYear: true,
                            onSelect: function (selected) {


                            var term_end_date = selected;
                            var term_start_date = $(".datepicker_term_from").val(); 
                            

                            if (term_end_date !== undefined && term_end_date !== null) { 
                                if (term_start_date !== undefined && term_start_date !== null) { 
                                                if (term_end_date.length >0 && term_start_date.length> 0 ){
                                        
                                                    if (new Date(term_start_date) > new Date(term_end_date)  ) {
                                                        $(".submit_btn").hide();
                                                      
                                                       notify("Error", "Start Date should be Less than End Date", "error", true);
                                                        
                                                    }
                                                    else{
                                                        $(".submit_btn").show();
                                                    }
                                                }                          

                                            }
                                }

                        }                                            
                            
                        }); 
                        
                        
                        $(".datepicker_term_from").not(':first').each(function (index) {
                        var $this = $(this);
                        var idx = index;
                        $this.datepicker({
                            minDate: academic_start_date,
                            maxDate: academic_end_date,
                            changeMonth: true,
                            changeYear: true,
                            onSelect: function (selected) {
                                // Get the corresponding "End Date" datepicker input
                                var $endDateInput = $('.datepicker_term_to').eq(idx);

                                    // Get the value of the "End Date" datepicker
                                    var endDateValue = $endDateInput.val();
                                    
                                    if (endDateValue !== undefined && endDateValue !== null) {
                                        if (endDateValue.length >0){

                                                if (new Date(endDateValue) >= new Date(selected)) {
                                                        notify("Error", "Start Date should be Greater than End Date", "error", true);

                                                    $(".datepicker_term_to").prop('disabled', true);
                                                    $(".datepicker_term_from").prop('disabled', true);
                                                    $this.prop('disabled', false);
                                                    
                                                }
                                                else{
                                                    $(".datepicker_term_to").prop('disabled', false);
                                                    $(".datepicker_term_from").prop('disabled', false);
                                                }

                                            } 
                                    } 
                                }
                        });
               });

               $(".datepicker_term_to").not(':first').each(function (index) {
                var $this = $(this);
                    var idx = index+1;
                    $this.datepicker({
                        minDate: academic_start_date,
                        maxDate: academic_end_date,
                        changeMonth: true,
                        changeYear: true,
                        onSelect: function (selected) {
                            // Get the corresponding "End Date" datepicker input
                            var $startDateInput = $('.datepicker_term_from').eq(idx);

                                // Get the value of the "End Date" datepicker
                                var startDateValue = $startDateInput.val();
                                 
                                if (startDateValue !== undefined && startDateValue !== null) {
                                    if (startDateValue.length >0){

                              
                                            if (new Date(startDateValue) > new Date(selected)) {
                                                $(".submit_btn").hide();
                                               
                                                  notify("Error", "Start Date should be Less than End Date", "error", true);
                                                  
                                            }
                                            else{
                                                $(".submit_btn").show();
                                            }                                         

                                        } 
                                } 
                            }
                    });
               });
           

            function notify(title, text, type, hide) {
                new PNotify({
                    title: title,
                    text: text,
                    type: type,
                    hide: hide,
                    styling: "fontawesome",
                });
            }


            $(".remove_box").on("click", function () {
                $(this).closest(".add_box").remove();
            });

            $('#add_fields b[role="presentation"]').hide();    
            

        });   
               
            

    });
</script>
@endsection


@section('script_link')

    <!-- validator -->
    {!! Cms::script("theme/vendors/validator/validator.js") !!}
    {!! Cms::script("theme/vendors/validator/validator_form.js") !!}

@endsection

@section('scripts')
    <script type="module">

            $(document).ready(function() {

                var academic_year = $("#acyear").val();
                //alert(academic_year);

                let element = "";

                GeneralConfig.generalinit(notify_script);
                GeneralConfig.getInfoacademic(0, academic_year, element, "academic year");

        });

         function notify_script(title, text, type, hide) {
            new PNotify({
                title: title,
                text: text,
                type: type,
                hide: hide,
                styling: 'fontawesome'
            })
        }
      
        window.getacademicinfo = "{{ route('academictermdetails') }}"
        //Academic Year
        GeneralConfiggeneralinit(notify_script);        
    </script>

    <script>
        window.onload = function() {
            // Code to be executed when the entire page has finished loading
            $(".subject_div").hide();
        };
     </script>
@endsection