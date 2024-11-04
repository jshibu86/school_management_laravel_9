<?php

namespace cms\library\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\library\Models\LibraryModel;

use Yajra\DataTables\Facades\DataTables;

use Session;
use DB;
use CGate;
use cms\library\Models\BookCategoryModel;
use cms\library\Models\IssuedBookModel;
use Configurations;

class LibraryController extends Controller
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
            $id = $request->query->get("id");
            $status = $request->query->get("status");

            $book_details = LibraryModel::find($id);

            $view = view("library::admin.datatable.bookstatus", [
                "id" => $id,
                "status" => $status,
                "book" => $book_details,
            ])->render();

            return response()->json(["viewfile" => $view]);
        }
        return view("library::admin.index");
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
        return view("library::admin.edit", [
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
        $this->validate(
            $request,
            [
                "isbn_no" => "required|max:191",
                "title" => "required",
                "publisher_name" => "required",
                "author_name" => "required",
            ],
            [
                "author_name.required" => "Please Provide Author Name",
                "isbn_no.max" => "ISBN Number to long",
            ]
        );
        DB::beginTransaction();
        try {
            // generation book number

            $book_info = LibraryModel::withTrashed()
                ->latest("id")
                ->first();

            $book_no = Configurations::GenerateUsername(
                $book_info != null ? $book_info->book_no : null,
                "LB"
            );

            $form_data = $request->all();

            unset($form_data["_token"]);
            unset($form_data["submit_cat"]);
            unset($form_data["submit_cat_continue"]);
            $form_data["book_no"] = $book_no;
            $form_data["active_count"] = $request->quantity;
            $form_data["total_count"] = $request->quantity;
            $form_data["is_recommended"] = $request->is_recommended ? 1 : 0;

            //dd($form_data);

            LibraryModel::create($form_data);

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
                ->route("library.create")
                ->with("success", "Saved Successfully");
        }

        Session::flash("success", "saved successfully");
        return redirect()->route("library.index");
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
        $data = LibraryModel::find($id);
        $categories = BookCategoryModel::where("status", 1)
            ->pluck("cat_name", "id")
            ->toArray();

        return view("library::admin.edit", [
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
        //dd($request->all());
        $this->validate(
            $request,
            [
                "isbn_no" => "required|max:191",
                "title" => "required",
                "publisher_name" => "required",
                "author_name" => "required",
            ],
            [
                "author_name.required" => "Please Provide Author Name",
                "isbn_no.max" => "ISBN Number to long",
            ]
        );

        try {
            $book = LibraryModel::find($id);
            $book->category_id = $request->category_id;
            $book->title = $request->title;
            $book->isbn_no = $request->isbn_no;
            $book->publisher_name = $request->publisher_name;
            $book->author_name = $request->author_name;
            $book->rack_number = $request->rack_number;
            $book->book_description = $request->book_description;
            $book->is_recommended = $request->is_recommended ? 1 : 0;

            $book->save();

            if ($request->quantity > 0) {
                $book->increment("quantity", $request->quantity);
                $book->increment("active_count", $request->quantity);
                $book->increment("total_count", $request->quantity);
            } else {
                $book->increment("quantity", $request->quantity);
            }
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
        return redirect()->route("library.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if (!empty($request->selected_library)) {
            $delObj = new LibraryModel();
            foreach ($request->selected_library as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            // find any issued books there
            $book = LibraryModel::find($id);
            $is_exists = IssuedBookModel::where("book_id", $id)
                ->where("is_return", 0)
                ->count();

            if ($is_exists > 0) {
                return redirect()
                    ->back()

                    ->with(
                        "exception_error",
                        "$is_exists - $book->title Books Are in Issued State || Not Return Still now"
                    );
            }
            $delObj = new LibraryModel();
            $delItem = $delObj->find($id);
            $delItem->delete();
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("library.index");
    }
    /*
     * get data
     */
    public function getData(Request $request)
    {
        CGate::authorize("view-library");
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;

        DB::statement(DB::raw("set @rownum=" . (int) $sTart));

        $data = LibraryModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "id",
            "title",
            "author_name",
            "book_no",
            "quantity",
            "active_count",
            "inactive_count",
            "damaged_count",
            "stolen_count",
            "lost_count",
            "total_count",
            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new LibraryModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new LibraryModel())->getTable() .
                    '.status = "-1" THEN "Trashed"
            ELSE "Enabled" END) AS status'
            )
        )
            ->where("status", "!=", -1)
            ->orderBy("id", "desc");

        $datatables = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn("check", function ($data) {
                if ($data->id != "1") {
                    return $data->rownum;
                } else {
                    return "";
                }
            })

            ->addColumn("available", function ($data) {
                if ($data->quantity != 0) {
                    return "<span class='badge btn-success'>Available - " .
                        $data->total_count .
                        " / " .
                        $data->quantity .
                        "</span>";
                } else {
                    return "<span class='badge btn-danger'>lended - " .
                        $data->total_count .
                        " / " .
                        $data->quantity .
                        "</span>";
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
            })
            ->addColumn("bookstatus", function ($data) {
                return view("library::admin.datatable.action", [
                    "data" => $data,
                    "route" => "library",
                ])->render();
            });

        // return $data;
        if (count((array) $data) == 0) {
            return [];
        }

        return $datatables
            ->rawColumns(["available", "action", "bookstatus"])
            ->make(true);
    }

    /*
     * country bulk action
     * eg : trash,enabled,disabled
     * delete is destroy function
     */
    function statusChange(Request $request)
    {
        CGate::authorize("edit-library");
        if ($request->ajax()) {
            LibraryModel::find($request->id)->update([
                "status" => $request->status,
            ]);
            return response()->json([
                "success" => "Status change successfully.",
            ]);
        }

        if (!empty($request->selected_library)) {
            $obj = new LibraryModel();
            foreach ($request->selected_library as $k => $v) {
                if ($item = $obj->find($v)) {
                    $item->status = $request->action;
                    $item->save();
                }
            }
        }

        Session::flash("success", "Status changed Successfully!!");
        return redirect()->back();
    }
    public function issuedBooks()
    {
        abort(404);
    }

    public function historyBook(Request $request, $id)
    {
        $data = LibraryModel::find($id);

        return view("library::admin.historybook", ["data" => $data]);
    }

    public function getDatahistoryBook(Request $request, $id)
    {
        CGate::authorize("view-library");
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;

        DB::statement(DB::raw("set @rownum=" . (int) $sTart));

        $data = IssuedBookModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "issued_books.id as id",
            "books.title as btitle",
            "library_member.member_username as memusername",
            "issued_date",
            "return_date",
            "issued_by",
            "is_return",
            "returned_date",
            "users.name as uname",
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
            ->join("users", "users.id", "=", "library_member.member_id")
            ->where("issued_books.book_id", $id);

        $datatables = Datatables::of($data)
            ->addIndexColumn()

            ->addColumn("returnDate", function ($data) {
                if ($data->return_date == null) {
                    return "<span class='badge bg-rose'>Not Return</span>";
                } else {
                    return $data->return_date;
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

    public function BookStatusChange(Request $request)
    {
        //dd($request->all());
        $book = LibraryModel::find($request->id);

        $status = $request->status;

        $quantity = $request->qty;

        if ($status == 0) {
            //In Active
            $book->increment("inactive_count", $quantity);
            $book->decrement("active_count", $quantity);
            $book->decrement("quantity", $quantity);
        } elseif ($status == 1) {
            //Active
            if ($request->selectstatus == 0) {
                // inactive
                $book->decrement("inactive_count", $quantity);
                $book->increment("active_count", $quantity);
                $book->increment("quantity", $quantity);
            } elseif ($request->selectstatus == 2) {
                //damage
                $book->decrement("damaged_count", $quantity);
                $book->increment("active_count", $quantity);
                $book->increment("quantity", $quantity);
            } elseif ($request->selectstatus == 3) {
                //stolen
                $book->decrement("stolen_count", $quantity);
                $book->increment("active_count", $quantity);
                $book->increment("quantity", $quantity);
            } elseif ($request->selectstatus == 4) {
                //lost
                $book->decrement("lost_count", $quantity);
                $book->increment("active_count", $quantity);
                $book->increment("quantity", $quantity);
            }
        } elseif ($status == 2) {
            // damage
            $book->increment("damaged_count", $quantity);
            $book->decrement("active_count", $quantity);
            $book->decrement("quantity", $quantity);
        } elseif ($status == 3) {
            // stolen
            $book->increment("stolen_count", $quantity);
            $book->decrement("active_count", $quantity);
            $book->decrement("quantity", $quantity);
        } elseif ($status == 4) {
            // stolen
            $book->increment("lost_count", $quantity);
            $book->decrement("active_count", $quantity);
            $book->decrement("quantity", $quantity);
        }
        return redirect()
            ->route("library.index")
            ->with("success", "Status Changed Successfully");
    }
}
