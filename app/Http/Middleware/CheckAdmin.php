<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Models\Admin;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Null_;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $id = Session()->get('admin_id');

        if ($id == NULL || $id == 0) {

            Session()->put('message', array(
                'title' => __('You Are Logged Out'),
                'body' => __('Username or Password is invalid'),
                'type' => 'warning'

            ));

            return redirect('/administrator');
        } else {
            $currentAdmin = Admin::find($id);
            $currentAdmin->last_active = date('Y-m-d H:i:s');
            $currentAdmin->save();
        }

        return $next($request);
    }
}
