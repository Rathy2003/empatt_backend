<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QRCode extends Model
{
    protected $table = 'qrcode';
    protected $fillable = ['name','token','checkin_time','checkout_time','embed_url','latitude','longitude','company_id','image'];

    public function company(){
        return $this->belongsTo(Company::class);
    }
}
