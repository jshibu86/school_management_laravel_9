
<style>.print_btn{
    text-align: right;
}</style>
<div class=" p-4 rounded">
    <div class="print_btn">
        <a class="btn btn-info btn-sm" id="print" target="_blank" href="{{ route("leaveprint",@$leave_data->id) }}">print</a>
    </div>
    
        <div class="card-body text-center">
            <img src="{{ @$user->images ?  @$user->images : "/assets/images/default.jpg" }}" width=80 alt="student_image" class="rounded-circle p-1 border"/>


        
            <h5 class="mb-0 mt-4">{{ $user->name }}</h5>
            <p class="mb-0 text-secondary">{{ $user->username }}</p>
            
        </div>
   
    <div class="card-title d-flex align-items-center">
        <div></i>
        </div>
        <h5 class="mb-0 text-info">View Leave Information</h5>
    </div>
    <hr>
    <input type="hidden" name="leave_id" value="{{ @$leave_data->id }}"/>
    <div class="row mb-3">
        <label for="inputEnterYourName" class="col-sm-3 col-form-label">Name</label>
        <div class="col-sm-9">
            : {{ $user->name }}
        </div>
    </div>
    <div class="row mb-3">
        <label for="inputPhoneNo2" class="col-sm-3 col-form-label">Email</label>
        <div class="col-sm-9">
            : {{ $user->email }}
        </div>
    </div>
    <div class="row mb-3">
        <label for="inputEmailAddress2" class="col-sm-3 col-form-label">Contact Number</label>
        <div class="col-sm-9">
            : {{ $user->mobile }}
        </div>
    </div>
   
    
    <div class="row mb-3">
        <label for="inputAddress4" class="col-sm-3 col-form-label">Reason </label>
        <div class="col-sm-9">
            <p>: {{ @$leave_data->reason }}</p>
        </div>
    </div>

    <div class="row mb-3">
        <label for="inputAddress4" class="col-sm-3 col-form-label">Leave Dates </label>
        <div class="col-sm-9">
            <p>: {{ @$leave_data->from_date }} - {{ @$leave_data->to_date }}</p>
        </div>
    </div>

    @if (@$leave_data->attachment)
    <div class="row mb-3">
        <label for="inputAddress4" class="col-sm-3 col-form-label">Attachment </label>
        <div class="col-sm-9">
            <a class="badge bg-light text-dark" href="{{ @$leave_data->attachment }}" target="_blank">View Attachment</a>
        </div>
    </div>
        
        @endif

    <div class="row mb-3">
        <label for="inputAddress4" class="col-sm-3 col-form-label">Application Status</label>
        <div class="col-sm-9">
            @if (@$leave_data->application_status == -1)
            <span class="badge bg-rose">Rejected</span>

            @elseif (@$leave_data->application_status == 1)

            <span class="badge bg-success">Approved</span>
            @elseif (@$leave_data->application_status == 2)
            <span class="badge bg-warning">Pending</span>
            @endif
            
        </div>
    </div>
   
   
   
</div>