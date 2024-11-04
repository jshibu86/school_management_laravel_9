@extends('layout::admin.master')

@section('title', 'library')
@section('style')


@endsection
@section('body')
    <div class="x_content">

        @if ($layout == 'create')
            {{ Form::open(['role' => 'form', 'route' => ['library.store'], 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'library-form', 'novalidate' => 'novalidate']) }}
        @elseif($layout == 'edit')
            {{ Form::open(['role' => 'form', 'route' => ['library.update', $data->id], 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form', 'novalidate' => 'novalidate']) }}
        @endif
        <div class="box-header with-border mar-bottom20">


            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', ['type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat', 'value' => 'Edit_library', 'class' => 'btn btn-success btn-sm m-1  px-3']) }}

            @if (@$layout == 'create')
                {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save-Continue', ['type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat_continue', 'value' => 'Edit_wallet', 'class' => 'btn btn-dark btn-sm m-1  px-3']) }}
            @endif
            <a class="btn btn-info btn-sm m-1  px-3" href="{{ route('library.index') }}"><i
                    class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>
            {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset', 'class' => 'btn btn-danger btn-sm m-1  px-3']) }}




        </div>
        @include('layout::admin.breadcrump', ['route' => $layout == 'edit' ? 'Edit Book' : 'Create Book'])

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Create a new Book</h5>
                <hr />
                <div class="col-xs-12">
                    <div class="row">
                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                                <label class="control-label margin__bottom" for="status">Category <span
                                        class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{ Form::select('category_id', @$categories, @$data->category_id, [
                                        'id' => 'category_id',
                                        'class' => 'single-select form-control',
                                        'required' => 'required',
                                        'placeholder' => 'select category',
                                    ]) }}
                                </div>
                            </div>

                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                                <label class="control-label margin__bottom" for="status">Title <span
                                        class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{ Form::text('title', @$data->title, [
                                        'id' => 'title',
                                        'class' => 'form-control col-md-7 col-xs-12',
                                        'placeholder' => 'Book Title',
                                        'required' => 'required',
                                    ]) }}
                                </div>
                            </div>
                        </div>


                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                                <label class="control-label margin__bottom" for="status">ISBN No <span
                                        class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{ Form::text('isbn_no', @$data->isbn_no, [
                                        'id' => 'isbn_no',
                                        'class' => 'form-control col-md-7 col-xs-12',
                                        'placeholder' => 'ISBN Number',
                                        'required' => 'required',
                                    ]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                                <label class="control-label margin__bottom" for="status">Publisher Name<span
                                        class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{ Form::text('publisher_name', @$data->publisher_name, [
                                        'id' => 'publisher_name',
                                        'class' => 'form-control col-md-7 col-xs-12',
                                        'placeholder' => 'Publisher name',
                                        'required' => 'required',
                                    ]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                                <label class="control-label margin__bottom" for="status">author Name<span
                                        class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{ Form::text('author_name', @$data->author_name, [
                                        'id' => 'author_name',
                                        'class' => 'form-control col-md-7 col-xs-12',
                                        'placeholder' => 'author name',
                                        'required' => 'required',
                                    ]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                                <label class="control-label margin__bottom" for="status">Quantity<span
                                        class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{ Form::text('quantity', @$data->quantity, [
                                        'id' => 'quantity',
                                        'class' => 'form-control col-md-7 col-xs-12',
                                        'placeholder' => 'quantity',
                                        'required' => 'required',
                                    ]) }}
                                </div>
                            </div>
                        </div>

                        {{-- <div class="col-xs-12 col-sm-4 col-md-3">
                        <div class="item form-group">
                            <label class="control-label margin__bottom" for="status">Price<span class="required"></span>
                            </label>
                            <div class="feild">
                                {{Form::number('price',@$data->price,array('id'=>"price",'class'=>"form-control col-md-7 col-xs-12" ,
                               'placeholder'=>"price"))}}
                            </div>
                        </div>
                    </div> --}}
                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                                <label class="control-label margin__bottom" for="status">Rack No<span
                                        class="required"></span>
                                </label>
                                <div class="feild">
                                    {{ Form::number('rack_number', @$data->rack_number, [
                                        'id' => 'rack_number',
                                        'class' => 'form-control col-md-7 col-xs-12',
                                        'placeholder' => 'rack number',
                                    ]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                                <label class="control-label margin__bottom" for="status">Description<span
                                        class="required"></span>
                                </label>
                                <div class="feild">

                                    <textarea class="form-control" id="inputAddress2" placeholder="Description..." rows="3" name="book_description">{{ old('book_description') ? old('book_description') : @$data->book_description }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                                <label class="control-label margin__bottom" for="library_subscription">Is Recommended <span class="required"></span>
                                </label>
                                <div>
                                    <label class="switch">
                                        <input type="checkbox" id="library_required" class="toggle-class"
                                            {{ @$data->is_recommended ? 'checked' : '' }} name="is_recommended">
                                        <span class="slider round"></span>
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>





    {{ Form::close() }}
    </div>

@endsection

@section('script_link')

    <!-- validator -->
    {!! Cms::script('theme/vendors/validator/validator.js') !!}
    {!! Cms::script('theme/vendors/validator/validator_form.js') !!}

@endsection
