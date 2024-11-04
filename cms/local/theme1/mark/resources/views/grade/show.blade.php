
@extends('layout::admin.master')

@section('title','mark')
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
            <h4 class="mb-0">View Grade</h4>
          
            <a class="btn btn-primary btn-sm m-1  px-3" href="{{route('grade.index')}}" ><i class='fa fa-arrow-left'></i>&nbsp;&nbsp;Back</a>
          
          
        </div>
        <hr/>
        <div class="table-responsive">
            <table id="datatable-buttons1" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <th>No</th>
                <th>Grade</th>
                <th>Grade Point</th>
                <th>Mark From</th>
                <th>Mark Upto</th>
            </thead>
                <tbody>
                @foreach (@$grades_data->grades as $grade)
                    <tr>
                        <td>{{$loop->index+1}}</td>
                        <td>{{$grade->grade_name}}</td>
                        <td>{{$grade->grade_point}}</td>
                        <td>{{$grade->mark_from}}</td>
                        <td>{{$grade->mark_upto}}</td>
                    </tr>
                @endforeach
            </tbody>
            </table>
        </div>
    </div>
</div>


  

@endsection
@section('script')
  

@endsection
