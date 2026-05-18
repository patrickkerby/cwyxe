<?php

namespace App\Api\Support;

/**
 * Normalizes WordPress post dates to ISO 8601 UTC strings for the client API.
 */
class DateTimeHelper
{
    /**
     * Convert a WordPress GMT datetime string to ISO 8601 UTC (e.g. 2026-05-18T18:42:11Z).
     */
    public static function toIso8601Utc(?string $gmtDatetime): string
    {
        if (empty($gmtDatetime) || $gmtDatetime === '0000-00-00 00:00:00') {
            return '';
        }

        try {
            $date = new \DateTimeImmutable($gmtDatetime, new \DateTimeZone('UTC'));

            return $date->format('Y-m-d\TH:i:s\Z');
        } catch (\Exception $e) {
            return '';
        }
    }

    /**
     * Normalize an ACF date value (Ymd, Y-m-d, or other parseable strings) to ISO 8601 UTC.
     */
    public static function acfDateToIso8601Utc($value): string
    {
        if ($value === null || $value === false || $value === '') {
            return '';
        }

        if (is_numeric($value)) {
            $value = (string) $value;
        }

        if (! is_string($value)) {
            return '';
        }

        $value = trim($value);

        if ($value === '') {
            return '';
        }

        // ACF return format Ymd (e.g. 20260518)
        if (preg_match('/^\d{8}$/', $value)) {
            $date = \DateTimeImmutable::createFromFormat('Ymd', $value, new \DateTimeZone('UTC'));

            return $date instanceof \DateTimeImmutable ? $date->format('Y-m-d\TH:i:s\Z') : '';
        }

        // Property listing_date uses return_format "F j, Y" (e.g. "May 18, 2026")
        $formats = ['F j, Y', 'Y-m-d', 'm/d/Y'];

        foreach ($formats as $format) {
            $date = \DateTimeImmutable::createFromFormat($format, $value, new \DateTimeZone('UTC'));

            if ($date instanceof \DateTimeImmutable) {
                return $date->format('Y-m-d\TH:i:s\Z');
            }
        }

        try {
            $date = new \DateTimeImmutable($value, new \DateTimeZone('UTC'));

            return $date->format('Y-m-d\TH:i:s\Z');
        } catch (\Exception $e) {
            return '';
        }
    }

    /**
     * Parse an ISO 8601 timestamp from a query parameter into a MySQL GMT datetime string.
     */
    public static function parseIso8601ToMysqlGmt(string $iso8601): ?string
    {
        $iso8601 = trim($iso8601);

        if ($iso8601 === '') {
            return null;
        }

        try {
            $date = new \DateTimeImmutable($iso8601);

            return $date->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            return null;
        }
    }
}
