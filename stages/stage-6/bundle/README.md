# ReadMeHub — Stage 6 offline staging bundle

**This is a draft-only, offline export, not a deployed site or permission to publish.** No credentials are included. No WordPress, Elementor, theme or live site was changed when this bundle was built. The user selected the offline route; do not install Jetpack to connect a WordPress.com account. Establish isolated, access-controlled staging and get explicit approval before performing even staging writes.

## Contents

```text
bundle/
  README.md                              This guide
  SHA256SUMS                             Bundle integrity hashes (excluding this file)
  acf/acf-export-readmehub.json          ACF Free field groups
  plugin/readmehub-core-dynamic.zip      Staging-candidate plugin ZIP
  plugin/readmehub-core-dynamic/         Matching plugin source, CSS, JS and block patterns
  content/draft-manifest.json            39 draft-only records including structural parent
  content/stage-0/                       Audit, decisions, affiliate status and blockers
  content/stage-1/                       Plugin/data/setup specifications
  content/stage-2/                       Trust/legal Page Files and chrome spec
  content/stage-3/                       15 Elementor page specs, 404 copy and T12 skeleton
  content/stage-4/                       Wave 1 Page Files, evidence worksheets, blockers
  content/stage-5/                       Wave 2 Page Files, sources, matched workload, blockers
  assembly/                              Shared Elementor Free and global chrome specifications
  scripts/import-drafts.php              WP-CLI draft importer with explicit plan/apply modes
  scripts/build-manifest.py              Optional reproducible source-to-manifest generator
  tests/                                 Disposable integration test, results, static validator
```

The ZIP and source are identical, plugin ZIP SHA-256 `a36c62dde151fb0ac50b7c8c7f44d9ba8d5361e1a737762f377a0867ac13e62f`. Verify integrity before use with `sha256sum -c SHA256SUMS` from this directory. The checksums detect accidental changes, not malicious replacement of the bundle and checksums together.

## What the manifest contains

- Six Stage 2 and 17 Stage 4/5 complete-copy Page Files become Gutenberg draft pages with separate paragraphs, headings, lists, shortcodes and per-table HTML blocks. Source Markdown remains in `content/` and is the editorial authority; the importer strips frontmatter and the *schema specification*, rather than adding a duplicate JSON-LD script. Page-level ACF values and intended SEO title/description are set where available. All imported records have `rank_math_robots` noindex/nofollow and `_rmh6_publish_blocked=1`; these flags do not enforce every editorial release gate.
- Fifteen Stage 3 pages are **Gutenberg reference scaffolds**, not Elementor documents. Their source files contain the exact Elementor Free section/widget trees, responsive rules and copy. Build the real layout manually from the Page File; do not publish the scaffold or copy implementation instructions and conditional empty states as reader-facing copy. Global chrome, search/archive components and 404 theme integration are specs, not built features. T12 is a skeleton; no deal page is created.
- The homepage copy becomes `/readmehub-home-draft/`, not `/`, and the importer never changes `show_on_front` or `page_on_front`. Changing the home page requires a separate approved, reversible action after QA. The custom 404 is not a page and is not imported. `/hosting/wordpress/` is a draft structural parent, not a new public hub or redirect; the exact small-business path sits beneath it.
- Draft source links to unpublished pages are **planning links**, not verified live routes. Remove/disable them until the destination is published. No canonical meta or manual schema is injected. Graph specifications are for review, not extra scripts to paste alongside Rank Math.

## Prerequisites and safe preparation

