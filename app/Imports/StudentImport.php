<?php

namespace App\Imports;

use App\Models\Students;
use App\Models\Subjects;
use App\Models\SubjectStudents;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentImport implements ToModel, WithHeadingRow
{
    protected $subjectId;
    protected $course;
    protected $yearLevel;
    protected $section;

    public function __construct($subjectId, $course, $yearLevel, $section)
    {
        $this->subjectId = $subjectId;
        $this->course = $course;
        $this->yearLevel = $yearLevel;
        $this->section = $section;
    }

    public function model(array $row)
    {
        $subjectId = $this->subjectId;
        $course = $this->course;
        $yearLevel = $this->yearLevel;
        $section = $this->section;

        $fullName = $row['name'];

        $nameParts = explode(', ', $fullName);
        $lastname = $nameParts[0];

        $firstAndMiddle = explode(' ', $nameParts[1]);
        $firstname = implode(' ', array_slice($firstAndMiddle, 0, -1));
        $middleinitial = end($firstAndMiddle);

        $existingStudent = Students::where('last_name', $lastname)
            ->where('first_name', $firstname)
            ->where('middle_name', $middleinitial)
            ->first();

        if ($existingStudent) {
            $existingSubjectStudent = SubjectStudents::where('subject_id', $subjectId)
                ->where('student_id', $existingStudent->id)
                ->exists();

            if (!$existingSubjectStudent) {
                $subjectCode = Subjects::find($subjectId);

                $hasDuplicatedSubjects = $existingStudent->subjects->pluck('subject_code')->contains($subjectCode->subject_code);
                if (!$hasDuplicatedSubjects) {
                    SubjectStudents::create([
                        'subject_id' => $subjectId,
                        'student_id' => $existingStudent->id,
                    ]);
                }

                return null;
            }

            return null;
        }

        $students = Students::create([
            'last_name'     => $lastname,
            'first_name'    => $firstname,
            'middle_name'   => $middleinitial,
            'course'        => $course,
            'year_level'    => $yearLevel,
            'section'       => $section,
        ]);

        SubjectStudents::create([
            'subject_id' => $subjectId,
            'student_id' => $students->id,
        ]);

        // Insert the data into the students table
        return $students;
    }
}
