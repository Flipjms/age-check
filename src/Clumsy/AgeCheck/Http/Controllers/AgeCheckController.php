<?php

namespace Clumsy\AgeCheck\Http\Controllers;

use Clumsy\AgeCheck\Traits\AgeCheckable;
use Illuminate\Routing\Controller;

class AgeCheckController extends Controller
{
    use AgeCheckable;
}
