<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\View;

use Tests\TestCase;

class ViewTest extends TestCase
{
    public function test_view_profile (): void
    {
        $this->get('/profile')
             ->assertStatus(200)
             ->assertSeeText('hallo james');
    }

    public function test_view_without_route ():void
    {
        $this->view('hello', ['nama' => 'sinta'])
             ->assertSeeText('hallo sinta');
    }

    public function test_view_without_route2 (): void
    {
        $view = View::make('hello')->with('nama', 'dodi');
        $this->assertNotEmpty('hello dodi', $view->render());
        $this->assertEquals('dodi', $view->getData()['nama']);
    }
}
