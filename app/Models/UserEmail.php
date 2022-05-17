<?php

/**
 * Generated by Muhammad Muflih Kholidin
 * https://github.com/mmuflih
 * muflic.24@gmail.com
 **/

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserEmail extends Model
{
    protected $table = "user_emails";

    protected $fillable = [
        'email', 'domain', 'raw_input', 'primary', 'active'
    ];

    protected $hidden = [
        'domain', 'raw_input', 'primary', 'active', 'user_id', 'created_at', 'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
