<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileStorageTest extends TestCase
{
    public function testStorage (): void
    {
        $fileSystem = Storage::disk('local');

        $fileSystem->put('data.txt', 'ini adalah text yang dibuat');
        $this->assertEquals('ini adalah text yang dibuat', $fileSystem->get('data.txt'));
    }

    public function testUploadFileImage (): void
    {
        // upload file fake = Illuminate\Http\UploadedFile
        $fileImage = UploadedFile::fake()->image('gambar.jpg');

        $this->post('upload/file', [
            'picture'   => $fileImage
        ])
        ->assertStatus(200)
        ->assertSuccessful()
        ->assertOk()
        ->assertSeeText('ok gambar.jpg');
    }

    public function testUploadFileFakePrivate (): void
    {
        $fileDoct = UploadedFile::fake()->create('contoh.pdf', 100, 'application/pdf');
        $fileImage = UploadedFile::fake()->create('contoh.jpg', 100, 'image/jpg');

        $response = $this->post('/upload/file/private', [
           'dokument'   => $fileDoct,
           'image'      => $fileImage
        ])
        ->assertStatus(200)
        ->assertOk()
        ->assertSuccessful()
        ->assertSeeText('dokument/coba-file.pdf image/coba-file.jpg');

        $this->assertNotNull($response->getContent());
        $this->assertNotEmpty($response->getContent());

        var_dump(
            $response->getContent()
        );
    }

    public function testUploadFileFakePublic (): void
    {
        $fileDoct = UploadedFile::fake()->create('contoh.pdf', 100, 'application/pdf');
        $fileImage = UploadedFile::fake()->create('contoh.jpeg', 100, 'image/jpeg');
        $fileVideo = UploadedFile::fake()->create('contoh.mp4', 100, 'video/mp4');

        $response = $this->post('/upload/file/public', [
           'dokument'   => $fileDoct,
           'image'      => $fileImage,
           'video'      => $fileVideo
        ])
        ->assertStatus(200)
        ->assertOk()
        ->assertSuccessful()
        ->assertSeeText('dokument/coba-file.pdf image/coba-file.jpeg video/coba-file.mp4');

        $this->assertNotNull($response->getContent());
        $this->assertNotEmpty($response->getContent());

        var_dump(
            $response->getContent()
        );
    }

    public function testGetFile (): void
    {
        // private
        $response1 = Storage::disk('private')->exists('dokument/coba-file.pdf');
        $response2 = Storage::disk('private')->exists('image/coba-file.jpg');

        // public
        $response3 = Storage::disk('public')->exists('dokument/coba-file.pdf');
        $response4 = Storage::disk('public')->exists('image/coba-file.jpeg');
        $response5 = Storage::disk('public')->exists('video/coba-file.mp4');

        $this->assertTrue($response1);
        $this->assertTrue($response2);
        $this->assertTrue($response3);
        $this->assertTrue($response4);
        $this->assertTrue($response5);
    }
}
