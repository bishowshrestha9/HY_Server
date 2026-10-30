<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    
    protected $fillable = [
        'name',
        'email',
        'phone',
        'message',
        'service_id',
        'ip_address',
        'user_agent',
    ];
   

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }


}
//This is just comment for testing purpose.
//This is not just a comment for testing this is for also for testing purpose.
//Very Important comment for testing purpose.
//This ios just a part of comment 
//This is just a part of comment for testing purpose.
//Bishow Shrestha is just my name not my identity.

