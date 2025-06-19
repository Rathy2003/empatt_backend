<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestLeave extends Model
{
    protected $table = 'request';
    protected $fillable = ['employee_id','title','reason','duration','status'];

    public function employee()
    {
        return $this->belongsTo(User::class,'employee_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
