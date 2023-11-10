<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarksSettingDetail extends Model
{
    use HasFactory;

    protected $table = "marks_setting_details";

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */

    protected $guarded = ['id', 'created_at', 'updated_at'];


    public function main ()
    {
        return $this->belongsTo(MarksSetting::class, 'main_id');
    }

    public function indicators ()
    {
        return $this->hasMany(MarksSettingDetail::class, 'indicator_id', 'subject_id');
    }
}
