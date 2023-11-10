<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guardian extends Model
{
    use HasFactory;

    protected $table = "guardians";

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
        });
        static::updating(function ($model) {
            $model->updated_by = auth()->user()->id;
        });
    }

    public function institute ()
    {
        return $this->belongsTo(InstituteInfo::class, 'institute_id');
    }

    public function child ()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function user ()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
