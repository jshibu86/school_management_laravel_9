<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Stancl\Tenancy\Database\Models\Domain;
use App\Models\MultiTenant;
use cms\core\schoolmanagement\Models\SchoolProfile;

class VerifyTenantStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $host = $request->getHost();
        $domain = Domain::where("domain", $host)->first();

        if ($request->route()->getName() == "errorPage") {
            return $next($request);
        }

        if ($domain) {
            $status = SchoolProfile::where("tenant_id", $domain->tenant_id)
                ->pluck("status")
                ->first();

            if ($status != 1) {
                $request->session()->put("inactive_error", true);
                return redirect()
                    ->route("errorPage")
                    ->with(
                        "error",
                        "Site was deactivated by Admin.Contact Admin for more details."
                    );
            } else {
                $request->session()->put("inactive_error", false);
            }
        } else {
            $request->session()->put("connection", "central");
        }

        return $next($request);
    }
}
