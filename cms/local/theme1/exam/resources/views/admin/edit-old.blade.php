@extends('layout::admin.master')

@section('title','exam')
@section('style')
<link rel="stylesheet" href="{{asset('assets/backend/css/question.css')}}">
<style>
 .type_content{
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 20px;
 }
 .types{
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
            {{ Form::open(array('role' => 'form', 'route'=>array('exam.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'exam-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('exam.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
        <div class="box-header with-border mar-bottom20">
           
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_exam' , 'class' => 'btn btn-success btn-sm m-1  px-3 ')) }}

            @if (@$layout == "create")  

            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save-Continue', array('type' => 'submit', 'id' => 'submit_btn_', 'name' => 'submit_cat_continue' , 'value' => 'Edit_wallet' , 'class' => 'btn btn-dark btn-sm m-1  px-3')) }}

            @endif
           

           

             <a class="btn btn-info btn-sm m-1  px-3" href="{{route('exam.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

              {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }}

              

             
        </div>
           @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit exam" : "Create Exam"])

             <div class="card">
                <div class="card-body">
                    <div class="container py-5">
                        <div class="row">
                            <div class="col-md-12">
                
                                <h5 class="mb-3 main_head"><b>Questions</b></h5>
                                
                                <ul class="nav nav-pills mb-3 ques_tab" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="tab1-tab" data-bs-toggle="pill" href="#tab1" role="tab" aria-controls="tab1" aria-selected="true">Exam Configuration</a>
                                        
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link " id="tab2-tab" data-bs-toggle="pill" href="#tab2" role="tab" aria-controls="tab1" aria-selected="true">Notifications</a>
                                     
                                    </li>
                                    <li class="nav-item">

                                        <a class="nav-link " id="tab3-tab" data-bs-toggle="pill" href="#tab3" role="tab" aria-controls="tab1" aria-selected="true">Questions</a>
                                      
                                    </li>
                                </ul>
                
                
                                <div class="tab-content mt-4">
                                    
                                    <div class="tab-pane fade active show" id="tab1"  role="tabpanel" aria-labelledby="tab1-tab">
                
                                            <div class="row">
                                                <div class="col-md-6">
                
                                                    <div class="d-flex align-items-center mb-4 ">
                                                        <label for="acc_yr" class="col-4 main_label mb-0">Academic Year</label>
                                                        <div class="item form-group col-8">
                                                        {{ Form::select('academic_year',@$academic_years,@$data->academic_year ,
                                                        array('id'=>'acyear','class' => 'single-select  form-control','required' => 'required',"placeholder"=>"Select Academic Year" )) }}
                                                        </div>
                                                       
                                                    </div>
                
                                                    <div class="d-flex align-items-center mb-4">
                                                        <label for="exam_type" class="col-4 main_label mb-0">Exam Type</label>
                                                        <div class="item form-group col-8">
                                                        {{ Form::select('exam_type',@$exam_types,@$data->exam_type ,
                                                        array('id'=>'exam_type','class' => 'single-select form-control','required' => 'required',"placeholder"=>"Select Exam Type" )) }}
                                                        </div>
                                                    </div>
                                                    <div class="d-flex align-items-center mb-4">
                                                        <label for="class_id" class="col-4 main_label mb-0">Class</label>
                                                        <div class="item form-group col-8">
                                                        {{ Form::select('class_id',@$class_lists,@$data->class_id ,
                                                         array('id'=>'class_id','class' => 'single-select form-control','required' => 'required',"placeholder"=>"Select Class" )) }}
                                                        </div>
                                                    </div>
                                                    <div class="d-flex align-items-center mb-4">
                                                        <label for="class_id" class="col-4 main_label mb-0">Section</label>
                                                        <div class="item form-group col-8">
                                                        {{ Form::select('section_id',@$section_lists,@$data->section_id ,
                                                         array('id'=>'section_id','class' => 'single-select form-control','required' => 'required',"placeholder"=>"Select Section" )) }}
                                                        </div>
                                                    </div>
                                                    <div class="d-flex align-items-center mb-4">
                                                        <label for="class_id" class="col-4 main_label mb-0">Subject</label>
                                                        <div class="item form-group col-8">
                                                        {{ Form::select('subject_id',@$subject_lists,@$data->subject_id ,
                                                         array('id'=>'subject_id','class' => 'single-select form-control','required' => 'required',"placeholder"=>"Select Subject" )) }}
                                                        </div>
                                                    </div>
                
                                                    <div class="d-flex align-items-center mb-4">
                                                        <label for="dep" class="col-4 main_label mb-0">Department</label>
                                                        <div class="item form-group col-8">
                                                        {{ Form::select('department_id',@$department,@$data->department_id ,
                                                         array('id'=>'department_id','class' => 'single-select form-control','required' => 'required',"placeholder"=>"Select Department" )) }}
                                                        </div>
                                                    </div>
                
                                                    
                
                                                    <div class="d-flex align-items-center mb-4">
                                                        <label for="maxmark" class="col-4 main_label mb-0">Max Mark</label>
                                                        <div class="item form-group col-8">
                                                        <input type="text" required value="{{ @$data->max_mark }}" id="maxmark" name="max_mark" class="form-control ques_ip" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" />
                                                        </div>
                                                    </div>
                
                                                    <div class="d-flex align-items-center mb-4">
                                                        <label for="minmark" class="col-4 main_label mb-0">Min Mark</label>
                                                        <div class="item form-group col-8">
                                                        <input type="text" required value="{{ @$data->min_mark }}" id="minmark"  name="min_mark" class="form-control ques_ip" />
                                                        </div>
                                                    </div>
                
                                                 
                
                                                </div>
                
                                                <div class="col-md-6">
                
                                                    
                
                                                    <div class="d-flex align-items-center mb-4">
                                                        <label for="student" class="col-4 main_label mb-0">Include Students</label>
                                                        <div class="col-8">
                                                            {{ Form::select('include_students[]',@$include_students,explode(",",@$data->include_students) ,
                                                            array('id'=>'include_students','class' => 'multiple-select form-control',"multiple"=>true )) }}
                                                        </div>
                                                       
                                                    </div>
                
                                                    <div class="d-flex align-items-center mb-4">
                                                        <label for="excstu" class="col-4 main_label mb-0">Exclude Students</label>
                                                        <div class="col-8">
                                                            {{ Form::select('exclude_students[]',@$exclude_students,explode(",",@$data->exclude_students) ,
                                                            array('id'=>'exclude_students','class' => 'multiple-select form-control',"multiple"=>true )) }}
                                                        </div>
                                                      
                                                    </div>
                
                                                    <div class="d-flex align-items-center mb-4">
                                                        <label for="exdate" class="col-4 main_label mb-0">Date</label>
                                                        <div class="item form-group col-8 ">
                                                        <input type="date" required id="exdate" class="form-control  ques_ip" name="exam_date" value="{{ @$data->exam_date }}" />
                                                        </div>
                                                    </div>
                
                                                    <div class="d-flex align-items-center mb-4">
                                                        <label for="extime" class="col-4 main_label mb-0">Time</label>
                                                        <div class="item form-group col-8 ">
                                                        <input type="time" required id="extime" class="form-control  ques_ip" name="exam_time" value="{{ @$data->exam_time }}" />
                                                        </div>
                                                    </div>
                
                                                    <div class="d-flex align-items-center mb-4">
                                                        <label class="col-4 main_label mb-0">Promotion</label>
                                                        <input type="radio" id="promotionyes" name="promotion" class="qus_radio" {{ @$data->promotion == "on" ? "checked" : "" }} />
                                                        <label for="promotionyes" class="main_label mb-0 mr-4">Yes</label>
                                                        <input type="radio" id="promotionno" name="promotion" class="qus_radio"  {{ @$data->promotion == "on" ? "checked" : "" }}/>
                                                        <label for="promotionno" class="main_label mb-0">No</label>
                                                    </div>

                                                    
                                                    
                                                    <div class="d-flex align-items-center mb-4 promotionbox">
                                                        <label for="percentage" class="col-4 main_label mb-0">Percentage</label>
                                                        <input type="text" id="percentage" class="form-control ques_ip " name="exam_percentage" value="{{ @$data->exam_percentage }}" />
                                                    </div>
                                                    <div class="d-flex align-items-center mb-4">
                                                      
                                                            
                                                        <label for="minmark" class="col-4 main_label mb-0">Time Line</label>
                                                        <div class="hr-time-pickers">
                                                            <div class="picked-time-wrapper">
                                                                <input type="text" class="picked-time" name="timeline" value="{{ @$data->timeline }}">
                                                            </div>
                                                            <div class="pick-time-now">
                                                                <div class="hours hr-timer">
                                                                    <div class="movable-area">
                                                                        <ul></ul>
                                                                    </div>
                                                                </div>
                                                                <div class="minutes hr-timer">
                                                                    <ul></ul>
                                                                </div>
                                                            </div>
                                                        </div>   
                                                    </div>
                
                                                </div>
                                            </div>
                                      
                                    </div>
                
                                    <div class="tab-pane" id="tab2"  role="tabpanel" aria-labelledby="tab2-tab">
                
                                       
                                            <div class="row">
                                                <div class="col-md-4">
                
                                                    <div class="mb-4">
                                                        <label for="notifydate" class="main_label mb-0">Date</label>
                                                       
                                                        <input type="date" id="notifydate" class="form-control ques_ip" value="{{ @$data->notification->notify_date }}" name="notify_date" />
                                                    </div>
                
                                                    <div class="mb-4">
                                                        <label for="notifytime" class="main_label mb-0">Time</label>
                                                        <input type="time" id="notifytime" class="form-control ques_ip" value="{{ @$data->notification->notify_time }}" name="notify_time" />
                                                    </div>
                
                                                    <div class="mb-4">
                                                        <label for="notifymsg" class="main_label mb-0">Message</label>
                                                        <textarea rows="6" id="notifymsg" class="form-control ques_ip" name="notify_message">{{ @$data->notification->notify_message }}</textarea>
                                                    </div>
                
                                                </div>
                                            </div>
                                          
                                    </div>
                
                                    <div class="tab-pane" id="tab3"  role="tabpanel" aria-labelledby="tab3-tab">

                                        @if (@$layout =="create")
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="type_content">
                                                    <span>Select Question Type : </span>
                                                    <div class="types">
                                                        <input type="radio" class="selecttype" name="type" value="question"/>
                                                        <label>create question</label>
                                                    </div>

                                                    <div class="types">
                                                        <input type="radio" class="selecttype" name="type" value="upload"/>
                                                        <label>upload question</label>
                                                    </div>
                                                   
                                                 
                                                </div>
                                            </div>
                                        </div>
                                        @endif

                                        @if (@$layout == "create")

                                        <div class="row mt-4 createquestiontab" style="display:
                                        none">
                                        @else

                                        @if (@$data->uploaded_file == null)
                                        <div class="row mt-4 createquestiontab" >
                                        @else

                                        <div class="row mt-4 createquestiontab" style="display:
                                            none">
                                            
                                        @endif


                                            
                                        @endif
                
                                            
                                                <div class="col-md-12">
                                                    <ul class="nav nav-pills ques_tab allqus_tab" id="myTab-" role="tablist">

                                                       
                                                        <li class="nav-item">
                                                            <a class="nav-link active " id="tabc-tab" data-bs-toggle="pill" href="#online-tab" role="tab" aria-controls="online-tab" aria-selected="true">Create Question</a>
                                                         
                                                        </li>
                                                       
                                                      
                                                        
                                                    </ul>

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                          
                                                            <div class="d-flex justify-content-between">
                                                                <button type="button" class="btn qus-btns" id="fillintheblanks">Fill in the Blanks</button>
                                                                <button type="button" class="btn qus-btns" id="choosethebestans">Choose the Best Answer</button>
                                                                <button type="button" class="btn qus-btns" id="typequs">Define Type Questions</button>
                                                                <button type="button" class="btn qus-btns" id="shortqus">Short Questions</button>
                                                                <button type="button" class="btn qus-btns" id="longqus">Long Questions</button>
                                                            </div>
                                                         
                                                            
                                                        </div>
                                                    </div>
                
                                                    <div class="tab-content mt-4">
                                                        @if (@$layout =="create")
                                                        <div class="tab-pane fade active show" id="online-tab"  role="tabpanel" aria-labelledby="tabc-tab">
                                                        @else
                                                        <div class="tab-pane fade {{ @$data->uploaded_file == null ? "active show" : "" }}" id="online-tab"  role="tabpanel" aria-labelledby="tabc-tab">
                                                            
                                                        @endif
                                                      
                                                            @if (@$layout == "edit")
                                                            <div id="all_qus">
                                                                @php
                                                                    $total=0;
                                                                @endphp
                                                                @foreach (@$data->questions as$key=> $question )
                                                                @php
                                                                    $total=$total+$question->mark;
                                                                @endphp
                                                                @if($question->question_type == "fillintheblanks")
                                                                <div class="tab_row mb-3"><div class="row align-items-end"><div class="col-md-6"><label ><span><span class="inc_span">{{ @$key+1 }}</span>:</span>Question </label><input type="text" name="fillblanks[{{ @$loop->index }}][{{ @$question->order }}][]" value="{{ @$question->question }}" id="{{ @$loop->index }}fillques" class="form-control ques_ip" /></div><div class="col-md-3"><label >Answer</label><input type="text" name="fillblanks[{{ @$loop->index }}][{{ @$question->order }}][]" value="{{ @$question->answer }}" id="{{ @$loop->index }}fillan" class="form-control ques_ip" /></div><div class="col-md-2"><label >Mark</label><input type="text" value="{{ @$question->mark }}" name="fillblanks[{{ @$loop->index }}][{{ @$question->order }}][]" id="{{ @$loop->index }}fillmark" class="form-control ques_ip mark_cls" /></div><div class="col-md-1"><button type="button" class="remove_ques btn btn-danger" onclick="ExamConfig.deletequestion({{ @$data->id }},{{ $question->id }})"><i class="fa fa-trash"></i></button></div></div></div>
                                                                 @endif
                                                                 @if($question->question_type == "choosebest")
                                                                 <div class="tab_row mb-3">
                
                                                                <div class="row align-items-end"><div class="col-md-7"><div class="row"><div class="col-md-12"><label>Question <span class="inc_span">{{ @$key+1}} </span></label><input type="text" name="choose_best[{{ @$loop->index }}][{{ @$question->order }}][]" value="{{ @$question->question }}" id="{{ @$loop->index }}" class="form-control ques_ip" /></div></div>
                                                                
                                                                <div class="row mt-3">

                                                                
                                                                 @foreach (explode(",",$question->options) as $answer)
                                                                 <div class="col-md-3 pr-0">
                                                                <div class="input-group"> <span class="input-group-text" id="inputGroup-sizing-default">A</span>
                                                                 <input type="text" name="choose_best[{{ @$key }}][{{ @$question->order }}][]" value="{{ @$answer }}" class="form-control ques_ip"  aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                                                                </div>
                                                                 </div>
                                                                 @endforeach   
                                                               
                        
                                                                 
                    
                                                                </div>
                    
                                                                 </div>
                    
                                                                <div class="col-md-3"><label for="ans_2">Answer</label><div class="ans_checkbox">
                                                                    @foreach (["A","B","C","D"] as $keyans => $ans)
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="choose_best[{{ @$key }}][{{ @$question->order }}][]" {{ @$question->answer == $keyans ? "checked":"" }}  value={{ @$keyans }}>
                                                                        <label class="form-check-label" >{{ @$ans }}</label>
                                                                    </div>
                                                                    @endforeach
                                                                   
                                                        </div></div>
                                                        <div class="col-md-1">
                                                        <label for="mark_1">Mark</label>
                                                        <input type="text" name="choose_best[{{ @$key }}][{{ @$question->order }}][mark][]"  value="{{ @$question->mark }}" class="form-control mark_ip_choose_best ques_ip mark_cls" placeholder="0"/>
                                                            </div>
                                                         <div class="col-md-1"><button type="button" class="remove_ques btn btn-danger" onclick="ExamConfig.deletequestion({{ @$data->id }},{{ $question->id }})"><i class="fa fa-trash"></i></button></div></div></div>
                                                                 @endif
                                                                 @if($question->question_type == "definequestion")
                                                                 <div class="tab_row py-3"><div class="row align-items-center"><div class="col-md-6"><div class="row"><div class="col-md-12"><p class="mb-2"><span><span class="inc_span">{{ @$key+1 }}</span>:</span>Upload Image</p><input type="file" name="define_type[{{ @$key }}][{{ @$question->order }}][]" value="{{ $question->attachment }}"  placeholder="Choose the file" class="form-control ques_ip file_ip imag" accept=".xlsx,.docx,.pdf,.txt,.jpeg,.gif,.jpg,.png,.mp4" /><label  class="upload_cls btn mt-3">Upload</label><p class="ch_span_">Choose the file... Ex:jpeg, gif, png, pdf</p></div><div class="col-md-12 mt-3"><label>Add Question</label><input type="text" name="define_type[{{ @$key }}][{{ @$question->order }}][question][]" value="{{ $question->question }}"  class="form-control ques_ip" /></div><div class="col-md-12 mt-3"><label >Answer</label><textarea rows="5" name="define_type[{{ @$key }}][{{ @$question->order }}][answer][]"class="form-control ques_ip">{{ @$question->answer }}</textarea></div><div class="col-md-12 mt-3"><label >Mark</label><input type="text" name="define_type[{{ @$key }}][{{ @$question->order }}][mark][]" value="{{ $question->mark }}"  class="form-control ques_ip mark_cls" /></div></div></div><div class="col-md-5"><label for="preview_3">Image Preview</label><div class="img_box">
                                                                @if (str_contains($question->attachment,"pdf"))
                                                                <img id="ImgPreview" src="{{ asset("assets/docs/pdf.png") }}" 
                                                                class="preview1" />
                                                                @else
                                                                
                                                                <img id="ImgPreview" src="{{ asset($question->attachment) }}" 
                                                                class="preview1" />
                                                                @endif
                                                               
                                                            
                                                                </div></div><div class="col-md-1"><button type="button" class="remove_ques btn btn-danger" onclick="ExamConfig.deletequestion({{ @$data->id }},{{ $question->id }})"><i class="fa fa-trash"></i></button></div></div></div>
                                                                 @endif
                                                                 @if($question->question_type == "shortquestion")
                                                                 <div class="tab_row mb-3"><div class="row align-items-end"><div class="col-md-9"><label><span><span class="inc_span">{{ @$key+1 }}</span>:</span>Question </label><input type="text" value="{{ $question->question }}" name="shortquestion[{{ $key }}][{{ $question->order }}][]" class="form-control ques_ip" /></div><div class="col-md-9 mt-3"><label >Answer</label><textarea rows="5" name="shortquestion[{{ $key }}][{{ $question->order }}][]"  class="form-control ques_ip">{{ $question->answer }}</textarea></div><div class="col-md-2"><label>Mark</label><input type="text" value="{{ $question->mark }}" name="shortquestion[{{ $key }}][{{ $question->order }}][]"  class="form-control ques_ip mark_cls" /></div><div class="col-md-1"><button type="button" class="remove_ques btn btn-danger" onclick="ExamConfig.deletequestion({{ @$data->id }},{{ $question->id }})"><i class="fa fa-trash"></i></button></div></div></div>
                                                                 @endif
                                                                 @if($question->question_type == "longquestion")
                                                                 <div class="tab_row mb-3"><div class="row align-items-end"><div class="col-md-9"><label><span><span class="inc_span">{{ @$key+1 }}</span>:</span>Question </label><input type="text" value="{{ $question->question }}" name="longquestion[{{ $key }}][{{ $question->order }}][]" id="{{ $key }}" class="form-control ques_ip" /></div><div class="col-md-9 mt-3"><label>Answer</label><textarea rows="10" name="longquestion[{{ $key }}][{{ $question->order }}][]" id="${idans5}" class="form-control ques_ip">{{ $question->answer }}</textarea></div><div class="col-md-2"><label for="${idmark5}">Mark</label><input type="text" name="longquestion[{{ $key }}][{{ $question->order }}][]" value="{{ $question->mark }}" class="form-control ques_ip mark_cls" /></div><div class="col-md-1"><button type="button" class="remove_ques btn btn-danger" onclick="ExamConfig.deletequestion({{ @$data->id }},{{ $question->id }})"><i class="fa fa-trash"></i></button></div></div></div>
                                                                 @endif
                                                                @endforeach
                                                            </div>

                                                            @else
                                                            <div id="all_qus">
                                                            </div>
                                                            @endif
                                                           
                                                            <div class="row justify-content-end">
                                                                <div class="col-md-2">
                                                                    <label for="totalmark">Total Mark</label>
                                                                    @if (@$layout=="edit")
                                                                    <input type="text" readonly id="totalmark"  value="{{ $total }}" class="form-control ques_ip" onkeyup="totalvalidate();" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" />
                                                                    @else
                                                                    <input type="text" readonly id="totalmark"  class="form-control ques_ip" onkeyup="totalvalidate();" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" />
                                                                    @endif
                                                                  
                                                                </div>
                                                                <div class="col-md-1">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        @if (@$layout =="create")
                                                        <div class="tab-pane" id="image-tab"  role="tabpanel" aria-labelledby="imgtab">
                                                        @else

                                                        <div class="tab-pane {{ @$data->uploaded_file !=null ? "active" :"" }}" id="image-tab"  role="tabpanel" aria-labelledby="imgtab">
                                                        @endif
                
                                                      
                
                                                            
                
                                                        </div>
                                                    </div>
                
                                                </div>
                                            </div>

                                        @if (@$layout == "create")

                                        <div class="row mt-4 uploadquestiontab" style="display:none">
                                        @else

                                        @if (@$data->uploaded_file == null)
                                        <div class="row mt-4 uploadquestiontab" style="display:none" >
                                        @else

                                        <div class="row mt-4 uploadquestiontab" >
                                            
                                        @endif


                                            
                                        @endif
                                           
                                                <div class="col-md-12">
                                                    <ul class="nav nav-pills ques_tab allqus_tab" id="myTab-" role="tablist">

                                                       
                                                        <li class="nav-item">
                                                            
                                                            <a href="#image-tab" data-bs-toggle="tab"  class="nav-link active" id="imgtab" role="tab" aria-controls="image-tab" aria-selected="false">Upload Question</a>
                                                        </li>
                                                       
                                                      
                                                        
                                                    </ul>
                
                                                    <div class="tab-content mt-4">
                                                       
                                                        <div class="tab-pane fade active show" id="online-tab"  role="tabpanel" aria-labelledby="tabc-tab">
                                                            
                                                       
                                                      
                                                          
                                                           
                                                            
                                                        </div>

                                                       

                                                        <div class="tab-pane active " id="image-tab"  role="tabpanel" aria-labelledby="imgtab">
                                                     
                
                                                      
                
                                                            <div class="tab_row">
                                                                <div class="row">
                                                                    
                                                                    
                                                                    <div class="col-md-6">
                                                                        <input type="file" id="upload_qus" class="form-control ques_ip fileuploadip imag" accept=".pdf,.jpeg,.jpg,.png" name="upload_question">
                                                                        <label for="upload_qus" class="upload_cls btn mt-3">Upload Question</label>


                                                                        @if (@$layout != "create")
                                                                        <div class="uploadquestion">
                                                                            <a href="{{ asset(@$data->uploaded_file) }}" target="_blank" class="uploadqus upload_cls btn mt-3" >View Document <i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
                                                                        </div>
                                                                        @endif

                                                                       
                                                                    </div>
                
                                                                    <div class="col-md-6">
                                                                        <div class="img_box">
                                                                            <img id="ImgPreview" src="" class="preview1" style="display: none;">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                
                                                        </div>
                                                    </div>
                
                                                </div>
                                            </div>

                                          
                                            
                                       
                
                                    </div>
                
                                </div>
                
                            </div>
                        </div>
                    </div>
                
                </div>
            </div>

        
       
       

        {{Form::close()}}
    </div>

@endsection

@section("scripts")


<script>
    var maxorder={!! json_encode(@$maxorder) !!};
     var ordercount=maxorder ?maxorder :0;
    $(document).ready(function() {
        $(".hr-time-picker").hrTimePicker({
            disableColor: "#989c9c", // red, green, #000
            enableColor: "#ff5722", // red, green, #000
            arrowTopSymbol: "&#9650;", // ▲ -- Enter html entity code
            arrowBottomSymbol: "&#9660;" // ▼ -- Enter html entity code
        });
    });
</script>
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
        window.subjecturl="{{ route('subject.index') }}";
        window.sectionurl="{{ route('section.index') }}";
        window.fetchstudents="{{ route('exam.index') }}"
        window.deletequestion="{{ route('exam.deletequestion') }}"

        ExamConfig.examinit(notify_script);

        

        

       
</script>

<script type="text/javascript">
	$(document).ready(function() {
        @if (@$layout=="edit")

       window.edit=false;

           
			findTotal();

			$(".remove_ques").on("click", function() {

				$(this).closest('.tab_row').remove();
				
				findTotal2();
				
			});
        @else
        window.edit=true;
            
        @endif
	    $('.selectacc_yr').select2({
			placeholder: '',
			width: '100%',
			allowHtml: true,
			allowClear: false,
			tags: true,
			closeOnSelect : true,
			selectOnClose: false,
	    });

	    $('.selectexam_type').select2({
			placeholder: '',
			width: '100%',
			allowHtml: true,
			allowClear: false,
			tags: true,
			closeOnSelect : true,
			selectOnClose: false,
	    });

	    $('.selectdep').select2({
			placeholder: '',
			width: '100%',
			allowHtml: true,
			allowClear: false,
			tags: true,
			closeOnSelect : true,
			selectOnClose: false,
	    });

	    $('.selectsub').select2({
			placeholder: '',
			width: '100%',
			allowHtml: true,
			allowClear: false,
			tags: true,
			closeOnSelect : true,
			selectOnClose: false,
	    });

	    $('.selectsec').select2({
			placeholder: '',
			width: '100%',
			allowHtml: true,
			allowClear: false,
			tags: true,
			closeOnSelect : true,
			selectOnClose: false,
	    });

	    $('.selectstudent').select2({
			placeholder: '',
			width: '100%',
			allowHtml: true,
			allowClear: false,
			tags: true,
			closeOnSelect : true,
			selectOnClose: false,
	    });

	    $('.selectexcstu').select2({
			placeholder: '',
			width: '100%',
			allowHtml: true,
			allowClear: false,
			tags: true,
			closeOnSelect : true,
			selectOnClose: false,
	    });
	});

</script>

<script>
	$(document).ready(function() {
	    $("#promotionno").click( function() {
	        $(".promotionbox").hide();
	    });
	    $("#promotionyes").click( function() {
	        $(".promotionbox").show();
	    });
	});
</script>

<script>
     function notify_script(title,text,type,hide) {
            new PNotify({
                title: title,
                text: text,
                type: type,
                hide: hide,
                styling: 'bootstrap3'
            })
        }
	function totalvalidate(){
       var total = parseInt(document.getElementById("totalmark").value);
       var max = parseInt(document.getElementById("maxmark").value);
       if(total > max) {
        notify_script('Error',"Total Mark must be lesser than or Equal to Maximum Mark.",'error',true)
                    
          
       } 
 	}

 	updateIndex = function() {
		$('.inc_span').each(function(i) {
            $(this).text(" ");
			$(this).text(i+1);
		});
	};

	function findTotal(){
		$(document).on('keyup', ".mark_cls", function() {
			var arr = document.getElementsByClassName('mark_cls');
			var tot=0;
			for(var i=0;i<arr.length;i++){
				if(parseFloat(arr[i].value))
				tot += parseFloat(arr[i].value);
			}
			

            document.getElementById('totalmark').value = tot;
           
			totalvalidate();
		});
	}

	function findTotal2(){
		var arr2 = document.getElementsByClassName('mark_cls');
		var tot2=0;
		for(var i=0;i<arr2.length;i++){
			if(parseFloat(arr2[i].value))
				tot2 += parseFloat(arr2[i].value);
		}
		document.getElementById('totalmark').value = tot2;
		totalvalidate();
	}

</script>


<!-- Qus 1 script -->

<script>

	$(document).ready(function() { 
       

		$("#fillintheblanks").on("click", function() {

            ordercount++;

			var idqus=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);
			var idans=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);
			var idmark=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);

			$("#all_qus").append(`<div class="tab_row mb-3"><div class="row align-items-end"><div class="col-md-6"><label for="${idqus}"><span><span class="inc_span"></span>&nbsp;.&nbsp;</span>Question </label><input type="text" name="fillblanks[${idqus}][${ordercount}][]" id="${idqus}" class="form-control ques_ip" /></div><div class="col-md-3"><label for="${idans}">Answer</label><input type="text" name="fillblanks[${idqus}][${ordercount}][]" id="${idans}" class="form-control ques_ip" /></div><div class="col-md-2"><label for="${idmark}">Mark</label><input type="text" name="fillblanks[${idqus}][${ordercount}][]" id="${idmark}" class="form-control ques_ip mark_cls" /></div><div class="col-md-1"><button type="button" class="remove_ques btn btn-danger"><i class="fa fa-trash"></i></button></div></div></div>`);
			updateIndex();
			findTotal();


			$(".remove_ques").on("click", function() {

				$(this).closest('.tab_row').remove();
				updateIndex();
				findTotal2();

			});

		}); 

	});  

</script>

<!-- Qus 2 script -->

<script>

	$(document).ready(function() { 

		$("#choosethebestans").on("click", function() {
            ordercount++;

			var idqus2=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);
			var idans2=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);
			var idmark2=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);

            $("#all_qus").append(`<div class="tab_row mb-3">
                
                <div class="row align-items-end"><div class="col-md-7"><div class="row"><div class="col-md-12"><label for="${idqus2}"> <span class="inc_span"></span>:Question</label><input type="text" name="choose_best[${idqus2}][${ordercount}][]" id="${idqus2}" class="form-control ques_ip" /></div></div><div class="row mt-3"><div class="col-md-3 pr-0"><div class="input-group"> <span class="input-group-text" id="inputGroup-sizing-default">A</span>
                 <input type="text" name="choose_best[${idqus2}][${ordercount}][]" class="form-control ques_ip" id="${idans2}" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default"></div>
                        
                        </div>
                    
                    <div class="col-md-3 pr-0">
                        
                        <div class="input-group"> <span class="input-group-text" id="inputGroup-sizing-default">B</span>
                 <input type="text" name="choose_best[${idqus2}][${ordercount}][]" class="form-control ques_ip" id="${idans2}" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default"></div>
                        
                        </div>
                    
                    <div class="col-md-3 pr-0">
                        
                        <div class="input-group"> <span class="input-group-text" id="inputGroup-sizing-default">C</span>
                 <input type="text" name="choose_best[${idqus2}][${ordercount}][]" class="form-control ques_ip" id="${idans2}" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default"></div>
                        
                        </div>
                    
                    <div class="col-md-3">
                        
                        <div class="input-group"> <span class="input-group-text" id="inputGroup-sizing-default">D</span>
                 <input type="text" name="choose_best[${idqus2}][${ordercount}][]" class="form-control ques_ip" id="${idans2}" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default"></div>
                        
                        </div>
                    
                    </div>
                    
                    </div>
                    
                    <div class="col-md-3"><label for="ans_2">Answer</label><div class="ans_checkbox">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="choose_best[${idqus2}][${ordercount}][]" checked id="${idans2}" value="0">
                                                                <label class="form-check-label" for="${idans2}">A</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="choose_best[${idqus2}][${ordercount}][]" id="${idans2}" value="1">
                                                                <label class="form-check-label" for="${idans2}">B</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="choose_best[${idqus2}][${ordercount}][]" id="${idans2}" value="2">
                                                                <label class="form-check-label" for="${idans2}">C</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="choose_best[${idqus2}][${ordercount}][]" id="${idans2}" value="3">
                                                                <label class="form-check-label" for="${idans2}">D</label>
                                                            </div>
                                                        </div></div>
                                                        <div class="col-md-1">
                                                        <label for="mark_1">Mark</label>
                                                        <input type="text" name="choose_best[${idqus2}][${ordercount}][mark][]" id="${idmark2}" class="form-control mark_ip_choose_best ques_ip mark_cls" placeholder="0"/>
                                                    </div>
                    <div class="col-md-1"><button type="button" class="remove_ques btn btn-danger"><i class="fa fa-trash"></i></button></div></div></div>`);
		
			
			updateIndex();
			findTotal();

			$(".remove_ques").on("click", function() {

				$(this).closest('.tab_row').remove();
				updateIndex();
				findTotal2();
				
			});

		}); 
	});  

