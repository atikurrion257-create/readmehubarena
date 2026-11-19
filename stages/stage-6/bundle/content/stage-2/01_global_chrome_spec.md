# Stage 2 — Global chrome: build specification

**Status:** design/implementation specification for the Hello Elementor child-theme layer. No theme was created or activated; no WordPress site was changed. Existing Stage 1 breadcrumbs and author-strip shortcodes are available in the staging-candidate plugin. Actual header, footer and drawer must be built and verified during the offline Stage 6 implementation, not represented as already deployed.

## 1. Shared requirements

- Semantic landmarks: one skip link to `#main-content`, `<header>`, primary `<nav aria-label="Primary navigation">`, `<main id="main-content">`, optional breadcrumb `<nav aria-label="Breadcrumb">`, and `<footer>`. Exactly one page H1 in main, not in the header. Respect WordPress `wp_head()`, `wp_body_open()`, `wp_footer()`, `body_class()`, and menu APIs in a child theme; avoid Elementor Pro and don't overwrite parent theme files.
- Use locked tokens: navy `#0F2A4A`, deep navy `#0A1C33`, blue `#1E3A8A`, orange `#FF7A00`, orange hover `#E66E00`, pale blue `#F0F5FA`, mint `#EFFBFA`, light orange `#FFF4EB`, neutral `#F8FAFC`, text `#0F172A` / `#334155` / `#64748B`, border `#E2E8F0`, white `#FFFFFF`; Inter body, Plus Jakarta Sans headings. Spacing 4/8/12/16/24/32/48/64/96px, radii 6/12/999px; widths 1200px wide, 1000px medium, 720px reading. Do not invent a logo: use text `ReadMeHub` linking to `/` until a verified asset is supplied. Orange buttons use dark text, not white.
- Core navigation and text render server-side without JavaScript. All focus states remain visible; no keyboard trap, autoplay, fake counts, ratings, endorsements, urgency or affiliate links in header/footer. Respect reduced motion and allow zoom and text reflow. All URLs are relative to `https://www.readmehub.com/`.

## 2. Desktop header and primary navigation

**Container:** white background, bottom border; centered max-width 1200px with horizontal padding 24px desktop, 16px mobile. One horizontal row: brand (left), navigation (center), accessible search control (right). Space with flex and allow links to wrap or switch to the mobile treatment before overlap. Avoid sticky behavior unless tested and explicitly selected; no LCP image required.

| Order | Visible label | URL | Availability |
|---|---|---|---|
| 1 | SaaS & AI Tools | `/saas/` | Enable once destination exists. |
| 2 | Web Hosting | `/hosting/` | Enable once destination exists. |
| 3 | Comparisons | `/comparisons/` | Enable once destination exists. |
| 4 | Guides | `/blog/` | Enable once destination exists. |
| 5 | About | `/about/` | Trust page, Stage 2 draft. |
| 6 | Deals | `/deals/` | **Omit until published**; verified offers and index conditions apply. |
| Utility | Search | `/search/` | Accessible text label; use native WordPress search form on destination, not an icon without a name. Search results remain noindex. |

Create a WordPress primary menu location; do not render links to destinations that do not exist or publish empty hubs merely to populate it. In previews, a draft destination may be annotated in the assembly checklist rather than presented as a live link.

## 3. Mobile drawer and no-JS behavior

At widths where the desktop row no longer fits, show brand, search link and a labeled `Menu` button. Use a native `<details><summary>Menu</summary>…</details>` disclosure as the progressive-enhancement baseline, or an equivalently accessible button + drawer with a fully functional no-JS fallback. Drawer has the same ordered links as desktop, logical tab order, clear close control when using a scripted drawer, Escape-to-close when scripted, focus returned to its trigger, and no offscreen focusable items while closed. Don't trap focus for a non-modal disclosure; if implementing a modal instead, use correct modal/focus management and return focus. Close on navigation. Test with keyboard, touch, screen reader, 200% zoom and JavaScript disabled. Targets: 48px minimum tap area and adequate spacing; no invented animation duration or shadow token.

## 4. Breadcrumb bar

Place `[rmh_breadcrumbs]` immediately inside main before the H1 where appropriate, not on the homepage. Use an ordered list of linked ancestors and a current-page text item; no duplicate H1. Keep wrapped lines accessible at 360px. The small-business path needs an intermediate draft `wordpress` parent for hierarchy; don't expose a link to an unpublished parent. **Blocker:** Stage 1 shortcode currently does not filter draft ancestors; fix/test in Stage 6 before using it on this path. Breadcrumb JSON-LD is emitted only when the plugin can detect the visible shortcode; verify that synced patterns and theme-rendered breadcrumbs match before publishing.

## 5. Author strip

Use `[rmh_author_strip]` adjacent to the title on articles. It displays only owner-supplied author and optional reviewer names plus the WordPress modified date. Real names, roles, credentials and policy link require owner verification; leave absent when unknown. Do not substitute a fictional persona or describe research as hands-on. For trust/legal pages, use the site identity and document dates only after owner approval; author byline is optional. Confirm dates reflect actual substantive updates before showing them.

## 6. Footer

One `<footer>` with a 1200px container, three titled nav groups, a plain-language disclosure and bottom identity/legal row. Headings are not page H1s. Links render only when their target exists.

| Group | Links |
|---|---|
| Explore | SaaS & AI Tools `/saas/`; Web Hosting `/hosting/`; Comparisons `/comparisons/`; Guides `/blog/`. |
| Trust | About `/about/`; Editorial Methodology `/editorial-methodology/`; Affiliate Disclosure `/affiliate-disclosure/`; Contact & Report an Error `/contact/`. |
| Legal | Privacy Policy `/privacy-policy/`; Terms of Service `/terms-of-service/`. |

Footer disclosure: “ReadMeHub may earn a commission when you use some links, at no extra cost to you. [Affiliate disclosure](/affiliate-disclosure/).” A footer disclosure does **not** replace an above-the-first-link disclosure on commercial pages. Bottom line: `ReadMeHub` and `[OWNER TO CONFIRM: copyright owner and year]`; do not infer a legal entity or start year.

## 7. Responsive assembly and QA

| Viewport | Header | Footer / reading content |
|---|---|---|
| 1440px | 1200px max, full row | Three link columns and disclosure; 720px reading width. |
| 1280px | Full row if it fits without overlap | Three columns; test wrap. |
| 768px | Switch to menu disclosure when full row cannot fit | Two columns if legible; then stack disclosure. |
| 390px | Brand, labeled search and Menu; full-width drawer | One column, links and disclosure readable. |
| 360px | Same as 390px, no clipped controls | One column, breadcrumb wraps, no horizontal page overflow. |

On staging inspect desktop and mobile DOM order, valid targets, no broken/duplicate IDs, contrast, focus, menu state and no-JS navigation. Recheck title, canonical, robots, XML sitemap and all dynamic widget caching. No visual test or performance benchmark was run in Stage 2.
