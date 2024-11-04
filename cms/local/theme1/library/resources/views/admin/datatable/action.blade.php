<span class="badge bg-success">{{ @$data->active_count }}-Active</span>
<span class="badge bg-danger">{{ @$data->inactive_count }}-Inactive</span>
<span class="badge bg-info">{{ @$data->damaged_count }}-Damaged</span>
<span class="badge bg-primary">{{ @$data->stolen_count }}-Stolen</span>
<span class="badge bg-warning">{{ @$data->lost_count }}-Lost</span>

<div class="btn-group">
    <button type="button" class="btn btn-outline-dark dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Select</button>
    <div class="dropdown-menu" style="">

        <a class="dropdown-item" data-toggle="modal" id={{ $data->id }} data-status="1" href="#" title="status" onclick="status(this)">Active</a>
        <a class="dropdown-item" data-toggle="modal" id={{ $data->id }} href="#" data-status="0" title="status" onclick="status(this)">InActive</a>
        <a class="dropdown-item" data-toggle="modal" id={{ $data->id }} href="#" data-status="2" title="status" onclick="status(this)">Damage</a>
        <a class="dropdown-item" data-toggle="modal" id={{ $data->id }} href="#" data-status="3" title="status" onclick="status(this)">Stolen</a>
        <a class="dropdown-item" data-status="4" data-toggle="modal" id={{ $data->id }} href="#" title="status" onclick="status(this)">Lost</a>
    </div>
</div>


<script>


window.bookstatus='{{route('library.index')}}'

function status(element){

    var id=$(element).attr("id");
    var status=$(element).data("status");
    console.log(id,status,"from action");
    AcademicConfig.ViewLibraryBookStatus(id,status)
}

</script>