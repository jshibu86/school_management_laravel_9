<style>
      .list-group-item {
         border: none; 
         /* width: 150px; */
    text-align: center;
      
    }
    .dropdown-menu{
        border: none; 
    }  

 </style>   
 <script>

 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous"/>
 <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>


 </script>   
<div class="card">
            <div class="card-body">
                 <div class="att_div bg-white border_r_8 p-4 row mt-4">

<div class="col-md-12">
        <input type="hidden" name="academic_year_from" value="{{$data['academic_year_from']}}"/>
        <input type="hidden" name="class_id_from" value="{{$data['class_id_from']}}"/>
        <input type="hidden" name="section_id_from" value="{{$data['section_id_from']}}"/>
        <input type="hidden" name="academic_year_to" value="{{$data['academic_year_to']}}"/>
        <input type="hidden" name="class_id_to" value="{{$data['class_id_to']}}"/>
        <input type="hidden" name="section_id_to" value="{{$data['section_id_to']}}"/>
        <input type="hidden" name="promotion_type" value="{{$data['promotion_type']}}"/>
                      
        
                        <div class="att_table my-4">
                            <!-- Row start -->
                            <div class="row row_head">
                                <div class="col-md-1">
                                    <h6 class="mb-0">No</h6>
                                </div>
                                <div class="col-md-2">
                                    <h6 class="mb-0">
                                        
                                        name & photo
                                    </h6>
                                </div>
                                <div class="col-md-2">
                                    <h6 class="mb-0">Email</h6>
                                </div>
                                <div class="col-md-2">
                                    <h6 class="mb-0">
                                       
                                        Roll Number
                                    </h6>
                                </div>
                                <div class="col-md-1 ">
                                    <h6 class="mb-0">
                                       
                                        Class
                                    </h6>
                                </div>
                                <div class="col-md-2">
                                    <h6 class="mb-0">
                                          Section                                                                              
                                    </h6>
                                </div>                               

                                
                                <div class="col-md-1">
                                    <h6 class="mb-0">Average</h6>
                                </div>
                                <div class="col-md-1 ">
                                    <input type="checkbox" id="select_all">
                                    <label>Select </label>
                                   
                                </div>
                            </div>
                            <!-- Row end -->
        
                            <!-- Row start -->
                            @forelse (@$students as $student)
                            <div class="row row_data my-2 align-items-center">

                               
                                <div class="col-md-1">
                                    <h6 class="mb-0">{{ $loop->index+1 }}</h6>
                                </div>
                                <div class="col-md-2">
                                    <h6 class="mb-0 sub_txt">
                                        <img src="{{ asset(@$student->image) }}" class="img-fluid stu_profile">&nbsp;&nbsp;
                                        <span>{{ @$student->studentname }} </span>
                                    </h6>
                                </div>
                                <div class="col-md-2">
                                    <h6 class="mb-0 sub_txt">{{ $student->email }}</h6>
                                </div>
                                <div class="col-md-2">
                                    <h6 class="mb-0 sub_txt">{{ @$student->reg_no }}</h6>
                                 </div>
                                <div class="col-md-1">
                                    <h6 class="mb-0 sub_txt"> {{ $data['className'] }}                                        
                                        </h6>  
                                </div>                           
                              <div class="col-md-2">
                      
                                    {{-- <div class="dropdown">
                                    <button
                                      class="btn btn-default dropdown-toggle"
                                      type="button"
                                      id="dropdownMenu1"
                                     
                                      data-toggle="dropdown"
                                      aria-haspopup="true"
                                      aria-expanded="true"
                                    >
                                    <span id="selectedItem"name="section_id_to" >Section </span><span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu"aria-labelledby="dropdownMenu1">
                                        @foreach ($section_lists as $s)                                           
                                          
                                      <li class="list-group-item" data-value="{{$s->name }}">{{$s->name }}</li>
                                  
                                         @endforeach   
                         
                                         </ul>
                                       </div> --}}
                                       
                                       <div class="item form-group">
                                        <select name="section_id_to" aria-label="Default select example" class="single-select form-control section_id_from">
                                            <option selected>section</option>
                                            @foreach($section_lists as $list)
                                            <option value="{{$list->id}}">{{$list->name}}</option>
                                            @endforeach
                                          </select>
                        
                                      </div>  
                               </div>                               
                               

                                <div class="col-md-1">
                                   {{ @$student->last_avg }}                                    
                                   
                                </div>
                                <div class="col-md-1">
                                 <input name="students[]" value="{{ @$student->id }}" type="checkbox" class="dormitory_checkbox emp_checkbox" >
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
                                <button type="submit" class="btn btn-primary"> <i class="fa fa-check-circle-o"></i>&nbsp;&nbsp;&nbsp;Submit Entry</button>
                            </div>
                        </div>
                        @endif
                       
 </div>
</div>	
            </div>
        </div>


    <script>

        $(document).ready(function(){
             $("#select_all").prop("checked",true);
              $('.emp_checkbox').each(function() {
                        $(this).prop("checked", true);
                    // $("#select_count").html($("input.emp_checkbox:checked").length);
                });
        });
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

             
  $(document).ready(function () {
    $(".dropdown-menu li").click(function () {
      var selectedValue = $(this).data("value");
      $("#selectedItem").text(selectedValue);
    });
  });



</script>

        