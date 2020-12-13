<?php
namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Model;

class Chat extends Model
{
    protected $collection = 'chats';

    protected $fillable = [
        'users',
        'messages',
    ];
}
