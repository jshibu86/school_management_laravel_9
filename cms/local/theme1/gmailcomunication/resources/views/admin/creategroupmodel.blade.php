<style>
    .file-input__input {
        width: 0.1px;
        height: 0.1px;
        opacity: 0;
        overflow: hidden;
        position: absolute;
        z-index: -1;
    }

    .file-input__label {
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 600;
        color: #fff;
        font-size: 14px;
        padding: 10px 12px;
        background-color: #673ab7;
       
    }

    .file-input__label svg {
        height: 16px;
        margin-right: 4px;
    }

</style>
<div class="x_content container">
    @if($layout == "create")
      {{ Form::open(array('role' => 'form', 'route'=>array('create_group'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'gmailcomunication-form','novalidate' => 'novalidate')) }}
    @else 
      {{ Form::open(array('role' => 'form', 'route'=>array('edit_group',$group->id), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left ', 'id' => 'gmailcomunication-form','novalidate' => 'novalidate')) }}
    @endif
     <h5 class="mb-5">@if($layout == "create")
        Create New Group
        @else
        Update Group
        @endif</h5>
     <div class="item form-group mb-4">
        <label class="control-label" for="group_title">Group Title <span class="required">*</span>
        </label>
        <div class="">
            {{Form::text('group_title',@$group->title,array('id'=>"group_title",'class'=>"form-control" ,
            'placeholder'=>"e.g Class A",'required'=>"required"))}}
        </div>
    </div>

    <div class="item form-group mb-4">
        <label class="control-label" for="group_describition">Group Description </label>
        <div>
            {{ Form::textarea('group_description', @$group->descripition, ['id' => 'group_description', 'class' => 'form-control', 'rows' => 3]) }}
        </div>
    </div>
    
    <div class="item form-group mb-4">
        @if($layout == "create")
        <label class="control-label" for="group_image">Group Image <span class="required">*</span></label>
        @else
        <label class="control-label" for="group_image">Group Image </label>
        @endif
        <div>
            @if(isset($group))
                @php
                if(isset($group)){
                    if($group->image !== "null"){
                        $image = asset($group->image);
                    }
                    else{
                        $image = "NA";
                    }
                }
                @endphp
                <div class="">
                    <label class="control-label file-input__label my-2" for="group_image">Choose Image <span class="required">*</span></label><br>
                    <span class="input-group-btn">
                        {{ Form::file('group_image',['id' => 'group_image','data-id'=>"imagec",'class' => 'file-input__input mb-1 form-control thumb', 'accept' => '.jpg,.jpeg',]) }}

                    </span>
                    <img id="group_imageholder" style="max-height:130px;"
                        src="{{ @$image }}">
                </div>
              
            @else 
            <div class="">
                <label class="control-label file-input__label my-2" for="group_image">Choose Image <span class="required">*</span></label><br>
                <span class="input-group-btn">
                    {{ Form::file('group_image',['id' => 'group_image','data-id'=>"imagec",'class' => 'file-input__input mb-1 form-control thumb', 'accept' => '.jpg,.jpeg', 'required' => 'required']) }}

                </span>
                <img id="group_imageholder" style="max-height:130px;"
                    src="{{ @$image }}">
            </div>
            @endif
        </div>
    </div>
    
    <div class="item form-group mb-4">
        <label class="control-label" for="group_type">Group Type <span class="required">*</span>
        </label>
        <div class="field">
            @php
                $type = isset($group) ? ($group->type == "0" ? "all" : $group->type) : null;
                $group_type = $type ?? null;
                $isDisabled = isset($group->type) ? 'disabled' : '';
            @endphp
            {{ Form::select('group_type', @$group_types, @$group_type, [
                'id' => 'group_type',
                'class' => @$layout !== "create" ? 'form-control' : 'single-select form-control', 
                'required' => 'required',
                'placeholder' => 'Select Group Type',
                $isDisabled => $isDisabled, 
            ]) }}
        </div>
        
    </div>

    <div class="item form-group mb-4 recipient">
        <label class="control-label" for="recipient">Recipient <span class="required">*</span></label>
        <div class="field">
            {{ Form::select('recipient[]', @$group_recipients,@$receptiants,
                array('id'=>"recipient",'class' => 'recipients form-select multiple-select form-control', 'size'=>"3",'required' => 'required',"multiple" )) }}
        </div>
    </div>
    
    <div class="item form-group ">
       
        <div class="">
           
           <button type="submit" class="btn btn-md btn-primary" style="float:right;"> @if($layout == "create")
            Create Group
            @else
            Update Group
            @endif</button>
        </div>
    </div>
    {{Form::close()}}
</div>


<script>
    $(document).ready(function() {
        $('.single-select').select2({
            theme: 'bootstrap4',
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            allowClear: Boolean($(this).data('allow-clear')),
            dropdownParent: $('#create_group_model')
        });
        $('.multiple-select').select2({
            theme: 'bootstrap4',
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            allowClear: Boolean($(this).data('allow-clear')),
            dropdownParent: $('#create_group_model')
        });

     
        const file = $('#image').val();
        if(file){
            if (file !== "NA") {
            // If a file is selected, set the input value to the file's URL
            $('#group_image').val(file);
            } else {
                // If no file is selected, set the input value to an empty string
                $('#group_image').setAttribute('value', '');
            }
        }
       
     
       
    
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
    window.get_receptiants = '{{route('get_receptiants')}}';
    GmailCommunicationConfig.GmailCommunicationInit(notify_script);
    
</script>