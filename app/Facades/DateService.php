<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class DateService extends Facade {

    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'dateCheck';
    }
}
