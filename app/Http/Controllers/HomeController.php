<?php

namespace App\Http\Controllers;

use App\Events\SomeEvent;
use App\Facades\DateService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() {
        var_dump(DateService::isValid("01/01/2001"));
    }

    public function checkEvent() {
        $some_useful_data = 'some useful data';
        event(new SomeEvent($some_useful_data));
    }
}
