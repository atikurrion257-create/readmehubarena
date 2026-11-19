# Stage 6 — Safe staging deployment: offline bundle complete

**Outcome:** The requested offline `/wp-admin/` export bundle is ready for an authorized operator. No site or staging deployment has occurred. This is not an Elementor-built site, a staging verification, or approval to publish. Stage 7 remains gated.

## Deliverables

- [Download the Stage 6 offline bundle](readmehub-stage-6-offline-bundle.zip)
- [Bundle checksum](readmehub-stage-6-offline-bundle.zip.sha256): SHA-256 `f81070beadfd0345fbe543e2d65e5b20b11f4d2c2ff0f8fdbcd1fcb2d23a0c83`
- [Bundle README](bundle/README.md): folder tree, prerequisites, staging setup, explicit WP-CLI plan/apply and optional taxonomy seeding commands, verification and rollback.
- [Draft manifest](bundle/content/draft-manifest.json), [draft importer](bundle/scripts/import-drafts.php), [ACF Free JSON](bundle/acf/acf-export-readmehub.json), [plugin ZIP](bundle/plugin/readmehub-core-dynamic.zip), matching plugin source, Stage 0–5 source files, Elementor assembly/chrome specs and test artifacts are included in the bundle. The per-file integrity list is `bundle/SHA256SUMS`.

## Scope and safety

The manifest contains 39 draft pages: 6 Stage 2 trust/legal pages, 15 Stage 3 scaffolds, 5 Stage 4 and 12 Stage 5 content drafts, and a structural `/hosting/wordpress/` parent. The homepage scaffold is deliberately imported at `/readmehub-home-draft/` and no front-page settings are changed. The 404 is a theme integration specification, not an imported page. The 15 Stage 3 records contain implementation instructions and are **not functional Elementor documents**; assemble and verify manually on staging before any launch decision.

The importer requires a single-site, non-indexable staging installation, ACF and the ReadMeHub plugin, exact matching staging home URL, matching home/site hosts, valid source hashes, ordered parents and collision-free paths. It refuses the production hosts and pre-existing non-owned or non-draft page collisions, imports drafts only and marks Rank Math robots noindex/nofollow; a rerun skips its own drafts rather than overwriting edits. It is not atomic: an interruption can leave partial drafts, including an untagged insert. Back up first and use the README recovery procedure. A default WordPress privacy-policy draft may block import and needs a human decision. No automatic deletion is supplied. Isolated staging access controls, explicit write approval and operator verification are still required; `blog_public=0` alone is not access control.

Tool/taxonomy seeding and synced patterns are separate write operations, not part of page import. Seeding can affect existing tool metadata; pattern provisioning creates published `wp_block` records. They require separate approval. The bundle does not invent affiliate identifiers, testing results, legal identity, prices or other unresolved values. The Stage 4/5 token inventories, affiliate action sheet and legal, evidence and editorial blockers remain binding. YMYL Finance/Insurance and head terms remain Wave 3 HOLD.

## Verification actually performed

- Portable `tests/validate_bundle.py`: **PASS**, 39 records and zero reported problems.
- Disposable WordPress/ACF importer integration: **16/16 checks passed**; results in `bundle/tests/playground-results.json`. It calls importer PHP functions directly, not the WP-CLI wrapper. Its runner needs excluded repository-local disposable dependencies and is not a standalone download-and-run test.
- Manifest regenerated deterministically with its optional generator; plugin ZIP and bundled source matched byte-for-byte; bundle file checksums verified; final ZIP passed `unzip -t`.
- **Not tested:** real WP-CLI invocation, actual staging or live deployment, Elementor editing/rendering, WordPress browser previews, real routing/canonicals, plugin interactions, keyboard/accessibility, frontend schema, performance or Core Web Vitals. Nothing was published. These require an authorized isolated staging environment and a later QA stage.

## Remaining implementation gates

Build the Stage 3 Elementor Free layouts and theme chrome, search/archive/404 integration, and replace reference scaffolds with actual components. Resolve legal and operator identity, unpublished links, source/evidence and price tokens, affiliate approvals and nontracking fallbacks, and all publication gates. Fix/test the breadcrumb's draft-ancestor behavior and validate hierarchy and grid behavior on staging. Confirm Elementor Element Caching is Inactive for dynamic/shortcode widgets. Preserve the FTC disclosure-before-affiliate-link rule, shortcode-only affiliate links and required `rel` attributes; do not emit duplicate schema. Owner approval and real staging QA are mandatory before publication.

**Gate: `PROCEED TO STAGE 7`** — full-site QA and handoff. Do not treat Stage 6 as evidence that Stage 7 checks passed.
