<?php

namespace Clumsy\AgeCheck\Http\Middleware;

use AgeCheck;
use Cookie;
use Closure;
use Clumsy\Utils\Facades\HTTP;
use Illuminate\Http\Request;
use Redirect;
use Symfony\Component\HttpFoundation\Response;
use URL;

/**
* 
*/
class ValidateAge
{
    public function handle($request, Closure $next)
    {
        if(!AgeCheck::check() && request()->cookie('agecheck') == null) {
            if (!HTTP::isCrawler()) {
                if (config('clumsy.age-check.success-url') != request()->fullUrl()) {
                    request()->session()->put('clumsy-age-check.before-url', request()->fullUrl());
                }

                return redirect()->to('age-check');
            }
        }

        return $next($request);
    }
}