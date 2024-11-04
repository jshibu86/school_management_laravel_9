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
<div class="card">
    <div class="card-body">
        <div class="card-title btn_style">
            <h4 class="mb-0">View {{@$type == 1 ? "Shop" : "Inventory"}} Purchase</h4>
            @if(CGate::allows('create-shop'))
            <div class="butns">
                @if (@$type==1)
                     <a class="btn btn-info btn-sm m-1  px-3" href="{{route('purchasereport')}}" ><i class='fa fa-file'></i>&nbsp;&nbsp;Purchase Report</a>
                @endif
               
                <a class="btn btn-primary btn-sm m-1  px-3" href="{{route('purchase.create',['type'=>@$type])}}" ><i class='fa fa-plus'></i>&nbsp;&nbsp;Create order</a>
            </div>
           
            @endif
          
        </div>
        <hr/>
        <div class="table-responsive">
            <table id="datatable-buttons1" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Product Name</th>
                        <th>Purchase Date</th>
                        <th>Bill Number</th>
                        <th>Quantity</th>
                        <th>Purchase Price ₦</th>
                       
                        <th class="noExport">Action</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            
            </table>
        </div>
    </div>
</div>


  

@endsection
@section('script')
    <script>
    
        $('document').ready(function(){

            var element = $("#datatable-buttons1");
            var url =  '{{route('get_purchase_data_from_admin')}}'+"?type="+@json(@$type);
            var column = [
              
                {data: 'DT_RowIndex', name: 'DT_RowIndex',searchable: false,sortable: false},
                { data: 'product_name', name: 'products.product_name', width: '15%' },
                { data: 'purchase_date', name: 'purchase_date' , className: 'textcenter' },
                { data: 'bill_no', name: 'bill_no' , className: 'textcenter' },
                { data: 'quantity', name: 'quantity' , className: 'textcenter' },
                { data: 'pprice', name: 'purchase_price' , className: 'textcenter' },
                 
                { data: 'action', name: 'id', searchable: false, sortable: false, className: 'textcenter'}
            ];
            var csrf = '{{ csrf_token() }}';

            var options  = {
                //order : [ [ 6, "desc" ] ],
                lengthMenu: [[15,25,50, 100 ,250, 500, -1], [15,25,50, 100 ,250, 500,"ALL"]],
                button : [
                    {
                        name : "Publish" ,
                        url : "{{route('transport_action_from_admin',1)}}"
                    },
                    {
                        name : "Un Publish",
                        url : "{{route('transport_action_from_admin',0)}}"
                    },
                    {
                        name : "Trash",
                        url : "{{route('transport_action_from_admin',-1)}}"
                    },
                    {
                        name : "Delete",
                        url : "{{route('transport.destroy',1)}}",
                        method : "DELETE"
                    }
                ],

            }


            dataTable(element,url,column,csrf,options);

        });
    </script>

@endsection
