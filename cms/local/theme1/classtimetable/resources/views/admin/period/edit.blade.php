@extends('layout::admin.master')

@section('title','period')
<style>
   .accordion-button:focus{
    border-color: #ffffff;
     box-shadow:none !important;
   }
   .add__update_period{
    margin-top: 30px
   }
   .accordion-button:before {
    float: right !important;
    font-family: FontAwesome;
    content: "\f068";
    background-color: #673ab7;
    border-radius: 50px;
    font-size: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    width: 21px;
    height: 21px;
    }
    .accordion-button.collapsed:before {
        float: right !important;
        content:"\f067";
    }
    .accordion-button::after{
        background-image: none!important;
    }
    .accordion-button:not(.collapsed){
        background-color: #ffff !important;
    }
    .accordion-button{
        font-weight: 700;
        color: black  !important;
        padding: 26px !important;
    }
    .period_select{
    background: #F6F6F6;
    border-radius: 4px;
    border-color: #F6F6F6;
    }
    .add_btn{
      background-color: #673AB7 !important;
      border-radius: 4px;
      color: #fff;
    }
    .btn i {
    margin-top: 0px !important;
    margin-bottom: 0px !important;
    font-size: 1.3rem;}
    .error{
      color: red;
    }
    .period__info{
      text-align: center;
    font-weight: 800;
    margin-bottom: 30px;
    }
    .note_info{
      background-color: whitesmoke;
    padding: 10px;
    border-left: 8px solid #673ab7;
    }
</style>
@section('style')


