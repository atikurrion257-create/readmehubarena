---
url: /contact/
template: T16
stage: 2
evidence_mode: not_applicable # Trust/legal page; not a product evaluation.
status: draft
publish_gate: BLOCK PUBLISH — working contact route, form provider, consent and operator approval required
index: true
canonical: https://www.readmehub.com/contact/
title_tag: "Contact & Report an Error | ReadMeHub"
meta_description: "Contact ReadMeHub with feedback, report a factual error, or ask about an advertiser inquiry. Include the page URL and relevant details."
primary_persona: "Readers reporting errors or contacting the site"
acf:
  rmh_page_template_type: T16
internal_links: [/editorial-methodology/, /affiliate-disclosure/, /privacy-policy/, /about/]
cta_map: []
open_items: ["OWNER TO CONFIRM: working contact email or endpoint", "OWNER TO CONFIRM: lean free form plugin and spam handling", "OWNER TO CONFIRM: form privacy notice, retention and response process"]
---

[rmh_breadcrumbs]

# Contact and Report an Error

Have a question, a suggestion or a correction for ReadMeHub? We welcome clear, specific messages about the information on this site. Choose the subject that best fits your message so it can be directed to the right review process.

**The contact form is not live yet.** [OWNER TO CONFIRM: a working contact email or form endpoint and the person responsible for receiving messages.] Do not enter personal information into an unconfigured form. No response-time promise is made until the operator confirms one.

## Report a factual error

To report an error, include the page URL and the sentence, table entry or link you believe needs attention. Explain what appears incorrect and, if possible, include the current provider documentation or another relevant source. For a pricing issue, the plan name, billing period, location and source date can help distinguish a change from a difference in terms.

Please avoid sending account passwords, payment information, access tokens or other confidential information. We do not need credentials to understand a public factual correction. The intended correction process is explained in our [Editorial Methodology](/editorial-methodology/).

## Other messages

Use **Reader feedback** for a suggestion about the site or a guide. Choose **Advertiser inquiry** for a commercial inquiry, or **Other** if neither category fits. Commercial inquiries do not purchase an editorial ranking or change a verdict; the proposed separation is described in our [Affiliate Disclosure](/affiliate-disclosure/).

## Contact form

Once an owner-approved, lean free form provider is configured and tested, the form should contain these visible, associated labels:

- **Name** — required.
- **Email address** — required, with an email-format check.
- **Subject** — required; choose Reader feedback, Report a factual error, Advertiser inquiry, or Other.
- **Message** — required.
- **Send message** — submit button.

If a required field is missing, the form should identify it and explain how to correct it. On successful submission it should say, “Your message has been sent.” If delivery fails, it should say, “Your message could not be sent. Please try again or use the contact option listed on this page.” The alternative contact option must be real and working before that message is enabled.

The operator must confirm the recipient, spam protection, retention period and any consent requirements before enabling the form. See the [Privacy Policy](/privacy-policy/) for the reviewed data-handling notice. Learn more about the site on [About ReadMeHub](/about/).

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
      "@id": "https://www.readmehub.com/contact/#webpage",
      "url": "https://www.readmehub.com/contact/",
      "name": "Contact and Report an Error",
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
          "name": "Contact and Report an Error"
        }
      ]
    }
  ]
}
```
