<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use cms\exam\Models\OnlineExamSubmissionModel;
use cms\exam\Models\ExamNotificationModel;
use cms\exam\Models\ExamQuestionModel;
use cms\exam\Models\ExamSectionModel;
use cms\exam\Models\ExamModel;
use cms\exam\Models\ExamTypeModel;
use cms\exam\Models\OnlineExamModel;
use cms\teacher\Models\TeacherModel;
use cms\students\Models\StudentsModel;
use cms\classteacher\Models\ClassteacherModel;
use cms\subject\Models\SubjectTeacherMapping;
use cms\core\configurations\Traits\FileUploadTrait;
use cms\homework\Models\HomeworkSubmissionModel;
use cms\exam\Models\OfflineExamMarkEntry;
use App\Traits\ApiResponse;
use Configurations;
use DB;
use File;
use Carbon\Carbon;
use User;
use DateTime;

class QuizApiController extends Controller
{
    use ApiResponse, FileUploadTrait;

    public function QuizCreate(Request $request, $type = null)
    {
        $user_id = $request->user()->id;
        $exam_type = $type == "assignment" ? "Home work" : "online";
        $current_academic_year = Configurations::getCurrentAcademicyear();
        $current_academic_term = Configurations::getCurrentAcademicterm();
        $disableexamtype = OnlineExamModel::DISABLEEXAMTYPE;
        if ($exam_type == "online") {
            $exam_types = ExamTypeModel::where(
                "exam_type_name",
                "!=",
                "Home Work"
            )
                ->where("exam_type_name", "!=", "Admission")
                ->where("status", "=", 1)
                ->select("id", "exam_type_name")
                ->get();
        } else {
            $exam_types = ExamTypeModel::where("status", 1)
                ->whereNull("deleted_at")
                ->where("exam_type_name", "=", $exam_type)
                ->select("id", "exam_type_name")
                ->first();
        }

        $question_types =
            $exam_type == "online"
                ? Configurations::ONLINEEXAMQUESTIONTYPE
                : Configurations::HOMEWORKEXAMQUESTIONTYPE;

        $data = [
            "exam_types" => $exam_types,
            "question_types" => $question_types,
        ];

        return $this->success($data, "Successfully Data Fetched", 200);
    }

