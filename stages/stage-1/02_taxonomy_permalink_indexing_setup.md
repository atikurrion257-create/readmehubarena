# Stage 1 — taxonomy, permalink and indexing specification

No site setting or page has been changed. The plugin defines an internal `rmh_tool` post type plus non-public `rmh_silo` and `rmh_cluster` taxonomies. One-click seeding creates two silos, seven cluster terms and 17 **draft** tool records; it does not create or publish hub pages. Finance, Insurance and the gated broad VPN/hosting head terms remain on HOLD.

| Silo | URL | Cluster | Term slug | Canonical hub URL |
|---|---|---|---|---|
| SaaS & AI Tools | `/saas/` | Project Management | `project-management` | `/saas/project-management/` |
| SaaS & AI Tools | `/saas/` | Email Marketing & Funnels | `email-marketing` | `/saas/email-marketing/` |
| SaaS & AI Tools | `/saas/` | SEO & Marketing | `seo-marketing` | `/saas/seo-marketing/` |
| SaaS & AI Tools | `/saas/` | AI Writing | `ai-writing` | `/saas/ai-writing/` |
| SaaS & AI Tools | `/saas/` | CRM | `crm` | `/saas/crm/` |
| Hosting | `/hosting/` | Managed WordPress | `managed-wordpress` | `/hosting/managed-wordpress/` |
| Hosting | `/hosting/` | Small Business WordPress | `small-business-wordpress` | `/hosting/wordpress/small-business/` |

## Permalinks and intermediate segment

Use evergreen, non-dated URLs and real hierarchical WordPress pages for the registry paths; assign `/hosting/wordpress/small-business/` a parent page with slug `wordpress` under `hosting`. The intermediate `wordpress` page may remain draft; the plugin does **not** redirect that path. Do not create a redirect until existing routing, canonicals and collision risks are inspected. The plugin adds explicit rules for the cluster and one child segment and flushes rewrites on activation. On a disposable WP 7.1.3 Playground, `get_page_by_path` and a `WP_Query` resolved the published child under a draft intermediate; HTTP routing, canonicals and the existing production hierarchy remain untested. If the intermediate parent is missing, the rewrite alone cannot manufacture the hierarchy.

Use `/blog/<slug>/` for ordinary informational posts unless the registry specifies a silo URL. Choose the post permalink structure during Stage 6 after inspecting existing URLs; pages use their hierarchy independently. Do not flush/reconfigure production rewrite rules without change approval and a rollback plan.

## Part 4 indexing gates

| Case | Intended behavior |
|---|---|
| Search, 404, tags, date and author archives | noindex. |
| `/search/`, T13, T20 | noindex. |
| T2 `/tools/` | noindex until ≥3 published `rmh_tool` entries. |
| T4 `/blog/` | noindex until ≥1 published T5. |
| T7 `/comparisons/` | noindex until ≥1 published T8. |
| T11 `/deals/` | noindex until ≥1 currently verified active T12 offer. |
| T6 hubs | noindex when there are no directly published page children; zero-child hubs should remain drafts. Review the special draft intermediate hierarchy explicitly. |
| `{{VERIFY-` in post content | noindex; the editorial publish gate also requires manual QA. |
| Draft/non-published objects | noindex and not intentionally added to sitemaps. |

Rank Math robots and sitemap hooks plus core `wp_robots` fallback implement these checks. Rank Math 1.0.280 was exercised locally for an empty tools page and a clean page, but not every archive, hub, sitemap provider, canonical, or conflict with existing plugins. Indexing is not equivalent to publish authorization. Commercial Mode A and unresolved verification items remain **BLOCK PUBLISH** under the master prompt; manual Stage 7 QA is required.

## Site Title

The requested target is **ReadMeHub**. Activation does not change the title or tagline. After explicit approval of this live setting, use WordPress Settings → General → Site Title, or the plugin's explicit title action / `wp rmh fix-site-title`. These change `blogname` only and preserve the existing tagline. Verify the actual site first, make a backup, record the old value and retain a rollback path. No title change was applied here.
