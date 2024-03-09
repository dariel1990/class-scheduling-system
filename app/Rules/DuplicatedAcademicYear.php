<?php

namespace App\Rules;

use App\Models\AcademicYear;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class DuplicatedAcademicYear implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */

    protected $semester;

    public function __construct(string $semester)
    {
        $this->semester = $semester;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $academic = AcademicYear::where('academic_year', $value)->where('semester', $this->semester)->count();

        if ($academic > 0) {
            $fail('Duplicated Entry.');
        }
    }
}
