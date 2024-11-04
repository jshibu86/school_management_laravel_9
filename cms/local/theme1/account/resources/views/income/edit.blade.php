@extends('layout::admin.master')

@section('title','account')
@section('style')


@endsection
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('income.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'account-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('income.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
        <div class="box-header with-border mar-bottom20">
           
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_account' , 'class' => 'btn btn-success btn-sm m-1  px-3 ')) }}

            @if (@$layout == "create")  

            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save-Continue', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat_continue' , 'value' => 'Edit_wallet' , 'class' => 'btn btn-dark btn-sm m-1  px-3')) }}

            @endif
           

           

             <a class="btn btn-info btn-sm m-1  px-3" href="{{route('income.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

              {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }}

              

             
        </div>
           @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit Income" : "Create Income"])

             <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{$layout == "edit" ?"Edit Income" : "Create Income"}}</h5>
                    <hr/>
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-12 col-sm-4 col-md-3">
                          <div class="item form-group">
                           <label class="control-label margin__bottom" for="status">Academic year <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{ Form::select('academic_year',@$academic_years,@$data->academic_year ? @$data->academic_year : @$current_academic_year ,
                                    array('id'=>'status_','class' => 'single-select form-control','required' => 'required','placeholder'=>"Select Academic year")) }}
                                </div>
                          </div>
                               
                        </div>

                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                                <label class="control-label margin__bottom" for="status">Income Title <span class="required">*</span>
                                </label>
                                <input type="hidden" name="type" value="income"/>
                                <div class="feild">
                                    {{Form::text('title',@$data->title,array('id'=>"name",'class'=>"form-control col-md-7 col-xs-12" ,
                                   'placeholder'=>"Donation income",'required'=>"required"))}}
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-3">
                          <div class="item form-group">
                           <label class="control-label margin__bottom" for="status">Category <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{ Form::select('category_id',@$category,@$data->category_id ,
                                    array('id'=>'status','class' => 'single-select form-control','required' => 'required','placeholder'=>"Select Category")) }}
                                </div>
                          </div>
                               
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                                <label class="control-label margin__bottom" for="status">Amount<span class="required">*</span>
                                </label>
                              
                                <div class="feild">
                                    {{Form::text('amount',@$data->amount,array('id'=>"name",'class'=>"form-control col-md-7 col-xs-12 " ,
                                   'placeholder'=>"Rent expense",'required'=>"required"))}}
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                                <label class="control-label margin__bottom" for="status">Date<span class="required">*</span>
                                </label>
                              
                                <div class="feild">
                                    {{Form::text('entry_date',@$data->entry_date,array('id'=>"name",'class'=>"form-control col-md-7 col-xs-12 datepicker" ,
                                   'placeholder'=>"Rent expense",'required'=>"required",'readonly'))}}
                                </div>
                            </div>
                        </div>
                         <div class="col-xs-12 col-sm-4 col-md-3">
                          <div class="item form-group">
                           <label class="control-label margin__bottom" for="status">Description <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{ 
                                    Form::textarea('Enter Description', @$data->description, [
                                        'class'      => 'form-control',
                                        'rows'       => 4, 
                                        'name'       => 'description',
                                        'id'         => 'description',
                                       
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
