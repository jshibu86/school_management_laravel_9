@extends('layout::admin.master')

@section('title','lclass')
@section('style')


@endsection
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('lclass.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'lclass-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('lclass.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
        <div class="box-header with-border mar-bottom20">
           
            
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_lclass' , 'class' => 'btn btn-success btn-sm m-1  px-3')) }}
            @if (@$layout == "create")

            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save-Continue', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat_continue' , 'value' => 'Edit_wallet' , 'class' => 'btn btn-dark btn-sm m-1  px-3')) }}

            @endif

            <a class="btn btn-info btn-sm m-1  px-3" href="{{route('lclass.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

            {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }}

            


           

            @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit Class" : "Create Class"])
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Create a new Class</h5>
                <hr/>
                <div class="col-xs-12">
                    <div class="row">
                       
                            
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                    <div class="item form-group">
                                        <label class="control-label margin__bottom" for="school_name"> Class Name <span class="required">*</span>
                                        </label>
                                        <div class="feild">
                                        {{Form::text('name',@$data->name,array('id'=>"name",'class'=>"form-control col-md-7 col-xs-12" ,
                                        'placeholder'=>"e.g Class 1",'required'=>"required"))}}
                                        </div>
                                    </div>
                            </div>
                             <div class="col-xs-12 col-sm-4 col-md-3">
                                 <div class="item form-group">
                                <label class="control-label margin__bottom" for="school_name"> Select School Type <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{ Form::select('school_type_id',@$types,@$data->school_type_id ,
                                     array('id'=>'type_id','class' => 'form-control single-select' ,"placeholder"=>"Select School Type",'required'=>"required")) }}
                                    </div>
                            </div>
                             </div>

                           


                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="school_name">Note <span class="required">(if any)</span>
                                    </label>
                                    <div class="feild">
                                        {{Form::textarea('note',@$data->note, [
                                            'class'      => 'form-control',
                                            'rows'       => 2, 
                                            'name'       => 'note',
                                            'id'         => 'note',
                                            
                                        ])}}
                                    </div>
                                </div>
                        </div>
                            
                        
                       
                        <!-- //status -->
                        
                    </div>
                    </div>
                    
                   
                   
                    {{Form::close()}}
                </div>
            </div>
        </div>
  

        
    </div>

@endsection

@section('script')

    <!-- validator -->
    {!! Cms::script("theme/vendors/validator/validator.js") !!}
    {!! Cms::script("theme/vendors/validator/validator_form.js") !!}

@endsection
