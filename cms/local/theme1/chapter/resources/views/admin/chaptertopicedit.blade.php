@extends('layout::admin.master')

@section('title','subject mapping')
@section('style')
<style>
    .tab__section{
        background-color: white;
    padding: 15px;
    box-shadow: rgb(0 0 0 / 10%) 0px 1px 3px 0px, rgb(0 0 0 / 6%) 0px 1px 2px 0px;
   
    border-radius: 7px;
    }
    .tabs-left>li.active>a {
    color: #fff !important;
    cursor: default;
    background-color: #2a3f54 !important;
    border: 1px solid #ddd;
    border-bottom-color: transparent;
}
.heading__tab{
    text-align: center;
    margin-bottom: 20px;
}
.heading__tab h4{
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 0px !important;
}
.small__header{
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
}
.btn-group{
    display: block!important;
}
.doc_ .active{
    background-color: #2a3f54!important;
    color: white!important;
    border-color: #2a3f54!important;
}
.btn-file {
    position: relative;
    overflow: hidden;
}
.btn-file input[type=file] {
    position: absolute;
    top: 0;
    right: 0;
    min-width: 100%;
    min-height: 100%;
    font-size: 100px;
    text-align: right;
    filter: alpha(opacity=0);
    opacity: 0;
    outline: none;   
    cursor: inherit;
    display: block;
}
.btn-secondary{
        background-color: #673ab7;
        /* width: 111px!important; */
    }
    input[type="file"] {
  display: block;
}
.imageThumb {
  max-height: 75px;
 
  padding: 1px;
  cursor: pointer;
}
.pip {
  display: inline-block;
  margin: 10px 10px 0 0;
}
.remove,.remove_ {
  display: block;
  background: rgb(213, 16, 16);
 
  color: white;
  text-align: center;
  cursor: pointer;
  padding: 5px;
    border-radius: 50px;
    font-size: 8px;
}

</style>

