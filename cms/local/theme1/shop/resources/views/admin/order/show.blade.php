@extends('layout::admin.master')

@section('title','shop')
@section('style')

<style>
    .invoice-to{
        display: flex;
        flex-direction: column;
        gap: 3px;
    }
    .text-center{
        text-align: center;

    }
    thead{
        
    background: #f7f7f7;
    }
    .card-item{
        display: flex;
    align-items: center;
    justify-content: space-between;
    }
    .slect-form{
        width: 15%;
    }
</style>
@endsection
@section('body')
    <div class="x_content">

      
     
            {{ Form::open(array('role' => 'form', 'route'=>array('order.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
      
        <div class="box-header with-border mar-bottom20">

            @if (Session::get("ACTIVE_GROUP") == "Super Admin")
            @if (@$data->order_status != 3)
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_shop' , 'class' => 'btn btn-success btn-sm m-1  px-3')) }}
            @endif
            @endif

            <a class="btn btn-info btn-sm m-1  px-3" href="{{route('order.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

            

              

             
        </div>
           @include("layout::admin.breadcrump",['route'=> "Show order"])

             <div class="card">
                <div class="card-body">
                    <div class="card-item">
                        <h5 class="card-title">Show Order</h5>

                   @if (Session::get("ACTIVE_GROUP") == "Super Admin")
                   <div class="slect-form">
                           @if (@$data->order_status != 3)
                           <div class="feild">
                            {{ Form::select('status',Configurations::ORDERSTATUS,@$data->order_status ,
                            array('id'=>'type','class' => 'form-control single-select','required'=>"required","placeholder"=>"Change Status" )) }}
                            </div>
                           @endif
                   
                </div>
                   @endif
                        
                    </div>
                    
                 
                    <hr/>

                    <div id="invoice">
                       
                        <div class="invoice overflow-auto">
                            <div style="min-width: 600px">
                                
                                <main>
                                    <div class="row contacts">
                                        <div class="col invoice-to">
                                            <div class="text-gray-light">CUSTOMER:</div>
                                            <h3 class="to">{{ @$user_data->name }}</h3>
                                            <div class="address"><i class="fa fa-phone "></i> {{ @$user_data->mobile }}</div>
                                            <div class="email"><a href="mailto:{{ @$user_data->email }}"><i class="fa fa-envelope"></i> {{ @$user_data->email }}</a>
                                            </div>
                                        </div>
                                        <div class="col invoice-details">
                                            <h2 class="invoice-id">{{ @$data->order_number }}</h2>
                                            <div class="date">Date of Order: {{ @$data->order_date }}</div>
                                            
                                        </div>
                                    </div>
                                    <table>
                                        <div class="text-gray-light mb-3">ORDER ITEMS:</div>
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th class="text-left">Image</th>
                                                <th class="text-center">Product Name</th>
                                                <th class="text-center">Unit Price</th>
                                                <th class="text-center">Quantity</th>
                                                <th class="text-center">Total Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @foreach ($data->orderitems as $key=> $item )
                                            <tr>
                                                <td>{{ $key+1 }}</td>
                                                <td ><img src="{{ @$item->product_image }}" alt="{{ $item->product_name }}" width=30/></td>

                                                <td class="text-center">{{ @$item->product_name }}</td>
                                                <td class="text-center">{{ @$item->product_price }} ₦</td>
                                                <td class="text-center">{{ @$item->qty }}</td>
                                                <td class="text-center">{{ @$item->total_price }} ₦</td>
                                            </tr>
                                            @endforeach

                                           
                                       
                                          
                                           
                                           
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="2"></td>
                                                <td colspan="3">SUBTOTAL</td>
                                                <td class="text-center">{{ @$data->order_amount }} ₦</td>
                                            </tr>
                                            
                                            <tr>
                                                <td colspan="2"></td>
                                                <td colspan="3">GRAND TOTAL</td>
                                                <td class="text-center">{{ @$data->order_amount }} ₦</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    {{-- <div class="thanks">Thank you!</div> --}}
                                    {{-- <div class="notices">
                                        <div>NOTICE:</div>
                                        <div class="notice">A finance charge of 1.5% will be made on unpaid balances after 30 days.</div>
                                    </div> --}}
                                </main>
                                <footer>Invoice was created on a computer and is valid without the signature and seal.</footer>
                            </div>
                            <!--DO NOT DELETE THIS div. IT is responsible for showing footer always at the bottom-->
                            <div></div>
                        </div>
                    </div>

                    
                </div>
            </div>

        
       
       

        {{Form::close()}}
    </div>

@endsection

@section('script_link')

    <!-- validator -->
    {!! Cms::script("theme/vendors/validator/validator.js") !!}
    {!! Cms::script("theme/vendors/validator/validator_form.js") !!}

@endsection
