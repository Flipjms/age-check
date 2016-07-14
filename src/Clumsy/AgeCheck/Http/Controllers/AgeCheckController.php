<?php

namespace Clumsy\AgeCheck\Http\Controllers;

use AgeCheck;
use Clumsy\Utils\Facades\Geo;
use Illuminate\Routing\Controller;
use Validator;

class AgeCheckController extends Controller
{
    public function validate()
    {
        return view('clumsy-age-check::form', AgeCheck::getFormData());
    }

    public function validateForm()
    {
        $rules = array(
            'country' => 'required',
            'day'     => 'required|integer',
            'month'   => 'required|integer',
            'year'    => 'required|integer',
        );

        $validator = Validator::make(request()->all(), $rules);

        if ($validator->fails()) {
            return redirect()->route('age-check.validate')->withErrors($validator);
        }

        $date = request('day').'-'.request('month').'-'.request('year');
        if (AgeCheck::checkByDate($date, request('country'))) {
            $url = request()->session()->has('clumsy-age-check.before-url') ? 
                    request()->session()->get('clumsy-age-check.before-url') :
                    url(config('clumsy.age-check.success-url'));

            if (request('agree') == 'check') {
                return redirect()->to($url)->withCookie(cookie()->forever('agecheck', true));
            }

            return redirect()->to($url);
        }
            
        return redirect()->to(config('clumsy.age-check.fail-url'));
    }
}