@endsection
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('chaptertopic.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left ', 'id' => 'subjectmapping-form','novalidate' => 'novalidate')) }}
       
        @endif

        @if (@$layout=="edit")

        {{ Form::open(array('role' => 'form', 'route'=>array('chaptertopic.update',@$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left ', 'id' => 'subjectmapping-form','novalidate' => 'novalidate')) }}
            
        @endif
        <div class="box-header with-border mar-bottom20">
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', ['type' => 'submit','class' => 'btn btn-success btn-sm m-1  px-3']) }}

            <a class="btn btn-info btn-sm m-1  px-3" href="{{route('chapter.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

            {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }}
          
            @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit ChapterTopic" : "Create New Chapter Topic"])
           
            
        </div>

        

        {{-- <div class="col-xs-12">
           
                <div class="col-12">
                    <div class="chapter__container">
                        <h4>{{ ucfirst(@$chaptername) }}</h4>
                       
                        <p>{{ @$classname }} - {{ @$sectionname }}</p>
                        
                        <p>{{ @$subjectname }} Subject</p>
                    </div>
                </div>
                
            
        </div> --}}
        <input type="hidden" name="class_id" value="{{ @$class_id }}"/>
        <input type="hidden" name="section_id" value="{{ @$section_id }}"/>
        <input type="hidden" name="subject_id" value="{{ @$subject_id }}"/>
        <input type="hidden" name="chapter_id" value="{{ @$chapter_id }}"/>

        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <h4>Chapter Topic</h4>
                    <hr/>
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="topic_name">Chapter Name <span class="required">*</span>
                                    </label>
                                    <div class="">
                                        {{Form::text('topic_name',@$chaptername,array('id'=>"topic_name",'class'=>"form-control col-md-7 col-xs-12 " ,
                                        'placeholder'=>"e.g Topic 1","disabled"))}}
                                    </div>
                                </div>
                            </div>
                            
                            
                           
                           
                           
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="topic_name">Topic Name <span class="required">*</span>
                                    </label>
                                    <div class="">
                                        {{Form::text('topic_name',@$data->topic_name,array('id'=>"topic_name",'class'=>"form-control col-md-7 col-xs-12 " ,
                                        'placeholder'=>"e.g Topic 1",'required'=>"required"))}}
                                    </div>
                                </div>
                            </div>
                           
                
                             
                           
                            
                            {{-- <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="school_name">Subject <span class="required">*</span>
                                    </label>
                                    <div class="feild">
                                    {{ Form::select('subject_id',[],@$data->subject_id ,
                                    array('id'=>'subject_id','class' => 'form-control','required'=>"required","placeholder"=>"Select Subject " )) }}
                                    </div>
                                </div>
                            </div> --}}
                            {{-- <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="school_name">Teacher <span class="required">*</span>
                                    </label>
                                    <div class="feild">
                                    {{ Form::select('teacher_id',[],@$data->teacher_id ,
                                    array('id'=>'teacher_id','class' => 'form-control','required'=>"required","placeholder"=>"Select Teacher " )) }}
                                    </div>
                                </div>
                            </div> --}}
                               
                               
                                
                               
                        </div>
                        <br/>
                        <div class="row">
                           
                            <div class="col-xs-12 col-sm-12 col-md-9">
                                <div class="item form-group">
                                    <label for="thumbnail" class="control-label margin__bottom">Select Chapter Media Source</label>
                                    <div class="btn-group " data-toggle="buttons">
                                        @if (@$layout=="edit")
                                        <label class="btn btn-default {{ @$type=="image" ? "active":"" }}">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="documenttype" id="documenttype" value="image" {{ @$type=="image" ? "checked": "" }} onchange="handleChange(this);">
                                                <label class="form-check-label" for="flexRadioDefault1">Image</label>
                                            </div>
                                          
                                        </label> 
                
                                        @else
                                        <label class="btn btn-default active">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="documenttype" id="documenttype_1" value="image" checked onchange="handleChange(this);">
                                                <label class="form-check-label" for="documenttype_1">Image</label>
                                            </div>
                                            
                                        </label> 
                                            
                                        @endif
                                       
                                        <label class="btn btn-default {{ @$type=="document" ? "active":"" }}" >
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="documenttype" id="documenttype_2" value="document" {{ @$type=="document" ? "checked": "" }} onchange="handleChange(this);">
                                                <label class="form-check-label" for="documenttype_2">Document</label>
                                            </div>
                                           
                                        </label> 
                                        <label class="btn btn-default {{ @$type=="video" ? "active":"" }}">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="documenttype" id="documenttype_3" value="video" {{ @$type=="video" ? "checked": "" }} onchange="handleChange(this);">
                                                <label class="form-check-label" for="documenttype_3">Video url</label>
                                            </div>
                                         
                                        </label> 
                                       
                                    </div>
                                </div>
                               
                            </div>
                            
                        </div>
                        <br/>
                        <div class="row chapter_image">
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="topic_name">Choose Images(multiple) <span class="required"></span>
                                    </label>
                                   
                                    <div class="chapter_image_display">
                                        <input type="file" id="chapter_image" name="chapter_image[]" multiple accept="image/*" data-placeholder="No file" data-classIcon="icon-plus">
                                        @if (@$layout=="edit")
                                        @foreach ($data->contents as $content)
                                        @if ($content->content_type =="image")
                                        <span class="pip"><img name="chapter__image[]" class="imageThumb" src="{{ $content->content_url }}" title="icons8-circled-user-male-skin-type-4-96.png"><br><span id="{{ $content->id }}" class="remove_" title="icons8-circled-user-male-skin-type-4-96.png">Remove image</span></span>
                                        @endif
                                       
                                        @endforeach
                                        @endif
                                 
                                   
                                        <div class="row" id="preview"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row chapter_document" style="display:none;">
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="topic_name">Choose Documents(multiple) <span class="required"></span>
                                    </label>
                                    <div class="chapter_image_document">
                                        <input type="file" id="chapter_document" name="chapter_document[]" multiple accept=".pdf,.ppt,.doc,.docx,application/msword" data-placeholder="No file" data-classIcon="icon-plus">
                                        @if (@$layout=="edit")
                                        @foreach ($data->contents as $key=> $content)
                                        @if ($content->content_type =="document")
                                        <span class="pip"><a href="{{ @$content->content_url }}" target="_blank">Document-{{ @$key+1 }}</a><br><span id="{{ $content->id }}" class="remove_" title="icons8-circled-user-male-skin-type-4-96.png">Remove</span></span>
                                        @endif
                                       
                                        @endforeach
                                        @endif
                                       
                                           
                                           
                                            
                                       
                                 
                                   
                                        <div class="row" id="preview"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class ="" style="display:none;">
                            <table id="sample_table">
                                <tr id="">
                                    <td ><span class="sn">1</span>.</td>
                                    <td > 
                                      <div class="condition condition1">
                                        {{Form::text('chapter_vedio[]',@$data->chapter_vedio,array('id'=>"chapter_vedio",'class'=>"form-control col-md-7 col-xs-12 " ,
                                        'placeholder'=>"Enter Url"))}}
                                      </div>
                                      </td>
                                      <td ><a class="btn btn-xs delete-record" data-id="1"><i class="fa fa-trash"></i></a></td>
                                </tr>
                           
                            </table>
                        </div>
                            
                        <div class="row chapter_vedio" style="display:none;">
                            <div class="col-xs-12 col-sm-12 col-md-9">
                                <div class="clearfix btn_style ">
                                    <a class="btn btn-primary pull-right add-record" data-added="0"><i class="fa fa-plus"></i> Add Url</a>
                                  </div>
                
                                  <table class="table" id="tbl_posts">
                                    <thead>
                                      <tr>
                                        <th>#</th>
                                        <th>Video url</th>
                                       
                                        <th>Action</th>
                                      </tr>
                                    </thead>
                                    @if (@$layout=="edit")
                                    <tbody id="tbl_posts_body">
                                        @foreach (@$data->contents as $key =>$content )
                                           @if ($content->content_type=="video")
                                           <tr id="rec-{{ @$key+1 }}">
                                            <td ><span class="sn">{{ $key+1 }}</span>.</td>
                                            <td > 
                                              <div class="condition condition1">
                                                {{Form::text('chapter_vedio[]',@$content->content_url,array('id'=>"chapter_vedio",'class'=>"form-control col-md-7 col-xs-12 " ,
                                                'placeholder'=>"Enter Url"))}}
                                              </div>
                                              </td>
                                              <td ><a class="btn btn-xs delete-record" data-id="{{ @$key+1 }}"><i class="fa fa-trash"></i></a></td>
                                        </tr>
                                           @endif
                                        @endforeach
                                       
                                    </tbody>
                                    @else
                                    <tbody id="tbl_posts_body">
                                        <tr id="rec-1">
                                            <td ><span class="sn">1</span>.</td>
                                            <td > 
                                              <div class="condition condition1">
                                                {{Form::text('chapter_vedio[]',@$data->chapter_vedio,array('id'=>"chapter_vedio",'class'=>"form-control col-md-7 col-xs-12 " ,
                                                'placeholder'=>"Enter Url"))}}
                                              </div>
                                              </td>
                                             <td></td>
                                        </tr>
                                    </tbody>
                                        
                                    @endif
                                   
                                  </table>
                
                            </div>
                           
                        </div>
                
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-9">
                                <div class="item form-group">
                                    <label for="thumbnail" class="control-label margin__bottom">Chapter Text<span>*</span></label>
                                    <div class="">
                                    <span class="input-group-btn">
                                        @include('layout::widget.ckeditor',['name'=>'description','id'=>'schoolicon','content'=>@$data->topic_description])   
                                    </span>
                                    <img id="schooliconholder" style="max-height:50px;" src="{{ @$data->schoolicon }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                       
                        {{Form::close()}}
                           
                            <!-- //status -->
                        </div>
                       
                    </div>
                </div>
            </div>
        </div>
      
   

</div>

        
    

@endsection
@section('scripts')

<script >
  $(document).ready(function() {
    var type={!! json_encode($type) !!};
    var layout={!! json_encode($layout) !!};
    if(layout=="edit")
    {
        if(type=="image")
        {
            $('.chapter_image').show();
            $('.chapter_document').hide();
            $('.chapter_vedio').hide();
            console.log("gere",type);

        }else if (type=="document") {
            $('.chapter_image').hide();
            $('.chapter_document').show();
            $('.chapter_vedio').hide();
            console.log("gere",type);
            
        } else if(type=="video") {
            $('.chapter_image').hide();
            $('.chapter_document').hide();
            $('.chapter_vedio').show();
            console.log("gere",type);
            
        }
    }
  });
   

    
</script>
@endsection

@section('script_link')

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-filestyle/2.1.0/bootstrap-filestyle.min.js" integrity="sha512-HfRdzrvve5p31VKjxBhIaDhBqreRXt4SX3i3Iv7bhuoeJY47gJtFTRWKUpjk8RUkLtKZUhf87ONcKONAROhvIw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- validator -->
    {!! Cms::script("theme/vendors/validator/validator.js") !!}

<script>
     $('#chapter_image').filestyle({
                buttonName : 'btn btn-success',
                buttonText : 'Open',
                placeholder: "No Image Choosen",
                classIcon: "icon-plus"
            });
            $('#chapter_document').filestyle({
                buttonName : 'btn btn-success',
                buttonText : 'Open',
                placeholder: "No Document Choosen",
                classIcon: "icon-plus"
            });

            $('.btn-secondary').text('Choose Files').css('color','#ffffff');
</script>
<script>
    $(document).ready(function() {
        const dt = [];
  if (window.File && window.FileList && window.FileReader) {
    $("#chapter_image").on("change", function(e) {
      var files = e.target.files,
        filesLength = files.length;
        for (let file of files) {
		dt.push(file);
	}
    console.log("before",dt);
      for (var i = 0; i < filesLength; i++) {
        var f = files[i]
        var fileReader = new FileReader();
        fileReader.onload = (function(e) {
          var file = e.target;
        
          $("<span class=\"pip\">" +
            "<img name=\"chapter__image[]\" class=\"imageThumb\" src=\"" + e.target.result + "\" title=\"" + f.name + "\"/>" +
            "<br/><span id=\"" + i + "\" class=\"remove\" \" title=\"" + f.name + "\">Remove image</span>" +
            "</span>").insertAfter(".chapter_image_display");
          $(".remove").click(function(){
            let name = $(this).attr("title");
          
            $(this).parent(".pip").remove();
          
            const dt = new DataTransfer()
            const input = document.getElementById('chapter_image')
            const { files } = input
            
            for (let i = 0; i < files.length; i++) {
                const file = files[i]
                console.log(file.name,"from name");
               if (name !== file.name)
                {
                    console.log("not equal")
                    dt.items.add(file)
                }else{
                    console.log("equal")
                }
                
                // here you exclude the file. thus removing it.
            }
            
            input.files = dt.files // Assign the updates list
            
           
          });
         
          // Old code here
          /*$("<img></img>", {
            class: "imageThumb",
            src: e.target.result,
            title: file.name + " | Click to remove"
          }).insertAfter("#files").click(function(){$(this).remove();});*/
          
        });
        fileReader.readAsDataURL(f);
      }
     // console.log(files);
    });
  } else {
    alert("Your browser doesn't support to File API")
  }
});
</script>
<script>
  
    $(document).delegate('a.add-record', 'click', function(e) {
     e.preventDefault();    
     var content = $('#sample_table tr'),
     size = $('#tbl_posts >tbody >tr').length + 1,
     element = null,   
     element = content.clone();
     element.attr('id', 'rec-'+size);
     element.find('.delete-record').attr('data-id', size);
    
     
     element.appendTo('#tbl_posts_body');
     element.find('.sn').html(size);
    
   });
   $(document).delegate('a.delete-record', 'click', function(e) {
     e.preventDefault();    
     //var didConfirm = confirm("Are you sure You want to delete");
    
      var id = $(this).attr('data-id');
      var targetDiv = $(this).attr('targetDiv');
      $('#rec-' + id).remove();
      
    //regnerate index number on table
    $('#tbl_posts_body tr').each(function(index) {
      //alert(index);
      $(this).find('span.sn').html(index+1);
    });
    return true;
  
});

    </script>

<script>
    function handleChange(src){

      
       

        if(src.value=="image")
        {
            $('.chapter_image').show();
            $('.chapter_document').hide();
            $('.chapter_vedio').hide();
            console.log("gere",src.value);

        }else if (src.value=="document") {
            $('.chapter_image').hide();
            $('.chapter_document').show();
            $('.chapter_vedio').hide();
            console.log("gere",src.value);
            
        } else if(src.value=="video") {
            $('.chapter_image').hide();
            $('.chapter_document').hide();
            $('.chapter_vedio').show();
            console.log("gere",src.value);
            
        }

    }
</script>
<script type="module">
      function notify_script(title,text,type,hide) {
            new PNotify({
                title: title,
                text: text,
                type: type,
                hide: hide,
                styling: 'bootstrap3'
            })
        }
    window.deletecontent="{{ route('chaptertopic.index') }}";
 
      $(".remove_").click(function(){
            let id = $(this).attr("id");
            AcademicConfig.DeleteContent(id,notify_script)
          
            $(this).parent(".pip").remove();});
</script>

@endsection
