@if($fees_type == "paid")   
   <thead>
        <tr>
            <th>No</th>
            <th>Student Name</th>
            <th>Reg No</th>
            <th>Amount</th>
            <th>Status</th>
            <th class="noExport">Action</th>
        </tr>
  </thead>
    <tbody>
        @if($data !== null)
            @foreach($data as $student)
                <tr>
                    <td>{{$loop->index+1}}</td>
                    <td>{{$student->student->first_name}}</td>
                    <td>{{$student->student->reg_no}}</td>       
                    <td>{{$student->paid_amount}}</td>
                    <td class = "text-success">Paid</td>
                    <td class="text-center">
                        <a target="_blank" class="btn btn-info print_url" data="{{ $student->student->id }}" data-paid-amount="{{$student->paid_amount}}" data-student="{{ $student->student->id }}" href="{{ asset($student->receipt_url) }}">Receipt</a>
                    </td>
                   
                </tr>
            @endforeach
        @else
         <tr>
            <td colspan="6" class="text-center">No data found</td>
         </tr>
        @endif
    
    </tbody>
@else
    <thead>
        <tr>
            <th>No</th>
            <th>Student Name</th>
            <th>Reg No</th>
            <th>Amount</th>
            <th>Status</th>
            <th class="noExport">Action</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($data1) && $total_amount !== null)
           @php
               $monthsPay = Configurations::GetMonthsOfAcademicYear(
                        $academic_year,
                        null
                    );
              
           @endphp
            @foreach($unpaid_students as $student)
              <input type="hidden" name="student[]" value="{{$student->id}}">
            
                @php

                   $academic_fee_info_sum = DB::table('academic_fees')->where(
                        "student_id",
                        $student->id
                    )->sum("due_amount");   
                   if($student->scholarship !== null){
                    $schamount =  ($student->scholarship / 100) * $total_amount + $academic_fee_info_sum;
                   
                    $total = $total_amount + $academic_fee_info_sum;
                 
                    $grand_total = $total - $schamount;
                   
                   }
                  else{
                    $grand_total = $total_amount + $academic_fee_info_sum;
                  }
                  if($payment_type == 0){
                    $monthlyPayment = $grand_total / sizeof($monthsPay);
                  }
                  if($payment_type == 1){
                   
                    $termsPay = $grand_total *($fees_percentage / 100);
                  }
                  if($payment_type == 2){
                   
                   $onePay = $grand_total;
                 }
                @endphp
              
                <tr>
                    <td>{{$loop->index+1}}</td>
                    <td>{{$student->first_name}}</td>
                    <td>{{$student->reg_no}}</td>  
                    @php
                      
                      $exist = collect($data1)->where('student_id', $student->id)->first();
                      
                      if($exist){
                        $paid_amount = $exist->sum('paid_amount');
                       
                        if($payment_type == 0){
                            $tot_amount = $monthlyPayment - $paid_amount;
                        }
                        if($payment_type == 1){
                            $tot_amount = $termsPay - $paid_amount;
                        }
                        if($payment_type == 2){
                            $tot_amount =  $onePay - $paid_amount;
                        }
                        $amount = $tot_amount;
                      }
                      else{
                        if($payment_type == 0){
                            $tot_amount = $monthlyPayment;
                        }
                        if($payment_type == 1){
                            $tot_amount = $termsPay;
                        }
                        if($payment_type == 2){
                            $tot_amount = $onePay; 
                        }
                        $amount = $tot_amount;                       
                      }
                      $status = "unpaid";
                    @endphp     
                    <td>{{round($amount)}}</td>
                    <td class="text-danger">{{$status}}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-default view_fees_unpaid" data-toggle="modal" data-student-id="{{$student->id}}"><i class="fa fa-eye"></i></button>
                    </td>
                    <input type="hidden" name="total_amount" class="total_amount" value="{{$total_amount}}">
                    <input type="hidden" name="payment_type" class="payment_type" value="{{$payment_type}}">
                    <input type="hidden" name="student_name{{$student->id}}" class="student_name{{$student->id}}" value="{{$student->first_name}}">
                    <input type="hidden" name="student_reg_no{{$student->id}}" class="student_reg_no{{$student->id}}" value="{{$student->reg_no}}">
                    <input type="hidden" name="unpaid_amount" class="unpaid_amount{{ $student->id }} unpaid_amount" data-student-id="{{ $student->id }}" value="{{ $amount}}">
                    @if($payment_type == 0)
                    <input type="hidden" name="monthly_amount{{$student->id}}" class="monthly_amount{{$student->id}}" value="{{$monthlyPayment}}">
                    @elseif($payment_type == 1)
                    <input type="hidden" name="term_amount{{$student->id}}" class="term_amount{{$student->id}}" value="{{$termsPay}}">
                    @else
                    <input type="hidden" name="one_pay_amount{{$student->id}}" class="one_pay_amount{{$student->id}}" value="{{$onePay}}">
                    @endif
                  
                    @if($student->scholarship !== null)
                    <input type="hidden" name="student_scholarship{{$student->id}}" class="student_scholarship{{$student->id}}" value="{{$student->scholarship}}">
                    @else
                    <input type="hidden" name="student_scholarship{{$student->id}}" class="student_scholarship{{$student->id}}" value="0">
                    @endif
                </tr>
            @endforeach
        @else
        <tr>
            <td colspan="6" class="text-center">No data found</td>
        </tr>    
        @endif
   
    </tbody>
@endif    

<div class="modal fade" id="view_student" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered form">

        <div class="modal-content">

            <div class="modal-body assigen_parent_body">

                    <div class="student_details position-relative">
                        some  
                        </div>

                    </div>
                    <div class="modal-footer position-absolute top-0 end-0">
                        @if (Session::get('ACTIVE_GROUP') == 'Super Admin')
                            {{-- <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Submit</button> --}}
                        @endif
                        {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> --}}
                        <i class="fa fa-times-circle fs-2" style="color: red" data-bs-dismiss="modal"
                            aria-hidden="true"></i>


                    </div>
            </div>




        </div>
    </div>
</div>

<script type="module">
    function notify_script(title, text, type, hide) {
        new PNotify({
            title: title,
            text: text,
            type: type,
            hide: hide,
            styling: 'fontawesome'
        })
    }


    window.sectionurl = '{{ route('section.index') }}';
    window.classurl = '{{ route('schooltype.index') }}';
    window.getstudentperformanceinfo = "{{ route('studentperformance') }}";
    window.fees_paid_report = "{{route('fees_payment')}}";
    window.fees_reminder = "{{route('fees_reminder')}}"
   
    AttendanceConfig.AttendanceInit(notify_script);
    AcademicConfig.Leaveinit(notify_script);
    //grade -- Class,Section List
    PromotionConfig.PromotionInit(notify_script);
    // ReportConfig.ReportInit(notify_script);
    FeeStructureConfig.FeeStructureInit(notify_script);
    //grade chart
    Account.AccountInit();
   
    // window.student_overall_report_info = "{{ route('grade_student_report_view') }}"
    // ReportConfig.getStudentsMarkinfo(id,academic_year,position,term);
</script>