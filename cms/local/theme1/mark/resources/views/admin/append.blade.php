
<div class="row">
   <div class="col-md-3 mb-4">
      <div class="item form-group">
          <label for="exam_status" class="mb-2">Exam Status <span>*</span></label>
  
  
          {{ Form::select('exam_status',['Online'=>'Online','Offline'=>'Offline'],@$data->status ,
          array('id'=>'exam_status','class' => 'single-select form-control','required' => 'required',$layout=="edit" ? "disabled" : "")) }}
      </div>
  </div>
   <div class="col-md-3 mb-4">
      <div class="item form-group">
       
         <label for="exam_type" class="mb-2">Exam Type <span>*</span></label>
   
   
          {{ Form::select('exam_type',@$exam_type,@$data->exam_type ,
             array('id'=>'exam_type','class' =>'single-select1 single-select form-control','required' => 'required',"placeholder"=>"Select Exam Type",$layout=="edit" ? "disabled" : "" )) }}
       </div>
      
   </div>
  
   <div class="col-md-3 mb-4">
      <div class="item form-group">
         <label for="exam_id" class="mb-2">Exam Name <span>*</span></label>
   
   
         {{ Form::select('exam_id',@$exams,@$data->exam_id ,
          array('id'=>'exam_id','class' => 'single-select form-control','required' => 'required',"placeholder"=>"Select Exam" ,$layout=="edit" ? "disabled" : "")) }}
       </div>
   </div>
  
   <div class="col-md-3">
      <div class="item form-group">
         <label for="exam_field" class="mb-2">         
            Select Field <button type="button" class="border-0" style="background: none !important;" data-toggle="tooltip" data-placement="top" title="Select the Field where you want the score or mark to be populated">
               <i class="fa fa-info-circle mb-0" style="font-size:16px;"></i>
             </button></label>
   
   
         {{ Form::select('exam_field',@$markdistribution,@$data->exam_field ,
         array('id'=>'exam_field','class' => 'single-select form-control','required' => 'required',$layout=="edit" ? "disabled" : "")) }}
     
       </div>
   </div> 
 
</div> 
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
     window.getexams="{{ route('mark.index') }}";
    ExamConfig.examinit(notify_script);
 </script>
 <script src="{{ asset('public/assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
 <script>
     // Initialize tooltips
     $(document).ready(function() {
         $('[data-toggle="tooltip"]').tooltip();
     });
 </script>