@extends('layout::admin.master')

@section('title','dormitory')
@section('style')
    <!-- Datatables -->
    @include('layout::admin.head.list_head')
    <link rel="stylesheet" href="{{asset('assets/backend/css/attendance.css')}}">
   
    <style>
        .table-div table {
            width: 100% !important;
        }
    </style>
@endsection
@section('body')
{{ Form::open(array('role' => 'form', 'route'=>array('dormitorystudent.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left','class'=>'dormitoryastudentform', 'id' => 'transport-form','novalidate' => 'novalidate')) }}
<div class="card">
    <div class="card-body">
        <div class="card-title btn_style">
            <h4 class="mb-0">Assign Hostel Students</h4>
            {{-- @if(CGate::allows('create-dormitory'))
            <a class="btn btn-primary btn-sm m-1  px-3" href="{{route('dormitory.create')}}" ><i class='fa fa-plus'></i>&nbsp;&nbsp;Create</a>
            @endif --}}
          
        </div>
        <hr/>
        <div class="accordion" id="accordionExample">
            <div class="accordion-item">
                <h1 class="accordion-header" id="headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                   Get Students 
                    </button>
                 </h1>
                <div id="collapseOne" class=" atnaccodrdian accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample" style="">
                    <div class="accordion-body">
                        <div class="row">
                           
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                 <label class="control-label margin__bottom" for="status">Academic Year <span class="required">*</span>
                                      </label>
                                      <div class="feild">
                                       
                                          @if (@$layout == "edit")
                                          <input type="hidden" name="academic_year" value="{{ @$data->academic_year }}"/>
                                          <input type="hidden" name="class_id" value="{{ @$data->class_id }}"/>
                                          <input type="hidden" name="section_id" value="{{ @$data->section_id }}"/>
                                          <input type="hidden" name="term_id" value="{{ @$data->term_id }}"/>
                                              
                                          @endif
                                          {{ Form::select('academic_year',@$academicyears,@$data->academic_year ,
                                          array('id'=>'timetableacyear','class' => @$layout =="edit" ? " form-control" : 
                                          "single-select form-control",'required' => 'required','placeholder'=>"Select Academic year",@$layout =="edit"? "disabled" : "")) }}
                                      </div>
                                </div>
                                     
                              </div>
                              <div class="col-xs-12 col-sm-4 col-md-3">
                                  <div class="item form-group">
                                   <label class="control-label margin__bottom" for="status">Class <span class="required">*</span>
                                        </label>
                                        <div class="feild">
                                            {{ Form::select('class_id',@$class_lists,@$data->class_id ,
                                            array('id'=>'class_id','class' =>@$layout =="edit" ? ' form-control': 'single-select form-control' ,'required' => 'required','placeholder'=>"Select Class",@$layout =="edit"? "disabled" : "" )) }}
                                        </div>
                                  </div>
                                       
                              </div>
                              <div class="col-xs-12 col-sm-4 col-md-3">
                                  <div class="item form-group">
                                   <label class="control-label margin__bottom" for="status">Section <span class="required">*</span>
                                        </label>
                                        <div class="feild">
                                            {{ Form::select('section_id',@$sections,@$data->section_id ,
                                            array('id'=>'section_id','class' =>@$layout =="edit" ? ' form-control': 'single-select form-control','required' => 'required','placeholder'=>"Select Section",@$layout =="edit"? "disabled" : "" )) }}
                                        </div>
                                  </div>
                                       
                              </div>
                              <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                 <label class="control-label margin__bottom" for="status">Semester <span class="required">*</span>
                                      </label>
                                      <div class="feild">
                                          {{ Form::select('term_id',[],@$data->semester_id ,
                                          array('id'=>'semester_id','class' =>@$layout =="edit" ? ' form-control': 'single-select form-control','required' => 'required','placeholder'=>"Select Semester",@$layout =="edit"? "disabled" : "" )) }}
                                      </div>
                                </div>
                                     
                            </div>
                              <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                 <label class="control-label margin__bottom" for="status">Hostel <span class="required">*</span>
                                      </label>
                                      <div class="feild">
                                          {{ Form::select('dormitory_id',@$dormitory,@$data->dormitory_id ,
                                          array('id'=>'dormitory_id','class' =>@$layout =="edit" ? ' form-control': 'single-select form-control','required' => 'required','placeholder'=>"Select Dormitory",@$layout =="edit"? "disabled" : "" )) }}
                                      </div>
                                </div>
                                     
                            </div>

                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                 <label class="control-label margin__bottom" for="status">Rooms <span class="required">*</span>
                                      </label>
                                      <div class="feild">
                                          {{ Form::select('room_id',[],@$data->room_id ,
                                          array('id'=>'room_id','class' =>@$layout =="edit" ? ' form-control': 'single-select form-control','required' => 'required','placeholder'=>"Select Room",@$layout =="edit"? "disabled" : "" )) }}
                                      </div>
                                </div>
                                     
                            </div>

                            {{-- <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                       <label class="control-label margin__bottom" for="status">Available Bed <span class="required"></span>
                                       </label>
                                       <div class="feild">
                                           {{Form::text('available_bed',null,array('id'=>"available_bed",'class'=>"form-control col-md-7 col-xs-12" ,
                                          'placeholder'=>"e.g C01",'required'=>"required","readonly"))}}
                                       </div>
                                   </div>
                               </div> --}}

                            
                              
                              

                            <div class="col-md-3 ">
                                <button type="button" id="addatt" class="btn btn-primary  add_btn att_btn w-100 assigndormitorybtn" name="daily"> <i class="fa fa-plus" name="daily"></i>&nbsp;&nbsp;Get Students</button>
                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>
           
           
        </div>
        <div class="get_students_dormitory_assign">
           {{-- here --}}
        </div>
    </div>
</div>
{{ Form::close() }}


  

@endsection

@section("scripts")
<script type="module">
    function notify_script(title,text,type,hide) {
            new PNotify({
                title: title,
                text: text,
                type: type,
                hide: hide,
                styling: 'fontawesome'
            })
        }

  
     window.sectionurl='{{route('section.index')}}';
     window.transportstudent='{{ route('transportstudent.index') }}';
     window.getvehicle='{{ route('getstopvehicle') }}';
     window.termurl='{{ route('examterm.index') }}';
     window.getrooms='{{ route('dormitoryroom.index') }}';
     window.dormitorystudent='{{ route('dormitorystudent.index') }}'
   
    AttendanceConfig.AttendanceInit(notify_script);
    AcademicConfig.Dormitoryinit(notify_script);
    
    
</script>
@endsection
@section('script')
    <script>
     window.statuschange='{{route('dormitory_action_from_admin')}}';
        $('document').ready(function(){

            var element = $("#datatable-buttons1");
            var url =  '{{route('get_dormitory_data_from_admin')}}';
            var column = [
              
                {data: 'DT_RowIndex', name: 'DT_RowIndex',searchable: false,sortable: false},
                { data: 'name', name: 'name', width: '15%' },
                { data: 'desc', name: 'desc' , className: 'textcenter' },
                 { data: 'status', name: 'id', searchable: false, sortable: false, className: 'textcenter',render : function(data, type, row, meta)
                    {
                        if(row['id']!=1)
                        {
                            return `<label class="switch">
                        <input type="checkbox" id=${row['id']} ${row['status']=="Enabled" ? 'checked':''} class="toggle-class" onchange="myFunction(this.checked ? 1:0,this.id)">
                        <span class="slider round"></span>
                      </label>`;
                        }else{
                            return "";
                        }
                        
                    }
                  },
                { data: 'action', name: 'id', searchable: false, sortable: false, className: 'textcenter'}
            ];
            var csrf = '{{ csrf_token() }}';

            var options  = {
                //order : [ [ 6, "desc" ] ],
                lengthMenu: [[15,25,50, 100 ,250, 500, -1], [15,25,50, 100 ,250, 500,"ALL"]],
                button : [
                    {
                        name : "Publish" ,
                        url : "{{route('dormitory_action_from_admin',1)}}"
                    },
                    {
                        name : "Un Publish",
                        url : "{{route('dormitory_action_from_admin',0)}}"
                    },
                    {
                        name : "Trash",
                        url : "{{route('dormitory_action_from_admin',-1)}}"
                    },
                    {
                        name : "Delete",
                        url : "{{route('dormitory.destroy',1)}}",
                        method : "DELETE"
                    }
                ],

            }


            dataTable(element,url,column,csrf,options);

        });
    </script>

@endsection
