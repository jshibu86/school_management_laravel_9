@extends('layout::admin.master')

@section('title','orders')
@section('style')
    <!-- Datatables -->
    @include('layout::admin.head.list_head')
    <style>
        .table-div table {
            width: 100% !important;
        }
        .search-text:focus {
   
        background-color: #fff;
        border-color: #cdcdcd !important;
    
        box-shadow: none !important;
        }
    </style>
@endsection
@section('body')
<div class="card">
    <div class="card-body">
        <div class="card-title btn_style">
            <h4 class="mb-0">View Order</h4>
            {{-- @if(CGate::allows('create-shop'))
            <a class="btn btn-light" href="{{route('shop.create')}}" ><i class='fa fa-plus'></i>&nbsp;&nbsp;Create</a>
            @endif --}}
          
        </div>
        <hr/>

    
    
        <div class="table-responsive">
            <table id="datatable-buttons1" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Order No</th>
                        <th>Customer Name</th>
                        <th>Payment Type</th>
                        <th>Amount</th>
                        <th>Payment Status</th>
                        <th>Delivery Status</th>
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

@section('scripts')
<script type="module">
    window.Productsearch='{{route('shop.index')}}';
    window.addtocart='{{route('cart.addtocart')}}';
    window.getproduct='{{route('shop.index')}}';
    AcademicConfig.searchContact();

    //window.minicart='{{route('cart.minicart')}}'


   
</script>
@endsection
@section('script')
    <script>
     window.statuschange='{{route('order_action_from_admin')}}';
        $('document').ready(function(){

            var element = $("#datatable-buttons1");
            var url =  '{{route('get_order_data')}}';
            var column = [
              
                {data: 'DT_RowIndex', name: 'DT_RowIndex',searchable: false,sortable: false},
                { data: 'order_number', name: 'order_number'},
                { data: 'customername', name: 'users.name'},
                { data: 'payment_type', name: 'payment_type'},
                { data: 'order_amount', name: 'order_amount'},
                { data: 'paymentstatus', name: 'paymentstatus' , className: 'textcenter',searchable: false,sortable: false },
                { data: 'orderstatus', name: 'orderstatus' , className: 'textcenter',searchable: false,sortable: false },
               
                
                { data: 'action', name: 'id', searchable: false, sortable: false, className: 'textcenter'}
            ];
            var csrf = '{{ csrf_token() }}';

            var options  = {
                //order : [ [ 6, "desc" ] ],
                lengthMenu: [[15,25,50, 100 ,250, 500, -1], [15,25,50, 100 ,250, 500,"ALL"]],
                button : [
                    {
                        name : "Publish" ,
                        url : "{{route('shop_action_from_admin',1)}}"
                    },
                    {
                        name : "Un Publish",
                        url : "{{route('shop_action_from_admin',0)}}"
                    },
                    {
                        name : "Trash",
                        url : "{{route('shop_action_from_admin',-1)}}"
                    },
                    {
                        name : "Delete",
                        url : "{{route('shop.destroy',1)}}",
                        method : "DELETE"
                    }
                ],

            }


            dataTable(element,url,column,csrf,options);

        });
    </script>

@endsection
