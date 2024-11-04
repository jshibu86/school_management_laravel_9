@extends('layout::admin.master')

@section('title','exam')
@section('style')
<link rel="stylesheet" href="{{asset('assets/backend/css/question.css')}}">
<style>
 
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

            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save-Continue', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat_continue' , 'value' => 'Edit_wallet' , 'class' => 'btn btn-dark btn-sm m-1  px-3')) }}

            @endif
           

           

             <a class="btn btn-info btn-sm m-1  px-3" href="{{route('exam.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

              {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }}

              

             
        </div>
           @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit exam" : "Create Exam"])

             <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Create Exam</h5>
                    <hr/>
                    <div class="col-xs-12">
                        <div class="row">
                        <div class="col-xs-12 col-sm-4 col-md-3">
                          <div class="item form-group">
                           <label class="control-label margin__bottom" for="status">Academic Year <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{ Form::select('academic_year',@$academic_years,@$data->academic_year ,
                                    array('id'=>'status','class' => 'single-select form-control','required' => 'required',"placeholder"=>"Select Academic Year" )) }}
                                </div>
                        </div>
                               
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                             <label class="control-label margin__bottom" for="status">Exam Type <span class="required">*</span>
                                  </label>
                                  <div class="feild">
                                      {{ Form::select('exam_type',@$exam_types,@$data->exam_type ,
                                      array('id'=>'status','class' => 'single-select form-control','required' => 'required',"placeholder"=>"Select Exam Type" )) }}
                                  </div>
                            </div>
                                 
                        </div>

                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                             <label class="control-label margin__bottom" for="status">Class <span class="required">*</span>
                                  </label>
                                  <div class="feild">
                                      {{ Form::select('class_id',@$class_lists,@$data->class_id ,
                                      array('id'=>'class_id','class' => 'single-select form-control','required' => 'required',"placeholder"=>"Select Class" )) }}
                                  </div>
                            </div>
                                 
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                             <label class="control-label margin__bottom" for="status">Subject <span class="required">*</span>
                                  </label>
                                  <div class="feild">
                                      {{ Form::select('subject_id',@$section_lists,@$data->subject_id ,
                                      array('id'=>'subject_id','class' => 'single-select form-control','required' => 'required',"placeholder"=>"Select Subject" )) }}
                                  </div>
                            </div>
                                 
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                                   <label class="control-label margin__bottom" for="status">Maximum Mark <span class="required">*</span>
                                   </label>
                                   <div class="feild">
                                       {{Form::number('max_mark',@$data->max_mark,array('id'=>"max_mark",'class'=>"form-control col-md-7 col-xs-12" ,
                                      'placeholder'=>"100",'required'=>"required"))}}
                                   </div>
                               </div>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-3">
                         <div class="item form-group">
                                <label class="control-label margin__bottom" for="status">Minimum Mark <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{Form::number('min_mark',@$data->min_mark,array('id'=>"min_mark",'class'=>"form-control col-md-7 col-xs-12" ,
                                   'placeholder'=>"100",'required'=>"required"))}}
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-5">
                            <div class="item form-group">
                                   <label class="control-label margin__bottom" for="status">Time Line<span class="required">*</span>
                                   </label>
                                   <div class="feild">
                                    <div class="hr-time-picker">
                                        <div class="picked-time-wrapper">
                                            <input type="text" class="picked-time">
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
                    </div>

                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-md-12">
                
                                <h5 class="card-title mt-3 mb-3">Questions</h5>
                                
                                <ul class="nav nav-pills mb-3 ques_tab justify-content-between" id="myTab" role="tablist">
                                    <li class="nav-item">

                                        <a class="nav-link active" id="tab1-tab" data-bs-toggle="pill" href="#tab1" role="tab" aria-controls="tab1" aria-selected="true">Fill in the Blanks</a>

                                    </li>
                                    <li class="nav-item">

                                        <a class="nav-link" id="tab2-tab" data-bs-toggle="pill" href="#tab2" role="tab" aria-controls="tab2" aria-selected="true">Choose the Best Answer</a>
                                       
                                    </li>
                                    <li class="nav-item">

                                        <a class="nav-link" id="tab3-tab" data-bs-toggle="pill" href="#tab3" role="tab" aria-controls="tab3" aria-selected="true">Define Type Questions</a>
                                        
                                    </li>
                                    <li class="nav-item">

                                        <a class="nav-link" id="tab4-tab" data-bs-toggle="pill" href="#tab4" role="tab" aria-controls="tab4" aria-selected="true">Short Questions</a>


                                        
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="tab5-tab" data-bs-toggle="pill" href="#tab5" role="tab" aria-controls="tab5" aria-selected="true">Long Questions</a>


                                      
                                    </li>
                                </ul>
                
                
                                <div class="tab-content mt-4">
                                    
                                    <div class="tab-pane fade active show" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">
                
                                        <div id="qus1_box">
                                            <div class="tab_row mb-3">
                                                <div class="row align-items-end">
                                                    <div class="col-md-6">
                                                        <label for="ques_1">Question 1</label>
                                                        <input type="text" name="fill_in_blanks[ques_1][]" id="ques_1" class="form-control ques_ip" />
                                                    </div>
                
                                                    <div class="col-md-4">
                                                        <label for="ans_1">Answer</label>
                                                        <input type="text" name="fill_in_blanks[ques_1][]" id="ans_1" class="form-control ques_ip" />
                                                    </div>

                                                    <div class="col-md-1">
                                                        <label for="mark_1">Mark</label>
                                                        <input type="text" name="fill_in_blanks[ques_1][]" id="mark_1" class="form-control mark_ip_fill_blanks" placeholder="0"/>
                                                    </div>
                
                                                </div>
                                            </div>
                                        </div>
                
                                        <div class="row align-items-end">
                                            <div class="col-md-6">
                                                <button type="button" class="btn w-100 add_btn" id="addquestion1">Add Question +</button>
                                            </div>
                                            <div class="col-md-4"></div>
                                            <div class="col-md-2">
                                                <label for="total_1">Total Mark</label>
                                                <input type="text" name="total_1" id="total_1" class="form-control total_mark_fill_blanks" />
                                            </div>
                                        </div>
                
                                    </div>
                
                                    <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab">
                
                                        <div id="qus2_box">
                                            <div class="tab_row mb-3">
                                                <div class="row align-items-end">
                                                    <div class="col-md-7">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <label for="ques_2">Question 1</label>
                                                                <input type="text" name="choose_best[ques_2][]" id="ques_2" class="form-control ques_ip" />
                                                            </div>
                                                        </div>
                                                        <div class="row mt-3">
                                                            <div class="col-md-3 pr-0">

                                                                <div class="input-group"> <span class="input-group-text" id="inputGroup-sizing-default">A</span>
                                                                    <input type="text" 
                                                                    name="choose_best[ques_2][]"
                                                                    class="form-control ques_ip" 
                                                                    id="ques_2_opt1"
                                                                    aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3 pr-0">

                                                                <div class="input-group"> <span class="input-group-text" id="inputGroup-sizing-default">B</span>
                                                                    <input type="text" 
                                                                    name="choose_best[ques_2][]"
                                                                    class="form-control ques_ip" 
                                                                    id="ques_2_opt1"
                                                                    aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                                                                </div>


                                                                
                                                            </div>
                                                            <div class="col-md-3 pr-0">
                                                                <div class="input-group"> <span class="input-group-text" id="inputGroup-sizing-default">C</span>
                                                                    <input type="text" 
                                                                    name="choose_best[ques_2][]"
                                                                    class="form-control ques_ip" 
                                                                    id="ques_2_opt1"
                                                                    aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                                                                </div>
                                                               
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="input-group"> <span class="input-group-text" id="inputGroup-sizing-default">D</span>
                                                                    <input type="text" 
                                                                    name="choose_best[ques_2][]"
                                                                    class="form-control ques_ip" 
                                                                    id="ques_2_opt1"
                                                                    aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                
                                                    <div class="col-md-3">
                                                        <label for="ans_2">Answer</label>
                                                        <div class="ans_checkbox">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="choose_best[ques_2][]" checked id="flexRadioDefault1" value="A">
                                                                <label class="form-check-label" for="flexRadioDefault1">A</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="choose_best[ques_2][]" id="flexRadioDefault1" value="B">
                                                                <label class="form-check-label" for="flexRadioDefault1">B</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="choose_best[ques_2][]" id="flexRadioDefault1" value="C">
                                                                <label class="form-check-label" for="flexRadioDefault1">C</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="choose_best[ques_2][]" id="flexRadioDefault1" value="D">
                                                                <label class="form-check-label" for="flexRadioDefault1">D</label>
                                                            </div>
                                                        </div>
                                                      
                                                       
                                                    </div>
                                                    <div class="col-md-1">
                                                        <label for="mark_1">Mark</label>
                                                        <input type="text" name="choose_best[ques_2][]" id="mark_1" class="form-control mark_ip_choose_best" placeholder="0"/>
                                                    </div>
                                                    <div class="col-md-1"></div>
                                                </div>
                                            </div>
                                        </div>
                
                                        <div class="row align-items-end">
                                            <div class="col-md-7">
                                                <button type="button" class="btn w-100 add_btn" id="addquestion2">Add Question +</button>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="total_2">Total Mark</label>
                                                <input type="text" name="total_2" id="total_2" class="form-control ques_ip total_mark_choose_best" />
                                            </div>
                                        </div>
                
                                    </div>
                
                                    <div class="tab-pane fade" id="tab3" role="tabpanel" aria-labelledby="tab3-tab">
                
                                        <div id="qus3_box">
                                            <div class="tab_row mb-3">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <p class="mb-2">Upload Image</p>
                                                                <input type="file" name="define_type[ques_3][]" id="file_3" placeholder="Choose the file" class="form-control ques_ip file_ip imag" accept=".xlsx,.docx,.pdf,.txt,.jpeg,.gif,.jpg,.png,.mp4" />
                                                                <span class="ch_span">Choose the file... Ex:jpeg, gif, png, pdf</span>
                                                                <label for="file_3" class="upload_cls btn mt-3">Upload</label>
                                                            </div>
                                                            <div class="col-md-12 mt-3">
                                                                <label for="ques_3">Add Question</label>
                                                                <input type="text" name="define_type[ques_3][]" id="ques_3" class="form-control ques_ip" />
                                                            </div>
                                                            <div class="col-md-12 mt-3">
                                                                <label for="ans_3">Answer</label>
                                                                <textarea rows="5" name="define_type[ques_3][]" id="ans_3" class="form-control ques_ip"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                
                                                    <div class="col-md-4">
                                                        <label for="preview_3">Image Preview</label>
                                                        <div class="img_box">
                                                            <img id="ImgPreview" src="" class="preview1" style="display: none;" type="application/pdf"/>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <label for="mark_1">Mark</label>
                                                        <input type="text" name="define_type[ques_3][]" id="mark_1" class="form-control mark_ip_define_type" placeholder="0"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                
                                        <div class="row align-items-end">
                                            <div class="col-md-6">
                                                <button type="button" class="btn w-100 add_btn" id="addquestion3">Add Question +</button>
                                            </div>
                                            <div class="col-md-4"></div>
                                            <div class="col-md-1">
                                                <label for="total_3">Total Mark</label>
                                                <input type="text" name="total_3" id="total_3" class="form-control total_mark_define_type" />
                                            </div>
                                        </div>
                
                                    </div>
                
                                    <div class="tab-pane fade" id="tab4" role="tabpanel" aria-labelledby="tab4-tab">
                
                                        <div id="qus4_box">
                                            <div class="tab_row mb-3">
                                                <div class="row align-items-end">
                                                    <div class="col-md-10">
                                                        <label for="ques_4">Question 1</label>
                                                        <input type="text" name="short_ques[ques_4][]" id="ques_4" class="form-control ques_ip" />
                                                    </div>
                
                                                    <div class="col-md-10 mt-3">
                                                        <label for="ans_4">Answer</label>
                                                        <textarea rows="5" name="short_ques[ques_4][]" id="ans_4" class="form-control ques_ip"></textarea>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <label for="mark_1">Mark</label>
                                                        <input type="text" name="short_ques[ques_4][]" id="mark_1" class="form-control mark_ip_short_ques" placeholder="0"/>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                
                                        <div class="row align-items-end">
                                            <div class="col-md-7">
                                                <button type="button" class="btn w-100 add_btn" id="addquestion4">Add Question +</button>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="total_4">Total Mark</label>
                                                <input type="text" name="total_4" id="total_4" class="form-control total_mark_short_ques" />
                                            </div>
                                        </div>
                
                                    </div>
                
                                    <div class="tab-pane fade" id="tab5" role="tabpanel" aria-labelledby="tab5-tab">
                
                                        <div id="qus5_box">
                                            <div class="tab_row mb-3">
                                                <div class="row align-items-end">
                                                    <div class="col-md-10">
                                                        <label for="ques_5">Question 1</label>
                                                        <input type="text" name="long_ques[ques_5][]" id="ques_5" class="form-control ques_ip" />
                                                    </div>
                
                                                    <div class="col-md-10 mt-3">
                                                        <label for="ans_5">Answer</label>
                                                        <textarea rows="10" name="long_ques[ques_5][]" id="ans_5" class="form-control ques_ip"></textarea>
                                                    </div>

                                                    <div class="col-md-1">
                                                        <label for="mark_1">Mark</label>
                                                        <input type="text" name="long_ques[ques_5][]" id="mark_1" class="form-control mark_ip_long_ques" placeholder="0"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                
                                        <div class="row align-items-end">
                                            <div class="col-md-7">
                                                <button type="button" class="btn w-100 add_btn" id="addquestion5">Add Question +</button>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="total_4">Total Mark</label>
                                                <input type="text" name="total_4" id="total_4" class="form-control total_mark_long_ques" />
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

        AcademicConfig.examInit(notify_script);

        $('select[name="class_id"]').on("change", function () {
            let class_id = $(this).val();
            AcademicConfig.getsubjects(class_id,notify_script);
        })

        

       
