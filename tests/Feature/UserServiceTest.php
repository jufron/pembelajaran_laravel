<?php

namespace Tests\Feature;

use Mockery;
use Tests\TestCase;
use App\Services\Interface\UserService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserServiceTest extends TestCase
{
    protected UserService $userservice;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userservice = app()->make(UserService::class);
    }

    public function test_userservice_called (): void
    {
        $this->assertInstanceOf(UserService::class, $this->userservice);
    }

    public function test_login_success (): void
    {
        $this->assertTrue($this->userservice->login('james', '12345678'));
    }

    public function test_login_fail (): void
    {
        $this->assertFalse($this->userservice->login('sinta', '87654321'));
    }
}
