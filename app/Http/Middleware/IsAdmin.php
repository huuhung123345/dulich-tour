<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (!$user || !$user->isAdmin()) {
            abort(403, 'Bạn không có quyền truy cập trang quản trị.');
        }
        return $next($request);
    }
}
