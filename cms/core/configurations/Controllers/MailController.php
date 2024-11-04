<?php
namespace cms\core\configurations\Controllers;

use Mail;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\core\configurations\helpers\Configurations;
use cms\core\schoolmanagement\Mail\WelcomeMessageEmail;
use Illuminate\Support\Facades\Log;
use cms\core\Demo\Mail\DemoScheduleEmail;

class MailController extends Controller
{
    protected $details;

    public function setDetails($key, $value)
    {
        $this->details[$key] = $value;
    }
    public function getDetails($key)
    {
        return isset($this->details[$key]) ? $this->details[$key] : null;
    }
    public function sendEmailValidate()
    {
    }
    public function sendWelcomeMessageEmail(Request $request)
    {
        //Add constants to setdetails array
        $this->setDetails("recipient", $request->school_email);
        $this->setDetails("recipient_name", $request->school_name);
        $this->setDetails("logo", Configurations::LOGO_PATH);
        $this->setDetails("message", Configurations::WELCOME_EMAIL);
        $this->setDetails("title", Configurations::WELCOME_EMAIL_TITLE);
        $this->setDetails("admin", Configurations::ADMIN_NAME);
        $details = $this->details;

        //email
        if (config("app.env") == "local") {
            \CmsMail::setMailTrapConfig();
        } else {
            \CmsMail::setMailConfig();
        }
        Mail::to($request->school_email)->send(
            new WelcomeMessageEmail($this->details)
        );
    }
    public function sendPasswordResetEmail()
    {
    }
    public function sendOnboardNotificationEmail($schoolName, $schoolEmail)
    {
        //Add constants to setdetails array
        $this->setDetails("recipient", $schoolEmail);
        $this->setDetails("recipient_name", $schoolName);
        $this->setDetails("logo", Configurations::LOGO_PATH);
        $this->setDetails("message", Configurations::WELCOME_ONBOARD);
        $this->setDetails("title", Configurations::WELCOME_EMAIL_ONBOARD);
        $this->setDetails("admin", Configurations::ADMIN_NAME);
        $details = $this->details;

        //send email
        if (config("app.env") == "local") {
            \CmsMail::setMailTrapConfig();
        } else {
            \CmsMail::setMailConfig();
        }
        Mail::to($schoolEmail)->send(new WelcomeMessageEmail($this->details));
    }
    public function sendExamURLEmail($user, $examLink)
    {
        Mail::to($receipentEmail)->send(new ExamLinkEmail($user, $resetLink));
    }
    public function sendNotificationEmail()
    {
    }

    public function sendDemoScheduleEmail(
        $demo_date,
        $demo_time,
        $sendEmail,
        $demo_text,
        $contact_name
    ) {
        //Add constants to setdetails array
        $this->setDetails("recipient", $sendEmail);
        $this->setDetails("recipient_name", $contact_name);
        $this->setDetails("logo", Configurations::LOGO_PATH);
        $this->setDetails("message", $demo_text);
        $this->setDetails("demo_date", $demo_date);
        $this->setDetails("demo_time", $demo_time);
        $this->setDetails(
            "title",
            "Demo Schedule - Online School Management System"
        );
        $this->setDetails("admin", Configurations::ADMIN_NAME);
        $details = $this->details;

        \Log::debug("sendDemoScheduleEmail", [
            "sendDemoScheduleEmail" => $sendEmail,
        ]);
        if (config("app.env") == "local") {
            \CmsMail::setMailTrapConfig();
        } else {
            \CmsMail::setMailConfig();
        }
        //send email
        Mail::to($sendEmail)->send(new DemoScheduleEmail($this->details));
    }
}
