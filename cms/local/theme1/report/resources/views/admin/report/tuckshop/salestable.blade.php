  <div class="table-responsive" style="margin-top: 40px;">
      <table id="datatable-buttons-tuckshop-purchase" class="table table-striped table-bordered" style="width:100%">
          <thead>
              <tr>
                  <th>No</th>
                  <th>Order Number</th>
                  <th>Customer Name</th>
                  <th>Payment Type</th>
                  <th>Amount ₦</th>
                  <th>Payment Status</th>
                  <th>Delivery Status</th>

              </tr>
          </thead>
          <tbody>

              @if (@$final_data)

                  @foreach (@$final_data as $item )
                      <tr>
                        <td>{{$loop->index+1}}</td>
                        <td>{{$item->order_number}}</td>
                        <td>{{$item->user->name}}</td>
                        <td>{{$item->payment_type}}</td>
                        <td>{{$item->order_amount}}</td>
                        <td>{!! $item->payment_status == 1 ? '<span class="text-success">Completed</span>' : 'Pending' !!}</td>
                       <td>{!! $item->payment_status == 1 ? '<span class="text-success">Completed</span>' : 'Pending' !!}</td>
                      </tr>
                  @endforeach

              @endif

          </tbody>

      </table>
  </div>
