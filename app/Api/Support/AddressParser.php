<?php

namespace App\Api\Support;

/**
 * Resolves city / province / postal from ACF Google Map data or a display address string.
 */
class AddressParser
{
    /**
     * @param array<string, mixed> $map ACF google_map value
     * @return array{city: string, province: string, postal_code: string}
     */
    public static function fromMapField(array $map): array
    {
        $city = trim((string) ($map['city'] ?? ''));
        $province = trim((string) ($map['state_short'] ?? $map['state'] ?? ''));
        $postal = trim((string) ($map['post_code'] ?? ''));

        if ($city !== '' || $province !== '' || $postal !== '') {
            return [
                'city'        => $city,
                'province'    => strtoupper($province),
                'postal_code' => self::normalizePostalCode($postal),
            ];
        }

        $mapAddress = trim((string) ($map['address'] ?? ''));

        return $mapAddress !== '' ? self::parse($mapAddress) : [
            'city'        => '',
            'province'    => '',
            'postal_code' => '',
        ];
    }

    /**
     * Best-effort parser for single-line Canadian display addresses.
     */
    /**
     * @return array{city: string, province: string, postal_code: string}
     */
    public static function parse(string $address): array
    {
        $result = [
            'city'        => '',
            'province'    => '',
            'postal_code' => '',
        ];

        $address = trim($address);

        if ($address === '') {
            return $result;
        }

        $parts = array_map('trim', explode(',', $address));

        if (count($parts) < 2) {
            return $result;
        }

        // Typical: "123 Main St, Saskatoon, SK S7K 1A1"
        $result['city'] = $parts[count($parts) - 2] ?? '';

        $last = $parts[count($parts) - 1] ?? '';

        if (preg_match('/\b([A-Z]{2})\b\s*([A-Z]\d[A-Z]\s?\d[A-Z]\d)/i', $last, $matches)) {
            $result['province']    = strtoupper($matches[1]);
            $result['postal_code'] = strtoupper(preg_replace('/\s+/', ' ', $matches[2]));
        } elseif (preg_match('/\b([A-Z]{2})\b/i', $last, $matches)) {
            $result['province'] = strtoupper($matches[1]);
        }

        return $result;
    }

    private static function normalizePostalCode(string $postal): string
    {
        $postal = strtoupper(preg_replace('/\s+/', ' ', trim($postal)));

        return $postal;
    }
}
