<?php

use Illuminate\Support\Facades\Route;
use Morcen\Passage\Services\PassageServiceInterface;

describe('PassageServiceProvider', function () {
    it('registers passage route macro when enabled', function () {
        // The macro is registered during package boot, which happens automatically
        expect(Route::hasMacro('passage'))->toBeTrue();
    });

    it('binds PassageServiceInterface when passage is enabled', function () {
        // Service is bound when passage is enabled (default in test config)
        expect($this->app->bound(PassageServiceInterface::class))->toBeTrue();
    });
});
