
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
                                            <h6 class="mb-0 ">
                                                
                                                User Name & photo
                                            </h6>
                                        </div>
                                        <div class="col-md-2 mt-2">
                                            <h6 class="mb-0">User  Email</h6>
                                        </div>
                                         <div class="col-md-2 mt-2">
                                            <h6 class="mb-0">Month</h6>
                                        </div>
                                        <div class="col-md-2 mt-2">
                                            <h6 class="mb-0">
                                            
                                           Salary {{Configurations::getConfig("site")->currency_symbol}}
                                            </h6>
                                        </div>
                                        <div class="col-md-2 mt-2">
                                            <h6 class="mb-0">
                                            
                                            View
                                            </h6>
                                        </div>
                                        
                                       
                                    </div>
                                    <!-- Row end -->
                
                                    <!-- Row start -->

                                 

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
                                        @php
                                            $is_month=$user->salerypayrollpayment && $user->salerypayrollpayment->month == $month ? true : false;
                                            $is_year=$user->salerypayrollpayment && $user->salerypayrollpayment->year == $year ? true : false;
                                        @endphp
                                        <div class="col-md-2">
                                            <h6 class="mb-0 sub_txt">{{$is_month && $is_year ? $monthyear : "-"}}</h6>
                                        </div>
                                        <div class="col-md-2">
                                            <h6 class="mb-0 sub_txt">{{$is_month && $is_year ?  $user->salerypayrollpayment->basic_salery ?? "NIL":"-" }}</h6>
                                        </div>
                                        <div class="col-md-2">
                                            @if ($is_month && $is_year )
                                                <h6 class="mb-0 sub_txt"><a href="{{route("viewpayslip",$user->salerypayrollpayment->id)}}" target="_blank"><i class="fa fa-eye"></i></a></h6>
                                            @else
                                            <span class="text-danger"> Not Available</span>
                                            @endif
                                            
                                        </div>
                                            
                                    
                                    </div>
                                    @empty
                                    <p class="text-center mt-2">No Users Found</p>
                                    <!-- Row end -->  
                                    @endforelse
                                        
                                   
                                   
                                
                
                                
                
                                </div>
                
                                
                               
                            
                
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