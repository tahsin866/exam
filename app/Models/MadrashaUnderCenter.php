<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MadrashaUnderCenter extends Model
{
    use HasFactory;

    protected $table = 'madrasha_under_centers';

    protected $fillable = [
        'parent_madrasha_id',
        'center_id',
        'child_madrasha_id'
    ];

    /**
     * Get the parent madrasha (center madrasha)
     */
    public function parentMadrasha()
    {
        return $this->belongsTo(School::class, 'parent_madrasha_id', 'madrasha_id');
    }

    /**
     * Get the child madrasha (under madrasha)
     */
    public function childMadrasha()
    {
        return $this->belongsTo(School::class, 'child_madrasha_id', 'madrasha_id');
    }

    /**
     * Get the center
     */
    public function center()
    {
        return $this->belongsTo(Center::class, 'center_id');
    }

    /**
     * Scope to get madrashas under a specific parent
     */
    public function scopeUnderParent($query, $parentMadrashaId)
    {
        return $query->where('parent_madrasha_id', $parentMadrashaId);
    }

    /**
     * Scope to get madrashas under a specific center
     */
    public function scopeUnderCenter($query, $centerId)
    {
        return $query->where('center_id', $centerId);
    }

    /**
     * Scope to get relationships for a specific child madrasha
     */
    public function scopeForChild($query, $childMadrashaId)
    {
        return $query->where('child_madrasha_id', $childMadrashaId);
    }

    /**
     * Get all child madrashas under a specific parent and center
     */
    public static function getChildMadrashas($parentMadrashaId, $centerId)
    {
        return self::where('parent_madrasha_id', $parentMadrashaId)
                   ->where('center_id', $centerId)
                   ->with(['childMadrasha', 'center'])
                   ->get();
    }

    /**
     * Get all parent madrashas for a specific child
     */
    public static function getParentMadrashas($childMadrashaId)
    {
        return self::where('child_madrasha_id', $childMadrashaId)
                   ->with(['parentMadrasha', 'center'])
                   ->get();
    }

    /**
     * Check if a madrasha is under a specific center
     */
    public static function isUnderCenter($childMadrashaId, $parentMadrashaId, $centerId)
    {
        return self::where('child_madrasha_id', $childMadrashaId)
                   ->where('parent_madrasha_id', $parentMadrashaId)
                   ->where('center_id', $centerId)
                   ->exists();
    }
}
