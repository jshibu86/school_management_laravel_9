<div class="row">
   <div class="title_info d-flex align-items-center">
    <div></div>
      <button type="button" class="btn-close success_close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
    <div class="col-md-12">
        <div class="text-center">
            <img src="{{ asset("assets/images/success.png") }}" style="width:100px"/>
            <p class="fw-bold text-success">Payment Done Successfully</p>
        </div>
         <div class="fee_amount_info mt-4 text-center ">
            <h5 class="fw-bold">Amount : {{ Configurations::CurrencyFormat(@$amount) }}</h5>
            @if (@$type == "term")
                 <p>{{ @$per }}% Amount to be Paid</p>
            @endif
           
         </div>

         <div class="student_information d-flex align-items-center justify-content-center fw-bold p-3 mt-4" style="background-color: #ededed;">
            <div class="info">
                <p>Name : {{ @$student_info->first_name }} {{ @$student_info->last_name }}</p>
                <p>Class/Section :{{ @$student_info->class->name }}/{{ @$student_info->section->name }} </p>
                <p>Department : {{ @$student_info->department ?  @$student_info->department->dept_name : $department }}</p>
                <p>Term : {{ @$term }}</p>
            </div>
           
        </div>

         <div class="action_btn mt-3 row">
                <div class="col-md-6">
                     <button type="button" class="me-4 btn btn-primary" style="background-color: white;width:100%;border-color: black;"><a href="#" target="_blank" style="color: rgb(0, 0, 0)" id="receipt_url">Get PDF Receipt</a></button>
                </div>

                <div class="col-md-6">
                    <button type="button" class="me-4 btn btn-dark done_payment" style="width:100%">Done</button>
                </div>
               
                
          </div>
    </div>
</div>