</script>

<!-- Qus 3 script -->

<script>

	$(document).ready(function() { 

		$("#typequs").on("click", function() {
            ordercount++;

			var idqus3=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);
			var idans3=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);
			var fileid=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);
			var idmark3=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);

			$("#all_qus").append(`<div class="tab_row py-3"><div class="row align-items-center"><div class="col-md-6"><div class="row"><div class="col-md-12"><p class="mb-2"><span><span class="inc_span"></span>&nbsp;.&nbsp;</span>Upload Image</p><input type="file" name="define_type[${fileid}][${ordercount}][]" id="${fileid}" placeholder="Choose the file" class="form-control ques_ip file_ip imag" accept=".xlsx,.docx,.pdf,.txt,.jpeg,.gif,.jpg,.png,.mp4" /><span class="ch_span">Choose the file... Ex:jpeg, gif, png, pdf</span><label for="${fileid}" class="upload_cls btn mt-3">Upload</label></div><div class="col-md-12 mt-3"><label for="${idqus3}">Add Question</label><input type="text" name="define_type[${fileid}][${ordercount}][question][]" id="${idqus3}" class="form-control ques_ip" /></div><div class="col-md-12 mt-3"><label for="${idans3}">Answer</label><textarea rows="5" name="define_type[${fileid}][${ordercount}][answer][]" id="${idans3}" class="form-control ques_ip"></textarea></div><div class="col-md-12 mt-3"><label for="${idmark3}">Mark</label><input type="text" name="define_type[${fileid}][${ordercount}][mark][]" id="${idmark3}" class="form-control ques_ip mark_cls" /></div></div></div><div class="col-md-5"><label for="preview_3">Image Preview</label><div class="img_box"><img id="ImgPreview" src="" class="preview1" style="display: none;"/></div></div><div class="col-md-1"><button type="button" class="remove_ques btn btn-danger"><i class="fa fa-trash"></i></button></div></div></div>`);
			afterClick();
			updateIndex();
			findTotal();

			$(".remove_ques").on("click", function() {

				$(this).closest('.tab_row').remove();
				findTotal2();
				
			});

		}); 
	});  

