@extends('layout::admin.master')

@section('title','shop')
@section('style')
    <!-- Datatables -->
    @include('layout::admin.head.list_head')
    <style>
        .table-div table {
            width: 100% !important;
        }
        .search-text:focus {
   
        background-color: #fff;
        border-color: #cdcdcd !important;
    
        box-shadow: none !important;
        }
    </style>
@endsection
@section('body')
<div class="card">
    <div class="card-body">
        <div class="card-title btn_style">
            <h4 class="mb-0">View Products</h4>
            @if(CGate::allows('create-shop'))
            <a class="btn btn-primary btn-sm m-1  px-3" href="{{route('shop.create',['type'=>@$type])}}" ><i class='fa fa-plus'></i>&nbsp;&nbsp;Create Product</a>
            @endif
          
        </div>
        <hr/>

        @if (Session::get("ACTIVE_GROUP") == "Student")
        <div class="modal fade" id="view__homeworks"  aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
               
                <div class="modal-content" >
                    <div class="modal-header">
                      
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body assigen_parent_body">
        
                        <div class="homework_details">
                           some
                        </div>
          
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                       
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

                <div class="search_place">
                    <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fa fa-search" aria-hidden="true"></i></span>
                        <input type="text" class="form-control search-text" placeholder="Search products" aria-label="Search products" aria-describedby="addon-wrapping" id="product_search">
                    </div>
                </div>
           
        </div>
        <div id="search_result">
             <div class="row">

                @forelse (@$products as$product )
                <div class="col-xs-12 col-lg-3 col-md-4">
                    <div class="card radius-15">
                        <div class="image_content">
                            <img src="{{ @$product->product_thambnail }}" class="product-card-img-top" alt="...">
                        </div>
                    
                    
                            <div class="card-body">
                                <h5 class="card-title">{{  Str::limit(ucfirst(@$product->product_name) ,7) }}</h5>
                                <p class="card-text">Price : <strong>{{ @$product->selling_price }} ₦</strong></p> 
                                
                                <button onclick="ProductConfig.Openmodel({{$product->id}})" class="btn btn-danger m-1 px-2 cart"><i class="fa fa-eye"></i> View Product</button>
                                
                            </div>

                    
                    
                    </div>
                </div>
            
                @empty
                <p>No Products</p>
                    
                @endforelse
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        {{ $products->links() }}
                    
                    </ul>
                </nav>
            </div>
        </div>
        @else
        <div class="table-responsive">
            <table id="datatable-buttons1" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Image</th>
                        <th>Product Name</th>
                        <th>Stock</th>
                        <th>Stock Status</th>
                        <th>Price ₦</th>
                        
                        <th>Status</th>
                        <th class="noExport">Action</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            
            </table>
        </div>
        @endif

        
     
       
    </div>
</div>


  

@endsection

@section('scripts')
<script type="module">
    window.Productsearch='{{route('shop.index')}}';
    window.addtocart='{{route('cart.addtocart')}}';
    window.getproduct='{{route('shop.index')}}';
    AcademicConfig.searchContact();

    //window.minicart='{{route('cart.minicart')}}'


   
</script>
@endsection
@section('script')
    <script>
     window.statuschange='{{route('shop_action_from_admin')}}';
        $('document').ready(function(){

            var element = $("#datatable-buttons1");
            var url =  '{{route('get_shop_data_from_admin')}}'+"?type="+@json(@$type);
            var column = [
              
                {data: 'DT_RowIndex', name: 'DT_RowIndex',searchable: false,sortable: false},
                { data: 'image', name: 'image' ,searchable: false,sortable: false},
                { data: 'product_name', name: 'product_name'},
                { data: 'product_qty', name: 'product_qty'},
                { data: 'qty', name: 'product_qty',searchable: false,sortable: false},
                { data: 'price', name: 'selling_price' , className: 'textcenter' },
               
                 { data: 'status', name: 'id', searchable: false, sortable: false, className: 'textcenter',render : function(data, type, row, meta)
                    {
                         return `<label class="switch">
                        <input type="checkbox" id=${row['id']} ${row['status']=="Enabled" ? 'checked':''} class="toggle-class" onchange="myFunction(this.checked ? 1:0,this.id)">
                        <span class="slider round"></span>
                      </label>`;
                       
                        
                    }
                  },
                { data: 'action', name: 'id', searchable: false, sortable: false, className: 'textcenter'}
            ];
            var csrf = '{{ csrf_token() }}';

            var options  = {
                //order : [ [ 6, "desc" ] ],
                lengthMenu: [[15,25,50, 100 ,250, 500, -1], [15,25,50, 100 ,250, 500,"ALL"]],
                button : [
                    {
                        name : "Publish" ,
                        url : "{{route('shop_action_from_admin',1)}}"
                    },
                    {
                        name : "Un Publish",
                        url : "{{route('shop_action_from_admin',0)}}"
                    },
                    {
                        name : "Trash",
                        url : "{{route('shop_action_from_admin',-1)}}"
                    },
                    {
                        name : "Delete",
                        url : "{{route('shop.destroy',1)}}",
                        method : "DELETE"
                    }
                ],

            }


            dataTable(element,url,column,csrf,options);

        });
    </script>

@endsection
