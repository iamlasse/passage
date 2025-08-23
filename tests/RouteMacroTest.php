<?php

use Illuminate\Support\Facades\Route;

it('has macro set', function () {
    $this->assertTrue(Route::hasMacro('passage'));
});
