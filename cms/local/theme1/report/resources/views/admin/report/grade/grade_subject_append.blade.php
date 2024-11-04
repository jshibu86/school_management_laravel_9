            
<thead>
    @php
        // Fetch the first mark distribution
       
        $markdistribution_first = DB::table('mark_entry')->where(['subject_id'=>$subject_id,'academic_year'=>$academic_year,
        'term_id'=>$term,'class_id'=>$class_id,'section_id'=>$section_id])->pluck('distribution')->first();

        // Decode the JSON string to an associative array
        $distribution_data = json_decode($markdistribution_first, true);
    @endphp

    <tr>
        <th>Sl.No</th>
        <th>Image</th>
        <th>Student Name</th>
        <th>Reg No</th>
        @if($distribution_data !== null)
        @foreach ($distribution_data as $key => $mark)
            <th>{{ $mark['distributionname'] }}</th>
        @endforeach
        <th>Total</th>
        <th>Position</th>
        @else
        <th>No Data Avalable</th>
        @endif
        <th>Action</th>
    </tr>
</thead>


<tbody>
    @forelse ($students as $student)
        <tr> 
            <td>{{ $loop->index+1 }}</td>
            <td><img src="{{ asset($student->image ? $student->image : 'assets/images/default.jpg') }}" class="img-fluid stu_profile" width="50px"/></td>
            <td>{{ $student->first_name }}</td>
            <td>{{ $student->reg_no }}</td>
            @php
                
                // Fetch mark distribution and total for the current student
                $markdistribution = DB::table('mark_entry')->where(['subject_id'=>$subject_id, 'student_id'=> $student->id])->pluck('distribution')->first(); 
                $total = DB::table('mark_entry')->where(['subject_id'=> $subject_id, 'student_id'=> $student->id])->pluck('total_mark')->first();
            @endphp
             @if($markdistribution !== null)
            {{-- Display each distribution mark --}}
            @foreach (json_decode($markdistribution, true) as $mark)
                @php
                   $distribution = ($mark['mark']) ? $mark['mark'] . '/' . $mark['originalmark'] : "NA";

                @endphp
                <td>{{ $distribution }}</td>
            @endforeach

            <td>{{ $total }} /100</td>
            @php
           
                        if ($total !== null) {
                            $marks = DB::table('mark_entry')->where(['subject_id'=> $subject_id])->get();
                            $rank =
                                $marks
                                    ->where("total_mark", ">", $total)
                                    ->count() + 1;
                            // dd($rank);
                        } else {
                            $rank = null;
                        }
                        $position = $rank;
            @endphp
            <td>@if($position !== null)
                {{ Configurations::ordinal($position) }}
                @else
                  
                @endif
            </td>   
            @else
            @php
                $position = 0;
            @endphp
            <td>NA</td>
            @endif
            <td>
                <button class="editbutton btn btn-default viewroute" id="{{ $student->id }}" 
                    onclick="ReportConfig.getStudentsSubjectMarkinfo({{ $student->id }}, {{ $academic_year }}, {{ @$position }}, {{ $term }} , {{ $subject_id }}, {{ $class_id }}, {{ $section_id }})"  title="view Member card">
                    <i class="fa fa-eye"></i>
                </button>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="8">No Students Found</td>
        </tr>
    @endforelse
</tbody>

<style>
 .paginate_button{
    padding: unset !important; 
     margin-left: unset !important;
} 
</style> 

<script type="module">
    function notify_script(title, text, type, hide) {
        new PNotify({
            title: title,
            text: text,
            type: type,
            hide: hide,
            styling: 'fontawesome'
        })
    }
    ReportConfig.ReportInit(notify_script);
window.student_subject_report_info = "{{ route('grade_student_subject_report_view') }}"
ReportConfig.getStudentsSubjectMarkinfo(id,academic_year,position,term,subject_id,class_id,section_id);
</script>