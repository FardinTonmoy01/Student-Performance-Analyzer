<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarksEntryDetail extends Model
{
    use HasFactory;

    protected $table = "marks_entry_details";

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

    public function student ()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function subject ()
    {
        return $this->belongsTo(MasterSubject::class, 'subject_id');
    }

    public function main ()
    {
        return $this->belongsTo(MarksEntry::class, 'main_id');
    }

}
