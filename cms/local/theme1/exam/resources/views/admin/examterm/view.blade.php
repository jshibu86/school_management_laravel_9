@extends('layout::admin.master')

@section('title', 'examterm_view')
@section('style')
    <!-- Datatables -->
    @include('layout::admin.head.list_head')
@endsection
@section('body')
    <div class="box-header with-border mar-bottom20">
        <a class="btn btn-info btn-sm m-1  px-3" href="{{route('examterm.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>
    </div>    
    <div class="card">
        <div class="card-body">
            <h2 class="card-title">Academic Term</h5>
            <hr/>
            <div class="row text-center">
              <p> Term Name : <span class="fw-bold">{{ $data->exam_term_name }}</span></p>
              <p> Academic Year : <span class="fw-bold">{{ $academic_year }}</span></p>
              <p> Start Date : <span class="fw-bold">{{ $data->from_date }}</span></p>
              <p> End Date : <span class="fw-bold">{{ $data->to_date }}</span></p>
            </div>
        </div>    
    </div>  
@endsection

@section('script')
@endsection