<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Sleep;
use Tests\TestCase;

class RedistTest extends TestCase
{
    public function test_redis_ping () : void
    {
        $response = Redis::command('ping');
        $this->assertEquals('PONG', $response);

        $response = Redis::ping();
        $this->assertEquals('PONG', $response);
    }

    public function test_string () : void
    {
        Redis::setEx('nama', 2, 'jufron');
        $response = Redis::get('nama');

        $this->assertNotNull($response);
        $this->assertEquals('jufron', $response);

        sleep(5);

        $response = Redis::get('nama');
        $this->assertNull($response);
    }

    public function test_list (): void
    {
        Redis::del('names');

        Redis::rpush('names', 'james');
        Redis::rpush('names', 'sinta');
        Redis::rpush('names', 'dodi');
        Redis::rpush('names', 'erik');
        Redis::rpush('names', 'putri');

        $response = Redis::lrange('names', 0, -1);
        $this->assertEquals(['james', 'sinta', 'dodi', 'erik', 'putri'], $response);

        $this->assertEquals('james', Redis::lpop('names'));
        $this->assertEquals('sinta', Redis::lpop('names'));
        $this->assertEquals('dodi', Redis::lpop('names'));
        $this->assertEquals('erik', Redis::lpop('names'));
        $this->assertEquals('putri', Redis::lpop('names'));
    }

    public function testStructureDataSet () : void
    {
        Redis::del('names');

        Redis::sadd('names', 'jufron');
        Redis::SADD('names', 'sinta');
        Redis::sadd('names', 'dodi');
        Redis::sadd('names', 'erik');
        Redis::sadd('names', 'erwin');

        $response = Redis::smembers('names');

        $this->assertIsArray($response);
        $this->assertNotNull($response);
        $this->assertEquals(['jufron', 'sinta', 'dodi', 'erik', 'erwin'], $response);
    }

    public function testSortetSet (): void
    {
        Redis::del('names');

        Redis::zadd('names', 100, 'jufron');
        Redis::zadd('names', 70, 'tamo');
        Redis::zadd('names', 50, 'ama');

        $response = Redis::zrange('names', 0, -1);

        $this->assertEquals(['ama', 'tamo', 'jufron'], $response);
        $this->assertNotNull($response);
        $this->assertIsArray($response);
    }

    public function testDataStructureHash () : void
    {
        Redis::del('user:1');

        Redis::hset('user:1', 'name', 'jufron');
        Redis::hset('user:1', 'email', 'jufrontamoama@gmail.com');
        Redis::hset('user:1', 'age', 24);

        $response = Redis::hgetAll('user:1');

        $this->assertEquals([
            'name'  => 'jufron',
            'email' => 'jufrontamoama@gmail.com',
            'age'  => 24
        ], $response);
        $this->assertArrayHasKey('name', $response);
        $this->assertArrayHasKey('email', $response);
        $this->assertArrayHasKey('age', $response);
    }

    public function testGeopoint (): void
    {
        Redis::del('sellers');

        Redis::geoadd('selers', 0, 0, 'toko A');
        Redis::geoadd('selers', 0, 0, 'toko B');


    }

    public function testHyperLogLog() : void
    {
        Redis::del('visitors');

        Redis::pfadd('visitors', 'jufron', 'tamo', 'ama');
        Redis::pfadd('visitors', 'james', 'dodi', 'sinta');
        Redis::pfadd('visitors', 'erik', 'erwin', 'jufron');

        $response = Redis::pfcount('visitors');

        var_dump($response);

        $this->assertSame(8, $response);
        $this->assertTrue(true);
    }

    public function testRedisPipeline (): void
    {
        Redis::pipeline(function ($pipeline) {
            $pipeline->setex('name', 10, 'jufron');
            $pipeline->setex('email', 20, 'jufrontamoama@gmail.com');
        });

        $response1 = Redis::get('name');
        $this->assertEquals('jufron', $response1);
        $this->assertNotNull($response1);

        $response2 = Redis::get('email');
        $this->assertEquals('jufrontamoama@gmail.com', $response2);
        $this->assertNotNull($response2);
    }

    public function testTransaction () : void
    {
        Redis::transaction(function ($transaction) {
            $transaction->setex('name', 2, 'jufron');
            $transaction->setex('email', 4, 'jufrontamoama@gmail.com');
        });

        $response = Redis::get('name');
        $this->assertEquals('jufron', $response);
        $this->assertNotNull($response);

        $response = Redis::get('email');
        $this->assertEquals('jufrontamoama@gmail.com', $response);
        $this->assertNotNull($response);
    }

    public function testPublish (): void
    {
        for ($i=1; $i <= 5; $i++) {
            Redis::publish('channel-1', "hello world $i");
            Redis::publish('channel-2', "hello world $i");
            Redis::publish('channel-3', "hello world $i");
        }

        $this->assertTrue(true);
    }

    public function testPublishStream (): void
    {
        for ($i=1; $i <= 10; $i++) {
            Redis::xadd('members', '*', [
                'name'      => 'jufron ' . $i,
                'email'     =>  'jufontamoama@mgail.com',
                'country'   => 'indoneisa'
            ]);
        }

        $this->assertTrue(true);
    }

    public function testCreateCostumerGroup (): void
    {
        Redis::xgroup('create', 'members', 'group1', '0');
        Redis::xgroup('createcostumer', 'members', 'group1', 'consumer-1');
        Redis::xgroup('createcostumer', 'members', 'group1', 'consumer-2');

        $this->assertTrue(true);
    }

    public function testConsumerStram (): void
    {
        $this->testPublishStream();
        $this->testCreateCostumerGroup();

        $result = Redis::xreadgroup('group1', 'consumer-1', ['members' => '>'], 5, 3000);

        $this->assertNotNull($result);
        echo json_encode($result, JSON_PRETTY_PRINT);
    }

    public function testCache (): void
    {
        Cache::store('redis')->put('databaseCacheName', 'james', 5);
        Cache::store('redis')->put('databaseCacheEamil', 'james@gmail.com', 5);

        var_dump(Cache::has('databaseCacheName'));
        var_dump(Cache::has('databaseCacheEamil'));

        $response = Cache::get('databaseCacheName');
        $this->assertNotNull($response);
        $this->assertEquals('james', $response);

        $response = Cache::get('databaseCacheEamil');
        $this->assertNotNull($response);
        $this->assertEquals('james@gmail.com', $response);

        sleep(10);

        $response = Cache::get('databaseCacheName');
        $this->assertNull($response);

        $response = Cache::get('databaseCacheEamil');
        $this->assertNull($response);
    }

    public function testRateLimiting (): void
    {
        $success = RateLimiter::attempt('send message', 100, function () {
            echo 'send message';
        });

        $this->assertTrue($success);
    }
}

