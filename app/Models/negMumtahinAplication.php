<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NegMumtahinAplication extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'neg_mumtahin_aplications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'mobile',
        'birth_date',
        'whatsapp',
        'birth_reg_no',
        'nid',
        'gender',
        'profile_image',
        'application_type',
        'status',
        'teaching_experience',
        'application_id',
        'class_name',
        'pass_year',
        'result',
        'institution',
        'selected_book',
        'post',
        'village',
        'division',
        'district',
        'thana',
        'nagad_number',
        'rocket_number',
        'bkash_number',
        'muhtamim_file',
        'nid_file',
        'birth_cert_file',
        'negaranComments',
        'mumtahinComments',
        'user_id',
           'madrasha_id',
           'teaching_books'


    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'birth_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];



 public function educationalQualifications()
    {
        return $this->hasMany(EducationalQualification::class, 'application_id', 'id');
    }

   public function madrasha()
    {
        return $this->belongsTo(Madrasha::class, 'madrasha_id');
    }



     public function logs()
    {
        return $this->hasMany(negran_mumtahin_log::class, 'application_id');


    }


    public function latestLog()
    {
        return $this->hasOne(negran_mumtahin_log::class, 'application_id')
            ->latest('created_at');
    }
}
