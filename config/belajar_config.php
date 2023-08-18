<?php

return [
  "author" => [
    'first' => env('FIRST_NAME', 'user'),
    'last'  => env('LAST_NAME', '')
  ],
  "email" => env('EMAIL', 'user@gmail.com'),
  "web"   => env('WEB_ADDRESS', 'https://www.examples.com'),
  'lulus' => env('LULUS', false)
];