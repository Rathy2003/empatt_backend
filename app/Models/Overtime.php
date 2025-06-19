<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Overtime extends Model
{
    protected $table = 'overtime';
    protected $fillable = ['employee_id','request_time','reason','start_time','end_time','status'];

    public function employee()
    {
        return $this->belongsTo(User::class,'employee_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
