<?php

namespace App\Http\Controllers\Api;
use DB;
use Mail;
use CGate;
use User;
use Session;
use Configurations;
use Carbon\Carbon;
use Illuminate\Http\Request;
use cms\chapter\Mail\ChapterMail;
use cms\lclass\Models\LclassModel;
use App\Http\Controllers\Controller;
use cms\chapter\Models\ChapterModel;
use cms\section\Models\SectionModel;
use cms\subject\Models\SubjectModel;
use Yajra\DataTables\Facades\DataTables;
use cms\chapter\Models\ChapterTopicModel;
use cms\department\Models\DepartmentModel;
use cms\subject\Models\SubjectTeacherMapping;
use cms\classteacher\Models\ClassteacherModel;
use cms\chapter\Models\ChapterTopicContentModel;
use cms\students\Models\StudentsModel;
use App\Traits\ApiResponse;
use cms\core\configurations\Traits\FileUploadTrait;
use cms\teacher\Models\TeacherModel;
use Illuminate\Pagination\LengthAwarePaginator;

class SyllabusApiController extends Controller
{
    use ApiResponse;

    public function create(Request $request, $layout = null, $id = null)
    {
        // abort(404);
        $user_id = $request->user()->id;
        $current_academic_year = Configurations::getCurrentAcademicyear();
        $teacher_id = TeacherModel::where("user_id", $user_id)
            ->pluck("id")
            ->first();
        $class_list = SubjectTeacherMapping::with("subject", "section", "class")
            ->where([
                "teacher_id" => $teacher_id,
                "academic_year" => $current_academic_year,
                "status" => 1,
            ])
            ->get();
        $class_subjects = [];
        foreach ($class_list as $list) {
            $text =
                $list->class->name .
                "/" .
                $list->section->name .
                "/" .
                $list->subject->name;
            $class_subjects[] = ["id" => $list->id, "text" => $text];
        }
        $departments = DepartmentModel::where("status", 1)

            ->select("id", "dept_name")
            ->get();
        if ($layout == "create") {
            $data = [
                "class_subject_list" => $class_subjects,
                "departments" => $departments,
            ];
        }
        if ($layout == "edit") {
            $chapter_data = ChapterModel::find($id);
            $data = [
                "class_subject_list" => $class_subjects,
                "departments" => $departments,
                "chapter_data" => $chapter_data,
            ];
        }
        return $this->success($data, "Data Feteched Successfully", 200);
    }

    public function store(Request $request)
    {
        //dd($request->all());
        $this->validate(
            $request,
            [
                "chapter_name" => "required",
                "subject_id" => "required",
                "description" => "required",
            ],
            [
                "description.required" => "Please Fill out Chapter Description",
                "chapter_name.required" => "Please Enter Chapter Name",
            ]
        );

        try {
            $user_id = $request->user()->id;
            $current_academic_year = Configurations::getCurrentAcademicyear();
            $teacher_id = TeacherModel::where("user_id", $user_id)
                ->pluck("id")
                ->first();
            $subject_teacher = SubjectTeacherMapping::where(
                "id",
                $request->subject_id
            )->first();
            // dd(
            //     $subject_teacher,
            //     $request->chapter_name,
            //     $request->description,
            //     $subject_teacher->class_id,
            //     $subject_teacher->section_id,
            //     $subject_teacher->subject_id
            // );
            $obj = new ChapterModel();
            $obj->chapter_name = $request->chapter_name;
            $obj->chapter_description = $request->description;
            $obj->class_id = $subject_teacher->class_id;
            $obj->section_id = $subject_teacher->section_id;
            $obj->subject_id = $subject_teacher->subject_id;
            $obj->dept_id = $request->dept_id;
            $obj->created_by = $user_id;
            $obj->updated_by = $user_id;
            $obj->save();

            DB::commit();
            return $this->success($obj, "Created Successfully", 200);
        } catch (\Exception $e) {
            $message = str_replace(
                ["\r", "\n", "'", "`"],
                " ",
                $e->getMessage()
            );

            return $this->error($message, 500);
        }
    }

