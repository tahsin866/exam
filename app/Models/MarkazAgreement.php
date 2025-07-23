<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarkazAgreement extends Model
{
    //
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

    // public function associatedMadrasas()
    // {
    //     return $this->hasMany(MarkazAgreementMadrasa::class);
    // }



    public function associatedMadrasas()
    {
        return $this->hasMany(MarkazAgreementMadrasa::class, 'markaz_agreement_id', 'id');
    }

    public function user()  // Changed from users() to user()
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
     * Status logs for this agreement
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
            default => 'অজানা'
        };
    }


    public function activityLogs()
    {
        return $this->hasMany(activity_log::class, 'markaz_agreement_id'); // Assuming the foreign key is 'markaz_agreement_id'
    }

    public function logs()
    {
        return $this->hasMany(MarkazAgreementLog::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }


    public function schedule_setups()
    {
        return $this->belongsTo(schedule_setups::class, 'exam_setup_id'); // Assuming the foreign key is 'markaz_agreement_id'
    }


    public function regStuInformations()
    {
        return $this->hasMany(reg_stu_information::class, 'markaz_agreement_id');
    }



}
