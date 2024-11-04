
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
                @if(isset($mark_report))
                <div class="row">
                    <div class="exam_information w-75 mx-auto text-center">
                        <div class="row">
                            <div class="col-6">
                                <p>Total Mark Obtainable : {{ $mark_report->total_mark_obtainable }}</p>
                                <p>Total Mark Obtain : {{ $mark_report->total_mark_obtain }}</p> 
                                <p>Teacher's Remark : {{ $mark_report->teacher_remark }}</p>
                            </div>
                            <div class="col-6">
                                <p>Avarage : {{ $mark_report->average }}</p>                 
                                    @php
                                        if($mark_report->average){
                                            $promotion = ($mark_report->is_promotion == 1) ? "Yes" : "No" ;
                                            $color = ($mark_report->is_promotion == 1) ? "bg-success" : "bg-danger" ;
                                        }
                                    @endphp
                                <p>Promoted : <span class="text-white {{ $color }}">{{ $promotion }}</span></p>
                                <p>Position : <span class="fw-bold">{{ Configurations::ordinal($position) }}</span></p>
                            </div>                        
                        </div>                      
                    </div>
                </div>         
                @else
                     <p class="badge bg-secondary fs-6 text-center" style="display:block !important">There is no mark records found.</p>
                @endif     
            </div>
         </div>         
    </div>    

