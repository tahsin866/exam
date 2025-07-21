<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class madrasha extends Model
{

protected $table = 'madrashas';


protected $fillable = [
    'MName_uni',
    'ElhaqNo',
    'MType',
        'markaz_serial',
        'Mobile',
        'division',
        'district',
        'Thana_uni'
];


public function users()
{
    return $this->hasMany(User::class, 'madrasha_id');
}



public function division()
{
    return $this->belongsTo(Division::class, 'DID', 'ID');
}

public function district()
{
    return $this->belongsTo(District::class, 'DISID', 'DesID');
}

public function thana()
{
    return $this->belongsTo(Thana::class, 'TID', 'Thana_ID');
}


    public function applications()
    {
        return $this->hasMany(negMumtahinAplication::class, 'madrasha_id');
    }


}
