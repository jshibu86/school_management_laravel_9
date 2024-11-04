<div class="x_content">
    <div class="card">
        <div class="card-body">
            <div class="card-title btn_style">
                <h4 class="mb-0">View Fee Payment</h4>
            </div>
            <hr/>  

            <div class="row text-center">
               <p>Name : <span class="fw-bold">{{$name}}</span></p>
               <p>Reg.No : <span class="fw-bold">{{$reg_no}}</span></p>
               @if($unpaid_start == "1")
                <p>Unpaid Amount : <span class="fw-bold">{{$unpaid_amount}}</span></p>
                <p>Total Amount : <span class="fw-bold">{{$total_amount}}</span></p>
               @else
                  <p>Unpaid Amount : <span class="fw-bold">{{$unpaid_amount}}</span></p>
                  @if($payment_type == 0)
                    <p>Monthly Amount : <span class="fw-bold">{{$monthly_amount}}</span></p>
                  @elseif($payment_type == 1)
                    <p>Term Amount : <span class="fw-bold">{{$term_amount}}</span></p>
                  @else
                    <p>One Pay Amount : <span class="fw-bold">{{$one_pay_amount}}</span></p>
                  @endif
                
                  @php
                    if($scholarship !== 0){
                      $total = $total_amount - $scholarship;
                    }
                  @endphp
                  @if($scholarship !== "0")
                  <p>Scholarship : <span class="fw-bold">{{$scholarship}}</span></p>
                  <p>Total Amount : <span class="fw-bold">{{$total}}</span></p>
                  @else
                  <p>Total Amount : <span class="fw-bold">{{$total_amount}}</span></p>
                  @endif 
               @endif
               <p>Status : <span class="badge bg-danger">Unpaid</span></p>
            </div>
        </div>
    </div>          
</div>