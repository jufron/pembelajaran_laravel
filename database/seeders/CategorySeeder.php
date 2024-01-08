<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $categry = new Category();
        // $categry->id            = '001';
        // $categry->name          = 'smartphone';
        // $categry->description   = 'ini description smartphone';

        // $categry->save();

        $categories = [];
        // for ($i=1; $i <= 5; $i++) {
        //     $categories[] = [
        //         'id'            => "00$i",
        //         'name'          => "ini name $i",
        //         'description'   => "ini description $i"
        //     ];
        // }

        // for ($i=1; $i <= 5; $i++) {
        //     $categories[] = [
        //         'id'            => "00$i",
        //         'name'          => "ini name $i"
        //     ];
        // }

        // Category::insert($categories);

        collect([
            ['id' => '001', 'name' => 'smartphone', 'description' => 'ini description smartphone'],
            ['id' => '002', 'name' => 'elektronik', 'description' => 'ini description elektronik'],
            ['id' => '003', 'name' => 'food'],
            ['id' => '004', 'name' => 'fashion']
        ])->each( function ($item) {
            Category::create($item);
        });
    }
}
