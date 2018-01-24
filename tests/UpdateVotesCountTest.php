<?php


namespace Ty666\LaravelVote\Tests;


class UpdateVotesCountTest extends TestCase
{

    public function test_update_up_votes_count()
    {
        $user = User::find(1);
        $other = Other::find(1);
        $origin = $other->getUpVotesCount();
        $user->upVote($other);
        $this->assertEquals($origin + 1, $other->getUpVotesCount());
        $user->downVote($other);
        $this->assertEquals($origin, $other->getUpVotesCount());
        $user->upVote($other);
        $this->assertEquals($origin + 1, $other->getUpVotesCount());
        $user->cancelVote($other);
        $this->assertEquals($origin, $other->getUpVotesCount());
    }

    public function test_update_down_votes_count()
    {
        $user = User::find(1);
        $other = Other::find(1);
        $origin = $other->getDownVotesCount();
        $user->downVote($other);
        $this->assertEquals($origin + 1, $other->getDownVotesCount());
        $user->upVote($other);
        $this->assertEquals($origin, $other->getDownVotesCount());
        $user->downVote($other);
        $this->assertEquals($origin + 1, $other->getDownVotesCount());
        $user->cancelVote($other);
        $this->assertEquals($origin, $other->getDownVotesCount());
    }
}
