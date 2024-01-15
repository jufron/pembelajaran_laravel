<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use Database\Seeders\ProductSeeder;
use Illuminate\Support\Facades\Log;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function PHPUnit\Framework\assertNotNull;

class ProductTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (Category::count() == 0) {
            $this->seed(CategorySeeder::class);
        }

        if (Product::count() == 0) {
            $this->seed(ProductSeeder::class);
        }
    }

    public function testProduct () : void
    {
        $product = Product::query()->get()->first();

        $this->get("api/product/$product->id")
            ->assertStatus(200)
            ->assertOk()
            ->assertSuccessful()
            ->assertJson([
                'value' => [
                    'id'        => $product->id,
                    'name'      => $product->name,
                    'category'  => [
                        'id'        => $product->category->id,
                        'name'      => $product->category->name,
                    ],
                    'price'         => $product->price,
                    'created_at'    => $product->created_at->toJSON(),
                    'updated_at'    => $product->updated_at->toJSON()
                ]
            ])
            ->assertJsonStructure([
                'value' => [
                    'id',
                    'name',
                    'category' => [
                        'id',
                        'name',
                    ],
                    'price',
                    'stock',
                    'created_at',
                    'updated_at',
                ]
            ]);
    }

    public function testProducts () : void
    {
        $this->get('api/products')
             ->assertStatus(200)
             ->assertOk()
             ->assertSuccessful()
             ->assertJsonStructure([
                'data'  => [
                    [
                        'id',
                        'name',
                        'category' => [
                            'id',
                            'name',
                        ],
                        'price',
                        'stock',
                        'created_at',
                        'updated_at',
                    ]
                ]
            ]);

        $response = $this->get('api/products')->json('data.*.name');

        Product::query()->pluck('name')->each(function ($prd) use ($response) {
            $this->assertContains($prd, $response);
        });

        Log::info($this->get('api/products')->json());
    }

    public function testProductPaginatation () : void
    {
        $this->get('api/products-paging')
        ->assertStatus(200)
        ->assertOk()
        ->assertSuccessful()
        ->assertJsonStructure([
            'data'  => [
                [
                    "id",
                    'name',
                    'category' => [
                        'id',
                        'name',
                    ],
                    'price',
                    'stock',
                    'created_at',
                    'updated_at',
                ]
            ],
            'links' => [
                'first' ,
                'last' ,
                'prev',
                'next'
            ],
            'meta'  => [

            ]
        ]);

        $response = $this->get('api/products-paging');

        $this->assertNotNull($response->json('data'));
        $this->assertNotNull($response->json('meta'));
        $this->assertNotNull($response->json('links'));
    }

    public function testProductDebugAdditional () : void
    {
        $product = Product::query()->get()->first();

        $this->get("api/product-debug/$product->id")
            ->assertStatus(200)
            ->assertOk()
            ->assertSuccessful()
            ->assertJsonStructure([
                'author',
                'data'  => [
                    'id',
                    'name',
                    'price'
                ]
            ])
            ->assertJson([
                'author' => 'jufron tamo ama',
                'data'   => [
                    'id'    => $product->id,
                    'name'  => $product->name,
                    'price' => $product->price
                ]
            ]);
    }

    public function testProductDebugAdditionalDynemic () : void
    {
        $product = Product::query()->get()->first();

        $response = $this->get("api/product-debug/$product->id")
            ->assertStatus(200)
            ->assertOk()
            ->assertSuccessful()
            ->assertJsonStructure([
                'author',
                'server_time',
                'data'  => [
                    'id',
                    'name',
                    'price'
                ]
            ])
            ->assertJson([
                'author' => 'jufron tamo ama',
                'data'   => [
                    'id'    => $product->id,
                    'name'  => $product->name,
                    'price' => $product->price
                ]
            ]);

        $this->assertNotNull($response->json('server_time'));
    }

    public function testProductAdditionalAttribute () : void
    {
        $product = Product::query()->with('category')->get()->first();

        $response = $this->get('api/product/additional-attribute/1');

        $response->assertStatus(200)
        ->assertOk()
        ->assertSuccessful()
        ->assertJsonStructure([
            'value'  => [
                "id",
                "name",
                "category" => [
                    "id",
                    "name",
                ],
                "price",
                "is_expensive",
                "stock",
                "created_at",
                "updated_at"
            ]
        ])
        ->assertJson([
            'value'  => [
                'id'        => $product->id,
                'name'      => $product->name,
                'category'  => [
                    'id'    => $product->category->id,
                    'name'  => $product->category->name
                ],
                'price'         => $product->price,
                'is_expensive'  => $product->price >= 200000,
                'stock'         => $product->stock,
                'created_at'    => $product->created_at->toJSON(),
                'updated_at'    => $product->updated_at->toJSON()
            ]
        ]);
    }

    public function testProductWithResponse () : void
    {
        $product = Product::query()->with('category')->get()->first();

        $response = $this->get('api/product/additional-attribute/1');

        $response->assertStatus(200)
        ->assertHeader('x-header', 'ini adalah x header')
        ->assertOk()
        ->assertSuccessful()
        ->assertJsonStructure([
            'value'  => [
                "id",
                "name",
                "category" => [
                    "id",
                    "name",
                ],
                "price",
                "is_expensive",
                "stock",
                "created_at",
                "updated_at"
            ]
        ])
        ->assertJson([
            'value'  => [
                'id'        => $product->id,
                'name'      => $product->name,
                'category'  => [
                    'id'    => $product->category->id,
                    'name'  => $product->category->name
                ],
                'price'         => $product->price,
                'is_expensive'  => $product->price >= 200000,
                'stock'         => $product->stock,
                'created_at'    => $product->created_at->toJSON(),
                'updated_at'    => $product->updated_at->toJSON()
            ]
        ]);
    }

    public function testProductWithResponse1 () : void
    {
        $product = Product::query()->with('category')->get()->first();

        $response = $this->get('api/product/with-response/1');

        $response->assertStatus(200)
        ->assertHeader('x-header', 'ini adalah x header')
        ->assertHeader('X-value', 'ini adalah x value')
        ->assertOk()
        ->assertSuccessful()
        ->assertJsonStructure([
            'value'  => [
                "id",
                "name",
                "category" => [
                    "id",
                    "name",
                ],
                "price",
                "is_expensive",
                "stock",
                "created_at",
                "updated_at"
            ]
        ])
        ->assertJson([
            'value'  => [
                'id'        => $product->id,
                'name'      => $product->name,
                'category'  => [
                    'id'    => $product->category->id,
                    'name'  => $product->category->name
                ],
                'price'         => $product->price,
                'is_expensive'  => $product->price >= 200000,
                'stock'         => $product->stock,
                'created_at'    => $product->created_at->toJSON(),
                'updated_at'    => $product->updated_at->toJSON()
            ]
        ]);
    }
}
