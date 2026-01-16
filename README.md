# Supplements Platform

SEO-first, multilingual supplement information and price comparison site built for NL/EN/DE audiences.

## Features
- Language-prefixed routing (`/nl`, `/en`, `/de`) with hreflang + canonical tags.
- Supplement information pages with TL;DR, dosing, safety, FAQ, and evidence.
- Price comparison scaffolding with normalized price metrics and sponsored placements (disabled by default).
- Supabase Postgres schema for actives, forms, goals, dosing, conversions, and sponsored entries.
- Tailwind CSS UI with a text size toggle for accessibility.

## Setup
1. Install dependencies (Laravel 11+ expected).
2. Configure environment variables:
   - `APP_URL`
   - `APP_LOCALE=nl`
   - `DATABASE_URL` (read-only connection for public reads)
   - `SUPABASE_ADMIN_URL` (privileged connection for admin writes)
   - `SPONSORED_PLACEMENTS_ENABLED=false`
3. Run the Supabase migrations in `database/supabase/migrations`.
4. Inspect existing Supabase tables (offers/products/product_actives/view_offer_active_metrics) using `database/supabase/inspect_existing_schema.sql`.
5. Update the comparison query service to match the verified schema.

## Database notes
- All new tables live in the `public` schema.
- `unit_conversions` allows safe conversions only when `is_safe = true`.
- `sponsored_entries` supports active slug targeting, country/language targeting, and scheduling.

## SEO
- Generate sitemaps via `/sitemap.xml` and `/sitemap-{lang}.xml`.
- `robots.txt` allows indexing for all languages (including `/de`).
- Pages include JSON-LD (Article, FAQ, Product) when data is available.

## Admin CMS
Admin CRUD routes and controllers should be added in a dedicated `/admin` prefix with authentication. This repo provides placeholders for the content and comparison services, ready for integration with Laravel policies and guards.
