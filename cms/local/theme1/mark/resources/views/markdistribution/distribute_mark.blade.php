@extends('layout::admin.master')

@section('title','markdistribution')
@section('style')


@endsection

@section('body')

<div class="x_content">

    @if($layout == "create")
        {{ Form::open(array('role' => 'form', 'route'=>array('distribute_mark.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'markdistribution-form','novalidate' => 'novalidate')) }}
    @elseif($layout == "edit")
        {{ Form::open(array('role' => 'form', 'route'=>array('distribute_mark.update',$id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
    @endif
    <div class="box-header with-border mar-bottom20">
       
        {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_mark' , 'class' => 'btn btn-success btn-sm m-1  px-3 ')) }}

    
         <a class="btn btn-info btn-sm m-1  px-3" href="{{route('markdistribution.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

          {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }}

          

         
    </div>
    @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit Distribute Mark" : "Distribute Mark"])

    <div class="card">
        <div class="card-body">
            <p class="text-center mb-5">School Type: <span class="fw-bold">{{ $school_type }}</span></p> 
            <div class="col-xs-8 justify-self-center" id="append">
                @php
                $index = 0;
            @endphp
            
            @if($layout == 'create')
                {{ Form::hidden('school_id', $id, ['id' => 'school_id']) }}
                
                <div class="row">

                    <div class="col-xs-12 col-sm-4 col-md-3">
                        <div class="item form-group">
                            <label class="control-label margin__bottom" for="status">Mark distribution Name <span class="required">*</span></label>
                            <div class="feild">
                                {{ Form::text('distribution_name[]', 'Exam', [
                                    'id' => 'distribution_name_' . $index,
                                    'class' => 'distribution form-control col-md-7 col-xs-12',
                                    'required' => 'required'
                                ]) }}
                            </div>
                        </div>
                    </div>
            
                    <div class="col-xs-12 col-sm-4 col-md-3">
                        <div class="item form-group">
                            <label class="control-label margin__bottom" for="distribution_mark">Mark Value <span class="required">*</span></label>
                            <div class="feild">
                                {{ Form::text('distribution_mark[]', 70, [
                                    'id' => 'distribution_mark_' . $index,
                                    'class' => 'form-control col-md-7 col-xs-12',
                                    'required' => 'required'
                                ]) }}
                            </div>
                        </div>
                    </div>
            
                    <div class="col-xs-12 col-sm-4 col-md-3">
                        <div class="item form-group">
                            <label class="control-label margin__bottom" for="status">Status <span class="required">*</span></label>
                            <div class="feild">
                                <label class="switch">
                                    <input type="checkbox" id="status" class="toggle-class" name="status[]" checked>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div> <!--row-->
                <div class="row">

                    <div class="col-xs-12 col-sm-4 col-md-3">
                        <div class="item form-group">
                            <label class="control-label margin__bottom" for="status">Mark distribution Name <span class="required">*</span></label>
                            <div class="feild">
                                {{ Form::text('distribution_name[]', 'Attendance', [
                                    'id' => 'distribution_name_' . $index,
                                    'class' => 'distribution form-control col-md-7 col-xs-12',
                                    'required' => 'required'
                                ]) }}
                            </div>
                        </div>
                    </div>
            
                    <div class="col-xs-12 col-sm-4 col-md-3">
                        <div class="item form-group">
                            <label class="control-label margin__bottom" for="distribution_mark">Mark Value <span class="required">*</span></label>
                            <div class="feild">
                                {{ Form::text('distribution_mark[]', 10, [
                                    'id' => 'distribution_mark_' . $index,
                                    'class' => 'form-control col-md-7 col-xs-12',
                                    'required' => 'required'
                                ]) }}
                            </div>
                        </div>
                    </div>
            
                    <div class="col-xs-12 col-sm-4 col-md-3">
                        <div class="item form-group">
                            <label class="control-label margin__bottom" for="status">Status <span class="required">*</span></label>
                            <div class="feild">
                                <label class="switch">
                                    <input type="checkbox" id="status" class="toggle-class" name="status[]" checked>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div> <!--row-->
                @else
                @foreach ( $distribution as $data)
                <div class="row">
                    {{ Form::hidden('distribution_id[]',@$data->id, ['id' => 'distribution_id']) }}
                    <div class="col-xs-12 col-sm-4 col-md-3">
                     <div class="item form-group">
                            <label class="control-label margin__bottom" for="status">Mark distribution Name <span class="required">*</span>
                            </label>
                            <div class="feild">
                                {{Form::text('distribution_name[]',@$data->distribution_name,array('id'=>'distribution_name_'.$index,
                                'class'=>"distribution form-control col-md-7 col-xs-12" ,
                                'required' => 'required'))}}
                            </div>
                        </div>
                    </div>
    
                    <div class="col-xs-12 col-sm-4 col-md-3">
                     <div class="item form-group">
                            <label class="control-label margin__bottom" for="distribution_mark">Mark Value <span class="required">*</span>
                            </label>
                            <div class="feild">
                                {{ Form::text('distribution_mark[]', @$data->mark, [
                                    'id' => 'distribution_mark_'.$index,
                                    'class' => 'form-control col-md-7 col-xs-12',
                                    'required' => 'required'
                                ]) }}
                            </div>
                        </div>
                    </div>
    
                
                    <div class="col-xs-12 col-sm-4 col-md-3">
                     <div class="item form-group">
                            <label class="control-label margin__bottom" for="status">Status <span class="required">*</span>
                            </label>
                            <div class="feild">
                                <label class="switch">
                                 <input type="checkbox" {{ @$data->status == 1 ? "checked" : "" }} id="status" class="distribution_status toggle-class" name="status[]" value="1">
                                <span class="slider round"></span>
                            </label>
                            </div>
                        </div>
                    </div>
    
                  
                    </div><!--row--> 
                @endforeach
                @endif
               
            </div>
            <div class="row">              
                <div class="col-xs-12 col-sm-4 col-md-3">
                  <div class="item form-group">
                     <button type="button" class="btn btn-outline mt-4 add_mark_distribution form-control" 
                     style="border-color:#7F01BA !important; color:#7F01BA !important">+ Add Mark Distribution</button>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-3"></div>
               </div>  <!--row--> 
        </div>
    </div>





{{Form::close()}}
</div>

        </div>
    </div>        

@endsection

@section("scripts")
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
  window.addMarkDistribution = "{{ route('add_mark_distribution') }}"
  window.distribution_status = "{{ route('status_change') }}"
  ExamConfig.examinit(notify_script);
</script>
@endsection