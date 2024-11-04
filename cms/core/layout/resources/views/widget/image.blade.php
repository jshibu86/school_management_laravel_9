<div>
    <input id="{{$name}}-thumbnail" class="form-control" type="text" name="{{$name}}" style="width: 50%;float: left" value="{{@$value}}" multiple="">
    <a data-input="{{$name}}-thumbnail" data-preview="{{$name}}holder" class="btn btn-primary lfm" style="width: 50%">

        <i class="fa fa-picture-o"></i> Choose
    </a>
</div>



@section('script')
    {!!Cms::script('theme/vendors-old/laravel-filemanager/js/lfm.js')!!}
<script>
    $('.lfm').filemanager('{{isset($type) ? $type : "image"}}');
</script>
@append
