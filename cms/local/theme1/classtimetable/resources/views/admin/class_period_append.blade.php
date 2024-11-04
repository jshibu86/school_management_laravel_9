@if ($periods)

    @foreach ($periods as $period)
        <div class="row" id="append_row">
            <div class="col-xs-12 col-sm-4 col-md-3">
                <div class="item form-group">
                    <label class="control-label margin__bottom" for="distribution_mark">Start Time <span
                            class="required">*</span></label>
                    <div class="feild">
                        {{ Form::time('start_time[]', @$period->from, [
                            'id' => 'start_time',
                            'class' => 'form-control col-md-7 col-xs-12',
                            'required' => 'required',
                        ]) }}
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-2 col-md-3">
                <div class="item form-group">
                    <label class="control-label margin__bottom" for="status">End Time <span
                            class="required">*</span></label>
                    <div class="feild">
                        {{ Form::time('end_time[]', @$period->to, [
                            'id' => 'end_time',
                            'class' => 'form-control col-md-7 col-xs-12',
                            'required' => 'required',
                        ]) }}
                    </div>
                </div>
            </div>
            @php
                if ($period->type ) {
                    $selected_period = $period->type;
                } else {
                    $selected_period = null;
                }
            @endphp

            <div class="col-xs-12 col-sm-4 col-md-3">
                <div class="item form-group">
                    <label class="control-label margin__bottom" for="status">Period Category<span
                            class="required">*</span></label>
                    <div class="feild">
                        {{ Form::select('period_category[]', Configurations::CLASSPERIODCATEGORIES,  $period->type, [
                            'id' => 'period_category',
                            'class' => @$layout == 'edit' ? 'form-control in' : 'single-select form-control in',
                            'required' => 'required',
                            'placeholder' => 'Select period category',
                            @$layout == 'edit' ? 'disabled' : '',
                        ]) }}
                    </div>
                </div>
            </div>

            <div class="col-1">
                <button class="btn delete-row" id="{{ $period->id }}"><i class="fa fa-times-circle text-secondary"
                        style="margin-top: 1em !important; font-size:26px;"></i></button>
            </div>
        </div> <!--row-->
    @endforeach
@endif

<script>
    $(document).on('click', '.delete-row', function() {
        var id = $(this).attr('id');
        $(this).closest('.row').remove();

    });
</script>

<script type="module">
    function notify_script(title, text, type, hide) {
        new PNotify({
            title: title,
            text: text,
            type: type,
            hide: hide,
            styling: 'fontawesome'
        })
    }

    //examtimetableconfig init
    //ExamTimetable.ExamTimetableInit(notify_script);
</script>
