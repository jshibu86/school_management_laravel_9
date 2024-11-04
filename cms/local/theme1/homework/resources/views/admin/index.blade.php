@extends('layout::admin.master')

@section('title','homework')
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
<div class="modal fade" id="view__homeworks"  aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
       
        <div class="modal-content" >
            {{-- <div class="modal-header">
              
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div> --}}
            <div class="modal-body assigen_parent_body">

                <div class="homework_details">
                   some
                </div>

               
               
                
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
               
            </div>
        </div>

    
    </div>
</div>

@if (Session::get("ACTIVE_GROUP") != "Student")
<div class="card">
    <div class="card-body">
        <div class="card-title btn_style">
            <h4 class="mb-0">View Homeworks</h4>
            @if(CGate::allows("create-homework"))
            <a class="btn btn-primary btn-sm m-1  px-3" href="{{route('homework.create')}}" ><i class='fa fa-plus'></i>&nbsp;&nbsp;Create Homework</a>
            @endif
          
        </div>
        <hr/>
        {{ Form::open(array('role' => 'form', 'route'=>array('homework.index'), 'method' => 'get', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'teacher-form','novalidate' => 'novalidate')) }}
        <input type="hidden" name="fillter" value="fillter"/>
        <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-3">
                <div class="item form-group">
                <label class="control-label margin__bottom" for="class_id">Class <span class="required">*</span>
                </label>
                <div class="feild">
                    {{ Form::select('class_id',@$class_list,@$class_id ,
                    array('id'=>'class_id','class' => 'col-md-7 col-xs-12 form-control single-select','placeholder'=>"Select Class",'required'=>"required")) }}
                </div>
            </div>
                
        </div>
        <div class="col-xs-12 col-sm-4 col-md-3">
            <div class="item form-group">
                <label class="control-label margin__bottom" for="section_id">Section <span class="required">*</span>
                </label>
                <div class="feild">
                    {{ Form::select('section_id',@$sections,@$section_id ,
                    array('id'=>'section_id','class' => 'col-md-7 col-xs-12 form-control single-select','required'=>"required","placeholder"=>"Select section")) }}
                </div>
            </div>
                
        </div>

        <div class="col-xs-12 col-sm-4 col-md-3">
            <div class="item form-group">
                <label class="control-label margin__bottom" for="subject_id">Subject <span class="required">*</span>
                </label>
                <div class="feild">
                    {{ Form::select('subject_id',@$subjects,@$subject_id ,
                    array('id'=>'subject_id','class' => 'col-md-7 col-xs-12 form-control single-select','placeholder'=>"Select Subject",'required'=>"required")) }}
                </div>
            </div>
                
        </div>
        <div class="col-xs-12 col-sm-4 col-md-3">
            <div class="item form-group">
                <label class="control-label margin__bottom" for="subject_id"><span class="required"></span>
                </label>
                <div class="feild" style="margin-top: 7px;">
                    <button class="btn btn-primary" type="submit">Get Homeworks</button>
                </div>
            </div>
            
        </div>
        </div>
        {{ Form::close() }}

        @if (@$homeworks)
        <div class="table-responsive" style="margin-top: 30px;">
            <table id="datatable-buttons1" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Title</th>
                        <th>Submission Date</th>
                        <th>Status</th>
                        <th>Evaluation</th>
                        <th class="noExport">Action</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            
            </table>
        </div>
        @endif

       
    </div>
</div>
@endif


  

@endsection
@section('script')


<script>
    var homework={!! @$homeworks !!};
    var class_id={!! @$class_id !!};
    var section_id={!! @$section_id !!};
    var subject_id={!! @$subject_id !!};

    console.log(homework,"from homework");
    if(homework)
    {
        window.statuschange='{{route('homework_action_from_admin')}}';
       $('document').ready(function(){

           var element = $("#datatable-buttons1");
           var url =  '{{route('get_homework_data_from_admin')}}' + '/' + class_id+ '/' + section_id+ '/' + subject_id;
           
           var column = [
             
               {data: 'DT_RowIndex', name: 'DT_RowIndex',searchable: false,sortable: false},
               { data: 'title', name: 'homework.title', width: '15%' },
               { data: 'submission', name: 'homework.submission_date' , className: 'textcenter' },
                { data: 'status', name: 'id', searchable: false, sortable: false, className: 'textcenter',render : function(data, type, row, meta)
                   {
                       
                           return `<label class="switch">
                       <input type="checkbox" id=${row['id']} ${row['status']=="Enabled" ? 'checked':''} class="toggle-class" onchange="myFunction(this.checked ? 1:0,this.id)">
                       <span class="slider round"></span>
                     </label>`;
                       
                       
                   }
                 },
                 { data: 'evaluation', name: 'id', searchable: false, sortable: false, className: 'textcenter'},
               { data: 'action', name: 'id', searchable: false, sortable: false, className: 'textcenter'}
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
    }
   
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
    window.viewhomeworkurl="{{ route('homework.index') }}";

    $(".view_homework").on("click",function(){

        var class_id=$(this).attr("data-classid");
        var section_id=$(this).attr("data-sectionid");
        var subject_id=$(this).attr("data-subject");
        console.log(class_id,"from home");
        AcademicConfig.Viewhomework( class_id,section_id,subject_id,notify_script);
       
    });

    @if (Session::get("ACTIVE_GROUP") == "Teacher")
    AcademicConfig.CommonClassSectionSubjects(notify_script,"homework");
    @else
    AcademicConfig.CommonClassSectionSubjects(notify_script);
    @endif
   

   
</script>
@endsection
