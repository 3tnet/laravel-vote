<?php

namespace Ty666\LaravelVote\Tests;



class CanCountUpVotesTest extends TestCase
{

    public function test_get_up_votes_count()
    {
        $post = Post::find(1);
        $this->assertTrue(is_numeric($post->getUpVotesCount()));
    }

    public function test_increment_up_votes_count()
    {
        $post = Post::find(1);
        $origin = $post->getUpVotesCount();
        $post->incrementUpVotesCount();
        $this->assertEquals($origin + 1, $post->getUpVotesCount());
        $post->incrementUpVotesCount(2);
        $this->assertEquals($origin + 3, $post->getUpVotesCount());
    }

    public function test_decrement_down_votes_count()
    {
        $post = Post::find(1);
        $origin = $post->getUpVotesCount();
        $post->decrementUpVotesCount();
        $this->assertEquals($origin - 1, $post->getUpVotesCount());
        $post->decrementUpVotesCount(2);
        $this->assertEquals($origin - 3, $post->getUpVotesCount());
    }

}
