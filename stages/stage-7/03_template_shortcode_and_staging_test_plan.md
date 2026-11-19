# Stage 7 — template, shortcode, schema and CWV test coverage

All entries below are **release FAIL / NOT VERIFIED** until tested in the actual staging implementation. Static specs or isolated hook tests are supporting evidence, not release passes. Run after explicit staging approval, against actual draft previews, with private access and a backup. Record the URL/ID, viewport, date, test environment, expected and actual result, screenshots/logs, reviewer and fix/retest for each item. No test in this document claims that execution occurred.

## Every template

| Template | Covered source or implementation | Required staging checks / known defect |
|---|---|---|
| T1 Homepage | Stage 3 homepage Page File; temporary home scaffold | Assemble Elementor sections; owner approval before changing front page; verify Wave-1 selection rather than general cluster grid, matrix fallback, LCP image and real destinations. |
| T2 Tools directory | Stage 3 `/tools/`; `rmh_tool_directory` | Verify only public approved review destinations, filters/pagination and empty states; noindex until ≥3 published tool entries; custom tagline is not implemented. |
| T3 Review | Stage 4/5 review Page Files, Stage 1 T3 pattern | Verify blueprint section order, real author/evidence, proof-before-pitch, CTA, freshness and graph; Mode A blocked pending actual logs. |
| T4 Blog archive | Stage 3 `/blog/` | Implement published article query, pagination, empty state and archive integration; index only once ≥1 article is live. |
| T5 Informational guide | Stage 4 guide and Stage 1 T5 pattern | Verify source claims, up and two sibling links, content self-audit, actual graph and Mode A release evidence. |
| T6 Silo/cluster | Stage 3 hubs | Check every actual published child, grid membership/self-exclusion/cap, no orphan and draft-ancestor breadcrumb. Empty hubs noindex; small-business route requires explicit design. |
| T7 Comparison archive | Stage 3 `/comparisons/` | Implement query/filter/pagination and verify only published comparisons; index only once ≥1 is live. |
| T8 Comparison | Stage 5 comparison files, T8 pattern | Verify matched criteria, pricing and source dates, exact row mapping, disclosure, mobile table, applicable CTA and single graph. Ahrefs has no affiliate CTA. |
| T9 Best-of | Stage 4/5 roundup files, T9 pattern | Verify selection rationale, 2,500-word minimum, comparable plan costs, eligibility, actual evidence, disclosure and mobile tables. |
| T10 Alternatives | Stage 4/5 files, T10 pattern | Verify alternatives, sources, fit, 2,000-word minimum, disclosure where CTA exists, relevant siblings; Notion has no affiliate CTA. |
| T11 Deals archive | Stage 3 `/deals/` | Index only after ≥1 verified deal; validate current/expired filters and empty state without inventing offers. |
| T12 Single deal | Stage 3 T12 skeleton and Stage 1 T12 pattern | No actual deal exists. Require verified source, exact dates/terms and independent affiliate eligibility, no fake code/countdown, then real rendering/schema. |
| T13 Search | Stage 3 `/search/` and archive/search specification | Implement safe query, filtering, pagination, empty state; never expose private CPT directly; noindex with query-aware canonical behavior. |
| T14 About | Stage 2 Page File | Owner identity, mission, real practices, contacts, links and visible schema; remove all unconfirmed wording. |
| T15 Editorial methodology | Stage 2 Page File | Confirm real process, who reviews, evidence and corrections; legal/editorial approval and rendered graph. |
| T16 Contact | Stage 2 Page File | Owner-approved endpoint/provider, consent, spam, delivery/error handling and accessibility; no unconfigured form submission. |
| T17 Affiliate disclosure | Stage 2 Page File | Confirm true relationships, controls, operator and legal review; not a replacement for on-page disclosures. |
| T18 Privacy | Stage 2 Page File | Confirm actual data flows, processors, retention, regions and legal entity; legal review and functioning rights/contact process. |
| T19 Terms | Stage 2 Page File | Confirm entity, jurisdiction, policies and legal review; no invented provisions. |
| T20 404 | Stage 3 `pages/404.md` | Implement theme integration, confirm HTTP 404 and noindex, usable navigation and no fake canonical. Not an imported WordPress page. |

For all templates: one H1, correct headings, WordPress output valid, no unsourced facts, no prohibited Pro widgets, correct index/sitemap and schema, keyboard/mobile, valid actual links. Stage 3 block contents are editorial scaffolds, not layouts; visible implementation instructions and planning links must be replaced before release.

## Every registered shortcode

The code registers these 18 shortcodes in `stages/stage-1/readmehub-core-dynamic/inc/shortcodes*.php`. The inherited disposable tests exercise selected paths only; absence of a named test below is **not** a passing assertion. For every shortcode test escaping with hostile input, missing data, correct template context, actual rendered placement and Element Cache Inactive; test full-page cache invalidation separately.

