@extends('layout::admin.master')

@section('title','wallet')
@section('style')
    <!-- Datatables -->
    @include('layout::admin.head.list_head')
    <style>
        .table-div table {
            width: 100% !important;
        }
        .form form{
            width: 100%;
        }
    </style>
@endsection
@section('body')
<div class="card">
    <div class="card-body">
        <div class="card-title btn_style">
            <h4 class="mb-0">View wallet</h4>
            @if(CGate::allows('create-wallet'))
            <a class="btn btn-primary btn-sm m-1  px-3" href="{{route('wallet.create')}}" ><i class='fa fa-plus'></i>&nbsp;&nbsp;Create Wallet</a>
            @endif
          
        </div>
        <hr/>
        <div class="table-responsive">
            <table id="datatable-buttons1" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Parent Name</th>
                        <th>Wallet Amount</th>
                        <th>Last Deposit Date</th>
                        <th>E Payment Verify</th>
                        <th class="noExport">Action</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            
            </table>
        </div>
    </div>

    <div class="modal fade" id="view__homeworks"  aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered form">
            {{ Form::open(array('role' => 'form', 'route'=>array('Paymentverify'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => '', 'id' => 'leave-form','novalidate' => 'novalidate')) }}
           
            <div class="modal-content" >
               
                <div class="modal-body assigen_parent_body">
    
                    <div class="homework_details">
                       some
                    </div>
       
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Submit</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                   
                </div>
            </div>
            {{ Form::close() }}
    
        
        </div>
    </div>
</div>


  

@endsection
@section('script')
    <script>
     window.statuschange='{{route('wallet_action_from_admin')}}';
     window.viewpaymenturl='{{ route('wallet.index') }}';
        $('document').ready(function(){

            var element = $("#datatable-buttons1");
            var url =  '{{route('get_wallet_data_from_admin')}}';
            var column = [
              
                {data: 'DT_RowIndex', name: 'DT_RowIndex',searchable: false,sortable: false},
                { data: 'parentname', name: 'parent.father_name', width: '15%' },
                { data: 'wallet_amount', name: 'wallet.wallet_amount' , className: 'textcenter',searchable: false,sortable: false },
                { data: 'deposit_date', name: 'deposit_date', width: '15%' },
                { data: 'verify', name: 'deposit_date', width: '15%' },
                { data: 'action', name: 'id', searchable: false, sortable: false, className: 'textcenter'}
            ];
            var csrf = '{{ csrf_token() }}';

            var options  = {
                //order : [ [ 6, "desc" ] ],
                lengthMenu: [[15,25,50, 100 ,250, 500, -1], [15,25,50, 100 ,250, 500,"ALL"]],
                button : [
                    {
                        name : "Publish" ,
                        url : "{{route('wallet_action_from_admin',1)}}"
                    },
                    {
                        name : "Un Publish",
                        url : "{{route('wallet_action_from_admin',0)}}"
                    },
                    {
                        name : "Trash",
                        url : "{{route('wallet_action_from_admin',-1)}}"
                    },
                    {
                        name : "Delete",
                        url : "{{route('wallet.destroy',1)}}",
                        method : "DELETE"
                    }
                ],

            }


            dataTable(element,url,column,csrf,options);

        });
    </script>

@endsection
