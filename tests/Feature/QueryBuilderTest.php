<?php

namespace Tests\Feature;

use Illuminate\Support\Collection;
use Illuminate\Database\Query\Builder;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;

use function PHPSTORM_META\map;

class QueryBuilderTest extends TestCase
{
    public function setUp (): void
    {
        parent::setUp();
        DB::table('categories')->delete();
        DB::table('products')->delete();
    }

    public function test_insert (): void
    {
        DB::table('categories')->insert([
            'name'          => 'sinta',
            'description'   => 'ini adalah description',
            'created_at'    => '2023-11-24 15:30:45'
        ]);

        $result = DB::table('categories')->get();
        $this->assertCount(1, $result);
        $this->assertTrue(count($result) > 0);
        $this->isInstanceOf(Collection::class, $result);
    }

    public function test_select (): void
    {
        $this->test_insert();

        $result = DB::table('categories')->select('id', 'name')->get();

        $this->assertNotNull($result);
        $this->assertTrue(!is_null($result));
        $this->isInstanceOf(Collection::class, $result);

        $result->each(function ($item) {
            Log::info(json_encode($item));
        });
    }

    public function insert_data (): void
    {
        DB::table('categories')->insert([
            ['id' => '100', 'name' => 'smartphone', 'description' => 'ini adalah deskripsi smartphone', 'created_at' => '2023-11-24 15:30:45'],
            ['id' => '200', 'name' => 'laptop', 'description' => 'ini adalah deskripsi laptop',  'created_at' => '2023-11-25 15:30:45'],
            ['id' => '300', 'name' => 'elektronik', 'description' => 'ini adalah deskripsi elektronik',  'created_at' => '2023-11-26 15:30:45'],
            ['id' => '400', 'name' => 'perabotan rumah tangga', 'description' => 'ini adalah deskripsi dari perabotan rumah tangga',  'created_at' => '2023-11-27 15:30:45'],
            ['id' => '500', 'name' => 'makanan', 'description' => 'ini adalah deskripsi makanan',  'created_at' => '2023-11-28 15:30:45']
        ]);
    }

    public function queryBuilder ()
    {
        return DB::table('categories')->select('id', 'name', 'description', 'created_at');
    }

    public function test_where (): void
    {
        $this->insert_data();

        $result1 = $this->queryBuilder()->where('name', 'laptop')->get();

        $this->assertNotNull($result1);
        $this->assertCount(1, $result1);
        $this->isInstanceOf(Collection::class, $result1);
    }

    public function test_where_callback (): void
    {
        $this->insert_data();

        $result2 = $this->queryBuilder()->where( function (Builder $query) {
            $query->where('name', '=', 'makanan')
                  ->orWhere('name', '=', 'smartphone');
        })->get();

        $this->assertNotNull($result2);
        $this->assertCount(2, $result2);
        $this->isInstanceOf(Collection::class, $result2);
    }

    public function test_where_beetwaen (): void
    {
        $this->insert_data();

        $result = $this->queryBuilder()->whereBetween('created_at', ['2023-11-24 15:30:45', '2023-11-26 15:30:45'])->get();

        $this->assertNotNull($result);
        $this->assertCount(3, $result);
        $this->isInstanceOf(Collection::class, $result);
    }

    public function test_where_in (): void
    {
        $this->insert_data();

        $result = $this->queryBuilder()->whereIn('name', ['smartphone', 'laptop', 'makanan'])->get();

        $this->assertNotNull($result);
        $this->assertCount(3, $result);
        $this->isInstanceOf(Collection::class, $result);
    }

    public function test_update (): void
    {
        $this->insert_data();

        $this->queryBuilder()
             ->where('name', 'elektronik')
             ->update([
                'name'         => 'laptop workstation',
                'description'  => 'ini description laptop workstation',
                'created_at'   => '2023-11-28 15:30:45'
             ]);

        $result = $this->queryBuilder()->get();

        var_dump($result);

        $this->assertNotNull($result);
        $this->assertCount(5, $result);
        $this->isInstanceOf(Collection::class, $result);

        $result->each(function ($item) {
            Log::info(json_encode($item));
        });
    }

    public function test_create_or_update (): void
    {
        $this->insert_data();

        $this->queryBuilder()->updateOrInsert([
            'name'  => 'fashion'
        ], [
            'name'              => 'fashion',
            'description'       => 'ini adalah fashion',
            'created_at'        => '2023-11-28 15:30:45',
        ]);

        $result = $this->queryBuilder()->get();

        var_dump($result);
        $this->assertNotNull($result);
        $this->assertTrue(6 === count($result));
        $this->assertCount(6, $result);
        $this->isInstanceOf(Collection::class, $result);

    }

    public function test_delete (): void
    {
        $this->insert_data();

        DB::table('categories')->where('name', '=', 'elektronik')->delete();

        $result = $this->queryBuilder()->get();

        var_dump($result);

        $this->assertNotNull($result);
        $this->assertTrue(4 === count($result));
        $this->assertCount(4, $result);
        $this->isInstanceOf(Collection::class, $result);

        $result->each(function ($item) {
            Log::info(json_encode($item));
        });
    }

