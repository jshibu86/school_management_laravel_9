


<div class="school_information text-center">

    <h4>{{ Configurations::getConfig('site')->school_name }}</h4>
    <p>{{ Configurations::getConfig('site')->place }},{{ Configurations::getConfig('site')->city }} ,{{ Configurations::getConfig('site')->post }}</p>

</div> 

<div class="row" style="width: 60%;margin:auto">
    
    <div class="col-12 col-lg-4">
        <div class="card radius-15 bg-primary-blue">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <h2 class="mb-0 text-white">{{sizeof(@$students)}} <i class="bx bxs-down-arrow-alt font-14 text-white"></i> </h2>
                    </div>
                    <div class="ms-auto font-35 text-white"><i class='bx bx-user' ></i>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-white">Total Students</p>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="card radius-15 bg-rose">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <h2 class="mb-0 text-white">{{@$students ? @$students->where("gender","male")->count() : 0}} <i class="bx bxs-up-arrow-alt font-14 text-white"></i> </h2>
                    </div>
                    <div class="ms-auto font-35 text-white"><i class='bx bx-male'></i></i>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-white">Total Male</p>
                    </div>
                    <
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="card radius-15 bg-sunset">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <h2 class="mb-0 text-white">{{@$students ? @$students->where("gender","female")->count() : 0}} <i class="bx bxs-up-arrow-alt font-14 text-white"></i> </h2>
                    </div>
                    <div class="ms-auto font-35 text-white"><i class='bx bx-female' ></i>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-white">Total Female</p>
                    </div>
                   
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-6">
             <div class="accordion" id="accordionSubject">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                Class Subject Teacher Information
                </button>
                 </h2>
                <div id="collapseTwo" class="accordion-collapse collapse show" aria-labelledby="headingTwo" data-bs-parent="#accordionSubject">
                    <div class="accordion-body">	
                        <div class="table-responsive">
                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Subject</th>
                                    <th scope="col">Subject Code</th>
                                    <th scope="col">Subject Teachers</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (@$subjects as $subject)

                                <tr>
                                    <th scope="row">{{$loop->index+1}}</th>
                                    <td>{{@$subject->name}}</td>
                                    <td>{{@$subject->subject_code}}</td>
                                    <td>
                                        @if (sizeof(@$subject->subjectmapping))
                                            <div>
                                                <ul class="map-class">
                                                
                                                @foreach (@$subject->subjectmapping as $map )
                                                    <li>{{$map->teacher->teacher_name}}</li>
                                                @endforeach

                                                </ul>
                                            </div>
                                        @else

                                        <span class="badge bg-danger">Not Assign</span>
                                        @endif
                                        

                                    </td>
                                </tr>
                                    
                                @endforeach
                                
                               
                            </tbody>
                        </table>
					</div>
                    </div>
                </div>
            </div>
            
    </div>
    </div>  
    
    <div class="col-md-6">
        <div class="row">
            <div class="col-12">
                <div class="card radius-15 bg-light-danger">
                    <div class="card-body">

                        @if (@$classteacher)
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{asset(@$classteacher->teacher->image)}}" width="80" height="80" class="rounded-circle p-1 border bg-white" alt="">
                            <div class="">
                                <h5 class="mb-0 text-danger">{{@$classteacher->teacher->teacher_name}}<small style="font-size: 11px;">(Class Teacher)</small> </h5>
                                <p class="mb-0 text-secondary">{{@$classteacher->teacher->employee_code}}</p>
                                <p class="mb-0 text-secondary">{{@$classteacher->teacher->qualification}}</p>
                                <p class="mb-0 text-secondary">{{@$classteacher->teacher->email}}</p>
                            
                            </div>
                        </div> 
                        @endif
                    
                    </div>
		        </div>
            </div>
            <div class="col-12">
                <div class="card radius-15 bg-light-danger">
                    <div class="card-body">

                        {{Configurations::CurrencyFormat(@$students_total_fees)}}
                        {{Configurations::CurrencyFormat(@$students_paid_fees)}}
 
         
                            <div class="card-body">                               
                                <div id="feespiechart" class="mb-4"></div>                    
                            </div>
                     </div>
		        </div>
            </div>
        </div>
        
    </div>
    
</div>  

 
<script type="module">

    var students_total_fees=@json(@$students_total_fees);
    var students_paid_fees=@json(@$students_paid_fees);    
    Account.AccountInit();
    Account.FeespieChart(students_total_fees,students_paid_fees);

</script>