    public function Store(Request $request, $type = null)
    {
        // dd($request->all());
        $user_id = $request->user()->id;
        $current_academic_year = Configurations::getCurrentAcademicyear();
        $current_academic_term = Configurations::getCurrentAcademicterm();
        $teacher_id = TeacherModel::where("user_id", $user_id)
            ->pluck("id")
            ->first();
        $classteacher = ClassteacherModel::with("class", "section")
            ->where([
                "academic_year" => $current_academic_year,
                "teacher_id" => $teacher_id,
            ])
            ->first();

        $subject_id = $request->subject_id;

        if ($request->exam_title) {
            $exists = DB::table("exam")
                ->whereRaw(
                    "LOWER(exam_title) = ?",
                    strtolower($request->exam_title)
                )
                ->exists();

            if ($exists) {
                return $this->error(
                    "Whoops !! This Exam Title $request->exam_title already Exists Try With Diffrent Title",
                    500
                );
            }
        }
        $this->validate($request, [
            "examtype" => "required",
            "max_mark" => "required",
            "min_mark" => "required",
            "exam_date" => "required",
            "exam_time" => "required",
            "exam_title" => "required",
            // "questions.homework.*.image.*" =>
            //     "max:4000|mimes:pdf,jpg,jpeg,docx,png",

            // "section" => function ($attribute, $value, $fail) use ($request) {
            //     $totalSectionMarks = array_reduce(
            //         $value,
            //         function ($carry, $sectionData) {
            //             return $carry + $sectionData["totalmark"];
            //         },
            //         0
            //     );

            //     if ($totalSectionMarks > $request->max_mark) {
            //         $fail(
            //             "The total marks of all sections exceeds the maximum mark."
            //         );
            //     }
            // },
        ]);

        try {
            DB::beginTransaction();
            // dd($request->all());
            $obj = new ExamModel();
            $obj->academic_year = $current_academic_year;
            $obj->exam_type = $request->examtype;
            $obj->class_id = $classteacher->class_id;
            $obj->section_id = $classteacher->section_id;
            $obj->subject_id = $subject_id;
            $obj->examistruction = $request->examinstruction;
            $obj->exam_term = $current_academic_term;
            // $obj->show_results = 0;
            $obj->type_of_exam = $type == null ? "online" : "Homework";
            $obj->max_mark = $request->max_mark;
            $obj->min_mark = $request->min_mark;
            $obj->exam_title = $request->exam_title;

            $obj->exam_date =
                $request->exam_date ?? carbon::now()->format("Y/m/d");
            $obj->exam_time = date("g:i a", strtotime($request->exam_time));
            $obj->exam_submission_date = Carbon::parse(
                $request->exam_submission_date
            )->format("m/d/Y");
            $obj->exam_submission_time = date(
                "g:i a",
                strtotime($request->exam_submission_time)
            );
            // $obj->promotion = $request->promotion;
            // $obj->exam_percentage = $request->exam_percentage;
            $obj->timeline = $this->Gettimeline(
                $request->timeline ? $request->timeline : "01:00"
            );

            if ($request->type == "upload") {
                if ($request->upload_question) {
                    $obj->uploaded_file = $this->uploadAttachment(
                        $request->upload_question,
                        null,
                        "school/exam/"
                    );
                }
            }

            if ($obj->save()) {
                if ($request->notify_date) {
                    $notify = new ExamNotificationModel();
                    $notify->exam_id = $obj->id;
                    $notify->notify_date = $request->notify_date;
                    $notify->notify_time = $request->notify_time;
                    $notify->notify_message = $request->notify_message;
                    $notify->save();
                }
                if ($request->questions) {
                    foreach ($request->questions as $type => $question) {
                        if ($type == "fillblanks") {
                            foreach ($question as $order => $ques) {
                                $question_fill = new ExamQuestionModel();
                                $question_fill->exam_id = $obj->id;
                                // $question_fill->section_id = $exam_section->id;
                                $question_fill->order = $order;
                                $question_fill->question_type =
                                    "fillintheblanks";
                                $question_fill->question = $ques[0];
                                $question_fill->answer = $ques["answer"];
                                $question_fill->mark = $ques["mark"] ?? 0;
                                $question_fill->save();
                            }
                        }
                        if ($type == "homework") {
                            foreach ($question as $order => $ques) {
                                $question_homework = new ExamQuestionModel();
                                $question_homework->exam_id = $obj->id;
                                // $question_homework->section_id =
                                //     $exam_section->id;
                                $question_homework->order = $order;
                                $question_homework->question_type = "homework";
                                $question_homework->question = $ques[0];
                                $question_homework->answer = null;
                                $question_homework->mark = $ques["mark"] ?? 0;
                                if (
                                    isset($ques["image"][0]) &&
                                    is_file($ques["image"][0])
                                ) {
                                    $question_homework->attachment = $this->uploadAttachment(
                                        $ques["image"][0],
                                        null,
                                        "school/exam/"
                                    );
                                }
                                $question_homework->save();
                            }
                        }
                        if ($type == "choose_best") {
                            //dd($exam_section->id);
                            foreach ($question as $order => $ques) {
                                $question_choose = new ExamQuestionModel();
                                $question_choose->exam_id = $obj->id;
                                // $question_choose->section_id =
                                //     $exam_section->id;
                                $question_choose->order = $order;
                                $question_choose->question_type = "choosebest";
                                $question_choose->question = $ques[0];

                                $question_choose->options = implode(
                                    ",",
                                    $ques["options"]
                                );
                                $question_choose->answer = $ques["answer"];
                                $question_choose->mark = $ques["mark"];

                                if (
                                    isset($ques["image"][0]) &&
                                    is_file($ques["image"][0])
                                ) {
                                    $question_choose->attachment = $this->uploadAttachment(
                                        $ques["image"][0],
                                        null,
                                        "school/exam/"
                                    );
                                } else {
                                    if (isset($ques["oldimage"][0])) {
                                        $question_choose->attachment =
                                            $ques["oldimage"][0];
                                    }
                                }
                                $question_choose->save();
                            }
                        }
                        if ($type == "yesorno") {
                            foreach ($question as $order => $ques) {
                                //dd($ques["options"]);
                                $question_yes = new ExamQuestionModel();
                                $question_yes->exam_id = $obj->id;
                                // $question_yes->section_id = $exam_section->id;
                                $question_yes->order = $order;
                                $question_yes->question_type = "yesorno";
                                $question_yes->question = $ques[0];
                                $question_yes->options = implode(
                                    ",",
                                    $ques["options"]
                                );
                                $question_yes->answer = $ques["answer"];
                                $question_yes->mark = $ques["mark"];

                                if (
                                    isset($ques["image"][0]) &&
                                    is_file($ques["image"][0])
                                ) {
                                    $question_yes->attachment = $this->uploadAttachment(
                                        $ques["image"][0],
                                        null,
                                        "school/exam/"
                                    );
                                } else {
                                    if (isset($ques["oldimage"][0])) {
                                        $question_yes->attachment =
                                            $ques["oldimage"][0];
                                    }
                                }
                                $question_yes->save();
                            }
                        }
                    }
                }
            }

            DB::commit();
            return $this->success("Exam Created Successfully", 200);
        } catch (\Exception $e) {
            DB::rollback();
            $message = str_replace(
                ["\r", "\n", "'", "`"],
                " ",
                $e->getMessage()
            );

            return $this->error($message, 500);
        }
    }

