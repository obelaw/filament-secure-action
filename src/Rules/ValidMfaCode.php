<?php

namespace Obelaw\Filament\SecureAction\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Filament\Auth\MultiFactor\App\AppAuthentication;

class ValidMfaCode implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $isValid = AppAuthentication::make()->verifyCode($value);

        if (! $isValid) {
            $fail(__('secure-action::validation.invalid_mfa_code'));
        }
    }
}
