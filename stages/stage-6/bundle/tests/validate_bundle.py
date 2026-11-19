#!/usr/bin/env python3
"""Portable integrity checks; no WordPress, visual, legal or publication validation."""
import hashlib,json,re,sys
from pathlib import Path
root=Path(__file__).resolve().parents[1]
m=json.loads((root/'content/draft-manifest.json').read_text()); pages=m['pages']; errors=[]
def ck(label,yes):
 if not yes: errors.append(label)
ck('39 unique draft paths',len(pages)==39 and len({p['draft_path'] for p in pages})==39)
ck('stage counts',{n:sum(x['stage']==n for x in pages) for n in (2,3,4,5,6)}=={2:6,3:15,4:5,5:12,6:1})
paths={x['draft_path'] for x in pages}
ck('no custom 404 page','/404/' not in paths)
ck('home separate','/readmehub-home-draft/' in paths)
ck('intermediate and exact hierarchy',all(p in paths for p in ['/hosting/wordpress/','/hosting/wordpress/small-business/','/hosting/wordpress/small-business/best-wordpress-hosting-for-small-business/']))
for p in pages:
 name=p['draft_path'];source=p['source']
 if source!='assembly/intermediate-parent': ck(name+' source hash',hashlib.sha256((root/source).read_bytes()).hexdigest()==p['source_sha256'])
 ck(name+' publish blocked',p['publish_blocked'] is True)
 if p['stage']!=6: ck(name+' single H1',len(re.findall(r'<h1(?:\s[^>]*)?>',p['content']))==1)
 if p['stage']==3:ck(name+' scaffold notice', 'STAGING SCAFFOLD ONLY' in p['content'])
 if p['stage'] in (4,5) and '[rmh_affiliate_cta' in p['content']:ck(name+' disclosure before CTA',0<=p['content'].find('[rmh_disclosure]')<p['content'].find('[rmh_affiliate_cta'))
 ck(name+' no forbidden affiliate CTA',not re.search(r'\[rmh_affiliate_cta tool="(?:ahrefs|hostinger)"',p['content']))
 ck(name+' no schema pasted','"@graph"' not in p['content'] and '<script' not in p['content'].lower())
ck('plugin ZIP', (root/'plugin/readmehub-core-dynamic.zip').exists())
print(f'{"PASS" if not errors else "FAIL"}: {len(pages)} draft records; {len(errors)} problems')
for x in errors:print('FAIL',x)
sys.exit(bool(errors))
