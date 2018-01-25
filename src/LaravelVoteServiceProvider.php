<?php

namespace Ty666\LaravelVote;

use Route;
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
            Route::macro('vote', function ($targetName, $targetController, $options = []) {
                $methods = [
                    'up_vote' => 'upVote',
                    'down_vote' => 'downVote',
                    'cancel_vote' => 'cancelVote'
                ];
                if (isset($options['only'])) {
                    $methods = array_only($methods, $options['only']);
                } else if (isset($options['except'])) {
                    $methods = array_except($methods, $options['except']);
                }

                $paramName = str_singular($targetName);
                foreach ($methods as $path => $method) {
                    $this->patch("$targetName/{{$paramName}}/$path", "$targetController@$method")->name("$targetName.$method");
                }
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
