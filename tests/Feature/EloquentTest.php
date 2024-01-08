<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Category;
use Database\Seeders\CategorySeeder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EloquentTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        DB::table('categories')->delete();
    }

    protected function insertCategories (): array
    {
        $categories = [];
        for ($i=1; $i <= 5; $i++) {
            $categories[] = [
                'id'            => "00$i",
                'name'          => "ini name $i",
                'description'   => "ini description $i"
            ];
        }
        return $categories;
    }

    public function test_insert (): void
    {
        $category = new Category();

        $category->id           = '001';
        $category->name         = 'smartphone';
        $category->description  = 'ini adalah description smartphone';

        $result = $category->save();

        $this->assertTrue($result);
    }

    public function test_insert_many (): void
    {
        $data = $this->insertCategories();
        Category::query()->insert($data);

        $result = Category::query()->count();
        $this->assertEquals(5, $result);
        $this->assertTrue($result == 5);
    }

    public function test_find (): void
    {
        $this->seed(CategorySeeder::class);

        $category = Category::query()->find('001');
        $this->assertNotNull($category);
        $this->isInstanceOf(Collection::class, $category);
        $this->assertEquals('001', $category->id);
        $this->assertEquals('smartphone', $category->name);
        $this->assertEquals('ini description smartphone', $category->description);
    }

    public function test_find_and_update (): void
    {
        $this->seed(CategorySeeder::class);

        $category = Category::query()->find('001');
        $category->name         = 'smartphone baru';
        $category->description  = 'ini description smartphone baru';
        $response = $category->update();

        $result = Category::query()->find('001');

        $this->assertTrue($response);
        $this->assertNotFalse($response);

        $this->assertNotNull($result);
        $this->isInstanceOf(Collection::class, $result);
        $this->assertEquals('smartphone baru', $result->name);
        $this->assertEquals('ini description smartphone baru', $result->description);
    }

    public function test_select (): void
    {
        $this->seed(CategorySeeder::class);

        $result = Category::query()->whereNull('description')->get();

        $this->assertNotNull($result);
        $this->isInstanceOf(Collection::class, $result);
        $this->assertEquals(2, $result->count());
        $this->assertTrue(null == $result[0]->description);
    }

    public function test_select_and_update (): void
    {
        $this->seed(CategorySeeder::class);

        $result = Category::query()->whereNull('description')->get();

        $this->assertNotNull($result);
        $this->isInstanceOf(Collection::class, $result);
        $this->assertEquals(2, $result->count());
        $this->assertTrue(null == $result[0]->description);

        $result->each(function ($item) {
            $item->description = 'ini adalah description update';
            $item->update();
        });

        $result1 = Category::query()->find('003');

        $this->assertNotNull($result1);
        $this->assertEquals('ini adalah description update', $result1->description);
    }

    public function test_update_many (): void
    {
        $this->seed(CategorySeeder::class);

        $result1 = Category::query()->get()->first();
        $this->assertNotNull($result1->description);

        Category::query()->whereNull('description')->update([
            'description'   => 'ini adalah description di update'
        ]);

        $result2 = Category::query()->where('description', 'ini adalah description di update')->get();
        $this->assertEquals(2, $result2->count());
        $this->assertEquals('ini adalah description di update', $result2->first()->description);
    }

    public function test_delete (): void
    {
        $this->seed(CategorySeeder::class);

        $categry = Category::query()->find('002');
        $result = $categry->delete();

        $this->assertNotFalse($result);
        $this->assertTrue($result);

        $this->assertEquals(3, Category::query()->count());
    }

    public function test_delete_many (): void
    {
        $this->seed(CategorySeeder::class);

        Category::query()->whereNull('description')->delete();

        $result = Category::query()->get();
        // var_dump($result->toJson(JSON_PRETTY_PRINT));
        $this->assertTrue(2 == $result->count());
        $this->assertCount(2, $result);
    }
}
