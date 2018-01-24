<?php

namespace Ty666\LaravelVote\Tests;


use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Log;
use Ty666\LaravelVote\Events\Voted;
use Ty666\LaravelVote\Listeners\UpdateDownVotesCount;
use Ty666\LaravelVote\Listeners\UpdateUpVotesCount;
use Event;

class TestCase extends \Illuminate\Foundation\Testing\TestCase
{

    use DatabaseTransactions;

    protected $config;

    /**
     * Creates the application.
     *
     * Needs to be implemented by subclasses.
     *
     * @return \Symfony\Component\HttpKernel\HttpKernelInterface
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../vendor/laravel/laravel/bootstrap/app.php';
        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite.database', ':memory:');
        return $app;
    }

    /**
     * Setup DB before each test.
     */
    public function setUp()
    {
        parent::setUp();
        if (empty($this->config)) {
            $this->config = require __DIR__ . '/../config/vote.php';
        }
        $this->app['config']->set('vote', $this->config);
        $this->app['config']->set('vote.user', User::class);
        $this->migrate();
        $this->seed();

        Event::listen(Voted::class, UpdateUpVotesCount::class);
        Event::listen(Voted::class, UpdateDownVotesCount::class);
    }

    /**
     * run package database migrations.
     */
    public function migrate()
    {
        $fileSystem = new Filesystem();
        $fileSystem->copy(
            __DIR__ . '/../database/migrations/create_laravel_votes_table.php',
            __DIR__ . '/../tests/database/migrations/create_laravel_vote_table.php'
        );
        foreach ($fileSystem->files(__DIR__ . '/../tests/database/migrations') as $file) {
            $fileSystem->requireOnce($file);
        }
        (new \CreateUsersTable())->up();
        (new \CreateOthersTable())->up();
        (new \CreatePostsTable())->up();
        (new \CreateLaravelVotesTable())->up();
    }

    public function tearDown()
    {
        parent::tearDown();
        unlink(__DIR__ . '/../tests/database/migrations/create_laravel_vote_table.php');
    }


    /**
     * Seed testing database.
     */
    public function seed($classname = null)
    {
        User::create(['name' => 'John']);
        User::create(['name' => 'Allison']);
        User::create(['name' => 'Ron']);
        Other::create(['name' => 'Laravel']);
        Other::create(['name' => 'Golang']);
        Other::create(['name' => 'Python']);
        Post::create(['name' => 'hello']);
        Post::create(['name' => 'world']);
    }


}