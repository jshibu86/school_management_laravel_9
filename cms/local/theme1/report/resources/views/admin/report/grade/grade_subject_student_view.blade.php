
<div class="x_content">
    <div class="card">   
        <div class="card-body">
            <h1 class="accordion-header mb-5 text-center" id="headingOne">               
                 Student Overall Grade Report     
            </h1>
            <div class="row">
                <div class="exam_information w-75 mx-auto text-center">          
                    <p class="text-center"><img src="{{ asset($student->image ? $student->image : 'assets/images/default.jpg') }}" class="img-fluid stu_profile" width="120px"/></p>
                     <div class="row">
                        <div class="col-6">
                            <p>Name: <span class="fw-bold">{{ $student->first_name }}</span></p>
                        </div>
                        <div class="col-6">
                            <p>Reg.NO: <span class="fw-bold">{{ $student->reg_no }}</span></p>
                        </div>
                     </div>
                </div>
            </div>
        </div>
    </div>  
     <div class="card">
        <div class="card-body">
            @if(isset($subject_report))
            <div class="row">
                <div class="exam_information w-75 mx-auto text-center">
                    <div class="">
                       
                            @if($subject_report->distribution)
                            @foreach($subject->distribution as $key=>$data)
                            <p>{{ $data->distributionname }} : {{ $data->mark }}/{{ $data->originalmark }}</p>
                            @endforeach
                            @endif
                        
                       
                            <p>Total : {{ $subject_report->total_mark }}</p>                 
                           
                            <p>Grade : <span class="fw-bold">{{ $subject_report->grade }}</span></p>
                            <p>Point : <span class="fw-bold">{{ $subject_report->point }}</span></p>
                            <p>Remark : <span class="">{{ $subject_report->remark }}</span></p>
                                             
                    </div>                      
                </div>
            </div>         
            @else
                 <p class="badge bg-secondary fs-6 text-center" style="display:block !important">There is no subject records found.</p>
            @endif     
        </div>
     </div>         
</div>    

