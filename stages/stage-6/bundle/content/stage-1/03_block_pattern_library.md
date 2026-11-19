# Stage 1 — Gutenberg pattern library

## Actual deliverables and provisioning

The six full **structural markup** files are `patterns/T3-review.html`, `T5-informational.html`, `T8-comparison.html`, `T9-roundup.html`, `T10-alternatives.html`, and `T12-deal.html`. Copies are bundled inside the plugin. The explicit `wp rmh provision-synced-patterns` command creates six published `wp_block` records (synced patterns); it never runs during activation and preserves existing marked records on sequential re-runs. It creates persistent shared WordPress records, so use on disposable staging first and only with approval. Actual provision/re-run and `core/block` reference rendering passed a local WordPress test. Editing/detaching in the browser editor remains untested.

A synced pattern is shared globally: **detach before inserting page-specific facts or headings**. Each markup file is a template structure, not a finished article. Blank-argument shortcodes remain empty until configured with verified data. Commercial content must be fully written later; do not publish these structures as pages. Template output is WordPress Block Editor, not a single Elementor HTML widget.

The plugin also registers ordinary **unsynced** component patterns: disclosure, pros/cons, FAQ, newsletter and author strip. They are independent inserted copies; only the explicit `wp_block` records above are synced.

## Section order and assembly

| Template | Structural order |
|---|---|
| T3 review | Breadcrumbs → disclosure → H1/author/pricing stamp → evidence → quick verdict → fit/skip → workflows → features/limits → sourced pricing → pros/cons → after-proof CTA → alternatives → final verdict/CTA → visible FAQ → sources. Add CTA shortcodes at the two indicated editorial positions; only use verified records. |
| T5 informational | Breadcrumbs → H1/author → optional contents → prose → contextual soft internal links → sources → three related items. No sales banners. |
| T8 comparison | Disclosure → persona decision → matched methodology → matrix → workflow comparison → persona choices/paired CTAs → final verdict/paired CTAs → FAQ. |
| T9 roundup | Disclosure → persona criteria and selection rubric → matrix → each individual pick/qualified CTA → total cost → buying guide/final verdict → FAQ. |
| T10 alternatives | Disclosure → switching reasons → alternatives grouped by relevant triggers → matched-criteria comparison → migration → verdict → FAQ. |
| T12 deal | Disclosure → verification status/date (`rmh_deal_stamp`) → offer → eligibility/terms → redemption → alternatives → sources. Never invent codes or timers. |

Commercial templates require the disclosure above the first affiliate link; the CTA shortcode additionally emits an adjacent disclosure as defense in depth. Configure `tool` and placement for each affiliate CTA, and use only verified, approved records. Add complete first-party sources, internal links and 5–8 visible commercial Q&As during content stages. Mode A remains BLOCK PUBLISH pending evidence and QA. Price/date/source fields and all empty states need editorial review.

## Empty states

- Cluster grid: “No published pages in this cluster yet. Check back soon — new reviews are added after verification.”
- Tools: “No tools match these filters. Reset filters or browse by category.”
- Deals: “No verified deals right now. We only list offers we have checked on the vendor's own site.”
- Unverified single deal: “Offer not verified.”
- Newsletter provider: blocked until the owner identifies a provider and endpoint; do not collect addresses before consent/privacy review.
