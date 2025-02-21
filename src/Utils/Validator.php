<?php

namespace Src\Utils;

/**
 * This class is responsible for handling the validation
 *
 * Class Validator
 *
 * @package Src\Utils
 */
class Validator {

    /**
     * Validate the request body
     *
     * @param array $body
     * @param array $rules
     * @return array
     */
    public static function validate(array $body, array $rules): array {
        $errors = [];

        foreach ($rules as $key => $rule) {
            $rules = explode('|', $rule);

            foreach ($rules as $rule) {
                if ($rule === 'required' && !isset($body[$key])) {
                    $errors[$key] = 'The ' . $key . ' field is required';
                }

                if ($rule === 'email' && !filter_var($body[$key], FILTER_VALIDATE_EMAIL)) {
                    $errors[$key] = 'The ' . $key . ' field must be a valid email address';
                }

                if ($rule === 'number' && !is_numeric($body[$key])) {
                    $errors[$key] = 'The ' . $key . ' field must be a number';
                }

                if ($rule === 'string' && !is_string($body[$key])) {
                    $errors[$key] = 'The ' . $key . ' field must be a string';
                }

                if ($rule === 'array' && !is_array($body[$key])) {
                    $errors[$key] = 'The ' . $key . ' field must be an array';
                }
            }
        }

        return $errors;
    }
}