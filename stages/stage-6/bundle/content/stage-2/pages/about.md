---
url: /about/
template: T14
stage: 2
evidence_mode: not_applicable # Trust/legal page; not a product evaluation.
status: draft
publish_gate: BLOCK PUBLISH — identity and policy implementation require owner review
index: true
canonical: https://www.readmehub.com/about/
title_tag: "About ReadMeHub | Evidence-Led Guides"
meta_description: "Learn how ReadMeHub approaches software and hosting guides, separates editorial decisions from affiliate links, and handles corrections."
primary_persona: "Readers choosing software and web hosting"
acf:
  rmh_page_template_type: T14
internal_links: [/editorial-methodology/, /affiliate-disclosure/, /contact/, /saas/, /hosting/]
cta_map: []
open_items: ["OWNER TO CONFIRM: site operator and real author identities", "OWNER TO CONFIRM: editorial policy and corrections workflow are operational", "Links to draft destinations require deployment coordination"]
---

[rmh_breadcrumbs]

# About ReadMeHub

ReadMeHub is a guide to choosing software and web hosting with a clearer view of the tradeoffs. Its purpose is to help readers understand what a product may be useful for, what it may cost, and where its limits matter before they decide whether to use it.

A product can be a sensible choice for one person and a poor fit for another. A free plan may be enough for an individual but too restrictive for a team. An introductory hosting price may look affordable until the renewal rate or resource limits are considered. We want our guides to make those distinctions visible rather than present every product as the right answer for everyone.

## Who our guides are for

ReadMeHub is intended for individuals, small teams and businesses comparing tools for a real task. That task might be organising projects, sending email, researching search visibility or choosing a place to host a WordPress site. Readers may be exploring a category for the first time or checking whether an existing tool still fits their needs.

Our [SaaS and AI tools](/saas/) and [web hosting](/hosting/) sections are intended to organise guides by the decision a reader is making. A category page can provide context and lead to individual reviews, comparisons and practical guides as those pages become available. An empty or unpublished section should not be treated as evidence that a product has been reviewed.

## How we approach an evaluation

Our editorial approach begins with the question a reader needs answered. We look for relevant first-party documentation, current vendor pricing pages and product information, then identify the features and restrictions that affect that question. Where a claim depends on a price, plan, eligibility rule or other changeable detail, the source and verification date should accompany it.

There is an important distinction between researching a product and using it. A research-based evaluation describes documented capabilities and their relevance; it does not become a hands-on review simply because the documentation is detailed. A hands-on label requires a dated record of the plan, workflows and observations, with supporting evidence. A missing test does not become an implied test, benchmark or score.

The [Editorial Methodology](/editorial-methodology/) explains the proposed evidence labels, selection criteria, pricing checks and correction process in more detail. It also explains the limits of those methods. The policy is a standard to verify against the published page, not proof that a particular product has already been tested.

## How ReadMeHub may earn money

Some links may be affiliate links. If a reader uses an eligible link and completes a qualifying action, ReadMeHub may receive a commission; the reader does not pay an additional charge because of that commission. Not every product mentioned has an affiliate relationship, and a product can be discussed without a paid link.

The intended editorial rule is that commissions do not buy a ranking or change a verdict. A relevant limitation should remain visible even when a link could earn a commission. We explain the relationship in the [Affiliate Disclosure](/affiliate-disclosure/), and a commercial page should disclose it above its first affiliate link as well as in the footer.

An affiliate link is not a guarantee that a particular plan, offer or price remains available. Vendor terms can change, and approval or eligibility can differ by location. Readers should check the current terms with the provider before purchasing.

## What we do not want a guide to imply

A guide should not invent a test, a benchmark, a review score or a reader testimonial. It should not present a temporary introductory price as the permanent cost, claim an offer is verified when it is not, or turn a documented feature into an observation of a workflow we have not actually performed.

We also avoid pretending that a list can remove the need for judgment. The useful question is not simply “Which product is best?” but “Which product is suited to this task, budget and set of constraints?” A recommendation should make its intended reader clear and explain who should consider another option.

## Corrections and questions

Information about software and hosting can change. If a page contains a factual error, a stale price or an unclear distinction between research and testing, please use [Contact and Report an Error](/contact/) and identify the page and the issue. A useful report includes the page URL, the sentence or table entry in question and, when available, a link to the provider's current documentation.

Our intended correction process is to review the report, check the relevant source and update or qualify the page when a correction is warranted. We do not promise a response time that has not been confirmed. If a claim cannot be verified, it should be removed, clearly qualified or held for review rather than presented as certain.

## Who is behind the site?

[OWNER TO CONFIRM: legal operator, real author and reviewer names, roles, biographies, credentials and any relevant affiliations. Add only verified information before publication.]

Questions about our approach are welcome through the [contact page](/contact/). For information about data associated with using the site or contacting us, read the [Privacy Policy](/privacy-policy/) once it has been reviewed and published.

## JSON-LD @graph

The following is a schema specification for the eventual single graph, not a second script to paste alongside Rank Math or the Stage 1 schema module. Confirm the visible H1 and breadcrumb match before publishing.

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
      "url": "https://www.readmehub.com/",
      "name": "ReadMeHub",
      "publisher": {
        "@id": "https://www.readmehub.com/#organization"
      }
    },
    {
      "@type": "WebPage",
      "@id": "https://www.readmehub.com/about/#webpage",
      "url": "https://www.readmehub.com/about/",
      "name": "About ReadMeHub",
      "isPartOf": {
        "@id": "https://www.readmehub.com/#website"
      }
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
          "name": "About ReadMeHub"
        }
      ]
    }
  ]
}
```
