<?php

namespace cms\chapter\Controllers;

use DB;
use CGate;
use User;
use Session;

use Illuminate\Http\Request;
use cms\lclass\Models\LclassModel;
use App\Http\Controllers\Controller;
use cms\chapter\Models\ChapterModel;
use cms\section\Models\SectionModel;
use cms\subject\Models\SubjectModel;
use Yajra\DataTables\Facades\DataTables;
use cms\chapter\Models\ChapterTopicModel;
use cms\chapter\Models\ChapterTopicContentModel;
use cms\core\configurations\helpers\Configurations;

class ChapterTopicController extends Controller
{
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
            $content_id = $request->content;

            $content = ChapterTopicContentModel::find($content_id);
            if ($content) {
                $content->delete();

                return true;
            } else {
                return false;
            }
        }
        abort(404);
        return view("1::admin.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $chaptername = ChapterModel::chaptername($request->get("chapter_id"));
        $classname = LclassModel::classname($request->get("class_id"));
        $sectionname = SectionModel::sectionname($request->get("section_id"));
        $subjectname = SubjectModel::subjectname($request->get("subject_id"));
        $sections = [];
        $subjects = [];
        $chapter_id = $request->get("chapter_id");
        $class_id = $request->get("class_id");
        $section_id = $request->get("section_id");
        $subject_id = $request->get("subject_id");
        $class_list = LclassModel::where("status", "!=", -1)
            ->whereNull("deleted_at")
            ->pluck("name", "id")
            ->toArray();
        //dd($chapter_id);
        return view("chapter::admin.chaptertopicedit", [
            "layout" => "create",
            "chapter_id" => $chapter_id,
            "class_id" => $class_id,
            "section_id" => $section_id,
            "subject_id" => $subject_id,
            "chaptername" => $chaptername,
            "classname" => $classname,
            "sectionname" => $sectionname,
            "subjectname" => $subjectname,
            "type" => "",
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $this->validate($request, [
            "topic_name" => "required",
            "class_id" => "required",
            "section_id" => "required",
            "subject_id" => "required",
            "chapter_id" => "required",
        ]);
        DB::beginTransaction();
        try {
            $obj = new ChapterTopicModel();
            $obj->class_id = $request->class_id;
            $obj->section_id = $request->section_id;
            $obj->subject_id = $request->subject_id;
            $obj->chapter_id = $request->chapter_id;
            $obj->topic_name = $request->topic_name;
            $obj->topic_description = $request->description;
            $obj->created_by = User::getUser()->id;
            $obj->updated_by = User::getUser()->id;
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
                            $content->created_by = User::getUser()->id;
                            $content->updated_by = User::getUser()->id;
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

                            $content->save();
                        }
                    }
                }
            }
            if ($request->documenttype == "video") {
                if (!empty($request->chapter_vedio)) {
                    foreach ($request->chapter_vedio as $file) {
                        if ($file != null) {
                            $video = Configurations::getVedioid($file);
                            //dd("https://www.youtube.com/embed/" . $vedio);
                            $content = new ChapterTopicContentModel();

                            $content->chapter_id = $request->chapter_id;
                            $content->topic_id = $obj->id;
                            $content->content_type = $request->documenttype;
                            $content->content_url =
                                "https://www.youtube.com/embed/" . $video;

                            $content->save();
                        }
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
        return redirect()->route("chapter.index");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //dd($id);

        $data = ChapterTopicModel::with("contents")->find($id);
        return view("chapter::admin.chaptertopicview", [
            "data" => $data,
        ]);
        //dd($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = ChapterTopicModel::with("contents")->find($id);
        $type = ChapterTopicContentModel::where("topic_id", $data->id)->first();

        $chaptername = ChapterModel::chaptername($data->chapter_id);
        // dd($data);
        return view("chapter::admin.chaptertopicedit", [
            "layout" => "edit",
            "data" => $data,
            "chaptername" => $chaptername,
            "type" => $type->content_type,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
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
            $obj->updated_by = User::getUser()->id;
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

                            $content->chapter_id = $obj->chapter_id;
                            $content->topic_id = $obj->id;
                            $content->content_type = $request->documenttype;
                            $content->content_url = $full_name;
                            $content->updated_by = User::getUser()->id;
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

                            $content->chapter_id = $obj->chapter_id;
                            $content->topic_id = $obj->id;
                            $content->content_type = $request->documenttype;
                            $content->content_url = $full_name;
                            $content->updated_by = User::getUser()->id;
                            $content->save();
                        }
                    }
                }
            }
            if ($request->documenttype == "video") {
                if (!empty($request->chapter_vedio)) {
                    ChapterTopicContentModel::where("topic_id", $id)->delete();
                    foreach ($request->chapter_vedio as $file) {
                        if ($file != null) {
                            //$url = "";

                            if (str_contains($file, "embed")) {
                                $url = $file;
                            } else {
                                $vedio = Configurations::getVedioid($file);
                                $url =
                                    "https://www.youtube.com/embed/" . $vedio;
                            }

                            //save data

                            $content = new ChapterTopicContentModel();

                            $content->chapter_id = $obj->chapter_id;
                            $content->topic_id = $obj->id;
                            $content->content_type = $request->documenttype;
                            $content->content_url = $url;
                            $content->updated_by = User::getUser()->id;
                            $content->save();
                        }
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
        return redirect()->route("chapter.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if (!empty($request->selected_1)) {
            $delObj = new ChapterTopicModel();
            foreach ($request->selected_1 as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }

        if ($id) {
            $topic = ChapterTopicModel::find($id);

            ChapterTopicContentModel::where("topic_id", $topic->id)->delete();

            $topic->delete();
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("chapter.index");
    }
    /*
     * get data
     */
    public function getData(Request $request, $id)
    {
        CGate::authorize("view-chapter");
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;

        DB::statement(DB::raw("set @rownum=" . (int) $sTart));

        $data = ChapterTopicModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "chapter_topics.id as id",
            "topic_name",

            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new ChapterTopicModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new ChapterTopicModel())->getTable() .
                    '.status = "-1" THEN "Trashed"
            ELSE "Enabled" END) AS status'
            )
        )
            ->where("chapter_topics.chapter_id", $id)
            ->where("deleted_by", null);

        $datatables = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn("check", function ($data) {
                if ($data->id != "1") {
                    return $data->rownum;
                } else {
                    return "";
                }
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
                    "route" => "chaptertopic",
                    "subroute" => "chapter",
                ])->render();

                //return $data->id;
            });

        // return $data;
        if (count((array) $data) == 0) {
            return [];
        }

        return $datatables->make(true);
    }

    /*
     * country bulk action
     * eg : trash,enabled,disabled
     * delete is destroy function
     */
    function statusChange(Request $request)
    {
        CGate::authorize("edit-1");

        if (!empty($request->selected_1)) {
            $obj = new ChapterTopicModel();
            foreach ($request->selected_1 as $k => $v) {
                if ($item = $obj->find($v)) {
                    $item->status = $request->action;
                    $item->save();
                }
            }
        }

        Session::flash("success", "Status changed Successfully!!");
        return redirect()->back();
    }
}
