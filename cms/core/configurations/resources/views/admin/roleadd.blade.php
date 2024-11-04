<div class="row">
    <div class="col-xs-12 col-sm-4 col-md-3">
        <div class="item form-group">
            <label class="control-label margin__bottom" for="school_start">Role <span class="required">*</span>
            </label>

            <div class="">
                {{ Form::select('role_id[]',@$roles, @$data->role_id, ['id'=>'','class' => 'role_id form-control single-select','placeholder'=>'Select Role']) }}
            </div>

        </div>
    </div>

    <div class="col-xs-12 col-sm-4 col-md-3">
        <div class="item form-group">
            <label class="control-label margin__bottom" for="school_start">Receptients <span class="required">*</span>
            </label>

            <div class="">
                {{ Form::select('receptiants[]', @$receptiants, @$data->receptiants, ['id'=>'','class' => 'receptiants form-control multiple-select','multiple'=>'multiple ',
                'placeholder'=>'Select Receptiants' ]) }}
            </div>

        </div>
    </div>

</div>

<script>
    function generateUniqueId() {  
         return 'id_' + Math.random().toString(36).substr(2, 9);
    }

    $('.role_id').on("change", function() {
            let id = $(this).val();
            let row = $(this).closest('.row');
            row.find('.receptiants').attr('name', 'receptiants' + id + '[]');
        });
     
    $(document).ready(function() {
        $('.multiple-select').each(function() {   
            var uniqueId = generateUniqueId();       
            $(this).attr('id', uniqueId);
            $('#'+uniqueId).select2({
            theme: 'bootstrap4',
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            allowClear: Boolean($(this).data('allow-clear')),
           
        });
        });
      
        $('.single-select').each(function() {   
            var uniqueId = generateUniqueId();       
            $(this).attr('id', uniqueId);
            $('#'+uniqueId).select2({
            theme: 'bootstrap4',
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            allowClear: Boolean($(this).data('allow-clear')),
           
        });
        });
    });
</script>