    public function insert_products (): void
    {
        DB::table('products')->insert([
            ['id' => '100', 'name' => 'samsung galaxy s23 ultra', 'description' => 'ini adalah samsung galaxy s23 ultra', 'price' => 18000000, 'category_id' => '100', 'created_at' => '2023-11-24 15:30:45'],
            ['id' => '200', 'name' => 'apple iphone 14 pro', 'description' => 'ini adalah smartphone apple iphone 14 pro', 'price' => 22000000, 'category_id' => '100', 'created_at' => '2023-11-24 15:30:45'],
            ['id' => '300', 'name' => 'xiaomi 12', 'description' => 'ini adalah smartphone xiaomi 12', 'price' => 9000000, 'category_id' => '100', 'created_at' => '2023-11-24 15:30:45'],
            ['id' => '400', 'name' => 'redmi note 12 pro', 'description' => 'ini adalah smartphone redmi note 12 pro', 'price' => 4100000, 'category_id' => '100', 'created_at' => '2023-11-24 15:30:45'],
            ['id' => '500', 'name' => 'poco f5', 'description' => 'ini adalah smartphone poco f5', 'price' => 5500000, 'category_id' => '100', 'created_at' => '2023-11-24 15:30:45'],
            ['id' => '600', 'name' => 'xiaomi 13T ', 'description' => 'ini adalah smartphone xiaomi 13T', 'price' => 7000000, 'category_id' => '100', 'created_at' => '2023-11-24 15:30:45'],
            ['id' => '700', 'name' => 'TV samsung 45 inch', 'description' => 'ini adalah televisi samsung', 'price' => 7000000, 'category_id' => '300', 'created_at' => '2023-11-24 15:30:45'],
            ['id' => '800', 'name' => 'TV xiaomi 65 inch', 'description' => 'ini adalah televisi xiaomi', 'price' => 9000000, 'category_id' => '300', 'created_at' => '2023-11-24 15:30:45'],
            ['id' => '900', 'name' => 'TV polytron android 45 inch', 'description' => 'ini adalah televisi polytron andorid tv', 'price' => 5000000, 'category_id' => '300', 'created_at' => '2023-11-24 15:30:45'],
            ['id' => '1000', 'name' => 'bakso', 'description' => 'ini adalah makanan bakso', 'price' => 10000, 'category_id' => '500', 'created_at' => '2023-11-24 15:30:45'],
            ['id' => '1100', 'name' => 'mie ayam', 'description' => 'ini adalah makanan mie ayam', 'price' => 15000, 'category_id' => '500', 'created_at' => '2023-11-24 15:30:45'],
            ['id' => '1200', 'name' => 'soto', 'description' => 'ini adalah makanan soto', 'price' => 15000, 'category_id' => '500', 'created_at' => '2023-11-24 15:30:45'],
            ['id' => '1300', 'name' => 'sate', 'description' => 'ini adalah makanan sate', 'price' => 20000, 'category_id' => '500', 'created_at' => '2023-11-24 15:30:45'],
            ['id' => '1400', 'name' => 'nasi goreng', 'description' => 'ini adalah makanan nasi goreng', 'price' => 15000, 'category_id' => '500', 'created_at' => '2023-11-24 15:30:45'],
        ]);
    }

    public function insert_many_data_category (): void
    {
        $data = [];
        for ($i=1; $i <= 50; $i++) {
            $data[] = [
                'id'            => "$i",
                'name'          => "categories name $i",
                'description'   => "ini description ke $i",
                'created_at'    => '2023-11-24 15:30:45'
            ];
        }
        DB::table('categories')->insert($data);
    }

    public function test_join (): void
    {
        $this->insert_data();
        $this->insert_products();

        $result = DB::table('products')
                    ->join('categories', 'products.category_id', '=', 'categories.id')
                    ->select('products.id', 'products.name', 'categories.name as category_name', 'products.price')
                    ->get();

        var_dump($result);
        $this->assertCount(2, $result);
        $this->assertNotNull($result);
        $this->isInstanceOf(Collection::class, $result);

        $result->each(function ($item) {
            Log::info(json_encode($item));
        });
    }

    public function test_join2 (): void
    {
        $this->insert_data();
        $this->insert_products();

        $result = DB::table('categories')
                    ->join('products', 'categories.id', '=', 'products.category_id')
                    ->select('categories.id', 'categories.name', 'products.name', 'products.price')
                    ->get();

        var_dump($result);
        $this->assertNotNull($result);
        $this->isInstanceOf(Collection::class, $result);
    }

    public function test_ordering (): void
    {
        $this->insert_data();
        $this->insert_products();

        $categorys = $this->queryBuilder()
                    ->orderBy('name', 'desc')
                    ->get();

        $products = DB::table('products')
                    ->orderBy('name', 'asc')
                    ->orderBy('price', 'desc')
                    ->get();

        var_dump($products);

        $this->assertNotNull($products);
        $this->assertTrue(2 === count($products));
        $this->assertCount(2, $products);
        $this->isInstanceOf(Collection::class, $products);

        $products2 = DB::table('products')->get();
        var_dump($products2);
    }

