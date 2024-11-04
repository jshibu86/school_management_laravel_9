@extends('layout::admin.master')

@section('title','purchase')
@section('style')
    <!-- Datatables -->
    @include('layout::admin.head.list_head')
    <style>
        .table-div table {
            width: 100% !important;
        }
     
    </style>
@endsection
@section('body')
{{ Form::open(array('role' => 'form', 'route'=>array('getreportdata'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'academicyear-form')) }}
<div class="card">
    <div class="card-body">
        <div class="card-title btn_style">
            <h4 class="mb-0">Purchase Report</h4>
           
            <div class="butns">
                {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Get Report', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_academicyear' , 'class' => 'btn btn-success btn-sm m-1  px-3')) }}
                
                <a class="btn btn-primary btn-sm m-1  px-3" href="{{route('purchase.index')}}" ><i class='fa fa-arrow-left'></i>&nbsp;&nbsp;Back</a>
            </div>
           
            
          
        </div>
        <hr/>

        <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-3">
                <div class="item form-group">
                    <label class="control-label margin__bottom" for="status">Select Report Type <span class="required">*</span>
                    </label>
                    <div class="feild">
                        <div class="feild">
                            {{ Form::select('report_type',Configurations::REPORTTYPE,@$type ,
                            array('id'=>'report_type','class' => 'single-select form-control','required' => 'required',"placeholder"=>"select report type" )) }}
                        </div>
                    </div>
                </div>
                   
            </div>

            <div class="col-xs-12 col-sm-4 col-md-3 weekly" style="display: none;">
                <div class="item form-group">
                    <label class="control-label margin__bottom" for="status">Start Date <span class="required">*</span>
                    </label>
                    <div class="feild">
                        <div class="feild">
                            {{ Form::text('start_date',@$start_date ,
                            array('id'=>'start_date','class' => ' form-control datepicker_academic_start startdate',"placeholder"=>"select start date" )) }}
                        </div>
                    </div>
                </div>
                   
            </div>
            <div class="col-xs-12 col-sm-4 col-md-3 weekly" style="display: none;">
                <div class="item form-group">
                    <label class="control-label margin__bottom" for="status">End Date <span class="required">*</span>
                    </label>
                    <div class="feild">
                        <div class="feild">
                            {{ Form::text('end_date',@$end_date ,
                            array('id'=>'end_date','class' => ' form-control datepicker_academic_start enddate',"placeholder"=>"select end date" )) }}
                        </div>
                    </div>
                </div>
                   
            </div>

            <div class="col-xs-12 col-sm-4 col-md-3 daily" style="display: none;">
                <div class="item form-group">
                    <label class="control-label margin__bottom" for="status">Select Day <span class="required">*</span>
                    </label>
                    <div class="feild">
                        <div class="feild">
                            {{ Form::text('day',@$day ,
                            array('id'=>'day','class' => ' form-control datepicker_academic_start day',"placeholder"=>"select day" )) }}
                        </div>
                    </div>
                </div>
                   
            </div>

            <div class="col-xs-12 col-sm-4 col-md-3 monthly" style="display: none;">
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

            <div class="col-xs-12 col-sm-4 col-md-3 yearly" style="display: none;">
                <div class="item form-group">
                    <label class="control-label margin__bottom" for="status">Select Year <span class="required">*</span>
                    </label>
                    <div class="feild">
                        <div class="feild">
                            {{ Form::text('year',@$year ,
                            array('id'=>'year','class' => ' form-control year-picker year',"placeholder"=>"select year" )) }}
                        </div>
                    </div>
                </div>
                   
            </div>
        </div>
        <div class="table-responsive" style="margin-top: 40px;">
            <table id="datatable-buttons1" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Product Name</th>
                        <th>Total purchaseQuantity</th>
                        <th>Total purchaseAmount ₦</th>
                        <th>Total orderQuantity</th>
                        <th>Total orderAmount ₦</th>
                        
                    </tr>
                </thead>
                <tbody>

                    @if (@$final_data)

                    @foreach (@$final_data as $data )
                    @php
                        $totalpurchaseqty=@$data->totalpurchaseqty ?? 0;
                        $totalpurchasesale=@$data->totalpurchasesale ?? 0;

                        $total =$totalpurchaseqty *$totalpurchasesale;

                        $totalordersale = $data->totalordersale ?? 0;
                        $totalpurchasesale = $data->totalpurchasesale ?? 0;
                    @endphp
                    

                    <tr>
                        <td>{{ @$loop->index+1 }}</td>

                        <td>{{ @$data->product_name }}</td>

                        <td>{{ @$data->totalpurchaseqty ?? 0 }}</td>
                        <td>{{  number_format($total,2)}}</td>
                        <td>{{ @$data->totalorderqty  ?? 0}}</td>
                        <td>{{ number_format(@$data->totalordersale ?? 0,2) }} 

                            @if (  $totalordersale > 0 && $totalpurchasesale> 0)
                            @if ( $totalordersale > $total )
                            <i class="fa fa-arrow-up text-success"></i>

                            @else
                            <i class="fa fa-arrow-down text-danger"></i>
                            @endif
                            @endif
                           
                        </td>
                            
                            
                    
                    </tr>
                        
                    @endforeach
                        
                    @endif

                </tbody>
            
            </table>
        </div>
    </div>
</div>

{{ Form::close() }}


  

@endsection
@section("scripts")
<script type="text/javascript">

@if (Session::get("type"))
var rtype = "{{ Session::get("type") }}";

if(rtype && rtype == "daily")
    
    {
        $(".daily").show();
        
    }else if(rtype == "monthly")
    {
        $(".monthly").show();
    }else if(rtype == "yearly")
    {
        $(".yearly").show();
    }
    else if(rtype == "weekly")
    {
        $(".weekly").show();
    }

    
@endif
    $(function () {
      
      var table = $('#datatable-buttons1').DataTable({
        dom: '<"toolbar" <"row" <"col-xs-12 col-sm-4"l> <"col-xs-12 col-sm-3"B> >>frtip',
        buttons: [
            {
                extend: "csv",
                className: "btn-sm",
                exportOptions: {
                    columns: "thead th:not(.noExport)",
                },
            },
            {
                extend: "pdf",
                className: "btn-sm",
                exportOptions: {
                    columns: "thead th:not(.noExport)",
                    format: {
                        body: function (data, col, row) {
                            var isImg = data.toLowerCase().indexOf("img")
                                ? $(data).is("img")
                                : false;
                            if (isImg) {
                                return $(data).attr("title");
                            }
                            return data;
                        },
                    },
                },
            },
            {
                extend: "print",
                className: "btn-sm",
                exportOptions: {
                    columns: "thead th:not(.noExport)",
                },
            },
        ],
        responsive: true,
      });
      
    });

    let type ={!! json_encode(@$type) !!};

    if(type && type == "daily")
    
    {
        $(".daily").show();
        
    }else if(type == "monthly")
    {
        $(".monthly").show();
    }else if(type == "yearly")
    {
        $(".yearly").show();
    }
    else if(type == "weekly")
    {
        $(".weekly").show();
    }
  </script>
<script type="module">

function notify_script(title,text,type,hide) {
            new PNotify({
                title: title,
                text: text,
                type: type,
                hide: hide,
                styling: 'fontawesome'
            })
        }

  
    ProductConfig.ProductConfiginit(notify_script);
</script>
@endsection

@section('script_link')

    <!-- validator -->
    {!! Cms::script("theme/vendors/validator/validator.js") !!}
    {!! Cms::script("theme/vendors/validator/validator_form.js") !!}

@endsection
