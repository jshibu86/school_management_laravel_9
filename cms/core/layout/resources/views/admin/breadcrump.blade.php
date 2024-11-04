{{-- <div class="bread">
    <ol class="breadcrumb">
        <li><a href="{{ route("backenddashboard") }}">Home</a></li>
        
        <li class="active">{{ $route }}</li>
      </ol>
</div> --}}

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-md-flex align-items-center mb-3">
  {{-- <div class="breadcrumb-title pe-3">Components</div> --}}
  <div class="">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0 p-0">
        <li class="breadcrumb-item"><a href="{{ route("backenddashboard") }}"><i class='bx bx-home-alt'></i></a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">{{ ($route == "Virtual Comunication" )? "Virtual Communication" : $route  }}</li>
      </ol>
    </nav>
  </div>
 
</div>
<!--end breadcrumb-->