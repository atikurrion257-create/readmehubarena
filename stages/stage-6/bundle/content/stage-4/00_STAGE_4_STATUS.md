# Stage 4 — Wave 1 offline content status

**Stage 4 content and worksheets are complete as blocked research drafts, not publishable pages.** No live WordPress changes, account creation, hands-on product testing or staging/browser testing were performed. Unverified facts are explicitly flagged rather than invented.

## Page Files, in requested order

1. [Systeme.io review — T3](pages/systeme-io-review.md)
2. [ClickUp review — T3](pages/clickup-review.md)
3. [Best project management software for freelancers — T9](pages/best-for-freelancers.md)
4. [ClickUp alternatives — T10](pages/clickup-alternatives.md)
5. [How to choose project management software — T5](pages/how-to-choose-project-management-software.md)

Each has YAML frontmatter, a Mode A research header, full draft copy, headings, buyer questions, internal links, a Page File ACF payload, CTA map and a single target schema graph. Commercial pages have a disclosure shortcode above affiliate CTA shortcodes; the informational guide has no sales CTA. Shortcodes are staging-candidate components, not proof of live output. No ratings, observed test results or invented SEO metrics are included.

## Supporting deliverables

- [First-party source audit](01_source_audit.md) — vendor pages accessed 2026-11-19; clarifies what could and could not be verified.
- [Systeme.io Free testing worksheet](worksheets/systeme-io.md) and [ClickUp Free Forever testing worksheet](worksheets/clickup.md) — proposed workflows, intentionally uncompleted.
- [Wave-2/Mode B evidence trigger checklist](02_mode_b_trigger_checklist.md).
- [Blockers and full price-token inventory](03_blockers_and_price_inventory.md): 44 file/token pairs, plus approvals, links, components and deployment blockers.
- [Static QA script](tests/validate.py) and [results](tests/static-results.json).

## QA and exceptions

**66/66 static checks passed**, including registry URL/template, YAML, noindex/draft, SEO lengths, heading hierarchy, 5–8 buyer Q&As, evidence mode, CTA ordering and map, graph parsing, token inventory and approximate body-word minimums. Approximate body words: Systeme.io 2,224; ClickUp 2,227; freelancer shortlist 2,504; alternatives 2,004; selection guide 1,828. Counts depend on the script's treatment of Markdown, and are not a quality or evidence test. Re-run with PyYAML installed: `python3 stages/stage-4/tests/validate.py`.

Fetched first-party pages supplied some dated free-plan facts, but scraped billing toggles did not establish a complete monthly/annual introductory and renewal schedule. Those 44 price-token instances must be resolved before publication. The ClickUp Free time-tracking discrepancy also needs vendor clarification. Affiliate application/approval and the real Systeme.io ID remain unconfirmed; fallback links must be independently verified as nontracking. The Systeme.io cluster has only one other registered sibling; do not fabricate a second to satisfy the link rule.

The Stage 1 verdict-box implementation can insert a CTA before proof when a tool resolves; these drafts intentionally use written verdicts and place CTAs after proof. The dynamic price stamp/table is withheld until the relevant prices are genuinely verified. Actual Gutenberg/shortcode rendering, disclosure placement, populated ACF, schema, responsive layout, accessibility, links and cache behavior still require staging QA. Earlier-stage legal, breadcrumb and archive blockers remain open. The static graph examples are specifications, not extra scripts to deploy alongside Rank Math.

## Gate

Await **PROCEED TO STAGE 5**. Release remains blocked until the outstanding evidence, pricing, affiliate, editorial and staging checks are resolved.
