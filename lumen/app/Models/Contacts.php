<?php

namespace App\Models;

use App\Helpers\Helpers;
use Illuminate\Database\Eloquent\Model;
use Mockery\Matcher\Contains;

class Contacts extends Model
{
    protected $fillable = [
        'customer_id',
        'name',
        'email',
        'phone',
        'function',
    ];

    protected $hidden = [
        "created_at",
        "updated_at"
    ];

    public function setForeignKey(int $foreign)
    {
        $this->customer_id = $foreign;
    }
}
