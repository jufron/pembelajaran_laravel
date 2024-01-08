<?php

namespace Tests\Feature;

use App\Models\Vocher;
use Database\Seeders\VocherSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UuidTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        DB::table('vochers')->delete();
    }

    public function test_uuid (): void
    {
        $this->seed(VocherSeeder::class);

        $data = Vocher::query()->get();

        $this->assertEquals(3, $data->count());
        $this->assertEquals('example vocher 1', $data->first()->name);
        // $this->assertEquals('1978543289123463', $data->first()->vocher_code);
    }
    public function test_uuid_and_vocher_using_uuid_unique (): void
    {
        $this->seed(VocherSeeder::class);

        $data = Vocher::query()->get();
        $this->assertEquals(3, $data->count());
        $this->assertEquals('example vocher 1', $data->first()->name);
    }
}
