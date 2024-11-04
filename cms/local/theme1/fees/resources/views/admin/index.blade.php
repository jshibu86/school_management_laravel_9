@extends('layout::admin.master')

@section('title','fees')
@section('style')
    <!-- Datatables -->
    @include('layout::admin.head.list_head')
    <style>
        .table-div table {
            width: 100% !important;
        }
        .fee_reminder_div{
            float:inline-end;
        }
        .nav_div{
            width:90%;
        }
    </style>
@endsection
@section('body')
<div class="card">
    <div class="card-body">
        <div class="card-title btn_style">
            <h4 class="mb-0">View Fee Payment</h4>
            @if(CGate::allows('create-fees'))
            <a class="btn btn-primary btn-sm m-1  px-3" href="{{route('fees.create')}}" ><i class='fa fa-plus'></i>&nbsp;&nbsp;{{  Session::get("ACTIVE_GROUP") == "Student" ? "Pay Fee" : "Create" }}</a>
            @endif
          <input type="hidden" class="active_group" value = {{Session::get("ACTIVE_GROUP")}}>
        </div> 
        <hr/>
        <div class="d-flex mb-4">
          
            <div class="nav_div pills_div">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation"> <a class="nav-link active" id="pills-paid-tab" data-bs-toggle="pill"
                            href="#pills-paid" role="tab" aria-controls="pills-paid" aria-selected="true">Fees Paid</a>
                    </li>
                    <li class="nav-item" role="presentation"> <a class="nav-link" id="pills-unpaid-tab" data-bs-toggle="pill"
                            href="#pills-paid" role="tab" aria-controls="pills-paid" aria-selected="false">Fees Unpaid</a>
                    </li>
        
                </ul>
            </div>
          
            <div class="fee_reminder_div w-100">
                <button type="button" class="btn btn-warning" id="fee_reminder" style="float:right;">Fee Reminder</button>
            </div>
            @php
            $display = ($btn_exist == 1) ? "" : "none";
            @endphp
            <div class="w-100 print_btn_div">
                <button type="button" class="btn btn-primary print_btn" style="float:right;display:{{ $display }}">BulkPrint</button>
            </div>
        
           
        </div>
       
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade active show" id="pills-paid" role="tabpanel" aria-labelledby="pills-paid-tab">
               
                <div class="row paid_row">

                    <div class="col-xs-12 col-sm-4 col-md-2">
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

                    <div class="col-xs-12 col-sm-4 col-md-2">
                        <div class="item form-group">
                            <label class="control-label margin__bottom" for="status">Fee Structure<span class="required">*</span>
                            </label>
                            <div class="feild">
                                <div class="feild">
                                    {{ Form::select('payment_type',$payment_types,@$type ,
                                    array('id'=>'payment_type','class' => 'single-select form-control','required' => 'required',"placeholder"=>"select period type" )) }}
                                </div>
                            </div>
                        </div>
                           
                    </div>
        
                                                                
                    <div class="col-xs-12 col-sm-4 col-md-2 monthly">
                        <div class="item form-group">
                            <label class="control-label margin__bottom" for="status">Select Month <span class="required">*</span>
                            </label>
                            <div class="feild">
                                <div class="feild">
                                    {{ Form::text('month',@$current_month_year ,
                                    array('id'=>'month','class' => ' form-control month-picker month',"placeholder"=>"select month" )) }}
                                </div>
                            </div>
                        </div>
                           
                    </div>

                    <div class="col-md-2 academic_term">
                        <div class="item form-group">
                            <label for="exam_term" class="mb-2">Academic Term <span>*</span></label>


                            {{ Form::select('academic_term', @$examterms, @$data->term_id ? @$data->term_id : @$current_academic_term, [
                                'id' => 'examterm',
                                'class' => 'single-select form-control',
                                'required' => 'required',
                                'placeholder' => 'Select Exam Term',
                                @$layout == 'edit' ? 'disabled' : '',
                            ]) }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-2">
                        <div class="item form-group">
                            <label class="control-label margin__bottom" for="status">School Type <span
                                    class="required">*</span>
                            </label>
                            <div class="feild">

                                {{ Form::select(
                                    'school_type',
                                    @$school_type_info,
                                    @$data->school_type_info ? @$data->school_type_info : @$school_type_infos,
                                    [
                                        'id' => 'school_type_grade',
                                        'class' => @$layout == 'edit' ? ' form-control' : 'single-select form-control',
                                        'required' => 'required',
                                        'placeholder' => 'Select School Type',
                                        @$layout == 'edit' ? 'disabled' : '',
                                    ],
                                ) }}
                            </div>
                        </div>

                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-2">
                        <div class="item form-group">
                            <label for="class_id" class="mb-2">Class <span>*</span></label>
                            {{ Form::select('class_id', @$class_lists, @$data->class_id, [
                                'id' => 'class_id_grade',
                                'class' => @$layout == 'edit' ? ' form-control' : 'single-select form-control',
                                'required' => 'required',
                                'placeholder' => 'Select CLass',
                                @$layout == 'edit' ? 'disabled' : '',
                            ]) }}

                        </div>

                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-2">
                        <div class="item form-group">
                            <label for="sec_dep" class="mb-2">Sec/Dep <span>*</span></label>
                            {{ Form::select('sec_dep', @$section_lists, @$data->class_id, [
                                'id' => 'sec_dep',
                                'class' => @$layout == 'edit' ? ' form-control' : 'single-select form-control',
                                'required' => 'required',
                                'placeholder' => 'Select CLass',
                                @$layout == 'edit' ? 'disabled' : '',
                            ]) }}

                        </div>

                    </div>
                     
                    
                    
                       
                            <div class="col-md-3">
                           
                                <button type="button" class="btn btn-primary fees_paid_report form-control" style="margin-top: 30px;"> <i
                                        class="fa fa-plus" name="daily"></i>&nbsp;&nbsp;Get Report</button>
                                <button type="button" class="btn btn-primary fees_unpaid_report form-control" style="margin-top: 30px;"> <i
                                        class="fa fa-plus" name="daily"></i>&nbsp;&nbsp;Get Report</button>        
                            </div>
                        
                           
                </div>
                <div class="row unpaid_row">

                    <div class="col-xs-12 col-sm-4 col-md-2">
                        <div class="item form-group">
                            <label class="control-label margin__bottom" for="status">Academic Year <span
                                    class="required">*</span>
                            </label>
                            <div class="feild">
                                {{ Form::select('academic_year_grade', @$academicyears, Configurations::getCurrentAcademicyear(), [
                                    'id' => 'academic1',
                                    'class' => @$layout == 'edit' ? ' form-control' : 'single-select form-control',
                                    'required' => 'required',
                                    'placeholder' => 'Select Academic year',
                                    @$layout == 'edit' ? 'disabled' : '',
                                ]) }}
                            </div>
                        </div>

                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-2">
                        <div class="item form-group">
                            <label class="control-label margin__bottom" for="status">Fee Structure<span class="required">*</span>
                            </label>
                            <div class="feild">
                                <div class="feild">
                                    {{ Form::select('payment_type',$payment_types,@$type ,
                                    array('id'=>'payment_type1','class' => 'single-select form-control','required' => 'required',"placeholder"=>"select period type" )) }}
                                </div>
                            </div>
                        </div>
                           
                    </div>
        
                                                                
                    <div class="col-xs-12 col-sm-4 col-md-2 monthly">
                        <div class="item form-group">
                            <label class="control-label margin__bottom" for="status">Select Month <span class="required">*</span>
                            </label>
                            <div class="feild">
                                <div class="feild">
                                    {{ Form::text('month',@$current_month_year,
                                    array('id'=>'month1','class' => ' form-control month-picker month',"placeholder"=>"select month" )) }}
                                </div>
                            </div>
                        </div>
                           
                    </div>

                    <div class="col-md-2 academic_term">
                        <div class="item form-group">
                            <label for="exam_term" class="mb-2">Academic Term <span>*</span></label>


                            {{ Form::select('academic_term', @$examterms, @$data->term_id ? @$data->term_id : @$current_academic_term, [
                                'id' => 'examterm1',
                                'class' => 'single-select form-control',
                                'required' => 'required',
                                'placeholder' => 'Select Exam Term',
                                @$layout == 'edit' ? 'disabled' : '',
                            ]) }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-2">
                        <div class="item form-group">
                            <label class="control-label margin__bottom" for="status">School Type <span
                                    class="required">*</span>
                            </label>
                            <div class="feild">

                                {{ Form::select(
                                    'school_type',
                                    @$school_type_info,
                                    @$data->school_type_info ? @$data->school_type_info : @$school_type_infos,
                                    [
                                        'id' => 'school_type_grade1',
                                        'class' => @$layout == 'edit' ? ' form-control' : 'single-select form-control',
                                        'required' => 'required',
                                        'placeholder' => 'Select School Type',
                                        @$layout == 'edit' ? 'disabled' : '',
                                    ],
                                ) }}
                            </div>
                        </div>

                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-2">
                        <div class="item form-group">
                            <label for="class_id" class="mb-2">Class <span>*</span></label>
                            {{ Form::select('class_id', @$class_lists, @$data->class_id, [
                                'id' => 'class_id_grade1',
                                'class' => @$layout == 'edit' ? ' form-control' : 'single-select form-control',
                                'required' => 'required',
                                'placeholder' => 'Select CLass',
                                @$layout == 'edit' ? 'disabled' : '',
                            ]) }}

                        </div>

                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-2">
                        <div class="item form-group">
                            <label for="sec_dep" class="mb-2">Sec/Dep <span>*</span></label>
                            {{ Form::select('sec_dep', @$section_lists, @$data->class_id, [
                                'id' => 'sec_dep1',
                                'class' => @$layout == 'edit' ? ' form-control' : 'single-select form-control',
                                'required' => 'required',
                                'placeholder' => 'Select CLass',
                                @$layout == 'edit' ? 'disabled' : '',
                            ]) }}

                        </div>

                    </div>
                     
                    
                    
                       
                            <div class="col-md-3">
                           
                                <button type="button" class="btn btn-primary fees_paid_report form-control" style="margin-top: 30px;"> <i
                                        class="fa fa-plus" name="daily"></i>&nbsp;&nbsp;Get Report</button>
                                <button type="button" class="btn btn-primary fees_unpaid_report form-control" style="margin-top: 30px;"> <i
                                        class="fa fa-plus" name="daily"></i>&nbsp;&nbsp;Get Report</button>        
                            </div>
                        
                           
                </div>
                <div class="table-responsive table_paid pt-5">               
                        <table id="datatable-buttons1" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Student Name</th>
                                    <th>Reg No</th>
                                    <th>Fee Academic Year</th>
                                    <th>Paid Date</th>
                                    <th>Paid Amount</th>
                                    <th class="noExport">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        
                        </table>                              
                </div>
                <div class="table-responsive table_unpaid mt-5">
                    <table id="datatable-buttons2" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Student Name</th>
                                <th>Reg No</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th class="noExport">Action</th>
                            </tr>
                        </thead>
                       
                    
                    </table>
                </div>
           </div>
           {{-- <div class="tab-pane fade " id="pills-unpaid" role="tabpanel" aria-labelledby="pills-unpaid-tab">
               
                <div class="row">

                            <div class="col-xs-12 col-sm-4 col-md-2">
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

                            <div class="col-xs-12 col-sm-4 col-md-2">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="status">Fee Structure<span class="required">*</span>
                                    </label>
                                    <div class="feild">
                                        <div class="feild">
                                            {{ Form::select('payment_type',Configurations::FEEPAYMENTTYPES,@$type ,
                                            array('id'=>'payment_type','class' => 'single-select form-control','required' => 'required',"placeholder"=>"select period type" )) }}
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                
                                                                        
                            <div class="col-xs-12 col-sm-4 col-md-2 monthly">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="status">Select Month <span class="required">*</span>
                                    </label>
                                    <div class="feild">
                                        <div class="feild">
                                            {{ Form::text('month',@$month ,
                                            array('id'=>'month','class' => ' form-control month-picker month',"placeholder"=>"select month" )) }}
                                        </div>
                                    </div>
                                </div>
                                
                            </div>

                            <div class="col-md-2 acdemic_year">
                                <div class="item form-group">
                                    <label for="exam_term" class="mb-2">Academic Term <span>*</span></label>


                                    {{ Form::select('academic_term', @$examterms, @$data->term_id ? @$data->term_id : @$current_academic_term, [
                                        'id' => 'examterm',
                                        'class' => 'single-select form-control',
                                        'required' => 'required',
                                        'placeholder' => 'Select Exam Term',
                                        @$layout == 'edit' ? 'disabled' : '',
                                    ]) }}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-2">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="status">School Type <span
                                            class="required">*</span>
                                    </label>
                                    <div class="feild">

                                        {{ Form::select(
                                            'school_type',
                                            @$school_type_info,
                                            @$data->school_type_info ? @$data->school_type_info : @$school_type_infos,
                                            [
                                                'id' => 'school_type_grade',
                                                'class' => @$layout == 'edit' ? ' form-control' : 'single-select form-control',
                                                'required' => 'required',
                                                'placeholder' => 'Select School Type',
                                                @$layout == 'edit' ? 'disabled' : '',
                                            ],
                                        ) }}
                                    </div>
                                </div>

                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-2">
                                <div class="item form-group">
                                    <label for="class_id" class="mb-2">Class <span>*</span></label>
                                    {{ Form::select('class_id', @$class_lists, @$data->class_id, [
                                        'id' => 'class_id_grade',
                                        'class' => @$layout == 'edit' ? ' form-control' : 'single-select form-control',
                                        'required' => 'required',
                                        'placeholder' => 'Select CLass',
                                        @$layout == 'edit' ? 'disabled' : '',
                                    ]) }}

                                </div>

                            </div>

                            <div class="col-xs-12 col-sm-4 col-md-2">
                                <div class="item form-group">
                                    <label for="sec_dep" class="mb-2">Sec/Dep <span>*</span></label>
                                    {{ Form::select('sec_dep', @$section_lists, @$data->class_id, [
                                        'id' => 'sec_dep',
                                        'class' => @$layout == 'edit' ? ' form-control' : 'single-select form-control',
                                        'required' => 'required',
                                        'placeholder' => 'Select CLass',
                                        @$layout == 'edit' ? 'disabled' : '',
                                    ]) }}

                                </div>

                            </div>
                            
                    
                    
                    
                            <div class="col-md-3">
                        
                                <button type="button" class="btn btn-primary fees_unpaid_report form-control" style="margin-top: 30px;"> <i
                                        class="fa fa-plus" name="daily"></i>&nbsp;&nbsp;Get Report</button>
                            </div>
                        
                        
                </div>
                <div class="table-responsive table_unpaid mt-5">
                    <table id="datatable-buttons2" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Student Name</th>
                                <th>Reg No</th>
                                <th>Fee Academic Year</th>
                                <th>Paid Date</th>
                                <th>Paid Amount</th>
                                <th class="noExport">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    
                    </table>
                </div>
          </div> --}}
        </div>   
    </div>
</div>

<div class="modal fade" id="view__report" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered form">

        <div class="modal-content">

            <div class="modal-body assigen_parent_body">

                    <div class="homework_details position-relative">
                        some

                    </div>
                    <div class="modal-footer position-absolute top-0 end-0">
                        @if (Session::get('ACTIVE_GROUP') == 'Super Admin')
                            {{-- <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Submit</button> --}}
                        @endif
                        {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> --}}
                        <i class="fa fa-times-circle fs-2" style="color: red" data-bs-dismiss="modal"
                            aria-hidden="true"></i>


                    </div>
            </div>




        </div>
    </div>
</div>    
<div class="modal fade" id="view_student" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered form">

        <div class="modal-content">

            <div class="modal-body assigen_parent_body">

                    <div class="student_details position-relative">
                        some  
                        </div>

                    </div>
                    <div class="modal-footer position-absolute top-0 end-0">
                        @if (Session::get('ACTIVE_GROUP') == 'Super Admin')
                            {{-- <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Submit</button> --}}
                        @endif
                        {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> --}}
                        <i class="fa fa-times-circle fs-2" style="color: red" data-bs-dismiss="modal"
                            aria-hidden="true"></i>


                    </div>
            </div>




        </div>
    </div>
