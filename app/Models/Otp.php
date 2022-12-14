<?php

/**
 * Generated by Muhammad Muflih Kholidin
 * https://github.com/mmuflih
 * muflic.24@gmail.com
 **/

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    protected $table = "otps";

    protected $fillable = [
        'user_id', 'to', 'code', 'expired_at', 'attempts'
    ];
}
