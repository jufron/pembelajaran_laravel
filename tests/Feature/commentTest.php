<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Product;
use App\Models\Vocher;
use Database\Seeders\VocherSeeder;
use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class commentTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        DB::table('comments')->delete();
        Vocher::query()->delete();
        DB::table('categories')->delete();
    }

    public function test_insert_with_timestamps (): void
    {
        $comment = new Comment();
        $comment->name              = 'james';
        $comment->title             = 'title comment 1';
        $comment->comment           = 'it is comment 1';
        $comment->commentable_type  = Product::class;
        $comment->commentable_id    = '001';
        $comment->created_at        = new DateTime();
        $comment->updated_at        = new DateTime();

        $res = $comment->save();

        $this->assertTrue($res);
        $this->assertNotFalse($res);

        $data = Comment::query()->get();

        $this->assertEquals(1, $data->count());
        $this->assertCount(1, $data);

        $this->assertEquals('james', $data->first()->name);
        $this->assertEquals('title comment 1', $data->first()->title);
        $this->assertEquals('it is comment 1', $data->first()->comment);
    }

    public function test_default_attributes (): void
    {
        $comment = new Comment();
        $comment->name              = 'name is comment';
        $comment->title             = 'title for comment';
        $comment->commentable_type  = Product::class;
        $comment->commentable_id    = '001';
        $comment->created_at        = now();
        $comment->updated_at        = now();

        $res = $comment->save();
        $this->assertTrue($res);
        $this->assertNotFalse($res);

        $data = Comment::query()->get();

        $this->assertEquals(1, $data->count());
        $this->assertCount(1, $data);

        $this->assertEquals('name is comment', $data->first()->name);
        $this->assertEquals('title for comment', $data->first()->title);
        $this->assertEquals('example comment from default attributes', $data->first()->comment);
    }

    public function test_create_using_mass_assignable (): void
    {
        $request = [
            'name'              => 'name from comment',
            'title'             => 'title from comment',
            'comment'           => 'example comment from default attributes',
            'commentable_type'  => Product::class,
            'commentable_id'    => '001'
        ];
        Comment::create($request);

        $result = Comment::query()->get();

        $this->assertCount(1, $result);
        $this->assertEquals(1, $result->count());

        $this->assertEquals('name from comment', $result->first()->name);
        $this->assertEquals('title from comment', $result->first()->title);
        $this->assertEquals('example comment from default attributes', $result->first()->comment);
    }

    public function test_delete_using_soft_delete (): void
    {
        $this->seed(VocherSeeder::class);

        $data = Vocher::query()->where('name', 'example vocher 2');

        $this->isInstanceOf(Collection::class, $data->get());
        $this->assertNotNull($data->get());
        $this->assertCount(1, $data->get());
        $this->assertTrue(1 == $data->count());
        $this->assertEquals('example vocher 2', $data->get()->first()->name);

        $data->delete();

        $result = Vocher::query()->get();

        $this->assertCount(2, $result);
        $this->assertTrue(2 == count($result));
    }

    public function test_global_scope (): void
    {
        collect([
            [
                'id'            => '111',
                'name'          => 'smartphone',
                'description'   => 'ini description smartphone'
            ],
            [
                'id'            => '112',
                'name'          => 'elektronik',
                'description'   => 'ini description elektronik'
            ],
            [
                'id'            => '113',
                'name'          => 'fashion',
                'description'   => 'ini description fashion',
                'is_active'     => true
            ]
        ])->each(function ($item) {
            Category::create($item);
        });

        $result = Category::query()->get();
        $this->assertNotEmpty($result);
        $this->isInstanceOf(Collection::class, $result);
        $this->assertCount(3, $result);
        $this->assertTrue(3 == $result->count());

        $this->assertEquals('smartphone', $result->first()->name);
        $this->assertEquals('ini description smartphone', $result->first()->description);
        $result1 = Category::query()->where('is_active', '=', true)->get();
        // var_dump($result1->toJson(JSON_PRETTY_PRINT));
        $this->assertEquals('1', $result1->count());
    }

    public function test_local_scope (): void
    {
        collect([
            ['name' => 'smartphone'],
            ['name' => 'elektronik'],
            ['name' => 'fashion','is_active' => true],
            ['name' => 'food','is_active' => true]
        ])->each(function ($item) {
            Vocher::create($item);
        });

        $result1 = Vocher::query()->isActive()->get();
        $result2 = Vocher::query()->IsNotActive()->get();

        $this->assertNotEmpty($result1);
        $this->isInstanceOf(Collection::class, $result1);
        $this->assertCount(2, $result1);
        $this->assertTrue(2 == $result1->count());
        $this->assertEquals('fashion', $result1[0]->name);
        $this->assertEquals('food', $result1[1]->name);
        $this->assertEquals('1', $result1[0]->is_active);

        $this->assertNotEmpty($result2);
        $this->isInstanceOf(Collection::class, $result2);
        $this->assertCount(2, $result2);
        $this->assertTrue(2 == $result2->count());
        $this->assertEquals('smartphone', $result2[0]->name);
        $this->assertEquals('elektronik', $result2[1]->name);
        $this->assertEquals('1', $result1[0]->is_active);
    }
}
