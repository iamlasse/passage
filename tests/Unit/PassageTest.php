<?php

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Morcen\Passage\Exceptions\MissingPassageService;
use Morcen\Passage\Passage;

beforeEach(function () {
    $this->passage = new Passage;
});

describe('Passage', function () {
    it('can get a registered service', function () {
        // Mock Http facade to return true for hasMacro
        Http::shouldReceive('hasMacro')
            ->with('testservice')
            ->once()
            ->andReturn(true);

        // Mock the service call
        $mockPendingRequest = Mockery::mock(PendingRequest::class);
        Http::shouldReceive('testservice')
            ->once()
            ->andReturn($mockPendingRequest);

        $result = $this->passage->getService('testservice');

        expect($result)->toBe($mockPendingRequest);
    });

    it('throws exception for non-existent service', function () {
        // Mock Http facade to return false for hasMacro
        Http::shouldReceive('hasMacro')
            ->with('nonexistentservice')
            ->once()
            ->andReturn(false);

        expect(fn () => $this->passage->getService('nonexistentservice'))
            ->toThrow(MissingPassageService::class, 'The service "nonexistentservice" is not available in your passage services.');
    });

    it('handles service names with special characters', function () {
        Http::shouldReceive('hasMacro')
            ->with('service-with-dashes')
            ->once()
            ->andReturn(true);

        $mockPendingRequest = Mockery::mock(PendingRequest::class);
        Http::shouldReceive('service-with-dashes')
            ->once()
            ->andReturn($mockPendingRequest);

        $result = $this->passage->getService('service-with-dashes');

        expect($result)->toBe($mockPendingRequest);
    });
});
