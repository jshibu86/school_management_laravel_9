@extends('layout::admin.master')

@section('title','leave')
@section('style')


@endsection
@section('body')

<div class="x_content">

    @if($layout == "create")
        {{ Form::open(array('role' => 'form', 'route'=>array('leave.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'leave-form','novalidate' => 'novalidate')) }}
    @elseif($layout == "edit")
        {{ Form::open(array('role' => 'form', 'route'=>array('leave.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
    @endif
    <div class="box-header with-border mar-bottom20">
       

        {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_leave' , 'class' => 'btn btn-success btn-sm m-1  px-3')) }}
        <a class="btn btn-info btn-sm m-1  px-3" href="{{route('leave.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>
        {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }}

        

        
    </div>
       @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit leave" : "Create leave"])

         <div class="card">
            <div class="card-body">
                <h5 class="card-title">Apply Leave</h5>
                <hr/>
                <div class="col-xs-12">
                    <div class="row">
                        @if (Session::get("ACTIVE_GROUP") == "Super Admin")
                            
                       
                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                             <label class="control-label margin__bottom" for="status">Select Staff Role <span class="required">*</span>
                                  </label>
                                  @if (@$layout =="create")
                                        <div class="feild">
                                            {{ Form::select('member_type',@$groups,@$data->group_id ,
                                            array('id'=>'member_type','class' => 'single-select form-control','required' => 'required',"placeholder"=>"select group" )) }}
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

                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                             <label class="control-label margin__bottom" for="status">Select User <span class="required">*</span>
                                  </label>
                                  <div class="feild">

                                    @if (@$layout=="edit")
                                    <input type="hidden" name="member_id" value="{{ @$data->user_id }}"/>
                                    @endif
                                   
                                   @php
                                       if($layout=="edit")
                                       {
                                        $dis=true;

                                       }else{
                                        $dis=false;
                                       }
                                   @endphp
                                      {{ Form::select('member_id',@$users,@$data->user_id ,
                                      array('id'=>'member_id','class' => 'single-select form-control','required' => 'required',"placeholder"=>"select user","disabled"=>$dis )) }}
                                  </div>
                            </div>
                                 
                        </div>

                        @endif
                    <div class="col-xs-12 col-sm-4 col-md-3">
                      <div class="item form-group">
                       <label class="control-label margin__bottom" for="status">Leave Type <span class="required">*</span>
                            </label>
                            <div class="feild">
                                {{ Form::select('leave_type_id',@$type,@$data->leave_type_id,
                                array('id'=>'status','class' => 'single-select form-control','required' => 'required',"placeholder"=>"select type" )) }}
                            </div>
                      </div>
                           
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-3">
                     <div class="item form-group">
                            <label class="control-label margin__bottom" for="status">From Date <span class="required">*</span>
                            </label>
                            <div class="feild">
                                {{Form::text('from_date',@$data->from_date,array('id'=>"from_date",'class'=>"form-control col-md-7 col-xs-12 from_datepicker" ,
                               'required'=>"required","placeholder"=>"Select Date"))}}
                            </div>
                        </div>
                    </div>


                    <div class="col-xs-12 col-sm-4 col-md-3">
                        <div class="item form-group">
                               <label class="control-label margin__bottom" for="status">To Date <span class="required">*</span>
                               </label>
                               <div class="feild">
                                   {{Form::text('to_date',@$data->to_date,array('id'=>"to_date",'class'=>"form-control col-md-7 col-xs-12 to_datepicker" ,
                                  'required'=>"required","placeholder"=>"Select Date"))}}
                               </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-3">
                        <div class="item form-group">
                               <label class="control-label margin__bottom" for="status">No Days <span class="required">*</span>
                               </label>
                               <div class="feild">
                                   {{Form::text('no_days',@$data->no_days,array('id'=>"no_days",'class'=>"form-control col-md-7 col-xs-12" ,
                                 "placeholder"=>"Days","readonly"))}}
                               </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-3">
                        <div class="item form-group">
                               
                               <div class="feild">
                                <div class="mb-3">
                                    <label for="formFile" class="form-label">Attachment</label>
                                    <input class="form-control" type="file" id="formFile" name="attachment" accept=".pdf,.word">
                                </div>
                               </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-5">
                        <div class="item form-group">
                               
                               <div class="feild">
                                <div class="mb-3">
                                    <label for="inputAddress2" class="form-label">Reason in Detail</label>
                                    <textarea class="form-control" id="inputAddress2" placeholder="Reason..." rows="3" name="reason">{{ @$data->reason }}</textarea>
                                </div>
                               </div>
                        </div>
                    </div>



                   



                    </div>
                    
                </div>
            </div>
        </div>

    
   
   

    {{Form::close()}}
</div>


   

@endsection

@section('script_link')

    <!-- validator -->
    {!! Cms::script("theme/vendors/validator/validator.js") !!}
    {!! Cms::script("theme/vendors/validator/validator_form.js") !!}

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
    AcademicConfig.Leaveinit(notify_script);

</script>
@endsection
