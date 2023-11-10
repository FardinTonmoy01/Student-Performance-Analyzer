<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $table = "teachers";

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->created_by = auth()->user()->id;
            $model->updated_by = auth()->user()->id;
            $model->auto_id = 'T-'.$model->institute_id.$model->year.$model->user_id;
        });
        static::updating(function ($model) {
            $model->updated_by = auth()->user()->id;
        });
    }

    // public function generateAutoId($model)
    // {
    //     $autoId = 'T-'.$model->institute_id.$model->year.$model->user_id;
    //     return $autoId;
    // }

    public function institute ()
    {
        return $this->belongsTo(InstituteInfo::class, 'institute_id');
    }

    public function user ()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