</script>


<script>

	$(document).ready(function() { 

		$("#addquestion1").on("click", function() {

			var idqus=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);
			var idans=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);

			updateIndex = function() {
				$('.inc_span').each(function(i) {
					$(this).html(i + 2);
				});
			};

			$("#qus1_box").append(`<div class="tab_row mb-3"><div class="row align-items-end"><div class="col-md-6"><label for="${idqus}">Question <span class="inc_span"></span></label><input type="text" name="fill_in_blanks[${idqus}][]" id="${idqus}" class="form-control ques_ip" /></div><div class="col-md-4"><label for="${idans}">Answer</label><input type="text" name="fill_in_blanks[${idqus}][]" id="${idans}" class="form-control ques_ip" /></div> <div class="col-md-1"><label for="${idans}">Mark</label><input type="text" name="fill_in_blanks[${idqus}][]" id="${idans}" class="form-control ques_ip mark_ip_fill_blanks" placeholder="0" /></div><div class="col-md-1"><button type="button" class="remove_ques btn btn-danger"><i class="fa fa-trash"></i></button></div></div></div>`);
			updateIndex();

			$(".remove_ques").on("click", function() {

				$(this).closest('.tab_row').remove();
				updateIndex();

			});

		}); 

	});  

</script>

<!-- Tab 2 script -->