1. Obtain explicit owner approval for isolated staging writes. Use a fresh staging clone with access controls and a database/files backup. Staging must block public indexing (`blog_public=0`), but this does **not** provide access control; use password protection or network isolation. Never run on `readmehub.com` or `www.readmehub.com`, or on multisite; the importer refuses those targets. It also requires matching home/site hosts and an exact operator-supplied staging home URL.
2. Record current `wp option get home`, `wp option get siteurl`, `wp option get blog_public`, `wp option get show_on_front`, `wp option get page_on_front`, `wp option get blogname`, permalink structure, plugins, schema emitters, existing routes and backups. Inspect `wp post list --post_type=page --post_status=any --fields=ID,post_name,post_status,post_parent` and `wp post list --post_type=rmh_tool --post_status=any` if the CPT exists. WordPress commonly installs a draft `/privacy-policy/`; it is a real collision and the importer will abort instead of overwriting it. Decide on a separate staging-only collision-resolution plan; never delete or change existing pages automatically.
3. Confirm WP/PHP compatibility, Hello Elementor and Elementor Free, ACF Free and Rank Math versions on staging. Install/activate ACF Free and the bundled plugin only after approval; activation changes plugin state and flushes rewrites. Import `acf/acf-export-readmehub.json` through **ACF → Tools → Import Field Groups** and confirm both groups/field keys. Do not use ACF Pro fields or Elementor Pro features. Importer checks ACF field definitions and plugin CPT before writes.
4. Inspect existing `rmh_silo`, `rmh_cluster` terms and tool records before using `wp rmh seed-tools`. It creates terms and up to 17 draft registry records and may fill missing metadata on existing records: it is **not** a pure draft-page import. Run it only with separate staging approval, preferably in an empty staging registry; inspect the Stage 0 action sheet first. It never proves affiliate approval or an official nontracking fallback. Verify the four program/verification/application/approval fields separately, the unresolved Systeme.io `YOURID` is not a real link, and current source dates before use. Do not provision synced patterns with `wp rmh provision-synced-patterns` unless separately approved: that command creates published `wp_block` records, even though no articles are published.

## WP-CLI draft import — inspect before write

The commands below must be run by an authorized operator **from this bundle directory** on the staging server with WP-CLI available. Set the variables to the *actual* staging installation path and its exact WordPress home URL. They are not supplied or guessed by this bundle. These commands do not set up staging, change robots settings or install plugins.

```bash
: "${WP_ROOT:?Set WP_ROOT to the isolated staging WordPress directory}"
: "${STAGING_HOME:?Set STAGING_HOME to the exact isolated staging home URL}"
wp --path="$WP_ROOT" option get home
wp --path="$WP_ROOT" option get siteurl
wp --path="$WP_ROOT" option get blog_public
wp --path="$WP_ROOT" post list --post_type=page --post_status=any --fields=ID,post_name,post_status,post_parent
# If the registered CPT exists, inspect its records before any optional seeding:
wp --path="$WP_ROOT" post list --post_type=rmh_tool --post_status=any --fields=ID,post_name,post_status
# Independently approved on staging only; persistent taxonomy/registry writes:
wp --path="$WP_ROOT" rmh seed-tools
# Mandatory dry run; inspect all actions and resolve conflicts manually on staging.
wp --path="$WP_ROOT" eval-file scripts/import-drafts.php plan "$STAGING_HOME"
# Only after a clean plan, a verified backup and explicit approval:
wp --path="$WP_ROOT" eval-file scripts/import-drafts.php apply "$STAGING_HOME"
```

`plan` makes no page writes; `apply` only inserts new pages as drafts and never updates existing pages. Before any insert it checks exact staging home, matching nonproduction hosts, single-site mode, noindex setting, dependencies, source hashes and all known hierarchical collisions. It refuses published, trashed, non-owned or ambiguous existing matching pages. Re-running skips its own still-draft records and preserves edits; it does **not** refresh them. Production host refusal is defense in depth, not proof that another host is safe staging. Inspect the `apply` JSON immediately and save its created IDs/path list outside the public web root. If a concurrent change, plugin hook or insert failure occurs mid-run, earlier inserts can remain: stop, inspect the output and restore from backup if needed. The importer is not transactional and does not alter/delete preexisting posts. Active automation can interfere with status; disable/review it on isolated staging first.

