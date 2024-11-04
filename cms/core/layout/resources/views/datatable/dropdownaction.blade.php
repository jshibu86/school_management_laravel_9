<div class="btn-group">
    <button type="button" class="btn btn-outline-dark dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Select</button>
    <div class="dropdown-menu" style="">

        @if ($route == "leave")
            
        @if (Session::get("ACTIVE_GROUP") == "Super Admin")
        @if ($data->leavestatus !=1)
        <a class="dropdown-item" data-toggle="modal" data={{ $data->id }} href="{{ route("leave_action_from_admin",["id"=>$data->id,"action"=>"1"]) }}" title="status">Approve</a>
        @endif
       
        <a class="dropdown-item" data-toggle="modal" data={{ $data->id }} href="{{ route("leave_action_from_admin",["id"=>$data->id,"action"=>"-1"]) }}" title="status">Reject</a>
        <a class="dropdown-item" data-toggle="modal" data={{ $data->id }} href="{{ route("leave_action_from_admin",["id"=>$data->id,"action"=>"2"]) }}" title="status">Pending</a>
        @endif
        {{-- "AcademicConfig.Viewleave(this.id) --}}
        @if (Session::get("ACTIVE_GROUP") == "Super Admin")
        <a class="dropdown-item" href="{{ route($route.".show",$data->id) }}"  id={{ $data->id }}   title="view">View</a>

        @else
        <a class="dropdown-item" href="#"  onclick="AcademicConfig.Viewleave(this.id)" id={{ $data->id }}   title="view">View</a>
        @endif
        @endif

        @if ($route == "issuebook")

        @if (@$data->is_return !=1)
        <a class="dropdown-item"  id={{ $data->id }} href="{{ route($route.'.returnBook',$data->id) }}" title="return book" > Return</a>
        @endif

       
            
        @endif
        @if (CGate::allows("edit-".$route))
        <a class="dropdown-item" data-toggle="modal" data={{ $data->id }} href="{{ route($route.'.edit',$data->id) }}" title="edit">Edit</a>
        @endif
        
        @if (CGate::allows("delete-".$route) && CGate::allows("delete-shop"))
        <form method="post" action="{{ route($route.'.destroy',$data->id) }}">
           <!-- here the '1' is the id of the post which you want to delete -->
       
           {{ csrf_field() }}
           {{ method_field('DELETE') }}
          
           <button class="delete text-danger btn" type="submit">Delete</button>
    @endif
    </div>
</div>