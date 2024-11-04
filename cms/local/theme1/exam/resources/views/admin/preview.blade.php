<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

<style>
 
    .section_name{
        text-align: center;
    font-weight: bold;
    margin-top: 6px;
    }
    .school_config{
        display: flex;
    align-items: center;
    justify-content: space-between;
    }
    .previewimg{
        width: 50%;
    }
    .info_section{
        text-align: right;
    }
    .info_section p{
        margin: 0
    }
    .options{
        display: flex;
        flex-direction: column;
        margin-left: 10px;
    }
    .options_div{
        display: flex;
        justify-content: space-between
    }
    .exam_information p{
        margin: 0;
    }
</style>

<style>
    .page-header, .page-header-space {
  height: 50px;
}

.page-footer, .page-footer-space {
  height: 50px;

}

.page-footer {
  position: fixed;
  bottom: 0;
  width: 100%;

 
}

.page-header {
  position: fixed;
  top: 0mm;
  width: 100%;
 
 
}
table{
    width: 100%;
}



@page {
  margin: 20mm
}

@media print {
   thead {display: table-header-group;} 
   tfoot {display: table-footer-group;}
   
   button {display: none;}
   
   body {margin: 0;}
}
</style>
<style>
    
    @media print {
         @page {
        size: auto;  
        /* margin: auto;   */
             }
       
      
    }
