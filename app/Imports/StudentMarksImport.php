<?php

namespace App\Imports;

use App\Models\MarksEntryDetail;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentMarksImport implements ToModel, WithHeadingRow
{
    public $id;
    
    public function __construct($id)
    {
        $this->id = $id;
    }
    
    public function model(array $row)
    {
        $data = MarksEntryDetail::where('main_id', $this->id)->where('student_id', $row['student_id'])
        ->where('subject_id', $row['subject_id'])
        ->first();
        if ($data) {
            $data->total_marks = $row['total_marks'];
            $data->obtain_marks = $row['obtain_marks'];
        } else {
            $data = new MarksEntryDetail([
                'main_id' => $this->id,
                'student_id' => $row['student_id'],
                'subject_id' => $row['subject_id'],
                'total_marks' => $row['total_marks'],
                'obtain_marks' => $row['obtain_marks'],
            ]);
        }
        
        $data->save();
        return $data;
    }

}
