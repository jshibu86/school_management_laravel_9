@extends('layout::admin.master')

@section('title','exam type')
@section('style')
    <!-- Datatables -->
    @include('layout::admin.head.list_head')
    <style>
        .table-div table {
            width: 100% !important;
        }
    </style>
@endsection
@section('body')
<div class="card">
    <div class="card-body">
        <div class="card-title btn_style">
            <h4 class="mb-0">View Homework</h4>
            {{-- @if(CGate::allows('create-exam'))
            <a class="btn btn-primary btn-sm m-1  px-3" href="{{route('exam.create')}}" ><i class='fa fa-plus'></i>&nbsp;&nbsp;Create</a>
            @endif --}}
          
        </div>
        <hr/>
        <div class="table-responsive">
            <table id="datatable-buttons1" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Academic Year</th>
                        <th>Exam Type</th>
                        <th>Class/Section</th>
                        <th>Subject</th>
                        <th>Home Work Date/Time</th>
                        <th>Submission Date/Time</th>
                        @if (Session::get("ACTIVE_GROUP") == "Super Admin")
                        <th class="noExport">Action</th>
                        @endif
                        @if (Session::get("ACTIVE_GROUP") == "Student")
                        <th class="noExport">Submit Homework</th>
                        @endif
                    </tr>
                </thead>
                <tbody>

                </tbody>
            
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="view_student" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered form">

        <div class="modal-content">

            <div class="modal-body assigen_parent_body">

                    <div class="student_details position-relative">
                        some  
                        </div>

                    </div>
                    <div class="modal-footer position-absolute top-0 end-0">
                        @if (Session::get('ACTIVE_GROUP') == 'Super Admin')
                            {{-- <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Submit</button> --}}
                        @endif
                        {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> --}}
                        <i class="fa fa-times-circle fs-2" style="color: red" data-bs-dismiss="modal"
                            aria-hidden="true"></i>


                    </div>
            </div>




        </div>
    </div>
</div>
<style>
    @media print {
    .action-column {
        display: flex;
        align-items: center;
    }

    .action-column button,
    .action-column i,
    .action-column a {
        font-size: 18px !important;
    }
}
</style>
@endsection
@section('script')
<script>
    window.statuschange='{{route('exam_action_from_admin')}}';
       $('document').ready(function(){

           var element = $("#datatable-buttons4");
           var url =  '{{route('get_admissionexam_data_from_admin')}}';
           var column = [
             
               {data: 'DT_RowIndex', name: 'DT_RowIndex',searchable: false,sortable: false},
               { data: 'acyear', name: 'acyear', width: '15%' },
               { data: 'exam_type_column', name: 'exam_type_column' , className: 'textcenter' },
               { data: 'class_section', name: 'class_section' , className: 'textcenter' },
               { data: 'subject', name: 'subject' , className: 'textcenter' },
               { data: 'examdatetime', name: 'examdatetime' , className: 'textcenter' }, 
               { data:'examsubmissiondatetime', name:'examsubmissiondatetime', className:'textcenter' },            
               @if (Session::get("ACTIVE_GROUP") == "Super Admin")            
               { data: 'duplicateexam', name: 'id', searchable: false, sortable: false, className: 'textcenter action-column'}
               @endif
               @if (Session::get("ACTIVE_GROUP") == "Student")
               { data: 'action', name: 'id', searchable: false, sortable: false, className: 'textcenter action-column'}
               @endif
                      
           ];
           var csrf = '{{ csrf_token() }}';

           var options  = {
               //order : [ [ 6, "desc" ] ],
               lengthMenu: [[15,25,50, 100 ,250, 500, -1], [15,25,50, 100 ,250, 500,"ALL"]],
               button : [
                   {
                       name : "Publish" ,
                       url : "{{route('exam_action_from_admin',1)}}"
                   },
                   {
                       name : "Un Publish",
                       url : "{{route('exam_action_from_admin',0)}}"
                   },
                   {
                       name : "Trash",
                       url : "{{route('exam_action_from_admin',-1)}}"
                   },
                   {
                       name : "Delete",
                       url : "{{route('exam.destroy',1)}}",
                       method : "DELETE"
                   }
               ],

           }
           dataTable(element,url,column,csrf,options);

       });
</script>
<script type="module">
    function notify_script(title, text, type, hide) {
        new PNotify({
            title: title,
            text: text,
            type: type,
            hide: hide,
            styling: 'fontawesome'
        })
    }


    // window.sectionurl = '{{ route('section.index') }}';
    // window.classurl = '{{ route('schooltype.index') }}';
    // window.getstudentperformanceinfo = "{{ route('studentperformance') }}";
    // window.fees_paid_report = "{{route('fees_payment')}}";
    // window.fees_reminder = "{{route('fees_reminder')}}"
   
    // AttendanceConfig.AttendanceInit(notify_script);
    // AcademicConfig.Leaveinit(notify_script);
    // //grade -- Class,Section List
    // PromotionConfig.PromotionInit(notify_script);
    // // ReportConfig.ReportInit(notify_script);
    // FeeStructureConfig.FeeStructureInit(notify_script);
    // //grade chart
    // Account.AccountInit();
   

</script>

@endsection
