<?php

namespace Tests\Feature;

use App\Contracts\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    private UserService $userService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userService = app()->make(UserService::class);
    }

    public function testLoginSuccess (): void
    {
        $statusLoginTrue = $this->userService->login('james', 'rahasia');
        $this->assertTrue($statusLoginTrue);
        $this->assertNotFalse($statusLoginTrue);
    }

    public function testLoginNotFound (): void
    {
        $statusLoginFalse = $this->userService->login('salah', 'salah');
        $this->assertFalse($statusLoginFalse);
        $this->assertNotTrue($statusLoginFalse);
    }

    public function testLoginPasswordWrong (): void
    {
        $statusLoginFalse = $this->userService->login('james', 'salah');
        $this->assertFalse($statusLoginFalse);
        $this->assertNotTrue($statusLoginFalse);
    }
}
