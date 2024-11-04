<style>
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
    height: 20px;
    width: 20px;
    font-size: 12px;
    font-weight: bold;
    cursor: pointer;
  
}
._p-qty input#number {
    text-align: center;
    border: none;
    margin: 0px;
    width: 25px;
    height: 23px;
    font-size: 14px;
    box-sizing: border-box;
}
._p-add-cart {
    margin-left: 0px;
    
}
.refresh-icon{
    margin-left: 5px;
    cursor: pointer;
}
.product__Details{
    width: 80px;
}
table{
    width: 100%;
}
.image-class{
    width: 30px!important;
}
table tr{
    line-height: 3;
}
.product__price{
    text-align: right;
}
.checkout__button{
    width: 80%;
    margin: auto;
}
</style>
<div class="order__heading">
    <h5 class="text-lg font-semibold">Order Summary</h5>
</div>

<div class="cart__contents">
    
    <table>
        <thead>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            @foreach (@$carts as $cart)
                <tr>
                    <td>
                        
                        <img src="{{ $cart->options->image }}" class="image-class"/>
                        
                     </td>

                     <td> <h5 class="text-base">{{ @$cart->name }}</h5></td>
                    <td>


                        <div class="_p-add-cart">
                            <div class="_p-qty">
                              
                               <div class="value-button decrease_" id="" value="Decrease Value">-</div>
                               <input type="number" name="qty" id="number" value="{{ $cart->qty }}" min="1" max="{{ @$product_qty }}"/>
                               <div class="value-button increase_" id="" value="Increase Value">+</div>
                               <i class="fa fa-refresh refresh-icon" data-id="{{ $cart->rowId }}" onclick="updatecart(this)"></i>
                            </div>
                        </div>
                    </td>

                    <td>  <div class="bottom__content">
                        <span onclick="ProductConfig.productremove(this.id,2)" id="{{ $cart->rowId }}">Remove</span>
                   </div></td>
                    
                    <td><div class="product__price">
                        <span class="font-semibold">{{ @$cart->options->total }} ₦</span>
                    </div></td>
                </tr>
            @endforeach
        </tbody>
    </table>

   
   
</div>

<hr/>

<div class="calculations">
    <div class="sub__total">
        <p> Subtotal </p>
        <p class=" font-semibold">{{ @$carttotal }} ₦ </p>
    </div>
    <div class="sub__total">
        <p> Total </p>
        <p class=" font-semibold">{{ @$carttotal }} ₦</p>
    </div>

    
</div>

<script>

    var product_qty={!! json_decode($cart->options->default_qty) !!};

    function updatecart(id)
    {
        window.updatecart="{{ route('cart.updatecart') }}";
        var id1 =  $(id).attr("data-id");
        var qty =$(id).parents("._p-qty").find("#number").val()

        ProductConfig.updateCart(id1,qty);
        console.log("data_id",id1,"quantity",qty);
    }

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
                        text: "Product Out of Stock",
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
</script>