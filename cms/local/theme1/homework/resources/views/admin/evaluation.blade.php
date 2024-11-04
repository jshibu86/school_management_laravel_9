@extends('layout::admin.master')

@section('title','homework evaluation')
@section('style')
    <!-- Datatables -->
    @include('layout::admin.head.list_head')
    <style>
        .table-div table {
            width: 100% !important;
        }
        .modal-header{
            border-bottom: 1px solid #ffffff !important;
        }

        .eval{
            cursor: pointer;
        }
        .eval:hover{
            color: white;
        }
    </style>
@endsection
@section('body')
@if (Session::get("ACTIVE_GROUP") == "Student")
<div class="card">
    <div class="card-body">
        <div class="card-title btn_style">
            <h4 class="mb-0">Homeworks Summary</h4> 
        </div>
        <hr/>
        <div class="row">
            @foreach (@$homework_lists as $class_id=>$section )

            @foreach ($section as $section_id =>$subjects )

            @foreach ($subjects->subjects as $subject_id =>$value)
            <div class="col-xs-12 col-sm-4 col-md-3">
                <div class="card radius-15 mb-0 shadow-none border">
                  <div class="card-body text-center">
                    @php

                    $name=$colors[array_rand(@$colors)];
                        
                    @endphp
                    <div
                      class="widgets-icons mx-auto bg-{{ $name }} text-white"
                    >
                    <span> {{ cms\subject\Models\SubjectModel::subjectname($subject_id) }}</span>
                    
                    </div>
                    <h4 class="mb-0 font-weight-bold mt-3">{{ $value->homework }} / {{ $value->homeworksubmissions }}</h4>

                    <span class="badge bg-info view_homework" data-classid={{ $class_id }} data-sectionid={{ $section_id }} data-subject={{ $subject_id }}><i class="fa fa-eye" aria-hidden="true"></i> View</span>
                    
                  </div>
                </div>
            </div>
            @endforeach
                
            @endforeach
           
            @endforeach
           
            
           
        </div>
    </div>

    <!-- Modal -->
        
    
</div> 
@endif
{{ Form::open(array('role' => 'form', 'route'=>array('homeworkevaluations'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'homework-form','novalidate' => 'novalidate')) }}
<div class="modal fade" id="view__homeworks"  aria-hidden="true">
    

    <div class="modal-dialog modal-lg modal-dialog-centered">
       
        <div class="modal-content" >
            
            <div class="modal-body assigen_parent_body">

                <div class="homework_details">
                   some
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Submit</button>
               
            </div>
        </div>

    
    </div>

</div>
{{ Form::close() }}
@if (Session::get("ACTIVE_GROUP") != "Student")
<div class="card">
    <div class="card-body">
        <div class="card-title btn_style">
            <h4 class="mb-0">Submitted Homeworks</h4>
            <a class="btn btn-info" href="{{route('homework.index')}}" ><i class='fa fa-arrow-left'></i>&nbsp;&nbsp;Back</a>
          
        </div>
        <hr/>
       

     
        <div class="table-responsive">
            <table id="datatable-buttons1" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Reg No</th>
                        <th>Name</th>
                        <th>Title</th>
                        <th>Subject</th>
                        <th>Submitted Date</th>
                        
                        <th class="noExport">Evaluation</th>
                        <th >Remark</th>
                       
                    </tr>
                </thead>
                <tbody>

                </tbody>
            
            </table>
        </div>
       

       
    </div>
</div>
@endif


  

@endsection
@section('script')


<script>
  
var homework_id={!! @$data->id !!};
   
    
        window.statuschange='{{route('homework_action_from_admin')}}';
       $('document').ready(function(){

           var element = $("#datatable-buttons1");
           var url =  '{{route('get_homework_eval_data_from_admin')}}' + '/' + homework_id;
           
           var column = [
             
               {data: 'DT_RowIndex', name: 'DT_RowIndex',searchable: false,sortable: false},
               { data: 'reg_no', name: 'students.reg_no', width: '15%' },
               { data: 'sfirst_name', name: 'students.first_name', width: '15%' },
               { data: 'homeworktitle', name: 'homework.title' , className: 'textcenter' },
               { data: 'subject', name: 'subject.name' , className: 'textcenter' },
               { data: 'subdate', name: 'homework_submissions.submitted_date' , className: 'textcenter' },
                
                 { data: 'evaluation', name: 'id', searchable: false, sortable: false, className: 'textcenter'},
                 
                 { data: 'teacher_remark', name: 'id', searchable: false, sortable: false, className: 'textcenter'},
            
           ];
           var csrf = '{{ csrf_token() }}';

           var options  = {
               //order : [ [ 6, "desc" ] ],
               lengthMenu: [[15,25,50, 100 ,250, 500, -1], [15,25,50, 100 ,250, 500,"ALL"]],
               button : [
                   {
                       name : "Publish" ,
                       url : "{{route('homework_action_from_admin',1)}}"
                   },
                   {
                       name : "Un Publish",
                       url : "{{route('homework_action_from_admin',0)}}"
                   },
                   {
                       name : "Trash",
                       url : "{{route('homework_action_from_admin',-1)}}"
                   },
                   {
                       name : "Delete",
                       url : "{{route('homework.destroy',1)}}",
                       method : "DELETE"
                   }
               ],

           }


           dataTable(element,url,column,csrf,options);

       });
    
   
   </script>

   

@endsection
@section('scripts')

<script type="module">
    function notify_script(title,text,type,hide) {
            new PNotify({
                title: title,
                text: text,
                type: type,
                hide: hide,
                styling: 'bootstrap3'
            })
        }
    window.sectionurl="{{ route('section.index') }}";
    window.subjecturl="{{ route('subject.index') }}";
    window.viewevaluationurl="{{ route('homeworkevaluations') }}";

    $(".evaluation").on("click",function(){

        var class_id=$(this).attr("id");
       
        console.log(class_id,"from home");
        //AcademicConfig.Viewevaluation( );
       
    });

    @if (Session::get("ACTIVE_GROUP") == "Teacher")
    AcademicConfig.CommonClassSectionSubjects(notify_script,"homework");
    @else
    AcademicConfig.CommonClassSectionSubjects(notify_script);
    @endif
   

   
</script>
@endsection
