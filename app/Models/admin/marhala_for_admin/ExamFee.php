<?php

namespace App\Models\admin\marhala_for_admin;
use Illuminate\Database\Eloquent\Model;
use App\Models\admin\marhala_for_admin\reg_stu_information;

class ExamFee extends Model
{
    protected $fillable = [
        'exam_setup_id',
        'exam_name',
        'reg_date_from',
        'reg_date_to',
        'reg_regular_fee',
        'reg_irregular_jemni',
        'reg_irregular_manonnoyon',
        'reg_irregular_others',
        'late_date_from',
        'late_date_to',
        'late_regular_fee',
        'late_irregular_jemni',
        'late_irregular_manonnoyon',
        'late_irregular_others',


    ];


    public function marhala()
    {
        return $this->belongsTo(Marhala::class, 'marhala_id', 'id');
    }

    public function examSetup()
    {
        return $this->belongsTo(ExamSetup::class , 'exam_setup_id');
    }

    // public function students()
    // {
    //     return $this->hasMany(reg_stu_information::class, 'exam_id', 'exam_setup_id');
    // }
}