<script>

	$(document).ready(function() { 

		$("#addquestion2").on("click", function() {

			var idqus2=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);
			var idans2=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);

			updateIndex2 = function() {
				$('.inc_span2').each(function(i) {
					$(this).html(i + 2);
				});
			};

			$("#qus2_box").append(`<div class="tab_row mb-3">
                
                <div class="row align-items-end"><div class="col-md-7"><div class="row"><div class="col-md-12"><label for="${idqus2}">Question <span class="inc_span2"></span></label><input type="text" name="choose_best[${idqus2}][]" id="${idqus2}" class="form-control ques_ip" /></div></div><div class="row mt-3"><div class="col-md-3 pr-0"><div class="input-group"> <span class="input-group-text" id="inputGroup-sizing-default">A</span>
                 <input type="text" name="choose_best[${idqus2}][]" class="form-control ques_ip" id="${idans2}" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default"></div>
                        
                        </div>
                    
                    <div class="col-md-3 pr-0">
                        
                        <div class="input-group"> <span class="input-group-text" id="inputGroup-sizing-default">B</span>
                 <input type="text" name="choose_best[${idqus2}][]" class="form-control ques_ip" id="${idans2}" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default"></div>
                        
                        </div>
                    
                    <div class="col-md-3 pr-0">
                        
                        <div class="input-group"> <span class="input-group-text" id="inputGroup-sizing-default">C</span>
                 <input type="text" name="choose_best[${idqus2}][]" class="form-control ques_ip" id="${idans2}" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default"></div>
                        
                        </div>
                    
                    <div class="col-md-3">
                        
                        <div class="input-group"> <span class="input-group-text" id="inputGroup-sizing-default">D</span>
                 <input type="text" name="choose_best[${idqus2}][]" class="form-control ques_ip" id="${idans2}" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default"></div>
                        
                        </div>
                    
                    </div>
                    
                    </div>
                    
                    <div class="col-md-3"><label for="ans_2">Answer</label><div class="ans_checkbox">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="choose_best[${idqus2}][]" checked id="${idans2}" value="A">
                                                                <label class="form-check-label" for="${idans2}">A</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="choose_best[${idqus2}][]" id="${idans2}" value="B">
                                                                <label class="form-check-label" for="${idans2}">B</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="choose_best[${idqus2}][]" id="${idans2}" value="C">
                                                                <label class="form-check-label" for="${idans2}">C</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="choose_best[${idqus2}][]" id="${idans2}" value="D">
                                                                <label class="form-check-label" for="${idans2}">D</label>
                                                            </div>
                                                        </div></div>
                                                        <div class="col-md-1">
                                                        <label for="mark_1">Mark</label>
                                                        <input type="text" name="choose_best[${idqus2}][]" id="mark_1" class="form-control mark_ip_choose_best" placeholder="0"/>
                                                    </div>
                    <div class="col-md-1"><button type="button" class="remove_ques btn btn-danger"><i class="fa fa-trash"></i></button></div></div></div>`);
			updateIndex2();

			$(".remove_ques").on("click", function() {

				$(this).closest('.tab_row').remove();
				updateIndex2();
				
			});

		}); 
	});  


