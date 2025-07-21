<?php

namespace App\Models;
use App\Models\admin\marhala_for_admin\ExamFee;
use Illuminate\Database\Eloquent\Model;

class reg_stu_information extends Model
{
    //
    protected $fillable = [
        'name_bn',
        'name_en',
        'name_ar',
        'father_name_bn',
        'father_name_en',
        'father_name_ar',
        'mother_name_bn',
        'mother_name_en',
        'mother_name_ar',
        'BRN_no',
        'NID_no',
        'madrasha_name',
        'markaz_name',
        'present_division_name',
        'present_DID',
        'present_district_name',
        'board_name',
        'present_thana_name',
        // 'present_thana_id',
        'exam_id',
        'exam_name_Bn',
        'roll',
        'reg_id',
        'past_Roll',
        'past_reg_id',
        // 'marhala_id',
        'student_type',
        'class',
        'Division',
        'Date_of_birth',
        'current_madrasha',
        'current_markaz',
        'current_class',
        'exam_books_name',
        'mobile_no',

        'present_division_name',
        'presernt_DID',
        'present_district_name',
        'present_desId',
        'present_thana_name',
        'present_TID',

        // New fields for permanent address
        'parmanent_division_name',
        'parmanent_DID',
        'parmanent_district_name',
        'parmanent_desId',
        'parmanent_thana_name',
        'parmanent_TID',
        'student_image',
        'NID_attach',
        'is_old_student',
        'madrasha_id',
        'marhala_id',
        'user_id',
        'user_name',
        'markaz_id',
        'passing_year',
        'present_year'

    ];


    // public function logs()
    // {
    //     return $this->hasMany(reg_stu_information_log::class, 'reg_student_id');
    // }




    // // সর্বশেষ লগ পাওয়ার জন্য রিলেশন
    // public function latestLog()
    // {
    //     return $this->hasOne(reg_stu_information_log::class, 'reg_student_id')
    //         ->latest('id'); // 'id' কলাম অনুসারে সর্টিং করুন
    // }


    public function examFee()
    {
        return $this->belongsTo(ExamFee::class, 'exam_id', 'exam_setup_id');
    }

    public function payments()
    {
        return $this->hasMany(student_reg_payment::class, 'students_id', 'id');
    }


    public function markaz()
    {
        return $this->belongsTo(Madrasha::class, 'markaz_id');
    }




// RegStuInformation মডেলে
public function logs()
{
    return $this->hasMany(reg_stu_information_log::class, 'reg_student_id');
}

public function latestLog()
{
    return $this->hasOne(reg_stu_information_log::class, 'reg_student_id')
        ->latest('created_at');
}



}
