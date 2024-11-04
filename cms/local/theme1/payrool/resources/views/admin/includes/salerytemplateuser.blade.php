
<style>
    .form-c{
    display: block;
    width: 80%;
    float: right;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #212529;
    }
</style>    
            
                    
                    
                    
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
                                                
                                                User Name & photo
                                            </h6>
                                        </div>
                                        <div class="col-md-2 mt-2">
                                            <h6 class="mb-0">User  Email</h6>
                                        </div>
                                        <div class="col-md-3 mt-2">
                                            <h6 class="mb-0">
                                            
                                            Phone Number
                                            </h6>
                                        </div>
                                        
                                        <div class="col-md-3">
                                            {{ Form::select('attendencesdefault',@$salery_grades,null ,
                                            array('id'=>'class_id','class' => 'period_select att_select select2 form-c attendencesdefault','placeholder'=>" Grades",'required'=>"required")) }}
                                        </div>
                                    </div>
                                    <!-- Row end -->
                
                                    <!-- Row start -->

                                    @if (@$layout=="edit")
                                     @forelse (@$users_data as $user)
                                    <div class="row row_data my-2 align-items-center">

                                    
                                        <div class="col-md-1">
                                            <h6 class="mb-0">{{ $loop->index+1 }}</h6>
                                        </div>
                                        <div class="col-md-3">
                                            <h6 class="mb-0 sub_txt">
                                                <img src="{{ asset(@$user->userinfo->images) }}" class="img-fluid stu_profile">&nbsp;&nbsp;
                                                <span>{{ @$user->userinfo->name }} </span>
                                            </h6>
                                        </div>
                                        <div class="col-md-2">
                                            <h6 class="mb-0 sub_txt">{{ $user->userinfo->email }}</h6>
                                        </div>
                                        <div class="col-md-3">
                                            <h6 class="mb-0 sub_txt">{{ @$user->userinfo->mobile ?? "NIL" }}</h6>
                                        </div>
                                        <div class="col-md-3">
                                            {{ Form::select('users['.$user->userinfo->id.']',@$salery_grades,$user->grade_id,
                                            array('id'=>'class_id','class' => 'period_select att_select form-c','placeholder'=>"Select Grade",'required'=>"required")) }}
                                            
                                        
                                        </div>
                                            
                                    
                                    </div>
                                    @empty
                                    <p class="text-center mt-2">No Users Found</p>
                                    <!-- Row end -->  
                                    @endforelse
                                    @else

                                     @forelse (@$users_data as $user)
                                    <div class="row row_data my-2 align-items-center">

                                    
                                        <div class="col-md-1">
                                            <h6 class="mb-0">{{ $loop->index+1 }}</h6>
                                        </div>
                                        <div class="col-md-3">
                                            <h6 class="mb-0 sub_txt">
                                                <img src="{{ asset(@$user->images) }}" class="img-fluid stu_profile">&nbsp;&nbsp;
                                                <span>{{ @$user->name }} </span>
                                            </h6>
                                        </div>
                                        <div class="col-md-2">
                                            <h6 class="mb-0 sub_txt">{{ $user->email }}</h6>
                                        </div>
                                        <div class="col-md-3">
                                            <h6 class="mb-0 sub_txt">{{ @$user->mobile ?? "NIL" }}</h6>
                                        </div>
                                        <div class="col-md-3">
                                            {{ Form::select('users['.$user->id.']',@$salery_grades,NULL,
                                            array('id'=>'class_id','class' => 'period_select att_select form-c','placeholder'=>"Select Grade",'required'=>"required")) }}
                                            
                                        
                                        </div>
                                            
                                    
                                    </div>
                                    @empty
                                    <p class="text-center mt-2">No Users Found</p>
                                    <!-- Row end -->  
                                    @endforelse
                                        
                                    @endif
                                   
                                
                
                                
                
                                </div>
                
                                
                                @if (sizeof(@$users_data))
                                <div class="row ">
                                    <div class="col-md-12 text-right" style="text-align: right;">
                                        <button type="submit" class="btn add_btn"> <i class="fa fa-check-circle-o"></i>&nbsp;&nbsp;&nbsp;Submit Entry</button>
                                    </div>
                                </div>
                                @endif
                            
                
                            </div>
        
</div>	


<script>

        var elements=document.querySelectorAll(".period_select");

      $(".attendencesdefault").on("change", function (e) {
            //console.log("yes change");
            var value = $(this).val();

            

            elements.forEach(element => {

              

               element.value=$(this).val();
            });

           // console.log(value);

           

        });
</script>