# T12 — Single deal Page File skeleton (not a deal page)

**Do not create a WordPress record from this skeleton without an actual verified offer.** No deal, brand, offer, code, discount, date, destination or claim is asserted here. It uses the Stage 1 Gutenberg structural pattern `../stage-1/patterns/T12-deal.html`, not Elementor. Detach a synced pattern before filling it. Every fact and link is gated by the evidence, freshness and approval requirements in Stage 1; unresolved fields are blockers, never copy them into a published page. No countdown.

```yaml
url: null # owner chooses the actual verified brand slug under /deals/
template: T12
stage: 3
evidence_mode: not_applicable
status: draft
index: false
canonical: null # derive only after a real slug is approved
title_tag: null
meta_description: null
primary_persona: "Readers evaluating a verified offer"
acf:
  rmh_page_template_type: T12
  rmh_tool_slug: null
  rmh_deal_status: null
  rmh_deal_last_verified: null
  rmh_deal_starts: null
  rmh_deal_expires: null
  rmh_deal_source_url: null
  rmh_deal_source_confirmed: false
  rmh_deal_terms_preview: null
internal_links: [/deals/, /affiliate-disclosure/, /editorial-methodology/]
cta_map: [] # add placement/tool only after the applicable four affiliate statuses and link are verified
open_items: ["OWNER TO CONFIRM: actual provider, offer, source, eligibility, dates, status and approved destination", "Owner and staging QA before publish"]
```

## Required Gutenberg section order and field instructions

1. `[rmh_breadcrumbs]` and `[rmh_disclosure]` before any affiliate link.
2. H1: identify the real provider and offer only after verification. `[rmh_author_strip]` only when real author metadata exists.
3. H2 **Verification status and last verified date**: `[rmh_deal_stamp]`; never present stale or expired as active.
4. H2 **Offer**: describe the source-verified offer, price and billing conditions with source and date. Include a code only if a real one is verified. Affiliate destination, if permitted, must use `[rmh_affiliate_cta]` configured with a real registry tool slug, label and placement. Otherwise use only the plugin's independently confirmed nontracking fallback.
5. H2 **Eligibility and terms**: accurately document applicable geography, new/existing customer conditions, exclusions and renewal implications as verified from the provider.
6. H2 **Redemption steps**: provide the actual verified steps; never infer an automatic code or discount.
7. H2 **Alternatives if ineligible**: links to genuinely published relevant guides; omit unpublished links.
8. H2 **Sources and updates**: provider source, verification date and honest update history.
9. Add visible relevant Q&A only if sourced and truthful. Schema is a single graph matching visible facts; no fabricated Offer, price, rating, review or FAQ schema. Build the graph after the actual page exists, not from the skeleton.

Empty state: “Offer not verified.” Do not publish an unverified, expired-as-active or stale offer. The skeleton has no actual page, title, schema or affiliate CTA and is not a substitute for the complete verified deal copy.
