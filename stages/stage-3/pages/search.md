---
url: "/search/"
template: "T13"
stage: 3
evidence_mode: "not_applicable"
status: "draft"
publish_gate: "BLOCK PUBLISH pending native-search routing and type/filter implementation and QA"
index: false
canonical: "https://www.readmehub.com/search/"
title_tag: "Search | ReadMeHub"
meta_description: "Search published ReadMeHub software and hosting guides by topic, product or task."
primary_persona: "Readers comparing software and hosting"
acf: {"rmh_page_template_type": "T13"}
internal_links: ["/saas/", "/hosting/"]
cta_map: []
open_items: ["Owner approval, published destination checks and staging QA required"]
---

## Elementor container tree

1. Section C1: Shortcode `[rmh_breadcrumbs]` (D); Heading H1; Text Editor intro.
2. Section C1: Heading H2 “Search the site”; native WordPress server-rendered search form, type filters, result cards, no-results and pagination integration (D).
3. Section C1: Text Editor fallback category links.

Apply [shared assembly settings](../01_elementor_assembly.md); D means Element Caching = Inactive. The body below is full copy; conditional components are shown only when their conditions are satisfied.

## Page copy

[rmh_breadcrumbs]

# Search ReadMeHub

Search published guides and comparisons by product, category or task.

## Search the site

Search label: “Search ReadMeHub”. Submit button: “Search”. Keep the current query in the field after submission. Filter by All, Tools, Articles, Comparisons or Deals where a result type exists; return only published, public results and preserve the query while paging.

Loading: “Searching…” No results: “No results found. Try a broader term, check the spelling, or browse a category.” Explore [SaaS and AI tools](/saas/) or [web hosting](/hosting/). Each result card shows its type, title and a short excerpt. Pagination labels: Previous and Next.


## JSON-LD @graph

Schema target only; use one provider, never paste a duplicate graph. Breadcrumbs must match the actual visible trail; the basic graph below must be extended for published intermediate ancestors before use.

```json
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "Organization",
      "@id": "https://www.readmehub.com/#organization",
      "name": "ReadMeHub",
      "url": "https://www.readmehub.com/"
    },
    {
      "@type": "WebSite",
      "@id": "https://www.readmehub.com/#website",
      "name": "ReadMeHub",
      "url": "https://www.readmehub.com/"
    },
    {
      "@type": "WebPage",
      "@id": "https://www.readmehub.com/search/#webpage",
      "url": "https://www.readmehub.com/search/",
      "name": "Search ReadMeHub"
    },
    {
      "@type": "BreadcrumbList",
      "itemListElement": [
        {
          "@type": "ListItem",
          "position": 1,
          "name": "Home",
          "item": "https://www.readmehub.com/"
        },
        {
          "@type": "ListItem",
          "position": 2,
          "name": "Search ReadMeHub"
        }
      ]
    }
  ]
}
```
