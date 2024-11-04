@extends('layout::admin.master')

@section('title','ebook')
@section('style')


@endsection
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('ebook.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'ebook-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('ebook.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
        <div class="box-header with-border mar-bottom20">
           
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_library' , 'class' => 'btn btn-success btn-sm m-1  px-3')) }}

            
            @if (@$layout == "create")

            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save-Continue', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat_continue' , 'value' => 'Edit_wallet' , 'class' => 'btn btn-dark btn-sm m-1  px-3')) }}

            @endif
            <a class="btn btn-info btn-sm m-1  px-3" href="{{route('ebook.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

            {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }}

          

           
        </div>
           @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit E-Book" : "Create E-Book"])

             <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Create a new Book</h5>
                    <hr/>
                    <div class="col-xs-12">
                        <div class="row">
                        <div class="col-xs-12 col-sm-4 col-md-3">
                          <div class="item form-group">
                           <label class="control-label margin__bottom" for="status">Category <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{ Form::select('category_id',@$categories,@$data->category_id ,
                                    array('id'=>'category_id','class' => 'single-select form-control','required' => 'required',"placeholder"=>"select category" )) }}
                                </div>
                          </div>
                               
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                                <label class="control-label margin__bottom" for="status">Title <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{Form::text('title',@$data->title,array('id'=>"title",'class'=>"form-control col-md-7 col-xs-12" ,
                                   'placeholder'=>"Title",'required'=>"required"))}}
                                </div>
                            </div>
                        </div>
                        
                   
                   
                   
                    <div class="col-xs-12 col-sm-4 col-md-3">
                        <div class="item form-group">
                            <label class="control-label margin__bottom" for="status">Author Name<span class="required">*</span>
                            </label>
                            <div class="feild">
                                {{Form::text('author_name',@$data->author_name,array('id'=>"author_name",'class'=>"form-control col-md-7 col-xs-12" ,
                               'placeholder'=>"author name",'required'=>"required"))}}
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-3">
                        <div class="item form-group">
                            <label class="control-label margin__bottom" for="status">Cover Image<span class="required">*</span>
                            </label>
                            <div class="feild">
                                <div class="mb-3">

                                    @if (@$layout=="create")
                                    <input class="form-control" type="file" id="formFile" name="cover_photo" accept="image/*" required>
                                    @else
                                    <input class="form-control" type="file" id="formFile" name="cover_photo" accept="image/*" >
                                    @endif
                                  
                                 
                                </div>
                                @if (@$layout=="edit")

                                <img src="{{ @$data->cover_photo }}" width="50" alt="image"/>
                                    
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-3">
                        <div class="item form-group">
                            <label class="control-label margin__bottom" for="status">Attachment(PDF)<span class="required">*</span>
                            </label>
                            <div class="feild">
                                <div class="mb-3">

                                    @if(@$layout == "create")
                                    <input class="form-control" type="file" id="formFile" name="attachment" accept=".pdf" required>
                                    @else
                                    <input class="form-control" type="file" id="formFile" name="attachment" accept=".pdf">
                                    @endif
                                  
                                  
                                </div>

                                @if (@$layout=="edit")

                               <a href="{{ @$data->attachment }}" target="_blank">View Attachment</a>
                                    
                                @endif
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
