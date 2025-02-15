<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::get('/api', function () {
    return ['Laravel' => app()->version()];
});

