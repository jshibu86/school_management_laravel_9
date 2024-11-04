@extends('layout::admin.master')

@section('title','fees Setup')

@section('style')

<link rel="stylesheet" href="{{asset('assets/plugins/multiselect/example-styles.css')}}">
@endsection
<style>
    #fee__items th{
        color: white !important;
        font-weight: 500 !important;
        font-size: 14px !important;
    }
</style>
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('feesetup.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'feesetup-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('feesetup.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
        <div class="box-header with-border mar-bottom20">
           
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_fees' , 'class' => 'btn btn-success btn-sm m-1  px-3 ')) }}

            @if (@$layout == "create")  

            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save-Continue', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat_continue' , 'value' => 'Edit_wallet' , 'class' => 'btn btn-dark btn-sm m-1  px-3')) }}

            @endif
           

           

             <a class="btn btn-info btn-sm m-1  px-3" href="{{route('feesetup.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

              {{-- {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }} --}}

              

             
        </div>
           @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit Fee Setup" : "Create a New Fee Setup"])

             <div class="card">
                <div class="card-body">
                    <h5 class="card-title"> {{@$layout == "edit" ? "Edit Fee Setup" : "Create Fee Setup"}}</h5>
                    <hr/>
                   <div class="col-xs-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-xs-12 col-sm-4 col-md-4">
                                    <div class="item form-group">
                                    <label class="control-label margin__bottom" for="status">Select Academic Year<span class="required">*</span>
                                        </label>
                                        <div class="feild">
                                            
                                            {{ Form::select('academic_year',@$info['academicyears'],@$data->academic_year ?@$data->academic_year : @$info['current_academic_year'],
                                            array('id'=>'timetableacyear','class' => @$layout =="edit" ? " form-control " : 
                                            "single-select form-control ",'required' => 'required','placeholder'=>"Select Academic Year",@$layout =="edit"? "disabled" : "")) }}
                                        </div>
                                    </div>
                                     
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-4">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="school_name"> Select School Type <span class="required">*</span>
                                    </label>
                                    <div class="feild">
                                        {{ Form::select('school_type',$school_type_info,@$data->school_type ,
                                    
                                    array('id'=>'school_type','class' => 'form-control single-select','required' => 'required','placeholder'=>"Select School Type" ,@$layout =="edit"? "disabled" : "")) }}
                                        </div>
                                </div>
                            </div>


                            <div class="col-xs-12 col-sm-4 col-md-4">
                                <div class="item form-group">
                                 <label class="control-label margin__bottom" for="status">Select Class<span class="required">*</span>
                                      </label>
                                      <div class="feild">
                                         
                                          {{ Form::select('class_id',@$class_lists,@$data->class_id,
                                          array('id'=>'class_id','class' => @$layout =="edit" ? " form-control " : 
                                          "single-select form-control ",'required' => 'required','placeholder'=>"Select Class",@$layout =="edit"? "disabled" : "")) }}
                                      </div>
                                </div>
                                     
                            </div>

                            {{-- <div class="col-xs-12 col-sm-4 col-md-4">
                                <div class="item form-group">
                                 <label class="control-label margin__bottom" for="status">Select Section<span class="required"></span>
                                      </label>
                                      <div class="feild">
                                         
                                          {{ Form::select('section_id',[],@$data->class_id,
                                          array('id'=>'section_id','class' => @$layout =="edit" ? " form-control " : 
                                          "single-select form-control ",'required' => 'required','placeholder'=>"Select Section",@$layout =="edit"? "disabled" : "")) }}
                                      </div>
                                </div>
                                     
                            </div> --}}

                            
                         @if (@$layout=="edit" && $data->department_id)
                             <div class="col-xs-12 col-sm-4 col-md-4 schooldepartment ">
                        @else
                            <div class="col-xs-12 col-sm-4 col-md-4 schooldepartment d-none">  
                        @endif
                       
                            <div class="item form-group">
                                <label class="control-label margin__bottom" for="school_name"> Select Department <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{ Form::select('department_id',@$departments,@$data->department_id ,
                                     array('id'=>'department_id','class' => 'form-control single-select','placeholder'=>'select department','required' => 'required' ,@$layout =="edit"? "disabled" : "")) }}
                                    </div>
                            </div>
                        </div>

                             
                            </div>

                            <div class="row mt-4">

                                <div class="terms__due" style="display:none">

                               
                                    <label class="control-label margin__bottom" for="status">Please Provide Terms Due Dates<span class="required">*</span>
                                        </label>

                                    <div class="terms__lists row"></div>
                                    
                                </div>

                                {{-- <div class="month_due" style="display:none">
                                    <div class="col-xs-12 col-sm-4 col-md-6">
                                         <label class="control-label margin__bottom" for="status">Please Provide Due Date of Every Month<span class="required">*</span>
                                        </label>
                                        <div class="item form-group input-group mb-3"> <span class="input-group-text">Monthly</span>
                                               
                                                <input type="date" class="form-control" aria-label="Dollar amount (with dot and two decimal places)">
                                        </div>
                                    </div>
                                </div> --}}



                                 <div class="full_due" style="display:none">
                                    <div class="col-xs-12 col-sm-4 col-md-6">
                                         <label class="control-label margin__bottom" for="status">Please Provide Due Date<span class="required">*</span>
                                        </label>
                                        <div class="item form-group input-group mb-3"> <span class="input-group-text">Full Payment</span>
                                                <span class="input-group-text">100%</span>
                                                <input type="date" class="form-control" aria-label="Dollar amount (with dot and two decimal places)">
                                        </div>
                                    </div>
                                </div>


                            </div>
                            
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                 <div class="col-xs-12 col-sm-4 col-md-4">
                                <div class="item form-group">
                                 <label class="control-label margin__bottom" for="status">Select Fees To Complete Your Template<span class="required">*</span>
                                      </label>
                                      <div class="feild">
                                         
                                          {{ Form::select('fees_type',@$feetypes,@$selectedfeetype,
                                          array('id'=>'fees_type_select','class' => "form-control",'required' => 'required',"multiple")) }}
                                      </div>
                                </div>
                                     
                            </div>

                            <div class="row_ mt-3">
                                <table class="table table-striped" id="fee__items">
                                    <thead>
                                         <tr style="background-color: #2a3f54;">
                                        <th>Fee Name</th>
                                        <th>Amount {{ Configurations::getConfig("site")->currency_symbol }}</th>
                                        @if (@$layout!="edit")
                                          <th>Action</th>
                                        @endif
                                        <th>Is Compulsory</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @if (@$layout=="edit")

                                    @foreach (@$data->feelists as$list )
                                        <tr>
                                            <td><input type="hidden" name="fee_name[]" value="{{ $list->fee_name }}" />
                                            <input type="hidden" name="fee_id[]" value="{{ $list->fee_id }}" />
                                            {{ $list->fee_name }}
                                            </td>
                                            <td><div class="item form-group"><input required type="number" name="fee_amount[]" class="form-control fee_amount " readonly value="{{ $list->fee_amount }}" /></div></td>
                                        
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input is_compulsory_check" onchange="updateCompulsory(this)" type="checkbox" id="flexSwitchCheckChecked"  {{@$list->is_compulsory == 1 ? "checked" : ""}} >
                                                    <input type="hidden" class="is_compulsory" name="is_compulsory[]" value="{{ @$list->is_compulsory == 1 ? 1 : 0 }}">
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                        
                                    @endif

                                      

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td>Total Amount</td>
                                            @if (@$layout=="edit")
                                            <td>
                                                <input type="hidden" name="total_amount" value="{{ $data->total_amount }}" class="total_amount"/>
                                                <span class="total_amount_text">{{ Configurations::getConfig("site")->currency_symbol }}{{ $data->total_amount }}</span>
                                            </td>
                                            @else
                                             <td>
                                                <input type="hidden" name="total_amount" class="total_amount"/>
                                                <span class="total_amount_text">{{ Configurations::getConfig("site")->currency_symbol }} 0.00</span>
                                            </td>
                                            @endif
                                           
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                    
                                    
                                </table>


                               
                                
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


@section("scripts")
 <script src="{{asset('assets/plugins/multiselect/jquery.multi-select.js')}}"></script>
 <script>
    function updateCompulsory(checkbox) {
        const hiddenInput = checkbox.closest('.form-check').querySelector('.is_compulsory');
        if (checkbox.checked) {
            hiddenInput.value = 1; 
        } else {
            hiddenInput.value = 0;
        }
    }

 </script>
<script type="module">
    window.sectionurl='{{route('section.index')}}';
     window.department = "{{route("is_department_applies")}}";
    $(document).ready(function() {
        $("#fees_type_select").multiSelect();
        var layout=@json(@$layout);
        var selectedid=@json(@$selectedid);
        var types=@json(@$feetypes);
        window.SelectedFeeArray=layout==="edit" ? selectedid : [0];
        FeeConfig.Feeinit();
        FeeConfig.FeeSetup(types);
    
    });
</script>
@endsection
