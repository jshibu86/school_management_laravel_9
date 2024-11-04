@extends('layout::admin.master')

@section('title','admissionform')
@section('style')
    <!-- Datatables -->
    @include('layout::admin.head.list_head')
    <style>
        .table-div table {
            width: 100% !important;
        }
    </style>
@endsection
        
@section('body')
<!-- 
    @php
        $is_super_admin = (User::isSuperAdmin()!=false) ? true : false;
    @endphp -->
        <div class="card">
            <div class="card-body">
                {{ Form::open(array('role' => 'form', 'route'=>array('admissionform.store'), 'method' => 'post', 'class' => 'form-horizontal form-label-left', 'id' => 'role-form')) }}
                    <div class="card-title btn_style">            
                        <h4 class="mb-0">Admission Form : Menu Settings</h4>
                            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;
                                Save Settings', array('type' => 'submit', 'id' => 'submit_btn', 'name' => '' , 'value' => 'role_save' , 'class' => 'btn btn-primary  px-3')) }}
                    </div>
            </div>
            <hr/>               
            <div class="nav flex-column nav-pills" id="v-pills-tab"  role="tablist" aria-orientation="vertical" >                    
                @csrf                                                                   
                <div class="container">
                    <div class="row">                           
                        @foreach ($items as $key =>$item)      
                            <div class="col-md-3">
                                <div class="p-2 mb-1">
                                    <label for="statusCheckbox{{ $item }}">{{ $formattedColumns[$key] }}</label>
                                        <div class="form-check form-switch">
                                            <input type="checkbox" name="{{ $item }}" class="form-check-input" data-id="{{ $item }}"  id="statusCheckbox{{ $item }}"  value="1" {{ in_array($item , $is_active ) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="statusCheckbox{{ $item }}"></label>                                                    
                                            <input type="hidden" name="label[]" value="{{ $item }}">                            
                                        </div>
                                </div>
                            </div>                            
                        @endforeach
                        <div class="row">    
                            <div class="col-8 col-md-4 mb-5" >
                                <label class="form-check-label" for="alert_msg">NOTE:</label>  
                                <textarea class="form-control" id="SchoolName" name="alert_msg" placeholder="">{{ isset($dataRecord['alert_msg']) ? $dataRecord['alert_msg'] : '' }}</textarea>                                
                            </div>                            
                        </div>
                    </div>   
                </div>
            </div>            
        </div>    
    {{ Form::close() }}
@endsection
@section('script')
        
    <!-- <script type="module">        
        function notify_script(title, text, type, hide) {
            new PNotify({
                title: title,
                text: text,
                type: type,
                hide: hide,
                styling: 'fontawesome'
            })
        }
       window.update_status = "{{route('update_status')}}";
       AdmissionFormConfig.AdmissionFormInit(notify_script);
        
    </script> -->

@endsection
