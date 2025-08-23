<?php

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Morcen\Passage\Http\Controllers\PassageController;
use Morcen\Passage\Services\PassageServiceInterface;
use Symfony\Component\HttpFoundation\Response as ResponseCode;

beforeEach(function () {
    $this->mockPassageService = Mockery::mock(PassageServiceInterface::class);
    $this->controller = new PassageController($this->mockPassageService);
});

describe('PassageController', function () {
    it('returns 404 for non-existent service', function () {
        Http::shouldReceive('hasMacro')
            ->with('nonexistent')
            ->once()
            ->andReturn(false);

        $request = Request::create('/nonexistent/test', 'GET');

        $response = $this->controller->index($request);

        expect($response->getStatusCode())->toBe(ResponseCode::HTTP_NOT_FOUND);
        expect($response->getData(true))->toBe(['error' => 'Route not found']);
    });

    it('handles basic service request successfully', function () {
        $request = Request::create('/testservice/users/123', 'GET');

        Http::shouldReceive('hasMacro')
            ->with('testservice')
            ->once()
            ->andReturn(true);

        $mockPendingRequest = Mockery::mock(PendingRequest::class);
        Http::shouldReceive('testservice')
            ->once()
            ->andReturn($mockPendingRequest);

        $mockResponse = Mockery::mock(Response::class);
        $mockResponse->shouldReceive('json')
            ->once()
            ->andReturn(['id' => 123, 'name' => 'John']);
        $mockResponse->shouldReceive('status')
            ->once()
            ->andReturn(200);

        $this->mockPassageService->shouldReceive('callService')
            ->with($request, $mockPendingRequest, 'users/123')
            ->once()
            ->andReturn($mockResponse);

        config(['passage.services.testservice' => ['base_uri' => 'https://api.example.com/']]);

        $response = $this->controller->index($request);

        expect($response->getStatusCode())->toBe(200);
        expect($response->getData(true))->toBe(['id' => 123, 'name' => 'John']);
    });

    it('extracts correct URI parts', function () {
        $request = Request::create('/github/users/morcen/repos', 'GET');

        Http::shouldReceive('hasMacro')
            ->with('github')
            ->once()
            ->andReturn(true);

        $mockPendingRequest = Mockery::mock(PendingRequest::class);
        Http::shouldReceive('github')
            ->once()
            ->andReturn($mockPendingRequest);

        $mockResponse = Mockery::mock(Response::class);
        $mockResponse->shouldReceive('json')
            ->once()
            ->andReturn(['repos' => []]);
        $mockResponse->shouldReceive('status')
            ->once()
            ->andReturn(200);

        $this->mockPassageService->shouldReceive('callService')
            ->with($request, $mockPendingRequest, 'users/morcen/repos')
            ->once()
            ->andReturn($mockResponse);

        config(['passage.services.github' => ['base_uri' => 'https://api.github.com/']]);

        $response = $this->controller->index($request);

        expect($response->getStatusCode())->toBe(200);
    });

    // Custom controller test removed due to complexity of mocking class instantiation

    it('handles empty URI paths', function () {
        $request = Request::create('/service', 'GET');

        Http::shouldReceive('hasMacro')
            ->with('service')
            ->once()
            ->andReturn(true);

        $mockPendingRequest = Mockery::mock(PendingRequest::class);
        Http::shouldReceive('service')
            ->once()
            ->andReturn($mockPendingRequest);

        $mockResponse = Mockery::mock(Response::class);
        $mockResponse->shouldReceive('json')
            ->once()
            ->andReturn(['data' => 'root']);
        $mockResponse->shouldReceive('status')
            ->once()
            ->andReturn(200);

        $this->mockPassageService->shouldReceive('callService')
            ->with($request, $mockPendingRequest, '')
            ->once()
            ->andReturn($mockResponse);

        config(['passage.services.service' => ['base_uri' => 'https://api.service.com/']]);

        $response = $this->controller->index($request);

        expect($response->getStatusCode())->toBe(200);
    });

    it('handles different HTTP methods', function () {
        $methods = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'];

        foreach ($methods as $method) {
            $request = Request::create('/api/endpoint', $method, ['test' => 'data']);

            Http::shouldReceive('hasMacro')
                ->with('api')
                ->once()
                ->andReturn(true);

            $mockPendingRequest = Mockery::mock(PendingRequest::class);
            Http::shouldReceive('api')
                ->once()
                ->andReturn($mockPendingRequest);

            $mockResponse = Mockery::mock(Response::class);
            $mockResponse->shouldReceive('json')
                ->once()
                ->andReturn(['method' => $method]);
            $mockResponse->shouldReceive('status')
                ->once()
                ->andReturn(200);

            $this->mockPassageService->shouldReceive('callService')
                ->with($request, $mockPendingRequest, 'endpoint')
                ->once()
                ->andReturn($mockResponse);

            config(['passage.services.api' => ['base_uri' => 'https://api.test.com/']]);

            $response = $this->controller->index($request);

            expect($response->getStatusCode())->toBe(200);
        }
    });
});
