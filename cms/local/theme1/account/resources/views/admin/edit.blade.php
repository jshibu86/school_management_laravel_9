@extends('layout::admin.master')

@section('title','account')
@section('style')
 @include('layout::admin.head.list_head')
<style>
    .pagination{
        margin-top: 34px!important;
    }
</style>
@endsection
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('IncomeExpenseCollectionReport'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'account-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('IncomeExpenseCollectionReport',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
        <div class="box-header with-border mar-bottom20">
           
            
           

           

             <a class="btn btn-info btn-sm m-1  px-3" href="{{route('account.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

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
                        <div class="col-xs-12 col-md-3">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="status">School Type<span class="required">*</span>
                                    </label>
                                    <div class="feild">
                                                        
                                        {{ Form::select('school_type',@$school_type,@$school_type_id,
                                        array('id'=>'school_type','class' => @$layout =="edit" ? " form-control " : 
                                        "single-select form-control ",'required' => 'required','placeholder'=>"Select School Type",@$layout =="edit"? "disabled" : "")) }}
                                    </div>
                                </div>
                                                
                        </div>
                        
                        @if (@$type =="income")
                            
                        
                        

                        {{-- <div class="col-xs-12 col-md-3">
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
                                                
                        </div> --}}
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
                        @if (@$type=="income")
                            
                        
                        <table id="datatable-buttons1" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Academic Year</th>
                                    <th>Income Title</th>
                                    <th>Date from</th>
                                    <th>Date To</th>
                                    
                                    
                                    <th>Amount {{Configurations::GetConfig("site")->currency_symbol}}</th>
                                    <th>Action</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                               

                                @foreach (@$incomeexpensecollection as $income )
                                    <tr>
                                        <td>{{$loop->index+1}}</td>
                                        <td>{{$academic_info}}</td>
                                        <td>{{$income->name}}</td>
                                        <td>{{$income->date_from}}</td>
                                        <td>{{$income->date_to}}</td>
                                      
                                        <td>{{$income->total_amount}}</td>
                                        <td>
                                            <a href="{{route("IncomeExpenseReportView",['type'=>@$type,"academic_year"=>$current_academic_year,"date_from"=>$date_from,"date_to"=>$date_to,'fee_type'=>$loop->index+1])}}">
                                            <i class="fa fa-eye"> </i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                    
                               
                            </tbody>
                        
                        </table>
                        @else

                         <table id="datatable-buttons1" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Academic Year</th>
                                    <th>Expense Title</th>
                                    <th>Date from</th>
                                    <th>Date To</th>
                                    
                                    
                                    <th>Amount {{Configurations::GetConfig("site")->currency_symbol}}</th>
                                    <th>Action</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                               

                                @foreach (@$incomeexpensecollection as $income )
                                    <tr>
                                        <td>{{$loop->index+1}}</td>
                                        <td>{{$academic_info}}</td>
                                        <td>{{$income->name}}</td>
                                        <td>{{$income->date_from}}</td>
                                        <td>{{$income->date_to}}</td>
                                      
                                        <td>{{$income->total_amount}}</td>
                                        <td>
                                            <a href="{{route("IncomeExpenseReportView",['type'=>@$type,"academic_year"=>$current_academic_year,"date_from"=>$date_from,"date_to"=>$date_to,'fee_type'=>$loop->index+1])}}">
                                            <i class="fa fa-eye"> </i>
                                            </a>
                                        </td>
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
        $("#datatable-buttons1").dataTable();
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
       
       AcademicConfig.ClassInit(notify_script);
       
</script>
@endsection
