<div>
    <input id="{{$name}}-thumbnail" class="form-control" type="text" name="{{$name}}" style="width: 50%;float: left" value="{{@$value}}" multiple="">
    <a data-input="{{$name}}-thumbnail" data-preview="{{$name}}holder" class="btn btn-primary lfmfile" style="width: 50%">
    
        <i class="fa fa-file"></i> Browse
    </a>
</div>



@section('script')
    {!!Cms::script('theme/vendors-old/laravel-filemanager/js/lfm.js')!!}
<script>
    $('.lfmfile').filemanager('{{isset($type) ? $type : "file"}}');
</script>
@append
