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
        .colorclass td{
            color: green !important;
        }
    </style>
@endsection
@section('body')
{{ Form::open(array('role' => 'form', 'route'=>array('Paymentverify'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'academicyear-form')) }}
<div class="card">
    <div class="card-body">
        <div class="card-title btn_style">
            <div class="headings">
                <h4 class="mb-0">View E-Payments <span class="badge bg-success">{{ number_format($wallet_data->wallet_amount,2) }} NGN</span></h4>
                <small>{{ $parent->father_name }}-{{ $parent->father_email }} </small>
            </div>

           
          
            <div>
                {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Verify', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_academicyear' , 'class' => 'btn btn-success btn-sm m-1  px-3')) }}
                
                <a class="btn btn-primary btn-sm m-1  px-3" href="{{route('wallet.index')}}" ><i class='fa fa-arrow-left'></i>&nbsp;&nbsp;Back</a>
            </div>
           
            
          
        </div>
        <hr/>
        <div class="table-responsive">
            <table id="datatable-buttons1" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Is Verified</th>
                        <th>Attachment</th>
                        <th>Amount ₦</th>
                        <th>Verified Date</th>
                       
                       
                       
                    </tr>
                </thead>
                <tbody>

                  
                   
                    @foreach ($wallet_data->attachments as $attach )
                    <input type="hidden" name="verify[{{$attach->id   }}]" value="0"/>
                        <tr class="{{ $attach->wallet_verified == 1 ? "colorclass":"" }}">
                            <td>{{ $loop->index +1 }}</td>

                            @if ($attach->wallet_verified == 1)
                            <td><i class="fa fa-check"></i></td>
                            @else

                            <td>
                                <input class="form-check-input" type="checkbox"  id="flexCheckChecked" name="verify[{{ $attach->id  }}]" value="1">
                            </td>

                          
                                
                            @endif
                          
                            <td><a href="{{ $attach->wallet_attachment }}" target="_blank" class="badge bg-info">View File</a></td>
                            @if ($attach->wallet_verified == 1)
                            <td>{{ number_format($attach->amount,2) }}</td>
                            @else
                            <td> <input class="form-control form-control-sm input" type="number" placeholder="Amount"  name="amount[{{$attach->id }}]" value="{{ $attach->amount }}"></td>
                            @endif
                            @if ($attach->wallet_verified == 1)
                            <td>{{ @$attach->updated_at->format("Y-m-d") }}</td>
                            @else
                            <td><span class="badge bg-warning">Not Verified</span></td>
                            @endif

                           
                           
                        </tr>
                        
                    @endforeach

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
{{ Form::close() }}


  

@endsection
@section('scripts')
    <script>
     window.statuschange='{{route('wallet_action_from_admin')}}';
     window.viewpaymenturl='{{ route('wallet.index') }}';
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
    </script>

@endsection
