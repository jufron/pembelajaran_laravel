<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use App\Services\Interface\UserService;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Testing\Assert;

class UserServiceTest extends TestCase
{
    protected UserService $userservice;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userservice = app()->make(UserService::class);
        User::query()->delete();
    }

    public function test_userservice_called (): void
    {
        $this->assertInstanceOf(UserService::class, $this->userservice);
    }

    public function test_login_success (): void
    {
        $this->seed([UserSeeder::class]);
        $this->assertTrue($this->userservice->login('jufrontamoama@gmail.com', '12345678'));
    }

    public function test_login_fail (): void
    {
        // Assert::assertArraySubset();
        $this->assertFalse($this->userservice->login('jufrontamoama@gmail.com', '12345678'));
    }
}
