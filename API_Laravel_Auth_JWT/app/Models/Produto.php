<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Produto extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "name","description", "price", "image", "stock", "user_id"
    ];

    public function hasUser(){
        return $this->belongsTo(User::class);
    }
}
