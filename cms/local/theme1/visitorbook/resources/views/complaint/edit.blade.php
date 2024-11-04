@extends('layout::admin.master')

@section('title','complaints')
@section('style')


@endsection
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('complaints.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'complaints-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('complaints.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
        <div class="box-header with-border mar-bottom20">
           
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_complaints' , 'class' => 'btn btn-success btn-sm m-1  px-3 ')) }}

            @if (@$layout == "create")  

            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save-Continue', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat_continue' , 'value' => 'Edit_wallet' , 'class' => 'btn btn-dark btn-sm m-1  px-3')) }}

            @endif
           

           

             <a class="btn btn-info btn-sm m-1  px-3" href="{{route('complaints.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

              {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }}

              

             
        </div>
           @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit complaints" : "Create Complaints"])

             <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{$layout == "edit" ?"Edit complaints" : "Create Complaints"}}</h5>
                    <hr/>
                    <div class="col-xs-12">
                        <div class="row">
                        
                        

                       

                        

                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                                <label class="control-label margin__bottom" for="status">Complaint  Date <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{Form::text('complaint_date',@$data->complaint_date,array('id'=>"name",'class'=>"form-control bg-white col-md-7 col-xs-12 datepicker" ,
                                   'placeholder'=>"99889999",'required'=>"required","readonly"))}}
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                                <label class="control-label margin__bottom" for="status">Complaint in Detail <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{ Form::textarea('complaint', @$data->complaint, [
                                            'class'      => 'form-control',
                                            'rows'       => 5, 
                                            'name'       => 'complaint',
                                            'id'         => 'complaint',
                                           
                                        ])
                                    }}
                                </div>
                            </div>
                        </div>


                            
                        </div>
                    </div>
                </div>
            </div>

        
       
       

        {{Form::close()}}
    </div>

@endsection

@section('script_link')

    <!-- validator -->
    {!! Cms::script("theme/vendors/validator/validator.js") !!}
    {!! Cms::script("theme/vendors/validator/validator_form.js") !!}

@endsection
