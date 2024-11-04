@extends('layout::admin.master')

@section('title', 'subscriptionmanagement-edit')
@section('style')
    @include('layout::admin.head.list_head')
    <link rel="stylesheet" href="{{ asset('assets/backend/css/profile.css') }}">
@endsection

@section('body')
    <div class="container-fluid">
        <div class="row">
            <div class="card-title btn_style">
                <h4 class="mb-0">Subscription Management - Modules</h4>
            </div>
        </div>
        @if (Session::get('ACTIVE_GROUP') == 'Super Admin')
            <div class="card radius-15">
                <div class="card-body">

                    <div class="box-header mar-bottom20 d-none">
                        <a href="{{ route('module.create') }}" class="btn btn-primary btn-sm m-1 px-3">Create
                            Module&nbsp;&nbsp;&nbsp;<i class="fa fa-plus-square"></i></a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="table-responsive">
                                <table id="example" class="table">
                                    <thead>
                                        <tr>
                                            <th>Sl.No</th>
                                            <th>Module Name</th>
                                            <th class="noExport">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
        @endif

    </div>

@endsection

@section('script')

    <!-- To fetch MODULE details from database -->
    <script>
        //        window.statuschange='{{ route('get_module_list_data') }}';

        $('document').ready(function() {

            var element = $("#example");
            var url = '{{ route('get_module_list_data') }}';
            var column = [{
                    data: 'rownum',
                    name: 'rownum',
                    searchable: false,
                    sortable: false
                },
                {
                    data: 'module_name',
                    name: 'name',
                    width: '20%'
                },
                {
                    data: 'action',
                    name: 'id',
                    searchable: false,
                    sortable: false,
                    className: 'textcenter'
                }
            ];
            var csrf = '{{ csrf_token() }}';

            var options = {
                //order : [ [ 6, "desc" ] ],
                //lengthMenu: [[100, 250, 500], [100, 250, 500]]
                button: [

                    {
                        name: "Trash",
                        url: "{{ route('user_action_from_admin', -1) }}"
                    },
                    {
                        name: "Delete",
                        url: "{{ route('user.destroy', 1) }}",
                        method: "DELETE"
                    }
                ],

            }

            dataTable(element, url, column, csrf, options);

        });
    </script>
    <!-- validator -->
    {!! Cms::script('theme/vendors/validator/validator.js') !!}
    <script>
        $('#rowAdder').click(function() {
            var html = $("#addMoreRow").first().clone();
            $("#newRow").last().after(html);
        });
        $("body").on("click", "#rowSubtracter", function() {
            $(this).parents("#addMoreRow").remove();
        });

        $('#rowPayAdder').click(function() {
            var html = $("#addPayMoreRow").first().clone();
            $("#newPayRow").last().after(html);
        });
        $("body").on("click", "#rowPaySubtracter", function() {
            $(this).parents("#addPayMoreRow").remove();
        });
    </script>
@endsection
