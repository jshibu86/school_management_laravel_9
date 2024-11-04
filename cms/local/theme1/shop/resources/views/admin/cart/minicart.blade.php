
<style>
    .cart_{
        justify-content: space-between;
    }
    .rbutton,.total{
        text-align: center;
    }
    .first-child{
        width: 30px;
    }
    .empty-cart{
        align-items: center;
        justify-content: center;
        padding: 11px;
    }
</style>
@forelse ($carts as $cart)



<div class="dropdown-item">
    <div class="d-flex align-items-center cart_">
      <div class="notify bg-light-primary text-primary">
       <img src="{{ $cart->options->image }}" width=20/>
      </div>
      <div class="flex-grow-1 first-child">
        <h6 class="msg-name">
            {{  Str::limit(ucfirst($cart->name) ,10) }}
        
        </h6>
        <p class="msg-info"> {{ $cart->qty }}*{{ $cart->price }}   
        </p>
      </div>
      <div class="flex-grow-1 total">
        <h6 class="msg-name">
        {{ $cart->options->total }} ₦
        </h6>
        
      </div>

      <div class="flex-grow-1 rbutton">
        <button id="{{ $cart->rowId }}" class="btn btn-primary" onclick="ProductConfig.productremove(this.id)" type="button"><i class="fa fa-trash"></i></button>
        
      </div>
     
    </div>
</div>

 
  
@empty
<div class="d-flex empty-cart">
    <p>Cart is Empty</p>
</div>
   
@endforelse

