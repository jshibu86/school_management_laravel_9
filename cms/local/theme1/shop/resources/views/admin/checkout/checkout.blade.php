@extends('layout::admin.master')

@section('title','checkout')
@section('style')


@endsection
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('order.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'order-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('order.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
        
           @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Order Checkout" : "Order Checkout"])


           @if(count($carts))
           <div class="card">
            <div class="card-body">
                <h5 class="card-title">Checkout Order</h5>
                <hr/>

             <div class="row">

                <input type="hidden" name="parsed" id="parsed" value="{{ @$parsed }}"/>

              
                    <div class="col-md-6">
                        <div class="accordion accordion-flush" id="accordionFlushExample">
                            <div class="accordion-item">
                              <h2 class="accordion-header" id="flush-headingOne">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                <i class='bx bx-user me-2' ></i>
                                Contact Information
                                </button>
                              </h2>
                              <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample" style="">
                                <div class="accordion-body">
                                  
                                  <div class="row">
                                    <div class="col-xs-12">
                                        <div class="contact_information">
                                            <div class="personal">
                                                <img src="{{ @$active_student->image }}" width=80/>
                                                <p class=" font-semibold">{{ @$active_student->first_name }} {{ @$active_student->last_name }}</p>

                                                <small class=" font-semibold">{{  @$active_student->reg_no}}</small>

                                                <small>{{  @$active_student->email}}</small>
                                                <small>{{  @$active_student->mobile}}</small>
                                                <span>{{ @$address_communication->house_no }} , {{ @$address_communication->province }} ,{{ @$address_communication->postal_code }} ,{{ @$address_communication->country }}</span>
                                            </div>
                                           
                                            
                                        </div>
                                           
                                    </div>
                                  
        
                                    
                                    
                            
                                </div>
                                </div>
                              </div>
                            </div>
                            <div class="accordion-item">
                              <h2 class="accordion-header" id="flush-headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                                <i class='bx bx-credit-card-alt me-2'></i>
                                  Select Payment Method
                                </button>
                              </h2>
                              <div id="flush-collapseTwo" class="accordion-collapse collapse show" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample" style="">
                                <div class="accordion-body">
                                    <div class="row">
                                        
                                       
                                        <div class="col-xs-12 col-sm-12 col-md-12 ">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="payment_type" 
                                                value="wallet"
                                                id="flexRadioDefault2" checked="">
                                                <label class="form-check-label" for="flexRadioDefault2">Wallet Payment</label>

                                              
                                                <span class="wallet_amount ms-2 font-semibold text-success">{{ @$walletamount }} ₦</span>
                                               
                                              
                                            </div>
                                            
                                               
                                        </div>

                                        <div class="col-xs-12 col-sm-12 col-md-12 pt-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="payment_type"
                                                value="flutterwave"
                                                id="flexRadioDefault3" >
                                                <label class="form-check-label" for="flexRadioDefault3">FlutterWave</label>
                                            </div>
                                            
                                               
                                        </div>
                                      
                                    </div>
                                </div>
                              </div>
                            </div>
                           
                        </div>
                    </div>
                    <div class="col-md-6">

                        <div class="container">

                            <div class="order-cart">
                                <div class="loader">
                                    <h5>Loading.....</h5>
                                    <img src="{{ asset("assets/images/loader.gif") }}" width=60/>
                                </div>
                               
                            </div>
                            
                            <div class="checkout__button pt-5 pb-5">
                                <button type="button" class="btn btn-dark m-1 px-5 radius-30 checkout-btn">Confirm Checkout</button>
                            </div>
                        </div>

                       

                    </div>


            </div>

                
            </div>
        </div>

           @else
           <div class="card">
            <div class="card-body">
                <div class="row empty-cart">
                    <img src="{{ asset("assets/images/cart1.png") }}"/>

                </div>
            </div>
         </div>

           @endif

           

           

        
       
       

        {{Form::close()}}
    </div>

@endsection

@section('script_link')

    <!-- validator -->
    {!! Cms::script("theme/vendors/validator/validator.js") !!}
    {!! Cms::script("theme/vendors/validator/validator_form.js") !!}

@endsection

@section("scripts")

<script type="module">
   
    window.checkoutcart='{{route('order.create')}}';
    ProductConfig.Loadcart();

    //window.minicart='{{route('cart.minicart')}}'


   
</script>

<script>

    var button=document.querySelector(".checkout-btn");
    var wallet_amount={!! json_decode($walletamount) !!};
    var total_amount=document.querySelector("#parsed");
    var form=document.querySelector("#order-form");

    if(button)
    {
        button.addEventListener("click",function(){
        var total=total_amount.value;
           
        var payment_type=document.querySelector('input[name="payment_type"]:checked').value;

        if(payment_type == "wallet")
        {
            if(wallet_amount < total)
            {
                Snackbar.show({
                text: "Whoops !! Insufficient Wallet Amount To Make an Order",
                pos: "bottom-left",
                });
                console.log(payment_type,wallet_amount,"not make payment");
                
            }else{
                form.submit();
                button.textContent="Loading .. "
                console.log("submit form");
            }
        }else{
            form.submit();
            button.textContent="Loading .. "
            console.log("submit form");
        }

        

        
    });
    }

   

   
</script>
@endsection
