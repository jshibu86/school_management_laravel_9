@extends('layout::admin.master')

@section('title','payrool')
@section('style')

@include('layout::admin.head.list_head')
<link rel="stylesheet" href="{{asset('assets/backend/css/attendance.css')}}">
@endsection
@section('body')
    <div class="x_content">
        @if (@$type !="payment")
        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('payroll.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'payrool-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('payroll.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif

        @else
        {{ Form::open(array('role' => 'form', 'route'=>array('PayrollMakePayment'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'payrool-form','novalidate' => 'novalidate')) }}

        @endif
      
        {{-- <div class="box-header with-border mar-bottom20">
           
             <a class="btn btn-info btn-sm m-1  px-3" href="#" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

              {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }}

              

             
        </div> --}}
        

        @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit Salery " : "Staff Attendance"])
            
      
           

             <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Staff Attendance</h5>
                    <hr/>
                    <div class="col-xs-12">
                    <div class="row">
                       
                        <div class="col-xs-12 col-sm-4 col-md-3">
                          <div class="item form-group">
                           <label class="control-label margin__bottom" for="status">User Group <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{ Form::select('group',@$user_group,@$data->group_id ,
                                    array('id'=>"group",'class' => 'user_group_staff single-select form-control','required' => 'required','placeholder' => 'Select User group' )) }}
                                </div>
                          </div>
                               
                        </div>
                         <div class="col-xs-12 col-sm-4 col-md-3">
                          <div class="item form-group">
                                <label class="control-label margin__bottom" for="status">Academic Year <span class="required">*</span>
                                </label>
                                <div class="feild">
                                   
                                    {{ Form::select('academic_year',@$academic_data['academicyears'],@$data->academic_year ?@$data->academic_year :@$academic_data['current_academic_year']   ,
                                    array('id'=>"academic_year",'class' => ' single-select form-control','required' => 'required','placeholder' => 'Select User group' ,'disabled')) }}
                                </div>
                          </div>
                               
                        </div>

                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                                <label class="control-label margin__bottom" for="status">Current Date <span class="required">*</span></label>
                                <div class="feild">
                                    <input id="nodays" class="form-control col-md-7 col-xs-12 " placeholder="date" required="" readonly="" disabled name="attendance_date" value="{{@$date}}" type="text" ></div>
                                </div>
                            </div>
                        </div>
                        

                        
                    </div>
                </div>

                   


                    @if (@$layout=="edit")


                    {{-- userlists --}}
                    <div class="container_ staffTemplateUserdata">

                        @include("payrool::admin.includes.salerytemplateuser",["data" => $data, "users_data" => $user_data,'layout'=>@$layout])
        
                    </div>

                    @else

                    {{-- userlists --}}
                    <div class="container_ staffTemplateUserdata">
                         
        
                    </div>
                        
                    @endif

                    
                </div>
            </div>

        
       
       

        {{Form::close()}}
    </div>

@endsection


@section("scripts")
<script type="module">
    window.staffattendanceurl='{{route('staff.attendance')}}'
    FeeConfig.StaffAttendance();
</script>
@endsection

@section('script_link')

    <!-- validator -->
    {!! Cms::script("theme/vendors/validator/validator.js") !!}
    {!! Cms::script("theme/vendors/validator/validator_form.js") !!}

@endsection
