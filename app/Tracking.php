<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tracking extends Model
{
    protected $table = 'tracking_master';
    protected $primaryKey = 'id';

    public function user_prepared(){
        return $this->belongsTo(User::class, 'prepared_by', 'id');
    }
}
