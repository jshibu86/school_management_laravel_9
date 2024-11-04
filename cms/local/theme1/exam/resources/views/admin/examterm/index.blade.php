@extends('layout::admin.master')

@section('title','exam')
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
            <h4 class="mb-0">Academic  Term</h4>
            @if(CGate::allows('create-exam'))
            <a class="btn btn-primary btn-sm m-1  px-3" href="{{route('examterm.create')}}" ><i class='fa fa-plus'></i>&nbsp;&nbsp;Create</a>
            @endif
          
        </div>
        <hr/>
        <div class="table-responsive">
            <table id="datatable-buttons1" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Title</th>
                        <th>Year</th>
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
        window.statuschange='{{route('academicyear_action_from_admin')}}';
        $('document').ready(function(){
                var element = $("#datatable-buttons1");
                var url = '{{ route('get_exam_term_data_from_admin') }}';
                var column = [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false, sortable: false },
                    { data: 'exam_term_name', name: 'exam_term_name', width: '15%' },
                    { data: 'academic_year', name: 'academic_year', className: 'textcenter' },
                    { data: 'action', name: 'id', searchable: false, sortable: false, className: 'textcenter' }
                ];
                var csrf = '{{ csrf_token() }}';

                var options = {
                    lengthMenu: [[15,25,50, 100 ,250, 500, -1], [15,25,50, 100 ,250, 500,"ALL"]],
                    button: [
                        {
                            name: "Trash",
                            url: "{{ route('academicyear_action_from_admin', -1) }}"
                        },
                        {
                            name: "Delete",
                            url: "{{ route('academicyear.destroy', 1) }}",
                            method: "DELETE"
                        }
                    ],
                };
                dataTable(element,url,column,csrf,options);

                // var dataTable = element.DataTable({
                //     processing: true,
                //     serverSide: true,
                //     order: [], // Disable automatic ordering
                //     ajax: {
                //         url: url,
                //         type: 'post',
                //         headers: {
                //                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                //                  },
                //         data: function (d) {
                //             // Include search parameters for each column
                //             // Example: d.column_name = $('#input_id').val();
                //         }
                //     },
                //     columns: column,
                //     // Add your other DataTable configurations here
                // });
           });

    </script>

@endsection
