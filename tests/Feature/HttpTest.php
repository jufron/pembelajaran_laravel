<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\RequestException;

use Tests\TestCase;

class HttpTest extends TestCase
{
    public function test_http_get (): void
    {
        // public request bin
        $response = Http::get('https://en0t0nwzu0td7b.x.pipedream.net');

        $this->assertTrue($response->ok());
        $this->assertEquals('200', $response->status());
        $this->assertNotNull($response->headers());
        $this->assertNotNull($response->body());

        $data = json_decode($response->body());
        $this->assertTrue($data->success);
    }

    public function test_http_post (): void
    {
        $response = Http::post('https://en0t0nwzu0td7b.x.pipedream.net', [
            'name'  => 'james',
            'email' => 'james@gmail.com'
        ]);

        $this->assertTrue($response->ok());
        $this->assertEquals('200', $response->status());
        $this->assertNotNull($response->headers());
        $this->assertNotNull($response->body());
    }

    public function test_http_put (): void
    {
        $response = Http::put('https://en0t0nwzu0td7b.x.pipedream.net', [
            'name'  => 'james',
            'email' => 'james@gmail.com'
        ]);

        $this->assertTrue($response->ok());
        $this->assertEquals('200', $response->status());
        $this->assertNotNull($response->headers());
        $this->assertNotNull($response->body());
    }

    public function test_http_delete (): void
    {
        $response = Http::delete('https://en0t0nwzu0td7b.x.pipedream.net', [
            'name'  => 'james',
            'email' => 'james@gmail.com'
        ]);

        $this->assertTrue($response->ok());
        $this->assertEquals('200', $response->status());
        $this->assertNotNull($response->headers());
        $this->assertNotNull($response->body());
    }

    public function test_http_patch (): void
    {
        $response = Http::patch('https://en0t0nwzu0td7b.x.pipedream.net', [
            'name'  => 'james',
            'email' => 'james@gmail.com'
        ]);

        $this->assertTrue($response->ok());
        $this->assertEquals('200', $response->status());
    }

    public function test_http_head (): void
    {
        $response = Http::head('https://en0t0nwzu0td7b.x.pipedream.net');

        $this->assertTrue($response->ok());
        $this->assertEquals('200', $response->status());
    }

    public function test_http_get_query_parameter () : void
    {
        $response = Http::withQueryParameters([
                        'name'  => 'james',
                        'page'  => 1,
                        'limit' => 1
                    ])->get('https://en0t0nwzu0td7b.x.pipedream.net');

        $this->assertTrue($response->ok());
        $this->assertEquals(200, $response->status());

        $response2 = Http::withQueryParameters([
                        'name'  => 'sinta',
                        'page'  => 1,
                        'limit' => 1
                    ])->delete('https://en0t0nwzu0td7b.x.pipedream.net');

        $this->assertTrue($response2->ok());
        $this->assertEquals(200, $response2->status());
    }

    public function test_http_with_header_with_query_parameter () : void
    {
        $response = Http::withQueryParameters([
                        'name'      => 'jaems',
                        'email'     => 'james@gmail.com',
                        'address'   => 'jl tasek'
                    ])
                    ->withHeaders([
                        'accept'        => 'application/json',
                        'x-request-id'  => '123456789'
                    ])
                    ->get('https://en0t0nwzu0td7b.x.pipedream.net');

        $this->assertTrue($response->ok());
        $this->assertEquals(200, $response->status());
        $this->assertNotNull($response->headers());
        $this->assertNotNull($response->body());

        $response2 = Http::withQueryParameters([
                        'name'  => 'sinta',
                        'email' => 'sinta@gmail.com'
                    ])
                    ->withHeaders([
                        'accept'        => 'application/json',
                        'x-request-id'  => '123456789'
                    ])
                    ->patch('https://en0t0nwzu0td7b.x.pipedream.net', [
                        'name'  => 'dodi',
                        'email' => 'dodi@gmail.com'
                    ]);

        $this->assertTrue($response2->ok());
        $this->assertEquals(200, $response2->status());
        $this->assertNotNull($response2->headers());
        $this->assertNotNull($response2->body());
    }

