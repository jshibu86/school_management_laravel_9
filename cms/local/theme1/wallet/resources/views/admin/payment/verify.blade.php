
<style>
    .input{
        width: 50%;
    }
</style>
@if (count(@$data) >0)

<div class="table-responsive">
    <table class="table table-borderless">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Attachment</th>
                <th scope="col">Verified</th>
                <th scope="col">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key => $wallet)
            <tr>
                <th scope="row">{{ @$key+1 }}</th>
                <td><a href="{{  $wallet->wallet_attachment }}" target="_blank">View File</a></td>
                <td>

                   
                  
                   <input type="hidden" name="verify[{{$wallet->id   }}]" value="0"/>
                    <div class="form-check">
                    <input class="form-check-input" type="checkbox"  id="flexCheckChecked" name="verify[{{ $wallet->id  }}]" value="1">
                    <label class="form-check-label" for="flexCheckChecked">Is Verified</label>
                </div></td>
                <td>
                    <input class="form-control form-control-sm mb-3 input" type="text" placeholder="Amount"  name="amount[{{$wallet->id }}]" value="{{ $wallet->amount }}">
                
                </td>
            </tr>
            @endforeach
           
            
        </tbody>
    </table>
</div>

@else
    <span>No Data</span>
@endif

