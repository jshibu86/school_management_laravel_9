<style>
    .form-c{
        display: block;
    width: 100%;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #212529;
    }
    @media(width:768px){
        .attend_head_text{
            font-size :1rem !important;
        }
        .font_text{
            font-size :12px !important;
        }
        .col-md-7 {
            flex: 0 0 auto;
            width: 68.33333333% !important;
        }
        .col-md-5 {
            flex: 0 0 auto;
            width: 31.66666667% !important;
        }
    }

</style>

<div class="card">
    <div class="card-body">
        
       
        {{ Form::open(array('role' => 'form', 'route'=>array('addhourlyattendance'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'atn-form','novalidate' => 'novalidate')) }}
        <section class="py-2">
            <div class="container">
        
                <div class="att_div bg-white border_r_8 p-4 row">
        
                    <div class="col-md-12">
        
                        
                            
                        <div class="row align-items-center ip_row border_r_8 py-2 my-4">
                            <div class="col-md-5">
                                <div class="row align-items-center">
                                    <div class="col-md-2">
                                        <img src="{{ asset("assets/images/clock_icon.png") }}" class="img-fluid">
                                    </div>
                                    <input type="hidden" name="class_id" value="{{ @$class_id }}"/>
                                    <input type="hidden" name="term_id" value="{{ @$term_id }}"/>
                                    <input type="hidden" name="section_id" value="{{ @$section_id }}"/>
                                    <input type="hidden" name="period_id" value="{{ @$period_id }}"/>
                                    <input type="hidden" name="type" value="{{ @$type }}"/>
                                    <input type="hidden" name="acyear" value="{{ @$academicyear_id }}"/>
                                    <input type="hidden" name="subject" value="{{ @$subject }}"/>
                                    <input type="hidden" name="teacher" value="{{ @$teacher }}"/>
                                    <div class="col-md-10">
                                        <h5 class="attend_head_text">Add {{ @$type === "hourly" ? "Hourly" : 'Daily'}} Attendance</h5>
                                        <h6 class="sub_txt font_text">Enter attendance below for the selected class </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="row align-items-center">
                                    <div class="col-md-2">
                                        <h6 class="font_text">Class :</h6>
                                        <h6  class="font_text"><b>{{ @$class_name }}</b></h6>
                                    </div>
                                    <div class="col-md-3">
                                        <h6 class="font_text">Section :</h6>
                                        <h6 class="font_text"><b>{{ @$section_name }}</b></h6>
                                    </div>
                                    <div class="col-md-4">
                                        <h6 class="font_text">Academic year :</h6>
                                        <h6 class="font_text"><b>{{ @$acyear }}</b></h6>
                                    </div>
                                    <div class="col-md-3">
                                        <h6 class="font_text">Date :</h6>
                                        <h6 class="font_text"><b>{{ @$date }}</b></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
        
                    </div>
        
                </div>
        
                <div class="att_div bg-white border_r_8 p-4 row mt-4">
        
                    <div class="col-md-12">
        
                      
        
                        <div class="att_table my-4">
                            <!-- Row start -->
                            <div class="row row_head">
                                <div class="col-md-1 mt-2">
                                    <h6 class="mb-0">No</h6>
                                </div>
                                <div class="col-md-3 mt-2">
                                    <h6 class="mb-0">
                                        
                                        Student name & photo
                                    </h6>
                                </div>
                                <div class="col-md-2 mt-2">
                                    <h6 class="mb-0">Student Email</h6>
                                </div>
                                <div class="col-md-2 mt-2">
                                    <h6 class="mb-0">
                                       
                                        Roll Number
                                    </h6>
                                </div>
                                <div class="col-md-2 mt-2">
                                    <h6 class="mb-0">Attendance</h6>
                                </div>
                                <div class="col-md-2">
                                    {{ Form::select('attendencesdefault',Configurations::ATNTYPES,null ,
                                    array('id'=>'class_id','class' => 'period_select att_select form-c attendencesdefault','placeholder'=>" Attendance",'required'=>"required")) }}
                                </div>
                            </div>
                            <!-- Row end -->
        
                            <!-- Row start -->
                            @forelse (@$students as $student)
                            <div class="row row_data my-2 align-items-center">

                               
                                <div class="col-md-1">
                                    <h6 class="mb-0">{{ $loop->index+1 }}</h6>
                                </div>
                                <div class="col-md-3">
                                    <h6 class="mb-0 sub_txt">
                                        <img src="{{ asset(@$student->image) }}" class="img-fluid stu_profile">&nbsp;&nbsp;
                                        <span>{{ @$student->first_name }} {{ @$student->last_name }}</span>
                                    </h6>
                                </div>
                                <div class="col-md-2">
                                    <h6 class="mb-0 sub_txt">{{ $student->email }}</h6>
                                </div>
                                <div class="col-md-2">
                                    <h6 class="mb-0 sub_txt">{{ @$student->reg_no }}</h6>
                                </div>
                                <div class="col-md-3">
                                    @if ($period_with_attendance)
                                    @if (isset($student->attendance[0]))
                                  
                                    {{ Form::select('attendences['.$student->id.']',Configurations::ATNTYPES,@$student->attendance[0]->attendance ,
                                    array('id'=>'class_id','class' => 'period_select att_select form-c','placeholder'=>"Select Attendance",'required'=>"required")) }}
                                    @else
                                    {{ Form::select('attendences['.$student->id.']',Configurations::ATNTYPES,@$student->attendance[0]->attendance ,
                                    array('id'=>'class_id','class' => 'period_select att_select form-c ','placeholder'=>"Select Attendance",'required'=>"required")) }}
                                    @endif
                                    @else 
                                    {{ Form::select('attendences['.$student->id.']',Configurations::ATNTYPES,1 ,
                                    array('id'=>'class_id','class' => 'period_select att_select form-c','placeholder'=>"Select Attendance",'required'=>"required")) }}
                                    @endif
                                    
                                   
                                </div>
                                    
                               
                            </div>
                            @empty
                            <p class="text-center mt-2">No Students Found</p>
                            <!-- Row end -->  
                            @endforelse
                        
        
                           
        
                        </div>
        
                        
                        @if (sizeof(@$students))
                        <div class="row ">
                            <div class="col-md-12 text-right" style="text-align: right;">
                                <button type="submit" class="btn add_btn"> <i class="fa fa-check-circle-o"></i>&nbsp;&nbsp;&nbsp;Submit Entry</button>
                            </div>
                        </div>
                        @endif
                       
        
                    </div>
        
                </div>	
        
            </div>
        
        </section>
        {{Form::close()}}
       
    </div>
</div>

<script>
     // select all attendance present/absent/late

     var elements=document.querySelectorAll(".period_select");


     elements.forEach(elementDemo => {
        $(elementDemo).on("change", function (e) {
            var value = $(this).val();
            if(value=="0")
               {
                elementDemo.style.borderColor="red";
               }else if(value=="1")
               {
                elementDemo.style.borderColor="green";
               }else if(value == "2")
               {
                elementDemo.style.borderColor="blue";
               }

        });
     });


     //console.log(elements);

     $(".attendencesdefault").on("change", function (e) {
            //console.log("yes change");
            var value = $(this).val();

            

            elements.forEach(element => {

               if(value=="0")
               {
                element.style.borderColor="red";
               }else if(value=="1")
               {
                element.style.borderColor="green";
               }else if(value == "2")
               {
                element.style.borderColor="blue";
               }

               element.value=$(this).val();
            });

           // console.log(value);

           

        });
</script>