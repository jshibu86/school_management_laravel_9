<style>
._p-qty > span {
    color: black;
    margin-right: 15px;
    font-weight: 500;
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
._p-add-cart {
    margin-left: 0px;
    
}
.cart__buttons{
    display: flex;
    align-items: center;
}
.heading{
    font-size: 26px;
}
.details{
    display: flex;
    flex-direction: column;
    gap: 5px;
}

</style>

<section class="single__product">

    <div class="row">

       <div class="col-xs-12 col-sm-6 col-md-4">
            <img src="{{ @$product->product_thambnail }}" class="single__product-img"/>
       </div>
       <div class="col-xs-12 col-sm-6 col-md-8">
            <h5 class="heading">{{ @$product->product_name }} </h5>

            <div class="details">
                <span><span>Product Code : </span >{{ @$product->product_code }}</span>
                <span><span>Supplier Name : </span>{{ @$product->supplier_name }}</span>
    
                <span><span>Stock: </span>{{ @$product->product_qty }} Available</span>
            </div>
           
            <div class="single__product-desription">
                <p>{{ @$product->short_descp }}</p>
            </div>

            <div class="cart__buttons">
                <div class="_p-add-cart">
                    <div class="_p-qty">
                       <span>Add Quantity</span>
                       <div class="value-button decrease_" id="" value="Decrease Value">-</div>
                       <input type="number" name="qty" id="number" value="1" min="1" max="{{ @$product_qty }}"/>
                       <div class="value-button increase_" id="" value="Increase Value">+</div>
                    </div>
                </div>
                <div class="cart__button ms-3">
                    <button onclick="productCart_({{$product->id}})" class="btn btn-dark text-white m-1 px-2 cart"><i class="fa fa-shopping-cart"></i> Add to Cart</button>
                </div>
            </div>

           
       </div>
    </div>
</section>



 <script>

    window.minicart='{{route('cart.minicart')}}'

    var product_qty={!! json_decode($product->product_qty) !!};

    console.log(product_qty);

   function productCart_(id){

        var qty=$("#number").val();
        ProductConfig.Addtocart(id,qty)
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