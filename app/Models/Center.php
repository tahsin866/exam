<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Center extends Model
{
    use HasFactory;

    protected $table = 'centers';

    protected $fillable = [
        'code',
        'name',
        'description'
    ];

    /**
     * Get center by code
     */
    public static function getByCode($code)
    {
        return self::where('code', $code)->first();
    }

    /**
     * Get all active centers
     */
    public static function getActiveCenters()
    {
        return self::all();
    }

    /**
     * Scope to filter by code
     */
    public function scopeByCode($query, $code)
    {
        return $query->where('code', $code);
    }

    /**
     * Get the schools associated with this center
     */
    public function schools()
    {
        return $this->belongsToMany(School::class, 'madrasha_centers', 'center_id', 'madrasha_id')
                    ->withPivot('serial_number')
                    ->withTimestamps();
    }

    /**
     * Get the madrasha centers pivot records
     */
    public function madrashaCenters()
    {
        return $this->hasMany(MadrashaCenter::class, 'center_id');
    }

    /**
     * Get madrasha under centers relationships
     */
    public function madrashaUnderCenters()
    {
        return $this->hasMany(MadrashaUnderCenter::class, 'center_id');
    }
}
