@extends('layout::admin.master')

@section('title','account')
@section('style')
 @include('layout::admin.head.list_head')
<style>
    /* .pagination{
        margin-top: 34px!important;
    } */

    #datatable-buttons1_wrapper{
        margin-top: 20px !important;
    }
</style>
@endsection
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('IncomeExpenseReportView'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'account-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('IncomeExpenseReportView',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
        <div class="box-header with-border mar-bottom20">
           
            <input type="hidden" name="fee_type" value="{{@$feetype}}"/>
           

           

             <a class="btn btn-info btn-sm m-1  px-3" href="{{route('IncomeExpenseCollectionReport',['type'=>@$type])}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

              {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }}

              

             
        </div>
           @include("layout::admin.breadcrump",['route'=> $type == "income" ?"Income Report" : "Expense Report"])

             <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{@$type=="income" ? "Income" : "Expense"}} Report</h5>
                    <hr/>
                    <div class="col-xs-12">
                        <div class="row">

                        <div class="col-xs-12 col-md-3">
                          <div class="item form-group">
                           <label class="control-label margin__bottom" for="status">Academic Year<span class="required">*</span>
                                </label>
                                <div class="feild">
                                   {{ Form::select('academic_year',@$academic_years,@$current_academic_year ,
                                    array('id'=>'status_','class' => 'single-select form-control ','required' => 'required','placeholder'=>"Select Acdemic year")) }}
                                </div>
                          </div>
                               
                        </div>
                        @if (@$type =="income" && @$feetype==1)
                            
                        {{-- <div class="col-xs-12 col-md-3">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="status">School Type<span class="required">*</span>
                                    </label>
                                    <div class="feild">
                                                        
                                        {{ Form::select('school_type',@$school_type,@$school_type_id,
                                        array('id'=>'school_type','class' => @$layout =="edit" ? " form-control " : 
                                        "single-select form-control ",'required' => 'required','placeholder'=>"Select School Type",@$layout =="edit"? "disabled" : "")) }}
                                    </div>
                                </div>
                                                
                        </div> --}}
                        

                        <div class="col-xs-12 col-md-3">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="status">Select Class<span class="required">*</span>
                                    </label>
                                    <div class="feild">
                                                        
                                        {{ Form::select('class_id',@$class_lists,@$class_id,
                                        array('id'=>'class_id','class' => @$layout =="edit" ? " form-control " : 
                                        "single-select form-control ",'required' => 'required','placeholder'=>"Select Class",@$layout =="edit"? "disabled" : "")) }}
                                    </div>
                                </div>
                                                
                        </div>

                        <div class="col-xs-12 col-md-3">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="status">Select Section<span class="required">*</span>
                                    </label>
                                    <div class="feild">
                                                    
                                     {{ Form::select('section_id',@$sections,@$section_id,
                                      array('id'=>'section_id','class' => @$layout =="edit" ? " form-control " : 
                                       "single-select form-control ",'required' => 'required','placeholder'=>"Select Section",@$layout =="edit"? "disabled" : "")) }}
                                    </div>
                                </div>
                                                
                        </div>
                        @endif
                        <div class="col-xs-12 col-md-3">
                         <div class="item form-group">
                                <label class="control-label margin__bottom" for="status">Date From <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{Form::text('date_from',@$date_from,array('id'=>"datefrom",'class'=>"datepickerwithoutselected form-control col-md-7 col-xs-12" ,
                                   'placeholder'=>"Date From",))}}
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3">
                         <div class="item form-group">
                                <label class="control-label margin__bottom" for="status">Date Upto <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{Form::text('date_to',@$date_to,array('id'=>"dateto",'class'=>"datepickerwithoutselected form-control col-md-7 col-xs-12" ,
                                   'placeholder'=>"Date From",))}}
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <input type="hidden" name="type" value="{{@$type}}"/>
                         <div class="item form-group">
                                <button type="submit" class="btn btn-primary" style="margin-top: 29px">Get Data</button>
                            </div> 
                        </div>
                        </div>
                    </div>

                    <hr/>
                    <div class="table-responsive">
                        @if (@$type=="income" && @$feetype == 1)
                            
                        
                            <table id="datatable-buttons1" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Bill Number</th>
                                        <th>Student Name</th>
                                        <th>Reg No</th>
                                        <th>Fee Academic Year</th>
                                        <th>Class/Section</th>
                                        <th>Paid Date</th>
                                        <th>Paid Amount {{Configurations::GetConfig("site")->currency_symbol}}</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                

                                    @foreach (@$fees as $fee )
                                        <tr>
                                            <td>{{$loop->index+1}}</td>
                                            <td>{{$fee->bill_no}}</td>
                                            <td>{{$fee->student->first_name}} {{$fee->student->last_name}}</td>
                                            <td>{{$fee->student->reg_no}}</td>
                                            <td>{{$fee->academicyear->year}}</td>
                                            <td>{{$fee->classinfo->name}} / {{$fee->section->name}}</td>
                                            <td>{{$fee->payment_date}}</td>
                                            <td>{{$fee->paid_amount}}</td>
                                        </tr>
                                    @endforeach
                                        
                                
                                </tbody>
                            
                            </table>


                        @elseif (@$type=="income" && @$feetype == 2)
                             <table id="datatable-buttons1" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Academic Year</th>
                                        <th>Income Title</th>
                                        <th>Category</th>
                                        <th>Paid Date</th>
                                        
                                        <th>Paid Amount {{Configurations::GetConfig("site")->currency_symbol}}</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                

                                    @foreach (@$manual_income as $data )
                                        <tr>
                                            <td>{{$loop->index+1}}</td>
                                            <td>{{$data->academicyear->year}}</td>
                                            <td>{{$data->title}}</td>
                                            <td>{{$data->category->category_name}}</td>
                                            <td>{{$data->entry_date}}</td>
                                        
                                        
                                            <td>{{$data->amount}}</td>
                                        </tr>
                                    @endforeach
                                        
                                
                                </tbody>
                        
                            </table>


                        @elseif (@$type=="income" && @$feetype==3)
                          <table id="datatable-buttons1" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Order Number</th>
                                        <th>Student Name</th>
                                        <th>Payment Type</th>
                                        <th>Order Date</th>
                                        
                                        <th>Order Amount {{Configurations::GetConfig("site")->currency_symbol}}</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                

                                    @foreach (@$tuck_shop as $shop )
                                        <tr>
                                            <td>{{$loop->index+1}}</td>
                                            <td>{{$shop->order_number}}</td>
                                            <td>{{$shop->student->first_name}} {{$shop->student->last_name}}</td>
                                            <td>{{$shop->payment_type}}</td>
                                            <td>{{$shop->order_date}}</td>
                                        
                                        
                                            <td>{{$shop->order_amount}}</td>
                                        </tr>
                                    @endforeach
                                        
                                
                                </tbody>
                        
                            </table>
                        @else

                           
                        @endif


                        @if (@$type=="expense" && @$feetype == 1)
                              <table id="datatable-buttons1" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>User Name</th>
                                        <th>Academic Year</th>
                                       
                                        <th>Paid Date</th>
                                        <th>Paid Amount {{Configurations::GetConfig("site")->currency_symbol}}</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                

                                    @foreach (@$payroll_data as $pay )
                                        <tr>
                                            <td>{{$loop->index+1}}</td>
                                            <td>{{$pay->user->name}}</td>
                                            <td>{{$pay->academicyear->year}}</td>
                                          
                                            <td>{{$pay->payment_date}}</td>
                                            <td>{{$pay->basic_salery}}</td>
                                        </tr>
                                    @endforeach
                                        
                                
                                </tbody>
                            
                            </table>

                        @elseif (@$type=="expense" && @$feetype == 2)
                         <table id="datatable-buttons1" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Academic Year</th>
                                        <th>Expense Title</th>
                                        <th>Category</th>
                                        <th>Paid Date</th>
                                        
                                        <th>Paid Amount {{Configurations::GetConfig("site")->currency_symbol}}</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                

                                    @foreach (@$manual_expense as $data )
                                        <tr>
                                            <td>{{$loop->index+1}}</td>
                                            <td>{{$data->academicyear->year}}</td>
                                            <td>{{$data->title}}</td>
                                            <td>{{$data->category->category_name}}</td>
                                            <td>{{$data->entry_date}}</td>
                                        
                                        
                                            <td>{{$data->amount}}</td>
                                        </tr>
                                    @endforeach
                                        
                                
                                </tbody>
                        
                            </table>
                        @endif
                    </div>
                </div>
            </div>

        
       
       

        {{Form::close()}}
    </div>

@endsection

@section('script_link')

    <!-- validator -->
    {!! Cms::script("theme/vendors/validator/validator.js") !!}
    {!! Cms::script("theme/vendors/validator/validator_form.js") !!}

@endsection

@section("scripts")
<script type="module">
    $('#datatable-buttons1').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    } );
       
        function notify_script(title,text,type,hide) {
            new PNotify({
                title: title,
                text: text,
                type: type,
                hide: hide,
                styling: 'bootstrap3'
            })
        }
       
        window.sectionurl="{{ route('section.index') }}";
        window.classurl="{{route('schooltype.index')}}";
       
       AcademicConfig.ClassInit(notify_script);
       
</script>
@endsection
