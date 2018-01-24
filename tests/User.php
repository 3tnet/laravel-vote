<?php
namespace Ty666\LaravelVote\Tests;

use Illuminate\Database\Eloquent\Model;
use Ty666\LaravelVote\Traits\CanVote;

class User extends Model
{
    use CanVote;
    protected $table = 'users';
    protected $fillable = ['name'];

}
