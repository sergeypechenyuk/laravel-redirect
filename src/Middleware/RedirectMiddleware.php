<?php
namespace PSV\Widgets\Middleware;

use Closure;
use PSV\Widgets\Redirect;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RedirectMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $path = $request->path();
            $redirect = Redirect::whereSource($path)->where("expired_at", ">", Carbon::now())->firstOrFail();
            return redirect(asset($redirect->destination), $redirect->code);
        }
        catch (ModelNotFoundException $exception) {}

        return $next($request);
    }


}
