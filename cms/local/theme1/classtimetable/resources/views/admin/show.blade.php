@extends('layout::admin.master')

@section('title','classtimetable')
@section('style')


@endsection
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('classtimetable.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'classtimetable-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('classtimetable.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
        <div class="box-header with-border mar-bottom20">
           
            {{-- {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_classtimetable' , 'class' => 'btn btn-success btn-sm m-1  px-3 ')) }} --}}

            @if (@$layout == "create")  

            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save-Continue', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat_continue' , 'value' => 'Edit_wallet' , 'class' => 'btn btn-dark btn-sm m-1  px-3')) }}

            @endif
           

           

             <a class="btn btn-info btn-sm m-1  px-3" href="{{route('classtimetable.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

              {{-- {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }} --}}

              

             
        </div>
           @include("layout::admin.breadcrump",['route'=> "View Timetable"])

             <div class="card">
                <div class="card-body">
                    <h5 class="card-title">View classtimetable</h5>
                    <hr/>
                   
                </div>
                <div class="getcalender">
                    @include("classtimetable::admin.parts.calender",["days" => $days,
                    "timing" => $timing,'subjects'=>$subjects,'type'=>'show'])
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
