<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StorageTest extends TestCase
{
    public function test_storage (): void
    {
        $file = Storage::disk('public');
        $file->put('document/coba.txt', 'hallo ini adalah text coba');
        $this->assertEquals('hallo ini adalah text coba', $file->get('document/coba.txt'));
    }

    public function test_upload_file (): void
    {
        $avatarFile = UploadedFile::fake()->image('gambar1.jpg');

        $this->post('avatar', [
            'avatar'    => $avatarFile
        ])
        ->assertStatus(200)
        ->assertSeeText('gambar1.jpg');
    }
}
