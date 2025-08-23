<?php

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Morcen\Passage\Services\PassageService;

beforeEach(function () {
    $this->service = new PassageService();
});

describe('PassageService', function () {
    it('can set and get method', function () {
        $this->service->setMethod('POST');
        expect($this->service->getMethod())->toBe('POST');
    });

    it('can set and get headers', function () {
        $headers = ['Authorization' => 'Bearer token', 'Content-Type' => 'application/json'];
        $this->service->setHeaders($headers);
        expect($this->service->getHeaders())->toBe($headers);
    });

    it('can set and get params', function () {
        $params = ['key' => 'value', 'foo' => 'bar'];
        $this->service->setParams($params);
        expect($this->service->getParams())->toBe($params);
    });

    it('starts with empty headers array', function () {
        expect($this->service->getHeaders())->toBe([]);
    });

    it('starts with empty params array', function () {
        expect($this->service->getParams())->toBe([]);
    });

    describe('callService', function () {
        it('calls the correct HTTP method with parameters', function () {
            $request = Request::create('/test', 'POST', ['param1' => 'value1', 'param2' => 'value2']);

            $mockPendingRequest = Mockery::mock(PendingRequest::class);
            $mockResponse = Mockery::mock(Response::class);

            $mockPendingRequest->shouldReceive('post')
                ->once()
                ->with('test/uri', ['param1' => 'value1', 'param2' => 'value2'])
                ->andReturn($mockResponse);

            $result = $this->service->callService($request, $mockPendingRequest, 'test/uri');

            expect($result)->toBe($mockResponse);
        });

        it('handles GET requests correctly', function () {
            $request = Request::create('/test?query=param', 'GET');

            $mockPendingRequest = Mockery::mock(PendingRequest::class);
            $mockResponse = Mockery::mock(Response::class);

            $mockPendingRequest->shouldReceive('get')
                ->once()
                ->with('test/uri', ['query' => 'param'])
                ->andReturn($mockResponse);

            $result = $this->service->callService($request, $mockPendingRequest, 'test/uri');

            expect($result)->toBe($mockResponse);
        });

        it('handles PUT requests correctly', function () {
            $request = Request::create('/test', 'PUT', ['data' => 'test']);

            $mockPendingRequest = Mockery::mock(PendingRequest::class);
            $mockResponse = Mockery::mock(Response::class);

            $mockPendingRequest->shouldReceive('put')
                ->once()
                ->with('test/uri', ['data' => 'test'])
                ->andReturn($mockResponse);

            $result = $this->service->callService($request, $mockPendingRequest, 'test/uri');

            expect($result)->toBe($mockResponse);
        });

        it('handles DELETE requests correctly', function () {
            $request = Request::create('/test', 'DELETE');

            $mockPendingRequest = Mockery::mock(PendingRequest::class);
            $mockResponse = Mockery::mock(Response::class);

            $mockPendingRequest->shouldReceive('delete')
                ->once()
                ->with('test/uri', [])
                ->andReturn($mockResponse);

            $result = $this->service->callService($request, $mockPendingRequest, 'test/uri');

            expect($result)->toBe($mockResponse);
        });
    });
});
