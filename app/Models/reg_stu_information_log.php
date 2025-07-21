<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class reg_stu_information_log extends Model
{

    use HasFactory;

    protected $fillable = [
        'reg_student_id',
        'actor_id',

        'status',
        'actor_type',



        'message',
        'feedback_image',
    ];

    /**
     * Get the student information that owns the log.
     */
    public function studentInformation()
    {
        return $this->belongsTo(reg_stu_information::class, 'reg_student_id');
    }

    /**
     * Get the admin user associated with the log.
     */
  public function user()
{
    return $this->belongsTo(User::class, 'actor_id');
}

public function admin()
{
    return $this->belongsTo(Admin::class, 'actor_id');
}




    public function student()
    {
        return $this->belongsTo(reg_stu_information::class, 'reg_student_id');
    }
}
