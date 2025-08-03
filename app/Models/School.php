<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $table = 'schools';

    protected $fillable = [
        'madrasha_id',
        'MType',
        'ElhaqNo',
        'Stage',
        'StageSerial',
        'MNName',
        'Ara_MName',
        'MName',
        'DID',
        'DES_ID',
        'TID',
        'Village',
        'Post',
        'mobile',
        'EnableDisable',
        'Year',
        'MMLabel',
        'EditDate'
    ];

    protected $casts = [
        'EnableDisable' => 'boolean',
        'EditDate' => 'datetime',
        'Year' => 'integer',
        'MType' => 'integer',
        'Stage' => 'integer',
        'StageSerial' => 'integer',
        'DID' => 'integer',
        'DES_ID' => 'integer',
        'TID' => 'integer'
    ];

    /**
     * Get the division that the school belongs to
     */
    public function division()
    {
        return $this->belongsTo(Division::class, 'DID');
    }

    /**
     * Get the district that the school belongs to
     */
    public function district()
    {
        return $this->belongsTo(District::class, 'DES_ID');
    }

    /**
     * Get the thana that the school belongs to
     */
    public function thana()
    {
        return $this->belongsTo(Thana::class, 'TID');
    }

    /**
     * Get the centers associated with this school
     */
    public function centers()
    {
        return $this->belongsToMany(Center::class, 'madrasha_centers', 'madrasha_id', 'center_id')
                    ->withPivot('serial_number')
                    ->withTimestamps();
    }

    /**
     * Get the madrasha centers pivot records
     */
    public function madrashaCenters()
    {
        return $this->hasMany(MadrashaCenter::class, 'madrasha_id', 'madrasha_id');
    }

    /**
     * Get child madrashas under this madrasha as parent
     */
    public function childMadrashas()
    {
        return $this->hasMany(MadrashaUnderCenter::class, 'parent_madrasha_id', 'madrasha_id');
    }

    /**
     * Get parent madrashas for this madrasha as child
     */
    public function parentMadrashas()
    {
        return $this->hasMany(MadrashaUnderCenter::class, 'child_madrasha_id', 'madrasha_id');
    }

    /**
     * Get all madrashas under this madrasha through centers
     */
    public function madrashasUnderCenters()
    {
        return $this->belongsToMany(School::class, 'madrasha_under_centers', 'parent_madrasha_id', 'child_madrasha_id')
                    ->withPivot('center_id')
                    ->withTimestamps();
    }

    /**
     * Scope to get only active schools
     */
    public function scopeActive($query)
    {
        return $query->where('EnableDisable', true);
    }

    /**
     * Scope to filter by madrasha type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('MType', $type);
    }

    /**
     * Scope to filter by stage
     */
    public function scopeByStage($query, $stage)
    {
        return $query->where('Stage', $stage);
    }

    /**
     * Scope to filter by year
     */
    public function scopeByYear($query, $year)
    {
        return $query->where('Year', $year);
    }

    /**
     * Get formatted mobile number
     */
    public function getFormattedMobileAttribute()
    {
        if ($this->mobile) {
            return '+88' . $this->mobile;
        }
        return null;
    }

    /**
     * Get full address
     */
    public function getFullAddressAttribute()
    {
        $address = [];

        if ($this->Village) {
            $address[] = $this->Village;
        }

        if ($this->Post) {
            $address[] = $this->Post;
        }

        if ($this->thana) {
            $address[] = $this->thana->name;
        }

        if ($this->district) {
            $address[] = $this->district->name;
        }

        if ($this->division) {
            $address[] = $this->division->name;
        }

        return implode(', ', $address);
    }
}
