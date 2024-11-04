  <div class="table-responsive" style="margin-top: 40px;">
      <table id="datatable-buttons-tuckshop-purchase" class="table table-striped table-bordered" style="width:100%">
          <thead>
              <tr>
                  <th>No</th>
                  <th>Product Name</th>
                  <th>Date</th>
                  <th>Bill Number</th>
                  <th>Supplier</th>
                  <th>Quantity</th>
                  <th>Purchase Price ₦</th>

              </tr>
          </thead>
          <tbody>

              @if (@$final_data)

                  @foreach (@$final_data as $item )
                      <tr>
                        <td>{{$loop->index+1}}</td>
                        <td>{{$item->product->product_name}}</td>
                        <td>{{$item->purchase_date}}</td>
                        <td>{{$item->bill_no}}</td>
                        <td>{{$item->vendor ? $item->vendor->supplier_name : "N/A"}}</td>
                        <td>{{$item->quantity}}</td>
                        <td>{{$item->purchase_price}}</td>
                      </tr>
                  @endforeach

              @endif

          </tbody>

      </table>
  </div>
