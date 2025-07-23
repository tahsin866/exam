<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarkazAgreementLog extends Model
{
    protected $fillable = [
        'markaz_agreement_id',
        'user_id',
        'previous_status',
        'new_status',
        'action_type',
        'comments',
        'additional_data'
    ];

    protected $casts = [
        'additional_data' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_SUSPENDED = 'suspended';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_BOARD_RETURNED = 'board_returned';
    const STATUS_BOARD_SUBMITTED = 'board_submitted';

    // Action type constants
    const ACTION_CREATED = 'created';
    const ACTION_SUBMITTED = 'submitted';
    const ACTION_APPROVED = 'approved';
    const ACTION_SUSPENDED = 'suspended';
    const ACTION_CANCELLED = 'cancelled';
    const ACTION_BOARD_RETURNED = 'board_returned';

    /**
     * Relationship with MarkazAgreement
     */
    public function markazAgreement(): BelongsTo
    {
        return $this->belongsTo(MarkazAgreement::class);
    }

    /**
     * Relationship with User (who performed the action)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get status in Bangla
     */
    public function getStatusBanglaAttribute(): string
    {
        return match($this->new_status) {
            self::STATUS_PENDING => 'পেন্ডিং',
            self::STATUS_APPROVED => 'অনুমোদিত',
            self::STATUS_SUSPENDED => 'স্থগিত',
            self::STATUS_CANCELLED => 'বাতিল',
            self::STATUS_BOARD_RETURNED => 'বোর্ড ফেরত',
            self::STATUS_BOARD_SUBMITTED => 'বোর্ড দাখিল',
            default => 'অজানা'
        };
    }

    /**
     * Get action type in Bangla
     */
    public function getActionTypeBanglaAttribute(): string
    {
        return match($this->action_type) {
            self::ACTION_CREATED => 'আবেদন তৈরি',
            self::ACTION_SUBMITTED => 'বোর্ডে দাখিল',
            self::ACTION_APPROVED => 'অনুমোদন',
            self::ACTION_SUSPENDED => 'স্থগিত',
            self::ACTION_CANCELLED => 'বাতিল',
            self::ACTION_BOARD_RETURNED => 'বোর্ড ফেরত',
            default => 'অজানা'
        };
    }
}
