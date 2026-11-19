# Stage 1 — offline build and gate report

**Offline Stage 1 deliverables are assembled.** They have been exercised in a disposable local WordPress environment, but **are not approved for production deployment or publishing**. No changes were made to `readmehub.com`, no page was published there, and Stage 2 has not started. Await the owner's `PROCEED TO STAGE 2` before starting it.

## Deliverable register

| Acceptance item | Artifact and status |
|---|---|
| Permalinks, 5 SaaS + 2 Hosting clusters, Part 4 indexing | Plugin `inc/cpt-tax.php`; map and limitations in `02_taxonomy_permalink_indexing_setup.md`. Local path/query and selected Rank Math noindex/sitemap checks passed. Real HTTP rewrites, canonical behavior and every archive condition remain staging checks. |
| ACF Free JSON | `acf-export-readmehub.json`: two field groups, 74 unique field keys. Imported and selected date/value operations passed in local ACF Free 6.8.11. Exact live ACF version was not established. Field extensions are logged in `05_data_contract.md`. |
| `rmh_tool` and Action Sheet seeds | Plugin `inc/seed-tools.php`: 17 draft records, independent program/verification/application/approval fields, Ahrefs and Notion non-monetized, NordVPN HOLD. Local repeat seeding preserved edited status/URL. Terms are the dated Stage 0 baseline, not newly verified live. |
| Core plugin | `readmehub-core-dynamic/` and `readmehub-core-dynamic.zip`: CPT/taxonomy, settings, shortcodes, schema, CSS tokens and GA4 hook. Staging candidate only; see limitations below. |
| Synced block markup | `patterns/` and bundled plugin markup for T3/T5/T8/T9/T10/T12. Explicit WP-CLI provisioning creates `wp_block` records; local creation, repeat and block-reference rendering passed. These are structural templates, **not finished articles**. |
| Elementor matrix and Inactive list | `01_elementor_performance_matrix.md`. Elementor 4.3.5 PHP widget behavior tested locally; manual widget-level Inactive is mandatory. Browser/UI and site-wide cache checks remain. |
| Site Title fix | Explicit manual/admin/WP-CLI action documented in `02_` and `04_`. It changes only the title, not the tagline. Activation does not change either. Production application needs explicit change approval. |

## Implementation notes

- Affiliate links only come from `[rmh_affiliate_cta]`, with `sponsored nofollow noopener`, the Systeme.io no-`www` rule, inline disclosure before the link, and separate program/verification/approval, 90-day and HOLD checks. The shortcode ignores URL overrides. Blocked programs may show only a separately confirmed, fresh non-affiliate official URL; no fallback is shown otherwise. These checks do not validate a human's source confirmation automatically.
- Prices require an editor-confirmed official HTTPS source, a valid nonfuture verification date, and intro and/or renewal details; >90 days is marked stale. Deals need a confirmed source and date; actual expiry overrides an active label, stale active/upcoming deals are withheld, and the list filters by status. Values remain blank until actually verified.
- The evidence stamp uses Mode A unless a human marks evidence reviewed and supplies a plan, nonfuture date, workflow, observed limits, an existing log attachment and screenshot attachment. That gate checks presence, not the truth of those materials. **No actual product test logs or screenshots have been supplied.** No review rating, aggregate rating or offer price schema is emitted. FAQPage schema is deliberately omitted while visible FAQs remain available.
- Rank Math mode replaces its graph nodes through its JSON-LD filter; standalone mode suppresses that Rank Math output and prints the plugin graph. The local Rank Math test explicitly initialized the JSON-LD module when necessary: it was not a full HTTP/theme test, and other plugins may emit additional graphs. Breadcrumb/review schema is conservative and depends on visible shortcode detection.
- The provider-specific newsletter form is intentionally disabled pending a named provider, contract and consent/privacy review. Elementor explicit Active-cache overrides run before the dynamic filter, so operators must mark every dynamic widget Inactive and separately account for full-page/server/CDN caches.

## Local checks — 2026-11-19 UTC

`tests/run-playground.sh` built disposable WordPress Playground instances from GitHub sources using WP **7.1.3**, PHP **8.2.35**, SQLite, ACF Free **6.8.11**, Rank Math **1.0.280**, and Elementor **4.3.5** (plugins tested in separate runs). **36/36** integration assertions passed: 23 WordPress/ACF, 8 Rank Math graph/indexing, 1 standalone-schema, and 4 Elementor PHP widget/cache checks. The earlier standalone stub suite also passed; no external product facts were tested. Machine-readable results and scripts are in `tests/`.

Important limitations: Playground used SQLite rather than the production database; target PHP was reported as 8.2.34 in the historical probe, not 8.2.35. The Elementor checkout was source-only; no Elementor editor UI or compiled front end was tested. npm emitted engine warnings under Node 22/npm 10 despite the tests passing. No actual production connection, WordPress staging preview, browser/keyboard/viewport, real HTTP redirect, schema validator, security audit, GA4 consent check or CWV measurement has been performed. These are not implied by the passing checks.

## Open blockers and deployment gate

1. Obtain an isolated staging site and explicit approval before installing/activating; backup and inspect existing state. Activation flushes rewrites, and provisioning synced patterns creates persistent shared records. Apply the staging checklist in `04_stage_1_installation_guide.md`.
2. On staging verify real Elementor/editor behavior at all five viewports, accessibility, SEO/canonical/sitemap and real hosting paths, one graph with all active plugins, cache invalidation, privacy/analytics consent and all full-page output. No LCP/INP/CLS values are claimed.
3. Obtain actual dated first-party product prices and terms, owner affiliate IDs and approvals, provider-specific newsletter integration, legal details and real test evidence where needed. The 2026-09-03 affiliate baseline expires **2026-12-02** and must be reverified. The spreadsheet and 12 missing keyword candidates remain Stage 0 blockers.
4. Every Mode A commercial page and unresolved `{{VERIFY…}}` fact remains **BLOCK PUBLISH**, with final manual Stage 7 QA. The plugin's noindex and URL guards are not a complete publication-control system. Do not publish or apply the live title fix without separate approval.

## Package integrity

`readmehub-core-dynamic.zip` SHA-256: `a36c62dde151fb0ac50b7c8c7f44d9ba8d5361e1a737762f377a0867ac13e62f`. This is a staging candidate; the ZIP contains only the plugin folder. Use the separate ACF JSON and guides as described in `04_`.
