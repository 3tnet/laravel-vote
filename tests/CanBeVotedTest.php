<?php

namespace Ty666\LaravelVote\Tests;


class CanBeVotedTest extends TestCase
{
    private $user1;
    private $user2;
    private $other1;
    private $other2;
    private $post1;
    private $post2;

    public function init()
    {
        $this->user1 = User::find(1);
        $this->user2 = User::find(2);
        $this->other1 = Other::find(1);
        $this->other2 = Other::find(2);
        $this->post1 = Post::find(1);
        $this->post2 = Post::find(2);
        $this->user1->upVote([$this->other1, $this->other2, $this->post1]);
        $this->user1->downVote([$this->other2]);
        $this->user2->downVote([$this->other1, $this->post2, $this->other2]);
        $this->user2->upVote([$this->post1, $this->other2]);

    }

    public function test_up_voters()
    {
        $this->init();
        $this->assertCount(1, $this->other1->upVoters);
        $this->assertCount(1, $this->other2->upVoters);
        $this->assertCount(2, $this->post1->upVoters);
        $this->assertCount(0, $this->post2->upVoters);

    }

    public function test_down_voters()
    {
        $this->init();
        $this->assertCount(1, $this->other1->downVoters);
        $this->assertCount(1, $this->other2->downVoters);
        $this->assertCount(0, $this->post1->downVoters);
        $this->assertCount(1, $this->post2->downVoters);
    }

    public function test_is_up_voted_by()
    {
        $this->init();
        $this->assertTrue($this->post1->isUpVotedBy($this->user2));
        $this->assertFalse($this->post2->isUpVotedBy($this->user1));
    }

    public function test_is_down_voted_by()
    {
        $this->init();
        $this->assertTrue($this->other2->isDownVotedBy($this->user1));
        $this->assertFalse($this->other2->isDownVotedBy($this->user2));
    }
}