No IDs or preview URLs exist yet. After a successful approved import, use the actual IDs from its JSON and `wp post list --post_type=page --post_status=draft` to construct previews in the site's admin, respecting its authentication. Do not publish pages or point the live homepage at the draft.

## Assembly and staging verification

- Use `assembly/elementor-free-assembly.md` and the individual `content/stage-3/pages/*.md` container trees to build Elementor Free pages manually, replacing each scaffold only on authorized staging. Do not use a single HTML widget for a whole page, Pro Theme Builder, Pro Posts/Loop Grid, Pro Form or custom attributes. Every shortcode, ACF/dynamic-tag, date or geo widget: **Element Caching = Inactive**. Dynamic components need their true data and empty states; 404/search/archive integrations identified in Stage 3 have not been built.
- Header, drawer, footer and chrome are a child-theme specification, not implemented theme files. Stage 1 breadcrumb output can link a draft ancestor at the small-business path; fix/test before use. Verify WordPress hierarchy, HTTP routing, canonical and noindex behavior, and stage 3 cluster-grid self-inclusion/cap before publication.
- Review source Page Files against converted Gutenberg output and editor block validity. Check all actual links and shortcodes, disclosure before the first affiliate link, correct fallback/approval and `rel`, price dates and source verification, and safe Elementor/page caches. Never paste the graph spec as a second graph. Consult the Stage 4 and 5 token inventories; Mode A content remains blocked by the master prompt. Verify legal/operator identity and contact route before publishing trust pages.
- After the real staging implementation exists, test 1440/1280/768/390/360px in a browser, keyboard/assistive technology and no-JS behavior. Verify schema, canonicals, sitemap, search/404 status, archive empty/populated states and genuine Core Web Vitals. None of these browser, SEO, plugin-conflict or live staging checks has been performed by this bundle. Stage 7 is a separate gate.

## Tests and rebuild

`python3 tests/validate_bundle.py` checks the manifest, source hashes, gating and basic structure. `tests/playground-results.json` records the disposable WordPress/ACF integration. `tests/run-playground.sh` depends on the repository's excluded `.cache/wp-src`, `.cache/acf-src` and `.cache/playground` directories and is not a portable installation command; see Stage 1's reproducible download script for how its test dependencies were acquired. The integration exercises PHP importer functions, not a real WP-CLI command, editor session or browser.

If source Markdown changes, regenerate `content/draft-manifest.json` using `scripts/build-manifest.py` with PyYAML, markdown-it-py and BeautifulSoup4 installed in a separate Python environment, review the resulting diff, run tests, and regenerate `SHA256SUMS`. Rebuilding the manifest does not update already-imported drafts: the importer intentionally skips them. Plan a separately approved manual staging edit or a fresh staging database; never silently overwrite an editor's work.

## Recovery / rollback on staging only

Before applying, save and verify a full database and files backup, and note the pre-import page and plugin inventories. The safest rollback of a failed or unwanted import is to restore that verified staging backup using the hosting provider's documented restore procedure; this can discard **all** subsequent staging work, so coordinate with other editors. To investigate tagged records without changing anything, run:

```bash
wp --path="$WP_ROOT" post list --post_type=page --post_status=any --meta_key=_rmh6_draft_path --fields=ID,post_title,post_status,post_parent
```

Compare those results, their meta and timestamps with the saved apply report and pre-import inventory. A failure can occur immediately after insert **before** the ownership marker is saved, so this query is not a complete recovery inventory; inspect recent untagged pages and WordPress/server logs too. A manual selective cleanup requires explicit owner approval and independent confirmation of every ID and dependency; do not bulk-delete by marker, delete pre-existing pages, or delete edited work. Registry seeds, activated plugins, imported ACF groups and any separately created patterns are outside the page importer and require their own approved rollback or the backup restore. Recheck homepage settings, routes, plugin state and privacy after any rollback.