</script>

<script>

	function afterClick()
	{
		function readIMG(input, imgControlName) {
			if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e) {
				$(imgControlName).attr('src', e.target.result);
			}
			reader.readAsDataURL(input.files[0]);
			}
		}

		$('.imag').change(function() {
			
			$(this).closest('.tab_row').find('.file_ip').css('color','#7b7b7b');
			$(this).closest('.tab_row').find('.ch_span').hide();

			var ext = $(this).closest('.tab_row').find(".imag").val().split('.').pop();

			if(ext == 'mp4'){

				$(this).closest('.tab_row').find(".img_box").html(`<video controls autoplay src="" class="vids"></video>`);

				var imgControlName = $(this).closest('.tab_row').find('.vids');
				readIMG(this, imgControlName);
				$(this).closest('.tab_row').find('.vidssrc').show();

			}
			else if (ext =="pdf" || ext =="xlsx" || ext =="docx") {

				$(this).closest('.tab_row').find(".img_box").html(`<img id="ImgPreview" src="" class="preview1" style="display: none;" type="application/pdf"/>`);

				$(this).closest('.tab_row').find('#ImgPreview').show();
				var image = $(this).closest('.tab_row').find('.preview1');

				switch (ext) {
					case 'pdf':
						image[0].src = "{{ URL::to('/') }}/assets/docs/pdf.png";
						break;
					case 'xlsx':
						image[0].src = "{{ URL::to('/') }}/assets/docs/xlsx.png";
						break;
					case 'docx':
						image[0].src = "{{ URL::to('/') }}/assets/docs/docimage.png";
						break;
				}
				
			}
			else{

				$(this).closest('.tab_row').find(".img_box").html(`<img id="ImgPreview" src="" class="preview1" style="display: none;" type="application/pdf"/>`);
				
				var imgControlName = $(this).closest('.tab_row').find('#ImgPreview');
				readIMG(this, imgControlName);
				$(this).closest('.tab_row').find('#ImgPreview').show();
			}
		});
	}
