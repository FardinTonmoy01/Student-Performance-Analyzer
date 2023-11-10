<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SubjectWiseStudentExport implements FromCollection, WithHeadings, WithColumnWidths
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return new Collection($this->data);
    }

    public function headings(): array
    {
        // Define the headings for the Excel file
        return [
            'subject_name',
            'student_name',
            'class',
            'group',
            'section',
            'shift',
            'subject_id',
            'student_id',
            'total_marks',
            'obtain_marks'
        ];
    }

    public function columnWidths(): array
    {
        // Define the width for each column (in characters)
        return [
            'A' => 20,
            'B' => 30,
            'C' => 10,
            'D' => 20,
            'E' => 15,
            'F' => 10,
            'G' => 10,
            'H' => 15,
            'I' => 15,
            'J' => 15
        ];
    }

}
