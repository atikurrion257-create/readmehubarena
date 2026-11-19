#!/usr/bin/env bash
# Uses disposable WordPress and plugin sources already available locally. Never targets a real site.
set -euo pipefail
bundle="$(cd "$(dirname "$0")/.." && pwd)"
root="$(cd "$bundle/../../.." && pwd)"
tmp="$(mktemp -d "${TMPDIR:-/tmp}/rmh6-test.XXXXXXXX")"
trap 'rm -rf "$tmp"' EXIT
cp -a "$root/.cache/wp-src" "$tmp/wp"
rm -rf "$tmp/wp/wp-content/database"
printf '%s\n' '{"preferredVersions":{"php":"8.2","wp":"7.1.3"},"steps":[{"step":"activatePlugin","pluginPath":"advanced-custom-fields/acf.php"},{"step":"activatePlugin","pluginPath":"readmehub-core-dynamic/readmehub-core-dynamic.php"},{"step":"runPHP","code":"<?php require '\''/bundle/tests/playground-import.php'\'';"}]}' > "$tmp/blueprint.json"
"$root/.cache/playground/node_modules/.bin/wp-playground-cli" run-blueprint --php=8.2 --wordpress-install-mode=install-from-existing-files --mount-before-install="$tmp/wp:/wordpress" --mount="$root/.cache/acf-src:/wordpress/wp-content/plugins/advanced-custom-fields" --mount="$bundle/plugin/readmehub-core-dynamic:/wordpress/wp-content/plugins/readmehub-core-dynamic" --mount="$bundle:/bundle" --blueprint="$tmp/blueprint.json"
node - "$bundle/tests/playground-results.json" <<'JS'
const r = require(process.argv[2]); let bad=0; for (const t of r.results){console.log(`${t.pass?'PASS':'FAIL'} ${t.name}`); if(!t.pass)bad++} process.exit(bad?1:0)
JS
