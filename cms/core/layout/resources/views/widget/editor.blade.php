@section("style")
 <!---summernote--->
 <link rel="stylesheet" href="{{asset('assets/backend/js/summernote/summernote.css')}}">
  <!-- summernote -->
  <link rel="stylesheet" href="{{asset('assets/backend/js/summernote/summernote-bs4.min.css')}}">
@endsection

<textarea rows="15" required="required" name="{{$name}}" class="form-control my-editor editor" id="summary-ckeditor">
    {!! old('content', @$value) !!}
</textarea>

@section('script')
<script src="{{asset('assets/backend/js/summernote/summernote.js')}}"></script>
<!-- Summernote -->
<script src="{{asset('assets/backend/js/summernote/summernote-bs4.min.js')}}"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/23.0.0/classic/ckeditor.js"></script>

{{-- //summernote --}}
<script>
    $(document).ready(function() {

        $('.editora').summernote({
          placeholder: 'Hello Type Here..',
          tabsize: 2,
          height: 300
        });
    });
  </script>

{{-- <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script> --}}
<script src="{{ asset("assets/backend/js/tinymce/tinymce/tinymce.min.js") }}"></script>




    <script>
        var editor_config = {
            path_absolute : "/",
            selector: "textarea.my-editor",
            plugins: [
                "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars code fullscreen",
                "insertdatetime media nonbreaking save table contextmenu directionality",
                "emoticons template paste textcolor colorpicker textpattern"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
            relative_urls: false,
            file_browser_callback : function(field_name, url, type, win) {
                var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
                var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

                var cmsURL = editor_config.path_absolute + 'laravel-filemanager?field_name=' + field_name;
                if (type == 'image') {
                    cmsURL = cmsURL + "&type=Images";
                } else {
                    cmsURL = cmsURL + "&type=Files";
                }

                tinyMCE.activeEditor.windowManager.open({
                    file : cmsURL,
                    title : 'Filemanager',
                    width : x * 0.8,
                    height : y * 0.8,
                    resizable : "yes",
                    close_previous : "no"
                });
            }
        };

        tinymce.init(editor_config);
    </script>
@append