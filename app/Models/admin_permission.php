<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class admin_permission extends Model
{
    
    protected $guarded = [];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }


}
