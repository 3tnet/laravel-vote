<?php

namespace Ty666\LaravelVote\Tests;

use Ty666\LaravelVote\Exceptions\TargetException;
use Ty666\LaravelVote\TargetFormatter;

class TargetFormatterTest extends TestCase
{

    public function test_format_throw_exception()
    {
        $this->expectException(TargetException::class);
        TargetFormatter::format([1, 2]);
    }

    public function test_format_by_int_id()
    {
        $targets = TargetFormatter::format([1, 2], Other::class);
        $this->assertArrayHasKey(Other::class, $targets);
        $this->assertArraySubset([1, 2], $targets[Other::class]);
    }

    public function test_format_by_model()
    {
        $user1 = User::find(1);
        $user2 = User::find(2);
        $other1 = Other::find(1);
        $other2 = Other::find(2);
        $targets = TargetFormatter::format([$user1, $user2, $other1, $other2]);
        $this->assertArrayHasKey(User::class, $targets);
        $this->assertArrayHasKey(Other::class, $targets);
        $this->assertEquals([1, 2], $targets[User::class]);
        $this->assertEquals([1, 2], $targets[Other::class]);
    }

    public function test_format_by_int_id_with_upload_data()
    {
        $targets = TargetFormatter::format([1, 2], Other::class, ['type' => 'up_vote']);
        $this->assertArrayHasKey(Other::class, $targets);
        $this->assertEquals([1 => ['type' => 'up_vote'], 2 => ['type' => 'up_vote']], $targets[Other::class]);
    }

    public function test_format_by_model_with_upload_data()
    {
        $user1 = User::find(1);
        $user2 = User::find(2);
        $other1 = Other::find(1);
        $other2 = Other::find(2);
        $targets = TargetFormatter::format([$user1, $user2, $other1, $other2], null, ['type' => 'down_vote']);
        $this->assertArrayHasKey(User::class, $targets);
        $this->assertArrayHasKey(Other::class, $targets);
        $this->assertEquals([1 => ['type' => 'down_vote'], 2 => ['type' => 'down_vote']], $targets[User::class]);
        $this->assertEquals([1 => ['type' => 'down_vote'], 2 => ['type' => 'down_vote']], $targets[Other::class]);
    }
}
