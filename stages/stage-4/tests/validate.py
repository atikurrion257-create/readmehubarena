#!/usr/bin/env python3
"""Static editorial checks; requires PyYAML. Not evidence or browser verification."""
from pathlib import Path
import json,re,sys,yaml
root=Path(__file__).resolve().parents[1]
items={'systeme-io-review':('/saas/email-marketing/systeme-io-review/','T3',2200),'clickup-review':('/saas/project-management/clickup-review/','T3',2200),'best-for-freelancers':('/saas/project-management/best-for-freelancers/','T9',2500),'clickup-alternatives':('/saas/project-management/clickup-alternatives/','T10',2000),'how-to-choose-project-management-software':('/saas/project-management/how-to-choose-project-management-software/','T5',1800)}
checks=[];words={}
def check(n,v): checks.append({'name':n,'pass':bool(v)})
for name,(url,t,minimum) in items.items():
 s=(root/'pages'/f'{name}.md').read_text();fm=yaml.safe_load(s.split('---',2)[1]);copy=s.split('[rmh_breadcrumbs]',1)[1].split('## JSON-LD @graph',1)[0];h1=re.findall(r'^# (.+)$',copy,re.M); head=re.findall(r'^(#{1,6})\s',copy,re.M)
 # Body words include headings and tables, but not frontmatter, schema or shortcode syntax.
 clean=re.sub(r'\{\{VERIFY-PRICE:.*?\}\}|\[rmh_[^\]]+\]|https?://\S+|\]\([^)]*\)', '',copy)
 words[name]=len(re.findall(r"\b[\w’-]+\b",clean))
 check(name+' identity and draft/noindex',fm['url']==url and fm['template']==t and fm['stage']==4 and fm['status']=='draft' and fm['index'] is False)
 check(name+' seo lengths',len(fm['title_tag'])<=60 and len(fm['meta_description'])<=155)
 check(name+' evidence mode',fm['evidence_mode']=='mode_a_research' and 'Hands-On Testing NOT YET CONDUCTED' in s and fm['acf']['rmh_evidence_verified'] is False)
 check(name+' title hierarchy',len(h1)==1 and all(len(b)<=len(a)+1 for a,b in zip(head,head[1:])))
 faq=copy.split('## Questions ',1)[1].split('## Sources',1)[0]
 check(name+' question count',5<=len(re.findall(r'^### .*\?$',faq,re.M))<=8)
 check(name+' canonical',fm['canonical']=='https://www.readmehub.com'+url)
 graph=json.loads(re.search(r'```json\n(.*?)\n```',s,re.S).group(1)); page=[x for x in graph['@graph'] if x.get('@type')=='WebPage'];check(name+' graph',len(page)==1 and page[0]['name']==h1[0] and page[0]['url']==fm['canonical'] and not any(x.get('@type') in ('Review','Offer','FAQPage','AggregateRating') for x in graph['@graph']))
 tokens=set(re.findall(r'\{\{VERIFY-PRICE:.*?\}\}',copy));check(name+' tokens registered',tokens.issubset(set(fm['open_items'])))
 check(name+' tokens in blocker inventory',all(token in (root/'03_blockers_and_price_inventory.md').read_text() for token in tokens))
 if t!='T5':
  check(name+' disclosure precedes CTA',0 <= copy.find('[rmh_disclosure]') < copy.find('[rmh_affiliate_cta'))
  check(name+' CTA maps match',len(fm['cta_map'])==len(re.findall(r'\[rmh_affiliate_cta ',copy)))
  check(name+' evidence stamp','[rmh_evidence_stamp]' in copy)
 else:check(name+' no sales CTA','[rmh_affiliate_cta' not in copy and not fm['cta_map'])
 check(name+' target word count',words[name]>=minimum)
check('five Page Files',len(list((root/'pages').glob('*.md')))==5)
for x in ['systeme-io','clickup']:check(x+' worksheet',(root/'worksheets'/f'{x}.md').exists())
failed=[x['name'] for x in checks if not x['pass']]
(root/'tests/static-results.json').write_text(json.dumps({'scope':'Static checks only; word count approximate. No claims verified by this test, no site/browser tests.','body_word_counts':words,'checks':checks},indent=2)+'\n')
print(f'{len(checks)-len(failed)}/{len(checks)} checks passed; approximate copy words {words}')
for x in failed:print('FAIL',x)
sys.exit(bool(failed))
