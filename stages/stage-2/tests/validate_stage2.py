#!/usr/bin/env python3
"""Static content checks; install PyYAML separately. No site or browser checks."""
import json
import re
from pathlib import Path
import yaml

root = Path(__file__).resolve().parents[1]
expected = {'about.md': ('/about/', 'T14'), 'editorial-methodology.md': ('/editorial-methodology/', 'T15'),
            'affiliate-disclosure.md': ('/affiliate-disclosure/', 'T17'), 'contact.md': ('/contact/', 'T16'),
            'privacy-policy.md': ('/privacy-policy/', 'T18'), 'terms-of-service.md': ('/terms-of-service/', 'T19')}
results = []
def check(label, condition):
    results.append({'name': label, 'pass': bool(condition)})
for filename, (url, template) in expected.items():
    text = (root / 'pages' / filename).read_text()
    parts = text.split('---', 2)
    meta = yaml.safe_load(parts[1])
    body = parts[2].split('## JSON-LD @graph', 1)[0]
    schema_text = re.search(r'```json\s*(.*?)\s*```', text, re.S)
    schema = json.loads(schema_text.group(1)) if schema_text else {}
    headings = re.findall(r'^(#{1,6})\s+(.*)$', body, re.M)
    h1 = [h for level, h in headings if level == '#']
    nodes = schema.get('@graph', [])
    web_pages = [n for n in nodes if n.get('@type') == 'WebPage']
    check(filename + ': frontmatter and URL', meta['url'] == url and meta['template'] == template and meta['stage'] == 2 and meta['status'] == 'draft')
    check(filename + ': title and meta limits', len(meta['title_tag']) <= 60 and len(meta['meta_description']) <= 155)
    check(filename + ': canonical', meta['canonical'] == 'https://www.readmehub.com' + url)
    check(filename + ': H1 and headings', len(h1) == 1 and all(len(b[0]) <= len(a[0]) + 1 for a,b in zip(headings,headings[1:])))
    check(filename + ': single graph matches visible title', schema.get('@context') == 'https://schema.org' and len(web_pages) == 1 and web_pages[0]['name'] == h1[0] and web_pages[0]['url'] == meta['canonical'])
    check(filename + ': no commercial CTA', not meta['cta_map'] and '[rmh_affiliate_cta' not in body)
    check(filename + ': draft gate', 'BLOCK PUBLISH' in meta['publish_gate'])
    if filename in ('privacy-policy.md', 'terms-of-service.md'):
        check(filename + ': legal draft and owner markers', 'Draft pending legal review' in body and '[OWNER TO CONFIRM' in body)
blockers = (root / '02_blockers_and_owner_inputs.md').read_text()
for file in [root / '01_global_chrome_spec.md', *(root / 'pages').glob('*.md')]:
    for marker in set(re.findall(r'\[OWNER TO CONFIRM(?: WITH COUNSEL)?:[^\]]+\]', file.read_text())):
        check(file.name + ': blocker enumerated ' + marker, marker in blockers)
result = {'scope': 'Static file validation only; not a legal, live WordPress, link-resolution or browser test.', 'results': results}
(root / 'tests' / 'static-results.json').write_text(json.dumps(result, indent=2, ensure_ascii=False) + '\n')
failures = [r['name'] for r in results if not r['pass']]
print(f'{len(results)-len(failures)}/{len(results)} static checks passed')
for failure in failures:
    print('FAIL:', failure)
raise SystemExit(bool(failures))
