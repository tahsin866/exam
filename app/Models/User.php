<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type', // 'admin' or 'madrasa'
        'phone',
        'nid',
        'brn',
        'address',
        'profile_image',
        'is_active',
        'admin_Designation',
        'NID_no',
        'Mobile_no',
        'madrasha_id',
        'custom_code',
        'madrasha_name',
        'thana',
        'post',
        'markaz_serial',
        'Stage',
        'MType'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'user_type', 'is_active'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // Scopes
    public function scopeAdmins($query)
    {
        return $query->where('user_type', 'admin');
    }

    public function scopeMadrasaUsers($query)
    {
        return $query->where('user_type', 'madrasa');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Helper Methods
    public function isAdmin()
    {
        return $this->user_type === 'admin';
    }

    public function isMadrasaUser()
    {
        return $this->user_type === 'madrasa';
    }

    public function madrasha()
    {
        return $this->belongsTo(Madrasha::class, 'madrasha_id', 'id');
    }

    public function regStuInformation()
    {
        return $this->hasMany(reg_stu_information::class, 'user_id');
    }

    public function studentLogs()
    {
        return $this->morphMany(reg_stu_information_log::class, 'actor');
    }

    public function activity_log()
    {
        return $this->hasMany(activity_log::class);
    }
}
