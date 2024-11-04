@extends('layout::admin.master')

@section('title', 'transportroute')
@section('style')
    <!-- Datatables -->
    @include('layout::admin.head.list_head')
    <link rel="stylesheet" href="{{ asset('assets/backend/css/attendance.css') }}">
    <style>
        .table-div table {
            width: 100% !important;
        }

        .error {
            display: none;
        }

        .input_text {
            width: 111% !important;
        }

        .html-body {
            font-family: roboto;
        }

        .main {
            margin: auto;
            max-width: 400px;
            height: 550px;
            background-image: url("background.png");
        }

        .container-main {
            border: 1px solid #00193F;
        }

        .para-main {
            text-align: center;
            padding-top: 35px;
            padding-bottom: 35px;
            font-weight: 800;
            font-size: 17px;
            color: #000f28;
        }

        .para1 {
            margin: auto;
            font-weight: 600;
            font-size: 20px;
            color: #4a4f4d;
            padding-top: 28px;
        }

        .para2 {
            margin: auto;
            padding-top: 8px;
            font-weight: 400;
            color: #4a4f4d;
        }

        .line1 {
            margin: auto;
            border: none;
            border-top: 1px double #333;
            width: 52%;
            color: #333;
            overflow: visible;
            text-align: center;
            height: 4px;
        }

        .line2 {
            margin: auto;
            border: none;
            border-top: 1px double #333;
            width: 27%;
            color: #4a4f4d;
            overflow: visible;
            text-align: center;
            height: 4px;
        }

        .para3 {
            margin: auto;
            padding-top: 8px;
            font-weight: 500;
            font-size: 11px;
            color: #4a4f4d;
        }

        .student-name {
            text-align: center;
            font-weight: 600;
            font-size: 14x;
            color: #4a4f4d;
        }

        .para1-stud {
            margin: auto;
        }

        .para-sub {
            text-align: center;
            padding-top: 29px;
            font-size: 12px;
            color: #4a4f4d;
        }

        .para1-sub {
            margin: auto;
            padding-bottom: 5px;
            text-align: center;
            width: 74%;
        }

        .signature {
            text-align: center;
            font-size: 12px;
            color: #4a4f4d;
            padding-top: 20px;
        }

        .logo_bottom {
            width: 100%;
            height: auto;
        }

        .bottom_top {
            height: 15px;
            background-color: #f3ce6e;
        }

        .bottom_center {

            height: 80px;
            background-color: #013e69;
        }

        #preview {
            margin: auto;
        }
    </style>
