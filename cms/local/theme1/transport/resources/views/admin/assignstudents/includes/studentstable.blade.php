
<section class="py-2">
    <div class="container">

        <div class="att_div bg-white border_r_8 p-4 row">

            <div class="col-md-12">
        
                        
                            
                <div class="row align-items-center ip_row border_r_8 py-2 my-4">
                    <div class="col-md-4">
                        <div class="row align-items-center">
                            <div class="col-md-2">
                                <img src="{{ asset("assets/images/t.jpg") }}" class="img-fluid">
                            </div>
                           
                            <div class="col-md-10">
                               
                                <h6 class="sub_txt">Assign Students to Transport</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <h6>Class :<b>{{ @$class_name }}</b></h6>
                               
                            </div>
                            <div class="col-md-4">
                                <h6>Section :<b>{{ @$section_name }}</b></h6>
                                
                            </div>
                            <div class="col-md-4">
                                <h6>Academic year :<b>{{ @$acyear }}</b></h6>
                                
                            </div>
                           
                        </div>

                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <h6>Stop Name :<b>{{ @$stop_name }}</b></h6>
                               
                            </div>
                            <div class="col-md-4">
                                <h6>Route Name :<b>{{ @$route_name }}</b></h6>
                               
                            </div>
                            <div class="col-md-4">
                                <h6>Bus Name :<b>{{ @$bus_name }}</b></h6>
                               
                            </div>
                        </div>
                    </div>
                </div>

            </div>

          

        </div>

        <div class="att_div bg-white border_r_8  row ">

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
                        <div class="col-md-3 mt-2">
                            <h6 class="mb-0">Student Email</h6>
                        </div>
                        <div class="col-md-3 mt-2">
                            <h6 class="mb-0">
                               
                                Roll Number
                            </h6>
                        </div>
                        <div class="col-md-2 mt-2" style="display: flex;
                        align-items: center;
                        gap: 5px;
                    ">
                            <h6 class="mb-0">Select All</h6>
                           <input type="checkbox" id="select_all">
                        </div>
                        
                    </div>
                    <!-- Row end -->

                    <!-- Row start -->
                    @forelse (@$students as $student)

                    @php
                        $check=0;
                        $alreadyassigend=0;

                        if(in_array($student->id,$assigenstudents ))
                                {
                                $check=1;
                                }
                                if(in_array($student->id,$alreadyassignstudents ))
                                {
                                $alreadyassigend=1;
                                }


                    @endphp
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
                        <div class="col-md-3">
                            <h6 class="mb-0 sub_txt">{{ $student->email }}</h6>
                        </div>
                        <div class="col-md-3">
                            <h6 class="mb-0 sub_txt">{{ @$student->reg_no }}</h6>
                        </div>
                        <div class="col-md-2">
                            <input name="students[]" value="{{ @$student->id }}" type="checkbox" class="emp_checkbox" data-emp-id="{{ @$student->id }}" {{ $check ? "checked" : "" }} data-id={{ $check }} {{ $alreadyassigend == $check ? "" : "disabled" }} {{ $alreadyassigend ? "checked" : "" }}>

                            @if ( $alreadyassigend != $check )
                                <small class="text-danger">Already Assigned</small>
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
                        <button onClick="window.location.reload();" type="button" class="btn btn btn-dark mt-4"> <i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</button>
                        <button type="submit" class="btn add_btn"> <i class="fa fa-check-circle-o"></i>&nbsp;&nbsp;&nbsp;Submit Entry</button>
                    </div>
                </div>
                @endif
               

            </div>

        </div>	

    </div>

</section>

<script>
     $("#select_all").on('change', function(e) {
                
                if (e.target.checked) {
                
                    $('.emp_checkbox').each(function() {
                        $(this).prop("checked", true);
                    // $("#select_count").html($("input.emp_checkbox:checked").length);
                });
               
                }else{
                    $('.emp_checkbox').each(function() {
                        $(this).prop("checked", false);
                    // $("#select_count").html($("input.emp_checkbox:checked").length);
                });
                }
        
            });

             $(".emp_checkbox").on('change', function(e) {

                $("#select_all").prop("checked",false);
            // $("#select_count").html($("input.emp_checkbox:checked").length);
             });

</script>

