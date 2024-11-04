<style>

    .form-control_{
     width: 100%;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    border: 1px solid #ced4da;
    line-height: 1.5;
    }
    .pay__fee__information{
        padding: 35px!important;
    }
    .feefull_information{
        display: flex;
        justify-content: center;
        gap: 20px;
    }
    .action_btn{
        display: flex;
        align-items: center;
        justify-content: center;

    }
    .pay__fee__information__success{
        padding: 42px;
    }
    .title_info{
        justify-content: space-between
    }
    .btn-close{
        border: 3px solid black;
        border-radius: 50px;
        padding: 6px;
    }
</style>

<div class="row pay__fee__information">
    <div class="col-md-12">
                                <div class="student_information mt-4">
                                    <div class="title_info d-flex align-items-center">
                                    <h5 class="card-title">Student Information</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <hr/>
                                    <div class="info_student d-flex">
                                         <div class="student_image">
                                            <img src="{{ @$student_info->image ? asset(@$student_info->image) : asset("assets/images/default.jpg")}}" style="width:100px"/>
                                        </div>
                                    <div class="student_contact">
                                        <p>Name : {{ @$student_info->first_name }} {{ @$student_info->last_name }}</p>
                                        <p>Class/Section :{{ @$student_info->class->name }}/{{ @$student_info->section->name }} </p>
                                        <p>Department : {{ @$student_info->department ?  @$student_info->department->dept_name : $department }}</p>
                                        <p>Term : {{ @$term }}</p>
                                    </div>
                                    </div>
                                   
                                </div>

                                <div class="fee_amount_info mt-4 text-center ">
                                    <h5 class="fw-bold">Amount : {{ Configurations::CurrencyFormat(@$paid_amount) }}</h5>
                                    @if (@$type=="month")
                                    <p>{{ @$selected_month }}-{{ @$selected_year }}</p>
   
                                    @endif
                                    @if (@$type == "term")
                                        <p>{{ @$per }}% Amount to be Paid</p>
                                    @endif
                                    
                                </div>

                                 <div class=" feefull_information mt-4">
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="item form-group">
                                        <label class="control-label margin__bottom" for="status">Select Payment Method<span class="required">*</span>
                                            </label>
                                            <div class="feild">
                                                
                                                {{ Form::select('payment_method',Configurations::FEEPAYMENTMETHOD,@$data->payment_method,
                                                array('id'=>'payment_method','class' => @$layout =="edit" ? " form-control_" : 
                                                "single-select form-control_",'required' => 'required','placeholder'=>"Select Payment Method",@$layout =="edit"? "disabled" : "")) }}
                                            </div>  
                                        </div>
                                        
                                    </div>

                                    <div class="col-xs-12 col-sm-6 col-md-4 demanddraft d-none">
                                        <div class="item form-group">
                                        <label class="control-label margin__bottom" for="status">Upload Demand Draft<span class="required">*</span>
                                            </label>
                                            <div class="feild">
                                                
                                               <input class="form-control" type="file" id="formFile" name="demand_draft">
                                            </div>
                                        </div>
                                        
                                    </div>

                                    <div class="col-xs-12 col-sm-6 col-md-4">

                                        <div class="item form-group">
                                        <label class="control-label margin__bottom" for="status">Remark<span class="required"></span>
                                            </label>
                                            <div class="feild">
                                                
                                                {{   Form::textarea('remark', null, [
                                                'class'      => 'form-control',
                                                'rows'       => 3, 
                                                'name'       => 'remark',
                                                'id'         => 'remark',
                                                
                                                ]) }}
                                            </div>
                                        </div>
                                    
                                    </div>
                                      <input type="hidden" name="type" value="{{ @$type }}"/>
                                     <input type="hidden" name="fee_setup_id" value="{{ @$feesetup->id }}"/>
                                    <input type="hidden" name="class_id" value="{{ @$class_id }}"/>
                                    <input type="hidden" name="section_id" value="{{ @$section_id }}"/>
                                    <input type="hidden" name="student_id" value="{{ @$student_id }}"/>
                                    <input type="hidden" name="academic_year" value="{{ @$academic_year }}"/>
                                    <input type="hidden" name="selected_term_date" value="{{ @$selected_term_date }}"/>
                                    <input type="hidden" name="selected_term" value="{{ @$selected_term }}"/>

                                     <input type="hidden" name="selected_month" value="{{ @$selected_month }}"/>
                                    <input type="hidden" name="selected_year" value="{{ @$selected_year }}"/>
                                    <input type="hidden" name="paid_amount" value="{{ @$paid_amount }}"/>
                                    

                                    

                                    

                                    
                                </div>
                                <div class="action_btn mt-3 row">
                                    <div class="col-md-6">
                                        <button type="button" style="width: 100%" class="me-4 btn btn-danger" data-bs-dismiss="modal" class="cancel_payment">Cancel</button>
                                    </div>
                                    <div class="col-md-6">
                                         <button type="submit" style="width: 100%" class="me-4 btn btn-success confirm_payment">Confirm Payment</button>
                                    </div>
                                         
                                       
                                </div>
    </div>
   
</div>
<div class="pay__fee__information__success d-none">
 @include("fees::admin.includes.success",['student_info'=>$student_info,"department"=>$department,"amount"=>$paid_amount,"term"=>$term,"type"=>$type])
</div>
<script>
    FeeConfig.PayFeepayment();
</script>