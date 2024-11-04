<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Employee</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="assets/css/style.css" />
  <link rel="stylesheet" href="assets/fontawesome/css/font-awesome.min.css" />
  <style>
    @media screen,
    print {
      .head_color {
        background-color: #c7c7c7 !important;
      }
    }

    .wave_color {
      fill:{{Configurations::getConfig('site')->id_card_header}};
    }

    #svg {
      border-radius: 9px;
    }

    .id_card {
      width: 350px;
      height: 465px;
      border: 1px solid #000;
      position: relative;
      border-radius: 10px;
    }

    .logo {
    position: absolute;
      top: 15px;
      left: 38%;
    }

    .logo_bg {
      width: 40px;
      height: 40px;
      background-color: #d9d9d9;
      border-radius: 50%;
       
    }

    .logo_img {
      width: 36px;
      margin-top: 5px;
    }

    .top_color {
      position: absolute;
      left: 0;
      right: 0;
    }

    .bottom_color {
      display: flex;
      position: absolute;
      left: 0;
      right: 0;
      bottom: 0;
    }

    .schoolname {
      padding-top: 62px;
      padding-bottom: 10px;
      font-weight: 800;
      font-size: 19px;
    }

    .studentimage {
      border-radius: 10px;
      border: 1px solid #8E37FF;
      object-fit: cover;
      width: 70px;
      height: 70px;
    }

    .name {
      font-weight: 800;
      font-size: 20px;
      color: #000F28;
      padding-right: 10px;
      margin-bottom: 0;
      padding-bottom: 12px;
      margin-top: 0;
      padding-top: 14px;
    }

    .address {
      text-align: center;
      padding-bottom: 7px;
      font-size: 12px;
    }

    .phone1 {
      text-align: center;
      padding-bottom: 14px;
      font-size: 12px;
    }

    .studentdetails {
      font-weight: 800;
      font-size: 13px;
      border-spacing: 0px;
      margin: auto
    }

    .rowdata {
      padding-left: 5px;
    }

    .rowdata1 {
      text-align: left;
      padding-bottom: 5%;
    }

    .rowdata2 {
      text-align:left;
      padding-bottom: 5%;
    }

    .valid {
      font-weight: 800;
      font-size: 13px;
      margin-bottom: 0;
    }

    .from {
      color: #8E37FF;
      font-weight: 800;
      font-size: 14px;
    }

    .to {
      color: #8E37FF;
      font-weight: 800;
      font-size: 14px;
    }
    .col_33{
        max-width: 32%;
        flex: 0 0 32%;
        /* padding: 5px; */
        
    }
    .main_div{
        text-align: center;
       display: flex;
       flex-wrap: wrap;
       gap: 10px;
        /* padding: 2px; */
    }
    .id_card{
        width: 100%;
    }
    /* .main_div:nth-child(3n+1){
        content: "";
        clear: both;
    } */

   .button-class{
    padding: 10px;
   
    color: white;
    margin: 10px;
    display: block;
    text-align: center;
    width: 49px;
    float: right;
    position: fixed;
    top: 0;
    right: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 20px;
    }

    .button-class a{
    background-color: black;
    padding: 12px;
    color:white;
    text-decoration: none;
    }
     
    .mainsub_div{
      width: 32%;
      text-align: center;   
    flex-wrap: wrap;
    gap: 10px;
    /* padding: 2px; */
    }
    .para_main {
      text-align: center;
      padding-top: 20px;
      padding-bottom: 40px;

      font-weight: 800;
      font-size: 13px;
      color: #000f28;
    }
    .para1 {
      margin: auto;
      line-height: 1.5;
    }
    .college_name {
      text-align: center;
      font-weight: 800;
      font-size: 27px;
      color: #000f28;
    }

    .para_sub {
      text-align: center;
      padding-top: 26px;
      font-weight: 800;
      font-size: 13px;
      color: #000f28;
    }
    .para1_sub {
      margin: auto;
      padding-bottom: 5px;
      line-height: 1.5;
    }
    .para2_sub {
      margin: auto;
    }

    .address {
      text-align: center;
      font-weight: 800;
      font-size: 13px;
      color: #000f28;
    }

    .para1_add {
      margin: auto;
      padding-bottom: 5px;
      padding-top: 10px;     
    }

    .para2_add {
      margin: auto;
      padding-bottom: 5px;
      padding-top: 10px;   
      line-height:2  
    }

    .phoneimage {
      padding-right: 15px;
      padding-top: 5px;
    }

    .signature {
      text-align: center;
      font-weight: 800;
      font-size: 18px;
      color: #000f28;
      padding-top: 25px;
    }

    .id_card_back {
      height: 465px;
      width: 100%;
      border: 1px solid #000;
      position: relative;
      border-radius: 10px;
    }

  </style>