@endsection
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('period.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'period-form form-horizontal form-label-left', 'id' => 'period-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('period.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'period-form form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
        <div class="box-header with-border mar-bottom20">
           
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_period' , 'class' => 'btn periodsubmit btn-success btn-sm m-1  px-3 ')) }}

            @if (@$layout == "create")  

            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save-Continue', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat_continue' , 'value' => 'Edit_wallet' , 'class' => 'btn btn-dark btn-sm m-1 periodsubmit  px-3')) }}

            @endif
           

           

             <a class="btn btn-info btn-sm m-1  px-3" href="{{route('period.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

              {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }}

              

             
        </div>
           @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit Period" : "Create Period"])

             <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ @$layout=="create" ? "Create" : "Edit" }} Period</h5>
                    <hr/>
                    
                    <div class="accordion" id="accordionExample">


                      @if (@$layout == "create")
                        
                        <div class="accordion-item">
                          <h2 class="accordion-header" id="headingOne">
                            <button
                              class="accordion-button"
                              type="button"
                              data-bs-toggle="collapse"
                              data-bs-target="#collapseOne"
                              aria-expanded="true"
                              aria-controls="collapseOne"
                            >
                            &nbsp; Class and Academic Year Details
                            </button>
                          </h2>
                          <div
                            id="collapseOne"
                            class="accordion-collapse collapse show"
                            aria-labelledby="headingOne"
                            data-bs-parent="#accordionExample"
                          >
                            <div class="accordion-body">
                                <div class="col-xs-12">
                                    <div class="row">
                                    <div class="col-xs-12 col-sm-4 col-md-3">
                                      <div class="item form-group">
                                       <label class="control-label margin__bottom" for="status">Academic Year <span class="required">*</span>
                                            </label>
                                            <div class="feild">
                                                {{ Form::select('academic_year',@$academicyears,@$data->academic_year ? @$data->academic_year :$info['current_academic_year'] ,
                                                array('id'=>'acyear','class' => 'single-select form-control termacademicyear','required' => 'required',"placeholder"=>"Select Academic year" )) }}
                                            </div>
                                      </div>
                                           
                                    </div>
                                    <div class="col-xs-12 col-sm-4 col-md-3">
                                      <div class="item form-group">
                                       <label class="control-label margin__bottom" for="status">Academic Term <span class="required">*</span>
                                            </label>
                                            <div class="feild">
                                                {{ Form::select('academic_term',@$info['examterms'],@$data->academic_term ?@$data->academic_term :$info['current_academic_term'],
                                                array('id'=>'acyear_term','class' => 'single-select form-control','required' => 'required',"placeholder"=>"Select Academic Term" )) }}
                                            </div>
                                      </div>
                                           
                                    </div>
                                    <div class="col-xs-12 col-sm-4 col-md-3">
                                        <div class="item form-group">
                                         <label class="control-label margin__bottom" for="status">Select Classes <span class="required">*</span>
                                              </label>
                                              <div class="feild">
                                                  {{ Form::select('class_id[]',@$class_lists,@$data->group_id ,
                                                  
                                                  array('id'=>'class','class' => 'multiple-select form-control','required' => 'required',"multiple" )) }}
                                              </div>
                                              <small class="text-danger">Already period Created Classes Not Appear here *</small>
                                        </div>
                                             
                                      </div>

                                      {{-- <div class="col-xs-12 col-sm-4 col-md-3">
                                        <div class="item form-group">
                                         <button type="submit" class="btn btn-primary add__update_period" name="addupdatepriod" value="addupdateperiod">Add/Update Period</button>
                                        </div>
                                             
                                      </div> --}}
                                     
                                    </div>
                                </div>
                            </div>
                          </div>
                        </div>
                        @endif

                       
                            
                       
                        <div class="accordion-item">
                          <h2 class="accordion-header" id="headingTwo">
                            <button
                              class="accordion-button collapsed"
                              type="button"
                              data-bs-toggle="collapse"
                              data-bs-target="#collapseTwo"
                              aria-expanded="false"
                              aria-controls="collapseTwo"
                            >
                            &nbsp; {{ @$layout=="create" ? "Add" : "Edit" }} Period For Academic Year
                            </button>
                          </h2>
                          <div
                            id="collapseTwo"
                            class="accordion-collapse collapse {{ @$layout=== "edit" ? "show" : "" }}"
                            aria-labelledby="headingTwo"
                            data-bs-parent="#accordionExample"
                          >
                            <div class="accordion-body">
                              <section>
                                <div class="container_ py-5">
                                  @if (@$layout == "edit")

                                  <input type="hidden" name="academic_year" value="{{ @$data->academic_year }}"/>
                                   <input type="hidden" name="academic_term" value="{{ @$data->academic_term }}"/>
                                  <input type="hidden" name="class_id" value="{{ @$data->class_id }}"/>
                                    
                                  
                                  <div class="period__info">
                                    <p>Academic year - {{ @$acyear }}</p>
                                    <p>Academic Term - {{ @$term }}</p>
                                    <p>Class - {{ @$class }}</p>
                                  </div>
                                  @endif
                              
                                  <form class="">
                              
                                    <div class="period_div">

                                      <div class="period_note">
                                        <p class="note_info"><span class="text-bold">Note :</span><span>Set the start and end time for each Period,also select the type of category of the period</span></p>
                                      </div>
                                      {{-- <div class="row align-items-end">
                                        <div class="col-md-1">
                                          <label><b>Sl. No</b></label>
                                        </div>
                                        <div class="col-md-10">
                                          <div class="row">
                                            <div class="col-md-4">
                                              <label for="start_time"><b>Start Time</b></label>
                                              <input type="text" readonly  class="form-control period_select btimepicker" id="start_time" placeholder="select start time"/>
                                              
                                            </div>
                                            <div class="col-md-4">
                                              <label for="end_time"><b>End Time</b></label>
                                              <input type="text" readonly  class="form-control period_select btimepicker" id="end_time" placeholder="select end time"/>
                                              
                                            </div>
                                            <div class="col-md-4">
                                              <label for="period_type"><b>Type</b></label>
                                              <input type="text" readonly class="form-control period_select" id="type" placeholder="select period type"/>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="col-md-1">
                                          <button type="button" id="addperiod" class="btn btn-primary"> <i class="fa fa-plus"></i></button>
                                        </div>
                                      </div> --}}
                              
                                      <div id="added_periods" class="mt-5">
                              
                                        {{-- <label class="mb-3"><b>Added Periods (<span class="total_cls" id="inc">{{ @$layout=== "create" ? 1 : sizeof(@$data->periods) }}</span>)</b></label> --}}
                                        @if (@$layout === "edit")

                                        @if (sizeof(@$data->periods))

                                        @foreach (@$data->periods as $period )


                                        <div class="row align-items-end mb-3 period_row">
                                          {{-- <div class="col-md-1">
                                            <label class="inc_span"><b>{{ $loop->index+1 }}</b></label>
                                          </div> --}}
                                          <div class="col-md-10">
                                            <div class="row">
                                              <div class="col-md-4">
                                                <label for="start{{ $period->id }}"><b>Start Time</b></label>

                                                <input type="hidden" name="map_id[]" value="{{ @$period->id }}"/>
                                                <input type="time" id="start{{ $period->id }}" required name="starttime[]" class="form-control period_select timepicker" value="<?php
                                                $date = date(
                                                    "H:i",
                                                    strtotime($period->from)
                                                );
                                                echo "$date";
                                                ?>">
                                              </div>
                                              <div class="col-md-4">
                                                <label for="end{{ $period->id }}"><b>End Time</b></label>
                                                <input type="time" id="end{{ $period->id }}" required name="endtime[]" class="form-control period_select timepicker" value="<?php
                                                $date = date(
                                                    "H:i",
                                                    strtotime($period->to)
                                                );
                                                echo "$date";
                                                ?>">
                                              </div>
                                              <div class="col-md-4">
                                                <label for="select{{ $period->id }}"><b>Period Category</b></label>
                                                <select class="form-select period_select" id="select{{ $period->id }}" name="periodtype[]" required >
                                                 
                                                  
                                                  <option value="0"  {{ $period->type == 0 ? "selected" :""}}>Teaching </option>
                                                  <option value="1" {{ $period->type == 1 ? "selected" :""}}>Lunch break </option>
                                                  <option value="2" {{ $period->type == 2 ? "selected" :""}}>Break </option>
                                                </select>
                                                
                                              </div>
                                            </div>
                                          </div>
                                          <div class="col-md-1">
                                            <button type="button" id="remove_period" class="btn btn-danger" onclick="AcademicConfig.timtablePerioddelete(this,'{{ $period->id }}','{{ @$data->academic_year }}','{{ @$data->class_id }}')"> <i class="fa fa-times"></i></button>
                                          </div>
                                        </div>
                                          
                                        @endforeach
                                          
                                        @endif

                                        @else
                                        <div class="row align-items-end mb-3 period_row">
                                          {{-- <div class="col-md-1">
                                            <label class="inc_span"><b>1</b></label>
                                          </div> --}}
                                          <div class="col-md-10">
                                            <div class="row">
                                              <div class="col-md-4">
                                                <label for="start"><b>Start Time</b></label>

                                                {{-- <input type="hidden" name="map_id[]" value="{{ @$period->id }}"/> --}}
                                                <input type="time" id="start" required name="starttime[]" class="form-control period_select timepicker">
                                              </div>
                                              <div class="col-md-4">
                                                <label for="end"><b>End Time</b></label>
                                                <input type="time" id="end" required name="endtime[]" class="form-control period_select timepicker" >
                                              </div>
                                              <div class="col-md-4">
                                                <label for="select"><b>Period Category</b></label>
                                                <select class="form-select period_select" id="select" name="periodtype[]" required >
                                                 
                                                  
                                                  <option >Teaching </option>
                                                  <option >Lunch break </option>
                                                  <option >Break </option>
                                                </select>
                                                
                                              </div>
                                            </div>
                                          </div>
                                          <div class="col-md-1">
                                            <button type="button" id="remove_period" class="btn btn-danger" > <i class="fa fa-times"></i></button>
                                          </div>
                                        </div>

                                        
                                          
                                        @endif

                                       


                                      </div>
                                      <div class="col-md-4">
                                        <button type="button" id="addperiod" class="btn btn-primary mt-3"> <i class="fa fa-plus"></i>Add New</button>
                                      </div>
                                    </div>
                                        
                                  </form>
                              
                                </div>
                              
                              </section>
                            </div>
                          </div>
                        </div>
                      
                       
                      </div>
                    
                </div>
            </div>

        
       
       

    {{ Form::close() }}    
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
        window.fetchstudents="{{ route('exam.index') }}"
    //AcademicConfig.Timetableinit(notify_script);
    window.termurl='{{route('examterm.index')}}';
    window.perioddeleteurl='{{route('period.index')}}';
    AcademicYearConfig.AcademicyearInit();
    //AcademicConfig.academicinit(notify_script);

