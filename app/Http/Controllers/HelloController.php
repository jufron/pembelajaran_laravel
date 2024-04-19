<?php

namespace App\Http\Controllers;

use App\Data\Bar;
use App\Data\UserRepository;
use App\Service\Contract\HelloService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Nette\Utils\Json;

class HelloController extends Controller
{

    private HelloService $helloService;
    private Bar $bar;
    private UserRepository $userRepository;

    public function __construct(HelloService $helloServic, Bar $bar, UserRepository $userRepository)
    {
        $this->helloService = $helloServic;
        $this->bar = $bar;
        $this->userRepository = $userRepository;
    }

    public function helloService (string $nama): string
    {
        // hallo selamat pagi jufron
        return $this->helloService->hello($nama);
    }

    public function bar (): string
    {
        // ini foo
        return $this->bar->bar();
    }

    public function get (string $table): string
    {
        // select * from user
        return $this->userRepository->get($table);
    }

    public function log (): string
    {
        // ini adalah log
        return $this->userRepository->log();
    }

    public function hello (): string
    {
        return 'hello from controller';
    }

    public function sayHello (Request $request): string
    {
        return "hello " . $request->input('nama');
    }

    public function requestNested (Request $request): string
    {
        return 'hello ' . $request->input('name.first') . ' ' . $request->input('name.last');
    }

    public function requestMarge (Request $request)
    {
        return $request->merge([
            'admin' => false,
            'data'  => null,
            'agama' => null
        ]);

        $data = $request->input();
        return response()->json($data);
    }
}
