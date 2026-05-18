# Listings API — Field mapping reference

Maps each **Airtable / API field** to its source in WordPress (ACF).  
The API field names are fixed; WordPress field names can change behind the scenes without breaking the sync.

**Legend**

| Source type | Meaning |
|-------------|---------|
| **Post** | WordPress core post data |
| **ACF** | Advanced Custom Fields on the Property post |
| **Taxonomy** | WordPress taxonomy term(s) |
| **Agent** | Linked Agent post (first agent on the listing) |
| **Additional Details** | Custom row in the “Additional Details” repeater (matched by title text) |
| **—** | Not stored in WordPress; API returns `""` |

**Site “Details” section** (on each property page) is built from four blocks, in order:

1. Property Details (ACF group)  
2. Dimensions (ACF group)  
3. Rates (ACF group)  
4. Additional Details (ACF repeater — free-form rows)

---

## Identity & URLs

| API field | Type | WordPress source | On-site label / notes |
|-----------|------|------------------|------------------------|
| `ID` | string | Post ID | — |
| `url` | string | Permalink | Listing page URL |
| `source` | string | Constant: `wordpress` | — |
| `dataQuality` | string | — | Airtable-only; always empty from WP |

---

## Address & location

| API field | Type | WordPress source | On-site label / notes |
|-----------|------|------------------|------------------------|
| `address` | string | ACF `address` | **Display Address** (may differ from map pin) |
| `city` | string | ACF `map` → `city` | From Google Map; falls back to parsing `address` |
| `province` | string | ACF `map` → `state_short` | e.g. `SK` |
| `postalCode` | string | ACF `map` → `post_code` | e.g. `S7K 1A1` |
| `latitude` | string | ACF `map` → `lat` | Map pin |
| `longitude` | string | ACF `map` → `lng` | Map pin |

---

## Media

| API field | Type | WordPress source | On-site label / notes |
|-----------|------|------------------|------------------------|
| `image` | string | ACF `primary_image` (URL) | Hero / gallery image |
| `cloudinaryImage` | string | Same as `image` | Legacy Airtable name; not a separate Cloudinary field |

---

## Classification & description

| API field | Type | WordPress source | On-site label / notes |
|-----------|------|------------------|------------------------|
| `propertyType` | string | Taxonomy `property-type` | Header pills, e.g. “Retail”, “Industrial” |
| `description` | string | ACF `overview` | **Overview** section |
| `propertyFeatures` | **array** | ACF `key_features` repeater | **Key Features** — each item: `"Title: content"` |
| `unitPropertyType` | string | ACF `property_details` → `space_type` | Details: **Space Type** |

---

## Property details (ACF group: `property_details`)

| API field | Type | ACF sub-field | On-site label / notes |
|-----------|------|---------------|------------------------|
| `parcelNumber` | string | `property_id` | **Parcel # / Property ID** (may be comma-separated) |
| `zoningCode` | string | `zoning` | **Zoning** |
| `possession` | string | `occupancy` | **Occupancy** (e.g. Immediate, Fall 2026) |
| `listingDate` | string (ISO UTC) | `listing_date` | CMS **Listing Date** (hidden on front-end Details table) |

---

## Dimensions (ACF group: `dimensions_section`)

Values include unit postfix where set, e.g. `66863.00 SF`, `4.21 Acres`.

| API field | Type | ACF sub-field | On-site label / notes |
|-----------|------|---------------|------------------------|
| `buildingSquareFootage` | string | `building_size` | **Building Size** |
| `landArea` | string | `lot_size` | **Lot Size** |
| `unitSquareFootage` | string | `area_size` | **Area Size** |
| `squareFootageRange_min` | string | `min_divisible` | **Min Divisible** — only when “Multiple listing Property?” is enabled |
| `squareFootageRange_max` | string | `max_contiguous` | **Max Contiguous** — only when “Multiple listing Property?” is enabled |

---

## Rates (ACF group: `rates`)

One rate block per listing (not per-unit repeater in this version).

| API field | Type | ACF sub-field | On-site label / notes |
|-----------|------|---------------|------------------------|
| `unitLeaseRateType` | string | `rate_type` | **Rate Type** (Lease, Rent, Sale) |
| `unitLeaseRateRaw` | string | `amount` | **Price** — numeric part, e.g. `32.00` |
| `unitLeaseRatePeriod` | string | `rate_postfix` | **Price** suffix, e.g. `per SF` |