    public function Gettimeline($time)
    {
        $hour = substr($time, 0, 2);
        $minute = substr($time, 3, 2);
        return $hour . "hr:" . $minute . "min";
    }

    public function QuizEdit(Request $request, $id)
    {
        $data = ExamModel::with(
            "academyyear",
            "class",
            "section",
            "subject",
            "notification"
        )->find($id);

        $data->questions = ExamQuestionModel::where(
            "exam_id",
            $data->id
        )->get();

        $data->questions = $data->questions->transform(function ($quiz) {
            $options_array = explode(",", $quiz->options);
            $options = array_values($options_array);
            $format_options = [];
            if (!empty($quiz->options)) {
                foreach ($options as $key => $value) {
                    $format_options[] = ["id" => $key, "text" => $value];
                }
            }
            $quiz->options = $format_options;
            return $quiz;
        });
        $is_exists = OfflineExamMarkEntry::where([
            "exam_id" => $id,
        ])->first();
        if ($is_exists) {
            $student_ids = StudentsModel::where([
                "academic_year" => $data->academic_year,
                "class_id" => $data->class_id,
                "section_id" => $data->section_id,
                "status" => 1,
            ])
                ->whereNull("deleted_at")
                ->whereNull("deleted_by")
                ->pluck("id");
            $students_submitted = 1;
            foreach ($student_ids as $id) {
                $submitted = OfflineExamMarkEntry::where([
                    "exam_id" => $data->id,
                ])
                    ->where("student_id", $id)
                    ->whereNotNull("score")
                    ->exists();
                if (!$submitted) {
                    $students_submitted = 0;
                }
            }

            if ($students_submitted == 0) {
                $mark_submission = 2;
            } else {
                $mark_submission = 1;
            }
        } else {
            $mark_submission = 0;
        }
        $infos = [
            "exam_info" => $data,
            "mark_submission" => $mark_submission,
        ];

        return $this->success($infos, "Successfully Data Fetched", 200);
    }

