@extends('layout::admin.master')

@section('title','classtimetable')
<style>
    .accordion-button:focus{
    border-color: #ffffff;
     box-shadow:none !important;
   }
   
    .accordion-button:not(.collapsed){
        background-color: #ffff !important;
    }
</style>
@section('style')


@endsection
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('classtimetableperiods'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'classtimetable-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('classtimetable.update',$period_id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "clone")
        {{ Form::open(array('role' => 'form', 'route'=>array('classtimetableperiods'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}     
        @endif
        <div class="box-header with-border mar-bottom20">
           
            {{-- {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_classtimetable' , 'class' => 'btn btn-success btn-sm m-1  px-3 ')) }} --}}

            {{-- @if (@$layout == "create")  

            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save-Continue', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat_continue' , 'value' => 'Edit_wallet' , 'class' => 'btn btn-dark btn-sm m-1  px-3')) }}

            @endif --}}
           

           

             <a class="btn btn-info btn-sm m-1  px-3" href="{{route('classtimetable.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

              {{-- {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }} --}}

              

             
        </div>
           @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit Class Timetable" : "Create Class Timetable"])

        
           <div class="card">
            @if ($layout == 'create')
                <div class="card-body">
                    <input type="hidden" name="type" value="create">
                    <div class="card-title btn_style">
                        <h4 class="mb-0">Class Time Table</h4>
    
                    </div>
                    <hr />
    
                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button accordion1" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    <span style="font-size: 1.5rem;">Class Details</span>
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse timetableaccordian collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                <div class="accordion-body">	
                                    <div class="col-xs-12">
                            
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
                                                {{ Form::select('academic_year',@$academicyears,@$data->academic_year ? @$data->academic_year :@$info['current_academic_year'] ,
                                                array('id'=>'timetableacyear','class' => @$layout =="edit" ? " form-control" : 
                                                "single-select form-control termacademicyear",'required' => 'required','placeholder'=>"Select Academic year",@$layout =="edit"? "disabled" : "")) }}
                                            </div>
                                      </div>
                                           
                                    </div>
    
                                    {{-- <div class="col-xs-12 col-sm-4 col-md-3">
                                          <div class="item form-group">
                                           <label class="control-label margin__bottom" for="status">Academic Term <span class="required">*</span>
                                                </label>
                                                <div class="feild">
                                                    {{ Form::select('term_id',@$terms ? @$terms : @$info['examterms'],@$data->term_id ?@$data->term_id :$info['current_academic_term'],
                                                    array('id'=>'acyear_term','class' => 'single-select form-control','required' => 'required',"placeholder"=>"Select Academic Term" )) }}
                                                </div>
                                          </div>
                                               
                                    </div> --}}
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
                                            <label class="control-label margin__bottom" for="status">No of Working Days <span class="required">*</span>
                                            </label>
                                            <div class="feild">
                                                {{Form::number('no_days',@$data->no_of_days,array('id'=>"nodays",'class'=>"form-control col-md-7 col-xs-12" ,
                                               'placeholder'=>"1-7[Mon-Sun]",'required'=>"required","maxlength"=>1))}}
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xs-12 col-sm-4 col-md-9">
                                        <div class="item form-group">
                                              <label class="control-label margin__bottom" for="status"><span class="required"></span>
                                              </label>
                                              <div class="row" style="margin-top:20px;">
                                                   
                                                    <div class="field col-4">
                                                        <button type="button"
                                                            class="btn btn-primary add_class_periods form-control"> <i
                                                                class="fa fa-plus" name="daily"></i>&nbsp;&nbsp;Add Exam
                                                            Periods</button>
                                                    </div>
                                              </div>  
                                        </div>
                                             
                                    </div>
                                    <div class="col-xs-12 col-sm-4 col-md-3">
                                        <div class="item form-group">
                                         <label class="control-label margin__bottom" for="status"> <span class="required"></span>
                                              </label>
                                             
                                              @if (@$layout == "edit" || @$layout == "clone")
            
                                              <div class="feild">
                                                {{-- <button type="button" class="btn btn-primary timetablebtn" name="{{ @$data->id }}" style="margin-top: 20px;">Add</button> --}}
                                             </div>
            
                                             @else
                                            
                                                  
                                              @endif
                                             
                                        </div>
                                             
                                    </div>
                                    </div>
                                    
                                    </div>
                                    
                                </div></div>
                        </div>
    
                        </div>
                    </div>
                </div>
                <div class="card-body append_card" style="display:none;">
                    <div class="accordion" id="accordionExample2">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button accordion2" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseOne">
                                    <span style="font-size: 1.5rem;">Add Exam Period</span>
                                </button>
                            </h2>
                            <div id="collapseTwo" class=" atnaccodrdian accordion-collapse collapse show"
                                aria-labelledby="headingOne" data-bs-parent="#accordionExample2" style="">
                                <div class="accordion-body" id="append_div">
    
    
                                    <div class="row" id="append_row">
    
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="distribution_mark">Start Time
                                                    <span class="required">*</span></label>
                                                <div class="feild">
                                                    {{ Form::time('start_time[]', '', [
                                                        'id' => 'start_time',
                                                        'class' => 'form-control col-md-7 col-xs-12',
                                                        'required' => 'required',
                                                    ]) }}
                                                </div>
                                            </div>
                                        </div>
    
                                        <div class="col-xs-12 col-sm-2 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="status">End Time <span
                                                        class="required">*</span></label>
                                                <div class="feild">
                                                    {{ Form::time('end_time[]', '', [
                                                        'id' => 'end_time',
                                                        'class' => 'form-control col-md-7 col-xs-12',
                                                        'required' => 'required',
                                                    ]) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="status">Period
                                                    Category<span class="required">*</span></label>
                                                <div class="feild">
                                                    {{ Form::select(
                                                        'period_category[]',
                                                        @$period_category,
                                                        @$data->$period_category ? @$data->$period_category : @$period_category,
                                                        [
                                                            'id' => 'period_category',
                                                            'class' => @$layout == 'edit' ? ' form-control' : 'single-select form-control',
                                                            'required' => 'required',
                                                            'placeholder' => 'Select period category',
                                                            @$layout == 'edit' ? 'disabled' : '',
                                                        ],
                                                    ) }}
    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-1">
                                            <button class="btn delete-row"><i class="fa fa-times-circle text-secondary"
                                                    style="margin-top: 1em !important; font-size:26px;"></i></button>
                                        </div>
                                    </div> <!--row-->
    
                                </div>
                                <div class="ms-2">
                                    <div class="ms-2 mb-2">
                                        <button type="button" class="btn btn-primary" id="add_new_button">Add New</button>
                                    </div>
    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif ($layout == 'clone')
                <div class="card-body">
                    <input type="hidden" name="type" value="clone">
                    <div class="card-title btn_style">
                        <h4 class="mb-0">class Time Table</h4>
    
                    </div>
                    <hr />
    
                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button accordion1" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    <span style="font-size: 1.5rem;">Class Details</span>
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse timetableaccordian collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                <div class="accordion-body">	
                                    <div class="col-xs-12">
                            
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
                                                    {{ Form::select('academic_year',@$academicyears,@$data->academic_year ? @$data->academic_year :@$info['current_academic_year'] ,
                                                    array('id'=>'timetableacyear','class' => @$layout =="edit" ? " form-control" : 
                                                    "single-select form-control termacademicyear",'required' => 'required','placeholder'=>"Select Academic year",@$layout =="edit"? "disabled" : "")) }}
                                                </div>
                                        </div>
                                            
                                        </div>
        
                                        {{-- <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                            <label class="control-label margin__bottom" for="status">Academic Term <span class="required">*</span>
                                                    </label>
                                                    <div class="feild">
                                                        {{ Form::select('term_id',@$terms ? @$terms : @$info['examterms'],@$data->term_id ?@$data->term_id :$info['current_academic_term'],
                                                        array('id'=>'acyear_term','class' => 'single-select form-control','required' => 'required',"placeholder"=>"Select Academic Term" )) }}
                                                    </div>
                                            </div>
                                                
                                        </div> --}}
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
                                                <label class="control-label margin__bottom" for="status">No of Working Days <span class="required">*</span>
                                                </label>
                                                <div class="feild">
                                                    {{Form::number('no_days',@$data->no_of_days,array('id'=>"nodays",'class'=>"form-control col-md-7 col-xs-12" ,
                                                'placeholder'=>"1-7[Mon-Sun]",'required'=>"required","maxlength"=>1))}}
                                                </div>
                                            </div>
                                        </div>
                                    
                                        {{-- <div class="col-xs-12 col-sm-4 col-md-9">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="status">Department <span class="required"></span>
                                                </label>
                                                <div class="row">
                                                        <div class="feild col-4">
                                                            {{ Form::select('dept_id',@$departments,@$data->dept_id ,
                                                            array('id'=>'dept_id','class' => 'single-select form-control','placeholder'=>"Select Department", )) }}                                               
                                                        </div> 
                                                        <div class="field col-4">
                                                            <button type="button"
                                                                class="btn btn-primary add_exam_periods form-control"> <i
                                                                    class="fa fa-plus" name="daily"></i>&nbsp;&nbsp;Add Exam
                                                                Periods</button>
                                                        </div>
                                                </div>  
                                            </div>
                                                
                                        </div> --}}
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="item form-group">
                                            <label class="control-label margin__bottom" for="status"> <span class="required"></span>
                                                </label>
                
                                                @if (@$layout == "edit" || @$layout == "clone")
                
                                                <div class="feild">
                                                    {{-- <button type="button" class="btn btn-primary timetablebtn" name="{{ @$data->id }}" style="margin-top: 20px;">Add</button> --}}
                                                </div>
                
                                                @else
                                                <div class="field col-4">
                                                    <button type="button"
                                                        class="btn btn-primary add_exam_periods form-control"> <i
                                                            class="fa fa-plus" name="daily"></i>&nbsp;&nbsp;Add Exam
                                                        Periods</button>
                                                </div>
                                                    
                                                @endif
                                                
                                            </div>
                                                
                                        </div>
                                    </div>
                                    
                                    </div>
                                    
                                </div></div>
                        </div>
    
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="card-title btn_style">
                        <h4 class="mb-0">Update Exam Period</h4>
                        <hr />
                    </div>
                    <div class="accordion" id="accordionExample2">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button accordion2" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseOne">
                                    <span style="font-size: 1.5rem;">Edit Exam Period</span>
                                </button>
                            </h2>
                            <div id="collapseTwo" class=" atnaccodrdian accordion-collapse collapse show"
                                aria-labelledby="headingOne" data-bs-parent="#accordionExample2" style="">
                                <div class="accordion-body" id="append_div">
    
                                    @if ($timing)
                                        @foreach ($timing as $period)
                                            <div class="row" id="append_row">
    
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom"
                                                            for="distribution_mark">Start Time <span
                                                                class="required">*</span></label>
                                                        <div class="feild">
                                                            {{ Form::time('start_time[]', @$period->from ? \Carbon\Carbon::parse($period->from)->format('h:i') : '', [
                                                                'id' => 'start_time',
                                                                'class' => 'form-control col-md-7 col-xs-12',
                                                                'required' => 'required',
                                                            ]) }}
                                                        </div>
                                                    </div>
                                                </div>
    
                                                <div class="col-xs-12 col-sm-2 col-md-3">
                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom" for="status">End Time
                                                            <span class="required">*</span></label>
                                                        <div class="feild">
                                                            {{ Form::time('end_time[]', @$period->to ? \Carbon\Carbon::parse($period->to)->format('h:i') : '', [
                                                                'id' => 'end_time',
                                                                'class' => 'form-control col-md-7 col-xs-12',
                                                                'required' => 'required',
                                                            ]) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                  
                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom" for="status">Period
                                                            Category<span class="required">*</span></label>
                                                        <div class="feild">
                                                            {{ Form::select('period_category[]', Configurations::CLASSPERIODCATEGORIES, $period->type, [
                                                                'id' => 'period_category',
                                                                'class' => @$layout == 'edit' ? ' form-control' : 'single-select form-control',
                                                                'required' => 'required',
                                                                'placeholder' => 'Select period category',
                                                            ]) }}
    
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-1">
                                                    <button class="btn delete-row-period"id="{{ $period->id }}"
                                                        type="button"><i class="fa fa-times-circle text-secondary "
                                                            style="margin-top: 1em !important; font-size:26px;"></i></button>
                                                </div>
                                            </div> <!--row-->
                                        @endforeach
                                    @endif
                                </div>
                                <div class="ms-2">
                                    <div class="ms-2 mb-2">
                                        <button type="button" class="btn btn-primary" id="add_new_button">Add New</button>
                                    </div>
    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
           
            
            @else
                <div class="card-body">
                    <div class="card-title btn_style">
                        <h4 class="mb-0">Update Period</h4>
                        <hr />
                    </div>
                    <div class="accordion" id="accordionExample2">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button accordion2" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseOne">
                                    <span style="font-size: 1.5rem;">Edit Period</span>
                                </button>
                            </h2>
                            <div id="collapseTwo" class=" atnaccodrdian accordion-collapse collapse show"
                                aria-labelledby="headingOne" data-bs-parent="#accordionExample2" style="">
                                <div class="accordion-body" id="append_div">
                                    <input type="hidden" name="period_id" value="{{$period_id}}">
                                
                                    @if ($period_data)
                                        @foreach ($period_data as $period)
                                            <div class="row" id="append_row">
                                            
                                                @php
                                                   $from = $period->from_time;
                                                    $to = $period->to_time;
                                                @endphp
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom "
                                                            for="distribution_mark">Start Time <span
                                                                class="required">*</span></label>
                                                        <div class="feild">
                                                            {{ Form::time('start_time[]', @$from, [
                                                                'id' => 'start_time',
                                                                'class' => 'form-control col-md-7 col-xs-12 ',
                                                                'required' => 'required',
                                                            ]) }}
                                                        </div>
                                                    </div>
                                                </div>
    
                                                <div class="col-xs-12 col-sm-2 col-md-3">
                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom" for="status">End Time
                                                            <span class="required">*</span></label>
                                                        <div class="feild">
                                                            {{ Form::time('end_time[]', @$to, [
                                                                'id' => 'end_time',
                                                                'class' => 'form-control col-md-7 col-xs-12',
                                                                'required' => 'required',
                                                            ]) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-4 col-md-3">
                                                 
                                                    <div class="item form-group">
                                                        <label class="control-label margin__bottom" for="status">Period
                                                            Category<span class="required">*</span></label>
                                                        <div class="feild">
                                                            {{ Form::select('period_category[]', Configurations::CLASSPERIODCATEGORIES, $period->type, [
                                                                'id' => 'period_category',
                                                                'class' => @$layout == 'edit' ? ' form-control' : 'single-select form-control',
                                                                'required' => 'required',
                                                                'placeholder' => 'Select period category',
                                                            ]) }}
    
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-1">
                                                    <button class="btn delete-row-period"id="{{ $period->id }}"
                                                        type="button"><i class="fa fa-times-circle text-secondary "
                                                            style="margin-top: 1em !important; font-size:26px;"></i></button>
                                                </div>
                                            </div> <!--row-->
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @php
                $style = $layout == 'create' ? 'display:none;' : '';
            @endphp
            <div class="row justify-content-end btn_submit" style="{{ $style }}">
                <div class="col-md-3 mb-3 me-2">
                    <button type="submit" class="btn btn-primary" style="float:inline-end" id="save_continue">
                        Save/Continue
                    </button>
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
    window.termurl='{{route('examterm.index')}}';
    window.calenderurl="{{ route('classtimetable.index') }}";
    window.subjectteachers="{{ route('subject.index') }}";
    window.depturl="{{ route('department.index') }}";

    window.sectionurl = '{{ route('section.index') }}';
    window.classurl = '{{ route('schooltype.index') }}';
    window.getstudentperformanceinfo = "{{ route('studentperformance') }}";
    window.append_new_period = '{{ route('examtimetable') }}';
    window.append_new_periods = '{{ route('classtimetable') }}';
    window.delete_period = "{{ route('timetable_period_delete') }}"
    // window.Assignedsubjectteachers="{{ route('subject.index') }}";
    // ExamTimetable.ExamTimetableInit(notify_script);
    ClassTimetable.ClassTimetableInit(notify_script);

    AcademicConfig.Timetableinit(notify_script);
    //AcademicConfig.academicinit(notify_script);

</script>
<script>
    $(document).ready(function() {
        function notify_script(title,text,type,hide) {
            new PNotify({
                title: title,
                text: text,
                type: type,
                hide: hide,
                styling: 'fontawesome'
            })
        }
            $("input[name^=no_days]").on('input', function(event) {
                this.value = this.value.replace(/[^1-9]/g, '');
            });

            $("#nodays").keyup(function() {
                if ($('#nodays').val() > 7) {
                    notify_script("Error","Please Type 1 to 7 Number Only");
                    $('#nodays').val("");
                } 
            });

            

        });

</script>
@endsection

@section('script_link')

    <!-- validator -->
    {!! Cms::script("theme/vendors/validator/validator.js") !!}
    {!! Cms::script("theme/vendors/validator/validator_form.js") !!}

@endsection
