<?php

/**
 * Airtable sync schema only — keys and order match the external Airtable base.
 * Internal ACF sources are wired in ListingTransformer; update mappings here only.
 *
 * @see \App\Api\Transformers\ListingTransformer
 */

return [

    'post_type' => 'property',

    'fields' => [
        'ID'                          => ['resolver' => 'postId'],
        'url'                         => ['resolver' => 'permalink'],
        'address'                     => 'address',
        'unitNumber'                  => ['resolver' => 'additionalDetail', 'match' => ['unit', 'suite']],
        'image'                       => ['resolver' => 'primaryImageUrl'],
        'cloudinaryImage'             => ['resolver' => 'primaryImageUrl'],
        'propertyType'                => ['resolver' => 'propertyType'],
        'description'                 => 'overview',
        'city'                        => ['resolver' => 'addressCity'],
        'province'                    => ['resolver' => 'addressProvince'],
        'postalCode'                  => ['resolver' => 'addressPostalCode'],
        'buildingSquareFootage'       => ['resolver' => 'dimension', 'field' => 'building_size'],
        'squareFootageRange_min'      => ['resolver' => 'dimension', 'field' => 'min_divisible'],
        'squareFootageRange_max'      => ['resolver' => 'dimension', 'field' => 'max_contiguous'],
        'previousTenant'              => ['resolver' => 'additionalDetail', 'match' => ['previous tenant', 'prior tenant']],
        'marketingPackage_name'       => ['resolver' => 'marketingPackageName'],
        'marketingPackage_link'       => 'marketing_package',
        'capRate'                     => ['resolver' => 'additionalDetail', 'match' => ['cap rate']],
        'noi'                         => ['resolver' => 'additionalDetail', 'match' => ['noi', 'net operating income']],
        'propertyFeatures'            => ['resolver' => 'propertyFeatures'],
        'contactInfo_name'            => ['resolver' => 'contactName'],
        'contactInfo_phone'           => ['resolver' => 'contactPhone'],
        'contactInfo_email'           => ['resolver' => 'contactEmail'],
        'contactInfo_company'         => ['resolver' => 'contactCompany'],
        'listingDate'                 => ['resolver' => 'listingDate'],
        'daysOnMarket'                => ['resolver' => 'daysOnMarket'],
        'source'                      => ['resolver' => 'source'],
        'lastScraped'                 => ['resolver' => 'updatedAt'],
        'dataQuality'                 => ['resolver' => 'empty'],
        'unitSquareFootage'           => ['resolver' => 'dimension', 'field' => 'area_size'],
        'unitOccupancyCosts'          => ['resolver' => 'additionalDetail', 'match' => ['occupancy cost', 'occupancy rate']],
        'unitOccupancyCostsPerSqft'   => ['resolver' => 'empty'],
        'unitLeaseRateRaw'            => ['group' => 'rates', 'field' => 'amount'],
        'unitLeaseRatePeriod'         => ['group' => 'rates', 'field' => 'rate_postfix'],
        'unitLeaseRateType'           => ['group' => 'rates', 'field' => 'rate_type'],
        'unitLeasePricePerSqftAnnual' => ['resolver' => 'empty'],
        'unitBuyPrice'                => ['resolver' => 'empty'],
        'unitBuyPricePerSqft'         => ['resolver' => 'empty'],
        'unitMonthlyRent'             => ['resolver' => 'empty'],
        'unitMonthlyRentGross'        => ['resolver' => 'empty'],
        'unitPropertyType'            => ['resolver' => 'propertyDetail', 'field' => 'space_type'],
        'unitClearHeight'             => ['resolver' => 'additionalDetail', 'match' => ['clear height', 'ceiling height']],
        'unitDockDoors'               => ['resolver' => 'additionalDetail', 'match' => ['dock', 'loading dock']],
        'unitGradeDoorsAtGrade'       => ['resolver' => 'additionalDetail', 'match' => ['grade door', 'drive-in', 'at grade']],
        'unitElectricalService'       => ['resolver' => 'additionalDetail', 'match' => ['electrical', 'power service', 'amps']],
        'unitColumnSpacing'           => ['resolver' => 'additionalDetail', 'match' => ['column spacing']],
        'unitDescription'             => ['resolver' => 'empty'],
        'landArea'                    => ['resolver' => 'dimension', 'field' => 'lot_size'],
        'pricePerAcre'                => ['resolver' => 'additionalDetail', 'match' => ['price per acre', 'per acre']],
        'stories'                     => ['resolver' => 'additionalDetail', 'match' => ['stories', 'storeys', 'floors']],
        'propertyTaxes'               => ['resolver' => 'additionalDetail', 'match' => ['property tax', 'taxes']],
        'vacantSpace'                 => ['resolver' => 'additionalDetail', 'match' => ['vacant space', 'available space']],
        'vacancyRate'                 => ['resolver' => 'additionalDetail', 'match' => ['vacancy rate']],
        'parcelNumber'                => ['group' => 'property_details', 'field' => 'property_id'],
        'parkingDetails'              => ['resolver' => 'additionalDetail', 'match' => ['parking', 'parkade', 'stalls']],
        'zoningCode'                  => ['group' => 'property_details', 'field' => 'zoning'],
        'possession'                  => ['group' => 'property_details', 'field' => 'occupancy'],
        'Created'                     => ['resolver' => 'createdAt'],
        'Updated'                     => ['resolver' => 'updatedAt'],
        'latitude'                    => ['resolver' => 'latitude'],
        'longitude'                   => ['resolver' => 'longitude'],
        'Notes'                       => ['resolver' => 'notes'],
    ],

    'defaults' => [
        'scalar' => '',
        'array'  => [],
    ],

    'array_fields' => [
        'propertyFeatures',
    ],

];
