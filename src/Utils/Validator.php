<?php

namespace Src\Utils;

/**
 * Class Validator
 *
 * This class is responsible for handling the validation of data.
 * It supports built-in rules, custom rules, and custom error messages.
 *
 * @package Src\Utils
 */
class Validator
{
    /**
     * Validate the request body based on the provided rules.
     *
     * @param array $data - Data to be validated.
     * @param array $rules - Validation rules (e.g., ['name' => 'required|string|min:3|max:50']).
     * @param array $messages - Custom error messages (optional).
     * @return array - Array of errors (empty if no errors).
     */
    public static function validate(array $data, array $rules, array $messages = []): array
    {
        $errors = [];

        foreach ($rules as $field => $ruleString) {
            $rulesList = explode('|', $ruleString);

            foreach ($rulesList as $rule) {
                $ruleParts = explode(':', $rule);
                $ruleName = $ruleParts[0];
                $ruleValue = $ruleParts[1] ?? null;

                $value = $data[$field] ?? null;

                if ($ruleName === 'required' && !self::validateRequired($value)) {
                    $errors[$field] = self::getErrorMessage($field, 'required', $messages);
                    break;
                }

                if ($value !== null && $value !== '') {
                    switch ($ruleName) {
                        case 'email':
                            if (!self::validateEmail($value)) {
                                $errors[$field] = self::getErrorMessage($field, 'email', $messages);
                            }
                            break;

                        case 'number':
                            if (!self::validateNumber($value)) {
                                $errors[$field] = self::getErrorMessage($field, 'number', $messages);
                            }
                            break;

                        case 'string':
                            if (!self::validateString($value)) {
                                $errors[$field] = self::getErrorMessage($field, 'string', $messages);
                            }
                            break;

                        case 'array':
                            if (!self::validateArray($value)) {
                                $errors[$field] = self::getErrorMessage($field, 'array', $messages);
                            }
                            break;

                        case 'min':
                            if (!self::validateMin($value, $ruleValue)) {
                                $errors[$field] = self::getErrorMessage($field, 'min', $messages, ['min' => $ruleValue]);
                            }
                            break;

                        case 'max':
                            if (!self::validateMax($value, $ruleValue)) {
                                $errors[$field] = self::getErrorMessage($field, 'max', $messages, ['max' => $ruleValue]);
                            }
                            break;

                        case 'regex':
                            if (!self::validateRegex($value, $ruleValue)) {
                                $errors[$field] = self::getErrorMessage($field, 'regex', $messages);
                            }
                            break;
                    }
                }
            }
        }

        return $errors;
    }

    /**
     * Validate if a value is required.
     *
     * @param mixed $value - Value to check.
     * @return bool - True if valid, false otherwise.
     */
    private static function validateRequired(mixed $value): bool {
        return !empty($value);
    }

    /**
     * Validate if a value is a valid email address.
     *
     * @param string $value - Value to check.
     * @return bool - True if valid, false otherwise.
     */
    private static function validateEmail(string $value): bool {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Validate if a value is a number.
     *
     * @param mixed $value - Value to check.
     * @return bool - True if valid, false otherwise.
     */
    private static function validateNumber(mixed $value): bool {
        return is_numeric($value);
    }

    /**
     * Validate if a value is a string.
     *
     * @param mixed $value - Value to check.
     * @return bool - True if valid, false otherwise.
     */
    private static function validateString(mixed $value): bool {
        return is_string($value);
    }

    /**
     * Validate if a value is an array.
     *
     * @param mixed $value - Value to check.
     * @return bool - True if valid, false otherwise.
     */
    private static function validateArray(mixed $value): bool {
        return is_array($value);
    }

    /**
     * Validate if a value meets the minimum length or value.
     *
     * @param mixed $value - Value to check.
     * @param int $min - Minimum length or value.
     * @return bool - True if valid, false otherwise.
     */
    private static function validateMin(mixed $value, int $min): bool {
        if (is_string($value)) {
            return strlen($value) >= $min;
        } elseif (is_numeric($value)) {
            return $value >= $min;
        }
        return false;
    }

    /**
     * Validate if a value meets the maximum length or value.
     *
     * @param mixed $value - Value to check.
     * @param int $max - Maximum length or value.
     * @return bool - True if valid, false otherwise.
     */
    private static function validateMax(mixed $value, int $max): bool {
        if (is_string($value)) {
            return strlen($value) <= $max;
        } elseif (is_numeric($value)) {
            return $value <= $max;
        }
        return false;
    }

    /**
     * Validate if a value matches a regex pattern.
     *
     * @param string $value - Value to check.
     * @param string $pattern - Regex pattern.
     * @return bool - True if valid, false otherwise.
     */
    private static function validateRegex(string $value, string $pattern): bool {
        return preg_match($pattern, $value) === 1;
    }

    /**
     * Get the error message for a validation rule.
     *
     * @param string $field - Field name.
     * @param string $rule - Rule name.
     * @param array $messages - Custom error messages.
     * @param array $params - Additional parameters for the message (e.g., min, max).
     * @return string - Error message.
     */
    private static function getErrorMessage(string $field, string $rule, array $messages, array $params = []): string {
        if (isset($messages["$field.$rule"])) {
            return $messages["$field.$rule"];
        }

        $defaultMessages = [
            'required' => "The $field field is required.",
            'email' => "The $field field must be a valid email address.",
            'number' => "The $field field must be a number.",
            'string' => "The $field field must be a string.",
            'array' => "The $field field must be an array.",
            'min' => "The $field field must be at least " . ($params['min'] ?? '') . " characters.",
            'max' => "The $field field must not exceed " . ($params['max'] ?? '') . " characters.",
            'regex' => "The $field field format is invalid.",
        ];

        return $defaultMessages[$rule] ?? "The $field field is invalid.";
    }
}