<?php

namespace Clumsy\Assets\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
* 
*/
class ValidateAge
{
    public function handle($request, Closure $next)
    {
        if(!AgeCheck::check() && Cookie::get('agecheck') == null) {
            if (!HTTP::isCrawler()) {

                if (URL::route('home') != Request::fullUrl()) {
                    Session::put('before-age-check', Request::fullUrl());
                }

                return Redirect::to('age-check');
            }
        }

        return $next($request);
    }
}