</script>

<!-- Tab 3 script -->

<script>

	$(document).ready(function() { 

		$("#addquestion3").on("click", function() {

			var idqus3=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);
			var idans3=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);
			var fileid=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);

			$("#qus3_box").append(`<div class="tab_row py-3"><div class="row align-items-center"><div class="col-md-6"><div class="row"><div class="col-md-12"><p class="mb-2">Upload Image</p><input type="file" name="define_type[${idqus3}][]" id="${fileid}" placeholder="Choose the file" class="form-control ques_ip file_ip imag" accept=".xlsx,.docx,.pdf,.txt,.jpeg,.gif,.jpg,.png,.mp4" /><span class="ch_span">Choose the file... Ex:jpeg, gif, png, pdf</span><label for="${fileid}" class="upload_cls btn mt-3">Upload</label></div><div class="col-md-12 mt-3"><label for="${idqus3}">Add Question</label><input type="text" name="define_type[${idqus3}]" id="${idqus3}" class="form-control ques_ip" /></div><div class="col-md-12 mt-3"><label for="${idans3}">Answer</label><textarea rows="5" name="define_type[${idqus3}]" id="${idans3}" class="form-control ques_ip"></textarea></div></div></div>
            
            <div class="col-md-4"><label for="preview_3">Image Preview</label><div class="img_box"><img id="ImgPreview" src="" class="preview1" style="display: none;"/></div></div>
            <div class="col-md-1">
            <label for="mark_1">Mark</label>
                                                        <input type="text" name="define_type[${idqus3}]" id="mark_1" class="form-control mark_ip_define_type" placeholder="0"/>
                                                    </div>
            <div class="col-md-1"><button type="button" class="remove_ques btn btn-danger"><i class="fa fa-trash"></i></button></div></div></div>`);
			afterClick();

			$(".remove_ques").on("click", function() {

				$(this).closest('.tab_row').remove();
				
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

		$(this).closest('.tab_row').find('.file_ip').css('color','#7b7b7b');
		$(this).closest('.tab_row').find('.ch_span').hide();

		var ext = $(this).closest('.tab_row').find(".imag").val().split('.').pop();

		if(ext == 'mp4'){
			console.log("Its video");
			$(this).closest('.tab_row').find(".img_box").html(`<video controls autoplay src="" class="vids"></video>`);

			var imgControlName = $(this).closest('.tab_row').find('.vids');
			readIMG(this, imgControlName);
			$(this).closest('.tab_row').find('.vidssrc').show();

		}
		else if (ext =="pdf" || ext =="xlsx" || ext =="docx") {

			console.log("Its docs");

			$(this).closest('.tab_row').find(".img_box").html(`<img id="ImgPreview" src="" class="preview1" style="display: none;" type="application/pdf"/>`);

			$(this).closest('.tab_row').find('#ImgPreview').show();
			var image = $(this).closest('.tab_row').find('.preview1');

			switch (ext) {
				case 'pdf':
					image[0].src = "{{ URL::to('/') }}/assets/docs/pdf.png";
					break;
				case 'xlsx':
					image[0].src = "assets/docs/xlsx.png";
					break;
				case 'docx':
					image[0].src = "assets/docs/docimage.png";
					break;
				
			}
			
		}
		else{

			console.log("Its img");

			$(this).closest('.tab_row').find(".img_box").html(`<img id="ImgPreview" src="" class="preview1" style="display: none;" type="application/pdf"/>`);
			
			var imgControlName = $(this).closest('.tab_row').find('#ImgPreview');
			readIMG(this, imgControlName);
			$(this).closest('.tab_row').find('#ImgPreview').show();
		}

	});


	// add question script

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
			console.log("Its video");
			$(this).closest('.tab_row').find(".img_box").html(`<video controls autoplay src="" class="vids"></video>`);

			var imgControlName = $(this).closest('.tab_row').find('.vids');
			readIMG(this, imgControlName);
			$(this).closest('.tab_row').find('.vidssrc').show();

		}
		else if (ext =="pdf" || ext =="xlsx" || ext =="docx") {

			console.log("Its docs");

			$(this).closest('.tab_row').find(".img_box").html(`<img id="ImgPreview" src="" class="preview1" style="display: none;" type="application/pdf"/>`);

			$(this).closest('.tab_row').find('#ImgPreview').show();
			var image = $(this).closest('.tab_row').find('.preview1');

			switch (ext) {
				case 'pdf':
					image[0].src = "{{ URL::to('/') }}/assets/docs/pdf.png";
					break;
				case 'xlsx':
					image[0].src = "assets/docs/xlsx.png";
					break;
				case 'docx':
					image[0].src = "assets/docs/docimage.png";
					break;
				
			}
			
		}
		else{

			console.log("Its img");

			$(this).closest('.tab_row').find(".img_box").html(`<img id="ImgPreview" src="" class="preview1" style="display: none;" type="application/pdf"/>`);
			
			var imgControlName = $(this).closest('.tab_row').find('#ImgPreview');
			readIMG(this, imgControlName);
			$(this).closest('.tab_row').find('#ImgPreview').show();
		}
		});
	}
