@extends('layout::admin.master')

@section('title','Issue Book')
@section('style')


@endsection
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('issuebook.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'issuebook-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('issuebook.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
        <div class="box-header with-border mar-bottom20">
           

            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_library' , 'class' => 'btn btn-success btn-sm m-1  px-3')) }}

            @if (@$layout == "create")

            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save-Continue', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat_continue' , 'value' => 'Edit_wallet' , 'class' => 'btn btn-dark btn-sm m-1  px-3')) }}

            @endif
            <a class="btn btn-info btn-sm m-1  px-3" href="{{route('issuebook.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

            {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }}

           
          
        </div>
           @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit Issue Book" : "Create Issue Book"])

             <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Issue Book</h5>
                    <hr/>
                    <div class="col-xs-12">
                        <div class="row">
                        <div class="col-xs-12 col-sm-4 col-md-3">
                          <div class="item form-group">
                           <label class="control-label margin__bottom" for="status">Library Member <span class="required">*</span>
                                </label>
                                @if (@$layout=="edit")

                                <input type="hidden" name="member_id" value="{{ @$data->member_id }}"/>
                                <input type="hidden" name="book_id" value="{{ @$data->book_id }}"/>
                                    
                                @endif
                                @php
                                   $dis=$layout=="edit" ? true :false;
                                @endphp
                                <div class="feild">
                                    {{ Form::select('member_id',@$categories,@$data->member_id ,
                                    array('id'=>'member_id','class' => 'single-select form-control','required' => 'required',"placeholder"=>"select member","disabled"=>$dis )) }}
                                </div>
                          </div>
                               
                        </div>

                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                             <label class="control-label margin__bottom" for="status">Select Book <span class="required">*</span>
                                  </label>
                                  <div class="feild">
                                      {{ Form::select('book_id',@$books,@$data->book_id ,
                                      array('id'=>'book_id','class' => 'single-select form-control','required' => 'required',"placeholder"=>"select book","disabled"=>$dis )) }}
                                  </div>
                            </div>
                                 
                          </div>
                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                                <label class="control-label margin__bottom" for="status">Due Date <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{Form::text('return_date',@$data->return_date,array('id'=>"return_date",'class'=>"form-control col-md-7 col-xs-12 datepicker" ,
                                   'placeholder'=>"Due Date",'required'=>"required"))}}
                                </div>
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
