#!/usr/bin/env python3
"""Static file checks only; requires PyYAML. It does not establish source accuracy or publication readiness."""
from pathlib import Path
import json,re,sys,yaml
root=Path(__file__).resolve().parents[1]
expected={'best-managed-wordpress-hosting':('/hosting/managed-wordpress/best-managed-wordpress-hosting/','T9',2500),'kinsta-review':('/hosting/managed-wordpress/kinsta-review/','T3',2200),'wp-engine-review':('/hosting/managed-wordpress/wp-engine-review/','T3',2200),'cloudways-review':('/hosting/managed-wordpress/cloudways-review/','T3',2200),'best-wordpress-hosting-for-small-business':('/hosting/wordpress/small-business/best-wordpress-hosting-for-small-business/','T9',2500),'systeme-io-alternatives':('/saas/email-marketing/systeme-io-alternatives/','T10',2000),'clickup-vs-asana':('/saas/project-management/clickup-vs-asana/','T8',2200),'semrush-review':('/saas/seo-marketing/semrush-review/','T3',2200),'semrush-vs-ahrefs':('/saas/seo-marketing/semrush-vs-ahrefs/','T8',2200),'jasper-review':('/saas/ai-writing/jasper-review/','T3',2200),'best-ai-writing-tools-for-bloggers':('/saas/ai-writing/best-ai-writing-tools-for-bloggers/','T9',2500),'hubspot-review':('/saas/crm/hubspot-review/','T3',2200)}
checks=[]; counts={}
def ck(name,ok):checks.append({'name':name,'pass':bool(ok)})
for name,(url,template,minimum) in expected.items():
 s=(root/'pages'/f'{name}.md').read_text();fm=yaml.safe_load(s.split('---',2)[1]); body=s.split('[rmh_breadcrumbs]',1)[1].split('## JSON-LD @graph',1)[0]
 h1=re.findall(r'^# (.+)$',body,re.M);hs=re.findall(r'^(#{1,6})\s',body,re.M);faq=body.split('## Buyer questions',1)[1].split('## Sources',1)[0];tokens=set(re.findall(r'\{\{VERIFY-(?:PRICE|FACT):.*?\}\}',body))
 clean=re.sub(r'\{\{VERIFY-(?:PRICE|FACT):.*?\}\}|\[rmh_[^\]]+\]|https?://\S+|\]\([^)]*\)','',body);counts[name]=len(re.findall(r"\b[\w’-]+\b",clean))
 ck(name+' registry/draft',fm['url']==url and fm['template']==template and fm['status']=='draft' and fm['index'] is False)
 ck(name+' SEO',len(fm['title_tag'])<=60 and len(fm['meta_description'])<=155 and fm['canonical']=='https://www.readmehub.com'+url)
 ck(name+' evidence',fm['evidence_mode']=='mode_a_research' and not fm['acf']['rmh_evidence_verified'] and '[rmh_evidence_stamp]' in body)
 ck(name+' H1/heading hierarchy',len(h1)==1 and all(len(b)<=len(a)+1 for a,b in zip(hs,hs[1:])))
 ck(name+' buyer FAQ count',5<=len(re.findall(r'^### .*\?$',faq,re.M))<=8)
 ck(name+' disclosure precedes CTA','[rmh_disclosure]' in body and ('[rmh_affiliate_cta' not in body or body.index('[rmh_disclosure]')<body.index('[rmh_affiliate_cta')))
 ck(name+' CTA map',len(fm['cta_map'])==len(re.findall(r'\[rmh_affiliate_cta ',body)))
 ck(name+' price markers',tokens.issubset(set(fm['open_items'])) and bool(tokens))
 ck(name+' blocker inventory',all(token in (root/'03_blockers_and_token_inventory.md').read_text() for token in tokens))
 ck(name+' internal-link manifest',set(re.findall(r'\]\((/[^)]+)\)',body)).issubset(set(fm['internal_links'])))
 ck(name+' pricing terms separated','Introductory monthly' in body and 'Renewal monthly' in body and 'Renewal annual' in body)
 g=json.loads(re.search(r'```json\n(.*?)\n```',s,re.S).group(1));page=[v for v in g['@graph'] if v.get('@type')=='WebPage'];ck(name+' graph',len(page)==1 and page[0]['name']==h1[0] and page[0]['url']==fm['canonical'] and all(n.get('@type') not in ('Review','FAQPage','Offer','AggregateRating') for n in g['@graph']))
 ck(name+' target minimum word count',counts[name]>=minimum)
 if url.startswith('/hosting/'):ck(name+' matched WordPress scenario','WordPress' in body and ('same' in body.lower()) and 'backup' in body.lower())
ck('all 12 registry files',len(list((root/'pages').glob('*.md')))==12)
ck('no Ahrefs or Hostinger affiliate CTA',not any(re.search(r'\[rmh_affiliate_cta tool="(?:ahrefs|hostinger)"',p.read_text()) for p in (root/'pages').glob('*.md')))
result={'scope':'Static file checks only. Word counts approximate; no rendering, live staging, claim verification or legal test.','body_word_counts':counts,'checks':checks}
(root/'tests/static-results.json').write_text(json.dumps(result,indent=2)+'\n');fails=[x['name'] for x in checks if not x['pass']]
print(f'{len(checks)-len(fails)}/{len(checks)} passed');print('\n'.join('FAIL '+s for s in fails));sys.exit(bool(fails))
