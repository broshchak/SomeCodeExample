<?php

namespace App\Http\Controllers;

use App\Facades\DateService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() {
        var_dump(DateService::isValid("01/01/2001"));
    }
}
