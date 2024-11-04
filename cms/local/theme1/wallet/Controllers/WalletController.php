<?php

namespace cms\wallet\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\wallet\Models\WalletModel;

use Yajra\DataTables\Facades\DataTables;
use Session;
use Carbon\Carbon;

use DB;
use CGate;
use cms\core\configurations\Traits\FileUploadTrait;
use cms\students\Models\ParentModel;
use cms\wallet\Models\WalletAttachmentsModel;
use Configurations;
use User;

class WalletController extends Controller
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
            $id = $request->query->get("id", 0);

            $wallet_data = WalletAttachmentsModel::where(
                "wallet_id",
                $id
            )->get();
            $view = view("wallet::admin.payment.verify", [
                "data" => $wallet_data,
            ])->render();

            return response()->json(["viewfile" => $view]);
        }
        return view("wallet::admin.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $parents = ParentModel::where("status", 1)
            ->whereNull("deleted_at")
            ->select([
                "parent.id as id",
                DB::raw(
                    "CONCAT(parent.username, ' - ', parent.father_email) as text"
                ),
            ])
            ->pluck("text", "id")
            ->toArray();

        $types = Configurations::WALLETTYPE;

        //dd($parents);
        return view("wallet::admin.edit", [
            "layout" => "create",
            "parents" => $parents,
            "types" => $types,
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
                "wallet_type" => "required",
            ],
            [
                "wallet_type.required" => "Please Select Wallet Type",
            ]
        );
        DB::beginTransaction();
        try {
            // find parent already added
            $date = Carbon::now()->toDateString();
            $is_already = WalletModel::where(
                "parent_id",
                $request->parent_id
            )->first();

            if ($is_already) {
                $wallet = WalletModel::find($is_already->id);

                $amount = $is_already->wallet_amount + $request->wallet_amount;

                if ($request->wallet_attachment) {
                    $attachment = $this->uploadAttachment(
                        $request->wallet_attachment,
                        null,
                        "school/wallet/"
                    );

                    // add new attachments

                    $obj = new WalletAttachmentsModel();
                    $obj->wallet_id = $wallet->id;
                    $obj->wallet_attachment = $attachment;
                    $obj->save();
                } else {
                    $wallet->update([
                        "deposit_date" => $date,
                        "wallet_amount" => $amount,
                    ]);
                }
            } else {
                $form_data = $request->all();

                unset($form_data["submit_cat_continue"]);
                unset($form_data["submit_cat"]);
                unset($form_data["_token"]);
                unset($form_data["wallet_attachment"]);

                $form_data["deposit_date"] = $date;
                $form_data["user_id"] = User::getUser()->id;
                $form_data["wallet_amount"] = $request->wallet_amount
                    ? $request->wallet_amount
                    : 0;

                $wallet = WalletModel::create($form_data);

                if ($request->wallet_attachment) {
                    $attachment = $this->uploadAttachment(
                        $request->wallet_attachment,
                        null,
                        "school/wallet/"
                    );
                    $obj = new WalletAttachmentsModel();
                    $obj->wallet_id = $wallet->id;
                    $obj->wallet_attachment = $attachment;
                    $obj->save();
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
                ->route("wallet.create")
                ->with("success", "Saved Successfully");
        }

        Session::flash("success", "saved successfully");
        return redirect()->route("wallet.index");
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
        $data = WalletModel::find($id);
        return view("wallet::admin.edit", [
            "layout" => "edit",
            "data" => $data,
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
            "name" =>
                "required|min:3|max:50|unique:" .
                (new WalletModel())->getTable() .
                ",name," .
                $id,
            "desc" => "required|min:3|max:190",
            "status" => "required",
        ]);

        try {
            $obj = WalletModel::find($id);
            $obj->name = $request->name;
            $obj->desc = $request->desc;
            $obj->status = $request->status;
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
        return redirect()->route("wallet.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if (!empty($request->selected_wallet)) {
            $delObj = new WalletModel();
            foreach ($request->selected_wallet as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            $delObj = new WalletModel();
            $delItem = $delObj->find($id);

            $attachments = WalletAttachmentsModel::where(
                "wallet_id",
                $id
            )->get();

            if (!empty($attachments)) {
                foreach ($attachments as $attach) {
                    $this->deleteImage(
                        null,
                        $attach->wallet_attachment
                            ? $attach->wallet_attachment
                            : null
                    );

                    $attach->delete();
                }
            }

            $delItem->delete();
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("wallet.index");
    }
    /*
     * get data
     */
    public function getData(Request $request)
    {
        CGate::authorize("view-wallet");
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;

        DB::statement(DB::raw("set @rownum=" . (int) $sTart));

        $data = WalletModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "wallet.id as id",
            "wallet_type",
            "wallet.wallet_amount",
            "deposit_date",
            "parent.father_name as father_name",

            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new WalletModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new WalletModel())->getTable() .
                    '.status = "-1" THEN "Trashed"
            ELSE "Enabled" END) AS status'
            )
        )
            ->where("wallet.status", "!=", -1)
            ->join("parent", "parent.id", "=", "wallet.parent_id");

        $datatables = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn("verify", function ($data) {
                $wallet = WalletAttachmentsModel::where(
                    "wallet_id",
                    $data->id
                )->count();

                if ($wallet) {
                    return "<a href=" .
                        route("epaymentverify", $data->id) .
                        " class='btn btn-sm btn-danger m-1' id=" .
                        $data->id .
                        "><i class='fa fa-credit-card'></i>
                    </a>";
                } else {
                    return "<span class='text-success'>No E-Payment Available</span>";
                }
            })
            ->addColumn("check", function ($data) {
                if ($data->id != "1") {
                    return $data->rownum;
                } else {
                    return "";
                }
            })
            ->addColumn("parentname", function ($data) {
                if ($data->father_name) {
                    return $data->father_name;
                } else {
                    return "No Name";
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
                    "route" => "wallet",
                ])->render();
            });

        // return $data;
        if (count((array) $data) == 0) {
            return [];
        }

        return $datatables->rawColumns(["verify", "action"])->make(true);
    }

    /*
     * country bulk action
     * eg : trash,enabled,disabled
     * delete is destroy function
     */
    function statusChange(Request $request)
    {
        CGate::authorize("edit-wallet");
        if ($request->ajax()) {
            WalletModel::find($request->id)->update([
                "status" => $request->status,
            ]);
            return response()->json([
                "success" => "Status change successfully.",
            ]);
        }

        if (!empty($request->selected_wallet)) {
            $obj = new WalletModel();
            foreach ($request->selected_wallet as $k => $v) {
                if ($item = $obj->find($v)) {
                    $item->status = $request->action;
                    $item->save();
                }
            }
        }

        Session::flash("success", "Status changed Successfully!!");
        return redirect()->back();
    }

    public function Paymentverify(Request $request)
    {
        //dd($request->all());

        if ($request->verify) {
            foreach ($request->verify as $key => $verify) {
                $amount = $request->amount[$key] ? $request->amount[$key] : 0;

                if ($verify != 0) {
                    WalletAttachmentsModel::where("id", $key)->update([
                        "wallet_verified" => 1,
                        "amount" => $amount,
                    ]);

                    $wallet_attach = WalletAttachmentsModel::where(
                        "id",
                        $key
                    )->first();

                    $wallet = WalletModel::where(
                        "id",
                        $wallet_attach->wallet_id
                    )->first();

                    $amount = $wallet->wallet_amount + $amount;

                    $wallet->update(["wallet_amount" => $amount]);
                }
            }
        }

        return redirect()
            ->route("wallet.index")
            ->with("success", "Verified Successfully");

        dd("done");
    }

    public function epaymentverify(Request $request, $id)
    {
        $wallet_data = WalletModel::with([
            "attachments" => function ($query) {
                $query->orderBy("wallet_verified", "desc");
            },
        ])->find($id);

        $parent = ParentModel::find($wallet_data->parent_id);

        //dd($wallet_data);

        return view("wallet::admin.payment.epaymentverify", [
            "wallet_data" => $wallet_data,
            "parent" => $parent,
        ]);

        dd($wallet_data);
    }
}
