<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_guest_can_not_create_a_thread()
    {
        $this->withoutExceptionHandling()->expectException(AuthenticationException::class);

        $thread = factory(Thread::class)->raw();

        $this->post('/threads', $thread);
    }

    /** @test */
    public function guests_cannot_see_the_create_thread_page()
    {
        $this->withExceptionHandling()
            ->get('/threads/create')
            ->assertRedirect('/login');
    }

    /** @test */
    public function an_authenticated_user_can_create_new_forum_thread()
    {
        $this->actingAs(factory(User::class)->create());

        $thread = factory(Thread::class)->raw();

        $response = $this->post('/threads', $thread);

        $this->followRedirects($response)
            ->assertSee($thread['title'])
            ->assertSee($thread['body']);
    }


    /** @test */
    public function a_thread_requires_a_title()
    {
        $this->actingAs(factory(User::class)->create());

        $thread = factory(Thread::class)->raw(['title' => null]);

        $response = $this->post('/threads', $thread)
            ->assertSessionHasErrors('title');

    }

    /** @test */
    public function a_thread_requires_a_body()
    {
        $this->actingAs(factory(User::class)->create());

        $thread = factory(Thread::class)->raw(['body' => null]);

        $response = $this->post('/threads', $thread)
            ->assertSessionHasErrors('body');

    }

    /** @test */
    public function a_thread_requires_a_valid_channel_id()
    {
        $this->actingAs(factory(User::class)->create());

        $thread = factory(Thread::class)->raw(['channel_id' => null]);

        $response = $this->post('/threads', $thread)
            ->assertSessionHasErrors('channel_id');


        $thread = factory(Thread::class)->raw(['channel_id' => 99]);

        $response = $this->post('/threads', $thread)
            ->assertSessionHasErrors('channel_id');

    }
}
