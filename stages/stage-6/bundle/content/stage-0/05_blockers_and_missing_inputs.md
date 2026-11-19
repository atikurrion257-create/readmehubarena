# STAGE 0.5 — Missing Inputs, Blockers & Gate Checklist

**Audit Date:** 2026-11-19 (UTC)

## 1. Missing Inputs & Active Blockers

1. **B-01 — Design Tokens Lock:** Confirm whether Stage 1 should lock **`#0F2A4A / #FF7A00 / #F0F5FA / #EFFBFA / #FFF4EB / #334155`** (plus `#0A1C33`, `#1E3A8A`, `#E66E00`, `#F5F3FF`, `#F8FAFC`, `#0F172A`, `#64748B`, `#E2E8F0`, `#FFFFFF`, **`Inter` + `Plus Jakarta Sans`**, and Spec §8.2 `4–96px` spacing, `6px / 12px / 999px` radii, `1200px / 1000px / 720px` widths, AA-safe button contrast).
2. **B-02 — Row #22 & #32 Hosting Slug:** Confirm **`/hosting/wordpress/small-business/`** verbatim (with safe rewrite handling for `/hosting/wordpress/`) vs **`/hosting/small-business-wordpress/`**.
3. **B-03 — Self-Hosted WP Admin vs Arena `wordpress.com` Connector (`MCP Offline Mode Confirmed`):** Admin URL `https://www.readmehub.com/wp-admin/` is self-hosted WordPress.org (`"My Blog - My WordPress Blog"` verified live `2026-11-19`), whereas Arena's built-in `wordpress` connector uses `wordpress.com` OAuth (`wordpress-org` and `royal-mcp` return `unsupported`). **Do NOT install Jetpack** just to bridge `wordpress.com` (preserves Anti-Bloat CWV budget). Stage 6 is locked to the **Export-Ready `/wp-admin/` Upload Bundle** (`.zip` plugin & child theme, `acf-export-readmehub.json`, Elementor JSON templates, WXR XML + WP-CLI draft importer).
4. **B-04 — Affiliate IDs & Approvals:** Systeme.io `sa=YOURID` + partner approvals for Kinsta, SiteGround, ClickUp, Semrush, WP Engine, Cloudways (+ validation for Jasper/HubSpot/Asana/Monday.com).
5. **B-05 — Stage-1 Hands-On Testing Logs:** `0` logs exist; all commercial pages ship as **Mode A (`mode_a_research`)** with `BLOCK PUBLISH` status until Stage-1 worksheets are completed.
6. **B-06 — Blueprint `.xlsx` & SEO Export:** `ReadMeHub_WordPress_Production_Build_Blueprint_v1.xlsx` and SEO CSV exports not attached (`38-vs-50` keyword gap; `REQUIRES SEO TOOL VALIDATION`).
7. **B-07 — Newsletter Provider:** ESP/embed unselected for `[rmh_newsletter]`.
8. **B-08 — Free Form Plugin:** Form plugin unselected for `/contact/` (`T16`).
9. **B-09 — Author / Reviewer Identity:** Real names, roles, and verifiable credentials not yet supplied.
10. **B-10 — Legal Entity Details:** Entity name, jurisdiction, contact email, postal address, and analytics/cookie stack bracketed `[OWNER TO CONFIRM: …]` for `T18`/`T19`.
