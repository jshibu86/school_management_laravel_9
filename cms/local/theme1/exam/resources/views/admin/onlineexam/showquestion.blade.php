<style>
    .print_btn {
        text-align: right;
    }

    .questiontag {
        padding: 20px;
        background-color: #e1e1e1;
        margin-bottom: 20px;
    }

    h5 {
        padding: 0px !important;
    }
</style>
<div class=" p-4 rounded">

    <div class="card-body text-center">
        <img src="{{ @$onlineExam->student ? @$onlineExam->student->user->images : '/assets/images/default.jpg' }}"
            width=80 alt="student_image" class="rounded-circle p-1 border" />



        <h5 class="mb-0 mt-4">{{ @$onlineExam->student->first_name }}</h5>
        <p class="mb-0 text-secondary">{{ @$onlineExam->student->username }}</p>

    </div>

    <div class="row">
        <h5>Question</h5>
        <div class="col-md-12 questiontag">
            <p>{{ $question->question }}</p>
        </div>
        @if ($question->question_type == 'fillintheblanks')
        <h5>Student Answer</h5>
        @else
        <h5>Options</h5>
        @endif
        <div class="col-md-12">
            @if ($question->question_type == 'fillintheblanks')
                <p>
                     {{ $submission->your_answer }}
                </p>

                <h5>Correct Answer</h5>

                <p>{{$question->answer}}</p>
              
            @elseif ($question->question_type == 'choosebest')
                @php
                    $chooseoptions = explode(',', $question->options);
                @endphp
                <ul>
                @foreach ($chooseoptions as $chooseoption)
                    @php
                        $studentAnswerC=$chooseoptions[$submission->your_answer];

                        if( $studentAnswerC == $chooseoption)
                        {
                            if($studentAnswerC == $chooseoptions[$question->answer])
                            {
                                $class="text-success";
                            }else{
                                $class="text-danger";
                            }
                        }else{
                            $class="";
                        }
                    @endphp
                   <li class="{{$class}}">{{$chooseoption}}</li>
                @endforeach
                </ul>
               
                <p>
                   Student Answer :  {{ isset($chooseoptions[$submission->your_answer]) ? $chooseoptions[$submission->your_answer] : 'N/A' }}
                </p>
            @elseif ($question->question_type == 'yesorno')
                @php
                    $chooseoptionsYes = explode(',', $question->options);
                @endphp
                    <ul>
                    @foreach ($chooseoptionsYes as $chooseoptionYes)
                    @php
                        $studentAnswer=$chooseoptionsYes[$submission->your_answer];

                        if( $studentAnswer == $chooseoptionYes)
                        {
                            if($studentAnswer == $chooseoptionsYes[$question->answer])
                            {
                                $class="text-success";
                            }else{
                                $class="text-danger";
                            }
                        }else{
                            $class="";
                        }
                    @endphp
                    <li class="{{$class}}">{{$chooseoptionYes}}</li>
                    
                    @endforeach
                    </ul>
               
                <p>
                    Student Answer :  {{ isset($chooseoptionsYes[$submission->your_answer]) ? $chooseoptionsYes[$submission->your_answer] : 'N/A' }} 
                </p>
                
            @endif

           
        </div>
    </div>

    <div class="buttons text-end">
        @if ($submission->is_correct)
            <button type="button" class="btn btn-success"><i class="fa fa-check"></i>Correct</button>
        @else
            <button type="button" class="btn btn-danger"><i class="fa fa-close"></i>Incorrect</button>
        @endif



    </div>











</div>
