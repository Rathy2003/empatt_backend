<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OTP extends Model
{
    protected $table = 'otp';
    protected $fillable = ['employee_id','code', 'expired_at', 'verified_at'];
    public $timestamps = true;

    public function scopeValid($query)
    {
        return $query->where('expired_at', '>', now());
    }
}