</head>

<body style="font-family: roboto" leftmargin="0" marginheight="0" marginwidth="0" offset="0" topmargin="10px">
   
  <div class="button-class">
     <a href="{{route('StudentIdcard')}}" class="button-back">Back</a>
    <a href="#" class="button-print">Print</a>
  </div>    
  
    <div class="container" style="max-width: 800px;margin-left: 20px;margin-top: 20px;">
        <div class="main_div">
        
        @foreach (@$students as $student )
 
    {{-- @if (Configurations::getConfig('site')->id_card_templates) 
    @if (in_array[1](Configurations::getConfig('site')->id_card_templates))
     --}}
          @if (Configurations::getConfig('site')->id_card_templates==[1])     

            <div class="col_33">
                <div class="id_card">
                <div class="top_color">
                    <svg width="100%" height="100%" id="svg" viewBox="0 0 1440 390" xmlns="http://www.w3.org/2000/svg"
                    class="transition duration-300 ease-in-out delay-150">
                    <path
                        d="M 0,400 C 0,400 0,200 0,200 C 127.86666666666667,166.39999999999998 255.73333333333335,132.79999999999998 423,147 C 590.2666666666667,161.20000000000002 796.9333333333334,223.2 973,240 C 1149.0666666666666,256.8 1294.5333333333333,228.4 1440,200 C 1440,200 1440,400 1440,400 Z"
                        stroke="none" stroke-width="0" class="wave_color" fill-opacity="1"
                        class="transition-all duration-300 ease-in-out delay-150 path-0" transform="rotate(-180 720 200)"></path>
                    </svg>
                </div>

                <div class="logo">
                    <div class="logo_bg">
                    <img src="{{ Configurations::getConfig('site')->imagec }} " class="logo_img" />
                    </div>
                </div>

                <div class="schoolname">{{ Configurations::getConfig('site')->school_name }}
                </div>

                <div class="address">{{ Configurations::getConfig('site')->place }} {{ Configurations::getConfig('site')->city }},{{ Configurations::getConfig('site')->post }}
                </div>

                <div class="phone1">Ph no : {{ Configurations::getConfig('site')->school_landline }}
                </div>

                <span><img class="studentimage" src="{{asset(@$student->image)}}" width="70" height="70"></a>
                </span>

                <div>
                    <p class="name">{{@$student->first_name}} {{@$student->last_name}}</p>
                </div>

                <table class="studentdetails">
                   

                    @if (Configurations::getConfig('site')->id_card_feilds)
                     @if (in_array(5,Configurations::getConfig('site')->id_card_feilds))
                     <tr>
                    <td class="rowdata1"><span>Roll No &nbsp;</span></td>
                    <td class="rowdata2">:<span class="rowdata">{{@$student->reg_no}} </td>
                    </tr>
                    @endif
                     @if (in_array(4,Configurations::getConfig('site')->id_card_feilds))
                    <tr>
                    <td class="rowdata1">Class </td>
                    <td class="rowdata2">:<span class="rowdata">{{@$student->class->name}} {{@$student->section->name}}</span></td>
                    </tr>
                    @endif

                    @if (in_array(1,Configurations::getConfig('site')->id_card_feilds))
                    <tr>
                    <td class="rowdata1">DOB </td>
                    <td class="rowdata2">:<span class="rowdata">{{@$student->dob}}</td>
                    </tr>
                    @endif

                     @if (in_array(2,Configurations::getConfig('site')->id_card_feilds))
                      <tr>
                      <td class="rowdata1">Blood</td>
                      <td class="rowdata2">:<span class="rowdata">{{@$student->blood_group}}</td>
                      </tr>
                     @endif

                     @if (in_array(3,Configurations::getConfig('site')->id_card_feilds))
                      <tr>
                      <td class="rowdata1">Phone</td>
                      <td class="rowdata2">:<span class="rowdata">{{@$student->mobile}}</td>
                      </tr>
                     @endif
                   
                    
                    
                    @else
                    <tr>
                    <td class="rowdata1">DOB </td>
                    <td class="rowdata2">:<span class="rowdata">{{@$student->dob}}</td>
                    </tr>
                    <tr>
                    <td class="rowdata1">Blood</td>
                    <td class="rowdata2">:<span class="rowdata">{{@$student->blood_group}}</td>
                    </tr>
                    <tr>
                    <td class="rowdata1">Phone</td>
                    <td class="rowdata2">:<span class="rowdata">{{@$student->mobile}}</td>
                    </tr>

                    @endif
                    
                </table>

                {{-- <div>
                    <p style="margin: 0"><span class="valid">Valid</span><span> : </span><span class="from">20-7-2023 </span><span>to</span><span class="to"> 16-03-2024</span></p>
                </div> --}}

                 <div class="bottom_color">
                    <svg width="100%" height="100%" id="svg" viewBox="0 0 1440 390" xmlns="http://www.w3.org/2000/svg"
                    class="transition duration-300 ease-in-out delay-150">
                    <path
                        d="M 0,400 C 0,400 0,200 0,200 C 141.06666666666666,152.26666666666665 282.1333333333333,104.53333333333333 434,125 C 585.8666666666667,145.46666666666667 748.5333333333333,234.13333333333333 918,258 C 1087.4666666666667,281.8666666666667 1263.7333333333333,240.93333333333334 1440,200 C 1440,200 1440,400 1440,400 Z"
                        stroke="none" stroke-width="0" class="wave_color" fill-opacity="1"
                        class="transition-all duration-300 ease-in-out delay-150 path-0"></path>
                    </svg>
                </div>
                </div>
            </div>

            
         @endif


         @if (Configurations::getConfig('site')->id_card_templates==[1,2])
