
    
    <div class="row">
      
        <div class="col-xs-12 col-sm-4 col-md-3">
            <div class="item form-group">
                <label class="control-label margin__bottom" for="distribution_mark">Start Time <span class="required">*</span></label>
                <div class="feild">
                    {{ Form::time('start_time[]', "", [
                        'id' => 'start_time',
                        'class' => 'form-control col-md-7 col-xs-12',
                        'required' => 'required'
                    ]) }}
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-2 col-md-3">
            <div class="item form-group">
                <label class="control-label margin__bottom" for="status">End Time <span class="required">*</span></label>
                <div class="feild">
                    {{ Form::time('end_time[]', "", [
                        'id' => 'end_time',
                        'class' => 'form-control col-md-7 col-xs-12',
                        'required' => 'required'
                    ]) }}
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-3">
            <div class="item form-group">
                <label class="control-label margin__bottom" for="status">Period Category<span class="required">*</span></label>
                <div class="feild">
                    @php
                         $period_category = Configurations::CLASSPERIODCATEGORIES;
                    @endphp
                    {{ Form::select(
                        'period_category[]',
                        @$period_category,
                        @$data->$period_category ? @$data->$period_category : @$period_category,
                        [
                            'id' => 'period_category',
                            'class' => @$layout == 'edit' ? ' form-control period_category' : 'single-select form-control period_category',
                            'required' => 'required',
                            'placeholder' => 'Select period category',
                            @$layout == 'edit' ? 'disabled' : '',
                        ],
                    ) }}
                </div>
            </div>
        </div>
        <div class="col-1">
            <button class="btn delete-row"><i class="fa fa-times-circle text-secondary" style="margin-top: 1em !important; font-size:26px;"></i></button>
        </div>
    </div> <!--row-->

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

<script>
    
    function initializeSelect2(newlyAppended = false) {
    var selector = newlyAppended ? '.single-select.newly-appended' : '.single-select';
    $(selector).select2({
        theme: 'bootstrap4',
        width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
        placeholder: $(this).data('placeholder'),
        allowClear: Boolean($(this).data('allow-clear')),
    });
 }

   
</script>

