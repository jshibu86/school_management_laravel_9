@extends('layout::admin.master')

@section('title','subject')
@section('style')
<style>
     .participants_field .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__rendered{
        height:60px !important;
     }
     /* Hides the calendar icon */
    input[type="date"]::-webkit-calendar-picker-indicator {
        display: none;
        -webkit-appearance: none;
    }

   

    
</style>

@endsection
@section('body')
    <div class="x_content">
       

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('virtualcomunication.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'virtualcomunication-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('virtualcomunication.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
        <div class="box-header with-border mar-bottom20">
           
           
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_subject' , 'class' => 'submit_edit_btn btn btn-success btn-sm m-1  px-3','style'=>'display:none;')) }}

            <button type="button" class="btn btn-warning" id="click_btn">Generate Meeting Token</button>
          

            <a class="btn btn-info btn-sm m-1  px-3" href="{{route('virtualcomunication.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

            {{-- {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }} --}}


           

          
            @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit Virtual Meeting" : "Create Virtual Meeting"])
        </div>
       
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Create a new Virtual Meeting</h5>
                <hr/>
                <div class="my-5 container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-xs-12 col-sm-4 col-md-4 mb-4" id="token_div" style="display:none;">
                            <div class="item form-group">
                                <label class="control-label margin__bottom" for="meeting_token">Meeting Token<span class="required">*</span>
                                </label>
                                <div class="feild">
                                {{Form::text('meeting_token',@$data->name,array('id'=>"meeting_token",'class'=>"form-control-lg form-control rounded-pill" ,
                                'required'=>"required",'readonly'=>'readonly'))}}
                                </div>
                            </div>
                        </div>
                    </div>  
                    <div class="row">
                     
                            <div class="col-xs-12 col-sm-4 col-md-4 mb-4">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="meeting_title">Meeting Title<span class="required">*</span>
                                    </label>
                                    <div class="feild">
                                    {{Form::text('meeting_title',@$data->name,array('id'=>"meeting_title",'class'=>"form-control-lg form-control rounded-pill" ,
                                    'placeholder'=>"e.g English",'required'=>"required"))}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-4 mb-4">
                                <div class="item form-group">
                                        <label class="control-label margin__bottom" for="status">Meeting Date <span class="required">*</span>
                                        </label>                               
                                        <div class="feild">
                                            {{ Form::text('meeting_date',@$start_date ,
                                            array('id'=>'meeting_date','class' => 'rounded-pill form-control-lg form-control meetdate',"placeholder"=>"select meeting date" )) }}
                                        </div>                              
                                </div>
                            </div>  
                            <div class="col-xs-12 col-sm-4 col-md-4 mb-4">
                                <label for="meet_time" class="control-label margin__bottom">Meeting Time <span>*</span></label>
                                <div class="item form-group  ">
                                    <input type="time" required
                                        class="form-control form-control-lg rounded-pill meet_time" name="meet_time"
                                        id="meet_time"
                                        value="{{ date('H:i', strtotime(@$data->meet_time)) }}" />
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 mb-4">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="meeting_type">Meeting Type  <span class="required">*</span>
                                    </label>
                                    <div class="feild ">
                                        {{ Form::select('meeting_type',@$meeting_types,@$data->meeting_type,
                                        array('id'=>'meeting_type','class' => ' form-control form-select-lg single-select','data-type'=>'edit','required' => 'required' ,'placeholder'=>'Select Meeting Type')) }}
                                    </div>
                                </div>
                           </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 mb-4">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="participants">Participant Group  <span class="required">*</span>
                                    </label>
                                    <div class="feild ">
                                        {{ Form::select('participants_group',@$groups,@$data->group,
                                        array('id'=>'participants_group','class' => ' form-control form-select-lg single-select','data-type'=>'edit','required' => 'required' )) }}
                                    </div>
                                </div>
                           </div>
                           <div class="col-xs-12 col-sm-12 col-md-6 mb-4 stud_div"style="display:none;">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="class_id">Class  <span class="required">*</span>
                                    </label>
                                    <div class="feild ">
                                        {{ Form::select('class_id',@$class,@$data->class,
                                        array('id'=>'class_id','class' => ' form-control form-select-lg single-select','data-type'=>'edit','placeholder'=>'Select a class','required' => 'required' )) }}
                                    </div>
                                </div>
                           </div>
                           <div class="col-xs-12 col-sm-12 col-md-6 mb-4 stud_div" style="display:none;">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="section">Section  <span class="required">*</span>
                                    </label>
                                    <div class="feild ">
                                        {{ Form::select('section',[],@$data->section,
                                        array('id'=>'section','class' => ' form-control form-select-lg single-select','data-type'=>'edit','required' => 'required' )) }}
                                    </div>
                                </div>
                           </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 mb-4">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="participants">Add Participant  <span class="required">*</span>
                                    </label>
                                    <div class="feild participants_field">
                                       
                                        {{ Form::select('participants[]',@$users,@$data->school_type ,
                                        array('id'=>'participants','class' => ' form-control form-select-lg multiple-select','style'=>'padding: 15px !important;','required' => 'required','multiple'=>'multiple' )) }}
                                    </div>
                                </div>
                           </div>
                           <div class="col-xs-12 col-sm-12 col-md-12 mb-4">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="meeting_description">Meeting Description <span class="required">*</span>
                                    </label>
                                    <div class="feild">
                                        {{ Form::textarea('meeting_description',@$data->meeting_description ,
                                        array('id'=>'meeting_description','class' => 'rounded form-control','style'=>'border-radius:22.41px !important;','required' => 'required','rows'=>'4' )) }}
                                    </div>
                                </div>
                           </div>
                        </div>    
                    </div>
                    
                       
                        <!-- //status -->
                       
                </div>
            </div>
        </div>   
        {{Form::close()}}
    </div>

        
    

@endsection
@section('script')
    <script type="module">
        function notify_script(title, text, type, hide) {
            new PNotify({
                title: title,
                text: text,
                type: type,
                hide: hide,
                styling: 'fontawesome'
            })
        }
        window.getparticipantssections = "{{route('participants_sections')}}";
        window.getparticipants = "{{ route('get_participants') }}";
        window.getparticipantgroups = "{{ route('get_participant_groups') }}";
        VirtualCommunicationConfig.VirtualCommunicationInit(notify_script);

        document.getElementById('meeting_date').addEventListener('paste', function(e) {
            e.preventDefault(); // Prevent the default paste action
        });
    </script>
@endsection
@section('script_link')

    <!-- validator -->
    {!! Cms::script("theme/vendors/validator/validator.js") !!}
    {!! Cms::script("theme/vendors/validator/validator_form.js") !!}

@endsection