<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCaseApi extends BaseTestCase
{
    protected string $apiVersion;

    protected function setUp(): void
    {
        parent::setUp();
        $this->apiVersion = config('api.default_version');
    }

    /**
     * Assert a successful JSON response structure.
     */
    protected function assertSuccessResponse($response, array $dataKeys = [], $expectedValues = [])
    {
        $response->assertStatus(200)
            ->assertJsonStructure(array_merge(['success', 'message', 'data' => $dataKeys]));

        if (! empty($expectedValues)) {
            $response->assertJson(['data' => $expectedValues]);
        }
    }

    /**
     * Assert an error JSON response structure.
     */
    protected function assertErrorResponse($response, int $status = 400, array $errorKeys = [])
    {
        $response->assertStatus($status)
            ->assertJsonStructure(['success', 'message', 'errors' => $errorKeys]);
    }
}