</script>

<!-- Tab 4 script -->

<script>

	$(document).ready(function() { 

		$("#addquestion4").on("click", function() {

			var idqus4=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);
			var idans4=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);

			updateIndex4 = function() {
				$('.inc_span4').each(function(i) {
					$(this).html(i + 2);
				});
			};


			$("#qus4_box").append(`<div class="tab_row mb-3"><div class="row align-items-center"><div class="col-md-10"><label for="${idqus4}">Question <span class="inc_span4"></span></label><input type="text" name="short_ques[${idqus4}][]" id="${idqus4}" class="form-control ques_ip" /></div><div class="col-md-10 mt-3"><label for="${idans4}">Answer</label><textarea rows="5" name="short_ques[${idqus4}][]" id="${idans4}" class="form-control ques_ip"></textarea></div>
            <div class="col-md-1">
                                                        <label for="mark_1">Mark</label>
                                                        <input type="text" name="short_ques[${idqus4}][]" id="mark_1" class="form-control mark_ip_short_ques" placeholder="0"/>
                                                    </div>
            <div class="col-md-1"><button type="button" class="remove_ques btn btn-danger"><i class="fa fa-trash"></i></button></div></div></div>`);
			updateIndex4();

			$(".remove_ques").on("click", function() {

				$(this).closest('.tab_row').remove();
				updateIndex4();
				
			});

		}); 
	});  