| Shortcode | Specific release test and observed limitation |
|---|---|
| `rmh_disclosure` | Plain-language disclosure visibly precedes first affiliate link on desktop/mobile, including custom text; source order and synthetic test pass only. |
| `rmh_affiliate_cta` | Approved/current record only, no false affiliate fallback, escaped URL, correct `rel`, no Ahrefs/Notion or `www.systeme.io`; validate real records/markup. Synthetic URL and approval checks passed. |
| `rmh_tool_card` | Missing record/review, published-review target, safe text and no unauthorized CTA; ensure blank output has page-level fallback. |
| `rmh_pros_cons` | No invented hands-on results; blank with missing lists; Mode B visible notes match schema. |
| `rmh_faq` | Visible Q&A matches content; safe empty behavior and no duplicate FAQ schema. |
| `rmh_newsletter` | No collection before real provider, consent and privacy review; verify empty/non-collecting state. |
| `rmh_author_box` | Same implementation as author strip; actual verified person, credentials and safe missing state. |
| `rmh_breadcrumbs` | Actual hierarchy and no links to draft ancestors; known defect for `/hosting/wordpress/` intermediate, fix and retest. |
| `rmh_evidence_stamp` | Mode A label is research, Mode B only after reviewed dated attachments and factual verification; blank/incomplete evidence fails closed. Synthetic evidence checks passed. |
| `rmh_verdict_box` | Accurate sourced verdict, placement and no unsupported score or test language. |
| `rmh_pricing_stamp` | Actual nonfuture fresh verified date/source and omitted/fallback output if missing or stale. |
| `rmh_pricing_table` | Source-confirmed fresh plan/term prices, intro and renewal distinct, table accessibility; blank when no usable record. |
| `rmh_comparison_matrix` | Match actual verified tool records and facts; scroll/keyboard on mobile. Known blank return with fewer than two records: implement a conditional static fallback. |
| `rmh_dynamic_cluster_grid` | Published correct children only, self-exclusion, complete membership and Wave-1 selection; current meta matching can include wrong records and is capped at 12. |
| `rmh_tool_directory` | Only intended approved/published review links, filters, pagination, category label and empty results; one-line verified tagline is not implemented. |
| `rmh_deals_list` | Only source-verified eligible offers; expired/stale/empty filter behavior and no fictional coupon; synthetic date tests passed. |
| `rmh_author_strip` | Actual confirmed person/role/credentials, safe omission if not supplied, accessible markup. |
| `rmh_deal_stamp` | Only real source-verified offer and dated status, including expired and absent states; no tracking disguised as source. |

The plugin sometimes intentionally returns an empty string, including matrix, pricing, FAQ and other missing-data cases; the Stage 1 DoD's universal written empty-state cannot be declared met solely from these implementations. Supply and verify appropriate page-level fallbacks or fix implementations before relying on them.

## Staging procedure: schema, SEO, responsive and CWV

1. Inventory real plugins and disable conflicting schema output only with approval. For each page type, retrieve the rendered authenticated preview and public response when safely possible. Inspect HTML source, decode each JSON-LD script, and verify exactly one site-owned `@graph`, correct IDs/references, visible title/author/actual breadcrumb and correct article/page type. Test Rank Math module active, disabled and conflicting emitters. Use Schema Markup Validator on an accessible sanitized page or pasted markup; never expose a protected preview or private information to a third party without approval. Do not paste spec graph as another script. No FAQ rich-result claim.
2. Confirm response code, robots header/meta, canonical and XML sitemap entry against actual policy, including the homepage, 404, search, thin/empty archives, draft intermediate and verified-deal threshold. Draft previews are authenticated and should not be mistaken for public canonical behavior. Inspect links for noindex destinations and eliminate broken/unpublished links before release.
3. On desktop and mobile, inspect every commercial page from top to first CTA and all outgoing anchors. Confirm an accessible disclosure first; approved link, target, rel and fresh record; no Ahrefs/Notion affiliate link; no `www.` for systeme.io. Capture evidence after caches are warm and after status changes.
4. At 1440, 1280, 768, 390 and 360 CSS pixels check layouts, headings, keyboard focus order, touch targets, horizontal scrolling tables, sticky columns, disclosure placement, filter/empty states, 200% zoom and no-JS. Check semantic landmarks, alt text, controls and screen-reader announcements. Ensure the LCP element is not lazy-loaded, and audit real image/font payloads and cache configuration.
5. Obtain real CrUX/Search Console field metrics where sufficient data exists, segmented mobile/desktop and URL group, noting date/window/sample. Run Lighthouse or equivalent lab tests with device/network and repeat count documented; lab LCP/CLS are not substitutes for field INP. Targets LCP ≤2.5 s, INP ≤200 ms and good CLS (≤0.1), evaluated at the 75th percentile for actual eligible field data. If no valid field data exists, mark UNKNOWN, use lab diagnostics and arrange later field monitoring; never invent a pass.
6. Test real GA4 events and Search Console verification with appropriate consent, access and privacy; inspect a click event without transmitting identifiers or credentials into this report. Record actual success or failure. Complete the per-page Part 5.7 checklist and obtain owner/editor/security/legal signoffs before any distinct publication approval.
