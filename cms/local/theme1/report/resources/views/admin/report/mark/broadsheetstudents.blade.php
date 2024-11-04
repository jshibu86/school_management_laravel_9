<link rel="stylesheet" href="{{asset('assets/backend/css/custom.css')}}">
<div class="row">
    <div class="info__Section">
        <img src="{{Configurations::getConfig("site")->imagec}}" style="width:100px"/>
        <div class="school_text">
            <h4>{{Configurations::getConfig("site")->school_name}}</h4>
            <strong>BroadSheet Report For {{@$acyear_name}} Session | Class : {{@$classsection}} | Term : {{@$term_name}} | Exam Type : {{@$exam_type_name}}</strong>
        </div>
     </div>
</div>

<div class="broadsheet__Students" style="overflow: scroll;">
    <table class="table school_table">
        <thead>
            <tr class="tab_head">
                <th>No</th>
                <th>Admission No</th>
                <th>Student Name</th>
               @foreach (@$subjects as $subject)
                   <th>{{$subject->name}}</th>
               @endforeach
               <th>Total No.of Subjects</th>
               <th>Total Mark Obtainable</th>
               <th>Total </th>
               <th>Average</th>
               <th>Percentage</th>
             
               <th>Status</th>
                
               
            </tr>
        </thead>

        <tbody>

            @foreach (@$student_data as$student_id => $info )
                <tr>
                <td>{{$loop->index+1}}</td>
                <td class="tab_admission">{{$info->admission_no}}</td>
                <td>{{$info->student_name}}</td>
               @foreach ($info->mark_entry as $subject_id => $subject_info)
                <td>{{$subject_info->subject_total_mark}}</td>
               @endforeach
               <td>{{$info->total_subjects}}</td>
               <td>{{$info->total_mark_obtainable}}</td>
               <td>{{$info->total_mark_obtain}}</td>
               <td>{{$info->avg}}</td>
               <td>{{$info->percentage}}</td>
              
               <td>{!!$info->status !!}</td>
            </tr>
            @endforeach
            
        </tbody>
    </table>
</div>