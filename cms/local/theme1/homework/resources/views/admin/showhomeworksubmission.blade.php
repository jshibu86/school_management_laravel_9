<style>
   
    .feedback{
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 40%;
    margin-top: 20px;
    }
</style>


<div class="student__content">

    <input type="hidden" name="student_id" value="{{ @$student->id }}"/>
    <input type="hidden" name="homework_id" value="{{ @$homework_submission->homework_id }}"/>
    <input type="hidden" name="homesub_id" value="{{ @$homework_submission->id }}"/>
    <div class="img__content text-center">
        <img src="{{ @$student->image ?  @$student->image : "/assets/images/default.jpg" }}" width=80 alt="student_image"/>
    </div>
    <div class="profile text-center">
       <h4>{{ @$student->username }}</h4>
       <span>Submitted Date : {{ @$homework_submission->submitted_date }}</span>
          
       
    </div>
    <div class="submission_details">
        <h5>Remarks</h5>
        <p>{{ @$homework_submission->remark }}</p>

        @if ( @$homework_submission->attachment)

        <a href="{{ @$homework_submission->attachment }}" class="badge bg-light text-dark" target="_blank">View Attachment</a>
            
        @endif

        
    </div>
    <div class="feedback">
        <label>FeedBack ? </label>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="feedback" id="flexRadioDefault2"  value="poor">
            <label class="form-check-label" for="flexRadioDefault2">Poor</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="feedback" id="flexRadioDefault2" checked="" value="good">
            <label class="form-check-label" for="flexRadioDefault2">Good</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="feedback" id="flexRadioDefault2" value="excellent">
            <label class="form-check-label" for="flexRadioDefault2">Excellent</label>
        </div>
    </div>
</div>

