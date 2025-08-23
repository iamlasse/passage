<?php

namespace Morcen\Passage;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Morcen\Passage\Exceptions\MissingPassageService;

class Passage
{
    /**
     * @throws MissingPassageService
     */
    public function getService(string $service): PendingRequest
    {
        if (Http::hasMacro($service)) {
            return Http::$service();
        }

        throw new MissingPassageService("The service \"{$service}\" is not available in your passage services.");
    }
}
