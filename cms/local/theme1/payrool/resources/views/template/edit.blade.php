@extends('layout::admin.master')

@section('title','salery template')
@section('style')


@endsection
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('salerytemplate.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'payrool-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('salerytemplate.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
        <div class="box-header with-border mar-bottom20">
           
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_payrool' , 'class' => 'btn btn-success btn-sm m-1  px-3 ')) }}

            @if (@$layout == "create")  

            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save-Continue', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat_continue' , 'value' => 'Edit_wallet' , 'class' => 'btn btn-dark btn-sm m-1  px-3')) }}

            @endif
           

           

             <a class="btn btn-info btn-sm m-1  px-3" href="{{route('salerytemplate.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

              {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }}

              

             
        </div>
           @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit Salerytemplate" : "Create Salerytemplate"])

             <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Create a new Salerytemplate</h5>
                    <hr/>
                    <div class="col-xs-12">
                        <div class="row">
                        
                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                                <label class="control-label margin__bottom" for="status">Grade Name <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{Form::text('grade_name',@$data->grade_name,array('id'=>"grade_name",'class'=>"form-control col-md-7 col-xs-12" ,
                                   'placeholder'=>"A,B,C",'required'=>"required"))}}
                                </div>
                            </div>
                        </div>


                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                                <label class="control-label margin__bottom" for="status">Salary <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{Form::number('basic_salery',@$data->basic_salery,array('id'=>"basic_salery",'class'=>"form-control col-md-7 col-xs-12" ,
                                   'placeholder'=>"10000",'required'=>"required"))}}
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>

                     <div class="col-xs-12">
                        <div class="row">
                            <div id="container__grade">
      
                           
      
                            <table id="particular-table" class="table table-stripped mt-3">
                                <tr>
                                <th>Particular Name</th>
                                
                                <th>Percentage (%)</th>
                                
                                <th> Amount</th>
                                
                                <th>Action</th>
                                
                                </tr>
                              
                                @if (@$layout=="edit")
                                @foreach (@$data->particulars as $particular)
                                @php
                                    $uuid=uniqid();
                                @endphp
                                    <tr>
                                    <td>
                                        <select class="form-control select2" name="particular[]">
                                            @foreach ($particulars as $data_)
                                            <option value="{{$data_->id}}" {{$particular['particular_id'] == $data_->id ? "selected" : ""}} >{{$data_->particular_name}}</option>  
                                            @endforeach  
                                        </select>
                                    </td>
                                    <td>
                                        <input class="form-control" type="number"  name="deduction[]" value="{{$particular['deduction_per']}}" id="{{$uuid}}" onkeyup=" DynamicRow.OnchangeDeduction(this, this.id)"  required  placeholder="2" />
                                    </td>
                                    <td>
                                         <input class="form-control deduction_amount" type="number" name="amount[]" value="{{$particular['deduction_amount']}}" id="deduct{{$uuid}}" required placeholder="0" readonly  />
                                    </td>
     
                                    <td>
                                         <input class="btn btn-danger" type="button" value="delete" id="particular-table" onclick="DynamicRow.deleteRow(this,this.id,false,null,true)" />
                                    </td>
                                </tr>
                                @endforeach
                                
                                    
                                @endif
                            </table>
                            <div class="row">
                                <div class="col-6"></div>

                                <div class="col-6">

                                    <div style="width: 90%;margin:auto">
                                    <table id="particular-table-demo" class="table table-stripped mt-3">
                                        
                                        
                                            <tr>
                                                <input type="hidden" name="salery_with_particulars" id="salery_with_particulars"/>
                                                <input type="hidden" name="total_deduction" id="total_deduction"/>
                                                {{-- <th>Percentage (<span id="deduction_amount">{{@$layout=="edit" ? @$data->total_deduction : 0}}</span>)</th> --}}
                                            </tr>
                                            <tr>
                                                <th>Basic Salary {{Configurations::getConfig("site")->currency_symbol}} (<span id="actual_salery">{{@$layout=="edit" ? @$data->salery_with_particulars : 0}}</span>)</th>
                                            </tr>
                                            
                                        </table>  
                                    </div>
                            
                                </div>
                            </div>
                             <button id="add-new-btn" type="button" class="btn btn-info mt-4 mb-3" ><i class="fa fa-plus"></i>Add Particular
                            </button>
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
<script type="module">

    
   
    DynamicRow.Rowinit();

    $("#add-new-btn").on("click", function(e){
      //calling method to add new row
        e.preventDefault();
        var id = Math.random().toString(16).slice(2);
    
      var rowHtml=`<tr><td><select class="form-control select2" name="particular[]">@foreach ($particulars as $data )
          <option value="{{$data->id}}">{{$data->particular_name}}</option>  
        @endforeach  </select></td>'
      +'<td><input class="form-control" type="number"  name="deduction[]" id="${id}" onkeyup=" DynamicRow.OnchangeDeduction(this, this.id)"  required  placeholder="2" /></td>'
      +'<td><input class="form-control deduction_amount" type="number" name="amount[]" id="deduct${id}" required placeholder="0" readonly  /></td>'
     
      +'<td><input class="btn btn-danger" type="button" value="delete" id="particular-table" onclick="DynamicRow.deleteRow(this,this.id,false,null,true)" /></td></tr>`;

     DynamicRow.addNewRow(rowHtml,"particular-table");

     $(".select2").select2();

     
    });
</script>

@endsection

