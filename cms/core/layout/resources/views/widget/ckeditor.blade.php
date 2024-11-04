<textarea class="form-control" id="{{ isset($id) ? $id : 'summary-ckeditor' }}" name="{{ $name }}">{{ @$content }}</textarea>
 <script src="//cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
<script>
    var options = {
        filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
        filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token=',
        filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
        filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token=',
        toolbar: ['Heading'],
    };
    var editorId = '{{ isset($id) ? $id : 'summary-ckeditor' }}';
    CKEDITOR.replace( editorId , {
        toolbar: [{
                name: 'styles',
                items: ['Styles', 'Format', 'Font', 'FontSize']
            },
            {
                name: 'paragraph',
                groups: ['list', 'indent', 'blocks', 'align', 'bidi'],
                items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote',
                    'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock',
                    '-', 'BidiLtr', 'BidiRtl', 'Language'
                ]
            },
            {
                name: 'colors',
                items: ['TextColor', 'BGColor']
            }, // Line break - next group will be placed in new line.
            {
                name: 'basicstyles',
                groups: ['basicstyles', 'cleanup'],
                items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-',
                    'CopyFormatting', 'RemoveFormat'
                ]
            },

            {
                name: 'clipboard',
                groups: ['clipboard', 'undo'],
                items: ['Undo', 'Redo']
            },
            { name: 'insert', items: ['Emoji'] },
        ]
    });
  
</script>
