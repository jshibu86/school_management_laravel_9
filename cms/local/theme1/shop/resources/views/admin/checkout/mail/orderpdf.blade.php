<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Invoice</title>

<style type="text/css">
    * {
        font-family: Verdana, Arial, sans-serif;
    }
    table{
        font-size: x-small;
    }
    tfoot tr td{
        font-weight: bold;
        font-size: x-small;
    }
    .gray {
        background-color: lightgray
    }
    .font{
      font-size: 15px;
    }
    .authority {
        /*text-align: center;*/
        float: right
    }
    .authority h5 {
        margin-top: -10px;
        color: green;
        /*text-align: center;*/
        margin-left: 35px;
    }
    .thanks p {
        color: green;;
        font-size: 16px;
        font-weight: normal;
        font-family: serif;
        margin-top: 20px;
    }
</style>

</head>
<body>

  <table width="100%" style="background: #F7F7F7; padding:0 20px 0 20px;">
    <tr>
        <td valign="top">
          <!-- {{-- <img src="" alt="" width="150"/> --}} -->
          <h2 style="color: green; font-size: 26px;"><strong>{{ Configurations::getConfig('site')->school_name }}</strong></h2>
        </td>
        <td align="right">
            <pre class="font" >
              {{ Configurations::getConfig('site')->school_name }}
              {{ Configurations::getConfig('site')->school_email }}<br>
               {{ Configurations::getConfig('site')->school_phone}} <br>
              {{ Configurations::getConfig('site')->place }}<br>
              
            </pre>
        </td>
    </tr>

  </table>


  <table width="100%" style="background:white; padding:2px;""></table>

  <table width="100%" style="background: #F7F7F7; padding:0 5 0 5px;" class="font">
    <tr>
        <td>
          <p class="font" style="margin-left: 20px;">
           <strong>Name:</strong> {{$user_data->name}} <br>
           <strong>Email:</strong> {{$user_data->email}} <br>
           <strong>Phone:</strong> {{$user_data->phone}} <br>
            
          
         </p>
        </td>
        <td>
          <p class="font">
            <h3><span style="color: green;">Invoice:</span> #{{$order_data->order_number}}</h3>
            Order Date: {{$order_data->order_date}} <br>
             
            Payment Type : {{$order_data->payment_type}} </span>
         </p>
        </td>
    </tr>
  </table>
  <br/>
<h3>Products</h3>


  <table width="100%">
    <thead style="background-color: green; color:#FFFFFF;">
      <tr class="font">
        <th>Image</th>
        <th>Product Name</th>
       
        <th>Quantity</th>
        <th>Unit Price </th>
        <th>Total </th>
      </tr>
    </thead>
    <tbody>

     @foreach($carts as $cart)
      <tr class="font">
        <td align="center">
            <img src="{{ public_path($cart->options->image)}}" height="30px;" width="30px;" alt="">
        </td>
        
        <td align="center">{{@$cart->name}}</td>
       

        
        <td align="center">{{@$cart->qty}}</td>
        <td align="center"><span style="font-family: DejaVu Sans; sans-serif;">₦ </span> {{@$cart->price}}</td>
        <td align="center"><span style="font-family: DejaVu Sans; sans-serif;">₦ </span> {{@$cart->options->total}}</td>
      </tr>
      @endforeach
      
    </tbody>
  </table>
  <br>
  <table width="100%" style=" padding:0 10px 0 10px;">
    <tr>
        <td align="right" >
            <h2><span style="color: green;">Subtotal:<span style="font-family: DejaVu Sans; sans-serif;">₦ </span> {{@$carttotal}}</span> </h2>
            <h2><span style="color: green;">Total:<span style="font-family: DejaVu Sans; sans-serif;">₦ </span></span>  {{@$carttotal}}</h2>
            {{-- <h2><span style="color: green;">Full Payment PAID</h2> --}}
        </td>
    </tr>
  </table>
  
  <!-- <div class="authority float-right mt-5">
      <p>-----------------------------------</p>
      <h5>Authority Signature:</h5>
    </div> -->
</body>
</html>