</style>
</head>
<body>

  <div class="page-header" style="text-align: center">
    
    <br/>
    <button type="button" class="btn btn-primary btn-sm m-1  px-3 print">
      PRINT 
    </button>
  </div>

  {{-- <div class="page-footer">
    Schoolmanagement
  </div> --}}

  <table> 
    

    <thead>
      <tr>
        <td>
          <!--place holder for the fixed-position header-->
          <div class="page-header-space"></div>
        </td>
      </tr>
    </thead>

    <tbody>
      <tr>
        <td>
          <!--*** CONTENT GOES HERE ***-->
          
          <div class="page container" >
            <div class="card_ questioninfo long-div">
                    <div class="card-body">
                        {{-- <div class="card-title  text-center title">
                            <h4 class="mb-0">Questions</h4>
                            <small>Instruction : {{ @$istruction }}</small>
                        </div> --}}
                        

                        @if (@$data->uploaded_file !=null)
                        <div class="uploadquestion text-center">

                        </div>

                        @else
                        
                    
                        <section class="container print_section ">
                            <div class="row ">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="container-fluid">

                                        <div class="school_config">
                                            <div class="image_section">
                                                <img src="{{ asset(@$config->imagec) }}" class="previewimg"/>
                                            </div>

                                            <div class="info_section">
                                                <p>{{ @$config->school_name }}</p>
                                                <p>{{ @$config->place }},{{ @$config->city }},{{ @$config->country }}</p>
                                                <p>{{ @$config->school_phone }}</p>
                                                <p>{{ @$config->school_email }}</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="exam_information w-75 mx-auto text-center">

                                                <p>Academic Year : {{ str_replace("-","/",@$acyear) }}</p>
                                                <p>Term : {{ @$term }}</p>
                                                <p>Class&Section :{{ @$class }} </p>
                                                <p>Department : {{ @$department }}</p>
                                                <p>Subject : {{ @$subject }}</p>
                                            </div>
                                        </div>
                                        <h4 style="text-align: center;margin-top: 1.5rem;">{{@$exam_title}}</h4>
                                        <h5 class="quspaper_head mt-4">Instructions:</h5>
                                        <p>{{ @$istruction }}</p>
                                        {{-- <h3 class="quspaper_head text-center">Session {{ @$ayear  }}</h3>
                                        <h3 class="quspaper_head text-center" >Subject : {{ @$subject }}</h3> --}}
                                        <div class="d-flex justify-content-between mt-4">
                                            @php
                                                $time = $time;
                                                $hour = substr($time, 0, 2);
                                                $minute = substr($time, 3, 2);
                                                $formattedTime = $hour . "hr:" . $minute . "min";
                                            @endphp
                                            <h6><b>Time - {{ @$time }}</b></h6>
                                            <h6><b>Full Mark - {{ @$totalmark }}</b></h6>

                                        </div>
                                        {{-- <h6 class="my-4 text-center"><i>Read the instructions carefully before attempting questions from each group.</i></h6> --}}


                                        <section class=" print_section ">
                                            <div class="row ">
                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                    
                                                    <div class="w-75 mx-auto">

                                                        @if(!empty(@$section))

                                                        @php
                                                            $count=0;
                                                        @endphp

                                                        @foreach (@$section as $sections )

                                                            <div class="section_name">

                                                            <p>{{ucfirst( @$sections[0]) }}</p>
                                                            </div>

                                                            @if (isset( $sections["questions"]))
                                                            
                                                        

                                                            @foreach (
                                                                $sections["questions"]
                                                                as $type => $question
                                                            )

                                                            @php
                                                                $count++;
                                                            @endphp


                                                            @if($type == "fillblanks")
                                                    
                                                            @foreach ($question as $order => $ques)
                                                            <div class="qus_box mt-3">
                                                                <h6 class="d-flex justify-content-between">
                                                                    <div class="d-flex">
                                                                        <span>{{ @$count++ }}.&nbsp;</span>
                                                                        <span> {{ $ques[0] }}</span>
                                                                    </div>
                                                                    
                                                                    <span><b>[{{ $ques["mark"][0] }}]</b></span>
                                                                </h6>
                                                                {{-- <h6 class="px-5">Answer</h6> --}}
                                                            </div>
                                                    
                                                            @endforeach
                                                            @php
                                                            $count=$count-1;
                                                            @endphp
                                                            @endif

                                                        @if($type == "choose_best")
                                                        @foreach ($question as $order => $ques)
                                                        <div class="qus_box mt-3">
                                                            <h6 class="d-flex justify-content-between">
                                                            <div class="d-flex">
                                                                    <span>{{ @$count++ }}.&nbsp;</span>
                                                                    <span> {{ $ques[0] }}</span>
                                                                </div>
                                                            
                                                                <span><b>[{{ $ques["mark"][0] }}]</b></span>
                                                            </h6>
                                                            <div class="row options_div text-left px-5">

                                                                <div class="options">
                                                                    @foreach ($ques["options"] as $answer)
                                                                    <span class=" py-1">{{ chr($loop->index +65) }}. {{ @$answer }}</span>
                    
                                                                    @endforeach
                                                                </div>

                                                                <div class="image_">
                                                                    <div class="px-5">
                                                                        @if (isset($ques['image'][0]))
                                                                        @php
                                                                        header('Content-Type: image/jpeg');
                        
                                                                        //$fileData = readfile($ques[0]);
                        
                                                                        $fileEncode = base64_encode(file_get_contents($ques['image'][0]));
                        
                                                                        $data="data:image/png;base64,${fileEncode}";
                        
                                                                    @endphp
                        
                        
                                                                    <img src="{{ $data }}" width="50" class="img-fluid quspaper_type py-1" alt="Type Questions Image" />
                                                                    @else
                                                                    @if (isset($ques['oldimage'][0]))
                                                                    
                                                                    <img src="{{ asset($ques['oldimage'][0])}}" width="50" class="img-fluid quspaper_type py-1" alt="Type Questions Image" />

                                                                    
                                                                        @endif
                                                                        @endif
                                                                    
                                                                    </div>
                                                                </div>
                                                            
                                                            </div>

                                                        
                                                        </div>

                                                        @endforeach
                                                        @php
                                                        $count=$count-1;
                                                        @endphp
                                                        @endif
                                                        @if($type == "yesorno")
                                                        @foreach ($question as $order => $ques)
                                                        <div class="qus_box mt-3">
                                                            <h6 class="d-flex justify-content-between">
                                                                <div class="d-flex">
                                                                    <span>{{ @$count++ }}.&nbsp;</span>
                                                                    <span> {{ $ques[0] }}</span>
                                                                </div>
                                                                
                                                                <span><b>[{{ $ques["mark"][0] }}]</b></span>
                                                            </h6>
                                                            <div class="row options_div text-left px-5">
                                                                <div class="options">
                                                                    @foreach ($ques["options"] as $answer)
                                                                    <span class=" py-1">{{ chr($loop->index +65) }}. {{ @$answer }}</span>
                    
                                                                    @endforeach
                                                                </div>

                                                                <div class="image_">
                                                                    <div class="px-5">

                                                                        @if (isset($ques['image'][0]))
                                                                        @php
                                                                        header('Content-Type: image/jpeg');
                        
                                                                        //$fileData = readfile($ques[0]);
                        
                                                                        $fileEncode = base64_encode(file_get_contents($ques['image'][0]));
                        
                                                                        $data="data:image/png;base64,${fileEncode}";
                        
                                                                    @endphp
                        
                        
                                                                    <img src="{{ $data }}" width="50" class="img-fluid quspaper_type py-1" alt="Type Questions Image" />

                                                                    @else
                                                                        @if (isset($ques['oldimage'][0]))
                                                                    
                                                                    <img src="{{ asset($ques['oldimage'][0])}}" width="50" class="img-fluid quspaper_type py-1" alt="Type Questions Image" />

                                                                    
                                                                        @endif
                                                                        @endif
                                                                    
                                                                    </div>
                                                                </div>
                                                            
                                                            </div>

                                                        
                                                        </div>

                                                        @endforeach
                                                        @php
                                                        $count=$count-1;
                                                        @endphp
                                                        @endif

                                                        @if($type == "typequs")
                                                        @foreach ($question as $order => $ques)
                                                        <div class="qus_box mt-3">
                                                            <h6 class="d-flex justify-content-between">

                                                                <div class="d-flex">
                                                                    <span>{{ @$count++ }}.&nbsp;</span>
                                                                    <span> {{ $ques["question"][0] }}</span>
                                                                </div>
                                                                
                                                            </h6>
                                                            <div class="px-5">
                                                                @php
                                                                header('Content-Type: image/jpeg');

                                                                //$fileData = readfile($ques[0]);

                                                                $fileEncode = base64_encode(file_get_contents($ques[0]));

                                                                $data="data:image/png;base64,${fileEncode}";

                                                                @endphp


                                                                <img src="{{ $data }}" width="50" class="img-fluid quspaper_type py-1" alt="Type Questions Image" />
                                                            </div>
                                                        </div>
                                                        @endforeach


                                                        @endif

                                                        @if($type == "shortques")
                                                        @foreach ($question as $order => $ques)
                                                        <div class="qus_box mt-3">
                                                            <h6 class="d-flex justify-content-between gap-5">
                                                            <div class="d-flex"><span>{{ @$count++ }}.&nbsp;</span><span>{{ $ques[0] }}</span></div>
                                                            
                                                                <span><b>[{{ $ques["mark"][0] }}]</b></span>
                                                            </h6>
                                                            {{-- <h6 class="px-5">Answer</h6> --}}
                                                        </div>
                                                        @endforeach
                                                        @php
                                                        $count=$count-1;
                                                        @endphp
                                                        @endif


                                                        @if($type == "sub_ques")
                                                        @foreach ($question as $order => $ques)

                                                        @php
                                                            $mark=$ques['mark'];
                                                            unset($ques['mark']);
                                                        @endphp
                                                        <div class="qus_box mt-3">

                                                            @for ($i=0;$i<sizeof($ques);$i++)

                                                            <h6 class="d-flex justify-content-between">

                                                                @if ($i==0)

                                                                <div class="d-flex">
                                                                    <span>{{ @$count++ }}.{{ chr($i +65) }}.&nbsp;</span>
                                                                    <span> {{ $ques[$i] }}</span>
                                                                </div>
                                                                
                                                                <span><b>[{{ $mark[$i] }}]</b></span>

                                                                @else

                                                                <div class="d-flex">
                                                                    <span>&nbsp;&nbsp;&nbsp;{{ chr($i +65) }}.</span>
                                                                    <span> &nbsp;{{ $ques[$i] }}</span>
                                                                </div>
                                                                
                                                                <span><b>[{{ $mark[$i] }}]</b></span>
                                                                @endif
                                                            
                                                            </h6>
                                                                
                                                            @endfor
                                                            
                                                            {{-- <h6 class="px-5">Answer</h6> --}}
                                                        </div>
                                                        @endforeach
                                                        @php
                                                        $count=$count-1;
                                                        @endphp
                                                        @endif
                                                        @if($type == "longques")
                                                        @foreach ($question as $order => $ques)
                                                        <div class="qus_box mt-3">
                                                            <h6 class="d-flex justify-content-between">
                                                                <div class="d-flex">
                                                                    <span>{{ @$count++ }}.&nbsp;</span>
                                                                    <span>{{ ucfirst($ques[0]) }}</span>
                                                                </div>
                                                             
                                                                <span><b>[{{$ques["mark"][0] }}]</b></span>

                                                                
                                                            
                                                            </h6>
                                                            {{-- <h6 class="px-5">Answer</h6> --}}
                                                        </div>
                                                        @endforeach
                                                        @php
                                                        $count=$count-1;
                                                        @endphp
                                                        @endif
                                                    
                                                    

                                                        @endforeach
                                                        @else
                                                        <small class="text-danger text-center" style="display: block">Please add Any question  to this Section</small>
                                                        @endif


                                                        @php
                                                            $count=0;
                                                        @endphp
                                                        @endforeach
                                                        @endif

                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </section>










                                        {{-- <h6 class="mt-5 text-center">page <b>1</b> of <b>2</b></h6> --}}

                                    </div>
                                </div>
                            </div>
                        </section>
                        
                        <div class="footer">
                            
                        </div>
                        @endif




                        {{-- <div class="questions__section">

                            @foreach (@$examquestions->questions as  $question)
                            @if($question->question_type == "fillintheblanks")
                            <div class="exam_question_fill">
                                <div class="exam_question">
                                    <p><small>{{ @$loop->index+1 }} : </small>{{ @$question->question }} ?</p>
                                </div>

                            </div>
                            @endif
                            @if($question->question_type == "choosebest")

                            @endif
                            @if($question->question_type == "definequestion")

                            @endif
                            @if($question->question_type == "shortquestion")

                            @endif
                            @if($question->question_type == "longquestion")

                            @endif
                            @endforeach
                        </div> --}}
                    </div>
                </div>
          </div>
        </td>
      </tr>
    </tbody>

    <tfoot>
      <tr>
        <td>
          <!--place holder for the fixed-position footer-->
          <div class="page-footer-space"></div>
        </td>
      </tr>
    </tfoot>

  </table>

</body>

</html>







        
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>

<script>
     var button=document.querySelector(".print");

button.addEventListener("click",function(){

    button.style.display = "none";

    window.print();
    button.style.display = "block";
});
</script>

{{-- <script>
    window.onload = function() {
      var longDiv = document.querySelector('.long-div');
      var headerHeight = document.querySelector('.header').offsetHeight;
      var longDivBottom = longDiv.getBoundingClientRect().bottom;
      var windowHeight = window.innerHeight;

      if (longDivBottom > windowHeight) {
        var additionalSpace = longDivBottom - windowHeight + headerHeight;
        longDiv.style.paddingTop = additionalSpace + 'px';
      }
    };
  </script> --}}
