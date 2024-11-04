@extends('layout::admin.master')

@section('title','profile')
@section('style')
@include('layout::admin.head.list_head')

@endsection


@section('body')
{{ Form::open(array('role' => 'form', 'route'=>array('mandatoryclosure'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'period-form form-horizontal form-label-left', 'id' => 'period-form','novalidate' => 'novalidate')) }}

{{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_period' , 'class' => 'btn periodsubmit btn-success btn-sm m-1  px-3 ')) }}
<div id="added_periods" class="mt-5">
<div class="row align-items-end mb-3 period_row">
   
    <div class="col-md-10">
      <div class="row">
        <div class="col-md-12">
          
          <input type="text" id="heading1" required name="mandatory[1][heading]" class="form-control period_select " placeholder="Enter Heading">
        </div>

        <div class="col-md-12 mt-2">
            <textarea class="input-clsck" name="mandatory[1][text]" id="firsttextarea" name="descriptionRich" rows="8"></textarea>
        </div>

        <div class="col-md-6 mt-2">
            <label for="formFileMultiple" class="form-label">Multiple Images</label>
            <input class="form-control" name="mandatory[1][images][]" accept="image/*" type="file" id="formFileMultiple" multiple>
        </div>
        <div class="col-md-6 mt-2">
            <label for="formFileMultiple" class="form-label">Multiple Pdf</label>
            <input class="form-control" name="mandatory[1][pdf][]" accept="application/pdf" type="file" id="formFileMultiple" multiple>
        </div>
        
       
      </div>
    </div>
    <div class="col-md-1">
      <button type="button" id="remove_period_first" class="btn btn-danger" > <i class="fa fa-times"></i></button>
    </div>
  </div>
 
</div>

<div class="col-md-4">
    <button type="button" id="addperiod" class="btn btn-primary mt-3"> <i class="fa fa-plus"></i>Add New</button>
  </div>
{{ Form::close() }}
@endsection

@section("scripts")
<script src="//cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
<script>

    var ordercount=1;

    ckeditor("firsttextarea");
     $(document).on("click", "#addperiod", function() {  

        ordercount++;
        console.log("click");


        var select1=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);
        var select2=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);
        var select3=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);
        var textarea=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);


$("#added_periods").append(`<div class="row align-items-end mb-3 period_row">
        
        <div class="col-md-10">
            <div class="row">
            
                <div class="col-md-12">
          
          <input type="text" id="heading1" required name="mandatory[${ordercount}][heading]" class="form-control period_select " placeholder="Enter Heading">
        </div>

        <div class="col-md-12 mt-2">
            <textarea class="input-clsck" name="mandatory[${ordercount}][text]" id="${textarea}" name="descriptionRich" rows="8"></textarea>
        </div>

        <div class="col-md-6 mt-2">
            <label for="formFileMultiple" class="form-label">Multiple Images</label>
            <input class="form-control" name="mandatory[${ordercount}][images][]" accept="image/*" type="file" id="formFileMultiple" multiple>
        </div>
        <div class="col-md-6 mt-2">
            <label for="formFileMultiple" class="form-label">Multiple Pdf</label>
            <input class="form-control" name="mandatory[${ordercount}][pdf][]" accept="application/pdf" type="file" id="formFileMultiple" multiple>
        </div>
            </div>
        </div>
        <div class="col-md-1">
            <button type="button" id="remove_period" class="btn btn-danger"> <i class="fa fa-times"></i></button>
        </div>
      </div>`);

      ckeditor(textarea);

     });

     $(document).on("click", "#remove_period", function() {
              $(this).closest('.period_row').remove(); 
              
    });

     function ckeditor(id)
        {
            var element=document.querySelector(`#${id}`);
            var path_absolute = "{{ url('/') }}";
            var options = {
                filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
                filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token={{csrf_token()}}',
                filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
                filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token={{csrf_token()}}',

                };
                CKEDITOR.replace(element,options);
        }
</script>
@endsection