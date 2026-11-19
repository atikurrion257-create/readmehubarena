#!/usr/bin/env python3
"""Read-only inventory of the Stage 6 manifest; not a substitute for rendered QA."""
import json,re,html
from pathlib import Path
ROOT=Path(__file__).resolve().parents[2]; OUT=Path(__file__).parent
pages=json.loads((ROOT/'stages/stage-6/bundle/content/draft-manifest.json').read_text())['pages']
rows=[]
for p in pages:
    text=p['content']; tokens=sorted(set(html.unescape(x).replace('\\|','|') for x in re.findall(r'\{\{VERIFY[^}]*\}\}',text,flags=re.I)))
    sc=re.findall(r'\[(rmh_[a-z_]+)(?:\s[^\]]*)?\]',text)
    disclosure=text.find('[rmh_disclosure'); cta=text.find('[rmh_affiliate_cta')
    rows.append({'url':p['registry_url'] or p['draft_path']+' (structural parent)', 'draft_path':p['draft_path'], 'source':p['source'], 'template':p['template'], 'stage':p['stage'], 'title_chars':len(p['seo_title']), 'meta_chars':len(p['seo_description']), 'shortcodes':sorted(set(sc)), 'cta_count':sc.count('rmh_affiliate_cta'), 'disclosure_before_first_cta': None if cta<0 else 0<=disclosure<cta, 'verify_tokens':tokens, 'owner_confirm_count':len(re.findall(r'\[OWNER TO CONFIRM[^\]]*\]',text,re.I)), 'release':'BLOCK PUBLISH'})
(OUT/'offline-inventory.json').write_text(json.dumps({'scope':'Stage 6 generated Gutenberg content; lexical inventory only, not rendered site QA','pages':rows},indent=2,ensure_ascii=False)+'\n')
lines=['# Stage 7 — page-by-page offline QA inventory','', 'Every row is **BLOCK PUBLISH**. P0 release status is FAIL, not a pass: no real page output, canonical, sitemap, schema, rendered disclosure/affiliate links or widget cache was checked. P1 browser/CWV, link resolution, tracking and full Part 5.7 review are NOT VERIFIED on every page. Counts below are source-block lexical indicators, not a certification of accuracy, accessibility or final page word count. A missing CTA does not prove there is no affiliate link. Title/meta lengths are character counts, not SERP pixel widths.','', '| Registry URL (or staging parent) | Template / stage | Title / meta chars | CTA / disclosure source order | Verify tokens | Owner markers | Status |','|---|---|---:|---|---:|---:|---|']
for r in rows:
    order='N/A' if r['disclosure_before_first_cta'] is None else ('before' if r['disclosure_before_first_cta'] else 'MISSING/AFTER')
    lines.append(f"| `{r['url']}` | {r['template']} / {r['stage']} | {r['title_chars']} / {r['meta_chars']} | {r['cta_count']} / {order} | {len(r['verify_tokens'])} | {r['owner_confirm_count']} | BLOCK PUBLISH |")
lines += ['', '## Explicit non-page and future-template coverage','','- Custom 404 (T20): `stages/stage-3/pages/404.md` is a copy/assembly specification, not a deployed endpoint; no real HTTP 404, indexing or accessibility test. BLOCK PUBLISH/launch.', '- T12 single deal: `stages/stage-3/02_T12_single_deal_skeleton.md` is a skeleton, not a deal or validated Elementor template. No offer or coupon page may be released without a real verified offer and all P0 checks. BLOCK PUBLISH.', '- The structural `/hosting/wordpress/` staging parent is not a registry page or a public hub; do not publish it merely to support a hierarchy. Routing and breadcrumb strategy must be resolved.','', '## Source-level details by page','']
for r in rows:
    lines += [f"### `{r['url']}` — {r['template']}",f"- Source: `{r['source']}`; staging path: `{r['draft_path']}`.",f"- Shortcodes in generated block content: {', '.join('`'+x+'`' for x in r['shortcodes']) or 'none'}. CTA order: {('not applicable' if r['disclosure_before_first_cta'] is None else 'disclosure appears first in source' if r['disclosure_before_first_cta'] else 'FAIL: missing or after first CTA')}; this is not a rendered mobile/desktop check.",'- Blockers: sitewide release checks; '+('Mode A: dated Stage-1 test logs and substantiation absent; editorial Mode B upgrade required by this project gate. ' if r['stage'] in (4,5) else '')+('Stage 3 reference scaffold, actual Elementor layout and integrations not built. ' if r['stage']==3 else '')+('owner/legal/operational review required. ' if r['stage']==2 else '')+('Structural parent and public-routing plan unresolved. ' if r['stage']==6 else '')+f"Visible owner markers: {r['owner_confirm_count']}; unresolved verify tokens: {len(r['verify_tokens'])}."]
    if r['verify_tokens']:
        lines.append('- Unresolved tokens:')
        for token in r['verify_tokens']: lines.append('  - `'+token+'`')
    lines.append('')
(OUT/'01_page_inventory_and_tokens.md').write_text('\n'.join(lines)+'\n')
print('pages',len(rows),'tokens distinct per page',sum(len(r['verify_tokens']) for r in rows),'ctas',sum(r['cta_count'] for r in rows),'bad order',sum(r['disclosure_before_first_cta'] is False for r in rows))
