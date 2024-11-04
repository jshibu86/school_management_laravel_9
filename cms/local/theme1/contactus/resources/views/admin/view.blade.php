@extends('layout::admin.master')

@section('title','contactus')
@section('style')


@endsection
@section('body')
    <div class="x_content">

            <div class="box-header with-border mar-bottom20">

                <a class="btn btn-info btn-sm m-1  px-3" href="{{route('contactus.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Message info</h5>
                    <hr/>  
                    <div class="mb-2">
                        <p class=""><span class="fw-bold">Name: </span><span>{{@$data->name}}</span></p> 
                        <p class=""><span class="fw-bold">Email: </span><span>{{@$data->email}}</span></p>    
                    </div>
                    <div class="mb-2">
                        <p class="fw-bold">Subject:</p>
                        <p>{{@$data->subject}}</p>
                    </div>
                    <div class="mb2">
                        <p class="fw-bold">Message:</p>
                        <p>{{@$data->message}}</p>
                    </div>
                               
               </div>
            </div>      
   </div>

@endsection

@section('script_link')

    <!-- validator -->
    {!! Cms::script("theme/vendors/validator/validator.js") !!}
    {!! Cms::script("theme/vendors/validator/validator_form.js") !!}

@endsection
