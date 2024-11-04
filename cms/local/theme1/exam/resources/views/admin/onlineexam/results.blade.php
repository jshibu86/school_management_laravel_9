
<link rel="stylesheet" href="{{asset('assets/backend/css/onlineexam.css')}}">
<link rel="stylesheet" href="{{asset('assets/backend/css/onlineexamresult.css')}}">
<section class="online__exam">
<div class="container">
<p id="resultexamtimer"></p>
<div class="text-center exam__information  mt-2 mb-2">
    <div class="row">
        <div class="col-3" style="text-align: left">
            @if(isset(Configurations::getConfig('site')->imagec))
            <img src="{{ Configurations::getConfig('site')->imagec }} " width="80" alt="nologo"/>
            @endif
                
            
        </div>
        <div class="col-6 company-details">
            <h2 class="name">
            <a target="_blank" href="{{ route("backenddashboard") }}">
                {{isset(Configurations::getConfig('site')->school_name) ?Configurations::getConfig('site')->school_name : 'School'}}
            </a>
            </h2>
            <div>{{ Configurations::getConfig('site')->place }}, {{ Configurations::getConfig('site')->city }},{{ Configurations::getConfig('site')->pin_code }},{{ Configurations::getConfig('site')->country }}</div>
            
            <div><span>Contact : {{ Configurations::getConfig('site')->school_phone }}</span></div>
            <div><span>Email : {{ Configurations::getConfig('site')->school_email }}</span></div>
            <div class="mt-2"><span>Class : <b>{{ @$exam->class->name }}</b></span>,<span>Section :  <b>{{ @$exam->section->name }}</b></span>,<span>Subject :  <b>{{ @$exam->subject->name }}</b></span></div>
        </div>
        <div class="col-3" style="text-align: right">
            <img class="" src="{{@$student->user->images ?@$student->user->images :asset('assets/images/staff.jpg')   }}"  width="80" alt="nologo">
        </div>
    </div>
</div>

<div class="result__information">
    
    <h4 class="siderbar_head my-4">Summary of Online Exam</h4>
    <div class="row">
        <div class="col-md-6 d-flex align-items-center">
            <h2 class="sum_box sum_box_answered bg_blue">{{ @$onlineexam->total_questions }}</h2>
            <h6 class="sum_txt">Total Questions</h6>
        </div>
        <div class="col-md-6 d-flex align-items-center">
            <h2 class="sum_box sum_box_answered bg_blue">{{ @$onlineexam->total_answered }}</h2>
            <h6 class="sum_txt">Answered</h6>
        </div>
        
        <div class="col-md-6 d-flex align-items-center">
            <h2 class=" sum_box sum_box_not_answered bg_red">{{ @$onlineexam->total_questions - @$onlineexam->total_answered }}</h2>
            <h6 class="sum_txt">Not Answered</h6>
        </div>
@if(@$exam->show_results == "1")
        <div class="col-md-6 d-flex align-items-center">
            <h2 class=" sum_box sum_box_not_answered bg_success">{{ @$onlineexam->total_correct }}</h2>
            <h6 class="sum_txt">Correct Answered</h6>
        </div>
        
    </div>
    
    <div class="result_info">
        <div class="passmark">
            <p>Pass Mark : {{ @$exam->min_mark }}</p>
        </div>
        <div class="total">
            <p>Total Mark Scored : {{ @$submission }}</p>
        </div>
        <div class="result">
            <p>Result : {{ @$submission >=@$exam->min_mark ? "Pass" :"Fail" }}</p>
        </div>
    </div>
    @endif
</div>                    
</div>
</section>

<script>
    var examresultTimer=()=>{
        var totalSeconds = 60 * 1; // 90 minutes in seconds
        var secondsRemaining = totalSeconds;

        var timer = setInterval(function () {
            secondsRemaining--;
            // Update the timer display on the page
            document.getElementById("resultexamtimer").innerHTML =
                formatTime(secondsRemaining);

            if (secondsRemaining == 0) {
                clearInterval(timer);
                // Automatically submit the exam
                window.location.href = '{{ route("onlineexam.index") }}';
            //     window.opener.location.reload(true);
            //     window.close();
            //    reloadfunction();
               
            }
        }, 1000);
    }

    var reloadfunction=()=>{
        setTimeout(() => {
            console.log("running");
        }, 2000);
    }

    var formatTime=(seconds)=>{
        var hours = Math.floor(seconds / 3600);
        var minutes = Math.floor((seconds - hours * 3600) / 60);
        var seconds = seconds - hours * 3600 - minutes * 60;

        var formattedTime =
            hours.toString().padStart(2, "0") +
            ":" +
            minutes.toString().padStart(2, "0") +
            ":" +
            seconds.toString().padStart(2, "0");
        return `This Window Close In: ${formattedTime}`;
    }

    examresultTimer();
</script>