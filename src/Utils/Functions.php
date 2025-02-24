<?php

namespace Src\Utils;

use DateTimeImmutable;
use Exception;

/**
 * This class is used to store utility functions
 *
 * Class Functions
 *
 * @package Src\Utils
 */
class Functions
{
    /**
     * Converts the first character of each word to uppercase
     *
     * @param string $string Input string
     * @return string
     */
    public static function toTitleCase(string $string): string {
        return ucwords(strtolower($string));
    }

    /**
     * Converts string to uppercase
     *
     * @param string $string Input string
     * @return string
     */
    public static function toUpperCase(string $string): string {
        return strtoupper($string);
    }

    /**
     * Converts string to lowercase
     *
     * @param string $string Input string
     * @return string
     */
    public static function toLowerCase(string $string): string {
        return strtolower($string);
    }

    /**
     * Converts the first character to uppercase
     *
     * @param string $string Input string
     * @return string
     */
    public static function capitalize(string $string): string {
        return ucfirst(strtolower($string));
    }

    /**
     * Removes all spaces from string
     *
     * @param string $string Input string
     * @return string
     */
    public static function removeSpaces(string $string): string {
        return str_replace(' ', '', $string);
    }

    /**
     * Removes special characters from string
     *
     * @param string $string Input string
     * @return string
     */
    public static function removeSpecialChars(string $string): string {
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
    }

    /**
     * Formats a date string to specified format
     *
     * @param string $date Date string
     * @param string $format Desired format
     * @return string
     * @throws Exception
     */
    public static function formatDate(string $date, string $format = 'Y-m-d'): string {
        $dateTime = new DateTimeImmutable($date);
        return $dateTime->format($format);
    }

    /**
     * Converts date to Brazilian format (dd/mm/yyyy)
     *
     * @param string $date Date string
     * @return string
     * @throws Exception
     */
    public static function toBrazilianDate(string $date): string {
        return self::formatDate($date, 'd/m/Y');
    }

    /**
     * Checks if string contains only numbers
     *
     * @param string $string Input string
     * @return bool
     */
    public static function isNumeric(string $string): bool {
        return ctype_digit($string);
    }

    /**
     * Masks a string based on pattern
     * Example pattern: '###.###.###-##' for CPF
     *
     * @param string $string Input string
     * @param string $pattern Mask pattern
     * @return string
     */
    public static function mask(string $string, string $pattern): string {
        $string = self::removeSpecialChars($string);
        $pattern = str_replace('#', '%s', $pattern);
        $split = str_split($string);

        return vsprintf($pattern, $split);
    }

    /**
     * Slugifies a string (converts to URL-friendly format)
     *
     * @param string $string Input string
     * @return string
     */
    public static function slugify(string $string): string {
        $string = strtolower($string);
        $string = preg_replace('/[^a-z0-9-]/', '-', $string);
        $string = preg_replace('/-+/', '-', $string);

        return trim($string, '-');
    }

    /**
     * Extracts only numbers from string
     *
     * @param string $string Input string
     * @return string
     */
    public static function onlyNumbers(string $string): string {
        return preg_replace('/[^0-9]/', '', $string);
    }

    /**
     * Formats CPF number
     *
     * @param string $cpf CPF number
     * @return string
     */
    public static function formatCPF(string $cpf): string {
        $cpf = self::onlyNumbers($cpf);

        return self::mask($cpf, '###.###.###-##');
    }

    /**
     * Formats CNPJ number
     *
     * @param string $cnpj CNPJ number
     * @return string
     */
    public static function formatCNPJ(string $cnpj): string {
        $cnpj = self::onlyNumbers($cnpj);

        return self::mask($cnpj, '##.###.###/####-##');
    }
}