<?php

declare(strict_types=1);

namespace Devlop\SecurePasswordRule;

use Dont\JustDont;
use Illuminate\Contracts\Validation\Rule;

class SecurePasswordRule implements Rule
{
    use JustDont;

    /**
     * The minimum length
     */
    protected int $minLength = 6;

    /**
     * Require the use of at least X letters
     */
    protected int $minLetters = 0;

    /**
     * Require the use of at least X lowercase letters
     */
    protected int $minLowercaseLetters = 0;

    /**
     * Require the use of at least X uppercase letters
     */
    protected int $minUppercaseLetters = 0;

    /**
     * Require the use of at least X numbers
     */
    protected int $minNumbers = 0;

    /**
     * Require the use of at least X special characters
     */
    protected int $minSpecial = 0;

    /**
     * No more than X of consecutive whitespace characters (null to disable check)
     */
    protected ?int $maxConsecutiveWhitespace = 2;

    /**
     * No more than X of consecutive identical characters (null to disable check)
     */
    protected ?int $maxConsecutiveIdentical = null;

    /**
     * The errors for the password being tested
     *
     * @var array<string>
     */
    protected array $errors = [];

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value) : bool
    {
        if (mb_strlen($value) < $this->minLength) {
            // must be of a minimum length
            $this->errors[] = $this->minLengthMessage(
                'The password must be at least :min characters.',
            );
        }

        if (preg_match_all('/\p{L}/', $value) < $this->minLetters) {
            $this->errors[] = $this->minLettersMessage(
                'The password must contain letters.|The password must contain at least :min letters.',
            );
        }

        if (preg_match_all('/\p{Ll}/', $value) < $this->minLowercaseLetters) {
            $this->errors[] = $this->minLowercaseLettersMessage(
                'The password must contain lowercase letters.|The password must contain at least :min lowercase letters.',
            );
        }

        if (preg_match_all('/\p{Lu}/', $value) < $this->minUppercaseLetters) {
            $this->errors[] = $this->minUppercaseLettersMessage(
                'The password must contain uppercase letters.|The password must contain at least :min uppercase letters.',
            );
        }

        if (preg_match_all('/\p{N}/', $value) < $this->minNumbers) {
            $this->errors[] = $this->minNumbersMessage(
                'The password must contain numbers.|The password must contain at least :min numbers.',
            );
        }

        if (preg_match_all('/[^\p{L}\p{N}]/', $value) < $this->minSpecial) {
            $this->errors[] = $this->minSpecialMessage(
                'The password must contain special characters.|The password must contain at least :min special characters.',
            );
        }

        // TODO: add check disallowing whitespace totally

        if ($this->maxConsecutiveWhitespace !== null && preg_match('/\s{' . max(0, $this->maxConsecutiveWhitespace) . ',}/', $value) === 1) {
            $this->errors[] = $this->maxConsecutiveWhitespaceMessage(
                'The password can not contain more than :max consecutive space.|The password can not contain more than :max consecutive spaces.',
            );
        }

        if ($this->maxConsecutiveIdentical !== null && preg_match('/(.)\1{' . max(0, $this->maxConsecutiveIdentical) . ',}/', $value) === 1)   {
            $this->errors[] = $this->maxConsecutiveIdenticalMessage(
                'The password can not contain more than :max consecutive identical character.|The password can not contain more than :max consecutive identical characters.',
            );
        }

        return count($this->errors) === 0;
    }

    /**
     * Get the validation error message.
     */
    public function message() : string
    {
        return __('The password is not good enough: :error', [
            'error' => $this->errors[0] ?? '',
        ]);
    }

    /**
     * Get the validation error message for $minLength
     */
    protected function minLengthMessage(string $default) : string
    {
        return trans_choice($default, $this->minLetters, [
            'min' => $this->minLetters,
        ]);
    }

    /**
     * Get the validation error message for $minLetters
     */
    protected function minLettersMessage(string $default) : string
    {
        return trans_choice($default, $this->minLetters, [
            'min' => $this->minLetters,
        ]);
    }

    /**
     * Get the validation error message for $minLowercaseLetters
     */
    protected function minLowercaseLettersMessage(string $default) : string
    {
        return trans_choice($default, $this->minLowercaseLetters, [
            'min' => $this->minLowercaseLetters,
        ]);
    }

    /**
     * Get the validation error message for $minUppercaseLetters
     */
    protected function minUppercaseLettersMessage(string $default) : string
    {
        return trans_choice($default, $this->minUppercaseLetters, [
            'min' => $this->minUppercaseLetters,
        ]);
    }

    /**
     * Get the validation error message for $minNumbers
     */
    protected function minNumbersMessage(string $default) : string
    {
        return trans_choice($default, $this->minNumbers, [
            'min' => $this->minNumbers,
        ]);
    }

    /**
     * Get the validation error message for $minSpecial
     */
    protected function minSpecialMessage(string $default) : string
    {
        return trans_choice($default, $this->minSpecial, [
            'min' => $this->minSpecial,
        ]);
    }

    /**
     * Get the validation error message for $maxConsecutiveWhitespaceMessage
     */
    protected function maxConsecutiveWhitespaceMessage(string $default) : string
    {
        return trans_choice($default, $this->maxConsecutiveWhitespace, [
            'max' => $this->maxConsecutiveWhitespace,
        ]);
    }

    /**
     * Get the validation error message for $maxConsecutiveIdentical
     */
    protected function maxConsecutiveIdenticalMessage(string $default) : string
    {
        return trans_choice($default, $this->maxConsecutiveIdentical, [
            'max' => $this->maxConsecutiveIdentical,
        ]);
    }
}
