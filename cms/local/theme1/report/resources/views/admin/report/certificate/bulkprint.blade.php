<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Certificate_School</title>

    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="assets/fontawesome/css/font-awesome.min.css" />
</head>

           @php
          $bottomTopColor = Configurations::getbottomtopcolor();
           @endphp
          @php
          $bottomCenterColor = Configurations::getbottomcentercolor();
          @endphp 


<style>
    .html-body {
        font-family: roboto;
    }

    .button-class a {
        background-color: black;
        padding: 12px;
        color: white;
        text-decoration: none;
    }

    .main {
        /* padding-top: 50px; */
        background-image: url("{{ asset('assets/images/background.png') }}");
    }

    .main {
        border: 3px solid #00193F;
    }
    /* .main {
        border: 1px solid #00193F;
    } */
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
        font-size: 38px;
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
        border: none;
        border-top: 1px double #333;
        width: 52%;
        color: #333;
        overflow: visible;
        text-align: center;
        height: 4px;
    }

    .line2 {
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
        font-size: 28px;
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
        padding-top: 52px;
    }

    .logo_bottom {
        width: 100%;
        height: auto;
    }

    .main {
      
        width: 50%;
        margin: auto;
    }

    .bottom_top {
            height: 15px;         
             background-color: {{  $bottomTopColor ?? "#f3ce6e" }}; 

       }

        .bottom_center {

            height: 80px;
           background-color: {{  $bottomCenterColor ?? "#013e69" }};         
        }



    @media print {
        .pagebreak {
            page-break-before: always;
        }

        .button-back {
            display: none !important;
        }

        .main {
            width: 100%;
            margin-top: 50px;

        }

        @page {
            size: A4;
            margin: 0;
        }

        
        /* .sub-main {
            margin: auto;
            height: 1300px;
        }

        .para-sub-main {
            padding-top: 50px;
        } */

        /* .student-name {
            padding-top: 100px;
            text-align: center;
            font-weight: 600;
            font-size: 50px;
            color: #4a4f4d;
        }


        .para1-sub {
            padding-top: 50px;
            text-align: center;
            width: 74%;
            font-size: 26px;
            color: #4a4f4d;
        }

        .para1 {
            font-size: 75px;
        }

        .para2 {
            margin: auto;
            padding-top: 8px;
            font-weight: 400;
            font-size: 35px;
            color: #4a4f4d;
        }

        .para3 {
            margin: auto;
            padding-top: 8px;
            font-weight: 500;
            font-size: 25px;
            color: #4a4f4d;
        }

        .signature {
            padding-top: 200px;
            font-weight: 500;
            font-size: 25px;

        }

        .logo_bottom {
            width: 100%;
            height: auto;
        } */

    }
</style>
<div class="button-class">
    <a href="{{ route('certificatebulkreport') }}" class="button-back">Back</a>
    <a href="#" class="button-print">Print</a>
</div>

<body class="html-body">

    @foreach (@$students as $student)
      <div class="main">
        {{-- @if(empty($configurations) || is_null($configurations) || !$configurations )  --}}
 
        @if($configurations)
            @foreach ($configurations as $config)
                <div class="container-main">
              <div class="sub-main">
                  <div class="para-main">
                      <span><img class="logo_image" src=" {{$config->logo_image ?? asset('assets/images/logo.png') }}" width="100"
                              height="100" /></span>
                      <div class="para-sub-main">

                          <span>
                             <p class="para1">{{$config->head_line ??  "CERTIFICATE"  }}</p>
                              {{-- <p class="para1">{{$config->head_line }}</p> --}}
                          </span>                          

                          <span>
                              {{-- <p class="para2">{{$config->tag_line1}}</p> --}}
                               <p class="para1">{{$config->tag_line1 ??  "OF ACHIEVEMENT"  }}</p> 
                          </span>
                          <hr class="line1">
                          </hr>
                          <span>
                              <p class="para3">{{$config->tag_line2 ??  "THIS CERTIFICATE IS PROUDLY PRESENTED TO"  }}</p>
                          </span>
                      </div>
                  </div>

                  <div class="student-name">
                      <span>
                          <p class="para1-stud">{{ @$student->first_name }} {{ @$student->last_name }}</p>
                      </span>
                  </div>

                  <div class="para-sub">
                      <p class="para1-sub">
                          {{$config->paragraph ??                             
                         " Lorem ipsum dolor sit amet,consectetur adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laboreet dolore
            quis nostrud exerci tation ullamcorper suscipit laboris nisi ut aliquip ex ea commodo consequat.Duis autem vel eum iriure
            dolor in reprehenderit in voluptate velit esse molestle consequat"
                          
                            }}
                      </p>
                  </div>

                  <div class="signature">
                      {{$config->signature ??  " "  }}
                      <hr class="line2">
                      </hr>
                      <span>
                          <p class="para1-sig">SIGNATURE </p>
                      </span>
                  </div>



                   <div class="bottom_top"></div>
                  <div class="bottom_center"> </div> 
              </div>

              <div>
                

              </div>

          </div>

          @endforeach  
         
          @endif          
       
        @if(count($configurations) == 0)     
            
        <div class="container-main">
            <div class="sub-main">
                <div class="para-main">
                    <span><img class="logo_image" src=" {{asset('assets/images/logo.png') }}" width="100"
                            height="100" /></span>
                    <div class="para-sub-main">

                        <span>
                           <p class="para1">CERTIFICATE</p>
                            </span>                          

                        <span>
                            <p class="para1">OF ACHIEVEMENT</p> 
                        </span>
                        <hr class="line1">
                        </hr>
                        <span>
                            <p class="para3">THIS CERTIFICATE IS PROUDLY PRESENTED TO</p>
                        </span>
                    </div>
                </div>

                <div class="student-name">
                    <span>
                        <p class="para1-stud">{{ @$student->first_name }} {{ @$student->last_name }}</p>
                    </span>
                </div>

                <div class="para-sub">
                    <p class="para1-sub">
                                             
                        Lorem ipsum dolor sit amet,consectetur adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laboreet dolore
          quis nostrud exerci tation ullamcorper suscipit laboris nisi ut aliquip ex ea commodo consequat.Duis autem vel eum iriure
          dolor in reprehenderit in voluptate velit esse molestle consequat"
                        
                         
                    </p>
                </div>

                <div class="signature">
                    
                    <hr class="line2">
                    </hr>
                    <span>
                        <p class="para1-sig">SIGNATURE </p>
                    </span>
                </div>



                 <div class="bottom_top"></div>
                <div class="bottom_center"> </div> 
            </div>

            <div>
              

            </div>

        </div>    

        @endif


      </div>
      <div class="pagebreak"></div>
      

   
@endforeach

</body>
   

</html>


<script>
    window.onload = function() {
        window.print();
    }
</script>

<script>
    document.querySelector(".button-print").addEventListener('click', function() {
        openPrintPreview();
    });

    function openPrintPreview() {
        window.print();
    }
</script>
