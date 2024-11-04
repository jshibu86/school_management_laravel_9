<?php

namespace cms\library\Controllers;

use DB;
use User;
use CGate;
use Configurations;
use Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use cms\core\user\Models\UserModel;
use App\Http\Controllers\Controller;
use cms\core\usergroup\Models\UserGroupMapModel;
use cms\core\usergroup\Models\UserGroupModel;
use cms\library\Models\LibraryModel;
use cms\students\Models\StudentsModel;
use cms\library\Models\IssuedBookModel;
use Yajra\DataTables\Facades\DataTables;
use cms\library\Models\LibraryMemberModel;

class IssuedBookController extends Controller
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
    public function index()
    {
        if (Session::get("ACTIVE_GROUP") != "Super Admin") {
            $active_user = User::getUser()->id;

            $group = UserGroupMapModel::where("user_id", $active_user)->first();

            $group_name = UserGroupModel::where(
                "id",
                $group->group_id
            )->first();

            if ($group_name->group == "Student") {
                // return "students";
                $student_id = Configurations::Activestudent()->id;
                //return $student_id;
                $is_member = LibraryMemberModel::whereNotNull("student_id")
                    ->where("student_id", $student_id)
                    ->first();

                // return $is_member;

                if (!$is_member) {
                    return redirect()
                        ->back()
                        ->with(
                            "exception_error",
                            "You are Not an Library Member | Contact Adminstrator"
                        );
                } else {
                    return view("library::admin.issuedbook.index");
                }
            } else {
                $is_member = LibraryMemberModel::whereNotNull("student_id")
                    ->where("member_id", $active_user)
                    ->first();

                if ($is_member) {
                    return view("library::admin.issuedbook.index");
                } else {
                    return redirect()
                        ->back()
                        ->with(
                            "exception_error",
                            "You are Not an Library Member | Contact Adminstrator"
                        );
                }
            }
        }

        return view("library::admin.issuedbook.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $members = [];
        $member = LibraryMemberModel::where("status", 1)->get();

        foreach ($member as $mem) {
            $user = UserModel::find($mem->member_id);

            $members[$mem->id] = $user->name . "-" . $mem->member_username;
        }

        //dd($members);

        $Books = LibraryModel::where("status", 1)
            ->where("quantity", ">", 0)
            ->pluck("title", "id")
            ->toArray();
        return view("library::admin.issuedbook.edit", [
            "layout" => "create",
            "categories" => $members,
            "books" => $Books,
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
        //dd($request->all());
        $this->validate($request, [
            "return_date" => "required",
        ]);
        DB::beginTransaction();
        try {
            $obj = new IssuedBookModel();
            $obj->member_id = $request->member_id;
            $obj->book_id = $request->book_id;
            $obj->return_date = $request->return_date;
            $obj->issued_by = User::getUser()->id;
            $obj->issued_date = date("Y-m-d");

            if ($obj->save()) {
                // decrese the quantity of book

                LibraryModel::where("id", $request->book_id)->decrement(
                    "quantity"
                );
                LibraryModel::where("id", $request->book_id)->increment(
                    "book_rended"
                );
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

        if ($request->has("submit_cat_continue")) {
            return redirect()
                ->route("issuebook.create")
                ->with("success", "Saved Successfully");
        }

        Session::flash("success", "saved successfully");
        return redirect()->route("issuebook.index");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = IssuedBookModel::find($id);

        //dd($data);
        $members = LibraryMemberModel::where("status", 1)
            ->pluck("member_username", "id")
            ->toArray();

        $Books = LibraryModel::where("status", 1)

            ->pluck("title", "id")
            ->toArray();

        return view("library::admin.issuedbook.edit", [
            "layout" => "edit",
            "data" => $data,
            "categories" => $members,
            "books" => $Books,
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
            "return_date" => "required",
        ]);

        try {
            $obj = IssuedBookModel::find($id);

            $obj->return_date = $request->return_date;
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
        return redirect()->route("issuebook.index");
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
            $delObj = new IssuedBookModel();
            foreach ($request->selected_1 as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            $delObj = new IssuedBookModel();
            $delItem = $delObj->find($id);
            $delItem->delete();
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("issuebook.index");
    }
    public function returnBook($id, Request $request)
    {
        // finding issuedbook

        $issue_book = IssuedBookModel::where("id", $id)->first();

        // increment book count

        $book = LibraryModel::where("id", $issue_book->book_id)->first();

        $book->increment("quantity");

        if ($book->book_rended > 0) {
            $book->decrement("book_rended", 1);
        }

        $issue_book->update([
            "is_return" => 1,
            "returned_date" => date("Y-m-d"),
        ]);

        return redirect()
            ->route("issuebook.index")
            ->with("success", "Book Returned Successfully");
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

        $data = IssuedBookModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "issued_books.id as id",
            "issued_books.member_id as member_id",
            "issued_books.is_return as is_return",
            "issued_books.return_date as return_date",
            "issued_books.issued_date as issued_date",
            "library_member.member_type as member_type",
            "library_member.member_username as memberid",
            "books.title as title",
            "users.name as username_",
            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new IssuedBookModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new IssuedBookModel())->getTable() .
                    '.status = "-1" THEN "Trashed"
            ELSE "Enabled" END) AS status'
            )
        )
            ->where("issued_books.status", "!=", -1)

            ->join(
                "library_member",
                "library_member.id",
                "=",
                "issued_books.member_id"
            )
            ->join("books", "books.id", "=", "issued_books.book_id")

            ->join("users", "users.id", "=", "library_member.member_id");

        if (Session::get("ACTIVE_GROUP") != "Super Admin") {
            $active_user = User::getUser()->id;

            $group = UserGroupMapModel::where("user_id", $active_user)->first();

            $group_name = UserGroupModel::where(
                "id",
                $group->group_id
            )->first();

            if ($group_name->group == "Student") {
                // return "students";
                $student_id = Configurations::Activestudent()->id;
                //return $student_id;
                $is_member = LibraryMemberModel::whereNotNull("student_id")
                    ->where("student_id", $student_id)
                    ->first();

                // return $is_member;

                if ($is_member) {
                    // return "yes";
                    $data = $data->where(
                        "issued_books.member_id",
                        $is_member->id
                    );
                }
            } else {
                $is_member = LibraryMemberModel::whereNotNull("student_id")
                    ->where("member_id", $active_user)
                    ->first();

                if ($is_member) {
                    $data = $data->where(
                        "issued_books.member_id",
                        $is_member->id
                    );
                }
            }
        }

        $datatables = Datatables::of($data)
            ->addIndexColumn()

            ->addColumn("membertype", function ($data) {
                return "<span class='badge bg-primary'>" .
                    $data->member_type .
                    "</span>";
            })

            ->addColumn("return", function ($data) {
                if ($data->is_return == "1") {
                    return "<span class='badge bg-success '>Returned</span>";
                } elseif ($data->is_return == "0") {
                    return "<span class='badge bg-warning'>Issued</span>";
                } else {
                    return "<span class='text-danger'>Fined</span>";
                }
            })
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
                return view("layout::datatable.dropdownaction", [
                    "data" => $data,
                    "route" => "issuebook",
                ])->render();
            });

        // return $data;
        if (count((array) $data) == 0) {
            return [];
        }

        return $datatables
            ->rawColumns(["return", "action", "membertype"])
            ->make(true);
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
            IssuedBookModel::find($request->id)->update([
                "status" => $request->status,
            ]);
            return response()->json([
                "success" => "Status change successfully.",
            ]);
        }

        if (!empty($request->selected_1)) {
            $obj = new IssuedBookModel();
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
