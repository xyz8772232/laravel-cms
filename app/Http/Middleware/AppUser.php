<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Input;
use Symfony\Component\HttpFoundation\Cookie;

class AppUser
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
        $config = config('appuser');
        $response =  $next($request);

        if (Input::get('from', '') == 'app') {
            $user_id = Input::get('uid', 0);
            $username = Input::get('username', '');

            if (!empty($user_id) && !empty($username)) {
                $response->headers->setCookie(
                    new Cookie('uid', $user_id, Carbon::now()->getTimestamp() + 60 *$config['lifetime'],
                    $config['path'], $config['domain'], $config['secure'], false)
                );
                $response->headers->setCookie(
                    new Cookie('username', $username, Carbon::now()->getTimestamp() + 60 *$config['lifetime'],
                        $config['path'], $config['domain'], $config['secure'], false)
                );
            }
        }

        return $response;
    }

}