</script>

<!-- Qus 4 script -->

<script>

	$(document).ready(function() { 

		$("#shortqus").on("click", function() {
            ordercount++;

			var idqus4=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);
			var idans4=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);
			var idmark4=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);

			$("#all_qus").append(`<div class="tab_row mb-3"><div class="row align-items-end"><div class="col-md-9"><label for="${idqus4}"><span><span class="inc_span"></span>&nbsp;.&nbsp;</span>Question </label><input type="text" name="shortquestion[${idqus4}][${ordercount}][]" id="${idqus4}" class="form-control ques_ip" /></div><div class="col-md-9 mt-3"><label for="${idans4}">Answer</label><textarea rows="5" name="shortquestion[${idqus4}][${ordercount}][]" id="${idans4}" class="form-control ques_ip"></textarea></div><div class="col-md-2"><label for="${idmark4}">Mark</label><input type="text" name="shortquestion[${idqus4}][${ordercount}][]" id="${idmark4}" class="form-control ques_ip mark_cls" /></div><div class="col-md-1"><button type="button" class="remove_ques btn btn-danger"><i class="fa fa-trash"></i></button></div></div></div>`);
			updateIndex();
			findTotal();

			$(".remove_ques").on("click", function() {

				$(this).closest('.tab_row').remove();
				updateIndex();
				findTotal2();
				
			});

		}); 
	});  

