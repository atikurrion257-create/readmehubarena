# Stage 1 ACF Free data contract — draft mapping

No legacy ACF slugs existed in the supplied content map. The names below are deliberate extensions; they are not assertions of existing live fields. All fields are in the accompanying ACF JSON unless otherwise stated. Do not import into production until validated.

| Part 7 field | Prototype storage | Notes |
|---|---|---|
| `tool_name` | `wp_posts.post_title` | Single source of truth; not duplicated as meta. |
| `brand_slug` | `rmh_tool_slug` and `wp_posts.post_name` | Seeder sets both; tooling resolves post slug. Editors must keep them aligned. |
| `silo_cluster` | `rmh_silo` and `rmh_cluster` taxonomy assignments | Two independent term sets; not a text field. |
| `program_status`, `verification_status`, `application_status`, `approval_status` | `rmh_program_status`, `rmh_verification_status`, `rmh_application_status`, `rmh_approval_status` | Four separate fields, never inferred from one another. Status vocabulary in ACF export and Action Sheet. |
| `affiliate_url` | `rmh_affiliate_url_template` | Only exact owner-supplied URL with approved account and current terms may render; no shortcode override. |
| `no_www_enforced` | `rmh_no_www_enforced` | Informational editor field; Systeme.io normalization is enforced by hostname regardless of the field. |
| `intro_price`, `renewal_price` | `rmh_intro_price`, `rmh_renewal_price` | Text, includes currency/period/conditions. Blank means unknown. |
| `pricing_verified_date` | `rmh_pricing_verified_date` | Separate from affiliate program verification. ACF `Ymd` storage and `Y-m-d` return normalized. |
| Price source (extension) | `rmh_pricing_source_url`, `rmh_pricing_source_confirmed` | Manually confirmed non-affiliate official HTTPS URL without query or fragment. Required alongside a valid nonfuture date before prices render. >90 days flagged stale. Confirmation is human input, not an automated verification. |
| `free_plan_available` | `rmh_free_plan_available` | Select yes/no; blank is unknown. Displayed in the comparison matrix only with the same verified pricing source/date. |
| `cookie_window` | `rmh_cookie_note`, `rmh_cookie_days` | Descriptive note preserves permanent/unknown distinctions; days blank when unknown. |
| `geo_notes`, `key_strength`, `primary_limitation` | `rmh_geo_notes`, `rmh_key_strength`, `rmh_primary_limitation` | Blank until documented and sourced; display integration remains open. |
| Verified plain link (extension) | `rmh_non_affiliate_url`, `rmh_non_affiliate_url_confirmed`, `rmh_non_affiliate_verified_date` | Separate non-affiliate official HTTPS URL, manual confirmation and fresh nonfuture date; unavailable means no fallback link. These fields do not establish that a tracking path is non-affiliate by themselves. |
| Program verification (extension) | `rmh_verified_date`, `rmh_expires_date` | Affiliate terms baseline, not pricing date. Gate requires both, nonfuture verification, nonexpired expiry and at most 90 days since verification. |
| Page editorial fields | `rmh_page_template_type`, `rmh_evidence_mode`, `rmh_plan_tested`, `rmh_test_date`, `rmh_workflow_evaluated`, `rmh_observed_limits`, `rmh_last_pricing_check`, `rmh_disclosure_override`, `rmh_primary_persona`, `rmh_skip_if`, `rmh_pros_list`, `rmh_cons_list`, `rmh_verdict_summary`, `rmh_rating_score` | Page-level fields defined in JSON; Mode B requires an explicit editorial review and attached dated log plus screenshot. |
| Trust, navigation, deal, FAQ extensions | `rmh_author_name`, `rmh_author_bio`, `rmh_author_credentials`, `rmh_author_role`, `rmh_reviewer_name`, `rmh_silo_slug`, `rmh_cluster_slug`, `rmh_faq_items`, `rmh_deal_status`, `rmh_deal_last_verified`, `rmh_deal_expires`, `rmh_review_url` | Flat ACF Free fields. Additional fields include `rmh_reviewer_bio`, `rmh_reviewer_credentials`, `rmh_reviewer_role`, `rmh_editorial_policy_url`, `rmh_deal_starts`, `rmh_deal_source_url`, `rmh_deal_source_confirmed`, `rmh_deal_terms_preview`, and page-level `rmh_tool_slug`. Deals use a manually confirmed source and date. Expiry overrides an active label; stale active/upcoming offers are hidden. |

**Seeder safety:** only Systeme.io's template URL is supplied by the Action Sheet; other affiliate templates were removed rather than guessed. Unverified-program dates remain blank. Editorial claims and price values are not seeded. The remaining seed terms must be audited against the Action Sheet before deployment; 2026-09-03 baseline expires 2026-12-02. Existing metadata are preserved on re-seeding. ACF Free 6.8.11 import and selected date/get-field behavior passed a local WP 7.1.3/PHP 8.2.35 Playground test; a real staging site still needs independent validation.

## Evidence extensions

`rmh_test_log_attachment` and `rmh_test_screenshot_attachment` are ACF Free file/image attachment IDs; `rmh_evidence_verified` is the human-review checkbox and `rmh_review_item_name` supplies the visible reviewed product name. Mode B additionally requires plan, nonfuture test date, workflow, observed limits, an existing local log file and existing image attachment. The gate establishes presence, not the truth or completeness of a test; a human must verify log contents and claims. No test evidence is supplied. Review schema is emitted only when the evidence stamp is explicitly present in the content and reviewed item is visible. Rating schema is deliberately omitted.
