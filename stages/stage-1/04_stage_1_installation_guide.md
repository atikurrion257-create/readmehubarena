# Stage 1 — staging installation and verification guide

**Do not install on production yet.** Stage 1 has been built and locally exercised, but real staging, editor, Elementor, full browser and production-stack checks are pending. The owner selected the offline deployment route; never connect a WordPress.com account or install Jetpack for this purpose. No passwords, application passwords, tokens or other credentials should be shared in chat.

## Files

- `readmehub-core-dynamic.zip` — packaged plugin source; plugin folder is the ZIP root. It is a **staging candidate**, not approval to deploy.
- `acf-export-readmehub.json` — ACF Free import file; import field groups through ACF → Tools.
- `patterns/` — six Gutenberg block-markup files, also included in the plugin for explicit synced-pattern provisioning.
- `01_elementor_performance_matrix.md`, `02_taxonomy_permalink_indexing_setup.md`, `03_block_pattern_library.md`, `05_data_contract.md`, and `tests/` — implementation and validation records.

## Approved staging procedure

1. Establish an isolated staging site; note its URL and obtain authorization for persistent changes. Back it up. Record WP, PHP, theme, plugin versions, existing title/tagline, permalink structure, robots settings, active schema emitters and existing hosting paths. Staging must not be publicly indexable.
2. On staging, install/activate **ACF Free**, then import the JSON field groups. Verify the editor fields and ACF date values. No Pro fields or Options Pages are used.
3. Upload and activate the plugin ZIP. Activation registers the CPT and rewrites and flushes rules; it does **not** change title or tagline. This is not a draft-only action, hence requires explicit approval even on production. Confirm plugin activation and that no unintended page routes or content changed.
4. Settings → ReadMeHub Core → seed, or `wp rmh seed-tools`. Confirm 17 draft registry records, 2 silos and 7 clusters. Existing metadata and already-assigned taxonomy terms are preserved. Confirm Ahrefs and Notion non-monetized, NordVPN HOLD, and all application/approval states separately. No real affiliate IDs or product prices are seeded.
5. If authorized, run `wp rmh provision-synced-patterns` on staging. This creates six **published `wp_block` pattern records**, not pages; it is a persistent write. Run twice, confirm six existing records are preserved, insert an instance, verify synced updates, and detach before making page-specific edits.
6. Create only draft test pages. Verify evidence-mode gates, disclosure and CTA fallback, approved/unapproved links, pricing sources and dates, all deal states, directory filters, pattern markup, and the hosting hierarchy. Fill no invented vendor data. Verify actual Rank Math output and compare schema values to visible text; FAQPage schema is deliberately omitted. Clear Elementor, page and server caches after configuration changes.
7. Apply the performance matrix, explicitly setting every dynamic widget's caching to Inactive. Check actual browser rendering at 1440, 1280, 768, 390 and 360px, keyboard/focus, LCP image attributes and real CWV metrics. Verify local fonts, consent configuration for analytics, and no competing JSON-LD scripts.
8. Run Stage 7's complete publication gate before any publishing. Mode A commercial pages and unresolved verification tokens remain blocked; the plugin's noindex checks alone do not enforce every editorial gate. No owner affiliate IDs, newsletter provider, legal facts, dated hands-on logs or actual prices have been supplied by this build.

## Site identity (separate live change)

After explicit approval and recording the existing value, set Settings → General → Site Title to `ReadMeHub`, or use `wp rmh fix-site-title`. The plugin action changes **only** the title, not tagline. Roll back by restoring the recorded previous value. No production action is authorized by this guide.

## WP-CLI staging commands

```bash
# After approved upload/activation and ACF Free import:
wp rmh seed-tools
wp rmh test-url-sanitizer
# Persistent synced-pattern write; only after approval:
wp rmh provision-synced-patterns
# Separate title write; only after specific approval:
wp rmh fix-site-title
```

For WordPress environments without WP-CLI, insert the supplied markup in a staging editor, select the blocks and create a synced pattern manually; verify behavior. Use the eventual Stage 6 exporter for page drafts; the Stage 1 ZIP is not that exporter.
