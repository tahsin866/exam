<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MadrashaCenter extends Model
{
    use HasFactory;

    protected $table = 'madrasha_centers';

    protected $fillable = [
        'madrasha_id',
        'center_id',
        'serial_number'
    ];

    /**
     * Get the school/madrasha that owns this center
     */
    public function school()
    {
        return $this->belongsTo(School::class, 'madrasha_id', 'madrasha_id');
    }

    /**
     * Get the center
     */
    public function center()
    {
        return $this->belongsTo(Center::class, 'center_id');
    }

    /**
     * Generate serial number based on center code
     */
    public function generateSerialNumber()
    {
        if ($this->center) {
            $centerCode = $this->center->code;
            $lastSerial = self::where('center_id', $this->center_id)
                ->where('serial_number', 'like', $centerCode . '%')
                ->orderBy('serial_number', 'desc')
                ->first();

            if ($lastSerial) {
                $lastNumber = (int) substr($lastSerial->serial_number, 1);
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }

            return $centerCode . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
        }

        return null;
    }

    /**
     * Scope to get centers for a specific madrasha
     */
    public function scopeForMadrasha($query, $madrashaId)
    {
        return $query->where('madrasha_id', $madrashaId);
    }

    /**
     * Scope to get madrashas for a specific center
     */
    public function scopeForCenter($query, $centerId)
    {
        return $query->where('center_id', $centerId);
    }
}
