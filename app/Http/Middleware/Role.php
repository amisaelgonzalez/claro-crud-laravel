<?php

namespace App\Http\Middleware;

use Closure;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $allowedRoles = array_slice(func_get_args(), 2);

        foreach($allowedRoles as $role) {
            if(auth()->user()->role === constant("\App\Enum\UserRoleEnum::$role")) {
                return $next($request);
            }
        }

        return redirect()->to('/');
    }
}
