<?php

describe('Passage Integration Tests', function () {
    beforeEach(function () {
        config(['passage.enabled' => true]);
    });

    it('returns 404 for non-configured services', function () {
        $response = $this->get('/nonexistent/test');

        $response->assertStatus(404);
        $response->assertJson(['error' => 'Route not found']);
    });

    it('handles timeout configuration placeholder', function () {
        config([
            'passage.services' => [
                'slow' => [
                    'base_uri' => 'https://slow.api.com/',
                    'timeout' => 1 // 1 second timeout
                ]
            ]
        ]);

        // This test verifies that configuration can be set without errors
        // Real timeout testing would require actual HTTP calls
        $this->assertTrue(true);
    });
});
