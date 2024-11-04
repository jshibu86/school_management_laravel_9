<?php
namespace cms\core\user\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

//helpers
use DB;
use User;
use Session;
use Cms;
use Roles;
use Carbon\Carbon;
use Plugins;
use Configurations;
use Event;
use Mail;
use CGate;
use cms\core\user\Models\UserModel;

class NotificationController extends Controller
{
    public function readNotifications()
    {
        $user = UserModel::where("id", User::getUser()->id)->first();

        $user->unreadNotifications->markAsRead();

        return redirect()
            ->back()
            ->with("success", "Mark as Read Successfully");
    }
}
