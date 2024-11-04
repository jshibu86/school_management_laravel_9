@extends('layout::admin.master')

@section('title','payroll deduction')
@section('style')


@endsection
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('payrolldeduction.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'payrolldeduction-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('payrolldeduction.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
        <div class="box-header with-border mar-bottom20">
           
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_mark' , 'class' => 'btn btn-success btn-sm m-1  px-3 ')) }}

            @if (@$layout == "create")  

            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save-Continue', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat_continue' , 'value' => 'Edit_wallet' , 'class' => 'btn btn-dark btn-sm m-1  px-3')) }}

            @endif
           

           

             

              {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }}

              

             
        </div>
           @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit Deduction" : "Create Deduction"])

             <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ @$layout == "edit" ? "Edit " : "Create a New "}}Deduction</h5>
                    <hr/>
                    

                    <div class="col-xs-12">
                        <div class="row">
                            <div id="container__grade">
      
                           
      
                            <table id="grade-table" class="table table-stripped mt-3">
                                <tr>
                                <th>Deduction Name</th>
                                
                                <th>Percentage</th>
                                
                                <th>Active</th>
                                
                                
                                
                                <th>Action</th>
                                
                                </tr>
                               

                                @if (@$deductions)

                                @foreach (@$deductions  as $deduction )
                                    <tr>
                                        <input type="hidden" name="deduction_id[]" value="{{ $deduction->id }}"/>
                                        <td><input class="form-control" type="text" name="deduction_name[]" required placeholder="A+" value="{{ $deduction->deduction_name }}" /></td>
                                        
                                        <td><input class="form-control" type="text" name="percentage[]" required  placeholder="5.0"  value="{{ $deduction->percentage }}"/></td>
                                        <td>
                                            <input type="hidden" name="hidden_value[]" id="slider{{$deduction->id}}" value="{{$deduction->active=="1" ? 1 : 0}}"/>
                                            <label class="switch">
                                            <input type="checkbox" id="{{$deduction->id}}" {{$deduction->active=="1" ? "checked" : ""}} class="toggle-class" onchange="DynamicRow.Checkbox(this.checked ? 1:0,this.id,'slider')">
                                            <span class="slider round"></span>
                                        </label></td>
                                        {{-- <td><select class="form-control" name="activate[]"><option value="1" {{$deduction->active=="1" ? "selected" : ""}}>Enable</option><option value="0" {{$deduction->active=="0"? "selected":""}}>Disable</option></select></td> --}}
                                        
                                        <td><input class="btn btn-danger" type="button" value="delete" id="grade-table" onclick="DynamicRow.deleteRow(this,this.id,true,{{ $deduction->id }})" /></td>
                                    </tr>
                                @endforeach
                                    
                                @endif
                                    
                               
                            </table>
                             <button id="add-new-btn" class="btn btn-info mt-4 mb-3" ><i class="fa fa-plus"></i>Add New Deduction
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
    window.deleteurl="{{ route("payrolldeduction.index") }}";
    DynamicRow.Rowinit();

    $("#add-new-btn").on("click", function(e){
      //calling method to add new row
        e.preventDefault();
    
      var rowHtml='<tr><td><input class="form-control" type="text" name="deduction_name[]" required placeholder="HMO" /></td>'
      +'<td><input class="form-control" type="text" name="percentage[]" required  value="0" /></td>'
      +'<td><select class="form-control" name="activate[]"><option value="1">Enable</option><option value="0">Disable</option></select></td>'
     
      +'<td><input class="btn btn-danger" type="button" value="delete" id="grade-table" onclick="DynamicRow.deleteRow(this,this.id)" /></td></tr>';

     DynamicRow.addNewRow(rowHtml,"grade-table");
    });
</script>
@endsection
