<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarkazAgreement extends Model
{
    protected $fillable = [
        'fazilat',
        'user_id',
        'user_name',
        'exam_id',
        'exam_name',
        'madrasa_Name',
        'markaz_type',
        'sanabiya_ulya',
        'sanabiya',
        'mutawassita',
        'ibtedaiyyah',
        'hifzul_quran',
        'qirat',
        'noc_file',
        'resolution_file',
        'requirements',
        'muhtamim_consent',
        'president_consent',
        'committee_resolution',
        'status',
        'admin_feedback',
        'processed_at',
        'feedback_image',
        'submitted_at',
        'admin_name',
        'madrasha_id',
        'created_by',
        'approved_by',
        'approved_at',
        'status_comment'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'processed_at' => 'datetime',
        'submitted_at' => 'datetime'
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_SUSPENDED = 'suspended';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_BOARD_RETURNED = 'board_returned';
    const STATUS_BOARD_SUBMITTED = 'board_submitted';
    const STATUS_IN_PROCESSING = 'in_processing';

    /**
     * Associated madrasas for this agreement
     */
    public function associatedMadrasas()
    {
        return $this->hasMany(MarkazAgreementMadrasa::class, 'markaz_agreement_id', 'id');
    }

    /**
     * User who owns this agreement
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * User who created this agreement
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * User who approved this agreement
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Status logs for this agreement (ordered descending)
     */
    public function statusLogs()
    {
        return $this->hasMany(MarkazAgreementLog::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get status in Bangla
     */
    public function getStatusBanglaAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'পেন্ডিং',
            self::STATUS_APPROVED => 'অনুমোদিত',
            self::STATUS_SUSPENDED => 'স্থগিত',
            self::STATUS_CANCELLED => 'বাতিল',
            self::STATUS_BOARD_RETURNED => 'বোর্ড ফেরত',
            self::STATUS_BOARD_SUBMITTED => 'বোর্ড দাখিল',
            self::STATUS_IN_PROCESSING => 'প্রক্রিয়াধীন',
            default => 'অজানা'
        };
    }

    /**
     * Activity logs for this agreement
     */
    public function activityLogs()
    {
        return $this->hasMany(activity_log::class, 'markaz_agreement_id');
    }

    /**
     * General logs for this agreement
     */
    public function logs()
    {
        return $this->hasMany(MarkazAgreementLog::class);
    }

    /**
     * Alias for created_by user relation
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Alias for approved_by user relation
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Main madrasha relation
     */
  public function mainMadrasa()
{
    return $this->belongsTo(madrasha::class, 'madrasha_id');
}

    /**
     * Exam setup relation
     */
    public function examSetup()
    {
        return $this->belongsTo(\App\Models\admin\marhala_for_admin\ExamSetup::class, 'exam_id');
    }

    /**
     * Schedule setup relation
     */
    public function schedule_setups()
    {
        return $this->belongsTo(schedule_setups::class, 'exam_setup_id');
    }

    /**
     * Registered student information relation
     */
    public function regStuInformations()
    {
        return $this->hasMany(reg_stu_information::class, 'markaz_agreement_id');
    }


}
