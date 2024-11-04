@extends('layout::admin.master')

@section('title','e-book')
@section('style')
    <!-- Datatables -->
    @include('layout::admin.head.list_head')
    <style>
        .table-div table {
            width: 100% !important;
        }
        .buttons{
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .buttons a{
            font-size: 10px;
        }

        .buttons a i{
            font-size: 15px;
        }
        #ifrm{
            height: 550px;
        }
    </style>
@endsection
@section('body')
@include("layout::admin.breadcrump",['route'=> "E Books"])
<div class="card">
  
    <div class="card-body">
        
        <div class="card-title btn_style">
            <h4 class="mb-0">{{ $data->title }}</h4>

           
            <a class="btn btn-info btn-sm m-1  px-3" href="{{route('ebook.index')}}" ><i class='fa fa-arrow-left'></i>&nbsp;&nbsp;Back</a>
           
          
        </div>
        <hr/>
       {{-- section degin --}}
       <div class="row">

        <iframe id="ifrm" src="{{ asset(@$data->attachment) }}"></iframe>
        
        
        
    </div>

       {{-- section end --}}

    </div>
</div>


  

@endsection

