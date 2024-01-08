<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Product;
use App\Models\Vocher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product = Product::query()->find('001');
        Comment::create([
            'name'     => 'jufron',
            'title'     => 'title from jufron',
            'comment'   => 'it.s comment from jufron',
            'commentable_type' => Product::class,
            'commentable_id'    => $product->id
        ]);

        Product::query()->find('001')->comments()->create([
            'name'     => 'james',
            'title'     => 'title from james',
            'comment'   => 'it.s comment from james new',
            'commentable_type' => Product::class,
            'commentable_id' => '001'
        ]);

        // Vocher::query()->whereName('example vocher 1')->first()->comments()->create([
        //     'name'     => 'sinta',
        //     'title'     => 'title from sinta',
        //     'comment'   => 'it.s comment fromm sinta'
        // ]);
        // Vocher::query()->whereName('example vocher 2')->first()->comments()->create([
        //     'name'     => 'sinta',
        //     'title'     => 'title from sinta',
        //     'comment'   => 'it.s comment fromm sinta'
        // ]);

    }
}
