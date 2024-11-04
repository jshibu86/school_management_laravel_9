<div class="row">
                            <div class="col-md-12">

                                <div class="student_information mt-4">
                                    <h5 class="card-title">Student Information</h5>
                                    <hr/>
                                    <div class="info_student d-flex">
                                         <div class="student_image">
                                            <img src="{{ @$student_info->image ? asset(@$student_info->image) : asset("assets/images/default.jpg")}}" style="width:100px"/>
                                        </div>
                                    <div class="student_contact">
                                        <p>Name : {{ @$student_info->first_name }} {{ @$student_info->last_name }}</p>
                                        <p>Class/Section :{{ @$student_info->class->name }}/{{ @$student_info->section->name }} </p>
                                        <p>Department : {{ @$student_info->department_name }}</p>
                                        <p>Term : {{ @$term }}</p>
                                    </div>
                                    </div>
                                   
                                </div>
                            <div class="fee_information mt-4">
                                <h5 class="card-title">Fee Information</h5>
                                <hr/>
                                <div class="row">
                                    @if (@$feedata->payment_type==1 && sizeof($term_due))
                                    
                                    @foreach ($term_due as$termid=> $due)
                                        <div class="col-12 col-lg-4">
                                            <div class="card radius-15 {{ @$due->ispaid ? "bg-wall" : "bg-rose" }}">
                                                <div class="card-body text-center">
                                                    <div class="widgets-icons mx-auto bg-white rounded-circle">
                                                        @if ($due->ispaid)
                                                           <i class='bx bx-check'></i>
                                                        @else
                                                        <i class='bx bx-x'></i>
                                                        @endif
                                                       
                                                    </div>

                                                    <div class="amount__info mt-4 mb-3">
                                                         <p class="mb-0 text-white term">{{ $due->terminfo }}</p>
                                                        <div class="amount">
                                                            <div>
                                                                 <h4 class="mb-0 font-weight-bold mt-3 text-white">{{ Configurations::CurrencyFormat(@$due->amount) }} </h4>
                                                            </div>
                                                           

                                                            <div class="mt-2">
                                                                <span class="{{ $due->ispaid ? "icon-class" : "icon-class-red" }}">
                                                                {{ @$due->per }}% <i class='bx {{ $due->ispaid ? "bx-trending-down" : "bx-trending-up"  }}'></i>
                                                            </span>
                                                            </div>
                                                           
                                                        </div>
                                                        <div class="info">
                                                           
                                                            <p class="mb-0 text-white duedate">Due Date : {{ $due->duedate }}</p>
                                                            @if ($due->ispaid)

                                                                 <p class="mb-0 text-white duedate">Paid Date : {{ $due->ispaid->payment_date }}</p> 
                                                            @endif
                                                        </div>
                                                    </div>
                                                    
                                                    @if (!$due->ispaid)
                                                        <span class="bg-white pay__button" data-id="{{ $termid }}" data-per="{{ @$due->per }}" data-amount="{{ @$due->amount }}" data-duedate="{{ $due->duedate }}" data-type="term">PAY NOW</span>
                                                    @else
                                                     <span class="bg-white confirm__button" data-id="{{ $termid }}" data-amount="{{ @$due->amount }}" data-duedate="{{ $due->duedate }}">CONFIRMED</span>
                                                    @endif
                                                    
                                                </div>
                                            </div>
                                    
                                        </div> 
                                    @endforeach
                                    
                                    @endif


                                    @if (@$feedata->payment_type==0 && sizeof($month_due))
                                       @foreach ($month_due as $id=> $month)
                                       <div class="col-12 col-lg-4">
                                            <div class="card radius-15 {{ @$month->ispaid ? "bg-wall" : "bg-rose" }}">
                                                <div class="card-body text-center">
                                                    <div class="widgets-icons mx-auto bg-white rounded-circle">
                                                        @if ($month->ispaid)
                                                           <i class='bx bx-check'></i>
                                                        @else
                                                        <i class='bx bx-x'></i>
                                                        @endif
                                                       
                                                    </div>

                                                    <div class="amount__info mt-4 mb-3">
                                                         <p class="mb-0 text-white term">{{ $month->year }} - {{ $month->month }}</p>
                                                        <div class="amount">
                                                            <div>
                                                                 <h4 class="mb-0 font-weight-bold mt-3 text-white">{{ Configurations::CurrencyFormat(@$month->amount) }} </h4>
                                                            </div>
                                                           

                                                            {{-- <div class="mt-2">
                                                                <span class="{{ $month->ispaid ? "icon-class" : "icon-class-red" }}">
                                                                {{ @$due->per }}% <i class='bx {{ $due->ispaid ? "bx-trending-down" : "bx-trending-up"  }}'></i>
                                                            </span>
                                                            </div> --}}
                                                           
                                                        </div>
                                                        <div class="info">
                                                           
                                                            {{-- <p class="mb-0 text-white duedate">Due Date : {{ $due->duedate }}</p> --}}
                                                            @if ($month->ispaid)

                                                                 <p class="mb-0 text-white duedate">Paid Date : {{ @$month->ispaid->payment_date }}</p> 
                                                            @endif
                                                        </div>
                                                    </div>
                                                    
                                                    @if (!$month->ispaid)
                                                        <span class="bg-white pay__button" data-month="{{ $month->month }}" data-year="{{ $month->year }}" data-amount="{{ round(@$month->amount) }}"  data-type="month">PAY NOW</span>
                                                    @else
                                                     <span class="bg-white confirm__button" data-month="{{ $month->month }}" data-year="{{ $month->year }}" data-amount="{{ round(@$month->amount) }}"  data-type="month">CONFIRMED</span>
                                                    @endif
                                                    
                                                </div>
                                            </div>
                                    
                                        </div>
                                       @endforeach
                                    @endif

                                     @if (@$feedata->payment_type==2)

                                     <div class="col-12 col-lg-4">
                                            <div class="card radius-15 {{ $full_due[0]->ispaid ? "bg-wall" : "bg-rose" }}">
                                                <div class="card-body text-center">
                                                    <div class="widgets-icons mx-auto bg-white rounded-circle">
                                                        @if ($full_due[0]->ispaid)
                                                           <i class='bx bx-check'></i>
                                                        @else
                                                        <i class='bx bx-x'></i>
                                                        @endif
                                                       
                                                    </div>

                                                    <div class="amount__info mt-4 mb-3">
                                                         <p class="mb-0 text-white term">Full Payment</p>
                                                        <div class="amount">
                                                            <div>
                                                                 <h4 class="mb-0 font-weight-bold mt-3 text-white">{{ Configurations::CurrencyFormat(@$grand_total) }} </h4>
                                                            </div>
                                                           

                                                            
                                                           
                                                        </div>
                                                        <div class="info">
                                                           
                                                            <p class="mb-0 text-white duedate">Due Date : {{ $feedata->dueinfo }}</p>
                                                            @if ($full_due[0]->ispaid)

                                                                 <p class="mb-0 text-white duedate">Paid Date : {{ $full_due[0]->ispaid->payment_date }}</p> 
                                                            @endif
                                                        </div>
                                                    </div>
                                                    
                                                    @if (!$full_due[0]->ispaid)
                                                        <span class="bg-white pay__button"  data-amount="{{ @$full_due[0]->amount }}" data-duedate="{{ $full_due[0]->duedate }}" data-type="full">PAY NOW</span>
                                                    @else
                                                     <span class="bg-white confirm__button"  data-amount="{{ @$full_due[0]->amount }}" data-duedate="{{ $full_due[0]->duedate }}">CONFIRMED</span>
                                                    @endif
                                                    
                                                </div>
                                            </div>
                                    
                                        </div> 
                                     @endif
                                    
                                    

                                    
                                </div>

                                <input type="hidden" name="selected_term" class="selected_term"/>
                                 <input type="hidden" name="paid_amount" class="selected_term_amount"/>
                                  <input type="hidden" name="selected_term_date" class="selected_term_date"/>

                               
                            </div>
                            </div>
                            {{-- here copied --}}
                        </div>
<script type="module">
FeeConfig.Feeinit();
</script>