<?php

namespace Tests\Feature;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RowQueryTest extends TestCase
{
    public function setUp (): void
    {
        parent::setUp();
        DB::delete('DELETE FROM categories');
    }

    public function test_crud (): void
    {
        DB::insert("INSERT INTO categories (id, name, description, created_at) VALUES (null, ?, ?, ? )", [
            'james', 'ini adalah description', '2023-11-24 15:30:45'
        ]);

        $result = DB::select("SELECT * FROM categories");

        $this->assertTrue(!is_null($result));
        $this->assertCount(1, $result);
        $this->isInstanceOf(Collection::class, $result);
        $this->assertEquals('james', $result[0]->name);
        $this->assertEquals('ini adalah description', $result[0]->description);
        $this->assertEquals('2023-11-24 15:30:45', $result[0]->created_at);
    }

    public function test_transation_success (): void
    {
        DB::transaction( function () {
            DB::insert("INSERT INTO categories (id, name, description, created_at) VALUES (null, ?, ?, ? )", [
                'james', 'ini adalah description', '2023-11-24 15:30:45'
            ]);
            DB::insert("INSERT INTO categories (id, name, description, created_at) VALUES (null, ?, ?, ? )", [
                'sinta', 'ini adalah description', '2023-11-24 15:30:45'
            ]);
            DB::insert("INSERT INTO categories (id, name, description, created_at) VALUES (null, ?, ?, ? )", [
                'dodi', 'ini adalah description', '2023-11-24 15:30:45'
            ]);
        });

        $result = DB::select("SELECT * FROM categories");
        $this->assertTrue(!is_null($result));
        $this->assertCount(3, $result);
        $this->isInstanceOf(Collection::class, $result);
    }

    public function test_transaction_failed (): void
    {
        try {
            DB::transaction( function () {
                DB::insert("INSERT INTO categories (id, name, description, created_at) VALUES (null, ?, ?, ? )", [
                    'james', 'ini adalah description', '2023-11-24 15:30:45'
                ]);
                DB::insert("INSERT INTO categories (id, name, description, created_at) VALUES (null, ?, ?, ? )", [
                    'sinta', 'ini adalah description', '2023-11-24 15:30:45'
                ]);
                DB::insert("INSERT INTO categorie (id, name, description, created_at) VALUES (null, ?, ?, ? )", [
                    'dodi', 'ini adalah description', '2023-11-24 15:30:45'
                ]);
            });
        } catch (\Exception $e) {

        }

        $result = DB::select("SELECT * FROM categories");

        $this->assertTrue(count($result) === 0);
        $this->assertCount(0, $result);
    }

    public function test_transaction_manual_success (): void
    {
        try {
            DB::beginTransaction();

            DB::insert("INSERT INTO categories (id, name, description, created_at) VALUES (null, ?, ?, ? )", [
                'james', 'ini adalah description', '2023-11-24 15:30:45'
            ]);
            DB::insert("INSERT INTO categories (id, name, description, created_at) VALUES (null, ?, ?, ? )", [
                'sinta', 'ini adalah description', '2023-11-24 15:30:45'
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }

        $result = DB::select('SELECT * FROM categories');

        $this->assertCount(2, $result);
        $this->assertTrue($result >= 0);
        $this->isInstanceOf(Collection::class, $result);
    }

    public function test_transaction_manual_failed (): void
    {
        try {
            DB::beginTransaction();

            DB::insert("INSERT INTO categories (id, name, description, created_at) VALUES (null, ?, ?, ? )", [
                'james', 'ini adalah description', '2023-11-24 15:30:45'
            ]);
            DB::insert("INSERT INTO categorie (id, name, description, created_at) VALUES (null, ?, ?, ? )", [
                'sinta', 'ini adalah description', '2023-11-24 15:30:45'
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }

        $result = DB::select('SELECT * FROM categories');

        $this->assertCount(0, $result);
        $this->assertTrue(count($result) === 0);
    }
}
