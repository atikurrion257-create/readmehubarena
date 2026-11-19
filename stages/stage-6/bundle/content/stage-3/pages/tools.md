---
url: "/tools/"
template: "T2"
stage: 3
evidence_mode: "not_applicable"
status: "draft"
publish_gate: "BLOCK PUBLISH until at least three published tool entries, destinations and QA"
index: false
target_index_after_gate: true
canonical: "https://www.readmehub.com/tools/"
title_tag: "Tools Directory | ReadMeHub"
meta_description: "Browse software and hosting tools by category and find published reviews in the ReadMeHub directory."
primary_persona: "Readers comparing software and hosting"
acf: {"rmh_page_template_type": "T2"}
internal_links: ["/saas/", "/hosting/", "/editorial-methodology/"]
cta_map: []
open_items: ["Owner approval, published destination checks and staging QA required"]
---

## Elementor container tree

1. Section C1: Shortcode `[rmh_breadcrumbs]` (D); Heading H1; Text Editor introduction.
2. Section C1: Heading H2 “Find a tool”; Shortcode `[rmh_tool_directory]` (D), including filters, cards, empty state and pagination.
3. Section C1: Text Editor category and methodology links.

Apply [shared assembly settings](../01_elementor_assembly.md); D means Element Caching = Inactive. The body below is full copy; conditional components are shown only when their conditions are satisfied.

## Page copy

[rmh_breadcrumbs]

# Tools Directory

Explore software and hosting tools by category. A directory entry is not a ranking, and a review link appears only when its corresponding review is published.

## Find a tool

[rmh_tool_directory]

If no results match: “No tools match these filters. Reset filters or browse by category.” Filter labels are Silo and Cluster, with Filter and Reset filters controls. Cards show an available logo, tool name, category and, when a published review exists, Read review. Directory cards do not contain affiliate links. Pagination uses Previous and Next when applicable.

Browse [SaaS and AI tools](/saas/) or [web hosting](/hosting/). Read [how we evaluate](/editorial-methodology/).


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
      "@id": "https://www.readmehub.com/tools/#webpage",
      "url": "https://www.readmehub.com/tools/",
      "name": "Tools Directory"
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
          "name": "Tools Directory"
        }
      ]
    }
  ]
}
```
