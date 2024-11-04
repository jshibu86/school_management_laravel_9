@extends('layout::admin.master')

@section('title','subject mapping')
@section('style')
<style>
    .tab__section{
        background-color: white;
    padding: 15px;
    box-shadow: rgb(0 0 0 / 10%) 0px 1px 3px 0px, rgb(0 0 0 / 6%) 0px 1px 2px 0px;
   
    border-radius: 7px;
    }
    .tabs-left>li.active>a {
    color: #fff !important;
    cursor: default;
    background-color: #2a3f54 !important;
    border: 1px solid #ddd;
    border-bottom-color: transparent;
}
.heading__tab{
    text-align: center;
    margin-bottom: 20px;
}
.heading__tab h4{
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 0px !important;
}
.small__header{
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
}
.action_btn{
    margin-top: 29px;
}
</style>

@endsection
@section('body')
    <div class="x_content">

        @if($layout == "create" || $layout =="subject")
            {{ Form::open(array('role' => 'form', 'route'=>array('subjectteacherMapping'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left ', 'id' => 'subjectmapping-form','novalidate' => 'novalidate')) }}
       
        @endif
        <div class="box-header with-border mar-bottom20">
            
            <a class="btn btn-info btn-sm m-1  px-3" href="{{route('subject.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

            {{-- {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }} --}}

            
            @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit Subject Teacher Assign" : "Create Subject Teacher Assign"])
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Subject Teacher Assign</h5>
                <hr/>
                <div class="col-xs-12">
                    <div class="col-sm-6">
                      <div class="card_">
                        <div class="card-body">
                          
                          <p class="card-text text-danger panel_alert"><i class="fa fa-hand-o-right text-danger" aria-hidden="true"></i> Before Mapping Subject with teacher Initially </p>
                          <p class="card-text text-danger panel_alert"><i class="fa fa-hand-o-right text-danger" aria-hidden="true"></i>  Create a <a href="{{ route("lclass.create") }}">Class</a> and <a href="{{ route("section.create") }}">Section</a></p>
                          <p class="card-text text-danger panel_alert"><i class="fa fa-hand-o-right text-danger" aria-hidden="true"></i> Create atleast one <a href="{{ route("subject.create") }}">Subject</a> with Class</p>
                          {{-- <a href="{{ route("section.create") }}" class="btn btn-primary">Create Section</a>
                          <a href="{{ route("subject.create") }}" class="btn btn-primary">Create Subject</a> --}}
                        </div>
                      </div>
                    </div>
                   {{-- //next column  --}}
                </div>
                 
            <div class="col-xs-12">
                <div class="row">
                    <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                                <label class="control-label margin__bottom" for="school_name"> Academic Year <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    @php
                                        if(isset($academic_year)){
                                            $year = $academic_year;
                                        }
                                        else{
                                            if(isset($data)){
                                                $academic =$data->academic_year;
                                            }
                                            else{
                                                $academic = '';
                                            }
                                            $year = $academic;
                                            
                                        }
                                    @endphp
                                {{ Form::select('academic_year',@$academic_years,@$year ,
                                array('id'=>'academic_year','class' => 'form-control single-select' )) }}
                                </div>
                            </div>
                        </div>
                    <div class="col-xs-12 col-sm-4 col-md-3">
                        <div class="item form-group">
                            <label class="control-label margin__bottom" for="school_name">Class <span class="required">*</span>
                            </label>
                            <div class="feild">
                            {{ Form::select('class_id',@$class_list,@$class_id ,
                            array('id'=>'class_id','class' => 'form-control single-select','required'=>"required","placeholder"=>"Select Class" )) }}
                            </div>
                        </div>
                    </div>
                   
                     <div class="col-xs-12 col-sm-4 col-md-3">
                        <div class="item form-group">
                            <label class="control-label margin__bottom" for="school_name">Section <span class="required">*</span>
                            </label>
                            <div class="feild">
                            {{ Form::select('section_id',@$sections,@$section_id ,
                            array('id'=>'section_id','class' => 'form-control single-select','required'=>"required","placeholder"=>"Select Section" )) }}
                            </div>
                        </div>
                    </div>
        
                     <div class="col-xs-12 col-sm-4 col-md-3">
                        <div class="action_btn">
                            @if (@$layout == "subject")
                            <button class="btn btn-primary getsubject" type="submit"><i class="fa fa-book"></i> Get Subjects </button>
                            @else
                            <button class="btn btn-primary getsubject" disabled type="submit"><i class="fa fa-book"></i> Get Subjects </button>
                            @endif
                            
                        </div>
                        
                    </div>
                   
                    
                    {{-- <div class="col-xs-12 col-sm-4 col-md-3">
                        <div class="item form-group">
                            <label class="control-label margin__bottom" for="school_name">Subject <span class="required">*</span>
                            </label>
                            <div class="feild">
                            {{ Form::select('subject_id',[],@$data->subject_id ,
                            array('id'=>'subject_id','class' => 'form-control','required'=>"required","placeholder"=>"Select Subject " )) }}
                            </div>
                        </div>
                    </div> --}}
                    {{-- <div class="col-xs-12 col-sm-4 col-md-3">
                        <div class="item form-group">
                            <label class="control-label margin__bottom" for="school_name">Teacher <span class="required">*</span>
                            </label>
                            <div class="feild">
                            {{ Form::select('teacher_id',[],@$data->teacher_id ,
                            array('id'=>'teacher_id','class' => 'form-control','required'=>"required","placeholder"=>"Select Teacher " )) }}
                            </div>
                        </div>
                    </div> --}}
                       
                       
                        
                       
                </div>
                {{Form::close()}}
                   
                    <!-- //status -->
                </div>
               
            </div>
            </div>
        </div>
      
  @if (@$class_subjects)
 

        @if (count(@$class_subjects) > 0)
        {{ Form::open(array('role' => 'form', 'route'=>array('storesubjectteacherMapping'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left ', 'id' => 'subjectmapping-form','novalidate' => 'novalidate')) }}
        <input type="hidden" name="class_id" value="{{ @$class_id }}"/>
        <input type="hidden" name="section_id" value="{{ @$section_id }}"/>
        <input type="hidden" name="academic_year" value="{{ @$academic_year }}"/>

        
        <div class="card">
            <div class="card-body">
                <div class="card-title btn_style">
                    <h4 class="mb-0">Subject Mapping</h4>
                    {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Assign Teacher', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_subject' , 'class' => 'mybuttn btn btn-primary pull-right')) }}
                  
                </div>
               
             
                <hr/>
                <div class="row">
                    <div class="col-3">
                      <div
                        class="nav flex-column nav-pills"
                        id="v-pills-tab"
                        role="tablist"
                        aria-orientation="vertical"
                      >
                            @foreach($class_subjects as $subject)
                            <a
                            class="nav-link {{($loop->iteration == 1) ? 'active' : ''}}"
                            id="v-pills-{{str_replace(' ','',$subject->name)}}-tab"
                            data-bs-toggle="pill"
                            href="#subject-{{str_replace(' ','',$subject->name)}}"
                            role="tab"
                            aria-controls="v-pills-{{str_replace(' ','',$subject->name)}}"
                            aria-selected="{{($loop->iteration == 1) ? 'true' : 'false'}}"
                            
                            >{{$subject->name}}</a
                            >
        
                            @endforeach
                        
                        
                       
                        
                      </div>
                    </div>
                    <div class="col-9">
                      <div class="tab-content" id="v-pills-tabContent">
                        @foreach($class_subjects as $subject)
                        <div
                          class="tab-pane fade  {{($loop->iteration == 1) ? 'show active' : ''}}"
                          id="subject-{{str_replace(' ','',$subject->name)}}"
                          role="tabpanel"
                          aria-labelledby="v-pills-{{str_replace(' ','',$subject->name)}}-tab"
                        >
                            <div class="row">
                                @foreach($teachers as $key => $values)
                                        <div class="col-xs-12 col-sm-6 col-md-3">
                                            <fieldset>
                                                    <input type="hidden" id="role-hidden-{{$subject->name.'-'.$values->id}}" name="map[{{$subject->id}}][{{$values->id}}]" value="0" />

                                                    <div class="form-check">
                                                        {!! Form::checkbox('map['.$subject->id.']['.$values->id.']', '1', (@$data[$subject->id][$values->id]==$values->id ) ? true : false, array('id'=>'map-'.$subject->name.'-'.$values->id,'class'=>"form-check-input")) !!}

                                                        
                                                        <label for="role-{{$subject->name.'-'.$values->id}}" class="form-check-label" for="flexCheckDefault">{{$values->teacher_name}}</label>
                                                        <br />
                                                        <small>{{cms\teacher\Models\DesignationModel::designationtype($values->designation_id)  }}</small>
                                                    </div>


                                                    
                                                   
                                                   
                                                  
                                            </fieldset>
                                        </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                       
                       
                      </div>
                    </div>
                </div>
            </div>
        </div>

        


    
        @else
        <div class="col-xs-12">
            <div class="row">
                <div class="heading__tab"><h5>This Class has No Subjects Found</h5></div>
                
            </div>
        </div>
        @endif
    
        @endif    
        
       
       
        {{Form::close()}}
</div>

        
    

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
    window.teacherurl="{{ route('teacher.index') }}";
    window.subjecturl="{{ route('subject.index') }}";
    window.checkassign="{{ route('classteacher.index') }}";

    AcademicConfig.Subjectmapping(notify_script)
</script>
@endsection

@section('script_link')

    <!-- validator -->
    {!! Cms::script("theme/vendors/validator/validator.js") !!}

@endsection
