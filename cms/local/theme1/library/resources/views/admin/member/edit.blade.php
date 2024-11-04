@extends('layout::admin.master')

@section('title','Member')
@section('style')
<style>


.hclass{
    display: none;
}
</style>
@endsection
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('member.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'member-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('member.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
        <div class="box-header with-border mar-bottom20">
           

               {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_library' , 'class' => 'btn btn-success btn-sm m-1  px-3')) }}

               @if (@$layout == "create")

               {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save-Continue', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat_continue' , 'value' => 'Edit_wallet' , 'class' => 'btn btn-dark btn-sm m-1  px-3')) }}
   
               @endif


            <a class="btn btn-info btn-sm m-1  px-3" href="{{route('member.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>
            {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }}

           

        
        </div>
           @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit Member" : "Create Member"])

             <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{$layout == "edit" ?"Edit Member" : "Create Member"}}</h5>
                    <hr/>
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-12 col-sm-4 col-md-3" style="margin-bottom:20px;">
                                <div class="item form-group">
                                        <label class="control-label margin__bottom" for="status">Member Type <span class="required">*</span>
                                        </label>

                                        @if (@$layout =="create")
                                        <div class="feild">
                                            {{ Form::select('member_type',@$groups,@$data->group_id ,
                                            array('id'=>'member_type','class' => 'single-select form-control','required' => 'required',"placeholder"=>"select Membertype" )) }}
                                        </div>

                                        @else
                                        <div class="feild">
                                            <input type="hidden" name="member_type" value="{{ @$data->group_id }}"/>
                                            {{Form::text('quantity',$group,array('id'=>"quantity",'class'=>"form-control col-md-7 col-xs-12" ,
                                            'placeholder'=>"author name","readonly"))}}
                                        </div>
                                        @endif
                                       
                                </div>
                                    
                            </div>

                    
                         </div> 


                         {{-- for students --}}

                            @php
                                if(@$layout == "edit" && @$data->member_type=="student")
                                {
                                    $sclass="sclass";
                                }else{
                                    $sclass="hclass";
                                }
                            @endphp

                            @php
                            if(@$layout == "edit" && @$data->member_type!="student")
                            {
                            $sclass="sclass";
                            }else{
                            $sclass="hclass";
                            }
                            @endphp



                        <div class="students_info row " style="{{ @$layout == "edit" && @$data->member_type=="student" ? "":"display:none" }}">
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                 <label class="control-label margin__bottom" for="status">Class <span class="required">*</span>
                                      </label>
                                      <div class="feild">
                                          {{ Form::select('class_id',@$class_list,@$data->class_id ,
                                          array('id'=>'class_id','class' => 'single-select form-control','required' => 'required',"placeholder"=>"select class" )) }}
                                      </div>
                                </div>
                                     
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                 <label class="control-label margin__bottom" for="status">Section <span class="required">*</span>
                                      </label>
                                      <div class="feild">
                                          {{ Form::select('section_id',@$sections,@$data->section_id ,
                                          array('id'=>'section_id','class' => 'single-select form-control','required' => 'required',"placeholder"=>"select section" )) }}
                                      </div>
                                </div>
                                     
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                 <label class="control-label margin__bottom" for="status">Student <span class="required">*</span>
                                      </label>
                                      <div class="feild">
                                          {{ Form::select('student_id',@$students,@$data->student_id ,
                                          array('id'=>'student_id','class' => 'single-select form-control','required' => 'required',"placeholder"=>"select student" )) }}
                                      </div>
                                </div>
                                     
                            </div>
                        </div>

                        

                        {{-- end students --}}

                        {{-- for others --}}
                        <div class="others_info row " style="{{ @$layout == "edit" && @$data->member_type!="student" ? "":"display:none" }}">
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                 <label class="control-label margin__bottom" for="status">Select User <span class="required">*</span>
                                      </label>
                                      <div class="feild">
                                          {{ Form::select('member_id',@$users,@$data->member_id ,
                                          array('id'=>'member_id','class' => 'single-select form-control','required' => 'required',"placeholder"=>"select user" )) }}
                                      </div>
                                </div>
                                     
                            </div>
                        </div>

                        {{-- end for others --}}

                    </div>
                    </div>
                </div>
            </div>

        
       
       

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
                styling: 'fontawesome'
            })
        }

    window.sectionurl="{{ route('section.index') }}";
    window.usersurl="{{ route('user.index') }}";
    window.studentsurl="{{ route('students.index') }}";
    AcademicConfig.Libraryinit(notify_script);

</script>
@endsection

@section('script_link')

    <!-- validator -->
    {!! Cms::script("theme/vendors/validator/validator.js") !!}
    {!! Cms::script("theme/vendors/validator/validator_form.js") !!}

@endsection
