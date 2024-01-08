<?php

namespace Tests\Feature;

use App\Models\Tag;
use Tests\TestCase;
use App\Models\Image;
use App\Models\Person;
use App\Models\Review;
use App\Models\Vocher;
use App\Models\Wallet;
use App\Models\Address;
use App\Models\Product;
use App\Models\Category;
use App\Models\Costumer;
use App\Models\Employee;
use App\Models\VirtualAcount;
use Illuminate\Support\Carbon;
use Database\Seeders\TagSeeder;
use Database\Seeders\ImageSeeder;
use Database\Seeders\ReviewSeeder;
use Database\Seeders\VocherSeeder;
use Database\Seeders\WalletSeeder;
use Illuminate\Support\Facades\DB;
use Database\Seeders\CommentSeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\CostumerSeeder;
use Database\Seeders\VirtualAcountSeeder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;

class RelationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Wallet::query()->delete();
        Category::query()->delete();
        Costumer::query()->delete();
        Product::query()->delete();
        Costumer::query()->delete();
        Wallet::query()->delete();
        VirtualAcount::query()->delete();
        Review::query()->delete();
        DB::table('vochers')->delete();
        DB::table('table_costumers_likes_products')->delete();
        DB::table('taggables')->delete();
        DB::table('comments')->delete();
        DB::table('images')->delete();
        Vocher::query()->delete();
        Tag::query()->delete();
        Person::query()->delete();
        Employee::query()->delete();
    }

    public function test_hasOne (): void
    {
        $this->seed([
            CostumerSeeder::class,
            WalletSeeder::class
        ]);

        $costumer = Costumer::query()->find('002');

        $this->assertNotNull($costumer);
        $this->isInstanceOf(Collection::class, $costumer);

        $this->assertEquals(1200000, $costumer->wallet->amount);
        $this->assertEquals('002', $costumer->wallet->costumer_id);

        $wallet = Wallet::query()->where('costumer_id', '003')->get()->first();

        $this->assertNotNull($wallet);
        $this->isInstanceOf(Collection::class, $wallet);

        $this->assertEquals('erik', $wallet->costumer->name);
        $this->assertEquals('erik@gmail.com', $wallet->costumer->email);
    }

    public function test_hasMany (): void
    {
        $this->seed([
            CategorySeeder::class,
            ProductSeeder::class
        ]);

        $product1 = Product::query()->where('id', '002')->get()->first();
        $this->assertNotNull($product1);
        $this->isInstanceOf(Collection::class, $product1);

        $this->assertEquals('smartphone', $product1->category->name);

        $category = Category::query()->where('name', 'smartphone')->get()->first();
        $this->assertNotNull($category);
        $this->isInstanceOf(Collection::class, $category);
        $this->assertTrue(2 == $category->products->count());
    }

    public function test_one_to_one_insert_query (): void
    {
        $costumer = new Costumer();
        $costumer->id = '001';
        $costumer->name = 'james';
        $costumer->email = 'james@gmail.com';
        $costumer->save();

        $wallet = new Wallet();
        $wallet->amount = 500000;

        $costumer->wallet()->save($wallet);

        $result = Wallet::query()->get();

        $this->assertNotNull($result);
    }

    public function test_one_to_many_insert_query (): void
    {
        $categry = new Category();
        $categry->id            = '001';
        $categry->name          = 'fashion';
        $categry->description   = 'ini adalah description fashion';
        $categry->save();

        $product = new Product();
        $product->id            = '010';
        $product->name          = 'lipstik';
        $product->description   = 'ini adalah lipstik';
        $product->price         = 75000;
        $product->stock         = 999;
        // $product->category()->save($product);
        $product->category()->associate($product);

        $result = Product::query()->get();

        $this->assertNotNull($result);
    }

    public function test_cheapestProduct_and_expensiveProduct () : void
    {
        $this->seed(CategorySeeder::class);
        $this->seed(ProductSeeder::class);

        $category = Category::query()->find('001');

        $cheapestProduct = $category->cheapestProduct;
        $this->assertNotNull($cheapestProduct);
        $this->assertEquals('001', $cheapestProduct->id);

        $expend = $category->mostExpensiveProduct;
        $this->assertNotNull($expend);
        $this->assertEquals('002', $expend->id);
    }

    public function test_has_one_thrugh () : void
    {
        $this->seed([
            CostumerSeeder::class,
            WalletSeeder::class,
            VirtualAcountSeeder::class
        ]);

        $costumer = Costumer::query()->find('001');
        $this->assertNotNull($costumer);

        $virtualAcount = $costumer->virtualAcount;

        $this->assertNotNull($virtualAcount);
        $this->assertEquals("BCA", $virtualAcount->bank);
    }

    public function test_many_trough () : void
    {
        $this->seed([
            CategorySeeder::class,
            CostumerSeeder::class,
            ProductSeeder::class,
            ReviewSeeder::class
        ]);

        $category = Category::query()->find('001');
        $this->assertNotNull($category);
        $this->isInstanceOf(Collection::class, $category);

        $review = $category->reviews;
        $this->assertNotNull($review);
        $this->assertCount(2, $review);
        $this->isInstanceOf(Collection::class, $review);
    }

    public function test_insert_using_attach_many_to_many () : void
    {
        $this->seed([
            CategorySeeder::class,
            ProductSeeder::class,
            CostumerSeeder::class
        ]);

        $costumer = Costumer::query()->find('001');
        $product = Product::query()->find('001');

        $costumer->likeProducts()->attach($product);

        $product2 = Product::query()->find('002');
        $product2->likeByeCostumers()->attach($costumer);

        $this->assertNotNull($costumer->likeProducts);
    }

    public function test_pivot_attributes_detech_many_to_many () : void
    {
        $this->test_insert_using_attach_many_to_many();

        $costumer = Costumer::query()->find('001');

        $products = $costumer->likeProducts;

        foreach ($products as $product) {
            $pivot = $product->likes;

            $this->assertNotNull($pivot);
        }
    }

    public function test_polymorphic_one_to_one () : void
    {
        $this->seed([
            CostumerSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            ImageSeeder::class
        ]);

        $costumer = Costumer::query()->find('001');
        $this->assertNotNull($costumer);

        $image = $costumer->image;
        $this->assertNotNull($image);
        $this->assertEquals('https://images.unsplash.com/photo-1517694712202-14dd9538aa97?q=80&w=1470&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', $image->url);
    }

    public function test_polymorphic_one_to_many () : void
    {
        $this->seed([
            CategorySeeder::class,
            ProductSeeder::class,
            VocherSeeder::class,
            CommentSeeder::class
        ]);

        $product = Product::query()->first();
        $comments = $product->comments;

        $this->assertCount(2, $comments);
        foreach ($comments as $comment) {
            $this->assertEquals(Product::class, $comment->commentable_type);
            // $this->assertEquals(Product::class, $comment->commentable_type);
            $this->assertEquals($product->id, $comment->commentable_id);

            $this->assertArrayHasKey('name', $comment->toArray());
            $this->assertArrayHasKey('title', $comment->toArray());
            $this->assertArrayHasKey('comment', $comment->toArray());
            $this->assertArrayHasKey('commentable_type', $comment->toArray());
            $this->assertArrayHasKey('commentable_id', $comment->toArray());
        }
    }

    public function test_polymorphic_one_of_many () : void
    {
        $this->seed([
            CategorySeeder::class,
            ProductSeeder::class,
            VocherSeeder::class,
            CommentSeeder::class
        ]);

        $product = Product::query()->first();
        $latestComment = $product->latestComment;
        $this->assertNotNull($latestComment);

        $oldestComment = $product->olddestComment;
        $this->assertNotNull($oldestComment);
    }

    public function test_polymorphic_many_to_many () : void
    {
        $this->seed([
            CategorySeeder::class,
            ProductSeeder::class,
            VocherSeeder::class,
            TagSeeder::class
        ]);

        $tag1 = Tag::query()->findOrFail('123');
        $tag2 = Tag::query()->findOrFail('113');

        Product::query()->get()->first()->tags()->attach($tag1);
        Vocher::query()->get()->first()->tags()->attach($tag2);

        $result_product = Product::query()->get()->first();
        $tags1 = $result_product->tags;

        $this->assertNotNull($tags1);
        $this->isInstanceOf(Collection::class, $tags1);
        $this->assertCount(1, $tags1);
        $this->assertTrue(1 == $result_product->tags()->count());

        $result_product->tags()->each( function ($item) {
            $this->assertNotNull($item);
            $this->assertNotNull($item->id);
            $this->assertNotNull($item->name);
        });

        $result_vocher = Vocher::query()->get()->first();
        $tags2 = $result_vocher->tags;

        $this->assertNotNull($tags2);
        $this->isInstanceOf(Collection::class, $tags2);
        $this->assertCount(1, $tags2);
        $this->assertTrue(1 == $result_vocher->tags()->count());

        $result_vocher->tags()->each(function ($item) {
            $this->assertNotNull($item);
            $this->assertNotNull($item->id);
            $this->assertNotNull($item->name);
        });
    }

    public function test_eager_loading () : void
    {
        $this->seed([
            CostumerSeeder::class,
            WalletSeeder::class,
            ImageSeeder::class
        ]);

        $costumer = Costumer::query()->findOrFail('001');

        $this->assertNotNull($costumer);
        $this->assertArrayHasKey('id', $costumer);
        $this->assertArrayHasKey('name', $costumer);
        $this->assertArrayHasKey('email', $costumer);
    }

    public function test_querying_relation () : void
    {
        $this->seed([
            CategorySeeder::class, ProductSeeder::class
        ]);

        $category = Category::query()->find('001');
        $product = $category->products()->where('id', '=', '002')->get();
        $this->assertNotNull($product);
        $this->assertNotEmpty($product);
        $this->isInstanceOf(Collection::class, $product);
    }

    public function test_querying_relation_aggregate () : void
    {
        $this->seed([
            CategorySeeder::class, ProductSeeder::class
        ]);

        $category = Category::query()->find('001');
        $product = $category->products()->count();

        $this->assertEquals(2, $product);
    }

    public function test_eloquent_colletion () : void
    {
        $this->seed([
            CategorySeeder::class, ProductSeeder::class
        ]);

        $product = Product::query()->get();

        $this->assertCount(4, $product);

        $product = $product->toQuery()->where('price', '=', '15000')->get();
        $this->assertCount(1, $product);
    }

    public function test_person () : void
    {
        Person::create([
            'first_name'    => 'jufron',
            'last_name'     => 'ama'
        ]);

        $person = Person::query()->where('first_name', '=', 'jufron')->get()->first();
        $this->assertEquals('JUFRON ama', $person->full_name);

        $person2 = new Person();
        $person2->full_name = 'erik ebsan';
        $person2->created_at = now();
        $person2->updated_at = now();
        $person2->save();

        $this->assertEquals('ERIK', $person2->first_name);
        $this->assertEquals('ebsan', $person2->last_name);
    }

    public function test_person2 () : void
    {
        Person::create([
            'first_name'    => 'jufron',
            'last_name'     => 'ama'
        ]);

        $person = Person::query()->where('first_name', '=', 'jufron')->get()->first();
        $this->assertEquals('JUFRON', $person->first_name);

        $person2 = new Person();
        $person2->full_name = 'erik ebsan';
        $person2->created_at = now();
        $person2->updated_at = now();
        $person2->save();

        $this->assertEquals('ERIK', $person2->first_name);
        $this->assertEquals('ebsan', $person2->last_name);
    }

    public function test_attribute_casting () : void
    {
        Person::create([
            'first_name'    => 'james',
            'last_name'     => 'maubila',
            'is_admin'      => true
        ]);

        $person = Person::query()->where('first_name', '=', 'james')->get()->first();

        $this->assertNotNull($person);
        $this->assertNotEmpty($person);
        $this->assertTrue($person->is_admin);
        $this->assertInstanceOf(Carbon::class, $person->created_at);
        $this->assertInstanceOf(Carbon::class, $person->updated_at);
    }

    public function test_costum_casts () : void
    {
        Person::create([
            'first_name'    => 'erik',
            'last_name'     => 'ebsan',
            'is_admin'      => false,
            'address'       => new Address(
                street:     'jalan tidak jelas',
                city:       'kupang',
                country:    'indonesia',
                postalCode: '85117'
            )
        ]);

        $person = Person::query()->where('first_name', '=', 'erik')->get()->first();
        $this->assertNotNull($person);
        $this->assertFalse($person->is_admin);
        $this->assertNotTrue($person->is_admin);
        $this->assertInstanceOf(Address::class, $person->address);

        $this->assertEquals('jalan tidak jelas', $person->address->street);
        $this->assertEquals('kupang', $person->address->city);
        $this->assertEquals('indonesia', $person->address->country);
        $this->assertEquals('85117', $person->address->postalCode);
    }

    public function test_serialization () : void
    {
        $this->seed([
            CategorySeeder::class, ProductSeeder::class
        ]);

        $product = Product::query()->get();
        $this->assertCount('4', $product);

        $json = $product->toJson(JSON_PRETTY_PRINT);

        $this->assertJson($json);
        Log::info($json);

        $array_convert = $product->toArray();

        $this->assertIsArray($array_convert);
    }

    public function test_serialization_relation () : void
    {
        $this->seed([
            CategorySeeder::class, ProductSeeder::class
        ]);

        // $product = Product::query()->with('category')->get();
        $product = Product::query()->select('id', 'name', 'description', 'price', 'stock', 'category_id')
                        ->get()->load(['category' => function ($query) {
                            $query->select('id', 'name', 'createt_at');
                        }])
                        ->each(function ($item) {
                            $item->category->makeHidden(['id']);
                        });

        $json = $product->toJson(JSON_PRETTY_PRINT);

        $this->assertJson($json);
        Log::info($json);

        // $array_convert = $product->toArray();

        // $this->assertIsArray($array_convert);
        // Log::info($array_convert);
    }

    public function test_factory () : void
    {
        $employee1 = Employee::factory()->programmer()->create([
            'id'    => '1',
            'name'  => 'employee 1'
        ]);

        $this->assertNotNull($employee1);

        $employee2 = Employee::factory()->seniorProgrammer()->create([
            'id'    => '2',
            'name'  => 'employee 2'
        ]);

        $this->assertNotNull($employee2);
    }
}