---

## Marketing & agent contact

| API field | Type | WordPress source | On-site label / notes |
|-----------|------|------------------|------------------------|
| `marketingPackage_name` | string | Derived | `Marketing Package` when PDF link exists |
| `marketingPackage_link` | string | ACF `marketing_package` | Marketing Package PDF URL |
| `contactInfo_name` | string | Agent → `contact_details` or post title | Sidebar agent |
| `contactInfo_phone` | string | Agent → `mobile_phone`, then `office_phone` | — |
| `contactInfo_email` | string | Agent → `email` | — |
| `contactInfo_company` | string | Agent → `company` | — |

---

## Dates & sync metadata

| API field | Type | WordPress source | On-site label / notes |
|-----------|------|------------------|------------------------|
| `Created` | string (ISO UTC) | `post_date_gmt` | When post was created |
| `Updated` | string (ISO UTC) | `post_modified_gmt` | Last modified in CMS |
| `lastScraped` | string (ISO UTC) | Same as `Updated` | For Airtable sync parity |
| `listingDate` | string (ISO UTC) | `property_details.listing_date` | Business listing date |
| `daysOnMarket` | string | Calculated | Days since `listingDate`, else since `Created` |

---

## Additional details (ACF repeater)

These fields are filled by matching **Detail Title** text in `additional_details` → `details` (case-insensitive substring). If no row matches, the API returns `""`.

| API field | Matched when title contains… | Typical on-site label |
|-----------|------------------------------|------------------------|
| `unitOccupancyCosts` | `occupancy cost`, `occupancy rate` | **Occupancy Cost** / **Occupancy Rate (20XX Est.)** |
| `capRate` | `cap rate` | Cap rate row |
| `noi` | `noi`, `net operating income` | NOI row |
| `propertyTaxes` | `property tax`, `taxes` | Property taxes |
| `pricePerAcre` | `price per acre`, `per acre` | Price per acre |
| `vacancyRate` | `vacancy rate` | Vacancy rate |
| `vacantSpace` | `vacant space`, `available space` | Vacant / available space |
| `previousTenant` | `previous tenant`, `prior tenant` | Previous tenant |
| `parkingDetails` | `parking`, `parkade`, `stalls` | Parking |
| `stories` | `stories`, `storeys`, `floors` | Stories / floors |
| `unitNumber` | `unit`, `suite` | Unit / suite |
| `unitClearHeight` | `clear height`, `ceiling height` | Clear height |
| `unitDockDoors` | `dock`, `loading dock` | Dock doors |
| `unitGradeDoorsAtGrade` | `grade door`, `drive-in`, `at grade` | Grade doors |
| `unitElectricalService` | `electrical`, `power service`, `amps` | Electrical |
| `unitColumnSpacing` | `column spacing` | Column spacing |

---

## Notes

| API field | Type | WordPress source | On-site label / notes |
|-----------|------|------------------|------------------------|
| `Notes` | string | All **Additional Details** rows | Concatenated as `"Title: info"` per line. May duplicate data also mapped above (e.g. occupancy cost). |

---

## Not in WordPress (empty for now)

These Airtable columns are included in the API but have **no CMS source** yet—they always return `""` until mapped later.

| API field |
|-----------|
| `unitOccupancyCostsPerSqft` |
| `unitLeasePricePerSqftAnnual` |
| `unitBuyPrice` |
| `unitBuyPricePerSqft` |
| `unitMonthlyRent` |
| `unitMonthlyRentGross` |
| `unitDescription` |

---

## Quick reference: Details table → API

| What you see on the property page | API field(s) |
|-----------------------------------|--------------|
| Parcel # / Property ID | `parcelNumber` |
| Space Type | `unitPropertyType` |
| Zoning | `zoningCode` |
| Occupancy | `possession` |
| Building / Lot / Area Size | `buildingSquareFootage`, `landArea`, `unitSquareFootage` |
| Min Divisible / Max Contiguous | `squareFootageRange_min`, `squareFootageRange_max` |
| Rate Type | `unitLeaseRateType` |
| Price | `unitLeaseRateRaw` + `unitLeaseRatePeriod` |
| Occupancy Cost (20XX Est.) | `unitOccupancyCosts` (+ may appear in `Notes`) |
| Any other custom Detail row | `Notes` (and dedicated field if title matches table above) |

---

*For endpoint URLs, query parameters, and sync examples, see [listings-api.md](./listings-api.md).*
