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
