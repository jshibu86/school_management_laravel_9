@extends('layout::admin.master')

@section('title', 'exam')
@section('style')
    <!-- Datatables -->
    @include('layout::admin.head.list_head')
    <style>
        .table-div table {
            width: 100% !important;
        }
    </style>
    <style>
        .actions {
            display: flex;
            align-items: center;
        }

        .actions button,
        .actions i,
        .actions a {
            font-size: 18px !important;
        }

        #datatable-buttons1_paginate {
            margin-top: 36px;
        }
    </style>
@endsection
@section('body')
    <div class="card">
        <div class="card-body">
            <div class="card-title btn_style">
                <h4 class="mb-0">Academic Term List</h4>
                @if (CGate::allows('create-exam'))
                    <a class="btn btn-primary btn-sm m-1  px-3" href="{{ route('examterm.create') }}"><i
                            class='fa fa-plus'></i>&nbsp;&nbsp;Create</a>
                   
                @endif

            </div>
            <hr />
            <div class="table-responsive">
                <table id="datatable-buttons1" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Title</th>
                            <th>Year</th>
                            <th>Term Name</th>
                            <th>From Date</th>
                            <th>To Date</th>
                            <th class="noExport">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (@$academic_terms as $term)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $academic_year->title }}</td>
                                <td>{{ $academic_year->year }}</td>
                                <td>{{ $term->exam_term_name }}</td>
                                <td>{{ $term->from_date }}</td>
                                <td>{{ $term->to_date }}</td>
                                <th>
                                    <div class="actions">
                                        {{-- <a class="editbutton btn btn-default" data-toggle="modal" data={{ $term->id }}
                                            href="{{ route('examterm' . '.edit', $term->id) }}" title="edit"><i
                                                class="fa fa-edit"></i></a> --}}

                                        <form method="post" action="{{ route("examterm" . '.destroy', $term->id) }}">
                                            <!-- here the '1' is the id of the post which you want to delete -->

                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}

                                            <button class="editbutton btn btn-default delete" type="submit"><i
                                                    class="fa fa-trash delete" title="delete"></i></button>
                                        </form>
                                    </div>
                                </th>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>




@endsection
@section('script')
    <script>
        window.statuschange = '{{ route('academicyear_action_from_admin') }}';
        $('document').ready(function() {

            $("#datatable-buttons1").DataTable();



        });
    </script>

@endsection
