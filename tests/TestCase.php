<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Vite;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Mock Vite facade to avoid manifest not found errors
        Vite::shouldReceive('asset')->andReturnUsing(function ($asset) {
            return '/' . ltrim($asset, '/');
        });
        
        Vite::shouldReceive('__invoke')->andReturnUsing(function ($arguments) {
            if (is_array($arguments)) {
                return collect($arguments)->map(fn($asset) => '/' . ltrim($asset, '/'))->implode('');
            }
            return '/' . ltrim($arguments, '/');
        });
    }
}