</script>

<!-- Qus 5 script -->

<script>

	$(document).ready(function() { 

		$("#longqus").on("click", function() {
            ordercount++;

			var idqus5=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);
			var idans5=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);
			var idmark5=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);
			
			$("#all_qus").append(`<div class="tab_row mb-3"><div class="row align-items-end"><div class="col-md-9"><label for="${idqus5}"><span><span class="inc_span"></span>&nbsp;.&nbsp;</span>Question </label><input type="text" name="longquestion[${idqus5}][${ordercount}][]" id="${idqus5}" class="form-control ques_ip" /></div><div class="col-md-9 mt-3"><label for="${idans5}">Answer</label><textarea rows="10" name="longquestion[${idqus5}][${ordercount}][]" id="${idans5}" class="form-control ques_ip"></textarea></div><div class="col-md-2"><label for="${idmark5}">Mark</label><input type="text" name="longquestion[${idqus5}][${ordercount}][]" id="${idmark5}" class="form-control ques_ip mark_cls" /></div><div class="col-md-1"><button type="button" class="remove_ques btn btn-danger"><i class="fa fa-trash"></i></button></div></div></div>`);
			updateIndex();
			findTotal();

			$(".remove_ques").on("click", function() {

				$(this).closest('.tab_row').remove();
				updateIndex();
				findTotal2();
				
			});

		}); 
	});

</script>

<script>
	function readIMG(input, imgControlName) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e) {
				$(imgControlName).attr('src', e.target.result);
			}
		reader.readAsDataURL(input.files[0]);
		}
	}
	
	$('.imag').change(function() {
		var ext = $(this).closest('.tab_row').find(".imag").val().split('.').pop();
		if(ext =="pdf"){
			$(this).closest('.tab_row').find(".img_box").html(`<img id="ImgPreview" src="{{ URL::to('/') }}/assets/docs/pdf.png" class="preview1" style="display: none;" type="application/pdf"/>`);
			$(this).closest('.tab_row').find('#ImgPreview').show();
		}
		else {
			var imgControlName = $(this).closest('.tab_row').find('#ImgPreview');
			readIMG(this, imgControlName);
			$(this).closest('.tab_row').find('#ImgPreview').show();
		}
	});
</script>


@endsection

@section('script_link')

    <!-- validator -->
    {!! Cms::script("theme/vendors/validator/validator.js") !!}
    {!! Cms::script("theme/vendors/validator/validator_form.js") !!}

@endsection
