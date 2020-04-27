<?php
namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;


class User extends Model implements Authenticatable
{
    use AuthenticableTrait;

    protected $collection = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone'
    ];

    protected $hidden = ['password'];

}
