<?php

namespace Clumsy\AgeCheck\Http\Middleware;

use Closure;
use Clumsy\AgeCheck\Facade as AgeCheck;
use Clumsy\Utils\Facades\HTTP;
use Illuminate\Http\Request;

/**
* 
*/
class ValidateAge
{
    public function handle(Request $request, Closure $next)
    {
        if(!AgeCheck::check() && is_null($request->cookie('agecheck'))) {
            if (!HTTP::isCrawler()) {
                if (config('clumsy.age-check.success-url') != $request->fullUrl()) {
                    $request->session()->put('clumsy-age-check.before-url', $request->fullUrl());
                }

                return redirect()->to('age-check');
            }
        }

        return $next($request);
    }
}
