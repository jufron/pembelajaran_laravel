<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Category;
use Illuminate\Support\Facades\Log;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (Category::count() == 0) {
            $this->seed(CategorySeeder::class);
        }
    }

    public function testCategoryResource () : void
    {
        $category = Category::query()->get()->first();

        $this->get("api/categories/$category->id")
            ->assertStatus(200)
            ->assertOk()
            ->assertSuccessful()
            ->assertJson([
            'data'  => [
                'id'            => $category->id,
                'name'          => $category->name,
                'description'   => $category->description,
                'createdAt'     => $category->created_at->toJSON(),
                'updatedAt'     => $category->updated_at->toJSON()
                ]
            ]);
    }

    public function testCategoryResourceCollection () : void
    {
        $category = Category::all();

        Log::info($this->get('api/categories')->json());

        $this->get("api/categories")
            ->assertStatus(200)
            ->assertOk()
            ->assertSuccessful()
            ->assertJson([
            'data'  => [
                    [
                        'id'            => $category[0]->id,
                        'name'          => $category[0]->name,
                        'description'   => $category[0]->description,
                        'createdAt'     => $category[0]->created_at->toJSON(),
                        'updatedAt'     => $category[0]->updated_at->toJSON()
                    ],
                    [
                        'id'            => $category[1]->id,
                        'name'          => $category[1]->name,
                        'description'   => $category[1]->description,
                        'createdAt'     => $category[1]->created_at->toJSON(),
                        'updatedAt'     => $category[1]->updated_at->toJSON()
                    ],
                    [
                        'id'            => $category[2]->id,
                        'name'          => $category[2]->name,
                        'description'   => $category[2]->description,
                        'createdAt'     => $category[2]->created_at->toJSON(),
                        'updatedAt'     => $category[2]->updated_at->toJSON()
                    ]
                ]
            ]);
    }

    public function testCategoryResourceCollectionCostum () : void
    {
        $category = Category::all();

        Log::info($this->get('api/categories-costum')->json());

        $this->get("api/categories-costum")
            ->assertStatus(200)
            ->assertOk()
            ->assertSuccessful()
            ->assertJson([
                'count' => 3,
                'data'  => [
                    [
                        'id'            => $category[0]->id,
                        'name'          => $category[0]->name,
                        // 'description'   => $category[0]->description,
                        // 'createdAt'     => $category[0]->created_at->toJSON(),
                        // 'updatedAt'     => $category[0]->updated_at->toJSON()
                    ],
                    [
                        'id'            => $category[1]->id,
                        'name'          => $category[1]->name,
                        // 'description'   => $category[1]->description,
                        // 'createdAt'     => $category[1]->created_at->toJSON(),
                        // 'updatedAt'     => $category[1]->updated_at->toJSON()
                    ],
                    [
                        'id'            => $category[2]->id,
                        'name'          => $category[2]->name,
                        // 'description'   => $category[2]->description,
                        // 'createdAt'     => $category[2]->created_at->toJSON(),
                        // 'updatedAt'     => $category[2]->updated_at->toJSON()
                    ]
                ]
            ]);
    }


}