    public function ChapterList(Request $request)
    {
        $user_id = $request->user()->id;
        $current_academic_year = Configurations::getCurrentAcademicyear();
        $teacher_id = TeacherModel::where("user_id", $user_id)
            ->pluck("id")
            ->first();

        $subjects = SubjectTeacherMapping::where(
            "teacher_id",
            $teacher_id
        )->pluck("subject_id");
        $chapter_list = ChapterModel::with(
            "class",
            "section",
            "subject",
            "topics"
        )
            ->whereIn("subject_id", $subjects)
            ->orderBy("created_at", "desc")
            ->get();

        $chapter_list = $chapter_list->transform(function ($list) {
            $list->total_topics = count($list->topics);
            $list->created_date = Carbon::parse($list->created_at)->format(
                "d/m/Y"
            );
            return $list;
        });

        $page = request()->get("page", 1);
        $perPage = 10;
        $collection = collect($chapter_list);
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

        return $this->success($paginator, "Data Fetched Successfully", 200);
    }

    public function update(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                "chapter_name" => "required|min:3",
                "description" => "required",
            ],
            [
                "description.required" => "Please Fill out Chapter Description",
                "chapter_name.required" => "Please Enter Chapter Name",
                "chapter_name.min" => "Chapter Name Minimum 3 Characters",
            ]
        );
        try {
            $user_id = $request->user()->id;
            $current_academic_year = Configurations::getCurrentAcademicyear();
            $teacher_id = TeacherModel::where("user_id", $user_id)
                ->pluck("id")
                ->first();

            $subject_teacher = SubjectTeacherMapping::where([
                "subject_id" => $request->subject_id,
                "teacher_id" => $teacher_id,
                "academic_year" => $current_academic_year,
            ])->first();
            $obj = ChapterModel::find($id);
            $obj->chapter_name = $request->chapter_name;
            $obj->chapter_description = $request->description;
            $obj->class_id = $subject_teacher->class_id;
            $obj->section_id = $subject_teacher->section_id;
            $obj->subject_id = $subject_teacher->subject_id;
            $obj->dept_id = $request->dept_id;
            $obj->updated_by = $user_id;
            $obj->save();

            DB::commit();
            return $this->success($obj, "Updated Successfully", 200);
        } catch (\Exception $e) {
            $message = str_replace(
                ["\r", "\n", "'", "`"],
                " ",
                $e->getMessage()
            );
            return $this->error($message, 500);
        }
    }

    public function CreateTopic(Request $request, $chapter_id)
    {
        $chapter = ChapterModel::with("class", "section", "subject")
            ->where("id", $chapter_id)
            ->first();
        $topic_types = Configurations::CHAPTERTOPICTYPE;
        return $this->success(
            ["chapter_data" => $chapter, "topic_types" => $topic_types],
            "Data Feteched successfully",
            200
        );
    }

    public function TopicStore(Request $request)
    {
        //dd($request->all());
        $this->validate($request, [
            "topic_name" => "required",
            "chapter_id" => "required",
        ]);

        try {
            DB::beginTransaction();

            $chapter = ChapterModel::where("id", $request->chapter_id)->first();

            $obj = new ChapterTopicModel();
            $obj->class_id = $chapter->class_id;
            $obj->section_id = $chapter->section_id;
            $obj->subject_id = $chapter->subject_id;
            $obj->chapter_id = $request->chapter_id;
            $obj->topic_name = $request->topic_name;
            $obj->created_by = $request->user()->id;
            $obj->updated_by = $request->user()->id;
            $obj->topic_description = $request->description;

            $obj->save();

            if ($request->documenttype == "image") {
                if (!empty($request->chapter_image)) {
                    foreach ($request->chapter_image as $file) {
                        if ($file != null) {
                            $exTension = $file->getClientOriginalExtension();
                            $fileName = uniqid() . "." . $exTension;

                            //dd($fileName);
                            $destinationPath =
                                public_path() . "/school/chapter/topicimages";
                            $file->move($destinationPath, $fileName);

                            $full_name =
                                "/school/chapter/topicimages/" . $fileName;

                            //save data

                            $content = new ChapterTopicContentModel();

                            $content->chapter_id = $request->chapter_id;
                            $content->topic_id = $obj->id;
                            $content->content_type = $request->documenttype;
                            $content->content_url = $full_name;
                            $content->created_by = $request->user()->id;
                            $content->updated_by = $request->user()->id;
                            $content->save();
                        }
                    }
                }
            }
            if ($request->documenttype == "document") {
                if (!empty($request->chapter_document)) {
                    foreach ($request->chapter_document as $file) {
                        if ($file != null) {
                            $exTension = $file->getClientOriginalExtension();
                            $fileName = uniqid() . "." . $exTension;

                            //dd($fileName);
                            $destinationPath =
                                public_path() . "/school/chapter/topicdocument";
                            $file->move($destinationPath, $fileName);

                            $full_name =
                                "/school/chapter/topicdocument/" . $fileName;

                            //save data

                            $content = new ChapterTopicContentModel();

                            $content->chapter_id = $request->chapter_id;
                            $content->topic_id = $obj->id;
                            $content->content_type = $request->documenttype;
                            $content->content_url = $full_name;
                            $content->created_by = $request->user()->id;
                            $content->updated_by = $request->user()->id;
                            $content->save();
                        }
                    }
                }
            }
            if ($request->documenttype == "video") {
                if (!empty($request->chapter_video)) {
                    foreach ($request->chapter_video as $file) {
                        if ($file != null) {
                            $video = Configurations::getVedioid($file);
                            //dd("https://www.youtube.com/embed/" . $video);
                            $content = new ChapterTopicContentModel();

                            $content->chapter_id = $request->chapter_id;
                            $content->topic_id = $obj->id;
                            $content->content_type = $request->documenttype;
                            $content->content_url =
                                "https://www.youtube.com/embed/" . $video;
                            $content->created_by = $request->user()->id;
                            $content->updated_by = $request->user()->id;
                            $content->save();
                        }
                    }
                }
            }
            DB::commit();
            return $this->success($obj, "Created Successfully", 200);
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

    public function TopicEdit($id)
    {
        $topic_data = ChapterTopicModel::with("contents")->find($id);
        $type = ChapterTopicContentModel::where(
            "topic_id",
            $topic_data->id
        )->first();

        $chapter = ChapterModel::find($topic_data->chapter_id);
        $topic_types = Configurations::CHAPTERTOPICTYPE;
        // dd($data);
        $data = [
            "chapter" => $chapter,
            "topic_data" => $topic_data,
            "topic_types" => $topic_types,
        ];
        return $this->success($data, "Data Feteched Successfully", 200);
    }

    public function TopicUpdate(Request $request, $id)
    {
        //dd($request->all());
        $this->validate($request, [
            "topic_name" => "required",
        ]);
        DB::beginTransaction();
        try {
            $obj = ChapterTopicModel::find($id);
            $obj->topic_name = $request->topic_name;
            $obj->topic_description = $request->description;
            $obj->updated_by = $request->user()->id;
            $obj->save();

            if ($request->documenttype == "image") {
                if (!empty($request->chapter_image)) {
                    $delt = ChapterTopicContentModel::where([
                        "chapter_id" => $obj->chapter_id,
                        "topic_id" => $id,
                    ])->forceDelete();
                    if ($delt) {
                        foreach ($request->chapter_image as $file) {
                            if ($file != null) {
                                $exTension = $file->getClientOriginalExtension();
                                $fileName = uniqid() . "." . $exTension;

                                //dd($fileName);
                                $destinationPath =
                                    public_path() .
                                    "/school/chapter/topicimages";
                                $file->move($destinationPath, $fileName);

                                $full_name =
                                    "/school/chapter/topicimages/" . $fileName;

                                //save data

                                $content = new ChapterTopicContentModel();

                                $content->chapter_id = $obj->chapter_id;
                                $content->topic_id = $obj->id;
                                $content->content_type = $request->documenttype;
                                $content->content_url = $full_name;
                                $content->updated_by = $request->user()->id;
                                $content->save();
                            }
                        }
                    }
                }
            }
            if ($request->documenttype == "document") {
                if (!empty($request->chapter_document)) {
                    $delt = ChapterTopicContentModel::where([
                        "chapter_id" => $obj->chapter_id,
                        "topic_id" => $id,
                    ])->forceDelete();
                    if ($delt) {
                        foreach ($request->chapter_document as $file) {
                            if ($file != null) {
                                $exTension = $file->getClientOriginalExtension();
                                $fileName = uniqid() . "." . $exTension;

                                //dd($fileName);
                                $destinationPath =
                                    public_path() .
                                    "/school/chapter/topicdocument";
                                $file->move($destinationPath, $fileName);

                                $full_name =
                                    "/school/chapter/topicdocument/" .
                                    $fileName;

                                //save data

                                $content = new ChapterTopicContentModel();

                                $content->chapter_id = $obj->chapter_id;
                                $content->topic_id = $obj->id;
                                $content->content_type = $request->documenttype;
                                $content->content_url = $full_name;
                                $content->updated_by = $request->user()->id;
                                $content->save();
                            }
                        }
                    }
                }
            }
            if ($request->documenttype == "video") {
                if (!empty($request->chapter_video)) {
                    $delt = ChapterTopicContentModel::where([
                        "chapter_id" => $obj->chapter_id,
                        "topic_id" => $id,
                    ])->forceDelete();
                    if ($delt) {
                        foreach ($request->chapter_video as $file) {
                            if ($file != null) {
                                //$url = "";

                                if (str_contains($file, "embed")) {
                                    $url = $file;
                                } else {
                                    $video = Configurations::getVedioid($file);
                                    $url =
                                        "https://www.youtube.com/embed/" .
                                        $video;
                                }

                                //save data

                                $content = new ChapterTopicContentModel();

                                $content->chapter_id = $obj->chapter_id;
                                $content->topic_id = $obj->id;
                                $content->content_type = $request->documenttype;
                                $content->content_url = $url;
                                $content->updated_by = $request->user()->id;
                                $content->save();
                            }
                        }
                    }
                }
            }
            DB::commit();
            return $this->success($obj, "Updated Successfully", 200);
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

    public function deleteContent(Request $request, $id)
    {
        $dlt_content = ChapterTopicContentModel::find($id);
        if ($dlt_content) {
            $dlt_content->forceDelete();
        }

        return $this->success("Deleted Successfully", 200);
    }

    public function ChapterView(Request $request, $id)
    {
        $chapter = ChapterModel::with("class", "section", "subject", "topics")
            ->where("id", $id)
            ->first();

        $chapter->created_date = Carbon::parse($chapter->created_at)->format(
            "d/m/Y"
        );

        $chapter->total_topics = count($chapter->topics);

        return $this->success($chapter, "Data Feteched Successfully", 200);
    }

    public function TopicView(Request $request, $id)
    {
        $topic_data = ChapterTopicModel::with("contents")->find($id);
        return $this->success($topic_data, "Data Fetched Successfully", 200);
    }

    public function TopicDelete(Request $request, $id)
    {
        $topic = ChapterTopicModel::find($id);
        if ($topic) {
            $content_dlt = ChapterTopicContentModel::where(
                "topic_id",
                $topic->id
            )->delete();

            $topic->delete();
        } else {
            return $this->error("Topic Was Not Found", 500);
        }

        return $this->success("Deleted Successfully", 200);
    }

    public function ContentDelete(Request $request, $id)
    {
        $content = ChapterTopicContentModel::find($id);
        if ($content) {
            $content->delete();
        }

        return $this->success("Deleted Successfully", 200);
    }

    public function ChapterDelete(Request $request, $id)
    {
        $chapter = ChapterModel::find($id);
        if ($chapter) {
            $topics = ChapterTopicModel::where("chapter_id", $id)->delete();
            $content_dlt = ChapterTopicContentModel::where(
                "chapter_id",
                $id
            )->delete();

            $chapter->delete();
        } else {
            return $this->error("Chapter Was Not Found", 500);
        }

        return $this->success("Deleted Successfully", 200);
    }
}