    public function QuizUpdate(Request $request, $id, $type = null)
    {
        $exists = DB::table("exam")
            ->where("id", "!=", $id)
            ->whereRaw(
                "LOWER(exam_title) = ?",
                strtolower($request->exam_title)
            )
            ->exists();

        if ($exists) {
            return $this->error(
                "Whoops !! This Exam Title $request->exam_title already Exists Try With Diffrent Title",
                500
            );
        }
        $this->validate($request, [
            "examtype" => "required",
            "max_mark" => "required",
            "min_mark" => "required",
            "exam_date" => "required",
            "exam_time" => "required",
            "exam_title" => "required",
        ]);
        try {
            DB::beginTransaction();
            $user_id = $request->user()->id;
            $current_academic_year = Configurations::getCurrentAcademicyear();
            $current_academic_term = Configurations::getCurrentAcademicterm();
            $teacher_id = TeacherModel::where("user_id", $user_id)
                ->pluck("id")
                ->first();
            $classteacher = ClassteacherModel::with("class", "section")
                ->where([
                    "academic_year" => $current_academic_year,
                    "teacher_id" => $teacher_id,
                ])
                ->first();
            if (!$classteacher) {
                return $this->error("Class teacher not found.", 404);
            }
            // $subject_id = SubjectTeacherMapping::where([
            //     "class_id" => $classteacher->class_id,
            //     "section_id" => $classteacher->section_id,
            //     "teacher_id" => $teacher_id,
            //     "academic_year" => $current_academic_year,
            // ])
            //     ->pluck("subject_id")
            //     ->first();
            $obj = ExamModel::find($id);
            if (!$obj) {
                return $this->error("Exam not found.id=" . $id, 404);
            }
            $obj->academic_year = $current_academic_year;
            $obj->exam_type = $request->examtype;
            $obj->class_id = $classteacher->class_id;
            $obj->section_id = $classteacher->section_id;
            // $obj->subject_id = $subject_id;
            $obj->examistruction = $request->examinstruction;
            $obj->exam_term = $current_academic_term;
            // $obj->show_results = 0;
            $obj->type_of_exam = $obj->type_of_exam =
                $type == null ? "online" : "Homework";
            $obj->max_mark = $request->max_mark;
            $obj->min_mark = $request->min_mark;
            $obj->exam_title = $request->exam_title;

            $obj->exam_date =
                $request->exam_date ?? carbon::now()->format("Y/m/d");
            $obj->exam_submission_date = Carbon::parse(
                $request->exam_submission_date
            )->format("m/d/Y");
            $obj->exam_submission_time = date(
                "g:i a",
                strtotime($request->exam_submission_time)
            );
            $obj->exam_time = date("g:i a", strtotime($request->exam_time));
            $obj->timeline = $this->Gettimeline(
                $request->timeline ? $request->timeline : "01:00"
            );

            if ($request->type == "upload") {
                if ($request->upload_question) {
                    $obj->uploaded_file = $this->uploadAttachment(
                        $request->upload_question,
                        null,
                        "school/exam/"
                    );
                }
            }
            if ($obj->save()) {
                if ($request->notify_date) {
                    $notify = ExamNotificationModel::where(
                        "exam_id",
                        $id
                    )->first();
                    if ($notify) {
                        $notify->exam_id = $obj->id;
                        $notify->notify_date = $request->notify_date;
                        $notify->notify_time = $request->notify_time;
                        $notify->notify_message = $request->notify_message;
                        $notify->save();
                    } else {
                        $notify_info = new ExamNotificationModel();
                        $notify_info->exam_id = $obj->id;
                        $notify_info->notify_date = $request->notify_date;
                        $notify_info->notify_time = $request->notify_time;
                        $notify_info->notify_message = $request->notify_message;
                        $notify_info->save();
                    }
                }
                if ($request->questions) {
                    // $deletequestions = ExamQuestionModel::where(
                    //     "exam_id",
                    //     $id
                    // )->forceDelete();

                    // dd($request->questions);
                    foreach ($request->questions as $type => $question) {
                        if ($type == "fillblanks") {
                            foreach ($question as $order => $ques) {
                                $is_exist = ExamQuestionModel::where([
                                    "exam_id" => $obj->id,
                                    "order" => $order,
                                    "question_type" => "fillintheblanks",
                                ])->first();
                                if ($is_exist) {
                                    $question_fill = $is_exist;
                                    $question_fill->exam_id = $obj->id;
                                    // $question_fill->section_id = $exam_section->id;
                                    $question_fill->order = $order;
                                    $question_fill->question_type =
                                        "fillintheblanks";
                                    $question_fill->question = $ques[0];
                                    $question_fill->answer = $ques["answer"];
                                    $question_fill->mark = $ques["mark"] ?? 0;
                                    $question_fill->save();
                                } else {
                                    $question_fill = new ExamQuestionModel();
                                    $question_fill->exam_id = $obj->id;
                                    // $question_fill->section_id = $exam_section->id;
                                    $question_fill->order = $order;
                                    $question_fill->question_type =
                                        "fillintheblanks";
                                    $question_fill->question = $ques[0];
                                    $question_fill->answer = $ques["answer"];
                                    $question_fill->mark = $ques["mark"] ?? 0;
                                    $question_fill->save();
                                }
                            }
                        }
                        if ($type == "homework") {
                            foreach ($question as $order => $ques) {
                                $is_exist = ExamQuestionModel::where([
                                    "exam_id" => $obj->id,
                                    "order" => $order,
                                    "question_type" => "homework",
                                ])->first();
                                if ($is_exist) {
                                    $question_homework = $is_exist;
                                    $question_homework->exam_id = $obj->id;
                                    // $question_homework->section_id =
                                    //     $exam_section->id;
                                    $question_homework->order = $order;
                                    $question_homework->question_type =
                                        "homework";
                                    $question_homework->question = $ques[0];
                                    $question_homework->answer = null;
                                    $question_homework->mark =
                                        $ques["mark"] ?? 0;
                                    if (
                                        isset($ques["image"][0]) &&
                                        is_file($ques["image"][0])
                                    ) {
                                        $question_homework->attachment = $this->uploadAttachment(
                                            $ques["image"][0],
                                            null,
                                            "school/exam/"
                                        );
                                    }
                                    $question_homework->save();
                                } else {
                                    $question_homework = new ExamQuestionModel();
                                    $question_homework->exam_id = $obj->id;
                                    // $question_homework->section_id =
                                    //     $exam_section->id;
                                    $question_homework->order = $order;
                                    $question_homework->question_type =
                                        "homework";
                                    $question_homework->question = $ques[0];
                                    $question_homework->answer = null;
                                    $question_homework->mark =
                                        $ques["mark"] ?? 0;
                                    if (
                                        isset($ques["image"][0]) &&
                                        is_file($ques["image"][0])
                                    ) {
                                        $question_homework->attachment = $this->uploadAttachment(
                                            $ques["image"][0],
                                            null,
                                            "school/exam/"
                                        );
                                    }
                                    $question_homework->save();
                                }
                            }
                        }
                        if ($type == "choose_best") {
                            //dd($exam_section->id);
                            foreach ($question as $order => $ques) {
                                $is_exist = ExamQuestionModel::where([
                                    "exam_id" => $obj->id,
                                    "order" => $order,
                                    "question_type" => "choosebest",
                                ])->first();
                                if ($is_exist) {
                                    $question_choose = $is_exist;
                                    $question_choose->exam_id = $obj->id;
                                    // $question_choose->section_id =
                                    //     $exam_section->id;
                                    $question_choose->order = $order;
                                    $question_choose->question_type =
                                        "choosebest";
                                    $question_choose->question = $ques[0];

                                    $question_choose->options = implode(
                                        ",",
                                        $ques["options"]
                                    );
                                    $question_choose->answer = $ques["answer"];
                                    $question_choose->mark = $ques["mark"];

                                    if (
                                        isset($ques["image"][0]) &&
                                        is_file($ques["image"][0])
                                    ) {
                                        $question_choose->attachment = $this->uploadAttachment(
                                            $ques["image"][0],
                                            null,
                                            "school/exam/"
                                        );
                                    } else {
                                        if (isset($ques["oldimage"][0])) {
                                            $question_choose->attachment =
                                                $ques["oldimage"][0];
                                        }
                                    }
                                    $question_choose->save();
                                } else {
                                    $question_choose = new ExamQuestionModel();
                                    $question_choose->exam_id = $obj->id;
                                    // $question_choose->section_id =
                                    //     $exam_section->id;
                                    $question_choose->order = $order;
                                    $question_choose->question_type =
                                        "choosebest";
                                    $question_choose->question = $ques[0];

                                    $question_choose->options = implode(
                                        ",",
                                        $ques["options"]
                                    );
                                    $question_choose->answer = $ques["answer"];
                                    $question_choose->mark = $ques["mark"];

                                    if (
                                        isset($ques["image"][0]) &&
                                        is_file($ques["image"][0])
                                    ) {
                                        $question_choose->attachment = $this->uploadAttachment(
                                            $ques["image"][0],
                                            null,
                                            "school/exam/"
                                        );
                                    } else {
                                        if (isset($ques["oldimage"][0])) {
                                            $question_choose->attachment =
                                                $ques["oldimage"][0];
                                        }
                                    }
                                    $question_choose->save();
                                }
                            }
                        }
                        if ($type == "yesorno") {
                            foreach ($question as $order => $ques) {
                                $is_exist = ExamQuestionModel::where([
                                    "exam_id" => $obj->id,
                                    "order" => $order,
                                    "question_type" => "yesorno",
                                ])->first();
                                if ($is_exist) {
                                    //dd($ques["options"]);
                                    $question_yes = $is_exist;
                                    $question_yes->exam_id = $obj->id;
                                    // $question_yes->section_id = $exam_section->id;
                                    $question_yes->order = $order;
                                    $question_yes->question_type = "yesorno";
                                    $question_yes->question = $ques[0];
                                    $question_yes->options = implode(
                                        ",",
                                        $ques["options"]
                                    );
                                    $question_yes->answer = $ques["answer"];
                                    $question_yes->mark = $ques["mark"];

                                    if (
                                        isset($ques["image"][0]) &&
                                        is_file($ques["image"][0])
                                    ) {
                                        $question_yes->attachment = $this->uploadAttachment(
                                            $ques["image"][0],
                                            null,
                                            "school/exam/"
                                        );
                                    } else {
                                        if (isset($ques["oldimage"][0])) {
                                            $question_yes->attachment =
                                                $ques["oldimage"][0];
                                        }
                                    }
                                    $question_yes->save();
                                } else {
                                    //dd($ques["options"]);
                                    $question_yes = new ExamQuestionModel();
                                    $question_yes->exam_id = $obj->id;
                                    // $question_yes->section_id = $exam_section->id;
                                    $question_yes->order = $order;
                                    $question_yes->question_type = "yesorno";
                                    $question_yes->question = $ques[0];
                                    $question_yes->options = implode(
                                        ",",
                                        $ques["options"]
                                    );
                                    $question_yes->answer = $ques["answer"];
                                    $question_yes->mark = $ques["mark"];

                                    if (
                                        isset($ques["image"][0]) &&
                                        is_file($ques["image"][0])
                                    ) {
                                        $question_yes->attachment = $this->uploadAttachment(
                                            $ques["image"][0],
                                            null,
                                            "school/exam/"
                                        );
                                    } else {
                                        if (isset($ques["oldimage"][0])) {
                                            $question_yes->attachment =
                                                $ques["oldimage"][0];
                                        }
                                    }
                                    $question_yes->save();
                                }
                            }
                        }
                    }
                }
            }

            DB::commit();

            return $this->success("Exam Updated Successfully", 200);
        } catch (\Exception $e) {
            DB::rollback();
            // dd($e);
            $message = str_replace(
                ["\r", "\n", "'", "`"],
                " ",
                $e->getMessage()
            );

            return $this->error($message, 500);
        }
    }

