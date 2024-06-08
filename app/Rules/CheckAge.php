<?php

namespace App\Rules;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckAge implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $date = Carbon::parse($value);

        if ($date->diffInYears(now()->subYears(16)) <= 0) {
            $fail('You must be at least 16 years old.');
        }
    }
}
