#!/usr/bin/env bash
# Disposable integration tests. Requires Node >=20, npm, curl, tar; network access to GitHub and npm.
set -euo pipefail
stage="$(cd "$(dirname "$0")/.." && pwd)"
tmp="$(mktemp -d "${TMPDIR:-/tmp}/rmh-wp-test.XXXXXXXX")"
trap 'rm -rf "$tmp"' EXIT
mkdir -p "$tmp/wp" "$tmp/acf" "$tmp/rank" "$tmp/elementor" "$tmp/npm"
curl -fsSL https://codeload.github.com/WordPress/WordPress/tar.gz/refs/tags/7.1.3 | tar -xz --strip-components=1 -C "$tmp/wp"
curl -fsSL https://codeload.github.com/AdvancedCustomFields/acf/tar.gz/refs/tags/6.8.11 | tar -xz --strip-components=1 -C "$tmp/acf"
curl -fsSL https://codeload.github.com/rankmath/seo-by-rank-math/tar.gz/50fc59587112 | tar -xz --strip-components=1 -C "$tmp/rank"
curl -fsSL https://codeload.github.com/elementor/elementor/tar.gz/refs/tags/4.3.5 | tar -xz --strip-components=1 -C "$tmp/elementor"
npm install --prefix "$tmp/npm" --no-save --no-package-lock @wp-playground/cli@3.1.65 >/dev/null
cli="$tmp/npm/node_modules/.bin/wp-playground-cli"
cat > "$tmp/integration.json" <<'JSON'
{"preferredVersions":{"php":"8.2","wp":"7.1.3"},"steps":[{"step":"activatePlugin","pluginPath":"advanced-custom-fields/acf.php"},{"step":"activatePlugin","pluginPath":"readmehub-core-dynamic/readmehub-core-dynamic.php"},{"step":"runPHP","code":"<?php require '/stage/tests/wp-integration.php';"}]}
JSON
cat > "$tmp/schema.json" <<'JSON'
{"preferredVersions":{"php":"8.2","wp":"7.1.3"},"steps":[{"step":"activatePlugin","pluginPath":"seo-by-rank-math/rank-math.php"},{"step":"activatePlugin","pluginPath":"readmehub-core-dynamic/readmehub-core-dynamic.php"},{"step":"runPHP","code":"<?php require '/stage/tests/wp-schema.php';"},{"step":"runPHP","code":"<?php require '/stage/tests/wp-schema-fallback.php';"}]}
JSON
cat > "$tmp/elementor.json" <<'JSON'
{"preferredVersions":{"php":"8.2","wp":"7.1.3"},"steps":[{"step":"activatePlugin","pluginPath":"elementor/elementor.php"},{"step":"activatePlugin","pluginPath":"readmehub-core-dynamic/readmehub-core-dynamic.php"},{"step":"runPHP","code":"<?php require '/stage/tests/wp-elementor.php';"}]}
JSON
rm -f "$stage/tests/wp-integration-result.json" "$stage/tests/wp-schema-result.json" "$stage/tests/wp-schema-fallback-result.json" "$stage/tests/wp-elementor-result.json"
"$cli" run-blueprint --php=8.2 --wordpress-install-mode=install-from-existing-files --mount-before-install="$tmp/wp:/wordpress" --mount="$tmp/acf:/wordpress/wp-content/plugins/advanced-custom-fields" --mount="$stage/readmehub-core-dynamic:/wordpress/wp-content/plugins/readmehub-core-dynamic" --mount="$stage:/stage" --blueprint="$tmp/integration.json"
rm -rf "$tmp/wp/wp-content/database"
"$cli" run-blueprint --php=8.2 --wordpress-install-mode=install-from-existing-files --mount-before-install="$tmp/wp:/wordpress" --mount="$tmp/rank:/wordpress/wp-content/plugins/seo-by-rank-math" --mount="$stage/readmehub-core-dynamic:/wordpress/wp-content/plugins/readmehub-core-dynamic" --mount="$stage:/stage" --blueprint="$tmp/schema.json"
rm -rf "$tmp/wp/wp-content/database"
"$cli" run-blueprint --php=8.2 --wordpress-install-mode=install-from-existing-files --mount-before-install="$tmp/wp:/wordpress" --mount="$tmp/elementor:/wordpress/wp-content/plugins/elementor" --mount="$stage/readmehub-core-dynamic:/wordpress/wp-content/plugins/readmehub-core-dynamic" --mount="$stage:/stage" --blueprint="$tmp/elementor.json"
node - "$stage/tests" <<'JS'
const fs=require('fs'),path=require('path'); let fail=false;
for (const file of ['wp-integration-result.json','wp-schema-result.json','wp-schema-fallback-result.json','wp-elementor-result.json']) {
 const r=JSON.parse(fs.readFileSync(path.join(process.argv[2],file),'utf8'));
 for(const t of r.results){console.log(`${t.pass?'PASS':'FAIL'} ${file}: ${t.name}`); if(!t.pass)fail=true;}
}
process.exit(fail?1:0);
JS