</script>

<!-- Tab 5 script -->

<script>

	$(document).ready(function() { 


		$("#addquestion5").on("click", function() {

			var idqus5=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);
			var idans5=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);

			updateIndex5 = function() {
				$('.inc_span5').each(function(i) {
					$(this).html(i + 2);
				});
			};


			$("#qus5_box").append(`<div class="tab_row mb-3"><div class="row align-items-center"><div class="col-md-10"><label for="${idqus5}">Question <span class="inc_span5"></span></label><input type="text" name="long_ques[${idqus5}][]" id="${idqus5}" class="form-control ques_ip" /></div><div class="col-md-10 mt-3"><label for="${idans5}">Answer</label><textarea rows="10" name="long_ques[${idqus5}][]" id="${idans5}" class="form-control ques_ip"></textarea></div>

            <div class="col-md-1">
                                                        <label for="mark_1">Mark</label>
                                                        <input type="text" name="long_ques[${idqus5}][]" id="mark_1" class="form-control mark_ip_long_ques" placeholder="0"/>
                                                    </div>
            
            <div class="col-md-1"><button type="button" class="remove_ques btn btn-danger"><i class="fa fa-trash"></i></button></div></div></div>`);
			updateIndex5();

			$(".remove_ques").on("click", function() {

				$(this).closest('.tab_row').remove();
				updateIndex5();
				
			});

		}); 
	});  


</script>

@endsection

@section('script_link')

    <!-- validator -->
    {!! Cms::script("theme/vendors/validator/validator.js") !!}
    {!! Cms::script("theme/vendors/validator/validator_form.js") !!}

@endsection