<<<<<<< HEAD

        
=======
         {{-- @if (in_array([1,2],Configurations::getConfig('site')->id_card_templates)) --}}

>>>>>>> ed0c043744234ee36d96bdcda012c73b00e439f8
          <div class="col_33">
          <div class="id_card">
          <div class="top_color">
              <svg width="100%" height="100%" id="svg" viewBox="0 0 1440 390" xmlns="http://www.w3.org/2000/svg"
              class="transition duration-300 ease-in-out delay-150">
              <path
                  d="M 0,400 C 0,400 0,200 0,200 C 127.86666666666667,166.39999999999998 255.73333333333335,132.79999999999998 423,147 C 590.2666666666667,161.20000000000002 796.9333333333334,223.2 973,240 C 1149.0666666666666,256.8 1294.5333333333333,228.4 1440,200 C 1440,200 1440,400 1440,400 Z"
                  stroke="none" stroke-width="0" class="wave_color" fill-opacity="1"
                  class="transition-all duration-300 ease-in-out delay-150 path-0" transform="rotate(-180 720 200)"></path>
              </svg>
          </div>

          <div class="logo">
              <div class="logo_bg">
              <img src="{{ Configurations::getConfig('site')->imagec }} " class="logo_img" />
              </div>
          </div>

          <div class="schoolname">{{ Configurations::getConfig('site')->school_name }}
          </div>

          <div class="address">{{ Configurations::getConfig('site')->place }} {{ Configurations::getConfig('site')->city }},{{ Configurations::getConfig('site')->post }}
          </div>

          <div class="phone1">Ph no : {{ Configurations::getConfig('site')->school_landline }}
          </div>

          <span><img class="studentimage" src="{{asset(@$student->image)}}" width="70" height="70"></a>
          </span>

          <div>
              <p class="name">{{@$student->first_name}} {{@$student->last_name}}</p>
          </div>

          <table class="studentdetails">
             

              @if (Configurations::getConfig('site')->id_card_feilds)
               @if (in_array(5,Configurations::getConfig('site')->id_card_feilds))
               <tr>
              <td class="rowdata1"><span>Roll No &nbsp;</span></td>
              <td class="rowdata2">:<span class="rowdata">{{@$student->reg_no}} </td>
              </tr>
              @endif
               @if (in_array(4,Configurations::getConfig('site')->id_card_feilds))
              <tr>
              <td class="rowdata1">Class </td>
              <td class="rowdata2">:<span class="rowdata">{{@$student->class->name}} {{@$student->section->name}}</span></td>
              </tr>
              @endif

              @if (in_array(1,Configurations::getConfig('site')->id_card_feilds))
              <tr>
              <td class="rowdata1">DOB </td>
              <td class="rowdata2">:<span class="rowdata">{{@$student->dob}}</td>
              </tr>
              @endif

               @if (in_array(2,Configurations::getConfig('site')->id_card_feilds))
                <tr>
                <td class="rowdata1">Blood</td>
                <td class="rowdata2">:<span class="rowdata">{{@$student->blood_group}}</td>
                </tr>
               @endif

               @if (in_array(3,Configurations::getConfig('site')->id_card_feilds))
                <tr>
                <td class="rowdata1">Phone</td>
                <td class="rowdata2">:<span class="rowdata">{{@$student->mobile}}</td>
                </tr>
               @endif
             
              
              
              @else
              <tr>
              <td class="rowdata1">DOB </td>
              <td class="rowdata2">:<span class="rowdata">{{@$student->dob}}</td>
              </tr>
              <tr>
              <td class="rowdata1">Blood</td>
              <td class="rowdata2">:<span class="rowdata">{{@$student->blood_group}}</td>
              </tr>
              <tr>
              <td class="rowdata1">Phone</td>
              <td class="rowdata2">:<span class="rowdata">{{@$student->mobile}}</td>
              </tr>

              @endif
              
          </table>

          {{-- <div>
              <p style="margin: 0"><span class="valid">Valid</span><span> : </span><span class="from">20-7-2023 </span><span>to</span><span class="to"> 16-03-2024</span></p>
          </div> --}}

           <div class="bottom_color">
            <svg width="100%" height="100%" id="svg" viewBox="0 0 1440 390" xmlns="http://www.w3.org/2000/svg"
            class="transition duration-300 ease-in-out delay-150">
            <path
                d="M 0,400 C 0,400 0,200 0,200 C 141.06666666666666,152.26666666666665 282.1333333333333,104.53333333333333 434,125 C 585.8666666666667,145.46666666666667 748.5333333333333,234.13333333333333 918,258 C 1087.4666666666667,281.8666666666667 1263.7333333333333,240.93333333333334 1440,200 C 1440,200 1440,400 1440,400 Z"
                stroke="none" stroke-width="0" class="wave_color" fill-opacity="1"
                class="transition-all duration-300 ease-in-out delay-150 path-0"></path>
            </svg>
        </div>
        </div>
    </div>  

 @include("report::admin.report.student.studentidcardbackpage",['route'=> "StudentIdcardBack"])

     
      @endif

        @endforeach 

        
                
       

        </div>
     </div>
 
 
</body>
<script>
  window.onload = function() {
    var buttonBack = document.querySelector(".button-back");

    var buttonprint=document.querySelector(".button-print");
   
   buttonBack.style.display = "none";
    

    setTimeout(() => {
          window.print();
           buttonBack.style.display = "block";
    }, 1000);

    buttonprint.addEventListener("click",function(e){
      e.preventDefault();
      buttonBack.style.display = "none";
      window.print();
      buttonBack.style.display = "block";
    });
 
};
</script>

</html>