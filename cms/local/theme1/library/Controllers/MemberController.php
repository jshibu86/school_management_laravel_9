<?php

namespace cms\library\Controllers;

use DB;
use CGate;

use Session;
use Carbon\Carbon;
use Configurations;
use Illuminate\Http\Request;
use cms\lclass\Models\LclassModel;
use cms\core\user\Models\UserModel;
use App\Http\Controllers\Controller;
use cms\section\Models\SectionModel;
use cms\students\Models\StudentsModel;
use Yajra\DataTables\Facades\DataTables;
use cms\library\Models\LibraryMemberModel;
use cms\core\usergroup\Models\UserGroupModel;
use cms\core\usergroup\Models\UserGroupMapModel;
use cms\fees\Models\AcademicFeeModel;

class MemberController extends Controller
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
    public function GetSubscription()
    {
        $fees = Configurations::getConfig("site")->library_subscription;
        return $fees;
    }
    public function index()
    {
        // dd("index");
        return view("library::admin.member.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // dd("here");
        //getting all groups

        $groups = UserGroupModel::where("status", 1)
            ->pluck("group", "id")
            ->toArray();
        $class_list = LclassModel::where("status", 1)
            ->orderBy("name", "asc")
            ->pluck("name", "id")
            ->toArray();
        //return $classes;
        return view("library::admin.member.edit", [
            "layout" => "create",
            "groups" => $groups,

            "class_list" => $class_list,
            "sections" => [],

            "students" => [],
            "users" => [],
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
        $this->validate(
            $request,
            [
                "member_type" => "required",
            ],
            ["member_type.required" => "Please Select Member Type"]
        );
        DB::beginTransaction();
        try {
            if ($request->student_id) {
                // dd("here");
                $is_exists = LibraryMemberModel::where(
                    "student_id",
                    $request->student_id
                )->first();

                if ($is_exists) {
                    return redirect()
                        ->back()
                        ->withInput()
                        ->with(
                            "exception_error",
                            "This Student Already Registered || Or Inactive State"
                        );
                }
            } else {
                $is_exists = LibraryMemberModel::where(
                    "member_id",
                    $request->member_id
                )->first();
                if ($is_exists) {
                    return redirect()
                        ->back()
                        ->withInput()
                        ->with(
                            "exception_error",
                            "This Member Already Registered || Inactive State"
                        );
                }
            }

            // dd("yes");
            $bmember_info = LibraryMemberModel::withTrashed()
                ->latest("id")
                ->first();
            $member_username = Configurations::GenerateUsername(
                $bmember_info != null ? $bmember_info->member_username : null,
                "LM"
            );

            $member_type = UserGroupModel::where(
                "id",
                $request->member_type
            )->first();
            $date = Carbon::now("Asia/Kolkata")->toDateString();
            $obj = new LibraryMemberModel();

            $obj->member_username = $member_username;
            $obj->date_ofjoin = $date;
            $obj->academic_year = Configurations::getCurrentAcademicyear();

            $obj->group_id = $request->member_type;
            $obj->member_type = strtolower($member_type->group);

            if ($request->class_id) {
                $student = StudentsModel::find($request->student_id);
                $obj->class_id = $request->class_id;
                $obj->section_id = $request->section_id;
                $obj->student_id = $request->student_id;
                $obj->member_id = $student->user_id;
            } else {
                $obj->member_id = $request->member_id;
            }

            $obj->save();
            if ($obj->save()) {
                if ($student) {
                    $months_library = Configurations::GetMonthsOfAcademicYear(
                        Configurations::getCurrentAcademicyear(),
                        date("Y-m-d")
                    );

                    // save acdemic fees
                    $fee = new AcademicFeeModel();
                    $fee->academic_year = Configurations::getCurrentAcademicyear();
                    $fee->student_id = $student->id;
                    $fee->model_id = $obj->id;
                    $fee->model_name = LibraryMemberModel::class;
                    $fee->added_date = date("Y-m-d");
                    $fee->type = "library";
                    $fee->fee_name = "Library Fees";
                    $fee->due_amount =
                        sizeof($months_library) * $this->GetSubscription();
                    $fee->month_info = json_encode($months_library);
                    $fee->save();
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

        if ($request->has("submit_cat_continue")) {
            return redirect()
                ->route("member.create")
                ->with("success", "Saved Successfully");
        }

        Session::flash("success", "saved successfully");
        return redirect()->route("member.index");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id, $print = null)
    {
        //abort(404);

        $data = LibraryMemberModel::find($id);

        if ($data->student_id == null) {
            $member_data = UserModel::where("id", $data->member_id)->first();
        } else {
            $member_data = StudentsModel::where(
                "id",
                $data->student_id
            )->first();
        }

        if ($print) {
            return view("library::admin.member.idcard.html.idcard", [
                "user" => $member_data,
                "data" => $data,
            ]);
        }

        // dd($member_data, $data);

        return view("library::admin.member.show", [
            "user" => $member_data,
            "data" => $data,
        ]);

        //dd($member_data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = LibraryMemberModel::find($id);
        $class_list = LclassModel::where("status", "!=", -1)
            ->whereNull("deleted_at")
            ->pluck("name", "id")
            ->toArray();
        $sections = SectionModel::where("status", "!=", -1)
            ->whereNull("deleted_at")
            ->where("class_id", @$data->class_id)
            ->pluck("name", "id")
            ->toArray();
        $groups = UserGroupModel::where("status", 1)
            ->pluck("group", "id")
            ->toArray();

        if ($data->member_type == "student") {
            $students = StudentsModel::where([
                "class_id" => $data->class_id,
                "section_id" => $data->section_id,
            ])
                ->select([
                    "students.id as id",
                    DB::raw(
                        "CONCAT(students.username, ' - ', students.email) as text"
                    ),
                ])
                ->pluck("text", "id")
                ->toArray();
        } else {
            $students = [];
        }
        $users = [];

        if ($data->member_type != "student") {
            //dd("here");
            $user_ids = UserGroupMapModel::where(
                "group_id",
                $data->group_id
            )->pluck("user_id");
            $users = UserModel::where("status", 1)
                ->whereNull("deleted_at")
                ->whereIn("id", $user_ids)
                ->select([
                    "users.id as id",
                    DB::raw(
                        "CONCAT(users.username, ' - ', users.email) as text"
                    ),
                ])
                ->pluck("text", "id")
                ->toArray();
        }
        // dd($users);

        $group = UserGroupModel::where("id", $data->group_id)->first();
        // dd($data);
        return view("library::admin.member.edit", [
            "layout" => "edit",
            "data" => $data,
            "class_list" => $class_list,
            "sections" => $sections,
            "groups" => $groups,
            "students" => $students,
            "users" => $users,
            "group" => $group->group,
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
        $this->validate(
            $request,
            [
                "member_type" => "required",
            ],
            ["member_type.required" => "Please Select Member Type"]
        );

        try {
            $member_type = UserGroupModel::where(
                "id",
                $request->member_type
            )->first();
            $obj = LibraryMemberModel::find($id);

            $obj->group_id = $request->member_type;
            $obj->member_type = strtolower($member_type->group);

            if ($request->class_id) {
                $obj->class_id = $request->class_id;
                $obj->section_id = $request->section_id;
                $obj->student_id = $request->student_id;
            } else {
                $obj->member_id = $request->member_id;
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
        return redirect()->route("member.index");
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
            $delObj = new LibraryMemberModel();
            foreach ($request->selected_1 as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            $delObj = new LibraryMemberModel();
            $date = Carbon::now("Asia/Kolkata")->toDateString();
            $delItem = $delObj->find($id);
            $delItem->update(["status" => -1, "date_ofleave" => $date]);

            $delItem->delete();
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("member.index");
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

        $data = LibraryMemberModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "library_member.id as id",
            "library_member.member_type as member_type",
            "library_member.member_username as username",
            "library_member.student_id as student_id",
            "library_member.member_id as member_id",
            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new LibraryMemberModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new LibraryMemberModel())->getTable() .
                    '.status = "-1" THEN "Trashed"
            ELSE "Enabled" END) AS status'
            )
        )

            ->leftjoin("users", "users.id", "=", "library_member.member_id")
            ->leftjoin(
                "students",
                "students.id",
                "=",
                "library_member.student_id"
            );

        $datatables = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn("check", function ($data) {
                if ($data->id != "1") {
                    return $data->rownum;
                } else {
                    return "";
                }
            })
            ->addColumn("userinfo", function ($data) {
                if ($data->student_id == null) {
                    $member_data = UserModel::where(
                        "id",
                        $data->member_id
                    )->first();

                    return $member_data->name;
                } else {
                    $member_data = StudentsModel::where(
                        "id",
                        $data->student_id
                    )->first();

                    return $member_data->first_name .
                        " " .
                        $member_data->last_name;
                }
            })
            ->addColumn("useremail", function ($data) {
                if ($data->student_id == null) {
                    $member_data = UserModel::where(
                        "id",
                        $data->member_id
                    )->first();

                    return $member_data->email;
                } else {
                    $member_data = StudentsModel::where(
                        "id",
                        $data->student_id
                    )->first();

                    return $member_data->email;
                }
            })
            ->addColumn("membertype", function ($data) {
                return "<span class='badge bg-primary'>" .
                    $data->member_type .
                    "</span>";
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
                    "route" => "member",
                ])->render();
            });

        // return $data;
        if (count((array) $data) == 0) {
            return [];
        }

        return $datatables->rawColumns(["membertype", "action"])->make(true);
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
            LibraryMemberModel::find($request->id)->update([
                "status" => $request->status,
            ]);
            // $acdemic_fee = AcademicFeeModel::where([
            //     "model_id" => $request->id,
            //     "type" => "library",
            // ])->first();
            // if ($acdemic_fee) {
            //     $acdemic_fee->update(["status" => $request->status]);
            // }
            return response()->json([
                "success" => "Status change successfully.",
            ]);
        }

        if (!empty($request->selected_1)) {
            $obj = new LibraryMemberModel();
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

    public function historyMember(Request $request, $id)
    {
        $data = LibraryMemberModel::with("user")->find($id);

        return view("library::admin.member.historymember", ["data" => $data]);
    }

    public function getDatahistoryMember(Request $request, $id)
    {
        CGate::authorize("view-library");
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;

        DB::statement(DB::raw("set @rownum=" . (int) $sTart));

        $data = LibraryMemberModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "library_member.id as id",
            "books.title as btitle",
            "library_member.member_username as memusername",
            "issued_books.issued_date as issued_date",
            "issued_books.return_date as return_date",
            "issued_books.issued_by as issued_by",
            "issued_books.is_return as is_return",
            "issued_books.returned_date as returned_date",
            "users.name as uname",
            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new LibraryMemberModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new LibraryMemberModel())->getTable() .
                    '.status = "-1" THEN "Trashed"
            ELSE "Enabled" END) AS status'
            )
        )
            ->where("library_member.status", "!=", -1)
            ->join(
                "issued_books",
                "library_member.id",
                "=",
                "issued_books.member_id"
            )
            ->join("books", "books.id", "=", "issued_books.book_id")
            ->join("users", "users.id", "=", "library_member.member_id")
            ->where("library_member.id", $id);

        $datatables = Datatables::of($data)
            ->addIndexColumn()

            ->addColumn("issuedDate", function ($data) {
                return date("Y-m-d", strtotime($data->issued_date));
            })

            ->addColumn("returnDate", function ($data) {
                if ($data->return_date == null) {
                    return "<span class='badge bg-rose'>Not Return</span>";
                } else {
                    return date("Y-m-d", strtotime($data->return_date));
                }
            })
            ->addColumn("returnedDate", function ($data) {
                if ($data->returned_date == null) {
                    return "<span class='badge bg-rose'>Not Return</span>";
                } else {
                    return $data->returned_date;
                }
            })
            ->addColumn("bookstatus", function ($data) {
                if ($data->is_return == 0) {
                    return "<span class='badge bg-rose'>Not Return</span>";
                } elseif ($data->is_return == 1) {
                    return "<span class='badge bg-success'>Return</span>";
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
                return view("layout::datatable.action", [
                    "data" => $data,
                    "route" => "library",
                ])->render();
            });

        // return $data;
        if (count((array) $data) == 0) {
            return [];
        }

        return $datatables
            ->rawColumns(["action", "returnDate", "bookstatus", "returnedDate"])
            ->make(true);
    }
}
