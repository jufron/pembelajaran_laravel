<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class RouteTest extends TestCase
{
    public function testRouteMock (): void
    {
        Route::shouldReceive('get')
            ->with('/students')
            ->andReturn('mock testing halaman students');

        Route::shouldReceive('get')
            ->with('/students/create')
            ->andReturn('mock testing halaman students create');

        Route::shouldReceive('post')
            ->with('/students')
            ->andReturn('mock testing halaman students post store');

        Route::shouldReceive('get')
            ->with('/student/123')
            ->andReturn([
                'id'        => 123,
                'name'      => 'james',
                'email'     => 'james@gmail.com'
            ]);

        Route::shouldReceive('patch')
            ->with('/student/123')
            ->andReturn([
                'id'        => 456,
                'name'      => 'sinta',
                'email'     => 'sinta@gmail.com'
            ]);

        Route::shouldReceive('delete')
            ->with('/student/999')
            ->andReturn([
                'success'   => true
            ]);

        $students_get = Route::get('/students');
        $students_create = Route::get('/students/create');
        $student_store = Route::post('/students');
        $student_get_where_id = Route::get('/student/123');
        $student_update_where_id = Route::patch('/student/123');
        $student_delete_where_id = Route::delete('/student/999');

        $this->assertNotNull($students_get);
        $this->assertNotNull($students_create);
        $this->assertNotNull($student_store);
        $this->assertNotNull($student_get_where_id);
        $this->assertNotNull($student_update_where_id);
        $this->assertNotNull($student_delete_where_id);

        $this->assertEquals('mock testing halaman students', $students_get);
        $this->assertEquals('mock testing halaman students create', $students_create);
        $this->assertEquals('mock testing halaman students post store', $student_store);
        $this->assertEquals([
                'id'        => 123,
                'name'      => 'james',
                'email'     => 'james@gmail.com'
            ], $student_get_where_id);
        $this->assertEquals([
            'id'        => 456,
            'name'      => 'sinta',
            'email'     => 'sinta@gmail.com'
        ], $student_update_where_id);
        $this->assertEquals([
            'success'   => true
        ], $student_delete_where_id);
    }

    public function testRouteStudentsGet (): void
    {
        $this->get('/students')
            ->assertStatus(200)
            ->assertSuccessful()
            ->assertOk()
            ->assertSeeText('students url get');
    }

    public function testRouteStudentsPost (): void
    {
        $this->post('/students')
            ->assertStatus(200)
            ->assertOk()
            ->assertSuccessful()
            ->assertSeeText('students url post');
    }

    public function testRouteNotFound (): void
    {
        $this->get('mahasiswa')
            ->assertStatus(404)
            ->assertNotFound()
            ->assertSeeText('Not Found');
    }
}