@endsection
@section('body')
    {{-- {{ Form::open(array('role' => 'form', 'route'=>array('certificatebulk'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'trasnportreport-form','novalidate' => 'novalidate')) }} --}}
    <div class="card">
        <div class="card-body">
            <div class="card-title btn_style">
                <h4 class="mb-0">Certificate Configurations</h4>


                {{-- @if (@$layout == 'create') --}}
                {{ Form::open(['role' => 'form', 'route' => ['configurationsstore'], 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'mark-form', 'novalidate' => 'novalidate']) }}
                {{-- @endif  --}}



                <div class="box-header with-border mar-bottom20">
                    {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset', 'class' => 'btn btn-danger btn-sm m-1  px-3']) }}


                    {{-- @if (@$layout == 'create')   --}}
                    {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', ['type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_certificate_configurations', 'value' => 'save', 'class' => 'btn btn-success btn-sm m-1  px-3']) }}
                    {{-- @endif --}}
                </div>

            </div>
            <hr />

            <div class="accordion" id="accordionExample">
                <div class="accordion-item">
                    <h1 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Get Configurations Data
                        </button>
                    </h1>
                    <div id="collapseOne" class=" atnaccodrdian accordion-collapse collapse show"
                        aria-labelledby="headingOne" data-bs-parent="#accordionExample" style="">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="col-xs-12 col-sm-12 col-md-6 ">
                                                <div class="item form-group">
                                                    <label class="control-label margin__bottom" for="status">Head Line
                                                        <span class="required">*</span>
                                                    </label>
                                                    <div class="feild">
                                                        <input id="head_line_id"
                                                            class="form-control col-md-7 col-xs-12 head_line input_text"
                                                            placeholder="Head Line" required="required" name="head_line"
                                                            type="text">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-sm-12 col-md-6 ">
                                                <div class="item form-group">
                                                    <label class="control-label margin__bottom" for="status">Tag Line1
                                                        <span class="required">*</span>
                                                    </label>
                                                    <div class="feild">
                                                        <input id="tag_line1_id"
                                                            class="form-control col-md-7 col-xs-12 tag_line1 input_text"
                                                            placeholder="Tag Line1" required="required" name="tag_line1"
                                                            type="text">
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-md-6">

                                            <div class="col-xs-12 col-sm-12 col-md-6 ">
                                                <div class="item form-group">
                                                    <label class="control-label margin__bottom" for="status">Remove
                                                        HeadLine<span class="required"></span>
                                                    </label>
                                                    <div class="feild">
                                                        <input id="remove_headline_id" type="checkbox" required="required"
                                                            name="remove_headline" >
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-sm-12 col-md-6 ">
                                                <div class="item form-group">
                                                    <label class="control-label margin__bottom" for="status">Remove
                                                        TagLine1<span class="required"></span>
                                                    </label>
                                                    <div class="feild">
                                                        <input id="remove_tagline1_id" type="checkbox" required="required"
                                                            name="remove_tagline1" >
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-6">

                                            <div class="col-xs-12 col-sm-12 col-md-6 ">
                                                <div class="item form-group">
                                                    <label class="control-label margin__bottom" for="status">Tag Line2
                                                        <span class="required"></span>
                                                    </label>
                                                    <div class="feild">
                                                        <input id="tag_line2_id"
                                                            class="form-control col-md-7 col-xs-12 tag_line2 input_text"
                                                            placeholder="Tag Line2" required="required" name="tag_line2"
                                                            type="text">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-sm-12 col-md-6 ">
                                                <div class="item form-group">
                                                    <label class="control-label margin__bottom" for="status">Name <span
                                                            class="required"></span>
                                                    </label>
                                                    <div class="feild">
                                                        <input id="name_id"
                                                            class="form-control col-md-7 col-xs-12 name input_text"
                                                            placeholder="name" required="required" name="name"
                                                            type="text">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="col-xs-12 col-sm-12 col-md-6 ">
                                                <div class="item form-group">
                                                    <label class="control-label margin__bottom" for="status">Remove
                                                        TagLine2<span class="required"></span>
                                                    </label>
                                                    <div class="feild">
                                                        <input id="remove_tagline2_id" type="checkbox"
                                                            required="required" name="remove_tagline2">
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-xs-12 col-sm-12 col-md-6 ">
                                                <div class="item form-group">
                                                    <label class="control-label margin__bottom" for="status">Remove
                                                        Name<span class="required"></span>
                                                    </label>
                                                    <div class="feild">
                                                        <input id="remove_name_id" type="checkbox" required="required"
                                                            name="remove_name" >
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-md-6">

                                            <div class="col-xs-12 col-sm-12 col-md-6 ">

                                                <div class="item form-group">
                                                    <label class="control-label margin__bottom"
                                                        for="status">Paragraph<span class="required"></span>
                                                    </label>
                                                    <div class="feild">
                                                        <textarea id="para1_id" class="form-control col-md-7 col-xs-12 para1_para input_text" rows="5" cols="50"
                                                            required="required" name="paragraph" type="text"></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-sm-12 col-md-6 ">

                                                <div class="item form-group">
                                                    <label class="control-label margin__bottom"
                                                        for="status">Signature<span class="required"></span>
                                                    </label>
                                                    <div class="feild">
                                                        <input id="signature_id"
                                                            class="form-control col-md-7 col-xs-12 signature input_text"
                                                            placeholder="Student name" required="required" name="signature"
                                                            type="text">
                                                       
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-md-6">

                                            <div class="col-xs-12 col-sm-12 col-md-6 ">
                                                <div class="item form-group">
                                                    <label class="control-label margin__bottom" for="status">Remove
                                                        Paragraph<span class="required"></span>
                                                    </label>
                                                    <div class="feild">
                                                        <input id="remove_para1_id" type="checkbox" required="required"
                                                            name="remove_paragraph" >                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-6 ">
                                                <div class="item form-group">
                                                    <label class="control-label margin__bottom" for="status">Remove
                                                        Signature<span class="required"></span>
                                                    </label>
                                                    <div class="feild">
                                                        <input id="remove_signature_id" type="checkbox"
                                                            required="required" name="remove_signature">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="col-xs-12 col-sm-12 col-md-6 ">
                                                <div class="item form-group">
                                                    <label class="control-label margin__bottom" for="status">Logo<span
                                                            class="required"></span>
                                                    </label>
                                                    <div class="feild">
                                                        <input id="logoimage" class="form-control input_text"
                                                            type="file" accept="image" rows="5" cols="50"
                                                            required="required" name="logo_image"
                                                            type="text" accept="image/png, image/jpeg"></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-sm-12 col-md-6 ">
                                                <div class="item form-group">
                                                    <label class="control-label margin__bottom" for="status">Remove
                                                        Logo<span class="required"></span>
                                                    </label>
                                                    <div class="feild">
                                                        <input id="remove_logoimage" type="checkbox" required="required"
                                                            name="logo_image" >                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-md-6">
                                            <div class="col-xs-12 col-sm-12 col-md-6 ">
                                                <div class="item form-group">
                                                    <label class="control-label margin__bottom"
                                                        for="status">Bottom-top<span class="required"></span>
                                                    </label>
                                                    <div class="feild">
                                                        <input type="color" id="top_colorpicker" value="#0000ff">
                                                        <input id="top_name"
                                                            class="form-control col-md-7 col-xs-12 name input_text"
                                                            placeholder="Bottom Top Color" required="required"
                                                            name="bottom_top" type="text">


                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-sm-12 col-md-6 ">
                                                <div class="item form-group">
                                                    <label class="control-label margin__bottom" for="status">Center<span
                                                            class="required"></span>
                                                    </label>
                                                    <div class="feild">
                                                        <input type="color" id="center_colorpicker" value="#0000ff">
                                                        <input id="center_name"
                                                            class="form-control col-md-7 col-xs-12 name input_text"
                                                            placeholder="Bottom Center Color" required="required"
                                                            name="bottom_center" type="text">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>



                                    </div>
                                </div>
                                 

                            <div class="col-md-6">

                                        <body class="html-body">
                                            <div class="main">
                                              <div class="container-main">
                                                   <div class="para-main">
                                                        <span><img id="preview" class="logo_image" src="{{ asset('assets/images/logo.png') }}" width="100" height="100"/></span>
                                                        <span><p class="para1">CERTIFICATE</p></span>
                                                        <span><p class="para2">OF ACHIEVEMENT</p></span>
                                                        <hr class="line1"></hr>
                                                        <span><p class="para3">THIS CERTIFICATE IS PROUDLY PRESENTED TO</p></span>
                                                     </div>
                                          
                                                    <div class="student-name">
                                                      <span><p class="para1-stud">MR.HIWORTH</p></span>
                                                    </div>
                                          
                                                    <div class="para-sub">        
                                                        <p class="para1-sub">
                                                          Lorem ipsum dolor sit amet,consectetur adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laboreet dolore
                                                        quis nostrud exerci tation ullamcorper suscipit laboris nisi ut aliquip ex ea commodo consequat.
                                                      </p>        
                                                    </div>
                                              
                                                    <div class="signature"> 
                                                     <span><p class="para1-sig2"></p></span>
                                                      <hr class="line2"></hr>
                                                      <span><p class="para1-sig">SIGNATURE</p></span>
                                                    </div>
                                                    <div class="bottom_top"> </div>
                                                    <div class="bottom_center"> </div>
                                                 </div>
                                               </div>
                                            </body>
                                        </div>  
                                        
                                  </div>   

                               </div> 
                                    
                             </div>                                
                             
                  
                                   </div>                                                                        
                                                                                                 
                                      
                                        
                            </div>
                                </div>
                    </div>
                </div>
            </div>
           
           
        </div>
       
        </div>
    </div>
</div>
{{ Form::close() }}
 

@endsection

@section("scripts")
<script type="module">
    function notify_script(title,text,type,hide) {
            new PNotify({
                title: title,
                text: text,
                type: type,
                hide: hide,
                styling: 'fontawesome'
            })
        }

  
     window.sectionurl='{{route('section.index')}}';
     window.transportstudent='{{ route('transportstudent.index') }}';
     window.getvehicle='{{ route('getstopvehicle') }}';
     window.termurl='{{ route('examterm.index') }}';
     window.classurl ='{{ route('schooltype.index') }}'
     window.transportreport ='{{ route('transportreport') }}'
     window.studentsurl='{{route('students.index')}}'
   
    AttendanceConfig.AttendanceInit(notify_script,"idcard");
    ReportConfig.ReportInit(notify_script);
    
    
</script>
  
<script>
   window.statuschange='{{route('transportroute_action_from_admin')}}';

  $(document).ready(function() {
      console.log("1");

$("#head_line_id").keypress(function() {
  $(".para1").html($("#head_line_id").val());
}); 
$("#tag_line1_id").keypress(function() {
  $(".para2").html($("#tag_line1_id").val());
}); 

$("#tag_line2_id").keypress(function() {
  $(".para3").html($("#tag_line2_id").val());
});

$("#name_id").keypress(function() {
  $(".para1-stud").html($("#name_id").val());
});


$("#para1_id").keypress(function() {
  $(".para1-sub").html($("#para1_id").val());
});

$("#signature_id").keypress(function() {
  $(".para1-sig2").html($("#signature_id").val());
});

$('#remove_headline_id').on('change', function (e) {
    console.log("2");
        $("#remove_headline_id").prop("checked", this.checked);
           if(this.checked){
            console.log("8");
            $("#head_line_id").val('');          
            $(".para1").html('')
     
        }
    });

    $('#remove_tagline1_id').on('change', function (e) {
    console.log("2");
        $("#remove_tagline1_id").prop("checked", this.checked);
           if(this.checked){
            console.log("8");
            $("#tag_line1_id").val('');          
            $(".para2").html('');
     
        }
    });

    $('#remove_tagline2_id').on('change', function (e) {
    console.log("2");
        $("#remove_tagline2_id").prop("checked", this.checked);
           if(this.checked){
            console.log("8");
            $("#tag_line2_id").val('');          
            $(".para3").html('');
     
        }
    });

    $('#remove_name_id').on('change', function (e) {
    console.log("2");
        $("#remove_name_id").prop("checked", this.checked);
           if(this.checked){
            console.log("8");
            $("#name_id").val('');          
            $(".para1-stud").html('');     
        }
    });

    $('#remove_para1_id').on('change', function (e) {
    console.log("2");
        $("#remove_para1_id").prop("checked", this.checked);
           if(this.checked){
            console.log("8");
            $("#para1_id").val('');          
            $(".para1-sub").html('');     
        }
    });

    $('#remove_signature_id').on('change', function (e) {
    console.log("2");
        $("#remove_signature_id").prop("checked", this.checked);
           if(this.checked){
            console.log("8");
            $("#signature_id").val('');          
            $(".para1-sig2").html('');     
        }
    });


    $('#remove_logoimage').on('change', function (e) {
    console.log("2");
        $("#remove_logoimage").prop("checked", this.checked);
           if(this.checked){
            console.log("8");
            // $('#preview').attr('src', '');

            document.querySelector('#preview').style.display = 'none';
            
        }
    });
    
    function readIMG(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                document.querySelector('#preview').style.display = 'block'; 
                $('#preview').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#logoimage").change(function(){
        readIMG(this);
    });

var backRGB = document.getElementById("top_colorpicker").value;

document.getElementById("top_colorpicker").onchange = function() {
  backRGB = this.value;
  $("#top_name").val(backRGB);
  console.log(backRGB);
  document.querySelector('.bottom_top').style.background = backRGB;

}

var backRGB = document.getElementById("center_colorpicker").value;

document.getElementById("center_colorpicker").onchange = function() {
  backRGB = this.value;
  $("#center_name").val(backRGB);
  console.log(backRGB);
  document.querySelector('.bottom_center').style.background = backRGB;
 
}
 });

    </script>

@endsection
