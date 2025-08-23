<?php

namespace Morcen\Passage;

use Morcen\Passage\Exceptions\MissingPassageService;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

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