    public function test_http_with_cookies () : void
    {
        $response2 = Http::withQueryParameters([
                        'name'  => 'sinta',
                        'email' => 'sinta@gmail.com'
                    ])
                    ->withHeaders([
                        'accept'        => 'application/json',
                        'x-request-id'  => '123456789'
                    ])
                    ->withCookies([
                        'session_id'    => 123456789,
                        'user_id'       => 987654321
                    ], 'https://en0t0nwzu0td7b.x.pipedream.net')
                    ->patch('https://en0t0nwzu0td7b.x.pipedream.net', [
                        'name'  => 'dodi',
                        'email' => 'dodi@gmail.com'
                    ]);

        $this->assertTrue($response2->ok());
        $this->assertEquals(200, $response2->status());
        $this->assertNotNull($response2->headers());
        $this->assertNotNull($response2->body());
    }

    public function test_http_with_form_oist () : void
    {
        $response2 = Http::asForm()
                    ->withQueryParameters([
                        'name'  => 'sinta',
                        'email' => 'sinta@gmail.com'
                    ])
                    ->withHeaders([
                        'accept'        => 'application/json',
                        'x-request-id'  => '123456789'
                    ])
                    ->withCookies([
                        'session_id'    => 123456789,
                        'user_id'       => 987654321
                    ], 'https://en0t0nwzu0td7b.x.pipedream.net')
                    ->patch('https://en0t0nwzu0td7b.x.pipedream.net', [
                        'name'  => 'dodi',
                        'email' => 'dodi@gmail.com'
                    ]);

        $this->assertTrue($response2->ok());
        $this->assertEquals(200, $response2->status());
        $this->assertNotNull($response2->headers());
        $this->assertNotNull($response2->body());
    }

    public function test_http_multipart_data () : void
    {
        $fileText = file_get_contents(__DIR__ . "/../../public/img/80-803527_html5-css3-and-javascript-logos-html5-logo-png - Copy.png");
        $fileImage = file_get_contents(__DIR__ . "/../../public/robots.txt");
        $file = file_get_contents(__DIR__ . "/../../tests/Feature/HttpTest.php");

        $response = Http::asMultipart()
                        ->attach('dokument', $file, 'httpTest.php')
                        ->attach('dokument', $fileText, 'document.txt')
                        ->attach('profile-image', $fileImage, 'profile.png')
                        ->post('https://en0t0nwzu0td7b.x.pipedream.net', [
                            'name'  => 'jaems',
                            'email' => 'james@gmail.com'
                        ]);

        $this->assertTrue($response->ok());
        $this->assertEquals(200, $response->status());
        $this->assertNotNull($response->headers());
        $this->assertNotNull($response->body());
    }

    public function test_http_json () : void
    {
        $response = Http::asJson()
                        ->post('https://en0t0nwzu0td7b.x.pipedream.net', [
                            'username'  => 'admin',
                            'password'  => '123456789'
                        ]);

        $this->assertTrue($response->ok());
        $this->assertEquals(200, $response->status());
        $this->assertNotNull($response->headers());
        $this->assertNotNull($response->body());

        $respnse2 = Http::acceptJson()
                        ->post('https://en0t0nwzu0td7b.x.pipedream.net', [
                            'username'  => 'admin',
                            'password'  => '123456789'
                        ]);

        $this->assertTrue($respnse2->ok());
        $this->assertEquals(200, $respnse2->status());
        $this->assertNotNull($respnse2->headers());
        $this->assertNotNull($respnse2->body());
    }

    public function test_http_timeout () : void
    {
        $response = Http::asJson()
                        ->timeout(60)
                        ->post('https://en0t0nwzu0td7b.x.pipedream.net', [
                            'username'  => 'admin',
                            'password'  => '123456789'
                        ]);

        $this->assertTrue($response->ok());
        $this->assertEquals(200, $response->status());
        $this->assertNotNull($response->headers());
        $this->assertNotNull($response->body());
    }

    public function test_http_timeout_retry () : void
    {
        $response = Http::asJson()
                        ->timeout(30)
                        ->retry(5, 3000)
                        ->post('https://en0t0nwzu0td7b.x.pipedream.net', [
                            'username'  => 'admin',
                            'password'  => '123456789'
                        ]);

        $this->assertTrue($response->ok());
        $this->assertEquals(200, $response->status());
        $this->assertNotNull($response->headers());
        $this->assertNotNull($response->body());

        $response2 = Http::asJson()
                        ->timeout(30)
                        ->retry(5, function (int $atteempt, $expetion) {
                            return $atteempt * 100;
                        })
                        ->get('https://en0t0nwzu0td7b.x.pipedream.net', [
                            'username'  => 'admin',
                            'password'  => '123456789'
                        ]);

        $this->assertTrue($response2->ok());
        $this->assertEquals(200, $response2->status());
        $this->assertNotNull($response2->headers());
        $this->assertNotNull($response2->body());
    }
}
