<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use cms\chapter\Models\ChapterModel;
use cms\subject\Models\SubjectModel;
use Illuminate\Http\Request;

class AcademicController extends StudentBaseController
{
    use ApiResponse;

    // $student = $this->GetStudent($request->user()->id);

    //academicsubjectsand chapter

    public function AcademicSubjects(Request $request)
    {
        $search = $request->query->get("search", 0);
        $subjects = SubjectModel::withCount("chapter")
            ->where("status", 1)
            ->where(
                "class_id",
                $this->GetStudent($request->user()->id)->class_id
            )
            ->when($search, function ($q) use ($search) {
                $q->where("name", "like", "%" . $search . "%");
            })
            ->get();
        return $this->success($subjects, "Successfully Fetched", 200);
    }

    public function getChapterandTopics(Request $request, $subject_id)
    {
        $data = ChapterModel::with([
            "topics" => function ($query) {
                $query
                    ->with("contents")
                    ->select("id", "chapter_id", "topic_name", "created_at");
            },
        ])
            ->with("class:id,name", "section:id,name", "subject:id,name")
            ->where("subject_id", $subject_id)
            ->get();

        return $this->success($data, "Successfully Fetched", 200);
    }
}
