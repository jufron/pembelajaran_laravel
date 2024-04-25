<?php

namespace Tests\Feature;

use App\View\Components\Layout;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ComponentAllTest extends TestCase
{
    public function testComponentLayout (): void
    {
        // $this->component(Layout::class, [
        //     'title' => 'Login',
        //     'slot'  => 'halaman untuk layoting'
        // ])
        // ->assertSee('Login');

        // $this->component(Layout::class, [
        //     'title' => 'title',
        //     'slot'  => 'halaman layoting'
        // ])
        //     ->assertDontSee('Login')
        //     ->assertSee('title');

        // ? component anonymous

        $this->blade(
            '<x-layout :title="$title" :content="$content"> </x-layout>',
            [
                'title'    => 'Login',
                'content'     => 'halaman untuk login'
            ]
        )
        ->assertDontSeeText('title')
        ->assertSee('Login');

        $this->blade(
            '<x-layout :title="$title" :content="$content"> </x-layout>',
            [
                'title' => 'title',
                'content'  => 'halaman untuk login'
            ]
        )
        ->assertSee('title')
        ->assertDontSee('Login');
    }
}
