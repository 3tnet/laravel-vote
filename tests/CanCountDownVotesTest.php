<?php

namespace Ty666\LaravelVote\Tests;


class CanCountDownVotesTest extends TestCase
{

    public function test_get_down_votes_count()
    {
        $other = Other::find(1);
        $this->assertTrue(is_numeric($other->getDownVotesCount()));
    }

    public function test_increment_down_votes_count()
    {
        $other = Other::find(1);
        $origin = $other->getDownVotesCount();
        $other->incrementDownVotesCount();
        $this->assertEquals($origin + 1, $other->getDownVotesCount());
        $other->incrementDownVotesCount(2);
        $this->assertEquals($origin + 3, $other->getDownVotesCount());
    }

    public function test_decrement_down_votes_count()
    {
        $other = Other::find(1);
        $origin = $other->getDownVotesCount();
        $other->decrementDownVotesCount();
        $this->assertEquals($origin - 1, $other->getDownVotesCount());
        $other->decrementDownVotesCount(2);
        $this->assertEquals($origin - 3, $other->getDownVotesCount());
    }

}
