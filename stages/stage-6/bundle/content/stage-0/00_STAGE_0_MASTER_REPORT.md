# READMEHUB — STAGE 0 MASTER DELIVERABLE
**Date:** 2026-11-19 (UTC)  
**Active Skills:** `readmehub-context-orchestrator` + `readmehub-affiliate-strategist`  
**Contract:** `ReadMeHub_Unified_Master_Build_Prompt_v3.md`

---

# STAGE 0.1 — Repository Audit & Cleanup Checklist

**Audit Date:** 2026-11-19 (UTC)  
**Active Skills:** `readmehub-context-orchestrator` + `readmehub-affiliate-strategist`  
**Inspected Source Repository:** `https://github.com/atikurrion257-create/readmehubinstructionsfile`  
**Working Build Repository:** `https://github.com/atikurrion257-create/readmehubarena` (`arena/86565b86-readmehubarena`)  
*(Note: Deliverables are stored under `stages/stage-N/` rather than `build/` so Arena's turn-end git snapshot persists all files across turns.)*

---

## 1. Repository Inspection Summary

1. **Source Repository Structure:** `atikurrion257-create/readmehubinstructionsfile` contains **31 extracted Markdown source files** at the repository root (with original folder hierarchy encoded via `__`), plus 3 repository entry-point files (`AGENTS.md`, `README.md`, and `AI_AGENT_CONTEXT.md`). Its manifest (`00_README_AND_FILE_MANIFEST.md`) catalogues all 59 items from the original `ReadMeHub_Complete_Project_Files` archive.
2. **Canonical Workspace Sync:** All 13 clean canonical reference files (plus `ReadMeHub_Unified_Master_Build_Prompt_v3.md`) are persisted in `/references/` inside this workspace without any duplicate `*(1)` files, raw paste dumps, or quarantined third-party prompt variants.
3. **Missing Tier-2 Source File Flagged:** `ReadMeHub_WordPress_Production_Build_Blueprint_v1.xlsx` (Part 2 Conflict-Resolution Order #2) is **NOT present** in `readmehubinstructionsfile`, `readmehubarena`, or session uploads. Zero workbook values are guessed; workbook-dependent items (`/hosting/wordpress/small-business/` slug confirmation, stale `"Formalized"` spreadsheet cells, and the 38-row Keyword Universe table) are logged in the Conflict Log and Blockers list.
4. **Stage-1 Hands-On Testing Logs Check:** **Zero (`0`) dated Stage-1 testing logs, completed worksheets, or dated benchmark screenshots** exist anywhere in the repository. Per Part 5.2, all commercial pages default strictly to **Mode A (`mode_a_research` — Stage-0 Research Draft)** with `BLOCK PUBLISH` enforcement until dated Stage-1 worksheets are supplied.

---

## 2. Canonical Files Read & Verified (`KEEP` Set in `/references/`)

| Canonical Reference File | Repo Source Filename | Size (Bytes) | SHA-256 Prefix | Disposition |
|---|---|---:|---|---|
| `references/01_READMEHUB_PROJECT_CONTEXT.md` | `01_Project_Documents__Claude_Project_MD_Files__01_READMEHUB_PROJECT_CONTEXT.md` | 2,873 | `364a40ff8cc8…` | **READ & KEPT** (paired with `readmehub-project-file-corrections.md`) |
| `references/02_READMEHUB_DESIGN_SYSTEM.md` | `01_Project_Documents__Claude_Project_MD_Files__02_READMEHUB_DESIGN_SYSTEM.md` | 1,763 | `393423fd992b…` | **READ & KEPT** (primary reference token list) |
| `references/03_ELEMENTOR_IMPLEMENTATION_RULES.md` | `01_Project_Documents__Claude_Project_MD_Files__03_ELEMENTOR_IMPLEMENTATION_RULES.md` | 2,360 | `5957adda9577…` | **READ & KEPT** (paired with `readmehub-project-file-corrections.md`) |
| `references/04_WORDPRESS_ACF_CONTENT_MAP.md` | `01_Project_Documents__Claude_Project_MD_Files__04_WORDPRESS_ACF_CONTENT_MAP.md` | 2,195 | `7f5333fc5e21…` | **READ & KEPT** (confirms zero pre-existing ACF slugs) |
| `references/05_IMPLEMENTATION_LOG_AND_QA.md` | `01_Project_Documents__Claude_Project_MD_Files__05_IMPLEMENTATION_LOG_AND_QA.md` | 2,464 | `80a7dd3d5372…` | **READ & KEPT** (QA log target for Stage 7 handoff) |
| `references/06_CLAUDE_PROJECT_INSTRUCTIONS.md` | `01_Project_Documents__Claude_Project_MD_Files__06_CLAUDE_PROJECT_INSTRUCTIONS.md` | 3,421 | `cc72106ef3b9…` | **READ & KEPT** *(verified present despite stale note in `SKILL (1).md`)* |
| `references/07_PER_SECTION_TASK_PROMPT.md` | `01_Project_Documents__Claude_Project_MD_Files__07_PER_SECTION_TASK_PROMPT.md` | 2,147 | `5d5e217a9454…` | **READ & KEPT** (paired with `readmehub-project-file-corrections.md`) |
| `references/08_READMEHUB_PAGE_TEMPLATE_SCOPE.md` | `01_Project_Documents__Claude_Project_MD_Files__08_READMEHUB_PAGE_TEMPLATE_SCOPE.md` | 3,078 | `47a10521b5d7…` | **READ & KEPT** (baseline 17-item scope extended to 20 templates in v3) |
| `references/connector-tools (3).md` | `06_Additional_Project_Files__connector-tools (3).md` | 5,062 | `f6a2dbb75c7b…` | **READ & KEPT** (latest Royal MCP read/draft probe dated `2026-10-23`) |
| `references/readmehub-project-file-corrections.md` | `01_Project_Documents__readmehub-project-file-corrections.md` | 4,836 | `e5f88e9c50c3…` | **READ & KEPT** (owner confirmation `2026-10-23`: `www.readmehub.com` = PRODUCTION) |
| `references/readmehub-homepage-plan.md` | `01_Project_Documents__readmehub-homepage-plan.md` | 6,226 | `7b932bf34a2f…` | **READ & KEPT** (verified `2026-10-23` WP/plugin/kit runtime snapshot) |
| `references/ReadMeHub — Elementor Free-First Website Specification.md` | `01_Project_Documents__ReadMeHub — Elementor Free-First Website Specification.md` | 84,787 | `cb3fe055dea7…` | **READ & KEPT** (Tier-4 fallback architectural specification) |
| Canonical Skill Files (`SKILL.md`, `SKILL (1).md`, `SKILL (3).md`, `SKILL (4).md`) | `03_Elementor_and_Skills__SKILL*.md` (deduplicated) | 3,080–4,185 | Verified | **READ** (`wp-change-safety`, `readmehub-project-pack`, `design-system-to-elementor`, `elementor-qa`) |

---

## 3. Mandatory Repository Cleanup Checklist (`readmehubinstructionsfile`)

### A. DELETE — Exact Byte-for-Byte Duplicates & Superseded Connector Drafts
- `03_Elementor_and_Skills__SKILL (3)(1).md` (`3,080 B`, SHA-256 `35577c9d…` — exact duplicate of `SKILL (3).md`)
- `03_Elementor_and_Skills__SKILL (4)(1).md` (`3,745 B`, SHA-256 `32bd4251…` — exact duplicate of `SKILL (4).md`)
- `02_Master_Prompts__ReadMeHub_Master_Website_Build_Prompt(1).md` (`37,900 B`, SHA-256 `8bc59ec3…` — exact 2nd copy of `ReadMeHub_Master_Website_Build_Prompt.md`)
- `06_Additional_Project_Files__connector-tools (1).md` (`3,040 B`, SHA-256 `47904fdd…` — duplicate of `connector-tools.md`)
- `06_Additional_Project_Files__connector-tools (2)(1).md` (`4,339 B`, SHA-256 `b08e67d5…` — duplicate of `connector-tools (2).md`)
- *(Recommended)* `06_Additional_Project_Files__connector-tools.md` & `connector-tools (2).md` — superseded by `connector-tools (3).md`.

### B. ARCHIVE / QUARANTINE — Non-Claude Prompts, Raw Paste Dumps & Legacy Archives
- `02_Master_Prompts__Manus_AI_Native_Elementor_Transfer_Ready_Build_Prompt.md` & `02_Master_Prompts__Manus_AI_ReadMeHub_Full_Elementor_Build_Master_Prompt.md`
- `01_Project_Documents__ReadMeHub Research and Architecture Foundation.md` (Manus AI, `2026-09-06`)
- `ReadMeHub_ChatGPT_Deep_Research_Execution_Mode.txt`, `ReadMeHub_Gemini_Deep_Research_Execution_Mode.txt`, `ReadMeHub_Perplexity_Deep_Research_Execution_Mode.txt`
- `Pasted text(2).txt` & `Pasted text(3).txt`
- `05_Source_Archives/*.zip` (8 legacy ZIP bundles)
- Pre-v3 prompts (`ReadMeHub_Master_Website_Build_Prompt.md`, `ReadMeHub_Complete_Remaining_Website_Build_Prompt.md`, `ReadMeHub_2026_Claude_Final_Synthesis_Prompt.md`, `ReadMeHub_2026_Masterstroke_Research_Prompt.md`)

### C. Permanently Quarantined Fabricated Demo Metrics
- ❌ `"420+ B2B tools tested"`, `"3,800+ synthetic benchmarks"`, `"1,840+ reviews"`, fake author personas, unverified star ratings, and unverified discount codes.


---

# STAGE 0.2 — Locked Execution Manifest

**Audit Date:** 2026-11-19 (UTC)  
**Live Public Fetch Verified:** `2026-11-19` (`https://www.readmehub.com/` — confirmed title `"My Blog - My WordPress Blog"`, Hello Elementor skip link, default `"Hello world!"` archive; owner supplied WP Admin URL `https://www.readmehub.com/wp-admin/`)  
**Latest Authenticated Runtime Probe in Repo:** `2026-10-23` (`readmehub-homepage-plan.md`, `SKILL (1).md`, `connector-tools (3).md`, `readmehub-project-file-corrections.md`)  
**Current Session Connector Probe (`2026-11-19`):** Arena `wordpress` connector = `disconnected`; `elementor` connector = `unsupported`.

---

## 1. Environment, Runtime & Stack Manifest

| Dimension | Locked / Observed Value | Evidence Source & Verification Status | Operational Rule |
|---|---|---|---|
| **Primary Domain & WP Admin** | Site: `https://www.readmehub.com/` · Admin: `https://www.readmehub.com/wp-admin/` | Owner-supplied (`2026-11-19`) + `readmehub-project-file-corrections.md` (`2026-10-23`) — **CONFIRMED PRODUCTION** | `www.readmehub.com` is the **MAIN PRODUCTION** site (no separate `/stagging` site). All automated/manual writes target **`draft` status only (`R1` under `wp-change-safety`)**; never overwrite published pages or global settings without explicit `R3`/`R4` approval. |
| **Live Public Front-End State (`2026-11-19`)** | Title: `"My Blog - My WordPress Blog"` · `show_on_front=posts` · 1 default `"Hello world!"` post (`blog_public=0` on `2026-10-23` probe) | Verified live via `fetch_page("https://www.readmehub.com/")` on `2026-11-19` | Fix Site Title to **`ReadMeHub`** in Stage 1 (`Settings → General`). Keep empty hubs/archives `Draft + noindex` until publish thresholds in Part 4 are met. |
| **WordPress & PHP Version** | **WordPress `7.1.3`** · **PHP `8.2.34`** | `readmehub-homepage-plan.md` (`2026-10-23` probe; re-verify once `wordpress` connector is connected) | Meets WP 6.8+ / 6.9+ threshold for Elementor MCP & `WordPress/mcp-adapter`. |
| **Active Theme** | **Hello Elementor `3.5.1`** (`block_theme: false`) | `readmehub-homepage-plan.md` (`2026-10-23`) + `2026-11-19` `#content` skip-link verification | Deliver `hello-elementor-child` templates for header, mobile drawer, breadcrumbs, author strip, and footer (Part 7). No Elementor Pro Theme Builder. |
| **Elementor & Pro Status** | **Elementor Free `4.3.5`** · **Elementor Pro: NO** *(~90 `pro-elements` in 204-widget registry are inactive stubs; `dynamic_tags = 0`)* | `readmehub-homepage-plan.md` & `connector-tools (3).md` (`2026-10-23`) | Lock layout dialect to **Elementor Free Flexbox/Grid Containers** (never mix with V4 Atomic elements on one page). Zero Pro features; all affiliate links via `[rmh_affiliate_cta]`. |
| **ACF Edition** | **NOT INSTALLED on `2026-10-23` probe** *(overrides stale `ACF 6.8.9` in `01_`)* · **Target: ACF Free** | `SKILL (1).md` & `readmehub-homepage-plan.md` (`2026-10-23`) | **ACF Free Hard Gate:** No Repeater, Flexible Content, Gallery, or Options Pages. Model lists (`rmh_pros_list`, `rmh_cons_list`) as 1-item-per-line `textarea` fields; store global defaults in `readmehub-core-dynamic/inc/settings.php`; wrap reads in `function_exists('get_field')` + `get_post_meta()` fallback. |
| **Rank Math SEO** | **Rank Math SEO `1.0.280` (Free)** | `readmehub-homepage-plan.md` (`2026-10-23`) | Owns titles, meta descriptions, canonicals, robots, XML sitemap. `inc/schema.php` extends `rank_math/json_ld` into **one consolidated `@graph`** (fallback: single `wp_head` `@graph` only if Rank Math schema is disabled; **never both**). |
| **Xpro Addons** | **Xpro Elementor Addons `1.7.11` (Free)** + **Xpro Theme Builder `1.2.16` (Free)** (`0` layouts) | `readmehub-homepage-plan.md` (`2026-10-23`) | Verified on installed plugin list (permitted per Part 3), kept strictly secondary to native Elementor Free widgets + `[rmh_*]` shortcodes. |
| **Form Plugin** | **NONE INSTALLED** (`wpforms` widget stub registered without plugin) | `SKILL (1).md` (`2026-10-23`) — **BLOCKER** | Owner to confirm lean free form plugin (Fluent Forms Free / WPForms Lite / Contact Form 7) for `/contact/` (`T16`). |
| **Newsletter Provider** | **UNSELECTED** | Spec §2.2 & Part 7 — **BLOCKER** | `[rmh_newsletter]` ships with configurable endpoint/embed setting + safe accessible fallback until owner names provider. |
| **MCP / Arena Connector** | Arena `wordpress` connector = **`disconnected`** (probed `2026-11-19` via `list_connector_tools`). Site ran `Royal MCP 1.5.10` on `2026-10-23`. Target MCP stack: **Elementor MCP** (Core 4.3.0+) + **`WordPress/mcp-adapter`** (WP 6.9+). | Live Arena `list_connector_tools("wordpress")` (`2026-11-19`) + `connector-tools (3).md` + Part 2 | **Never use** archived `Automattic/wordpress-mcp`. User must connect **WordPress** inside Arena's Connected Apps UI (never share passwords/tokens in chat), or Stage 6 ships a complete offline import bundle + WP-CLI draft scripts. |
| **CPTs, Taxonomies & ACF Slugs** | Currently `0` custom CPTs, `0` custom taxonomies, `0` existing ACF slugs in `04_WORDPRESS_ACF_CONTENT_MAP.md` | `readmehub-homepage-plan.md` & `04_WORDPRESS_ACF_CONTENT_MAP.md` | Stage 1 creates `rmh_tool` CPT + `/saas/` (5 clusters) & `/hosting/` (2 clusters) + Part 7 canonical ACF Free field groups. Finance (`CAT-038–040`), Insurance (`CAT-041–043`), `best-vpn-for-streaming`, and `best-web-hosting-for-wordpress` stay on **Wave-3 HOLD**. |
| **Stage-1 Test Logs** | **NONE (`0` logs)** | Full repo audit (`2026-11-19`) | All commercial pages ship in **Mode A (`mode_a_research`)** with `BLOCK PUBLISH` gate until dated Stage-1 worksheets are completed. |

---

## 2. Design Tokens Manifest (Verbatim from `02_READMEHUB_DESIGN_SYSTEM.md` vs Missing)

- **Colors (15 reference tokens in `02_`, not yet set in live Elementor Kit post `6`):**
  - Brand navy `#0F2A4A` · Deep navy `#0A1C33` · Supporting blue `#1E3A8A` · Primary orange `#FF7A00` · Orange hover `#E66E00` · Light orange `#FFF4EB` · Pale blue `#F0F5FA` · Mint `#EFFBFA` · Purple `#F5F3FF` · Soft neutral `#F8FAFC` · Primary text `#0F172A` · Secondary text `#334155` · Muted text `#64748B` · Border `#E2E8F0` · White `#FFFFFF`
- **Fonts (`02_` reference tokens, Roboto currently active in live Kit):** `Inter` (body) · `Plus Jakarta Sans` (headings)
- **QA Breakpoints (`02_`):** `1440px` / `1280px` / `768px` / `390px` / `360px` (live Elementor Site Settings thresholds unverified)
- **Missing Numeric Tokens in `02_` (proposed only in `Elementor Free-First Website Specification.md` §8.2):**
  - Spacing scale (`4, 8, 12, 16, 24, 32, 48, 64, 96px`), radii (`6px / 12px / 999px`), content widths (`1200px / 1000px / 720px`), box-shadows (`elev-1 / elev-2`), active/disabled/focus button hexes, and WCAG AA contrast pairing on `#FF7A00` buttons (`#0F172A` dark text `8.18:1` vs `#FFFFFF` white text `2.58:1`). **Flagged for owner confirmation before Stage 1 lock.**

---

## 3. Verified Connector Tool Signatures (`connector-tools (3).md`)

- **Read-only (`TESTED`):** `royal_mcp_connection_health`, `wp_get_site_status`, `wp_get_plugins`, `elementor_list_widget_schemas`, `elementor_list_dynamic_tags`, `elementor_list_local_templates`, `elementor_get_page_outline`, `wp_get_post_meta`, `wp_get_global_styles` *(gotcha: returns WP core `#ff6900` / `800px` on classic themes, NOT Elementor Kit post `6`)*, `wp_get_taxonomies`, `wp_get_post_types`, `wp_get_posts`, `seo_audit_meta_tags`, `wp_create_preview_link`, `wp_verify_rendered_page`.
- **Draft Writes (`TESTED`):** `wp_create_page(status:"draft")` *(requires Elementor seeding before `elementor_add_widget`)*, `elementor_add_widget`, `mcp_list_recent_changes`.
- **Registered Writes:** `elementor_clone_page`, `elementor_apply_template_to_page`, `elementor_import_template`, `elementor_replace_text`, `elementor_replace_image`, `elementor_rebuild_post_content`, `wp_update_page`, `wp_update_post`, `wp_update_post_meta`, `mcp_undo_last_operation`.


---

# STAGE 0.3 — Source-of-Truth Conflict Log (Resolved per Part 2)

**Audit Date:** 2026-11-19 (UTC)

| # | Conflict Area | Local / Legacy Repo Source | Nov 2026 Baseline / Live Probe State | Locked Resolution |
|---|---|---|---|---|
| **C-01** | **Hosting L2 Small-Business Slug** | All other 6 L2 clusters use 2 segments (`/<silo>/<cluster>/`); Blueprint `.xlsx` is absent from repo. | v3 Registry #22 lists `/hosting/wordpress/small-business/` (3 segments, no `/hosting/wordpress/` hub) & #32 under it. | **Flagged for Owner Confirmation** before Stage 1 rewrite lock: keep `/hosting/wordpress/small-business/` verbatim vs normalize to `/hosting/small-business-wordpress/`. |
| **C-02** | **Schema Ownership (Rank Math vs Custom `@graph`)** | Rank Math auto-outputs JSON-LD; Gemini used `[rmh_schema_graph]` shortcode; Claude put `Review` + `reviewRating` on all reviews. | Shortcodes don't execute in `<head>`; duplicate graphs violate Part 9; Mode A has zero hands-on test logs. | `inc/schema.php` hooks `rank_math/json_ld` into **one consolidated `@graph`** (fallback: single `wp_head` `@graph` only if Rank Math schema is disabled — **never both**). Mode A = `Organization` + `WebSite` + `BreadcrumbList` + `Article`/`WebPage` only (zero `Review`, `reviewRating`, `AggregateRating`, or `Offer.price`). |
| **C-03** | **Informational Pillar URLs** | `08_` placed single articles under Blog; `Foundation.md` used `/guides/`. | v3 Registry #27 maps CON-010 to `/saas/project-management/how-to-choose-project-management-software/` + #9 `/blog/`. | **Silo URL wins when mapped in Registry** (Row #27 at silo URL); `/blog/` (`T4`) is the archive (`Draft + noindex` until ≥1 article is live); unmapped general guides use `/blog/<slug>/`. |
| **C-04** | **Legal Slugs** | Spec §3 used `/privacy/` and `/terms/`. | v3 Registry #6–#7 & Part 12 specify `/privacy-policy/` and `/terms-of-service/`. | **Locked to `/privacy-policy/` and `/terms-of-service/`.** |
| **C-05** | **Environment (`stagging` vs Live Production)** | `01_`, `03_`, `05_`, `06_`, `07_` cite `https://www.readmehub.com/stagging`. | Owner confirmed (`2026-10-23` & `2026-11-19`) `https://www.readmehub.com/` (`/wp-admin/`) is **MAIN PRODUCTION**. | Treat `www.readmehub.com` as `PRODUCTION`; enforce `wp-change-safety` draft-only (`R1`) operations. |
| **C-06** | **Installed Stack (`ACF 6.8.9` vs Live Probe)** | `01_` cited `Elementor 4.2.4` + `ACF 6.8.9`. | `2026-10-23` probe verified `Elementor Free 4.3.5` and **ACF NOT INSTALLED**. | Build strictly within **ACF Free** limits + safe `get_post_meta()` fallbacks in `readmehub-core-dynamic`. |
| **C-07** | **Affiliate Anchors & Closed Programs** | Spec §17 cited ClickUp as sole confirmed program; legacy prompts cited Ahrefs, Notion, Cloudways `10%`, Hostinger, Jasper commissions. | Part 2 Audit Corrections: Systeme.io (`60%` recurring, no `www.`) = #1 anchor; Ahrefs discontinued; Notion closed; Cloudways `$30 + 7%`; Hostinger deferred; Jasper `REQUIRES VALIDATION`. | Part 2 overrides all legacy files; `[rmh_affiliate_cta]` strips `www.` on `systeme.io` and blocks affiliate markup on non-active/unapproved records. |
| **C-08** | **PM Freelancer Slug & ClickUp vs Asana** | Longer freelancer slug + Wave-3 duplicate of `ClickUp vs Asana` in older files. | Part 2 retires both. | Locked to `/saas/project-management/best-for-freelancers/` (Stage 4) and single Wave-2 `/saas/project-management/clickup-vs-asana/` (Stage 5B). |
| **C-09** | **Affiliate Link Attributes** | Mixed `nofollow` / native Elementor Button links in older prompts. | Elementor Free Button cannot set `rel="sponsored"`. | 100% of affiliate links output via `[rmh_affiliate_cta]` with `rel="sponsored nofollow noopener"`. |
| **C-10** | **FAQ Rich Results** | Legacy prompts targeted Google SERP FAQ snippets. | Discontinued by Google **May 7, 2026**. | Keep 5–8 visible buyer Q&As for readers + AI answers; validate any `FAQPage` JSON-LD in Schema Markup Validator. |
| **C-11** | **Visual System & Dark Mode** | Spec §8/§10 deferred palette and required Light/Dark toggle. | v3 Part 0/Part 7 + `02_` lock Stitch **Audit Ledger Navy + Orange** + `SaaS & AI Tools · Web Hosting · Comparisons · Guides · About` nav. | Higher-priority v3 + `02_` win; dark-mode toggle deferred unless requested by owner. |
| **C-12** | **MCP Stack** | `Royal MCP 1.5.10` on `2026-10-23`; older guides cited `Automattic/wordpress-mcp`. | Part 2 locks **Elementor MCP (Core 4.3.0+) + `WordPress/mcp-adapter` (WP 6.9+)**. | Archived `Automattic/wordpress-mcp` banned. |
| **C-13** | **Gated YMYL & Head Terms** | Legacy synthesis prompts explored 13 broad categories. | Part 0 & Part 3 lock launch to `/saas/` (5 clusters) and `/hosting/` (2 clusters) only. | Personal Finance (`CAT-038–040`), Insurance (`CAT-041–043`), `best-vpn-for-streaming`, and `best-web-hosting-for-wordpress` remain on **Wave-3 HOLD**. |


---

# STAGE 0.4 — Corrected Affiliate Action Sheet & Governance Flags

**Audit Date:** 2026-11-19 (UTC)  
**Hard Gate Enforced:** `Program Status`, `Verification Status`, `Application Status`, and `Approval Status` are tracked as **four strictly separate fields**.

| Priority | Program | Action | Program Status | Verification Status | Application Status | Approval Status | Verified Terms & Payout | Cookie | Critical Notes |
|---|---|---|---|---|---|---|---|---|---|
| 🔴 **P0** | **Systeme.io** | **Create free affiliate ID now**; no `www.` | `active_open` | `verified` (`2026-09-03` → expires `2026-12-02`) | `not_applied` | `pending_owner_id` | **60% lifetime recurring** (post-VAT), permanent lead lock on free signup, **$30** min paid monthly on 10th (30d hold) | **Permanent lead lock** | `#1` Wave-1 ROI anchor (`https://systeme.io/?sa=YOURID`). **Never add `www.`** (use `www.` only on custom domains). |
| 🔴 **P0** | **Kinsta** | **Apply (open)** | `active_open` | `verified` (expires `2026-12-02`) | `not_applied` | `not_approved` | **$50–$500 upfront + 10% monthly recurring** (Managed WP) | **60d** | Wave-2 Hosting anchor (Rows #28, #29). |
| 🔴 **P0** | **SiteGround** | **Apply (open)** | `active_open` | `verified` (expires `2026-12-02`) | `not_applied` | `not_approved` | **$50/$75/$100+ tiered CPA**, weekly payouts, **$0** min, 90d fraud withhold | **60d** (last-click) | Small-Business WP anchor (Row #32). |
| 🟡 **P1** | **ClickUp** | **Apply via PartnerStack** | `active_network` | `verified` (expires `2026-12-02`) | `not_applied` | `not_approved` | Up to **$25 Tier-1 CPA** (US/UK/CA/AU/NZ), **$0.75–$12.50 India**, 25% recurring at volume tiers | **90d** | Freelancer/agency **authority wedge** (Rows #24–#26, #34). |
| 🟡 **P1** | **Semrush** | **Apply once Wave-1 is live (Impact)** | `active_network` | `verified` (expires `2026-12-02`) | `not_applied` | `not_approved` | **$200 CPA** (SEO/Traffic), **$100** (AI Visibility/Content/Ads), **$50** (Social/Local), **$10** qualifying trial, **$150** starter bonus, loyalty tiers | **120d** (last-click) | Feature **Semrush One ($199.95/mo)** on Rows #35, #36. |
| 🟡 **P1** | **WP Engine** | **Apply (approval required)** | `active_approval_required` | `verified` (expires `2026-12-02`) | `not_applied` | `not_approved` | **$100 Lite / $200+ minimum or first month** | **180d** | Separate from Agency Partner Program (Rows #28, #30). |
| 🟢 **P2** | **Cloudways** | **Apply (Hybrid $30 + 7%)** | `active_open` | `verified` (expires `2026-12-02`) | `not_applied` | `not_approved` | **Hybrid $30 + 7% lifetime** (Slab/Custom), **$100** min payout | **60d** | **Never cite legacy 10%** (Rows #28, #31). |
| 🟢 **P2** | **NordVPN** | **Apply via Impact — build NO VPN pages** | `active_network` *(HOLD)* | `verified` (expires `2026-12-02`) | `not_applied` | `not_approved` | Per Impact terms | Per Impact | **Wave-3 HOLD:** build zero VPN pages (`best-vpn-for-streaming` gated). |
| ⚪ **DEFER** | **Hostinger** | **DEFER until baseline traffic exists** | `deferred_until_traffic` | `verified` (expires `2026-12-02`) | `deferred` | `not_approved` | Up to **40%** | **30d** | Eligibility note only in Row #32; no affiliate link yet. |
| ❌ **NONE** | **Ahrefs** | **DO NOT APPLY (discontinued)** | `discontinued` | `verified_closed` | `n_a` | `n_a` | **$0 — Discontinued** | N/A | Review objectively in Row #36; zero affiliate links. |
| ❌ **NONE** | **Notion** | **DO NOT APPLY (closed)** | `closed_to_new` | `verified_closed` | `n_a` | `n_a` | **$0 — Closed to new affiliates** | N/A | Monetize alternatives via ClickUp (+ verified Asana/Monday.com). |
| ⚠️ **COND.** | **Jasper / HubSpot / Asana / Monday.com / Surfer SEO / SE Ranking** | **Verify live terms before monetizing** | `requires_validation` | `REQUIRES VALIDATION` | `not_applied` | `not_approved` | **Do NOT state commission figures** until verified | `REQUIRES VALIDATION` | `[rmh_affiliate_cta]` outputs plain non-affiliate links until verified & approved. |

### Mandatory Governance Flags
- **⏰ 13-Day Re-Verification Deadline (`2026-12-02`):** Baseline `2026-09-03` expires **`2026-12-02` (13 days from `2026-11-19`)**. Re-verify all rates, cookies, and vendor prices on or before `2026-12-02`.
- **🧹 Stale `"Formalized"` Cells Cleanup:** Reset any stale `"Formalized"` status where `Application Status = Not Applied` or `Unknown`.
- **📉 38-vs-50 Keyword Universe Gap:** Only **38 populated rows** exist against the **50-candidate target** (**12-row gap**), and no SEO CSV export is attached (`REQUIRES SEO TOOL VALIDATION`).


---

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
