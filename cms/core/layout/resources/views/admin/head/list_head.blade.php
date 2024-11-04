<!-- Datatables -->



{!!Cms::style("theme/vendors/plugins/datatable/css/dataTables.bootstrap4.min.css" ) !!}
{!!Cms::style("theme/vendors/plugins/datatable/css/buttons.bootstrap4.min.css" ) !!}

{!!Cms::style("theme/vendors/plugins/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" ) !!}




@section('script_link')


{!! Cms::script("theme/vendors/plugins/datatable/js/jquery.dataTables.min.js") !!}



   

    <!--cms-dataTable-->
    {!! Cms::script("js/cms-datatable.js") !!}
@endsection