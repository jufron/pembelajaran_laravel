<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Crypt;

use Tests\TestCase;

class EncryptionTest extends TestCase
{
    public function test_encrypt_and_deskript (): void
    {
        $text = 'ini adalah text yang akan di enkripsi';

        $data_enkripsi = Crypt::encrypt($text);
        $text_deskripsi = Crypt::decrypt($data_enkripsi);

        var_dump($data_enkripsi);
        $this->assertEquals($text, $text_deskripsi);
    }

    public function test_encryptString_and_deskripString (): void
    {
        $text = 'ini adalah text yang akan di enkripsi';

        $data_enkripsi = Crypt::encryptString($text);
        $data_deskripsi = Crypt::decryptString($data_enkripsi);

        var_dump($data_enkripsi);
        $this->assertEquals($text, $data_deskripsi);
    }
}
