<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MigrationTest extends TestCase
{
    public function test_migration_counter_table_up (): void
    {
        $this->artisan('migrate');

        $this->assertTrue(Schema::hasTable('counter'));
        $this->assertTrue(Schema::hasColumn('counter', 'id'));
        $this->assertTrue(Schema::hasColumn('counter', 'counter'));

        $this->assertFalse(Schema::hasTable('counter'));

        // $this->assertFalse(Schema::hasColumn('counter', ['id', 'counter']));

    }

}
