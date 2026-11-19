# Prototype tests

The PHP scripts in this folder are standalone unit checks using minimal in-memory WordPress stubs. Run each with a PHP CLI, for example `php tests/cta-test.php` from `stages/stage-1/`. They do not boot WordPress and do not establish plugin, database, ACF, editor, Rank Math or Elementor integration. The 2026-11-19 execution used the npm package `@php-wasm/cli` version 3.1.64, which reports PHP 8.5.12; the target PHP 8.2 environment is untested.

- `affiliate-test.php`: URL normalization and program/approval/expiry gates.
- `cta-test.php`: synthetic CTA HTML, record binding and separately confirmed plain-link fallback.
- `data-test.php`: source/date/price validation.
- `seed-test.php`: 17 draft records and owner metadata on repeated seed using an in-memory stub.
- `pattern-test.php`: six blocks and repeated provision preserving edits using an in-memory stub.

## Disposable WordPress integration

`./tests/run-playground.sh` downloads WordPress 7.1.3, ACF Free 6.8.11, Rank Math 1.0.280 (GitHub commit `50fc59587112`) and Elementor 4.3.5 to a temporary directory and uses WordPress Playground with PHP 8.2.35 and SQLite. It writes `wp-integration-result.json`, `wp-schema-result.json`, `wp-schema-fallback-result.json`, and `wp-elementor-result.json` in this folder. Synthetic records exist only in the disposable database. The Rank Math schema test manually initializes its JSON-LD module if not already hooked, so it is a hook-level test rather than a full HTTP page test. Elementor tests instantiate the PHP shortcode widget; source checkout does not represent a compiled production UI. Test runs under Node 22/npm 10 produced package engine warnings for dependencies requesting Node 24/npm 11, although execution passed. Browser, editor, network, server-cache, full-page and production tests remain outstanding.
