<?php
namespace App\Http\Controllers\Api\student;

use App\Exceptions\GeneralJsonException;
use Session;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Otpverification;
use Illuminate\Http\JsonResponse;
use cms\core\user\Models\UserModel;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use App\Traits\AuthTrait;
use cms\core\user\Models\OtpVerificationModel;
use cms\core\usergroup\Models\UserGroupMapModel;
use cms\core\usergroup\Models\UserGroupModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Configurations;
use cms\exam\Models\OfflineExamMarkEntry;
use Illuminate\Support\Facades\Http;
use cms\students\Models\StudentsModel;
use cms\exam\Models\ExamModel;
use cms\subject\Models\SubjectModel;
use cms\lclass\Models\LclassModel;
use cms\section\Models\SectionModel;
use cms\exam\Models\ExamSectionModel;
use cms\exam\Models\ExamQuestionModel;
use cms\exam\Models\OnlineExamModel;
use cms\exam\Models\OnlineExamSubmissionModel;
use cms\teacher\Models\TeacherModel;
use cms\classteacher\Models\ClassteacherModel;
use cms\homework\Models\HomeworkSubmissionModel;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;
use DB;

class ExamController extends Controller
{
    use ApiResponse;

    public function examschedules(
        Request $request,
        $examType = null,
        $filter = null
    ) {
        try {
            $user = $request->user();
            if (!$user || !$user->student) {
                $current_academic_year = Configurations::getCurrentAcademicyear();
                $teacher_id = TeacherModel::where("user_id", $user->id)
                    ->pluck("id")
                    ->first();
                $classteacher = ClassteacherModel::with("class", "section")
                    ->where([
                        "academic_year" => $current_academic_year,
                        "teacher_id" => $teacher_id,
                    ])
                    ->first();
                $name = $user->name;
                $class_id = $classteacher->class_id;
                $section_id = $classteacher->section_id;
            } else {
                $student = $user->student;
                $student_id = $student->id;
                $reg_no = $student->reg_no;
                $name = $student->first_name;
                $class_id = $student->class_id;
                $section_id = $student->section_id;
            }
            if ($filter == "month") {
                $start_month = Carbon::now()
                    ->startOfMonth()
                    ->format("m/d/Y");
                $end_month = Carbon::now()
                    ->endOfMonth()
                    ->format("m/d/Y");
                // dd($start_month, $end_month);
                $exam_types = ExamModel::with([
                    "subject:id,name",
                    "class:id,name",
                    "section:id,name",
                ])
                    ->select(
                        "id",
                        "exam_time",
                        "exam_title",
                        "academic_year",
                        "exam_type",
                        "exam_term",
                        "type_of_exam",
                        "class_id",
                        "section_id",
                        "subject_id",
                        "exam_date",
                        "exam_submission_date",
                        "exam_submission_time"
                    )
                    ->where("class_id", $class_id)
                    ->where("section_id", $section_id)
                    ->where("status", 1)
                    ->whereDate("exam_date", ">=", $start_month)
                    ->whereDate("exam_date", "<=", $end_month)
                    ->when($examType, function ($query) use ($examType) {
                        return $query->where("type_of_exam", $examType);
                    })
                    ->orderBy("exam_date", "desc")
                    ->get();
            } elseif ($filter == "year") {
                $start_year = Carbon::now()
                    ->startOfYear()
                    ->format("m/d/Y");
                $end_year = Carbon::now()
                    ->endOfYear()
                    ->format("m/d/Y");
                // dd($start_month, $end_month);
                $exam_types = ExamModel::with([
                    "subject:id,name",
                    "class:id,name",
                    "section:id,name",
                ])
                    ->select(
                        "id",
                        "exam_time",
                        "exam_title",
                        "academic_year",
                        "exam_type",
                        "exam_term",
                        "type_of_exam",
                        "class_id",
                        "section_id",
                        "subject_id",
                        "exam_date",
                        "exam_submission_date",
                        "exam_submission_time"
                    )
                    ->where("class_id", $class_id)
                    ->where("section_id", $section_id)
                    ->where("status", 1)
                    ->whereDate("exam_date", ">=", $start_year)
                    ->whereDate("exam_date", "<=", $end_year)
                    ->when($examType, function ($query) use ($examType) {
                        return $query->where("type_of_exam", $examType);
                    })
                    ->orderBy("exam_date", "desc")
                    ->get();
            } else {
                $exam_types = ExamModel::with([
                    "subject:id,name",
                    "class:id,name",
                    "section:id,name",
                ])
                    ->select(
                        "id",
                        "exam_time",
                        "exam_title",
                        "academic_year",
                        "exam_type",
                        "exam_term",
                        "type_of_exam",
                        "class_id",
                        "section_id",
                        "subject_id",
                        "exam_date",
                        "exam_submission_date",
                        "exam_submission_time"
                    )
                    ->where("class_id", $class_id)
                    ->where("section_id", $section_id)
                    ->where("status", 1)
                    ->when($examType, function ($query) use ($examType) {
                        return $query->where("type_of_exam", $examType);
                    })
                    ->orderBy("exam_date", "desc")
                    ->get();
            }
            // $exam_types = $exam_types->transform(function ($exam) {
            //     $exam->exam_date = Carbon::parse($exam->exam_date)->format(
            //         "d/m/Y"
            //     );
            //     return $exam;
            // });
            // return $exam_types;

            //Exam_id -Exam- Complete-or-not===================>>>>start

            $exam_data = [];

            foreach ($exam_types as $exam) {
                $exam_id = ExamModel::where("id", $exam->id)->first();

                $timeline = $exam_id->timeline;
                // Store the timeline for each exam ID in the $total_exam_time array
                $total_exam_time[$exam->id] = $timeline;

                if ($exam_id) {
                    // $total_exam_time = $exam_id;
                    //$total_exam_time = $exam->timeline;
                    $total_exam_time = [];
                    $total_exam_time[$exam->id] = $timeline;

                    $exam_question_id = ExamQuestionModel::where(
                        "exam_id",
                        $exam->id
                    )
                        ->pluck("id")
                        ->toArray();

                    $online_exam = OnlineExamModel::where(
                        "exam_id",
                        $exam->id
                    )->first();

                    if ($online_exam) {
                        $online_exam_id = $online_exam->id;

                        $online_exam_submission = OnlineExamSubmissionModel::where(
                            "online_exam_id",
                            $online_exam_id
                        )->first();

                        $is_exam_complete = $online_exam_submission ? 1 : 0;

                        // Store the exam data
                        $exam_data[] = [
                            "Exam_id" => $exam->id,
                            "Exam_time" => $total_exam_time,
                            "Online_id" => $online_exam_id,
                            "Exam_question_id" => $exam_question_id,

                            "Is_exam_complete" => $is_exam_complete,
                        ];
                    } else {
                        $is_exam_complete = 0;
                        $exam_data[] = [
                            "Exam_id" => $exam->id,
                            "Exam_time" => $total_exam_time,
                            "Is_exam_complete" => $is_exam_complete,
                        ];
                    }
                }
            }

            // return response(
            //     [
            //         "Exam-id" => $exam_id,
            //         "timeline" => $total_exam_time,
            //     ],
            //     200
            // );
            // Return the response with all exam data
            // return response()->json($exam_data, 200);
            //Exam_id -Exam- Complete-or-not===================>>>>end

            $offline_exams = [];
            $online_exams = [];
            $quiz_exams = [];
            $homework = [];
            // dd($exam_types);
            // Separate exams based on type
            foreach ($exam_types as $exam) {
                $exam_info = [
                    "id" => $exam->id,
                    "exam_time" => $exam->exam_time,
                    "exam_title" => $exam->exam_title,
                    "academic_year" => $exam->academic_year,
                    "exam_type" => $exam->exam_type,
                    "exam_term" => isset(
                        Configurations::TERMNAMES[$exam->exam_term]
                    )
                        ? Configurations::TERMNAMES[$exam->exam_term]
                        : "Term Not Found",
                    "type_of_exam" => ucfirst(strtolower($exam->type_of_exam)),
                    "class_id" => $exam->class
                        ? $exam->class->name
                        : "Class Not Found",
                    "section_id" => $exam->section
                        ? $exam->section->name
                        : "Section Not Found",
                    "subject_id" => $exam->subject
                        ? $exam->subject->name
                        : "Subject Not Found",
                    "exam_date" => date("m/d/Y", strtotime($exam->exam_date)),
                    "submission_date" =>
                        $exam->exam_submission_date &&
                        $exam->exam_submission_time
                            ? date(
                                "m/d/Y",
                                strtotime($exam->exam_submission_date)
                            )
                            : null,
                    "submission_time" =>
                        $exam->exam_submission_date &&
                        $exam->exam_submission_time
                            ? $exam->exam_submission_time
                            : null,
                ];

                if (strtolower($exam->type_of_exam) === "online") {
                    $exam_info["Exam_total_time"] = $total_exam_time; // Add total exam time

                    // Search for the corresponding exam data in $exam_data
                    $exam_data_entry =
                        array_values(
                            array_filter($exam_data, function ($item) use (
                                $exam
                            ) {
                                return $item["Exam_id"] === $exam->id;
                            })
                        )[0] ?? null;

                    if ($exam_data_entry) {
                        $exam_info["Exam_total_time"] =
                            $exam_data_entry["Exam_time"][$exam->id];

                        $exam_info["Is_exam_complete"] =
                            $exam_data_entry["Is_exam_complete"];
                    } else {
                        $exam_info["Is_exam_complete"] = 0;
                    }

                    $online_exams[] = $exam_info;
                } elseif (strtolower($exam->type_of_exam) === "quiz") {
                    // Group quiz exams by exam date
                    $quiz_exams[] = $exam_info;
                } elseif (strtolower($exam->type_of_exam) === "homework") {
                    if ($user->student) {
                        $student_id = StudentsModel::where("user_id", $user->id)
                            ->pluck("id")
                            ->first();
                        $is_exists = HomeworkSubmissionModel::where([
                            "homework_id" => $exam->id,
                            "student_id" => $student_id,
                        ])->first();
                        if ($is_exists) {
                            $exam_info["is_submitted"] = 1;
                        } else {
                            $exam_info["is_submitted"] = 0;
                        }
                    } else {
                        $is_exists = OfflineExamMarkEntry::where([
                            "exam_id" => $exam->id,
                        ])->first();
                        if ($is_exists) {
                            $student_ids = StudentsModel::where([
                                "academic_year" => $exam->academic_year,
                                "class_id" => $exam->class_id,
                                "section_id" => $exam->section_id,
                                "status" => 1,
                            ])
                                ->whereNull("deleted_at")
                                ->whereNull("deleted_by")
                                ->pluck("id");
                            $students_submitted = 1;
                            foreach ($student_ids as $id) {
                                $submitted = OfflineExamMarkEntry::where([
                                    "exam_id" => $exam->id,
                                ])
                                    ->where("student_id", $id)
                                    ->whereNotNull("score")
                                    ->exists();
                                if (!$submitted) {
                                    $students_submitted = 0;
                                }
                            }

                            if ($students_submitted == 0) {
                                $exam_info["mark_submission"] = 2;
                            } else {
                                $exam_info["mark_submission"] = 1;
                            }
                        } else {
                            $exam_info["mark_submission"] = 0;
                        }
                    }

                    $homework[] = $exam_info;
                } else {
                    // Group offline exams by exam date
                    $offline_exams[] = $exam_info;
                }
            }

            // Return the response based on the exam type
            if ($examType === "online" || $examType === "Online") {
                $content = [
                    "Exam-Schedules" => "Online Exams",
                    "student_id" => $student_id ?? "NA",
                    "reg_no" => $reg_no ?? "NA",
                    "name" => $name,
                    "class_id" => $class_id,
                    "section_id" => $section_id,
                    "total" => count($online_exams),
                ];
                $data = $online_exams;
            } elseif ($examType === "Quiz") {
                $formatted_offline_exams = $quiz_exams;
                // dd($offline_exams);
                // foreach ($quiz_exams as $exam_date => $exams) {
                //     $formatted_offline_exams[] = [$exams];
                // }
                $data = $formatted_offline_exams;
                $content = [
                    "Exam-Schedules" => "Quiz Exams",
                    "student_id" => $student_id ?? "NA",
                    "reg_no" => $reg_no ?? "NA",
                    "name" => $name,
                    "class_id" => $class_id,
                    "section_id" => $section_id,
                    "total" => count($formatted_offline_exams),
                ];
            } elseif ($examType === "Homework") {
                $formatted_homework_exams = $homework;
                $content = [
                    "Exam-Schedules" => "Exams",
                    "student_id" => $student_id ?? "NA",
                    "reg_no" => $reg_no ?? "NA",
                    "name" => $name,
                    "class_id" => $class_id,
                    "section_id" => $section_id,
                    "total" => count($formatted_homework_exams),
                ];
                $data = $formatted_homework_exams;
            } else {
                // Reformat the offline exams data to match the desired output

                $formatted_offline_exams["exam_data"] = $offline_exams;

                $content = [
                    "Exam-Schedules" => "Offline Exams",
                    "student_id" => $student_id ?? "NA",
                    "reg_no" => $reg_no ?? "NA",
                    "name" => $name,
                    "class_id" => $class_id,
                    "section_id" => $section_id,
                ];
                $data = $formatted_offline_exams;

                return $this->success(
                    [$content, $data],
                    "Data Feteched Successfully",
                    200
                );
            }
            $page = request()->get("page", 1); // Get the current page or default to 1
            $perPage = 10; // Items per page

            $collection = collect($data);
            $currentPageItems = $collection
                ->slice(($page - 1) * $perPage, $perPage)
                ->values();

            $paginator = new LengthAwarePaginator(
                $currentPageItems,
                $collection->count(),
                $perPage,
                $page,
                ["path" => request()->url(), "query" => request()->query()]
            );

            return $this->success(
                [$content, $paginator],
                "Data Feteched Successfully",
                200
            );
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }
    public function examquestionsanswer(Request $request, $examid = null)
    {
        try {
            $user = $request->user();
            // if (!$user || !$user->student) {
            //     return response()->json(["error" => "Student not found"], 404);
            // }

            $exam_timeline = ExamModel::with("subject")
                ->where("id", $examid)
                ->first();

            $is_boolean_image;

            if ($exam_timeline) {
                $exam_question = ExamQuestionModel::where(
                    "exam_id",
                    $examid
                )->first();

                $exam_qustion_type;
                $exam_mark;

                if ($exam_question) {
                    $exam_section = ExamQuestionModel::where(
                        "exam_id",
                        $examid
                    )->first();

                    $exam_quiz = ExamQuestionModel::select(
                        "question_type",
                        "question",
                        "options",
                        "answer",
                        "mark",
                        "id"
                    )
                        ->where("exam_id", $examid)
                        ->get();

                    $exam_quiz_data = ExamQuestionModel::where(
                        "exam_id",
                        $examid
                    )
                        ->pluck("id")
                        ->toArray();
                    if ($exam_timeline->type_of_exam != "Quiz") {
                        if ($exam_section) {
                            $exam_section_data = ExamSectionModel::where(
                                "id",
                                $exam_section->section_id
                            )
                                ->where("status", 1)
                                ->first();
                        }
                    }

                    foreach ($exam_quiz as $question) {
                        $question->options = explode(",", $question->options);
                    }

                    $is_boolean_images = [];

                    foreach ($exam_quiz_data as $question_id) {
                        $exam_question = ExamQuestionModel::find($question_id);

                        if ($exam_question) {
                            $exam_attachment = ExamQuestionModel::where(
                                "exam_id",
                                $examid
                            )
                                ->where("id", $exam_question->id)
                                ->first();

                            $image_question = $exam_attachment
                                ? $exam_attachment->attachment
                                : "";
                            $is_boolean_image = $image_question ? 1 : 0;

                            // Add image URL and boolean flag to each corresponding question
                            foreach ($exam_quiz as $question) {
                                if ($question->id == $question_id) {
                                    $question->image_question = $image_question;
                                    $is_boolean_images[] = $is_boolean_image;
                                    break;
                                }
                            }
                        }
                    }
                } else {
                    return response()->json(
                        [
                            "Exam_id" => $examid,
                            "message" => "Does not match",
                        ],
                        500
                    );
                }
            }
            return response(
                [
                    "exam_quiz" => $exam_quiz,
                    "exam_total_mark" =>
                        $exam_section_data->section_mark ??
                        $exam_timeline->max_mark,
                    "exam_time" => $exam_timeline->timeline,
                    "subject" => $exam_timeline->subject,
                ],
                200
            );
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    public function storequestionsanswer(Request $request)
    {
        // dd("yes");
        try {
            $user = $request->user();
            if (!$user || !$user->student) {
                $current_academic_year = Configurations::getCurrentAcademicyear();
                $teacher_id = TeacherModel::where("user_id", $user->id)
                    ->pluck("id")
                    ->first();
                $classteacher = ClassteacherModel::with("class", "section")
                    ->where([
                        "academic_year" => $current_academic_year,
                        "teacher_id" => $teacher_id,
                    ])
                    ->first();
            }

            $student = $user->student ?? "NA";
            $student_id = $student->id ?? "NA";
            $reg_no = $student->reg_no ?? "NA";
            $name = $student->first_name ?? $user->name;
            $class_id = $student->class_id ?? $classteacher->class_id;
            $section_id = $student->section_id ?? $classteacher->section_id;

            $exam_id = $request->exam_id;
            $your_answer = $request->your_answer;

            foreach ($your_answer as $answer) {
                if (is_numeric($answer)) {
                    $answer = intval($answer);
                }
            }

            $exam_date_time = ExamModel::where("id", $exam_id)->first();

            if ($exam_date_time) {
                $exam_submit_date = $exam_date_time->exam_date;
                $exam_submit_time = $exam_date_time->exam_time;

                $exam_questions = ExamQuestionModel::where(
                    "exam_id",
                    $exam_id
                )->get();

                $converted_array = [];
                $total_mark = 0;
                $total_question = 0;
                $total_answer = 0;
                $total_correct = 0;
                $correct_answer = 0;
                // dd($exam_questions[0]->mark);
                foreach ($your_answer as $key => $answer) {
                    $question = $exam_questions[$key];

                    $correct_answer = $question->answer;
                    // $is_correct = $question->correct_answer == $answer;

                    if (is_int($answer)) {
                        // If the answer is an integer, compare it directly with the correct answer
                        $is_correct = $correct_answer == $answer;
                    } else {
                        // If the answer is a string, convert the correct answer to a string and compare
                        $is_correct = strval($correct_answer) === $answer;
                    }

                    $is_mark = $question->mark;
                    $total_question++;
                    $total_answer++;

                    if ($is_correct) {
                        $total_mark = $total_mark + $is_mark;
                        $total_correct++;
                    }

                    $converted_array[] = [
                        "question_id" => $question->id,
                        "mark" => $is_mark,
                        "your_answer" => $answer,
                        "correct_answer" => $correct_answer,
                        "is_correct" => $is_correct,
                        "Total" => $total_mark,
                    ];
                }

                $score = $total_mark;

                if ($score) {
                    // Get the rank of the current student
                    $rank =
                        OnlineExamModel::where("exam_id", $request->exam_id)
                            ->where("total_marks", ">", $score)
                            ->count() + 1;
                } else {
                    $rank = null;
                }

                DB::beginTransaction();
                $onlineexam = new OnlineExamModel();
                $onlineexam->exam_id = $exam_id;
                $onlineexam->academic_year = $exam_date_time->academic_year;
                $onlineexam->student_id = $student_id;
                $onlineexam->total_questions = $total_question;
                $onlineexam->total_answered = $total_answer;
                $onlineexam->total_correct = $total_correct;
                $onlineexam->total_marks = $score;
                $onlineexam->position = $rank;
                $onlineexam->submit_date = $exam_submit_date;
                $onlineexam->submit_time = $exam_submit_time;

                if ($onlineexam->save()) {
                    $online_exam = OnlineExamModel::where("exam_id", $exam_id)
                        ->latest()
                        ->first();
                    $online_exam_id = $online_exam ? $online_exam->id : null;
                    // dd($converted_array);
                    foreach ($converted_array as $answer) {
                        $online_exam_submit = new OnlineExamSubmissionModel();
                        $online_exam_submit->online_exam_id = $online_exam->id;
                        $online_exam_submit->question_id =
                            $answer["question_id"];
                        $online_exam_submit->correct_answer =
                            $answer["correct_answer"];
                        $online_exam_submit->your_answer =
                            $answer["your_answer"];
                        $online_exam_submit->mark = $answer["mark"];
                        $online_exam_submit->is_correct = $answer["is_correct"];
                        $online_exam_submit->save();
                    }
                }
            } else {
                return $this->error(
                    "We can't Find Information About This Exam_id  $request->exam_id",
                    400
                );
            }

            DB::commit();
            return response(
                [
                    "student_id" => $student_id,
                    "reg_no" => $reg_no,
                    "name" => $name,
                    "academmic_year" => $exam_date_time->academic_year,
                    "class_id" => $class_id,
                    "section_id" => $section_id,
                    "exam_id" => $exam_id,
                    "online_exam_id" => $online_exam_id,
                    "Total_Question" => $total_question,
                    "Total_answer" => $total_answer,
                    "Total_COrrect" => $total_correct,
                    "exam_submit_date" => $exam_submit_date,
                    "exam_submit_time" => $exam_submit_time,
                    "your_answer" => $converted_array,
                ],
                200
            );
        } catch (\Exception $e) {
            DB::rollback();
            $message = str_replace(
                ["\r", "\n", "'", "`"],
                " ",
                $e->getMessage()
            );

            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    public function ExamReport(Request $request, $id = null)
    {
        // dd($id);
        $user_id = $request->user()->id;
        $current_academic_year = Configurations::getCurrentAcademicyear();
        $teacher_id = TeacherModel::where("user_id", $user_id)
            ->pluck("id")
            ->first();
        $classteacher = ClassteacherModel::with("class", "section")
            ->where([
                "academic_year" => $current_academic_year,
                "teacher_id" => $teacher_id,
            ])
            ->first();
        $students = StudentsModel::with("user", "class", "section")
            ->where([
                "academic_year" => $current_academic_year,
                "class_id" => $classteacher->class_id,
                "section_id" => $classteacher->section_id,
                "status" => 1,
            ])
            ->whereNull("deleted_by")
            ->get();
        $exam = ExamModel::find($id);
        // dd($current_academic_year, $classteacher->section);
        $exam_results = [];
        if ($exam->type_of_exam == "Homework") {
            $questions = ExamQuestionModel::where("exam_id", $id)->get();
            if ($students) {
                foreach ($students as $student) {
                    $student_answer = HomeworkSubmissionModel::where([
                        "student_id" => $student->id,
                        "homework_id" => $id,
                    ])->first();

                    $mark = OfflineExamMarkEntry::where([
                        "exam_id" => $id,
                        "student_id" => $student->id,
                    ])->first();

                    $exam_results[] = [
                        "student" => $student,
                        "exam" => $exam,
                        "questions" => $questions,
                        "student_answer" => $student_answer,
                        "marks" => $mark ?? "Not Added",
                    ];
                }
            } else {
                return $this->error("Students Not Found", 500);
            }
        }
        if (
            $exam->type_of_exam == "Quiz" ||
            $exam->type_of_exam == "online" ||
            $exam->type_of_exam == "Online"
        ) {
            $questions = ExamQuestionModel::where("exam_id", $id)->get();
            $questions = $questions->transform(function ($question) {
                if ($question->options) {
                    $options = explode(",", $question->options);
                    $question->options = collect($options)->map(function (
                        $option,
                        $index
                    ) {
                        return [
                            "id" => $index,
                            "text" => trim($option),
                        ];
                    });
                }
                return $question;
            });
            if ($students) {
                foreach ($students as $student) {
                    $online_exam_id = OnlineExamModel::where([
                        "exam_id" => $id,
                        "student_id" => $student->id,
                        "academic_year" => $current_academic_year,
                    ])
                        ->pluck("id")
                        ->first();
                    $student_answer = OnlineExamSubmissionModel::where([
                        "online_exam_id" => $online_exam_id,
                    ])->get();

                    $mark = OnlineExamModel::where([
                        "exam_id" => $id,
                        "student_id" => $student->id,
                        "academic_year" => $current_academic_year,
                    ])->first();

                    $exam_results[] = [
                        "student" => $student,
                        "exam" => $exam,
                        "questions" => $questions,
                        "student_answer" => $student_answer,
                        "marks" => $mark,
                    ];
                }
            }
        }

        if (
            $exam->type_of_exam == "offline" ||
            $exam->type_of_exam == "Offline"
        ) {
            $questions = ExamQuestionModel::where("exam_id", $id)->get();
            $questions = $questions->transform(function ($question) {
                if ($question->options) {
                    $options = explode(",", $question->options);
                    $question->options = collect($options)->map(function (
                        $option,
                        $index
                    ) {
                        return [
                            "id" => $index,
                            "text" => trim($option),
                        ];
                    });
                }
                return $question;
            });
            if ($students) {
                foreach ($students as $student) {
                    $mark = OfflineExamMarkEntry::where([
                        "exam_id" => $id,
                        "student_id" => $student->id,
                    ])->first();
                    $exam_results[] = [
                        "student" => $student,
                        "exam" => $exam,
                        "questions" => $questions,
                        "marks" => $mark,
                    ];
                }
            }
        }

        return $this->success($exam_results, "Data Fetched Successfully", 200);
    }

    public function SubmitExamMark(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make(
            $request->all(),
            [
                "marks" => "required|array",
            ],
            [
                "marks.*.*.min" =>
                    "The marks must be greater than or equal to zero.",
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                [
                    "status" => "error",
                    "message" => "Validation failed",
                    "errors" => $validator->errors(),
                ],
                422
            );
        }
        try {
            $status = "";
            if ($request->isMethod("post")) {
                $isExists = OfflineExamMarkEntry::where(
                    "exam_id",
                    $request->exam_id
                )->exists();
                // $exam_mark = ExamModel::find($request->exam_id);
                // dd($exam_mark->max_mark);
                foreach ($request->marks as $student_id => $marks) {
                    foreach ($marks as $mark) {
                        $exam_mark = ExamModel::find($request->exam_id);
                        $mark_status = $mark >= $exam_mark->min_mark ? 1 : 2;
                        $total_marks = $exam_mark->max_mark;

                        $marks = OfflineExamMarkEntry::where(
                            "exam_id",
                            $request->exam_id
                        )->get();
                        $score = $marks
                            ->where("student_id", $student_id)
                            ->sortByDesc("score")
                            ->first();

                        // dd($score);
                        if ($score) {
                            $rank =
                                $marks
                                    ->where("exam_id", $request->exam_id)
                                    ->where("score", ">", $score->score)
                                    ->count() + 1;
                            // dd($rank);
                        } else {
                            $rank = null;
                        }

                        $student_rank = $rank;
                        if ($isExists) {
                            $update_entry = OfflineExamMarkEntry::where([
                                "student_id" => $student_id,
                                "exam_id" => $request->exam_id,
                            ])->first();

                            if ($update_entry) {
                                $update_entry->score =
                                    $mark != "NA" ? $mark : null;
                                $update_entry->total_score = $total_marks;
                                $update_entry->mark_status = $mark_status;
                                $update_entry->entry_date = date("Y-m-d");
                                $update_entry->position = $student_rank;
                                $update_entry->save();
                                $status = "Updates";
                            } else {
                                $mark_data = new OfflineExamMarkEntry();

                                $mark_data->student_id = $student_id;
                                $mark_data->exam_id = $request->exam_id;
                                $mark_data->score =
                                    $mark != "NA" ? $mark : null;
                                $mark_data->total_score = $total_marks;
                                $mark_data->mark_status = $mark_status;
                                $mark_data->entry_date = date("Y-m-d");
                                $mark_data->position = $student_rank;
                                $mark_data->exam_type = $request->exam_type;
                                $mark_data->save();
                                $status = "Updates";
                            }
                        } else {
                            $mark_data = new OfflineExamMarkEntry();

                            $mark_data->student_id = $student_id;
                            $mark_data->exam_id = $request->exam_id;
                            $mark_data->score = $mark != "NA" ? $mark : null;
                            $mark_data->total_score = $total_marks;
                            $mark_data->mark_status = $mark_status;
                            $mark_data->entry_date = date("Y-m-d");
                            $mark_data->position = $student_rank;
                            $mark_data->exam_type = $request->exam_type;
                            $mark_data->save();
                            $status = "Created";

                            if ($mark_data->save()) {
                                $marks = OfflineExamMarkEntry::where(
                                    "exam_id",
                                    $request->exam_id
                                )->get();
                                $score = $marks
                                    ->where("student_id", $student_id)
                                    ->sortByDesc("score")
                                    ->first();

                                // dd($score);
                                if ($score) {
                                    $std_rank = $marks
                                        ->where("exam_id", $request->exam_id)
                                        ->where("score", ">", $score->score)
                                        ->count();
                                    // dd($rank);
                                    $rank = $std_rank + 1;
                                } else {
                                    $rank = null;
                                }
                                $student_rank = $rank;
                                $create_position = OfflineExamMarkEntry::where([
                                    "student_id" => $student_id,
                                    "exam_id" => $request->exam_id,
                                ])->first();
                                $create_position->position = $student_rank;
                                $create_position->save();
                            }
                        }
                    }
                }

                return $this->success("Marks Submitted Successfully", 200);
            }
        } catch (\Exception $e) {
            DB::rollback();
            $message = str_replace(
                ["\r", "\n", "'", "`"],
                " ",
                $e->getMessage()
            );

            return response()->json(["error" => $e->getMessage()], 500);
        }
    }
    public function HomeworkSubmitView(Request $request, $exam_id)
    {
        // dd($id,$exam_id);
        $user_id = $request->user()->id;
        $student_id = StudentsModel::where("user_id", $user_id)
            ->pluck("id")
            ->first();
        $exam_id = $exam_id;
        if ($student_id !== 0 && $exam_id !== 0) {
            $exam = ExamModel::with("class", "section", "subject")
                ->where("id", $exam_id)
                ->first();
            $answer = HomeworkSubmissionModel::where([
                "student_id" => $student_id,
                "homework_id" => $exam_id,
            ])->first();
            //dd($homework);
            if ($exam) {
                $info = ExamQuestionModel::where("exam_id", $exam_id)->get();
                $data = [
                    "homework" => $exam,
                    "questions" => $info,
                    "answer" => $answer,
                ];
                return $this->success($data, "Data Feteched Successfully", 200);
            } else {
                return $this->error("Exam Not Found", 500);
            }
        }
    }
}
