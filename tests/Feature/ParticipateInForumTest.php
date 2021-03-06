<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function an_authenticated_user_may_participate_in_forum_threads()
    {

        $this->be($user = factory(User::class)->create());

        $thread = factory(Thread::class)->create();

        $reply = factory(Reply::class)->make();

        $this->post($thread->path() . '/replies', $reply->toArray());

        $this->get($thread->path())->assertSee($reply->body);
    }

    /** @test */
    public function unauthenticated_user_may_not_add_reply()
    {
        $this->withoutExceptionHandling()->expectException(AuthenticationException::class);

        $thread = factory(Thread::class)->create();

        $this->post($thread->path() . '/replies', []);
    }

    /** @test */
    public function a_reply_requires_a_body()
    {
        $this->actingAs(factory(User::class)->create());

        $reply = factory(Reply::class)->raw(['body' => null]);

        $thread = factory(Thread::class)->create();

        $this->post($thread->path() . '/replies', $reply)
            ->assertSessionHasErrors('body');


    }
}
