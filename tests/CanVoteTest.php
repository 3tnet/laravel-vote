<?php

namespace Ty666\LaravelVote\Tests;

use Illuminate\Database\Eloquent\Model;
use Ty666\LaravelVote\Events\Voted;

class CanVotedest extends TestCase
{
    private function assertVotedEvent(Voted $votedEvent, Model $targetObj, $user, array $change)
    {
        $this->assertEquals($targetObj->getKey(), $votedEvent->getTargetId());
        $this->assertEquals($targetObj->getKey(), $votedEvent->getTargetModel()->getKey());
        $this->assertEquals(get_class($targetObj), $votedEvent->getClassName());
        $this->assertSame($user, $votedEvent->getUser());
        $this->assertEquals($change, $votedEvent->getChange());
    }

    public function test_user_up_vote_by_id()
    {
        $user = User::find(1);
        $this->expectsEvents(Voted::class);
        $user->upVote([1, 2], Other::class);
        $i = 1;
        foreach ($this->firedEvents as $event) {
            $this->assertVotedEvent($event, Other::find($i), $user, [
                'up_vote' => 1,
                'down_vote' => 0,
            ]);
            $i++;
        }
    }

    public function test_user_up_vote_by_model()
    {
        $user = User::find(1);
        $other1 = Other::find(1);
        $other2 = Other::find(2);
        $post1 = Post::find(1);
        $post2 = Post::find(2);
        $this->expectsEvents(Voted::class);
        $toUpVotes = [$other1, $other2, $post1, $post2];
        $user->upVote($toUpVotes);
        foreach ($this->firedEvents as $i => $event) {
            $this->assertVotedEvent($event, $toUpVotes[$i], $user, [
                'up_vote' => 1,
                'down_vote' => 0,
            ]);
        }
    }


    public function test_user_up_vote_after_down_vote()
    {
        $user = User::find(1);
        $other = Other::find(1);
        $this->expectsEvents(Voted::class);
        $user->downVote($other);
        $user->upVote($other);
        $this->assertVotedEvent($this->firedEvents[1], $other, $user, [
            'up_vote' => 1,
            'down_vote' => -1
        ]);
    }

    public function test_user_down_vote_after_up_vote()
    {
        $user = User::find(1);
        $other = Other::find(1);
        $this->expectsEvents(Voted::class);
        $user->upVote($other);
        $user->downVote($other);

        $this->assertVotedEvent($this->firedEvents[1], $other, $user, [
            'up_vote' => -1,
            'down_vote' => 1
        ]);
    }

    public function test_cancel_vote_with_no_voted()
    {
        $user = User::find(1);
        $other = Other::find(1);
        $this->doesntExpectEvents(Voted::class);
        $user->cancelVote($other);
    }

    public function test_cancel_vote_after_up_vote()
    {
        $user = User::find(1);
        $other = Other::find(1);
        $this->expectsEvents(Voted::class);
        $user->upVote($other);
        $user->cancelVote($other);
        $this->assertVotedEvent($this->firedEvents[1], $other, $user, [
            'up_vote' => -1,
            'down_vote' => 0
        ]);
    }

    public function test_cancel_vote_after_down_vote()
    {
        $user = User::find(1);
        $other = Other::find(1);
        $this->expectsEvents(Voted::class);
        $user->downVote($other);
        $user->cancelVote($other);
        $this->assertVotedEvent($this->firedEvents[1], $other, $user, [
            'up_vote' => 0,
            'down_vote' => -1
        ]);
    }

    public function test_cancel_vote_by_id()
    {
        $user = User::find(1);
        $other = Other::find(1);
        $this->expectsEvents(Voted::class);
        $user->upVote($other);
        $user->cancelVote($other->id, Other::class);
        $this->assertVotedEvent($this->firedEvents[1], $other, $user, [
            'up_vote' => -1,
            'down_vote' => 0
        ]);
    }
}
