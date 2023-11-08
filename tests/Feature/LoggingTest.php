<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class LoggingTest extends TestCase
{                                                                                                                                                    
    public function test_log (): void
    {
        Log::info('hello ini log info');
        Log::warning('hello ini log warning');
        Log::error('hello ini log error');

        $this->assertTrue(true);
    }
}
