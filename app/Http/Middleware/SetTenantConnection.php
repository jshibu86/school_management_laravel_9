<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Stancl\Tenancy\Database\Models\Domain;
use App\Models\MultiTenant;
use cms\core\schoolmanagement\Models\SchoolProfile;

class SetTenantConnection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $host = $request->getHost();
        $domain = Domain::where("domain", $host)->first();
        if ($domain) {
            $status = SchoolProfile::where("tenant_id", $domain->tenant_id)
                ->pluck("status")
                ->first();
            if ($status == 1) {
                $db_data = MultiTenant::where(
                    "id",
                    $domain->tenant_id
                )->first();
                $request->session()->put("connection", "tenant");
                $tenantConnection = [
                    "driver" => "mysql",
                    "host" => "127.0.0.1",
                    "port" => "3306",
                    "database" => $db_data->tenancy_db_name,
                    "username" => $db_data->tenancy_db_user
                        ? $db_data->tenancy_db_user
                        : (env("APP_ENV") == "local"
                            ? "root"
                            : "wbdev_schoolmanagement"),

                    "password" => $db_data->tenancy_db_password
                        ? $db_data->tenancy_db_password
                        : (env("APP_ENV") == "local"
                            ? ""
                            : 'wMBKG&2@$CrX'),
                    "charset" => "utf8mb4",
                    "collation" => "utf8mb4_unicode_ci",
                    "prefix" => "",
                    "strict" => false,
                    "engine" => null,
                ];

                // Set the tenant connection configuration
                Config::set("database.connections.tenant", $tenantConnection);

                // Purge and set the default connection to tenant
                DB::purge("tenant");
                DB::setDefaultConnection("tenant");

                // Store tenant connection in session
                $request
                    ->session()
                    ->put("tenant_connection", $tenantConnection);
                $request->session()->put("inactive_error", false);
            } else {
                $request->session()->put("inactive_error", true);
            }
        } else {
            $request->session()->put("connection", "central");
        }

        return $next($request);
    }
}
