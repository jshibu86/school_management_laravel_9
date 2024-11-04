@php
    $index = 0;
@endphp

@if($distribution)
   
    
    <div class="row">
        <div class="col-xs-12 col-sm-4 col-md-3">
            <div class="item form-group">
                <label class="control-label margin__bottom" for="status">Mark distribution Name <span class="required">*</span></label>
                <div class="feild">
                    {{ Form::text('distribution_name[]', "", [
                        'id' => 'distribution_name_' . $index,
                        'class' => 'distribution form-control col-md-7 col-xs-12',
                        'required' => 'required'
                    ]) }}
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-4 col-md-3">
            <div class="item form-group">
                <label class="control-label margin__bottom" for="distribution_mark">Mark Value <span class="required">*</span></label>
                <div class="feild">
                    {{ Form::text('distribution_mark[]', "", [
                        'id' => 'distribution_mark_' . $index,
                        'class' => 'form-control col-md-7 col-xs-12',
                        'required' => 'required'
                    ]) }}
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-2 col-md-1">
            <div class="item form-group">
                <label class="control-label margin__bottom" for="status">Status <span class="required">*</span></label>
                <div class="feild">
                    <label class="switch">
                        <input type="checkbox" id="status" class="toggle-class" name="status[]" value="1" checked>
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
        </div>
        <div class="col-1">
            <button class="btn delete-row" data-index="{{ $index }}"><i class="fa fa-times text-danger" style="margin-top: 1em !important;"></i></button>
        </div>
    </div> <!--row-->
@endif
<style>
    .btn:focus{
        box-shadow: unset !important;
    }
</style>
<script>
    $(document).on('click', '.delete-row', function() {
    var index = $(this).data('index');
    $(this).closest('.row').remove();
});
</script>