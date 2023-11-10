<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterSubject extends Model
{
    use HasFactory;

    protected $table = "subjects";

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

    public function class ()
    {
        return $this->belongsTo(MasterClass::class, 'class_id');
    }

    public function group ()
    {
        return $this->belongsTo(MasterGroup::class, 'group_id');
    }
}
