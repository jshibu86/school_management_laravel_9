@extends('layout::admin.master')

@section('title','Staff Upload')
@section('style')
<style>
   
.small__header{
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
}
</style>

@endsection
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('teacher.bulkupload'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left ', 'id' => 'subjectmapping-form','novalidate' => 'novalidate')) }}
       
        @endif
        <div class="box-header with-border mar-bottom20">
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Upload', ['type' => 'submit','class' => 'btn btn-success btn-sm m-1  px-3']) }}
            <a class="btn btn-info btn-sm m-1  px-3" href="{{route('teacher.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

           
            @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit Staff Upload" : "Create Staff Upload"])
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Staff Upload</h5>
                <hr/>
               
            <div class="col-xs-12">
                <div class="row">
                    
                    
                    <div class="col-xs-12 col-sm-4 col-md-3">
                        <div class="item form-group">
                            <label class="control-label margin__bottom" for="school_name"> Departments <span class="required">*</span>
                            </label>
                            <div class="feild designation_select_feild">

                               
                            {{ Form::select('stu_department[]',@$departments,@$selected_departments ,
                             array('id'=>'designation_type','class' => 'form-control multiple-select','required' => 'required',"multiple"=>true)) }}
                            
                            </div>
                            
                        </div>
                    </div>
                     
                    <div class="col-xs-12 col-sm-4 col-md-3">
                        <div class="item form-group">
                            <label class="control-label margin__bottom" for="school_name">Upload File <span class="required">*</span>
                            </label>
                            <div class="feild">
                           <input type="file" class="form-control" name="upload_file" accept=".csv"/>
                            </div>
                        </div>
                    </div>
               
                </div>
                
                {{Form::close()}}
                   
                    <!-- //status -->
                </div>
               
            </div>
            <div class="col-xs-12">
                   
                <div class="card_">
                  <div class="card-body_">
                      <div class="accordion accordion-flush" id="accordionFlushExample">
                          <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingOne">
                              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                  <i class="fa fa-exclamation-triangle text-danger" aria-hidden="true" style="margin-right: 20px"></i>Instructions For Upload Staff Information<small class="text-danger">(Kindly read The Istructions)</small>
                              </button>
                            </h2>
                            <div id="flush-collapseOne" class="accordion-collapse collapse show" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample" style="">
                              <div class="accordion-body">
                                  <p class="card-text text-danger panel_alert"><i class="fa fa-hand-o-right text-danger" aria-hidden="true"></i>Read all Instructions Carefully </p>
                                  <p class="card-text text-danger panel_alert"><i class="fa fa-hand-o-right text-danger" aria-hidden="true"></i> Sample CSV file  <a href="{{ asset("school/staff_format.csv") }}">Download</a> </p>

                                  <p class="card-text text-danger panel_alert"><i class="fa fa-hand-o-right text-danger" aria-hidden="true"></i>Religion Types [hinduism,islam,christianity,buddhism] </p>

                                  <p class="card-text text-danger panel_alert"><i class="fa fa-hand-o-right text-danger" aria-hidden="true"></i>Bloodgroup Types [A+, A-, B+, B-, O+, O-,AB+] </p>

                                  <p class="card-text text-danger panel_alert"><i class="fa fa-hand-o-right text-danger" aria-hidden="true"></i>Date Format [10/25/1998 - d/m/y] </p>
                                  <p class="card-text text-danger panel_alert"><i class="fa fa-hand-o-right text-danger" aria-hidden="true"></i>Gender Types [male,female,other] </p>
                                <p class="card-text text-danger panel_alert"><i class="fa fa-hand-o-right text-danger" aria-hidden="true"></i>Download the sample CSV File and fill Staff Data(Staff Upload Accept only CSV File format) </p>
                                <p class="card-text text-danger panel_alert"><i class="fa fa-hand-o-right text-danger" aria-hidden="true"></i> Don't Change or add new header values in dowloaded CSV File </p>
                                <p class="card-text text-danger panel_alert"><i class="fa fa-hand-o-right text-danger" aria-hidden="true"></i> Staff Email,Mobile should be in unique</p>
                               
                                <p class="card-text text-danger panel_alert"><i class="fa fa-hand-o-right text-danger" aria-hidden="true"></i> in CSV File 0 represent NO , 1 represent YES </p>
                                
                              </div>
                            </div>
                          </div>
                        
                          
                        </div>
                   
                   
                   
                    {{-- <a href="{{ route("section.create") }}" class="btn btn-primary">Create Section</a>
                    <a href="{{ route("subject.create") }}" class="btn btn-primary">Create Subject</a> --}}
                  </div>
                </div>
             
             {{-- //next column  --}}
          </div>
           
            </div>
        </div>
      

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
                styling: 'bootstrap3'
            })
        }
    window.sectionurl="{{ route('section.index') }}";
    window.teacherurl="{{ route('teacher.index') }}";
    window.subjecturl="{{ route('subject.index') }}";
    window.checkassign="{{ route('classteacher.index') }}";

    AcademicConfig.Subjectmapping(notify_script)
</script>
@endsection

@section('script_link')

    <!-- validator -->
    {!! Cms::script("theme/vendors/validator/validator.js") !!}

@endsection
