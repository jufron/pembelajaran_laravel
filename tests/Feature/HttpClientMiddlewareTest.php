<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\RequestInterface;
use Illuminate\Http\Client\Request;
use Tests\TestCase;

class HttpClientMiddlewareTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $header = [
            'Content-Type'  => 'application/json',
            'accept'        => 'json'
        ];

        Http::fake([
            'https://facebook.com'    => Http::response(
                body: ['name' => 'jaems'],
                status:200,
                headers: $header
            ),
            'https://github.com'      => Http::response(
                body:[
                    'status'      => 200,
                    'message'     => 'success'
                ],
                status: 200,
                headers: $header
            ),
            'https://microsoft.com'   => Http::response(
                body:[
                    'status'      => 200,
                    'message'     => 'success'
                ],
                status: 200,
                headers: $header
            ),
            'https://google.com'      => Http::response(
                body:[
                    'status'      => 200,
                    'message'     => 'success'
                ],
                status: 200,
                headers: $header
            ),
        ]);
    }

    public function test_http_request_middleware (): void
    {
        $response = Http::withRequestMiddleware( function (RequestInterface $request) {
            return $request->withHeader('X-code', 12345678)
                           ->withHeader('Content-Type', 'application/json');
        })
        ->retry(5, 1000)
        ->timeout(60)
        ->get($this->url);

        $this->assertTrue($response->ok());
        $this->assertEquals('200', $response->status());
        $this->assertEquals('application/json', $response->header('Content-Type'));
        $this->assertNotNull($response->headers());
    }

    public function test_http_with_middleware () : void
    {
        $response = Http::withMiddleware('guest')
                         ->retry(3, 500)
                         ->timeout(30)
                         ->get($this->url);

        $this->assertTrue($response->ok());
        $this->assertEquals('200', $response->status());
        $this->assertEquals('application/sjon', $response->header('Content-Type'));
        $this->assertNotNull($response->headers());
    }

    public function test_http_with_option () : void
    {
        $response = Http::withOptions([
                            'debug' => true
                        ])
                         ->retry(3, 500)
                         ->timeout(30)
                         ->get($this->url);

        $this->assertTrue($response->ok());
        $this->assertEquals('200', $response->status());
        $this->assertEquals('application/json; charset=utf-8', $response->header('Content-Type'));
        $this->assertNotNull($response->headers());
    }

    public function test_http_async () : void
    {
        $promise = Http::async()
                        ->retry(5, 100)
                        ->timeout(30)
                        ->get($this->url);

        var_dump($promise->body());

        $this->assertTrue($promise->ok());
        $this->assertEquals('200', $promise->status());
    }

    public function test_http_macro (): void
    {
        Http::macro('facebook', function () {
            return Http::withHeaders([
                'Content-Type'  => 'application/json',
                'username'      => 'james',
                'authorization' => 1234567890
            ])->baseUrl('https://facebook.com');
        });

        Http::facebook()->get('/');
    }

    public function test_http_fake () : void
    {

        $response = Http::withOptions([
            'debug' => true,
        ])
        ->dump()
        ->withHeaders(['Content-Type' => 'application/json'])
        ->retry(3, 100)
        ->timeout(30)
        ->get('https://facebook.com');

        $this->assertTrue($response->ok());
        $this->assertEquals(200, $response->status());
        $this->assertNotNull($response->body());
        $this->assertNotNull($response->header('Content-Type'));
        $this->assertEquals('application/json', $response->header('Content-Type'));
        $this->assertEquals('json', $response->header('accept'));

        $response2 = Http::withOptions([
            'debuh' => true
        ])
        ->dump()
        ->withHeader('Content-Type', 'application/json')
        ->retry(3, 200)
        ->timeout(30)
        ->get('https://github.com');

        $this->assertTrue($response2->ok());
        $this->assertEquals(200, $response2->status());
        $this->assertNotNull($response2->body());
        $this->assertNotNull($response2->header('Content-Type'));
        $this->assertEquals('application/json', $response2->header('Content-Type'));
        $this->assertEquals('json', $response2->header('accept'));
    }

    public function test_http_fake_test () : void
    {
        Http::fake();

        Http::post('http://instagram.com', [
            'name'  => 'james',
            'email' => 'jamesGmail.com'
        ]);

        Http::assertNotSent(function (Request $request) {
            return $request->url() === 'http://instagram.com';
        });

        $this->assertTrue(true);
        $this->assertNotFalse(true);
    }

    public function test_http_pool () : void
    {
        $responses = Http::pool(fn (Pool $pool) => [
            $pool->get('https://facebook.com'),
            $pool->get('https://facebook.com'),
            $pool->get('https://github.com'),
            $pool->get('https://microsoft.com'),
            $pool->get('https://google.com')
        ]);

        $this->assertTrue($responses[0]->ok());
        $this->assertTrue($responses[1]->ok());
        $this->assertTrue($responses[2]->ok());
        $this->assertTrue($responses[3]->ok());
        $this->assertTrue($responses[4]->ok());

        $headers = [
            'Content-Type'  => 'application/json',
            'accept'        => 'json'
        ];

        $responses1 = Http::pool(fn (Pool $pool) => [
            $pool->as('facebook')->withHeaders($headers)->get('https://facebook.com'),
            $pool->as('github')->withHeaders($headers)->get('https://github.com'),
            $pool->as('microsoft')->withHeaders($headers)->get('https://microsoft.com'),
            $pool->as('google')->withHeaders($headers)->get('https://google.com')
        ]);

        $this->assertTrue($responses1['facebook']->ok());
        $this->assertTrue($responses1['github']->ok());
        $this->assertTrue($responses1['microsoft']->ok());
        $this->assertTrue($responses1['google']->ok());
    }
}
