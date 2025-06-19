<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QRCode extends Model
{
    protected $table = 'qrcode';
    protected $fillable = ['name','token','morning_checkin_time','morning_checkout_time','afternoon_checkin_time','afternoon_checkout_time','embed_url','latitude','longitude','company_id','image'];

    public function company(){
        return $this->belongsTo(Company::class);
    }
}