    public function ExamSubmit(Request $request)
    {
        // dd("submit");
        $this->validate(
            $request,
            [
                "attachment" => "max:4000|mimes:pdf,jpg,jpeg,docx,png",
            ],
            [
                "attachment.mimes" =>
                    "The attachment must be a PDF, JPG, JPEG, or DOCX file.",
            ]
        );

        //    dd($request->all());
        $homework = ExamModel::where([
            "id" => $request->exam_id,
            "type_of_exam" => "Homework",
        ])->first();

        if (isset($request->user()->student)) {
            // $active_student = Configurations::Activestudent();

            if ($homework) {
                // CHECKING THIS HOMEWORK Already submitted this students
                // dd("it enter");
                $is_submitted = HomeworkSubmissionModel::where([
                    "homework_id" => $request->exam_id,
                    "student_id" => $request->user()->student->id,
                    "subject_id" => $request->subject_id,
                ])->first();
                $date = Carbon::now(
                    Configurations::getConfig("site")->time_zone
                )->toDateString();
                $time = Carbon::now(
                    Configurations::getConfig("site")->time_zone
                )->toTimeString();

                if ($is_submitted) {
                    // Updated Submitted count
                    return $this->error("Already Submitted", 500);
                } else {
                    // new homework submiited this student
                    // dd($request->all());
                    $data = [
                        "homework_id" => $request->exam_id,
                        "subject_id" => $request->subject_id,
                        "remark" => $request->remark,
                    ];

                    unset($data["_token"]);
                    unset($data["submit_cat"]);

                    $data["count"] = 1;
                    $data["student_id"] = $request->user()->student->id;
                    $data["homework_status"] = 0;
                    $data["submitted_date"] = $date;
                    $data["submitted_time"] = $time;

                    if ($request->attachment) {
                        $data["attachment"] = $this->uploadAttachment(
                            $request->attachment,
                            null,
                            "school/homework/"
                        );
                    }
                    HomeworkSubmissionModel::create($data);

                    return $this->success("Exam Submitted successfully", 200);
                }
            }
        } else {
            return $this->error("No Access", 500);
        }
    }
}
