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
    <div class="box-header with-border mar-bottom20">
       <a class="btn btn-info btn-sm m-1 px-3" href="{{route('academicyear.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>
    </div>
   @if(@$academic_year)
     <div class="card">
        <div class="card-body justify-content-center">
            <h1 class="text-center">Academic Year Info</h1>

           
                <div class="text-center mt-5">
                    <p class="">Title : <span class="fw-bold">{{ $academic_year->title }}</span></p> 
                    <p class="">Year : <span class="fw-bold">{{ $academic_year->year }}</span></p> 
                    <p class="">Start Date : <span class="fw-bold">{{ $academic_year->start_date }}</span></p>
                    <p class="">End Date : <span class="fw-bold">{{ $academic_year->end_date }}</span></p>               
                </div>
                
           
        </div>
      
     </div>
     <div class="card mt-5">
        <div class="card-body ">
            <h2 class="text-center mb-5">Terms for Academic Year</h2>

            @if($academic_terms->isNotEmpty())
            <div class="row justify-content-center">
                @foreach($academic_terms as $key => $data)
                    <div class="col-4 text-center">
                        <p class="">Title : <span class="fw-bold">{{ $data->exam_term_name }}</span></p> 
                        <p class="">Start Date : 
                            <span class="fw-bold">
                                @if($data->from_date)
                                   {{ $data->from_date }}
                                @else
                                   NA
                                @endif   
                            </span>
                        </p>
                        <p class="">End Date : 
                            <span class="fw-bold">
                                @if($data->to_date)
                                    {{ $data->to_date }}
                                @else
                                    NA
                                @endif  
                            </span>
                        </p>               
                    </div>
                @endforeach    
            </div>
        @else
            <div>
                <p class="badge bg-secondary w-100 text-center" style="font-size: 16px !important;">Terms Not Found... </p>
            </div>    
        @endif
        
        </div>
     </div>
   @endif  
</div>
@endsection

@section("scripts")
@endsection