<?php

namespace Ty666\LaravelVote;

use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Ty666\LaravelVote\Events\Voted;
use Ty666\LaravelVote\Listeners\UpdateDownVotesCount;
use Ty666\LaravelVote\Listeners\UpdateUpVotesCount;

class LaravelVoteServiceProvider extends ServiceProvider
{

    /**
     * Boot the service provider.
     *
     * @return null
     */
    public function boot()
    {
        $root = dirname(__DIR__);
        if (!file_exists(config_path('vote.php'))) {
            $this->publishes([
                $root . '/config/vote.php' => config_path('vote.php'),
            ], 'config');
        }
        if (!class_exists('CreateLaravelFollowTables')) {
            $datePrefix = date('Y_m_d_His');
            $this->publishes([
                $root . '/database/migrations/create_laravel_votes_table.php' => database_path("/migrations/{$datePrefix}_create_laravel_votes_table.php"),
            ], 'migrations');
        }

        Event::listen(Voted::class, UpdateUpVotesCount::class);
        Event::listen(Voted::class, UpdateDownVotesCount::class);

        $this->macroRoute();
    }

    public function macroRoute()
    {
        if (!Route::hasMacro('vote')) {
            Route::macro('vote', function ($targetName, $targetController) {
                $paramName = rtrim($targetName, 's');
                $this->put($targetName . '/{' . $paramName . '}/up_vote', $targetController . '@upVote')->name($targetName . '.upVote');
                $this->put($targetName . '/{' . $paramName . '}/down_vote', $targetController . '@downVote')->name($targetName . '.downVote');
                $this->put($targetName . '/{' . $paramName . '}/cancel_vote', $targetController . '@cancelVote')->name($targetName . '.cancelVote');
            });
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(dirname(__DIR__) . '/config/vote.php', 'vote');

    }

}
