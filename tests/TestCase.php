<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected string $url = 'https://en0t0nwzu0td7b.x.pipedream.net';

}
