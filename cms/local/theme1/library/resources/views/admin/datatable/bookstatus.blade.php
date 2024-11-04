<style>
    .information{
        display: flex;
        flex-direction: column;
        gap: 15px;
        margin: 25px 10px;
        align-items: center
    }
    .copy__information{
        display: flex;
        gap: 20px;
    }
    ._p-qty > span {
    color: black;
   
    font-weight: 500;
    text-align: center;
    margin-bottom: 9px;
}
._p-qty .value-button {
    display: inline-flex;
    border: 0px solid #ddd;
    margin: 0px;
    width: 30px;
    height: 35px;
    justify-content: center;
    align-items: center;
    background: #fd7f34;
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    color: #fff;
}

._p-qty .value-button {
    border: 0px solid #fe0000;
    height: 35px;
    font-size: 20px;
    font-weight: bold;
}
._p-qty input#number {
    text-align: center;
    border: none;
   
    margin: 0px;
    width: 50px;
    height: 35px;
    font-size: 14px;
    box-sizing: border-box;
}
._p-qty{
    display: flex;
    flex-direction: column
}
</style>

<div class="modal-header">
    <h5 class="modal-title" id="staticBackdropLabel">{{ @$book->title }}</h5>
    
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

    
  </div>

  <div class="information">
    <div class="copy__information">
        @php
            $noofcopyavailable_change=@$book->active_count - @$book->book_rended;


            $no_of_count_without_active = $book->inactive_count + $book->damaged_count +$book->stolen_count+ $book->lost_count;
        @endphp

      <input type="hidden" value="{{ @$id }}" name="id"/>
      <input type="hidden" value="{{ @$status }}" name="status"/>
        <strong>Total Copies : {{ $book->total_count }}</strong>

       
        <strong style="{{ @$status == 1 ? "display:none":"" }}" class="available">Available Copies to change status : <span class="count">{{ $noofcopyavailable_change }}</span></strong>
     
       
    </div>
    <div class="sttaus__list">
        <span class="badge bg-light text-dark">{{ $book->book_rended }} - Already Rended Book</span>
        <span class="badge bg-{{ @$status == 1 ? "success" : "light text-dark" }}">{{ $book->active_count }} - Active</span>
        @if (@$status != 1)
        <span class="badge bg-{{ @$status == 0 ? "danger" : "light text-dark" }}">{{ $book->inactive_count }} - In Active</span>
        <span class="badge bg-{{ @$status == 2 ? "info" : "light text-dark" }}">{{ $book->damaged_count }} - Damaged</span>
        <span class="badge bg-{{ @$status == 3 ? "primary" : "light text-dark" }}">{{ $book->stolen_count }} - Stolen</span>
        <span class="badge bg-{{ @$status == 4 ? "warning" : "light text-dark" }}">{{ $book->lost_count }} - Lost</span>
        @endif
    </div>

    @if (@$status == 1)


    <div class="active__list">
        <label>Select Status : </label>
        <input class="form-check-input" type="radio" value="0" id="{{ $book->inactive_count }}" name=selectstatus >

        <span class="badge bg-{{ @$status == 0 ? "danger" : "light text-dark" }}">{{ $book->inactive_count }} - In Active</span>

        <input class="form-check-input" type="radio" value="2" id="{{ $book->damaged_count }}" name=selectstatus>

       <span class="badge bg-{{ @$status == 2 ? "info" : "light text-dark" }}">{{ $book->damaged_count }} - Damaged</span>

       <input class="form-check-input" type="radio" value="3" id="{{ $book->stolen_count }}" name=selectstatus>

       <span class="badge bg-{{ @$status == 3 ? "primary" : "light text-dark" }}">{{ $book->stolen_count }} - Stolen</span>

       <input class="form-check-input" type="radio" value="4" id="{{ $book->lost_count }}" name=selectstatus>
       <span class="badge bg-{{ @$status == 4 ? "warning" : "light text-dark" }}">{{ $book->lost_count }} - Lost</span>
    </div>

       
  
        
    @endif
    <div class="danger" style="display: none">
        <p class="text-danger">No Books Available !</p>
    </div>

   
    <div class="sction_button qty_section" style="{{ @$status == 1 ? "display:none":"" }}">
        <div class="_p-add-cart">
            <div class="_p-qty">
               <span>Select Copies</span>
               <div>
                <div class="value-button decrease_" id="" value="Decrease Value">-</div>
                <input type="number" name="qty" id="number" value="0" min="1" readonly/>
                <div class="value-button increase_" id="" value="Increase Value">+</div>
               </div>
           
            </div>
        </div>
    </div>
    @if ($status !=1 && $book->active_count ==0)
    <div class="danger">
        <p class="text-danger">No Active Books Available | Change any of Books to Active State</p>
    </div>
    @endif
   
   
   
</div>
<div class="modal-footer">
                 
    <button type="submit" class="btn btn-success modalsubmit" data-bs-dismiss="modal" {{ @$status == 1 ? "disabled":"" }}>Change Status</button>
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
   
</div>

<script>

var product_qty={!! json_decode($noofcopyavailable_change) !!};

$('.decrease_').click(function () {
            decreaseValue(this);
        });
$('.increase_').click(function () {
            increaseValue(this);
        }); 

        function increaseValue(_this) {
            var value = parseInt($(_this).siblings('input#number').val(), 10);
            value = isNaN(value) ? 0 : value;
            if(product_qty <= value)
            {
                Snackbar.show({
                        text: "Empty Available Copies to change status ",
                        pos: "top-center",
                    });
                    return ;

            }else{
                value++;
            }
           
            
           
            $(_this).siblings('input#number').val(value);
        }

        function decreaseValue(_this) {
            var value = parseInt($(_this).siblings('input#number').val(), 10);
            value = isNaN(value) ? 0 : value;
            value < 1 ? value = 1 : '';
           if(value>1) value--;
            $(_this).siblings('input#number').val(value);
        }

$('input[type=radio][name=selectstatus]').change(function() {
    let value=this.value;
    let count = $(this).attr("id");

    $(".available").show();
    $(".count").text(count);
    product_qty=count;

    if(count > 0)
    {
        $(".qty_section").show();
        $("#number").val(0);
        $(".danger").hide();
        $(".modalsubmit").attr("disabled",false);
    }else{
        $(".qty_section").hide();
        $(".danger").show();
        $("#number").val(0);
        $(".modalsubmit").attr("disabled",true);
    }

   
    if (this.value == 'allot') {
       
    }
    else if (this.value == 'transfer') {
        // ...
    }
});
</script>