</div>
@endsection
@section('script')
    <script>
     window.statuschange='{{route('fees_action_from_admin')}}';
        $('document').ready(function(){

            var element = $("#datatable-buttons1");
            var url =  '{{route('get_fees_data_from_admin')}}';
            var column = [
              
                {data: 'DT_RowIndex', name: 'DT_RowIndex',searchable: false,sortable: false},
                { data: 'full_name', name: 'students.last_name', width: '15%' },
                { data: 'reg_no', name: 'students.reg_no', width: '15%' },
                { data: 'year', name: 'academic_year', width: '15%' },
                { data: 'payment_date', name: 'payment_date', width: '15%' },
                { data: 'paid_amount', name: 'paid_amount',className: 'paid_amount', width: '15%' },
               
                 
                { data: 'action', name: 'id', searchable: false, sortable: false, className: 'textcenter'}
            ];
            var csrf = '{{ csrf_token() }}';

            var options  = {
                //order : [ [ 6, "desc" ] ],
                lengthMenu: [[15,25,50, 100 ,250, 500, -1], [15,25,50, 100 ,250, 500,"ALL"]],
                button : [
                    {
                        name : "Publish" ,
                        url : "{{route('fees_action_from_admin',1)}}"
                    },
                    {
                        name : "Un Publish",
                        url : "{{route('fees_action_from_admin',0)}}"
                    },
                    {
                        name : "Trash",
                        url : "{{route('fees_action_from_admin',-1)}}"
                    },
                    {
                        name : "Delete",
                        url : "{{route('fees.destroy',1)}}",
                        method : "DELETE"
                    }
                ],

            }


            dataTable(element,url,column,csrf,options);

        });
    </script>
    <script>
        window.statuschange='{{route('fees_action_from_admin')}}';
           $('document').ready(function(){
   
               var element = $("#datatable-buttons2");
               var url =  '{{route('get_fees_unpaid_data_from_admin')}}';
               var column = [
                 
                   {data: 'DT_RowIndex', name: 'DT_RowIndex',searchable: false,sortable: false},
                   { data: 'first_name', name: 'data.first_name',className:'name' },
                   { data: 'reg_no', name: 'data.reg_no',className:'reg_no' },
                   { data: 'unpaid_amount', name: 'unpaid_amount',className:'unpaid_amount'},
                   { data: 'status', name: 'status' },
                    
                   { data: 'action', name: 'id', searchable: false, sortable: false, className: 'textcenter'}
               ];
               var csrf = '{{ csrf_token() }}';
   
               var options  = {
                   //order : [ [ 6, "desc" ] ],
                   lengthMenu: [[15,25,50, 100 ,250, 500, -1], [15,25,50, 100 ,250, 500,"ALL"]],
                   button : [
                       {
                           name : "Publish" ,
                           url : "{{route('fees_action_from_admin',1)}}"
                       },
                       {
                           name : "Un Publish",
                           url : "{{route('fees_action_from_admin',0)}}"
                       },
                       {
                           name : "Trash",
                           url : "{{route('fees_action_from_admin',-1)}}"
                       },
                       {
                           name : "Delete",
                           url : "{{route('fees.destroy',1)}}",
                           method : "DELETE"
                       }
                   ],
   
               }
   
   
               dataTable(element,url,column,csrf,options);
           
            });
       </script>
    
    <script>
   
        $(document).ready(function(){
            $('.fees_unpaid_report').hide();
            $('.unpaid_row').hide();
            $('.table_unpaid').hide();
            $('#fee_reminder').hide();
            let active_group = $('.active_group').val();
            let type = $('select[name="payment_type"]').val();
            if (type == "0") {
                $(".monthly").show();
                $(".academic_term").hide();                            
            } else if (type == "1") {
                $(".academic_term").show();
                $(".monthly").hide();
            } else {
                $(".academic_term").hide();
                $(".monthly").hide();
            }
            if(active_group == "Student"){
                $('.fees_unpaid_report').hide();
                $('.unpaid_row').hide();
                $('.table_unpaid').hide();
                $('#fee_reminder').hide();
                $('.paid_row').hide();
                $('#pills-unpaid-tab').hide();
                $("#pills-paid-tab").css("cursor", "default");
            }
            
          
        });
    </script>
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
        window.fees_paid_report = "{{route('fees_payment')}}";
        window.fees_reminder = "{{route('fees_reminder')}}";
       
        AttendanceConfig.AttendanceInit(notify_script);
        AcademicConfig.Leaveinit(notify_script);
        //grade -- Class,Section List
        PromotionConfig.PromotionInit(notify_script);
        //ReportConfig.ReportInit(notify_script);
        FeeStructureConfig.FeeStructureInit(notify_script);
        //grade chart
        Account.AccountInit();
       
        // window.student_overall_report_info = "{{ route('grade_student_report_view') }}"
        // ReportConfig.getStudentsMarkinfo(id,academic_year,position,term);
    </script>
    <script>
        $(document).ready(function() {
            console.log('Document is ready');
        $(document).on('click', '.view_fees_unpaid', function() {
            console.log('Button clicked');

            // Extract necessary data from the clicked element
            let student_id = $(this).data('student-id');
            let unpaid_start = $(this).data('unpaid-id') ?? 0;
            let url;

            // Construct the URL based on the extracted data
            if (unpaid_start == 1) {
                var row = $(this).closest('tr');
            
                let name = row.find('.name').html();
                let reg_no = row.find('.reg_no').html();
                let unpaid_amount = row.find('.unpaid_amount').html();
                let total_amount = $(this).data('total-amount');
                let payment_type = $(this).data('payment-type');
                url = window.fees_reminder + "?student_id=" + student_id + "&name=" + name + "&reg_no=" + reg_no + "&unpaid_amount=" + unpaid_amount +
                    "&total_amount=" + total_amount + "&payment_type=" + payment_type + "&unpaid_start=" + unpaid_start + "&type=" + "1";
            } else {
                let name = $('.student_name' + student_id).val();
                let reg_no = $('.student_reg_no' + student_id).val();
                let unpaid_amount = $('.unpaid_amount' + student_id).val();
                let payment_type = $('.payment_type').val();
                let monthly_amount = $('.monthly_amount' + student_id).val() ?? 0;
                let term_amount = $('.term_amount' + student_id).val() ?? 0;
                let one_pay_amount = $('.one_pay_amount' + student_id).val() ?? 0;
                let scholarship = $('.student_scholarship' + student_id).val();
                let total_amount = $('.total_amount').val();

                url = window.fees_reminder + "?student_id=" + student_id + "&name=" + name + "&reg_no=" + reg_no + "&payment_type=" + payment_type +
                    "&unpaid_amount=" + unpaid_amount + "&one_pay_amount=" + one_pay_amount + "&monthly_amount=" + monthly_amount + "&term_amount=" + term_amount + "&payment_type=" + payment_type +
                    "&scholarship=" + scholarship + "&total_amount=" + total_amount + "&type=" + "1" + "&unpaid_start=" + unpaid_start;
            }

            // Perform AJAX request if the URL is valid
            if (url) {
                axios
                    .get(url)
                    .then((response) => {
                        console.log(response);
                        if (response.data.view) {
                            $(".homework_details").empty();
                            $(".homework_details").html(response.data.view);
                            $("#view__report").modal("show");
                        } else {
                            console.error("Invalid response data:", response);
                            // Handle the case where viewfile is not present in the response data
                        }
                    })
                    .catch((error) => {
                        console.error("Error fetching student report:", error);
                        // Handle AJAX error gracefully, e.g., display an error message to the user
                    });
            } else {
                console.error("Invalid URL:", url);
            }
        });
        });

    </script>
    <script>
         window.printurl = '{{ route('bulk_print') }}';
        $('.print_btn').on('click',function(){
            console.log("its enter");
            let academic_year = $('#academic').val();
            let class_id = $('#class_id_grade').val();
            let section = $('#sec_dep').val();
            let paid_amount = []
            let student_id = []
            let url = window.printurl;
            $('.print_url').each(function() {
                paid_amount.push($(this).attr('data-paid-amount')); 
                student_id.push($(this).attr('data-student'));
            });
           
            const form = document.createElement('form');
    form.method = 'POST';
    form.action = url;
    form.target = '_blank'; // Open in a new tab

    // Add CSRF token
    const token = document.createElement('input');
    token.type = 'hidden';
    token.name = '_token';
    token.value = '{{ csrf_token() }}';
    form.appendChild(token);

    // Add other form fields
    const addField = (name, value) => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = value;
        form.appendChild(input);
    };

    addField('academic_year', academic_year);
    addField('class_id', class_id);
    addField('section', section);
    paid_amount.forEach((amount, index) => addField('paid_amount[]', amount));
    student_id.forEach((id, index) => addField('student_id[]', id));

    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
        });
    </script>
  

@endsection
