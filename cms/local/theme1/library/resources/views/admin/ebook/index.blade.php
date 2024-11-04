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
        .buttons a,.buttons button{
            font-size: 10px;
        }

        .buttons a i{
            font-size: 15px;
        }
        .buttons button i{
            font-size: 15px;
        }
        .pagination{
            align-items: center;
            justify-content: center;
        }
    </style>
@endsection
@section('body')
@include("layout::admin.breadcrump",['route'=> "E Books"])
<div class="card">
  
    <div class="card-body">
        
        <div class="card-title btn_style">
            <h4 class="mb-0">View E Books</h4>

            @if(CGate::allows("edit-library"))
            <a class="btn btn-primary btn-sm m-1  px-3" href="{{route('ebook.create')}}" ><i class='fa fa-plus'></i>&nbsp;&nbsp;Create E-book</a>
            @endif
          
        </div>
        <hr/>
       {{-- section degin --}}
       <div class="row">

        @forelse (@$data as$book )
        <div class="col-12 col-lg-3 col-xl-3">
            <div class="card">
                <img src="{{ @$book->cover_photo }}" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">{{ @$book->title }}</h5>
                    <p class="card-text">Author : {{ @$book->author_name }}</p> 

                    <div class="buttons">
                        <a  href="{{ route("ebook.show",$book->id) }}" class="btn btn-success btn-sm "><i class="fa fa-eye"> </i> View</a>
                            @if (CGate::allows("edit-library"))
                        <a  href="{{ route("ebook.edit",@$book->id) }}" class="btn btn-info btn-sm "><i class="fa fa-edit"> </i> Edit</a>
                            <form method="post" action="{{ route("ebook.destroy",$book->id) }}">
                                <!-- here the '1' is the id of the post which you want to delete -->
                            
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                               
                                <button class="delete btn btn-danger btn-sm " type="submit"><i class="fa fa-trash delete" title="delete"></i> Delete</button>
                               
                            </form>
                            @endif
                       
                    </div>

                   
                </div>
            </div>
        </div>
        @empty
        <p>No Books</p>
            
        @endforelse
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                 {{ $data->links() }}
               
            </ul>
        </nav>
       
        
        
    </div>

       {{-- section end --}}

    </div>
</div>


  

@endsection

