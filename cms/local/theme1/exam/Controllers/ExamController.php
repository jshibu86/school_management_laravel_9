<?php

namespace cms\exam\Controllers;

use DB;
use CGate;
use Session;

use Configurations;

use Illuminate\Http\Request;
use cms\exam\Models\ExamModel;
use cms\exam\Models\ExamTypeModel;
use cms\lclass\Models\LclassModel;
use App\Http\Controllers\Controller;
use cms\academicyear\Models\AcademicyearModel;
use cms\core\configurations\Traits\FileUploadTrait;
use cms\department\Models\DepartmentModel;
use cms\exam\Models\ExamNotificationModel;
use cms\exam\Models\ExamQuestionModel;
use cms\exam\Models\ExamSectionModel;
use cms\section\Models\SectionModel;
use cms\students\Models\StudentsModel;
use cms\subject\Models\SubjectModel;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use cms\exam\Models\ExamTermModel;
use cms\exam\Models\OfflineExamMarkEntry;
use cms\exam\Models\OnlineExamModel;
use cms\exam\Models\OnlineExamSubmissionModel;
use cms\exam\Models\SubQuestionMapping;
use Symfony\Component\HttpKernel\HttpCache\SubRequestHandler;

class ExamController extends Controller
{
    use FileUploadTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $error = CGate::module();
            if ($error == 1) {
                return redirect()
                    ->route("errorPage")
                    ->with(
                        "error",
                        "You do not have access to this module. Please contact the administrator for further assistance."
                    );
            } else {
                return $next($request);
            }
        });
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $class_id = $request->query->get("class", 0);
            $section_id = $request->query->get("section", 0);
            $type = $request->query->get("type", 0);

            if ($type == 4) {
                $academic_year = $request->query->get("academic_year", 0);
                $academic_term = DB::table("exam_term")
                    ->where("academic_year", $academic_year)
                    ->select("id", "exam_term_name as text")
                    ->get();
                return $academic_term;
            }
            // exclude students

            $students_exclude = StudentsModel::where([
                "class_id" => $class_id,
                "section_id" => $section_id,
            ])
                ->where("status", 1)
                ->select([
                    "students.id as id",
                    DB::raw(
                        "CONCAT(students.first_name , ' ',students.last_name ,'-',students.username, ' - ',  students.email) as text"
                    ),
                ])
                ->get();

            $students_include = StudentsModel::where("status", 1)
                ->where("class_id", "!=", $class_id)
                ->where("section_id", "!=", $section_id)
                ->select([
                    "students.id as id",
                    DB::raw(
                        "CONCAT(students.first_name , ' ',students.last_name ,'-',students.username, ' - ',  students.email) as text"
                    ),
                ])
                ->get();

            $data = [
                "students_include" => $students_include,
                "students_exclude" => $students_exclude,
            ];

            return $data;
        }
        return view("exam::admin.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //dd("here");
        $academic_years = Configurations::getAcademicyears();
        $current_academic_year = Configurations::getCurrentAcademicyear();
        $current_academic_term = Configurations::getCurrentAcademicterm();

        $examterms = Configurations::getCurentAcademicTerms();
        $department = DepartmentModel::where("status", 1)
            ->pluck("dept_name", "id")
            ->toArray();
        $exam_types = ExamTypeModel::where("status", 1)
            ->whereNull("deleted_at")
            ->pluck("exam_type_name", "id")
            ->toArray();
        $class_lists = LclassModel::whereNull("deleted_at")
            ->where("status", "!=", -1)
            ->orderBy("id", "asc")
            ->pluck("name", "id")
            ->toArray();
        return view("exam::admin.edit", [
            "layout" => "create",
            "academic_years" => $academic_years,
            "current_academic_year" => $current_academic_year,
            "exam_types" => $exam_types,
            "class_lists" => $class_lists,
            "subject_lists" => [],
            "section_lists" => [],
            "section_lists" => [],
            "department" => $department,
            "include_students" => [],
            "exclude_students" => [],
            "examterms" => $examterms,
            "maxsectionorder" => 0,
            "current_academic_term" => $current_academic_term,
            "type" => null,
        ]);
    }

    public function Gettimeline($time)
    {
        $hour = substr($time, 0, 2);
        $minute = substr($time, 3, 2);
        $formated_time = $hour . "hr:" . $minute . "min";
        // dd($time, $formated_time);
        return $formated_time;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // abort(500);
        // dd($request->all());
        // $rules = [];
        // $messages = [];

        // if (isset($request->questions['homework']) && is_array($request->questions['homework'])) {
        //     foreach ($request->questions['homework'] as $index => $homework) {
        //         if (isset($homework['image']) && is_array($homework['image'])) {
        //             foreach ($homework['image'] as $key => $file) {
        //                 $rules["questions.homework.{$index}.image.{$key}"] = "max:4000|mimes:pdf,jpg,jpeg,docx,png,mp4";
        //                 $messages["questions.homework.{$index}.image.{$key}.mimes"] = 'The attachment must be a PDF, JPG, JPEG, PNG, or MP4 file.';
        //             }
        //         }
        //     }
        // }

        // $this->validate($request, $rules, $messages);

        if ($request->exam_title) {
            $exists = DB::table("exam")
                ->whereRaw(
                    "LOWER(exam_title) = ?",
                    strtolower($request->exam_title)
                )
                ->exists();

            if ($exists) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        "exception_error",
                        "Whoops !! This Exam Title $request->exam_title already Exists Try With Different Title"
                    );
            }
        }

        if ($request->hiddenpreview == "preview") {
            $config = Configurations::getConfig("site");
            // dd($config);
            //dd($request->all());

            if (
                !$request->academic_year ||
                !$request->class_id ||
                !$request->section_id ||
                !$request->subject_id
            ) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        "exception_error",
                        "Whoops !! Something Went Wrong Maybe You Missedout Select academicyear or Class or Section or Subject Go to previous tab and Fill out Feilds and Preview"
                    );
            }

            $acyear = AcademicyearModel::where(
                "id",
                $request->academic_year
            )->first()->year;

            $class = LclassModel::where("id", $request->class_id)->first()
                ->name;
            $section = SectionModel::where("id", $request->section_id)->first()
                ->name;
            $subject = SubjectModel::where("id", $request->subject_id)->first()
                ->name;
            $department_id = SectionModel::where("id", $request->section_id)
                ->pluck("department_id")
                ->first();
            if ($department_id !== null) {
                $department = DepartmentModel::where(
                    "id",
                    $department_id
                )->first()->dept_name;
            } else {
                $department = "NA";
            }
            $term = ExamTermModel::where("id", $request->academic_term)->first()
                ->exam_term_name;

            //dd($subject);

            return view("exam::admin.preview", [
                "class" => $class . "-" . $section,
                "section" => $section,
                "acyear" => $acyear,
                "subject" => $subject,
                "time" => $request->timeline,
                "istruction" => $request->examinstruction,
                "totalmark" => $request->max_mark,
                "section" => $request->section ? $request->section : [],
                "config" => $config,
                "department" => $department,
                "term" => $term,
                "exam_title" => $request->exam_title,
            ]);
        }

        //dd(date("g:i a", strtotime($request->exam_time)));

        // dd($request->all());

        $this->validate(
            $request,
            [
                "examtype" => "required",
                "max_mark" => "required|numeric",
                "min_mark" => "required|numeric",
                "exam_date" => "required",
                "exam_time" => "required",
                "notify_time" => "required",
                "exam_title" => "required",
                "section.*.questions" => "required",
                "questions.homework.*.image.*" =>
                    "max:4000|mimes:pdf,jpg,jpeg,docx,png",

                "section" => function ($attribute, $value, $fail) use (
                    $request
                ) {
                    $totalSectionMarks = array_reduce(
                        $value,
                        function ($carry, $sectionData) {
                            // dd($sectionData);
                            // dd($sectionData["totalmark"], $carry);
                            $total_mark = $sectionData["totalmark"] ?? 0;
                            // dd($total_mark);
                            return $carry + $total_mark;
                        },
                        0
                    );

                    if ($totalSectionMarks > $request->max_mark) {
                        $fail(
                            "The total marks of all sections exceeds the maximum mark."
                        );
                    }
                },
            ],
            [
                "section.*.questions" => "Please Enter Questions.",
            ]
        );
        // if ($validator->fails()) {
        //     return redirect()->back()->withErrors($validator)->withInput();
        // }
        DB::beginTransaction();
        try {
            // dd($request->all());
            // save exam information
            $obj = new ExamModel();
            $obj->academic_year = $request->academic_year;
            $obj->exam_type = $request->examtype;
            $obj->class_id = $request->class_id;
            $obj->section_id = $request->section_id;
            $obj->subject_id = $request->subject_id;
            $obj->examistruction = $request->examinstruction;
            $obj->exam_term = $request->academic_term;
            $obj->show_results = $request->has("stu_results") ? 1 : 0;
            if ($request->is_homework == 1) {
                $obj->type_of_exam = "Homework";
            } elseif ($request->is_admission == 1) {
                $obj->type_of_exam = "Admission Exam";
            } else {
                $obj->type_of_exam = $request->type_of_exam ?? "Offline";
            }

            $obj->max_mark = $request->max_mark;
            $obj->min_mark = $request->min_mark;
            $obj->exam_title = $request->exam_title;
            if ($request->include_students) {
                $obj->include_students = implode(
                    ",",
                    $request->include_students
                );
            }
            if ($request->exclude_students) {
                $obj->exclude_students = implode(
                    ",",
                    $request->exclude_students
                );
            }
            $obj->exam_date = $request->exam_date ?? "2023-02-19";
            $obj->exam_time = date("g:i a", strtotime($request->exam_time));
            $obj->exam_submission_date = $request->exam_submission_date;
            $obj->exam_submission_time = date(
                "g:i a",
                strtotime($request->exam_submission_time)
            );
            $obj->promotion = $request->promotion;
            $obj->exam_percentage = $request->exam_percentage;
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
                // checkuploadefile

                // save notification
                if ($request->notify_date) {
                    $notify = new ExamNotificationModel();
                    $notify->exam_id = $obj->id;
                    $notify->notify_date = $request->notify_date;
                    $notify->notify_time = $request->notify_time;
                    $notify->notify_message = $request->notify_message;
                    $notify->save();
                }
                //save questions
                // dd($request->section);
                if ($request->section) {
                    $seccount = 0;
                    foreach ($request->section as $sections) {
                        // dd($sections[0]);

                        // save sections
                        $exam_section = new ExamSectionModel();
                        $exam_section->exam_id = $obj->id;
                        $exam_section->section_name = $sections[0] ?? "section";
                        $exam_section->section_order = $sections["secorder"];
                        $exam_section->section_mark =
                            $sections["totalmark"] ?? 0;
                        if ($exam_section->save()) {
                            //dd($sections["questions"]);

                            foreach (
                                $sections["questions"]
                                as $type => $question
                            ) {
                                if ($type == "homework") {
                                    foreach ($question as $order => $ques) {
                                        $question_homework = new ExamQuestionModel();
                                        $question_homework->exam_id = $obj->id;
                                        $question_homework->section_id =
                                            $exam_section->id;
                                        $question_homework->order = $order;
                                        $question_homework->question_type =
                                            "homework";
                                        $question_homework->question = $ques[0];
                                        $question_homework->answer = "homework";
                                        $question_homework->mark =
                                            $ques["mark"][0] ?? 0;
                                        if (
                                            isset($ques["image"][0]) &&
                                            is_file($ques["image"][0])
                                        ) {
                                            $question_homework->attachment = $this->uploadAttachment(
                                                $ques["image"][0],
                                                null,
                                                "school/exam/"
                                            );
                                        } else {
                                            if (
                                                $request->dup_type ==
                                                "duplicate"
                                            ) {
                                                $dup_questions = ExamQuestionModel::where(
                                                    [
                                                        "exam_id" =>
                                                            $request->pre_exam_id,
                                                        "section_id" =>
                                                            $sections[
                                                                "pre_section_id"
                                                            ],
                                                        "order" => $order,
                                                    ]
                                                )
                                                    ->pluck("attachment")
                                                    ->first();
                                                if ($dup_questions) {
                                                    $question_homework->attachment = $dup_questions;
                                                }
                                            }
                                        }
                                        $question_homework->save();
                                    }
                                }
                                if ($type == "fillblanks") {
                                    foreach ($question as $order => $ques) {
                                        $question_fill = new ExamQuestionModel();
                                        $question_fill->exam_id = $obj->id;
                                        $question_fill->section_id =
                                            $exam_section->id;
                                        $question_fill->order = $order;
                                        $question_fill->question_type =
                                            "fillintheblanks";
                                        $question_fill->question = $ques[0];
                                        $question_fill->answer =
                                            $ques["answer"][0];
                                        $question_fill->mark =
                                            $ques["mark"][0] ?? 0;
                                        $question_fill->save();
                                    }
                                } elseif ($type == "choose_best") {
                                    //dd($exam_section->id);
                                    foreach ($question as $order => $ques) {
                                        $question_choose = new ExamQuestionModel();
                                        $question_choose->exam_id = $obj->id;
                                        $question_choose->section_id =
                                            $exam_section->id;
                                        $question_choose->order = $order;
                                        $question_choose->question_type =
                                            "choosebest";
                                        $question_choose->question = $ques[0];

                                        $question_choose->options = implode(
                                            ",",
                                            $ques["options"]
                                        );
                                        $question_choose->answer =
                                            $ques["answer"][0];
                                        $question_choose->mark =
                                            $ques["mark"][0];

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
                                } elseif ($type == "yesorno") {
                                    foreach ($question as $order => $ques) {
                                        //dd($ques["options"]);
                                        $question_yes = new ExamQuestionModel();
                                        $question_yes->exam_id = $obj->id;
                                        $question_yes->section_id =
                                            $exam_section->id;
                                        $question_yes->order = $order;
                                        $question_yes->question_type =
                                            "yesorno";
                                        $question_yes->question = $ques[0];
                                        $question_yes->options = implode(
                                            ",",
                                            $ques["options"]
                                        );
                                        $question_yes->answer =
                                            $ques["answer"][0];
                                        $question_yes->mark = $ques["mark"][0];

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
                                } elseif ($type == "typequs") {
                                    foreach ($question as $order => $ques) {
                                        $question_define = new ExamQuestionModel();
                                        $question_define->exam_id = $obj->id;
                                        $question_define->section_id =
                                            $exam_section->id;
                                        $question_define->order = $order;
                                        $question_define->question_type =
                                            "definequestion";
                                        $question_define->question =
                                            $ques["question"][0];

                                        $question_define->answer =
                                            $ques["answer"];
                                        $question_define->mark =
                                            $ques["mark"][0];
                                        if (
                                            isset($ques[0]) &&
                                            is_file($ques[0])
                                        ) {
                                            $question_define->attachment = $this->uploadAttachment(
                                                $ques[0],
                                                null,
                                                "school/exam/"
                                            );
                                        }

                                        $question_define->save();
                                    }
                                } elseif ($type == "shortques") {
                                    foreach ($question as $order => $ques) {
                                        $question_short = new ExamQuestionModel();
                                        $question_short->exam_id = $obj->id;
                                        $question_short->section_id =
                                            $exam_section->id;
                                        $question_short->order = $order;
                                        $question_short->question_type =
                                            "shortquestion";
                                        $question_short->question = $ques[0];

                                        $question_short->mark =
                                            $ques["mark"][0] ?? 0;
                                        $question_short->save();
                                    }
                                } elseif ($type == "longques") {
                                    foreach ($question as $order => $ques) {
                                        $question_long = new ExamQuestionModel();
                                        $question_long->exam_id = $obj->id;
                                        $question_long->section_id =
                                            $exam_section->id;
                                        $question_long->order = $order;
                                        $question_long->question_type =
                                            "longquestion";
                                        $question_long->question = $ques[0];

                                        $question_long->mark =
                                            $ques["mark"][0] ?? 0;
                                        $question_long->save();
                                    }
                                } elseif ($type == "sub_ques") {
                                    foreach ($question as $order => $ques) {
                                        $question_sub = new ExamQuestionModel();
                                        $question_sub->exam_id = $obj->id;
                                        $question_sub->section_id =
                                            $exam_section->id;
                                        $question_sub->order = $order;
                                        $question_sub->question_type =
                                            "subquestion";
                                        $question_sub->mark = 0;
                                        if ($question_sub->save()) {
                                            $mark = $ques["mark"];
                                            unset($ques["mark"]);
                                            //dd($ques);
                                            for (
                                                $i = 0;
                                                $i < sizeof($ques);
                                                $i++
                                            ) {
                                                $sub = new SubQuestionMapping();
                                                $sub->exam_id = $obj->id;
                                                $sub->exam_question_id =
                                                    $question_sub->id;
                                                $sub->question = $ques[$i];
                                                $sub->mark = $mark[$i];
                                                $sub->save();
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        $seccount++;
                    }
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            $message = str_replace(
                ["\r", "\n", "'", "`"],
                " ",
                $e->getMessage()
            );

            return redirect()
                ->back()
                ->withInput()
                ->with("exception_error", $e->getMessage());
        }

        if ($request->has("submit_cat_continue")) {
            return redirect()
                ->route("exam.create")
                ->with("success", "Saved Successfully");
        }

        Session::flash("success", "saved successfully");
        return redirect()->route("exam.index");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // abort(500);
        $data = ExamModel::with(
            "academyyear:id,year",
            "class:id,name",
            "section:id,name",
            "subject:id,name"
        )->find($id);

        $config = Configurations::getConfig("site");

        $questions = ExamModel::with("sections.questions.subquestion")->find(
            $id
        );

        //dd($data);
        //dd($questions);
        return view("exam::admin.show", [
            "data" => $data,
            "examquestions" => $questions,
            "config" => $config,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, $type = null)
    {
        //abort(500);

        $data = ExamModel::with(
            "notification",
            "sections.questions.subquestion"
        )->find($id);
        // dd($data->exam_submission_time);

        $data->exam_submission_time = Carbon::parse(
            $data->exam_submission_time
        )->format("h:i");
        // dd($data->exam_submission_time);
        $maxorder = ExamQuestionModel::where("exam_id", $data->id)->max(
            "order"
        );
        $maxsectionorder = ExamSectionModel::where("exam_id", $id)->max(
            "section_order"
        );

        // dd($maxsectionorder);
        $examterms = ExamTermModel::where("status", 1)
            ->where("academic_year", $data->academic_year)
            ->pluck("exam_term_name", "id")
            ->toArray();

        $academic_years = Configurations::getAcademicyears();
        $current_academic_year = Configurations::getCurrentAcademicyear();
        $department = DepartmentModel::where("status", 1)
            ->pluck("dept_name", "id")
            ->toArray();
        $exam_types = ExamTypeModel::where("status", 1)
            ->pluck("exam_type_name", "id")
            ->toArray();
        $class_lists = LclassModel::whereNull("deleted_at")
            ->where("status", "!=", -1)
            ->orderBy("id", "asc")
            ->pluck("name", "id")
            ->toArray();
        $section_lists = SectionModel::whereNull("deleted_at")
            ->where("status", "!=", -1)
            ->where("class_id", $data->class_id)
            ->orderBy("id", "asc")
            ->pluck("name", "id")
            ->toArray();
        $subject_lists = SubjectModel::whereNull("deleted_at")
            ->where("status", "!=", -1)
            ->where("class_id", $data->class_id)
            ->orderBy("id", "asc")
            ->pluck("name", "id")
            ->toArray();

        $students_exclude = StudentsModel::where([
            "class_id" => $data->class_id,
            "section_id" => $data->section_id,
        ])
            ->where("status", 1)
            ->select([
                "students.id as id",
                DB::raw(
                    "CONCAT(students.username, ' - ', students.email) as text"
                ),
            ])
            ->pluck("text", "id")
            ->toArray();
        $students_include = StudentsModel::where("status", 1)
            ->where("class_id", "!=", $data->class_id)
            ->where("section_id", "!=", $data->section_id)
            ->select([
                "students.id as id",
                DB::raw(
                    "CONCAT(students.username, ' - ', students.email) as text"
                ),
            ])
            ->pluck("text", "id")
            ->toArray();

        //dd($data->exclude_students);
        // dd($data);
        return view("exam::admin.edit", [
            "layout" => $type == "duplicate" ? "create" : "edit",
            "data" => $data,
            "academic_years" => $academic_years,
            "current_academic_year" => $current_academic_year,
            "exam_types" => $exam_types,
            "class_lists" => $class_lists,
            "subject_lists" => $subject_lists,
            "section_lists" => $section_lists,

            "department" => $department,
            "include_students" => $students_include,
            "exclude_students" => $students_exclude,
            "maxorder" => $maxorder,
            "examterms" => $examterms,
            "maxsectionorder" => $maxsectionorder,
            "type" => $type,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function deletequestion(Request $request)
    {
        $exam_id = $request->query->get("exam_id", 0);
        $question_id = $request->query->get("question_id", 0);

        $data = ExamQuestionModel::where([
            "id" => $question_id,
            "exam_id" => $exam_id,
        ])->first();

        if ($data->attachment) {
            $this->deleteImage(null, $data->attachment);
        }
        $data->delete();
        return true;
    }
    public function deletesection(Request $request)
    {
        $exam_id = $request->query->get("exam_id", 0);
        $section_id = $request->query->get("section_id", 0);

        $section_data = ExamSectionModel::where("id", $section_id)->first();
        if ($section_data) {
            $section_questions = ExamQuestionModel::where("exam_id", $exam_id)
                ->where("section_id", $section_id)
                ->get();

            foreach ($section_questions as $question) {
                if ($question->question_type == "subquestion") {
                    $subquestions = SubQuestionMapping::where(
                        "exam_question_id",
                        $question->id
                    )->get();

                    foreach ($subquestions as $sub) {
                        $sub->delete();
                    }

                    $question->delete();
                } else {
                    $question->delete();
                }
            }
        }

        $section_data->delete();
        return true;
    }
    public function update(Request $request, $id)
    {
        // dd($request->all());
        // $this->validate($request, $rules, $messages);
        if ($request->hiddenpreview == "preview") {
            $config = Configurations::getConfig("site");
            // dd($config);
            // dd($request->all());

            if (
                !$request->academic_year ||
                !$request->class_id ||
                !$request->section_id ||
                !$request->subject_id
            ) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        "exception_error",
                        "Whoops !! Something Went Wrong Maybe You Missedout Select academicyear or Class or Section or Subject Go to previous tab and Fill out Feilds and Preview"
                    );
            }

            $acyear = AcademicyearModel::where(
                "id",
                $request->academic_year
            )->first()->year;

            $term = ExamTermModel::where("id", $request->academic_term)->first()
                ->exam_term_name;

            $class = LclassModel::where("id", $request->class_id)->first()
                ->name;
            $section = SectionModel::where("id", $request->section_id)->first()
                ->name;
            $subject = SubjectModel::where("id", $request->subject_id)->first()
                ->name;

            //dd($subject);
            // dd($request->timeline);
            return view("exam::admin.preview", [
                "class" => $class . "-" . $section,
                "section" => $section,
                "acyear" => $acyear,
                "subject" => $subject,
                "time" => $request->timeline,
                "istruction" => $request->examinstruction,
                "totalmark" => $request->max_mark,
                "section" => $request->section ? $request->section : [],
                "config" => $config,
                "exam_id" => $id,
                "term" => $term,
                "exam_title" => $request->exam_title,
            ]);
        }
        //dd($request->all());
        $this->validate(
            $request,
            [
                "examtype" => "required",
                "max_mark" => "required|numeric",
                "min_mark" => "required|numeric",
                "exam_date" => "required",
                "exam_time" => "required",
                "section.*.questions" => "required",
                "questions.homework.*.image.*" =>
                    "max:4000|mimes:pdf,jpg,jpeg,docx,png",
            ],
            [
                "section.*.questions" => "Please Enter Questions.",
            ]
        );
        DB::beginTransaction();
        try {
            // save exam information
            $obj = ExamModel::find($id);
            $obj->academic_year = $request->academic_year;
            $obj->exam_type = $request->examtype;
            $obj->class_id = $request->class_id;
            $obj->section_id = $request->section_id;
            $obj->subject_id = $request->subject_id;
            $obj->examistruction = $request->examinstruction;
            $obj->exam_term = $request->academic_term;
            if ($request->is_homework == 1) {
                $obj->type_of_exam = "Homework";
            } elseif ($request->is_admission == 1) {
                $obj->type_of_exam = "Admission Exam";
            } else {
                $obj->type_of_exam = $request->type_of_exam ?? "Offline";
            }
            $obj->max_mark = $request->max_mark;
            $obj->min_mark = $request->min_mark;
            $obj->exam_title = $request->exam_title;
            if ($request->include_students) {
                $obj->include_students = implode(
                    ",",
                    $request->include_students
                );
            }
            if ($request->exclude_students) {
                $obj->exclude_students = implode(
                    ",",
                    $request->exclude_students
                );
            }
            $obj->exam_date = $request->exam_date;
            $obj->exam_time = date("g:i a", strtotime($request->exam_time));
            $obj->promotion = $request->promotion;
            $obj->exam_percentage = $request->exam_percentage;
            $obj->timeline = $this->Gettimeline(
                $request->timeline ? $request->timeline : "01:00"
            );

            if ($request->upload_question) {
                $this->deleteImage(null, $obj->uploaded_file);
                $obj->uploaded_file = $this->uploadAttachment(
                    $request->upload_question,
                    null,
                    "school/exam/"
                );
            }

            if ($obj->save()) {
                // save notification
                if ($request->notify_date) {
                    $notify = ExamNotificationModel::where(
                        "exam_id",
                        $id
                    )->first();
                    $notify->exam_id = $obj->id;
                    $notify->notify_date = $request->notify_date;
                    $notify->notify_time = $request->notify_time;
                    $notify->notify_message = $request->notify_message;
                    $notify->update();
                }
                //save questions

                if ($request->section) {
                    $seccount = 0;
                    foreach ($request->section as $sections) {
                        if (isset($sections["secorder"])) {
                            $check_section_exists = ExamSectionModel::where(
                                "section_order",
                                $sections["secorder"]
                            )
                                ->where("exam_id", $id)
                                ->first();

                            if ($check_section_exists) {
                                //dd("yes");
                                $check_section_exists->section_name =
                                    $sections[0] ?? "section";
                                $check_section_exists->section_order =
                                    $sections["secorder"];
                                $check_section_exists->section_mark =
                                    $sections["totalmark"] ?? 0;

                                if ($check_section_exists->update()) {
                                    //dd($sections["questions"]);
                                    if (isset($sections["questions"])) {
                                        foreach (
                                            $sections["questions"]
                                            as $type => $question
                                        ) {
                                            if ($type == "homework") {
                                                foreach (
                                                    $question
                                                    as $order => $ques
                                                ) {
                                                    $question_homework_exists = ExamQuestionModel::where(
                                                        [
                                                            "section_id" =>
                                                                $check_section_exists->id,
                                                            "order" => $order,
                                                        ]
                                                    )->first();
                                                    if (
                                                        $question_homework_exists
                                                    ) {
                                                        $question_homework_exists->question =
                                                            $ques[0];
                                                        // $question_homework_exists->answer =
                                                        //     $ques["answer"][0];
                                                        $question_homework_exists->mark =
                                                            $ques["mark"][0] ??
                                                            0;
                                                        if (
                                                            isset(
                                                                $ques[
                                                                    "image"
                                                                ][0]
                                                            ) &&
                                                            is_file(
                                                                $ques[
                                                                    "image"
                                                                ][0]
                                                            )
                                                        ) {
                                                            $question_homework_exists->attachment = $this->uploadAttachment(
                                                                $ques[
                                                                    "image"
                                                                ][0],
                                                                null,
                                                                "school/exam/"
                                                            );
                                                        }
                                                        $question_homework_exists->update();
                                                    } else {
                                                        $question_homework = new ExamQuestionModel();
                                                        $question_homework->exam_id =
                                                            $obj->id;
                                                        $question_homework->section_id =
                                                            $exam_section->id;
                                                        $question_homework->order = $order;
                                                        $question_homework->question_type =
                                                            "homework";
                                                        $question_homework->question =
                                                            $ques[0];
                                                        $question_homework->answer =
                                                            "homwwork";
                                                        $question_homework->mark =
                                                            $ques["mark"][0] ??
                                                            0;
                                                        if (
                                                            isset(
                                                                $ques[
                                                                    "image"
                                                                ][0]
                                                            ) &&
                                                            is_file(
                                                                $ques[
                                                                    "image"
                                                                ][0]
                                                            )
                                                        ) {
                                                            $question_homework->attachment = $this->uploadAttachment(
                                                                $ques[
                                                                    "image"
                                                                ][0],
                                                                null,
                                                                "school/exam/"
                                                            );
                                                        }
                                                        $question_homework->save();
                                                    }
                                                }
                                            }
                                            if ($type == "fillblanks") {
                                                foreach (
                                                    $question
                                                    as $order => $ques
                                                ) {
                                                    // checkexists

                                                    $question_fill_exists = ExamQuestionModel::where(
                                                        [
                                                            "section_id" =>
                                                                $check_section_exists->id,
                                                            "order" => $order,
                                                        ]
                                                    )->first();

                                                    // dd($question_fill_exists);

                                                    if ($question_fill_exists) {
                                                        $question_fill_exists->question =
                                                            $ques[0];
                                                        $question_fill_exists->answer =
                                                            $ques["answer"][0];
                                                        $question_fill_exists->mark =
                                                            $ques["mark"][0] ??
                                                            0;
                                                        $question_fill_exists->update();
                                                    } else {
                                                        $question_fill = new ExamQuestionModel();
                                                        $question_fill->exam_id =
                                                            $obj->id;
                                                        $question_fill->section_id =
                                                            $check_section_exists->id;
                                                        $question_fill->order = $order;
                                                        $question_fill->question_type =
                                                            "fillintheblanks";
                                                        $question_fill->question =
                                                            $ques[0];
                                                        $question_fill->answer =
                                                            $ques["answer"][0];
                                                        $question_fill->mark =
                                                            $ques["mark"][0] ??
                                                            0;
                                                        $question_fill->save();
                                                    }
                                                }
                                            } elseif ($type == "choose_best") {
                                                //dd($exam_section->id);
                                                foreach (
                                                    $question
                                                    as $order => $ques
                                                ) {
                                                    $question_choose_exists = ExamQuestionModel::where(
                                                        [
                                                            "section_id" =>
                                                                $check_section_exists->id,
                                                            "order" => $order,
                                                        ]
                                                    )->first();
                                                    if (
                                                        $question_choose_exists
                                                    ) {
                                                        $question_choose_exists->question =
                                                            $ques[0];

                                                        $question_choose_exists->options = implode(
                                                            ",",
                                                            $ques["options"]
                                                        );
                                                        $question_choose_exists->answer =
                                                            $ques["answer"][0];
                                                        $question_choose_exists->mark =
                                                            $ques["mark"][0];

                                                        if (
                                                            isset(
                                                                $ques[
                                                                    "image"
                                                                ][0]
                                                            ) &&
                                                            is_file(
                                                                $ques[
                                                                    "image"
                                                                ][0]
                                                            )
                                                        ) {
                                                            $question_choose_exists->attachment = $this->uploadAttachment(
                                                                $ques[
                                                                    "image"
                                                                ][0],
                                                                null,
                                                                "school/exam/"
                                                            );
                                                        }

                                                        $question_choose_exists->update();
                                                    } else {
                                                        $question_choose = new ExamQuestionModel();
                                                        $question_choose->exam_id =
                                                            $obj->id;
                                                        $question_choose->section_id =
                                                            $check_section_exists->id;
                                                        $question_choose->order = $order;
                                                        $question_choose->question_type =
                                                            "choosebest";
                                                        $question_choose->question =
                                                            $ques[0];

                                                        $question_choose->options = implode(
                                                            ",",
                                                            $ques["options"]
                                                        );
                                                        $question_choose->answer =
                                                            $ques["answer"][0];
                                                        $question_choose->mark =
                                                            $ques["mark"][0];

                                                        if (
                                                            isset(
                                                                $ques[
                                                                    "image"
                                                                ][0]
                                                            ) &&
                                                            is_file(
                                                                $ques[
                                                                    "image"
                                                                ][0]
                                                            )
                                                        ) {
                                                            $question_choose->attachment = $this->uploadAttachment(
                                                                $ques[
                                                                    "image"
                                                                ][0],
                                                                null,
                                                                "school/exam/"
                                                            );
                                                        }
                                                        $question_choose->save();
                                                    }
                                                }
                                            } elseif ($type == "yesorno") {
                                                foreach (
                                                    $question
                                                    as $order => $ques
                                                ) {
                                                    $question_yes_exists = ExamQuestionModel::where(
                                                        [
                                                            "section_id" =>
                                                                $check_section_exists->id,
                                                            "order" => $order,
                                                        ]
                                                    )->first();
                                                    if ($question_yes_exists) {
                                                        $question_yes_exists->question =
                                                            $ques[0];
                                                        $question_yes_exists->options = implode(
                                                            ",",
                                                            $ques["options"]
                                                        );
                                                        $question_yes_exists->answer =
                                                            $ques["answer"][0];
                                                        $question_yes_exists->mark =
                                                            $ques["mark"][0];

                                                        if (
                                                            isset(
                                                                $ques[
                                                                    "image"
                                                                ][0]
                                                            ) &&
                                                            is_file(
                                                                $ques[
                                                                    "image"
                                                                ][0]
                                                            )
                                                        ) {
                                                            $question_yes_exists->attachment = $this->uploadAttachment(
                                                                $ques[
                                                                    "image"
                                                                ][0],
                                                                null,
                                                                "school/exam/"
                                                            );
                                                        }
                                                        $question_yes_exists->update();
                                                    } else {
                                                        $question_yes = new ExamQuestionModel();
                                                        $question_yes->exam_id =
                                                            $obj->id;
                                                        $question_yes->section_id =
                                                            $check_section_exists->id;
                                                        $question_yes->order = $order;
                                                        $question_yes->question_type =
                                                            "yesorno";
                                                        $question_yes->question =
                                                            $ques[0];
                                                        $question_yes->options = implode(
                                                            ",",
                                                            $ques["options"]
                                                        );
                                                        $question_yes->answer =
                                                            $ques["answer"][0];
                                                        $question_yes->mark =
                                                            $ques["mark"][0];

                                                        if (
                                                            isset(
                                                                $ques[
                                                                    "image"
                                                                ][0]
                                                            ) &&
                                                            is_file(
                                                                $ques[
                                                                    "image"
                                                                ][0]
                                                            )
                                                        ) {
                                                            $question_yes->attachment = $this->uploadAttachment(
                                                                $ques[
                                                                    "image"
                                                                ][0],
                                                                null,
                                                                "school/exam/"
                                                            );
                                                        }
                                                        $question_yes->save();
                                                    }
                                                    //dd($ques["options"]);
                                                }
                                            } elseif ($type == "typequs") {
                                                foreach (
                                                    $question
                                                    as $order => $ques
                                                ) {
                                                    $question_define = new ExamQuestionModel();
                                                    $question_define->exam_id =
                                                        $obj->id;
                                                    $question_define->section_id =
                                                        $exam_section->id;
                                                    $question_define->order = $order;
                                                    $question_define->question_type =
                                                        "definequestion";
                                                    $question_define->question =
                                                        $ques["question"][0];

                                                    $question_define->answer =
                                                        $ques["answer"];
                                                    $question_define->mark =
                                                        $ques["mark"][0];
                                                    if (
                                                        isset($ques[0]) &&
                                                        is_file($ques[0])
                                                    ) {
                                                        $question_define->attachment = $this->uploadAttachment(
                                                            $ques[0],
                                                            null,
                                                            "school/exam/"
                                                        );
                                                    }

                                                    $question_define->save();
                                                }
                                            } elseif ($type == "shortques") {
                                                foreach (
                                                    $question
                                                    as $order => $ques
                                                ) {
                                                    $question_short_exists = ExamQuestionModel::where(
                                                        [
                                                            "section_id" =>
                                                                $check_section_exists->id,
                                                            "order" => $order,
                                                        ]
                                                    )->first();

                                                    if (
                                                        $question_short_exists
                                                    ) {
                                                        $question_short_exists->question =
                                                            $ques[0];

                                                        $question_short_exists->mark =
                                                            $ques["mark"][0] ??
                                                            0;
                                                        $question_short_exists->update();
                                                    } else {
                                                        $question_short = new ExamQuestionModel();
                                                        $question_short->exam_id =
                                                            $obj->id;
                                                        $question_short->section_id =
                                                            $check_section_exists->id;
                                                        $question_short->order = $order;
                                                        $question_short->question_type =
                                                            "shortquestion";
                                                        $question_short->question =
                                                            $ques[0];

                                                        $question_short->mark =
                                                            $ques["mark"][0] ??
                                                            0;
                                                        $question_short->save();
                                                    }
                                                }
                                            } elseif ($type == "longques") {
                                                foreach (
                                                    $question
                                                    as $order => $ques
                                                ) {
                                                    $question_long_exists = ExamQuestionModel::where(
                                                        [
                                                            "section_id" =>
                                                                $check_section_exists->id,
                                                            "order" => $order,
                                                        ]
                                                    )->first();

                                                    if ($question_long_exists) {
                                                        $question_long_exists->question =
                                                            $ques[0];

                                                        $question_long_exists->mark =
                                                            $ques["mark"][0] ??
                                                            0;
                                                        $question_long_exists->update();
                                                    } else {
                                                        $question_long = new ExamQuestionModel();
                                                        $question_long->exam_id =
                                                            $obj->id;
                                                        $question_long->section_id =
                                                            $check_section_exists->id;
                                                        $question_long->order = $order;
                                                        $question_long->question_type =
                                                            "longquestion";
                                                        $question_long->question =
                                                            $ques[0];

                                                        $question_long->mark =
                                                            $ques["mark"][0] ??
                                                            0;
                                                        $question_long->save();
                                                    }
                                                }
                                            } elseif ($type == "sub_ques") {
                                                foreach (
                                                    $question
                                                    as $order => $ques
                                                ) {
                                                    $question_sub_exists = ExamQuestionModel::where(
                                                        [
                                                            "section_id" =>
                                                                $check_section_exists->id,
                                                            "order" => $order,
                                                        ]
                                                    )->first();
                                                    if ($question_sub_exists) {
                                                        $subalready = SubQuestionMapping::where(
                                                            "exam_question_id",
                                                            $question_sub_exists->id
                                                        )->delete();

                                                        $mark = $ques["mark"];
                                                        unset($ques["mark"]);
                                                        //dd($ques);
                                                        for (
                                                            $i = 0;
                                                            $i < sizeof($ques);
                                                            $i++
                                                        ) {
                                                            $sub = new SubQuestionMapping();
                                                            $sub->exam_id =
                                                                $obj->id;
                                                            $sub->exam_question_id =
                                                                $question_sub_exists->id;
                                                            $sub->question =
                                                                $ques[$i];
                                                            $sub->mark =
                                                                $mark[$i];
                                                            $sub->save();
                                                        }
                                                    } else {
                                                        $question_sub = new ExamQuestionModel();
                                                        $question_sub->exam_id =
                                                            $obj->id;
                                                        $question_sub->section_id =
                                                            $check_section_exists->id;
                                                        $question_sub->order = $order;
                                                        $question_sub->question_type =
                                                            "subquestion";
                                                        $question_sub->mark = 0;
                                                        if (
                                                            $question_sub->save()
                                                        ) {
                                                            $mark =
                                                                $ques["mark"];
                                                            unset(
                                                                $ques["mark"]
                                                            );
                                                            //dd($ques);
                                                            for (
                                                                $i = 0;
                                                                $i <
                                                                sizeof($ques);
                                                                $i++
                                                            ) {
                                                                $sub = new SubQuestionMapping();
                                                                $sub->exam_id =
                                                                    $obj->id;
                                                                $sub->exam_question_id =
                                                                    $question_sub->id;
                                                                $sub->question =
                                                                    $ques[$i];
                                                                $sub->mark =
                                                                    $mark[$i];
                                                                $sub->save();
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    } else {
                                        DB::rollback();
                                        return redirect()
                                            ->back()
                                            ->withInput()
                                            ->with(
                                                "exception_error",
                                                "We find Some Section are Empty QuestionPlease Add Some Questions"
                                            );
                                    }
                                }
                            } else {
                                $exam_section = new ExamSectionModel();
                                $exam_section->exam_id = $obj->id;
                                $exam_section->section_name =
                                    $sections[0] ?? "section";
                                $exam_section->section_order =
                                    $sections["secorder"];
                                $exam_section->section_mark =
                                    $sections["totalmark"] ?? 0;
                                if ($exam_section->save()) {
                                    //dd($sections["questions"]);
                                    if (isset($sections["questions"])) {
                                        foreach (
                                            $sections["questions"]
                                            as $type => $question
                                        ) {
                                            if ($type == "homework") {
                                                foreach (
                                                    $question
                                                    as $order => $ques
                                                ) {
                                                    $question_homework = new ExamQuestionModel();
                                                    $question_homework->exam_id =
                                                        $obj->id;
                                                    $question_homework->section_id =
                                                        $exam_section->id;
                                                    $question_homework->order = $order;
                                                    $question_homework->question_type =
                                                        "homework";
                                                    $question_homework->question =
                                                        $ques[0];
                                                    $question_homework->answer =
                                                        "homwwork";
                                                    $question_homework->mark =
                                                        $ques["mark"][0] ?? 0;
                                                    if (
                                                        isset(
                                                            $ques["image"][0]
                                                        ) &&
                                                        is_file(
                                                            $ques["image"][0]
                                                        )
                                                    ) {
                                                        $question_homework->attachment = $this->uploadAttachment(
                                                            $ques["image"][0],
                                                            null,
                                                            "school/exam/"
                                                        );
                                                    }
                                                    $question_homework->save();
                                                }
                                            } elseif ($type == "fillblanks") {
                                                foreach (
                                                    $question
                                                    as $order => $ques
                                                ) {
                                                    // checkexists
                                                    $question_fill = new ExamQuestionModel();
                                                    $question_fill->exam_id =
                                                        $obj->id;
                                                    $question_fill->section_id =
                                                        $exam_section->id;
                                                    $question_fill->order = $order;
                                                    $question_fill->question_type =
                                                        "fillintheblanks";
                                                    $question_fill->question =
                                                        $ques[0];
                                                    $question_fill->answer =
                                                        $ques["answer"][0];
                                                    $question_fill->mark =
                                                        $ques["mark"][0] ?? 0;
                                                    $question_fill->save();
                                                }
                                            } elseif ($type == "choose_best") {
                                                //dd($exam_section->id);
                                                foreach (
                                                    $question
                                                    as $order => $ques
                                                ) {
                                                    $question_choose = new ExamQuestionModel();
                                                    $question_choose->exam_id =
                                                        $obj->id;
                                                    $question_choose->section_id =
                                                        $exam_section->id;
                                                    $question_choose->order = $order;
                                                    $question_choose->question_type =
                                                        "choosebest";
                                                    $question_choose->question =
                                                        $ques[0];

                                                    $question_choose->options = implode(
                                                        ",",
                                                        $ques["options"]
                                                    );
                                                    $question_choose->answer =
                                                        $ques["answer"][0];
                                                    $question_choose->mark =
                                                        $ques["mark"][0];

                                                    if (
                                                        isset(
                                                            $ques["image"][0]
                                                        ) &&
                                                        is_file(
                                                            $ques["image"][0]
                                                        )
                                                    ) {
                                                        $question_choose->attachment = $this->uploadAttachment(
                                                            $ques["image"][0],
                                                            null,
                                                            "school/exam/"
                                                        );
                                                    }
                                                    $question_choose->save();
                                                }
                                            } elseif ($type == "yesorno") {
                                                foreach (
                                                    $question
                                                    as $order => $ques
                                                ) {
                                                    //dd($ques["options"]);
                                                    $question_yes = new ExamQuestionModel();
                                                    $question_yes->exam_id =
                                                        $obj->id;
                                                    $question_yes->section_id =
                                                        $exam_section->id;
                                                    $question_yes->order = $order;
                                                    $question_yes->question_type =
                                                        "yesorno";
                                                    $question_yes->question =
                                                        $ques[0];
                                                    $question_yes->options = implode(
                                                        ",",
                                                        $ques["options"]
                                                    );
                                                    $question_yes->answer =
                                                        $ques["answer"][0];
                                                    $question_yes->mark =
                                                        $ques["mark"][0];

                                                    if (
                                                        isset(
                                                            $ques["image"][0]
                                                        ) &&
                                                        is_file(
                                                            $ques["image"][0]
                                                        )
                                                    ) {
                                                        $question_yes->attachment = $this->uploadAttachment(
                                                            $ques["image"][0],
                                                            null,
                                                            "school/exam/"
                                                        );
                                                    }
                                                    $question_yes->save();
                                                }
                                            } elseif ($type == "typequs") {
                                                foreach (
                                                    $question
                                                    as $order => $ques
                                                ) {
                                                    $question_define = new ExamQuestionModel();
                                                    $question_define->exam_id =
                                                        $obj->id;
                                                    $question_define->section_id =
                                                        $exam_section->id;
                                                    $question_define->order = $order;
                                                    $question_define->question_type =
                                                        "definequestion";
                                                    $question_define->question =
                                                        $ques["question"][0];

                                                    $question_define->answer =
                                                        $ques["answer"];
                                                    $question_define->mark =
                                                        $ques["mark"][0];
                                                    if (
                                                        isset($ques[0]) &&
                                                        is_file($ques[0])
                                                    ) {
                                                        $question_define->attachment = $this->uploadAttachment(
                                                            $ques[0],
                                                            null,
                                                            "school/exam/"
                                                        );
                                                    }

                                                    $question_define->save();
                                                }
                                            } elseif ($type == "shortques") {
                                                foreach (
                                                    $question
                                                    as $order => $ques
                                                ) {
                                                    $question_short = new ExamQuestionModel();
                                                    $question_short->exam_id =
                                                        $obj->id;
                                                    $question_short->section_id =
                                                        $exam_section->id;
                                                    $question_short->order = $order;
                                                    $question_short->question_type =
                                                        "shortquestion";
                                                    $question_short->question =
                                                        $ques[0];

                                                    $question_short->mark =
                                                        $ques["mark"][0] ?? 0;
                                                    $question_short->save();
                                                }
                                            } elseif ($type == "longques") {
                                                foreach (
                                                    $question
                                                    as $order => $ques
                                                ) {
                                                    $question_long = new ExamQuestionModel();
                                                    $question_long->exam_id =
                                                        $obj->id;
                                                    $question_long->section_id =
                                                        $exam_section->id;
                                                    $question_long->order = $order;
                                                    $question_long->question_type =
                                                        "longquestion";
                                                    $question_long->question =
                                                        $ques[0];

                                                    $question_long->mark =
                                                        $ques["mark"][0] ?? 0;
                                                    $question_long->save();
                                                }
                                            } elseif ($type == "sub_ques") {
                                                foreach (
                                                    $question
                                                    as $order => $ques
                                                ) {
                                                    $question_sub = new ExamQuestionModel();
                                                    $question_sub->exam_id =
                                                        $obj->id;
                                                    $question_sub->section_id =
                                                        $exam_section->id;
                                                    $question_sub->order = $order;
                                                    $question_sub->question_type =
                                                        "subquestion";
                                                    $question_sub->mark = 0;
                                                    if ($question_sub->save()) {
                                                        $mark = $ques["mark"];
                                                        unset($ques["mark"]);
                                                        //dd($ques);
                                                        for (
                                                            $i = 0;
                                                            $i < sizeof($ques);
                                                            $i++
                                                        ) {
                                                            $sub = new SubQuestionMapping();
                                                            $sub->exam_id =
                                                                $obj->id;
                                                            $sub->exam_question_id =
                                                                $question_sub->id;
                                                            $sub->question =
                                                                $ques[$i];
                                                            $sub->mark =
                                                                $mark[$i];
                                                            $sub->save();
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    } else {
                                        DB::rollback();
                                        return redirect()
                                            ->back()
                                            ->withInput()
                                            ->with(
                                                "exception_error",
                                                "We find Some Section are Empty QuestionPlease Add Some Questions"
                                            );
                                    }
                                }
                            }
                        }
                        // dd($sections[0]);

                        // save sections

                        //$seccount++;
                    }
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $message = str_replace(
                ["\r", "\n", "'", "`"],
                " ",
                $e->getMessage()
            );
            return redirect()
                ->back()
                ->withInput()
                ->with("exception_error", $message);
        }

        Session::flash("success", "saved successfully");
        return redirect()->route("exam.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        DB::statement("SET FOREIGN_KEY_CHECKS=0;");
        if (!empty($request->selected_exam)) {
            $delObj = new ExamModel();
            foreach ($request->selected_exam as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            $delObj = ExamModel::find($id);

            $delObj->questions()->delete();

            $delObj->delete();
        }
        DB::statement("SET FOREIGN_KEY_CHECKS=1;");

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("exam.index");
    }
    /*
     * get data
     */
    public function getData(Request $request)
    {
        //dd($request);
        // CGate::authorize("view-exam");
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;

        DB::statement(DB::raw("set @rownum=" . (int) $sTart));
        $type = $request->query->get("type");

        $data = ExamModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "exam.id",
            "exam.academic_year",
            "exam.exam_type",
            "exam.type_of_exam",
            "exam.exam_term",
            "exam.exam_title",
            "exam.exam_date",
            "exam.class_id",
            "exam.section_id",
            "exam.subject_id",
            "exam.exam_submission_date",
            "exam.exam_submission_time",
            "academicyear.year as acyear",
            "exam_term.exam_term_name as term_name",
            "exam_type.exam_type_name as exam_type_column",
            "subject.name as subject_name",
            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new ExamModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new ExamModel())->getTable() .
                    '.status = "-1" THEN "Trashed"
            ELSE "Enabled" END) AS status'
            )
        )
            ->where("exam.type_of_exam", $type)
            ->where("exam.status", "!=", -1)
            ->leftJoin(
                "academicyear",
                "academicyear.id",
                "=",
                "exam.academic_year"
            )
            ->leftJoin("exam_term", "exam_term.id", "=", "exam.exam_term")
            ->leftJoin("subject", "subject.id", "=", "exam.subject_id")
            ->leftJoin("exam_type", "exam_type.id", "=", "exam.exam_type");

        $datatables = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn("examdate", function ($data) {
                $date = Carbon::now()->toDateString();
                $subdate = Carbon::parse($data->exam_date)->format("Y-m-d");

                if ($date > $subdate) {
                    return "<span class='text-danger'>" . $subdate . "</span>";
                } else {
                    return "<span>" . $subdate . "</span>";
                }
            })
            ->addColumn("examsubmissiondatetime", function ($data) {
                if (
                    $data->exam_submission_date &&
                    $data->exam_submission_time
                ) {
                    $subdate = Carbon::parse(
                        $data->exam_submission_date
                    )->format("Y-m-d");
                    $subtime = Carbon::parse(
                        $data->exam_submission_time
                    )->format("H:i:s");
                    $time = Carbon::parse($data->exam_submission_time)->format(
                        "g:i A"
                    );
                    $datetime = $subdate . " " . $subtime;
                    //return "<span class='text-danger'>" . $datetime . "</span>";
                    $expiration = Carbon::createFromFormat(
                        "Y-m-d H:i:s",
                        $datetime
                    );
                    if ($expiration->isPast()) {
                        return "<span class='text-danger'>" .
                            $subdate .
                            "-" .
                            $time .
                            "</span>";
                    } else {
                        return "<span>" . $subdate . "-" . $time . "</span>";
                    }
                } else {
                    return "N/A";
                }
            })
            ->addColumn("is_finish", function ($data) {
                $date = Carbon::now()->toDateString();
                $subdate = Carbon::parse($data->exam_date)->format("Y-m-d");

                if ($date > $subdate) {
                    return 1;
                } else {
                    return 0;
                }
            })

            ->addColumn("check", function ($data) {
                if ($data->id != "1") {
                    return $data->rownum;
                } else {
                    return "";
                }
            })

            ->addColumn("class_section", function ($data) {
                $class = LclassModel::where("id", $data->class_id)->first()
                    ->name;
                $section = SectionModel::where("id", $data->section_id)->first()
                    ->name;
                return $class . "-" . $section;
            })

            ->addColumn("actdeact", function ($data) {
                if ($data->id != "1") {
                    $statusbtnvalue =
                        $data->status == "Enabled"
                            ? "<i class='glyphicon glyphicon-remove'></i>&nbsp;&nbsp;Disable"
                            : "<i class='glyphicon glyphicon-ok'></i>&nbsp;&nbsp;Enable";
                    return '<a class="statusbutton btn btn-default" data-toggle="modal" data="' .
                        $data->id .
                        '" href="">' .
                        $statusbtnvalue .
                        "</a>";
                } else {
                    return "";
                }
            })
            ->addColumn("action", function ($data) {
                return view("layout::datatable.action", [
                    "data" => $data,
                    "route" => "exam",
                    "examtype" => $data->type_of_exam,
                ])->render();
            })

            ->addColumn("entrymark", function ($data) {
                $date = Carbon::now()->toDateString();
                $subdate = Carbon::parse($data->exam_date)->format("Y-m-d");

                if ($date > $subdate) {
                    return '<a class="editbutton btn btn-default" data-toggle="modal" data="' .
                        $data->id .
                        '" href="' .
                        route("mark.create", [
                            "id" => $data->id,
                            "type" => "markentry",
                        ]) .
                        '" title="markentry"><i class="fa fa-plus"></i></a>';
                } else {
                    return "";
                }
            });

        // return $data;
        if (count((array) $data) == 0) {
            return [];
        }

        return $datatables
            ->rawColumns([
                "examdate",
                "action",
                "is_finish",
                "entrymark",
                "examsubmissiondatetime",
            ])
            ->make(true);
    }

    /*
     * country bulk action
     * eg : trash,enabled,disabled
     * delete is destroy function
     */
    function statusChange(Request $request)
    {
        CGate::authorize("edit-exam");
        if ($request->ajax()) {
            ExamModel::find($request->id)->update([
                "status" => $request->status,
            ]);
            return response()->json([
                "success" => "Status change successfully.",
            ]);
        }

        if (!empty($request->selected_exam)) {
            $obj = new ExamModel();
            foreach ($request->selected_exam as $k => $v) {
                if ($item = $obj->find($v)) {
                    $item->status = $request->action;
                    $item->save();
                }
            }
        }

        Session::flash("success", "Status changed Successfully!!");
        return redirect()->back();
    }

    public function offlinexamreport(Request $request, $id)
    {
        // dd($request->all(),$id);

        $data = ExamModel::with(
            "academyyear:id,year",
            "class:id,name",
            "section:id,name",
            "subject:id,name"
        )->find($id);

        $config = Configurations::getConfig("site");

        $questions = ExamModel::with("sections.questions.subquestion")->find(
            $id
        );

        $questions_count = ExamQuestionModel::where("exam_id", $id)->count();

        // dd($questions);

        $students = StudentsModel::where("class_id", $data->class_id)
            ->where("section_id", $data->section_id)
            ->where("academic_year", $data->academic_year)
            ->get();

        // get entry marks

        $marks = OfflineExamMarkEntry::where("exam_id", $id)->get();
        $exam_type = $marks->pluck("exam_type")->first();
        $pass = $marks->where("mark_status", 1)->count();
        $fail = $marks->where("mark_status", 2)->count();
        $pass_fail = [$pass, $fail];
        return view(
            "exam::admin.offlineexam.report",
            compact(
                "data",
                "config",
                "questions",
                "students",
                "questions_count",
                "marks",
                "exam_type",
                "pass_fail",
                "pass",
                "fail"
            )
        );
    }

    public function onlinexamreport(Request $request, $id)
    {
        try {
            $data = ExamModel::with(
                "academyyear:id,year",
                "class:id,name",
                "section:id,name",
                "subject:id,name"
            )->find($id);

            $config = Configurations::getConfig("site");

            $questions = ExamQuestionModel::where("exam_id", $id)->get();

            // chck online exam

            $online_exam_submission = OnlineExamModel::with(
                "examsubmision",
                "student:id,user_id,username,first_name,last_name"
            )
                ->where("exam_id", $id)
                ->get();

            //dd($online_exam_submission);
            $online_Exam = OnlineExamModel::where("exam_id", $id)->get();
            $barquestions = [];
            $barquestionspercentage = [];

            foreach ($questions as $key => $value) {
                # code...
                $barquestions[] = "Q" . ($key + 1);

                // check ques percenatge
                $per = OnlineExamSubmissionModel::where(
                    "question_id",
                    $value->id
                )
                    ->where("is_correct", 1)
                    ->count();

                $barquestionspercentage[] = $per;
            }
            $min_mark = ExamModel::where("id", $id)
                ->pluck("min_mark")
                ->first();
            // dd($min_mark);
            $pass = OnlineExamModel::where("exam_id", $id)
                ->where("total_marks", ">=", $min_mark)
                ->count();
            $fail = OnlineExamModel::where("exam_id", $id)
                ->where("total_marks", "<", $min_mark)
                ->count();
            // dd($pass, $fail);
            // dd($online_exam_submission);
            return view(
                "exam::admin.onlineexam.report",
                compact(
                    "data",
                    "online_exam_submission",
                    "questions",
                    "barquestions",
                    "barquestionspercentage",
                    "online_Exam",
                    "pass",
                    "fail"
                )
            );
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with("error", $e->getMessage());
        }
    }

    public function SubmitofflineExamMark(Request $request)
    {
        // Validate incoming request
        $this->validate(
            $request,
            [
                "marks" => "required|array",
                "marks.*" => "required|array",
            ],
            [
                "marks.*.*.min" =>
                    "The marks must be greater than or equal to zero.",
            ]
        );

        try {
            $status = "";
            if ($request->isMethod("post")) {
                $isExists = OfflineExamMarkEntry::where(
                    "exam_id",
                    $request->exam_id
                )->exists();

                // Collect all the marks in a flat array
                $marks_array = [];
                foreach ($request->marks as $student_id => $marks) {
                    foreach ($marks as $mark) {
                        $marks_array[] = $mark; // Add mark to the array
                    }
                }

                // Sort the marks array in descending order
                rsort($marks_array); // Sort high to low

                // Remove any duplicate marks
                $marks_array = array_unique($marks_array);

                // Iterate over the students to assign ranks
                foreach ($request->marks as $student_id => $marks) {
                    foreach ($marks as $mark) {
                        $exam_mark = ExamModel::find($request->exam_id);
                        $mark_status = $mark >= $exam_mark->min_mark ? 1 : 2;
                        $total_marks = $exam_mark->max_mark;

                        // Find the rank based on the sorted marks
                        $rank = array_search($mark, $marks_array) + 1;

                        // Check if the entry already exists
                        if ($isExists) {
                            $update_entry = OfflineExamMarkEntry::where([
                                "student_id" => $student_id,
                                "exam_id" => $request->exam_id,
                            ])->first();

                            if ($update_entry) {
                                $update_entry->score = $mark ?? null;
                                $update_entry->total_score = $total_marks;
                                $update_entry->mark_status = $mark_status;
                                $update_entry->entry_date = date("Y-m-d");
                                $update_entry->position = $rank;
                                $update_entry->save();
                            }
                        } else {
                            $entry = new OfflineExamMarkEntry();
                            $entry->student_id = $student_id;
                            $entry->exam_id = $request->exam_id;
                            $entry->score = $mark ?? null;
                            $entry->total_score = $total_marks;
                            $entry->mark_status = $mark_status;
                            $entry->entry_date = date("Y-m-d");
                            $entry->position = $rank;
                            $entry->save();
                        }
                    }
                }

                return redirect()
                    ->route("exam.index")
                    ->with("success", "Marks " . $status . " Successfully");
            }
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with("error", $e->getMessage());
        }
    }

    public function getQuestionsinfo(Request $request)
    {
        $question = ExamQuestionModel::find($request->id);

        $exam = ExamModel::with(
            "academyyear:id,year",
            "class:id,name",
            "section:id,name",
            "subject:id,name"
        )
            ->where("id", $question->exam_id)
            ->first();

        $submission = OnlineExamSubmissionModel::where(
            "online_exam_id",
            $request->onlineexam_id
        )
            ->where("id", $request->submission_id)
            ->first();

        $onlineExam = OnlineExamModel::where(
            "id",
            $request->onlineexam_id
        )->first();

        $view = view("exam::admin.onlineexam.showquestion")
            ->with([
                "question" => $question,
                "exam" => $exam,
                "submission" => $submission,
                "onlineExam" => $onlineExam,
            ])
            ->render();

        return response()->json(["viewfile" => $view]);

        return $submission;
    }
}
