# CWYXE Listings API

Read-only REST API for syncing commercial property listings from WordPress to Airtable.

**WordPress is the source of truth.** This API replaces scraping the public website.

---

## Base URL

| Environment | Base URL |
|-------------|----------|
| Local (example) | `https://new-cwyxe.test` |
| Production | `https://cushmanwakefieldsaskatoon.com/` |

All endpoints live under:

```
https://cushmanwakefieldsaskatoon.com/wp-json/client/v1/
```

---

## Authentication

Currently **public read-only** (no API key required). Production may add authentication later—coordinate with the site administrator before go-live.

---

## Endpoints

### 1. List all listings

```
GET /wp-json/client/v1/listings
```

**Query parameters**

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `page` | integer | `1` | Page number |
| `per_page` | integer | `50` | Results per page (max `100`) |
| `modified_after` | string | — | ISO 8601 UTC timestamp; returns listings modified **after** this time |
| `include_inactive` | boolean | `false` | Include unpublished listings (draft, pending, private) |

**Response**

```json
{
  "listings": [ { /* listing object — see Field reference */ } ],
  "meta": {
    "total": 20,
    "page": 1,
    "per_page": 50,
    "total_pages": 1,
    "modified_after": null
  }
}
```

**Examples**

```bash
# First page (50 listings)
curl "https://cushmanwakefieldsaskatoon.com/wp-json/client/v1/listings"

# Small sample for testing
curl "https://cushmanwakefieldsaskatoon.com/wp-json/client/v1/listings?per_page=2"

# Incremental sync — only listings updated after timestamp
curl "https://cushmanwakefieldsaskatoon.com/wp-json/client/v1/listings?modified_after=2025-12-01T00:00:00Z"

# Include unpublished listings (for sync workflows)
curl "https://cushmanwakefieldsaskatoon.com/wp-json/client/v1/listings?include_inactive=1"
```

When `modified_after` is used, unpublished listings that changed since that time are included automatically (even without `include_inactive`).

---

### 2. Single listing

```
GET /wp-json/client/v1/listings/{id}
```

`{id}` is the WordPress post ID (same as the `ID` field in the response).

**Query parameters**

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `include_inactive` | boolean | `false` | Allow fetching unpublished listings |

**Response**

```json
{
  "listing": { /* listing object */ }
}
```

**Example**

```bash
curl "https://cushmanwakefieldsaskatoon.com/wp-json/client/v1/listings/26766"
```

---

## Listing object shape

Each listing is a **flat** JSON object. Field names match the Airtable base exactly (62 fields).

- **Strings** — Missing values return `""` (empty string), not `null`.
- **Arrays** — Only `propertyFeatures` is an array of strings; empty = `[]`.
- **Timestamps** — ISO 8601 UTC, e.g. `2025-12-08T21:42:51Z`.

### Sample listing (abbreviated)

```json
{
  "ID": "26766",
  "url": "https://new-cwyxe.test/property/639-main-street-2/",
  "address": "639 Main Street, Saskatoon SK",
  "propertyType": "Retail",
  "listingDate": "2023-06-14T00:00:00Z",
  "Created": "2024-11-25T21:35:54Z",
  "Updated": "2025-12-08T21:42:51Z",
  "unitLeaseRateRaw": "32.00",
  "unitLeaseRatePeriod": "per SF",
  "unitLeaseRateType": "Lease",
  "parcelNumber": "120158535, 120158041",
  "possession": "Immediate",
  "propertyFeatures": [
    "Tax Abatement: Tax abatement on occupancy costs...",
    "Parking: Tenant parking available at market rates"
  ],
  "latitude": "52.1169263",
  "longitude": "-106.6543344",
  "source": "wordpress",
  "lastScraped": "2025-12-08T21:42:51Z",
  "dataQuality": ""
}
```

---

## Sync workflow (recommended)

1. **Initial load** — `GET /listings?per_page=100` and paginate using `page` until `meta.page >= meta.total_pages`.
2. **Ongoing sync** — Store `Updated` from each record; on the next run call  
   `GET /listings?modified_after={last_sync_timestamp}`.
3. **Stable ID** — Use `ID` (WordPress post ID) as the primary key in Airtable.
4. **Images** — `image` and `cloudinaryImage` both return the primary listing image URL (legacy Airtable field name preserved).

---

## Errors

| HTTP | Code | When |
|------|------|------|
| `400` | `invalid_modified_after` | `modified_after` is not valid ISO 8601 |
| `400` | `invalid_id` | Single listing: ID is not a positive integer |
| `404` | `listing_not_found` | No property with that ID (or not published) |

**Example (400)**

```json
{
  "code": "invalid_modified_after",
  "message": "modified_after must be a valid ISO 8601 timestamp."
}
```

---

## Field mapping

See **[listings-field-mapping.md](./listings-field-mapping.md)** for a full table of every API field, its WordPress/ACF source, and how it appears on the public site.

---

## Support & changes

- API code lives in the CWYXE theme under `app/Api/`.
- To change how a field is populated, the site team updates the mapping config—**Airtable column names stay the same**.
- Questions or new fields: contact the WordPress development team.

*Document version: May 2026*