    public function test_paging (): void
    {
        $this->insert_data();

        $data = $this->queryBuilder()
                ->skip(3)
                ->take(2)
                ->get();

        $data->each(function ($item) {
            Log::info(json_encode($item));
        });

        var_dump($data);

        $this->assertNotNull($data);
        $this->isInstanceOf(Collection::class, $data);
    }

    public function test_chunk (): void
    {
        $this->insert_many_data_category();

        $result = collect([]);
        $this->queryBuilder()
            ->orderBy('name', 'desc')
            ->chunk(10, function (Collection $res) {
                $res->each(function ($item) {
                    $result[] = $item;
                });
            });

        Log::info(json_encode($result));

        $this->assertNotNull($result);
        $this->isInstanceOf(Collection::class, $result);
    }

    public function test_lazy (): void
    {
        $this->insert_many_data_category();

        $result = $this->queryBuilder()
             ->orderBy('id', 'desc')
             ->lazy(5)
             ->take(5)
             ->each(function ($item) {
                Log::info(json_encode($item));
             });

        $this->assertNotNull($result);
        $this->isInstanceOf(Collection::class, $result);
    }

    public function cursor (): void
    {
        $this->insert_many_data_category();

        $result = $this->queryBuilder()
             ->orderBy('id', 'desc')
             ->cursor();

        $this->assertNotNull($result);
        $this->isInstanceOf(Collection::class, $result);
    }

    public function test_agregate (): void
    {
        $this->insert_data();
        $this->insert_products();

        $result1 = DB::table('products')->count('id');

        $this->assertEquals(6, $result1);

        $result2 = DB::table('products')->min('price');

        $this->assertEquals(4100000, $result2);

        $result3 = DB::table('products')->max('price');

        $this->assertEquals(22000000, $result3);

        $result4 = DB::table('products')->avg('price');

        var_dump($result4);
    }

    public function test_raw (): void
    {
        $this->insert_data();
        $this->insert_products();

        $result = DB::table('products')
                    ->select(
                        DB::raw('count(*) as total_products'),
                        DB::raw('min(price) as min_products'),
                        DB::raw('max(price) as max_product')
                    )
                    ->get();

        var_dump($result);
        $this->assertNotNull($result);
        // $this->assertIsArray($result);
        $this->isInstanceOf(Collection::class, $result);
    }

    public function test_grouping (): void
    {
        $this->insert_data();
        $this->insert_products();

        $result = DB::table('products')
                    ->select('category_id', DB::raw('count(*) as total_product'))
                    ->groupBy('category_id')
                    ->orderBy('category_id', 'desc')
                    ->having(DB::raw('category_id', '>', 5))
                    ->get();

        $this->assertNotNull($result);
        $this->isInstanceOf(Collection::class, $result);
        var_dump($result);
    }

    public function test_locking (): void
    {
        $this->insert_data();
        $this->insert_products();

        $result = DB::table('categories')
                     ->where('id', '100')
                     ->lockForUpdate()
                     ->get();

        var_dump($result);
        $this->assertNotNull($result);
        $this->assertCount(1, $result->all());
        $this->isInstanceOf(Collection::class, $result);
    }

    public function test_paginate (): void
    {
        $this->insert_data();
        $this->insert_products();

        $result = DB::table('products')->orderBy('id', 'desc')->paginate(2);

        $this->assertNotNull($result);
        $this->isInstanceOf(Collection::class, $result);
        $this->assertEquals(1, $result->currentPage());
        $this->assertEquals(2, $result->perPage());
        $this->assertEquals(7, $result->lastPage());
        $this->assertEquals(14, $result->total());

        $collection = $result->items();

        foreach ($collection as $item) {
            Log::info(json_encode($item));
        }
    }

    public function test_loop_paginate (): void
    {
        $this->insert_data();
        $this->insert_products();

        $page = 1;
        while (true) {
            $paginate =DB::table('products')
                        ->orderBy('id', 'desc')
                        ->paginate(perPage: 2, page: $page);
            if ($paginate->isEmpty()) {
                break;
            } else {
                $page++;
                foreach ($paginate->items() as $item) {
                    $this->assertNotNull($item);
                    Log::info(json_encode($item));
                }
            }
        }
    }

    public function test_cursor_paginate (): void
    {
        $this->insert_many_data_category();

        $cursor = 'id';
        while (true) {
          $cursop_paginate = DB::table('categories')
                              ->orderBy('id', 'desc')
                              ->cursorPaginate(perPage: 10, cursor: $cursor);
          foreach ($cursop_paginate->items() as $item) {
            $this->assertNotNull($item);
            Log::info(json_encode($item));
          }

          $cursor = $cursop_paginate->nextCursor();
          if ($cursor == null) {
            break;
          }
        }
    }
}
