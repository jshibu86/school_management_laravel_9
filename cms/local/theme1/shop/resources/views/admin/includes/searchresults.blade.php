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
    <div class="no_products">
        <p>No Products Found</p>
    </div>
   
        
    @endforelse
    <nav aria-label="Page navigation example">
        <ul class="pagination">
             {{ $products->links() }}
           
        </ul>
    </nav>
</div>