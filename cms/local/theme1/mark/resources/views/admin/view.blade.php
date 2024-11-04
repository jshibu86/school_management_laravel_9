@extends('layout::admin.master')

@section('title','mark')
@section('style')

@endsection

<link rel="stylesheet" href="{{asset('assets/backend/css/attendance.css')}}">
<style>
    thead{
        background-color: #212529;
        color: white;
    }
    th{
        color: white !important;
        border:1px solid #ededed !important;
        text-align: center !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        top: 60% !important;
        border-color: #343a40 transparent transparent transparent !important;
        border-style: solid;
        border-width: 5px 4px 0 4px;
        width: 0;
        height: 0;
        left: 50%;
        margin-left: -4px;
        margin-top: -2px;
        position: absolute;
    }
    .select2-container .select2-selection--single .select2-selection__rendered{
        font-size: 1rem;
        color: black !important;
   }
   .btn-primary {
    color: #fff;
    background-color: #BD02FF !important;
    border-color: #BD02FF !important;
   }
</style>

@section('body')
    <div class="x_content">
        <div class="box-header with-border mar-bottom20">
            <a class="btn btn-info btn-sm m-1  px-3" href="{{route('mark.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>
        </div>
        @include("layout::admin.breadcrump",['route'=> $layout == "view" ?"View Mark Entry" : "View Mark Entry"])


     <div class="card">   
        <div class="card-body">
            <h1 class="accordion-header" id="headingOne">               
                Mark Entry Information       
            </h1>
            <div class="row">
                <div class="exam_information w-75 mx-auto text-center">          
                    <p>Academic Year : {{ str_replace("-", "/", @$data->academicyear->year) }}</p>
                    <p>Term : {{ @$data->term->exam_term_name }}</p>
                    <p>Class&Section :{{ @$data->class->name }}.{{ @$data->section->name }} </p>                 
                    <p>Subject : {{ @$data->subject->name }}</p>
                </div>
            </div>
        </div>
     </div>    
     
     <div class="card">
        <div class="card-body">
           
          
            

            {{-- add mark students info --}}

            <div class="col-xs-12">
                <div class="row">
                   

                    <div class="container">
                        <table class="table table-stripped table-responsive">
                            <thead>
                               
                                <tr>
                                    <th>Sl.No</th>
                                    <th>Image</th>
                                    <th>Student Name</th>
                                    <th>Reg No</th>
                                    {{-- Loop through distribution data to display headers --}}
                                   
                                    @foreach (@$distribution_head as $key => $distribution )
                                    @foreach (@$distribution as $key => $mark )
                                    <th>{{ $mark->distributionname }}({{ $mark->originalmark }})</th>
                                    @endforeach
                                    @endforeach
                                    <th>Total</th>
                                    <th>Grade</th>
                                    <th>Point</th>
                                    <th>Remark</th>
                                </tr>
                               
                            </thead>
                            <tbody>
                                {{-- Loop through mark entry data --}}
                                @foreach ($mark_entry_data as $index => $data)
                                    {{-- Fetch the student associated with this mark entry --}}
                                  
                                    
                                        <tr> 
                                            
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td><img src="{{ asset($data->students->image ? $data->students->image : 'assets/images/default.jpg') }}" class="img-fluid stu_profile" width="50px" /></td>
                                            <td>{{ $data->students->first_name }}</td>
                                            <td>{{ $data->students->reg_no }}</td>
                                            {{-- Loop through distribution data to display marks --}}
                                            @foreach (@$distribution_head as $key => $distribution )
                                            @foreach (@$distribution as $key => $mark )
                                              @php
                                                  $distri_name = $mark->distributionname;
                                              @endphp
                                            <td>{{$data->$distri_name}}</td>
                                            @endforeach
                                            @endforeach
                                          
                                         
                                            
                                            <td>{{ $data->total_mark }}</td>
                                            <td>{{ $data->grade }}</td>
                                            <td>{{ $data->point }}</td>
                                            <td>{{ $data->remark }}</td>
                                        </tr>
                                        
                                 
                                @endforeach
                            </tbody>
                        </table>
                </div>  
            </div>
        </div>    
    </div>  
  
@endsection

@section("scripts")
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
        ExamConfig.examinit(notify_script);
    </script>

@endsection

    
