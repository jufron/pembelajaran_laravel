<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class HttpExpectionTest extends TestCase
{
    public function test_http_throw_exception () : void
    {

        $this->assertThrows(function () {
            $response = Http::get($this->url);
            $response->failed();
            $response->clientError();
        }, HttpResponseException::class);

    }
}
