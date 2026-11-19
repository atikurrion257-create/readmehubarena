#!/usr/bin/env python3
"""Guard coverage of offline QA inventory; never interprets results as release approval."""
import json,re,subprocess,sys
from pathlib import Path
r=Path(__file__).resolve().parents[2]; d=r/'stages/stage-7'
subprocess.run([sys.executable,str(d/'audit_offline.py')],check=True)
j=json.loads((d/'offline-inventory.json').read_text())['pages']; m=json.loads((r/'stages/stage-6/bundle/content/draft-manifest.json').read_text())['pages']
assert len(j)==len(m)==39 and len({x['draft_path'] for x in j})==39
assert all(x['release']=='BLOCK PUBLISH' for x in j)
assert sum(len(x['verify_tokens']) for x in j if x['stage']==4)==44
assert sum(len(x['verify_tokens']) for x in j if x['stage']==5)==84
assert sum(x['cta_count'] for x in j)==29 and not any(x['disclosure_before_first_cta'] is False for x in j)
report=(d/'03_template_shortcode_and_staging_test_plan.md').read_text()
for n in range(1,21): assert re.search(r'\| T'+str(n)+r'\b',report), f'missing T{n}'
registered=[]
for p in (r/'stages/stage-1/readmehub-core-dynamic/inc').glob('shortcodes*.php'):
    registered += re.findall(r"add_shortcode\(\s*'([^']+)'",p.read_text())
assert len(registered)==18
for name in registered: assert '`'+name+'`' in report, 'missing '+name
assert (r/'stages/stage-3/pages/404.md').exists()
print('PASS: 39 records, 128 token pairs, all 20 templates and 18 registered shortcodes inventoried; no release testing implied')
