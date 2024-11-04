<?php

namespace cms\library\Controllers;

use DB;
use CGate;

use Session;

use Illuminate\Http\Request;
use cms\library\Models\EbookModel;
use App\Http\Controllers\Controller;
use cms\core\configurations\Traits\FileUploadTrait;
use Yajra\DataTables\Facades\DataTables;
use cms\library\Models\BookCategoryModel;

class EbookController extends Controller
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
    public function index()
    {
        $data = EbookModel::where("status", 1)->paginate(12);

        //dd($data);
        return view("library::admin.ebook.index", ["data" => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = BookCategoryModel::where("status", 1)
            ->pluck("cat_name", "id")
            ->toArray();
        return view("library::admin.ebook.edit", [
            "layout" => "create",
            "categories" => $categories,
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
            "title" => "required",
            "author_name" => "required",
            "category_id" => "required",
            "attachment" => "required|mimes:pdf|max:10000",
            "cover_photo" => "required|max:20000",
        ]);
        DB::beginTransaction();
        try {
            $obj = new EbookModel();
            $obj->category_id = $request->category_id;
            $obj->title = $request->title;
            $obj->author_name = $request->author_name;

            if ($request->attachment) {
                $obj->attachment = $this->uploadAttachment(
                    $request->attachment,
                    null,
                    "school/books/"
                );
            }

            if ($request->cover_photo) {
                $obj->cover_photo = $this->CoverImage(
                    $request->cover_photo,

                    "school/books/"
                );
            }

            $obj->save();

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

        if ($request->has("submit_cat_continue")) {
            return redirect()
                ->route("ebook.create")
                ->with("success", "Saved Successfully");
        }

        Session::flash("success", "saved successfully");
        return redirect()->route("ebook.index");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = EbookModel::find($id);

        return view("library::admin.ebook.show", ["data" => $data]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = EbookModel::find($id);
        $categories = BookCategoryModel::where("status", 1)
            ->pluck("cat_name", "id")
            ->toArray();
        return view("library::admin.ebook.edit", [
            "layout" => "edit",
            "data" => $data,
            "categories" => $categories,
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
        $this->validate($request, [
            "title" => "required",
            "author_name" => "required",
            "category_id" => "required",
            "attachment" => "mimes:pdf|max:10000",
            "cover_photo" => "max:20000",
        ]);

        try {
            $obj = EbookModel::find($id);
            $obj->category_id = $request->category_id;
            $obj->title = $request->title;
            $obj->author_name = $request->author_name;

            if ($request->attachment) {
                $this->deleteImage(
                    null,
                    $obj->attachment ? $obj->attachment : null
                );
                $obj->attachment = $this->uploadAttachment(
                    $request->attachment,
                    null,
                    "school/books/"
                );
            }

            if ($request->cover_photo) {
                $this->deleteImage(
                    null,
                    $obj->cover_photo ? $obj->cover_photo : null
                );
                $obj->cover_photo = $this->CoverImage(
                    $request->cover_photo,

                    "school/books/"
                );
            }

            $obj->save();
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
        return redirect()->route("ebook.index");
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
            $delObj = new EbookModel();
            foreach ($request->selected_1 as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            $delObj = new EbookModel();
            $delItem = $delObj->find($id);
            if ($delItem->attachment) {
                $this->deleteImage(
                    null,
                    $delItem->attachment ? $delItem->attachment : null
                );
            }
            if ($delItem->cover_photo) {
                $this->deleteImage(
                    null,
                    $delItem->cover_photo ? $delItem->cover_photo : null
                );
            }
            $delItem->delete();
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("ebook.index");
    }
    /*
     * get data
     */
    public function getData(Request $request)
    {
        CGate::authorize("view-1");
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;

        DB::statement(DB::raw("set @rownum=" . (int) $sTart));

        $data = EbookModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "id",
            "name",
            "desc",
            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new EbookModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new EbookModel())->getTable() .
                    '.status = "-1" THEN "Trashed"
            ELSE "Enabled" END) AS status'
            )
        )->where("status", "!=", -1);

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
                    "route" => "1",
                ])->render();
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
        if ($request->ajax()) {
            EbookModel::find($request->id)->update([
                "status" => $request->status,
            ]);
            return response()->json([
                "success" => "Status change successfully.",
            ]);
        }

        if (!empty($request->selected_1)) {
            $obj = new EbookModel();
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