</script>

<script>

	$(document).ready(function() {  

    @if (@$layout == "create")

    $(document).on("click", "#remove_period", function() {

      updateIndex1 = function() {
				$('.inc_span').each(function(i) {
					$(this).html(i + 1);
				});
			};

      updatecnt1 = function() {
	      const root = document.querySelectorAll('.period_row').length;
				const root1 = document.getElementById('inc');
				root1.innerHTML = root;
			}
				$(this).closest('.period_row').remove(); 
				updateIndex1();
				updatecnt1();
		    });
      
    @endif
			

        $(document).on("click", "#addperiod", function() {  

            updateIndex = function() {
            $('.inc_span').each(function(i) {
              $(this).html(i + 1);
            });
          };




          var select1=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);
          var select2=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);
          var select3=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);
			

            $("#added_periods").append(`<div class="row align-items-end mb-3 period_row">
					
					<div class="col-md-10">
						<div class="row">
							<div class="col-md-4 item form-group ">
								<label for="${select1}"><b>Strat Time</b></label>
								<input type="time" id="${select1}" required name="starttime[]" class="form-control period_select timepicker">
							</div>
							<div class="col-md-4 item form-group ">
								<label for="${select2}"><b>End Time</b></label>
								<input type="time" id="${select2}" required name="endtime[]" class="form-control period_select timepicker">
							</div>
							<div class="col-md-4 item form-group ">
								<label for="${select3}"><b>Period Category</b></label>
                <select class="form-select period_select" id="${select3}" name="periodtype[]" required >
									
									<option value="0">Teaching </option>
									<option value="1">Lunch break </option>
									<option value="2">Break </option>
								</select>
								
							</div>
						</div>
					</div>
					<div class="col-md-1">
						<button type="button" id="remove_period" class="btn btn-danger"> <i class="fa fa-times"></i></button>
					</div>
				  </div>`);
            updateIndex();

            updatecnt = function() {
	            const root = document.querySelectorAll('.period_row').length;
              const root1 = document.getElementById('inc');
              root1.innerHTML = root;
			}
			updatecnt();
            
           
            $(document).on("click", "#remove_period", function() {
              $(this).closest('.period_row').remove(); 
              updateIndex();
              updatecnt();
              });
            

        });



    });  
</script>

@endsection

@section('script_link')

    <!-- validator -->
    {!! Cms::script("theme/vendors/validator/validator.js") !!}
    {!! Cms::script("theme/vendors/validator/validator_form.js") !!}

@endsection
