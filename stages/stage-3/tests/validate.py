#!/usr/bin/env python3
"""Static offline checks only. Requires PyYAML."""
from pathlib import Path
import json, re, sys
import yaml
root=Path(__file__).resolve().parents[1]
expected={'home':('/','T1'),'tools':('/tools/','T2'),'blog':('/blog/','T4'),'comparisons':('/comparisons/','T7'),'deals':('/deals/','T11'),'search':('/search/','T13'),'404':('404','T20'),'saas':('/saas/','T6'),'project-management':('/saas/project-management/','T6'),'email-marketing':('/saas/email-marketing/','T6'),'seo-marketing':('/saas/seo-marketing/','T6'),'ai-writing':('/saas/ai-writing/','T6'),'crm':('/saas/crm/','T6'),'hosting':('/hosting/','T6'),'managed-wordpress':('/hosting/managed-wordpress/','T6'),'small-business':('/hosting/wordpress/small-business/','T6')}
checks=[]
def check(name,value): checks.append({'name':name,'pass':bool(value)})
for name,(url,t) in expected.items():
 s=(root/'pages'/f'{name}.md').read_text(); fm=yaml.safe_load(s.split('---',2)[1]); body=s.split('## Page copy\n',1)[1].split('## JSON-LD @graph',1)[0]
 h1=re.findall(r'^# (.+)$',body,re.M); headings=re.findall(r'^(#{1,6})\s+.+$',body,re.M)
 check(name+' metadata',fm['url']==url and fm['template']==t and fm['status']=='draft' and 'BLOCK PUBLISH' in fm['publish_gate'])
 check(name+' seo lengths',len(fm['title_tag'])<=60 and len(fm['meta_description'])<=155)
 check(name+' h1 and heading hierarchy',len(h1)==1 and all(len(b)<=len(a)+1 for a,b in zip(headings,headings[1:])))
 check(name+' links', bool(fm['internal_links']) and all(x.startswith('/') for x in fm['internal_links']))
 check(name+' no commercial cta',not fm['cta_map'] and '[rmh_affiliate_cta' not in body)
 check(name+' indexing gate',name=='home' or fm['index'] is False)
 check(name+' tree and dynamic tags','## Elementor container tree' in s and '(D)' in s)
 if name != '404':
  match=re.search(r'```json\n(.*?)\n```',s,re.S); graph=json.loads(match.group(1)); pages=[v for v in graph['@graph'] if v.get('@type')=='WebPage']; check(name+' schema',len(pages)==1 and pages[0]['name']==h1[0] and pages[0]['url']==fm['canonical'])
 if t=='T6': check(name+' Q&A and grid',len(re.findall(r'^### .*\?$',body,re.M))>=3 and '[rmh_dynamic_cluster_grid' in body)
check('registry page count',len(list((root/'pages').glob('*.md')))==len(expected))
check('T12 skeleton exists',(root/'02_T12_single_deal_skeleton.md').exists())
check('small-business slug','cluster="small-business-wordpress"' in (root/'pages/small-business.md').read_text())
failed=[c['name'] for c in checks if not c['pass']]
out={'scope':'Static file checks only: no WordPress, rendering, accessibility, link resolution, schema output, legal or browser testing.','checks':checks}
(root/'tests/static-results.json').write_text(json.dumps(out,indent=2)+'\n')
print(f'{len(checks)-len(failed)}/{len(checks)} checks passed')
for x in failed: print('FAIL',x)
sys.exit(bool(failed))
