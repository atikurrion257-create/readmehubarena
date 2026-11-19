---
url: /affiliate-disclosure/
template: T17
stage: 2
evidence_mode: not_applicable # Trust/legal page; not a product evaluation.
status: draft
publish_gate: BLOCK PUBLISH — owner must confirm disclosure, actual relationships and operating practices
index: true
canonical: https://www.readmehub.com/affiliate-disclosure/
title_tag: "Affiliate Disclosure | ReadMeHub"
meta_description: "Understand how ReadMeHub may earn commissions from affiliate links and how those relationships are separated from editorial decisions."
primary_persona: "Readers seeking to understand affiliate links"
acf:
  rmh_page_template_type: T17
internal_links: [/editorial-methodology/, /about/, /contact/, /privacy-policy/]
cta_map: []
open_items: ["OWNER TO CONFIRM: legal operator and disclosure accuracy", "OWNER TO CONFIRM: editorial independence controls and actual affiliate relationships", "Verify FTC and applicable local disclosure requirements"]
---

[rmh_breadcrumbs]

# Affiliate Disclosure

ReadMeHub may earn a commission when you follow some links on this site and complete a qualifying action, such as making a purchase or signing up for a service. If a commission is paid, it comes from the provider or its affiliate network; **you do not pay an extra charge because ReadMeHub receives that commission**.

Not every link is an affiliate link, and not every product we discuss has an affiliate relationship with ReadMeHub. We may also link to a provider's documentation or pricing page as a source. A link or mention on its own should not be understood as a guarantee that an affiliate program is open, that an application has been approved, or that an offer is available to you.

## How an affiliate link can work

An affiliate link may contain information that allows a provider or affiliate network to attribute a qualifying action to a referring site. If you follow the link and later take an eligible action under that program's rules, the provider or network may pay a commission. The provider's terms determine eligibility, attribution and payment; those terms can change.

The provider may use cookies or similar technology as part of attribution. Whether a commission is paid can depend on the program, the action taken, location, timing and other terms. Please read the provider's own terms and privacy information when deciding whether to use its service. Our [Privacy Policy](/privacy-policy/) describes the site-level data practices the operator needs to confirm before publication.

## How we intend to separate editorial work from commissions

The editorial standard is that a commission does not buy a favorable ranking, remove a relevant limitation or change a verdict. A product should be evaluated against the needs of the reader and the evidence available, including who might be better served by an alternative. A product without an affiliate program can still be relevant to a guide.

An affiliate relationship is not proof of product quality. Commission amounts and program terms belong in the site's internal affiliate records; they are not a reason for a reader to choose a product. Where a page compares options, the selection criteria and important tradeoffs should be visible. Our [Editorial Methodology](/editorial-methodology/) explains the proposed research and testing standards.

[OWNER TO CONFIRM: these editorial separation controls are in place and the published statement accurately reflects actual practice.]

## How links are disclosed on a page

A page that contains affiliate links should include a clear, plain-language disclosure **before the first affiliate link**, so readers can see it before deciding whether to follow the link. A disclosure in the footer or on this page alone is not a substitute for that placement. The site's designated affiliate-link component is intended to mark affiliate links with `sponsored`, `nofollow` and `noopener` relationship attributes.

If a program is closed, unverified, unapproved or otherwise on hold, ReadMeHub should not present it as an active earning relationship. A separately verified ordinary link to an official provider may be used where appropriate; an unapproved tracking link should not be relabeled as ordinary. Program status and account approval are different facts.

## Prices and offers

A commission does not determine the price shown in a guide. Vendor prices, free-plan limits and promotional terms can change, and an introductory price may differ from the renewal price. A dated source helps readers understand when information was checked, but it does not guarantee that the same terms remain available. Verify current pricing, eligibility and billing terms with the provider before making a decision.

ReadMeHub should not invent a discount, countdown or exclusive offer. If an offer has not been verified, it should not be displayed as verified or active.

## Questions or concerns

If a disclosure is missing, a link appears incorrectly labeled, or you have a question about a commercial relationship, please use [Contact and Report an Error](/contact/). Include the page URL and the relevant link if possible. You can also read [About ReadMeHub](/about/) for the site's purpose and the [Editorial Methodology](/editorial-methodology/) for how evidence should be handled.

[OWNER TO CONFIRM: legal operator, applicable disclosure requirements, actual program relationships and operational review process before publication.]

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
      "@id": "https://www.readmehub.com/affiliate-disclosure/#webpage",
      "url": "https://www.readmehub.com/affiliate-disclosure/",
      "name": "Affiliate Disclosure",
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
          "name": "Affiliate Disclosure"
        }
      ]
    }
  ]
}
```
