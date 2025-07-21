<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class negran_mumtahin_log extends Model
{


 protected $table = 'negran_mumtahin_logs';

    protected $fillable = [
        'application_id',
        'admin_id',
        'user_id',
        'status',
        'actor_type',
        'actor_name',
        'user_name',
        'user_position',
        'admin_position',
        'admin_message',
        'admin_feedback_image',
    ];

    public function application()
    {
        return $this->belongsTo(NegMumtahinAplication::class, 'application_id');
    }



}
