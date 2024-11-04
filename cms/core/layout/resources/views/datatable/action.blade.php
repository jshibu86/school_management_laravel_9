 <style>
     .actions {
         display: flex;
         align-items: center;
     }

     .actions button,
     .actions i,
     .actions a {
         font-size: 18px !important;
     }
 </style>



 <div class="actions">

     @if (isset($route1) && $route1 == 'examterm')
         <a class="editbutton btn btn-default" data-toggle="modal" data={{ $data->id }}
             href="{{ route($route1 . '.edit', $data->id) }}" title="edit"><i class="fa fa-edit"></i></a>
     @elseif($route == 'mark')
         <a class="editbutton btn btn-default" data-toggle="modal" data={{ $data->id }}
             href="{{ route($route . '.edit', $data->subject_id) }}" title="edit"><i class="fa fa-edit"></i></a>
     @elseif ($route == 'admission')
         <button class="btn btn-primary">
         <a data-toggle="modal" class="btn btn-primary btn-sm" data={{ $data->id }} href="{{ route($route . '.edit', $data->id) }}"
         title="edit" style="color:white;font-size: .875rem !important;">On Board</a>
         </button>
    @elseif ($route == 'contactus')
            <a class="editbutton btn btn-default" data-toggle="modal" data={{ $data->id }}
                href="{{ route($route . '.show', $data->id) }}" title="view"><i class="fa fa-eye"></i></a>  
     @else
     @if($route == "classtimetable")
        @if(Session::get("ACTIVE_GROUP") !== "Student")
                <a class="editbutton btn btn-default abc" data-toggle="modal" data={{ $data->id }}
                    href="{{ route($route . '.edit', $data->id) }}" title="edit"><i class="fa fa-edit"></i></a>
        @endif
     @else
        @if($route == 'attendance')
            <a class="editbutton btn btn-default" data-toggle="modal" data={{ $data->attendance_id }}
                href="{{ route($route . '.edit', $data->attendance_id) }}" title="edit"><i class="fa fa-edit"></i></a>  
        @else
            @if($route != 'member')
                <a class="editbutton btn btn-default abd" data-toggle="modal" data={{ $data->id }}
                    href="{{ route($route . '.edit', $data->id) }}" title="edit"><i class="fa fa-edit"></i></a>
            @endif          
        @endif          
     @endif
     @endif

     @if ($route != 'wallet' && $route != 'order' && $route != 'attendance') 
         @if (CGate::allows('edit-' . $route) && CGate::allows('edit-' . @$subroute))

             @if ($route == 'exam')
                 @php
                     $date = \Carbon\Carbon::now()->toDateString();
                     $subdate = \Carbon\Carbon::parse($data->exam_date)->format('Y-m-d');

                 @endphp
                 @if ($route != 'exam' && $route != 'onlinexam')
                     @if ($route != 'academicyear')
                         @if ($date < $subdate)
                             <a class="editbutton btn btn-default" data-toggle="modal" data={{ $data->id }}
                                 href="{{ route($route . '.edit', $data->id) }}" title="edit"><i
                                     class="fa fa-edit"></i></a>
                         @endif
                     @else
                         @if ($route == 'admission')
                             <a class="editbutton btn btn-default" data-toggle="modal" data={{ $data->id }}
                                 href="{{ route($route . '.edit', $data->id) }}" title="edit">On Board</a>
                         @else
                             <a class="editbutton btn btn-default" data-toggle="modal" data={{ $data->id }}
                                 href="{{ route($route . '.edit', $data->id) }}" title="edit"><i
                                     class="fa fa-edit"></i></a>
                         @endif

                     @endif
                 @endif

             @endif
         @endif
     @endif




     @if (
         $route == 'teacher' ||
             $route == 'students' ||
             $route == 'chapter' ||
             $route == 'chaptertopic' ||
             $route == 'user' ||
             $route == 'order' ||
             $route == 'exam' ||
             $route == 'classtimetable' ||
             $route == 'attendance' ||
             $route == 'academicyear' ||
             $route == 'grade' ||
             $route == 'examterm' ||
             $route == 'ExamTimetable')

         @if ($route == 'attendance')
             <a class="editbutton btn btn-default c" data-toggle="modal" data={{ $data->id }}
                 href="{{ route($route . '.show', ['id' => $data->id, 'type' => $type,]) }}" title="view"><i
                     class="fa fa-eye"></i></a>
         @elseif ($route == 'mark')
             <a class="editbutton btn btn-default b" data-toggle="modal" data={{ $data->subject_id }}
                 href="{{ route($route . '.show', $data->subject_id) }}" title="view"><i class="fa fa-eye"></i></a>
         @else
            @if(@$route == "exam")
              @if($data->type_of_exam != "Quiz")
                <a class="editbutton btn btn-default a {{$data->type_of_exam}}" data-toggle="modal" data={{ $data->id }}
                    href="{{ route($route . '.show', $data->id) }}" title="view"><i class="fa fa-eye"></i></a>
              @endif
            @else
             <a class="editbutton btn btn-default" data-toggle="modal" data={{ $data->id }}
                 href="{{ route($route . '.show', $data->id) }}" title="view"><i class="fa fa-eye"></i></a>
            @endif     
         @endif


     @endif
     @if ($route == 'leave')
         <a class="editbutton btn btn-default viewroute" id={{ $data->id }} href="#" title="view"
             onclick="AcademicConfig.Viewleave(this.id)"> <i class="fa fa-eye"></i></a>
     @endif

     @if ($route == 'mark')
         <a class="editbutton btn btn-default" data-toggle="modal" data={{ $data->subject_id }}
             href="{{ route($route . '.show', $data->subject_id) }}" title="view"><i class="fa fa-eye"></i></a>
     @endif

     @if ($route == 'students')
         <a class="editbutton btn btn-default viewroute" id={{ $data->id }}
             href="{{ route('students.printidcard', $data->id) }}" title="printidcard" target="_blank"> <i
                 class='bx bxs-dock-top'></i></a>
     @endif

     @if ($route == 'member')
         <a class="editbutton btn btn-default viewroute" id={{ $data->id }}
             href="{{ route($route . '.show', $data->id) }}" title="view Member card"> <i class="fa fa-eye"></i></a>
     @endif
     @if ($route == 'chapter')
     @endif

     @if ($route == 'issuebook')
         <a class="editbutton btn btn-default" id={{ $data->id }}
             href="{{ route($route . '.returnBook', $data->id) }}" title="return book"> <i
                 class="fa fa-mail-forward"></i></a>
     @endif

     @if ($route == 'library' || $route == 'member')
         <a class="editbutton btn btn-default" id={{ $data->id }}
             href="{{ route($route . 'historybook', $data->id) }}" title="history"> <i class="fa fa-history"></i></a>

         @if (CGate::allows('delete-' . $route) && CGate::allows('delete-shop'))

             @if (@$data->order_status)

                 @if (@$data->order_status != 3)
                     <form method="post" action="{{ route($route . '.destroy', $data->id) }}">
                         <!-- here the '1' is the id of the post which you want to delete -->

                         {{ csrf_field() }}
                         {{ method_field('DELETE') }}

                         <button class="editbutton btn btn-default delete a" type="submit"><i class="fa fa-trash delete"
                                 title="delete"></i></button>
                     </form>
                 @endif
             @else
                 @if ($route != 'attendance' && $route != 'member')
                     <form method="post" action="{{ route($route . '.destroy', $data->id) }}">
                         <!-- here the '1' is the id of the post which you want to delete -->

                         {{ csrf_field() }}
                         {{ method_field('DELETE') }}

                         <button class="editbutton btn btn-default delete b" type="submit"><i class="fa fa-trash delete"
                                 title="delete"></i></button>
                     </form>
                 @endif


             @endif


         @endif

     @endif
     @if ($route == 'exam' || $route == 'onlinexam')
        @if($route == "exam")
          @if($data->type_of_exam != "Quiz")
            <a class="editbutton btn btn-default" data-toggle="modal" data={{ $data->id }}
                href="{{ route('duplicatEexam', ['id' => $data->id, 'type' => 'duplicate']) }}" title="duplicate"><i
                    class="fa fa-copy"></i></a>
          @endif
        @else
            <a class="editbutton btn btn-default" data-toggle="modal" data={{ $data->id }}
                href="{{ route('duplicatEexam', ['id' => $data->id, 'type' => 'duplicate']) }}" title="duplicate"><i
                    class="fa fa-copy"></i></a>
        @endif         
     @endif

     @if ($route == 'classtimetable' && Session::get("ACTIVE_GROUP") !== "Student" && Session::get("ACTIVE_GROUP") !== "Teacher")
         <a class="editbutton btn btn-default" data-toggle="modal" data={{ $data->id }}
             href="{{ route('clonetimetable', ['id' => $data->id]) }}" title="clone"><i class="fa fa-copy"></i></a>
     @endif

     @if ($route == 'ExamTimetable')
         <a class="editbutton btn btn-default" data-toggle="modal" data={{ $data->id }}
             href="{{ route('cloneexamtimetable', ['id' => $data->id]) }}" title="clone"><i
                 class="fa fa-copy"></i></a>
     @endif

     @if (@$subroute == 'onlineexam')
         <a class="editbutton btn btn-default" data-toggle="modal" data={{ $data->id }}
             href="{{ route('onlinexamreport', ['id' => $data->id]) }}" title="report"><i
                 class="fa fa-file"></i></a>
     @endif

     @if ($route == 'exam' && @$subroute != 'onlineexam')
       @if($data->type_of_exam == "Quiz")
            <a class="editbutton btn btn-default" data-toggle="modal" data={{ $data->id }}
                href="{{ route('onlinexamreport', ['id' => $data->id]) }}" title="report"><i
                    class="fa fa-file"></i></a>
       @else
         <a class="editbutton btn btn-default" data-toggle="modal" data={{ $data->id }}
             href="{{ route('offlinexamreport', ['id' => $data->id]) }}" title="report"><i
                 class="fa fa-file"></i></a>
        @endif         
     @endif

     @if ($route == 'markdistribution')
         <a class="distribute btn btn-outline btn-sm"
             style="border-color:#7F01BA !important; color:#7F01BA !important;" id={{ $data->id }}
             href="{{ route('distribute_score', ['id' => $data->id]) }}" title="score">Distribute Score</a>
     @endif
     @if ($route == 'mark')

         <form method="post" action="{{ route($route . '.destroy', $data->subject_id) }}">
             <!-- here the '1' is the id of the post which you want to delete -->

             {{ csrf_field() }}
             {{ method_field('DELETE') }}

             <button class="editbutton btn btn-default delete" type="submit"><i class="fa fa-trash delete"
                     title="delete"></i></button>
         </form>
     @else
         @if ($route != 'admission')
            @if($route == "classtimetable")
              @if(Session::get("ACTIVE_GROUP") !== "Student")
                <form method="post" action="{{ route($route . '.destroy', $data->id) }}">
                    <!-- here the '1' is the id of the post which you want to delete -->

                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}

                    <button class="editbutton btn btn-default delete" type="submit"><i class="fa fa-trash delete"
                            title="delete"></i></button>
                </form>
              @endif
            @else
               @if($route == "attendance")
                    <form method="post" action="{{ route('attendance_delete', [$data->id, $attendance_id]) }}">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                            <button class="editbutton btn btn-default delete" type="submit">
                                <i class="fa fa-trash delete" title="delete"></i>
                            </button>
                    </form>
            
               @else
                    <form method="post" action="{{ route($route . '.destroy', $data->id) }}">
                        <!-- here the '1' is the id of the post which you want to delete -->

                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}

                        <button class="editbutton btn btn-default delete" type="submit"><i class="fa fa-trash delete"
                                title="delete"></i></button>
                    </form>
               @endif
            @endif 
         @endif
     @endif
 </div>
