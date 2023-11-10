<?php

namespace App\Imports;

use App\Actions\MailSendAction;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class StudentImport implements ToModel, WithHeadingRow
{
    public $year;
    public $instituteId;
    public $classId;
    public $sectionId;
    public $groupId;
    public $shiftId;
    
    public function __construct($data)
    {
        $this->year = $data['year'];
        $this->instituteId = $data['institute_id'];
        $this->classId = $data['class_id'];
        $this->sectionId = $data['section_id'];
        $this->groupId = $data['group_id'];
        $this->shiftId = $data['shift_id'];
    }
    
    public function model(array $row)
    {
        // Validate the data using the custom rule
        $this->validateData($row);

        try {
            DB::beginTransaction();
            $user = [
                'name' => $row['name'],
                'email' => $row['email'],
                'username' => $row['email'],
                'type' => 5,
                'password' => Hash::make('123456'),
                'mobile_no' => $row['mobile_no'],
                'address' => $row['present_address'],
                'photo' => $row['photo'] ?? null,
                'email_verified_at' => Str::random(32)
            ];
            $user = User::create($user);

            $date = \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date_of_birth']))->format('Y-m-d');

            $student = new Student([
                'year' => $this->year,
                'institute_id' => $this->instituteId,
                'class_id' => $this->classId,
                'group_id' => $this->groupId ?? null,
                'section_id' => $this->sectionId,
                'shift_id' => $this->shiftId,
                'user_id' => $user->id,
                'roll_no' => $row['roll_no'],
                'dob' => $date,
                'gender' => $row['gender'],
                'father_name' => $row['father_name'],
                'mother_name' => $row['mother_name'],
                'f_mobile_no' => $row['father_mobile_no'],
                'm_mobile_no' => $row['mother_mobile_no'],
                'f_occupation' => $row['father_occupation'],
                'm_occupation' => $row['mother_occupation'],
                'address' => $row['present_address'],
                'per_address' => $row['permanent_address'],
            ]);

            $student->save();

            if ($student) {
                /** Mail Send to Students email */
                (new MailSendAction())->handle($user, 'mail.verification');
            }

            DB::commit();

            return $student;
        
        } catch (\Exception $e) {
            DB::rollback();
            Log::info('Student Import Error: '. $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to save data.',
                'errors'  => $e->getMessage()
            ]);
        }
    }


    protected function validateData(array $row)
    {
        $validator = Validator::make($row, [
            'name'  => 'required',
            'date_of_birth' => 'required',
            'gender' => 'required',
            'present_address' => 'required',
            'permanent_address' => 'required',
            'mobile_no'  => 'required|unique:users,mobile_no',
            'email'  => 'required|unique:users,email',
            'roll_no' => [
                'required',
                 Rule::unique('students')->where(function ($q) {
                    $q->where('institute_id', $this->instituteId)
                    ->where('class_id', $this->classId)
                    ->where('section_id', $this->sectionId)
                    ->where('shift_id', $this->shiftId);

                    if ($this->groupId) {
                        $q->where('group_id', $this->groupId);
                    }
                    return $q;
                 }),
            ]
        ]);

        if ($validator->fails()) {
            throw new \Exception('Validation error: ' . implode(', ', $validator->errors()->all()));
        }
    }
}
