@extends('layout::admin.master')

@section('title','submit')
@section('style')

<style>
    .homework__data{
        text-align: center
    }
    .attachment a:hover{
        color: white
    }
</style>
@endsection
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('homeworksubmissionsSubmit'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'homework-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('homeworksubmissionsSubmit',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
        <div class="box-header with-border mar-bottom20">
            

            

            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Submit', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_homework' , 'class' => 'btn btn-success')) }}

            <a class="btn btn-info" href="{{route('homework.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

            {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger']) }}

        </div>
           @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Submit  homework" : "Submit  homework"])

             <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Submit Homework</h5>
                    <hr/>

                    <div class="homework__data">
                        <h4>{{ @$homework->title }}</h4>
                        <p>{{ @$homework->class->name }} / {{ @$homework->section->name }} </p>

                        <span>Last date for Submission : {{ @$homework->submission_date }}</span>
                    </div>

                    <h6 class="card-title">Homework Description</h6>
                    <hr/>

                    <div class="homework__description">
                        {!! @$homework->homework_description !!}
                    </div>

                    <div class="attachment">
                        <a href="{{ @$homework->attachment }}" target="_blank" class="badge bg-dark">View Attachment</a>
                    </div>

                    <div class="spacer"></div>

                    <div class="submit__homework">
                        <input type="hidden" name="homework_id" value="{{ @$homework->id }}"/>
                        <input type="hidden" name="subject_id" value="{{ @$homework->subject_id }}"/>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="formFile" class="form-label">Attach File</label>
                                <input class="form-control" type="file" id="formFile" name="attachment" accept=".png,.jpg,.jpeg,.pdf,.docx">
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="notes" class="form-label">Remarks / Notes</label>
                            <textarea class="form-control" id="notes" name="remark" placeholder="type..." rows="3"></textarea>
                        </div>
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
                styling: 'bootstrap3'
            })
        }
    window.sectionurl="{{ route('section.index') }}";
    window.subjecturl="{{ route('subject.index') }}";

   
   

   
</script>
@endsection

@section("script_link")

    <!-- validator -->

    {!! Cms::script("theme/vendors/validator/validator.js") !!}
    {!! Cms::script("theme/vendors/validator/validator_form.js") !!}
   
   
   
@endsection
