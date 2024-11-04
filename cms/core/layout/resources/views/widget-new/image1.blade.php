<input id="{{$id}}-thumbnail" class="form-control lfm" type="file" name="{{$id}}" style="width: 75%;float: left" value="{{@$value}}">
<a data-input="{{$id}}-thumbnail" data-preview="{{$id}}holder" class="btn btn-primary " style="width: 25%">

    <i class="fa fa-picture-o"></i> Choose
</a>

@section('script')
    {!!Cms::script('theme/vendors/laravel-filemanager/js/lfm.js')!!}
<script>
    $('.lfm').filemanager('{{isset($type) ? $type : "image"}}');
</script>
@append
