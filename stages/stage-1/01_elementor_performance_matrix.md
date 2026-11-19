# Stage 1 — Elementor performance and caching matrix

**Scope:** implementation specification for the confirmed Elementor Free 4.3.5 stack, not a record of changes to the site. Labels and cache-control behavior below were checked against the 4.3.5 GitHub source and an isolated PHP widget test; the live admin UI and browser output were not tested. Use a staging site and take a backup before changing global settings.

| Setting | Requested target | Source / operator check |
|---|---|---|
| Optimized Image Loading | Enable; verify the actual hero LCP image has `fetchpriority="high"`, below-fold images have `loading="lazy"` | Elementor → Settings → Performance; inspect rendered HTML. Do not lazy-load the LCP image. |
| Optimized Gutenberg Loading | Enable | Elementor → Settings → Performance; check block styles remain intact. |
| Lazy Load Background Images | Enable | Elementor → Settings → Performance; ensure the first/LCP background is not delayed. |
| Load Google Fonts Locally | Enable; use Inter body and Plus Jakarta Sans headings | Performance; check fonts are actually locally hosted and licensed/configured. |
| Element Cache | Enabled globally, choose an appropriate TTL in Performance | Every dynamic instance below must be Inactive. Source calls the setting **Element Cache**, not the earlier notes' Advanced-tab label. |
| Optimized Markup | Active when available | Elementor Features/Experiments; verify pages after changes. |
| Inline Font Icons | Active when available | Elementor Features/Experiments; verify icon output. |
| Content layout | Flexbox/Grid containers, ≤3 nested levels per section | Elementor Free; do not mix Atomic elements and containers on one page. |

## Explicit Inactive list

Set each instance's **Advanced → Cache Settings → Inactive** (the source control `_element_cache` uses `yes` for Inactive) for:

- Every **Shortcode** widget, including `[rmh_disclosure]`, `[rmh_evidence_stamp]`, `[rmh_verdict_box]`, `[rmh_pros_cons]`, `[rmh_pricing_stamp]`, `[rmh_pricing_table]`, `[rmh_comparison_matrix]`, `[rmh_affiliate_cta]`, `[rmh_dynamic_cluster_grid]`, `[rmh_tool_directory]`, `[rmh_deals_list]`, `[rmh_deal_stamp]`, `[rmh_faq]`, `[rmh_author_strip]`, `[rmh_author_box]`, `[rmh_newsletter]`, `[rmh_breadcrumbs]` and `[rmh_tool_card]`.
- Every **Text Editor** widget containing any shortcode, dynamic field, price, date, offer or geo-specific copy.
- Every **Heading**, **Image**, **Icon Box**, **Button** or other widget with dynamic tags, per-page ACF values, dates or geo offers. Never use a native Button as an affiliate link: use the CTA shortcode.
- Any approved Xpro widget containing dynamic query data, ACF values, shortcodes, prices or dates, including post grids, data tables and price boxes. Do not assume a particular addon widget exists until checked on staging.

The plugin also marks settings containing `[rmh_` as dynamic through Elementor's `elementor/element/is_dynamic_content` filter. In the 4.3.5 source, an explicit widget cache choice is checked **before** this filter; an explicit Active setting can bypass it. Therefore the filter is defense-in-depth, **not a substitute for Inactive**. Full-page/server/CDN caches require separate invalidation or bypass for changing affiliate/offer output.

## Responsive and accessibility checks

| Width | Required check |
|---|---|
| 1440px | Wide container ≤1200px; no clipped navigation or cards. |
| 1280px | Container ≤1200px; check column wrapping. |
| 768px | Grids adapt; matrix remains keyboard-scrollable with sticky first column. |
| 390px | One-column layouts; touch targets and CTA/disclosure visible. |
| 360px | No horizontal page overflow except intentional scroll regions; labels remain visible. |

Keep reading content around 720px and medium sections ≤1000px. Use spacing `4/8/12/16/24/32/48/64/96px`, radii `6/12/999px`. Primary orange buttons use dark text (white on orange fails AA). Confirm keyboard focus, semantics, zoom and contrast with real content. Targets are LCP ≤2.5s, INP ≤200ms and good CLS; **no measurement has been performed**. Do not install Jetpack or another mega-addon for this build.
