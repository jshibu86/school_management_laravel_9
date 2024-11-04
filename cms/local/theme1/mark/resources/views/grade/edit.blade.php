@extends('layout::admin.master')

@section('title','grade')
@section('style')


@endsection
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('grade.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'grade-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('grade.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
        <div class="box-header with-border mar-bottom20">
           
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_mark' , 'class' => 'btn btn-success btn-sm m-1  px-3 ')) }}

            @if (@$layout == "create")  

            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save-Continue', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat_continue' , 'value' => 'Edit_wallet' , 'class' => 'btn btn-dark btn-sm m-1  px-3')) }}

            @endif
           

           

             <a class="btn btn-info btn-sm m-1  px-3" href="{{route('grade.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

              {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }}

              

             
        </div>
           @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit Grade" : "Create Grade"])

             <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ @$layout == "edit" ? "Edit " : "Create a New "}}Grade System</h5>
                    <hr/>
                    <div class="col-xs-12">
                        <div class="row">
                       
                        <div class="col-xs-12 col-sm-4 col-md-3">
                         <div class="item form-group">
                                <label class="control-label margin__bottom" for="status">Grade System Name <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{Form::text('grade_sys_name',@$data->grade_sys_name,array('id'=>"grade_sys_name",'class'=>"form-control col-md-7 col-xs-12" ,
                                   'placeholder'=>"WAEC or CAMBRIDGE",'required'=>"required"))}}
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="row">
                            <div id="container__grade">
      
                           
      
                            <table id="grade-table" class="table table-stripped mt-3">
                                <tr>
                                <th>Grade Name</th>
                                
                                <th>Grade Point</th>
                                
                                <th>Mark From</th>
                                
                                <th>Mark Upto</th>

                                <th>Note</th>
                                
                                <th>Action</th>
                                
                                </tr>
                                @if (@$layout=="edit")

                                @if (sizeof(@$data->grades))

                                @foreach (@$data->grades  as $grade )
                                    <tr>
                                        <input type="hidden" name="grade_id[]" value="{{ $grade->id }}"/>
                                        <td><input class="form-control" type="text" name="grade_name[]" required placeholder="A+" value="{{ $grade->grade_name }}" /></td>
                                        
                                        <td><input class="form-control" type="text" name="grade_point[]" required  placeholder="5.0"  value="{{ $grade->grade_point }}"/></td>
                                        
                                        <td><input class="form-control" type="number" name="mark_from[]" value="{{ $grade->mark_from }}" required placeholder="0"  /></td>
                                        
                                        <td><input class="form-control" type="number" name="mark_upto[]" value="{{ $grade->mark_upto }}" required placeholder="50"  /></td>
                                        
                                        <td><input class="form-control" type="text" name="grade_note[]" value="{{ $grade->grade_note }}" placeholder="Good" /></td>
                                        
                                        <td><input class="btn btn-danger" type="button" value="delete" id="grade-table" onclick="DynamicRow.deleteRow(this,this.id,true,{{ $grade->id }})" /></td>
                                    </tr>
                                @endforeach
                                    
                                @endif
                                    
                                @endif
                            </table>
                             <button id="add-new-btn" class="btn btn-info mt-4 mb-3" ><i class="fa fa-plus"></i>Add New Grade
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
    window.deleteurl="{{ route("deletegrade") }}";
    DynamicRow.Rowinit();

    $("#add-new-btn").on("click", function(e){
      //calling method to add new row
        e.preventDefault();
    
      var rowHtml='<tr><td><input class="form-control" type="text" name="grade_name[]" required placeholder="A+" /></td>'
      +'<td><input class="form-control" type="text" name="grade_point[]" required  placeholder="5.0" /></td>'
      +'<td><input class="form-control" type="number" name="mark_from[]" required placeholder="0"  /></td>'
      +'<td><input class="form-control" type="number" name="mark_upto[]" required placeholder="50"  /></td>'
      +'<td><input class="form-control" type="text" name="grade_note[]" placeholder="Good" /></td>'
      +'<td><input class="btn btn-danger" type="button" value="delete" id="grade-table" onclick="DynamicRow.deleteRow(this,this.id)" /></td></tr>';

     DynamicRow.addNewRow(rowHtml,"grade-table");
    });
</script>
@endsection
