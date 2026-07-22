<?php

namespace App\Support;

/**
 * Normalizes general_settings.availability (button group string or checkbox array).
 */
class AvailabilityFormatter
{
    /**
     * @param mixed $value ACF availability value
     */
    public static function format($value): string
    {
        if ($value === null || $value === false || $value === '') {
            return '';
        }

        if (is_array($value)) {
            $items = array_filter(array_map(static function ($item) {
                if (is_string($item) || is_numeric($item)) {
                    return trim((string) $item);
                }

                return '';
            }, $value));

            return $items !== [] ? implode(' or ', $items) : '';
        }

        return trim((string) $value);
    }
}
