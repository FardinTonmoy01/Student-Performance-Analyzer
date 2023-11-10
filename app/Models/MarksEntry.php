<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarksEntry extends Model
{
    use HasFactory;

    protected $table = "marks_entries";

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

    public function section ()
    {
        return $this->belongsTo(MasterSection::class, 'section_id');
    }

    public function shift ()
    {
        return $this->belongsTo(MasterShift::class, 'shift_id');
    }

    public function indicator ()
    {
        return $this->belongsTo(EvaluatingIndicator::class, 'indicator_id');
    }

    public function details ()
    {
        $subjectIds = session('subject_ids') ?? null;
        return $this->hasMany(MarksEntryDetail::class, 'main_id')->when($subjectIds, function ($q) use ($subjectIds) {
            $q->whereIn('subject_id', $subjectIds);
        });
    }
}
