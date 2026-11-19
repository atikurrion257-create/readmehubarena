#!/usr/bin/env python3
"""Rebuild the draft manifest from bundled Page Files. Requires PyYAML, markdown-it-py and BeautifulSoup4; not needed for import."""
from pathlib import Path
import hashlib, json, re
import yaml
from markdown_it import MarkdownIt
from bs4 import BeautifulSoup

root = Path(__file__).resolve().parents[1]
md = MarkdownIt('commonmark', {'html': False}).enable('table')
acf = json.loads((root / 'acf/acf-export-readmehub.json').read_text())
acf_keys = {f['name']:f['key'] for group in acf for f in group['fields']}

def blocks(text):
    html = md.render(text)
    soup = BeautifulSoup(html, 'html.parser')
    output = []
    for el in soup.contents:
        if getattr(el, 'name', None) is None: continue
        tag = el.name
        rendered = str(el)
        if tag == 'p':
            inner = el.decode_contents().strip()
            if re.fullmatch(r'\[rmh_[a-z_]+(?:\s[^\]]*)?\]', inner):
                output.append('<!-- wp:shortcode -->\n' + inner.replace('&quot;', '"').replace('&#x27;', "'") + '\n<!-- /wp:shortcode -->')
            else:
                output.append('<!-- wp:paragraph -->\n' + rendered + '\n<!-- /wp:paragraph -->')
        elif tag in ('h1','h2','h3','h4','h5','h6'):
            level = int(tag[1]); attrs = '' if level == 2 else ' ' + json.dumps({'level':level}, separators=(',',':'))
            output.append('<!-- wp:heading' + attrs + ' -->\n' + rendered + '\n<!-- /wp:heading -->')
        elif tag in ('ul','ol'):
            attrs = ' {"ordered":true}' if tag == 'ol' else ''
            output.append('<!-- wp:list' + attrs + ' -->\n' + rendered + '\n<!-- /wp:list -->')
        else:
            # One table/blockquote/etc. per native Custom HTML block, never an entire page.
            output.append('<!-- wp:html -->\n' + rendered + '\n<!-- /wp:html -->')
    return '\n\n'.join(output)

pages = []
for stage in (2,3,4,5):
    for path in sorted((root / f'content/stage-{stage}/pages').glob('*.md')):
        if stage == 3 and path.stem == '404': continue # a theme response, not a page
        raw = path.read_text()
        _, front, main = raw.split('---',2)
        meta = yaml.safe_load(front)
        if stage == 3:
            main = main.split('## Page copy',1)[1]
        else:
            main = main[main.index('[rmh_breadcrumbs]'):]
        main = main.split('## JSON-LD @graph',1)[0].strip()
        h1 = re.findall(r'^# (.+)$',main,re.M)
        if len(h1) != 1: raise ValueError(f'{path}: expected one H1')
        url = meta['url']; target = '/readmehub-home-draft/' if url == '/' else url
        if not re.fullmatch(r'/(?:[a-z0-9-]+/)+', target): raise ValueError(target)
        payload = blocks(main)
        # The Stage 3 text includes assembly instructions/conditional states and is a draft scaffold, not an Elementor page.
        if stage == 3:
            payload = '<!-- wp:paragraph -->\n<p>STAGING SCAFFOLD ONLY — build and review the Elementor Free layout from the bundled Page File before use.</p>\n<!-- /wp:paragraph -->\n\n' + payload
        fields = {k:v for k,v in meta.get('acf',{}).items() if k in acf_keys and v not in ('',None,False)}
        pages.append({'source':str(path.relative_to(root)),'source_sha256':hashlib.sha256(raw.encode()).hexdigest(), 'registry_url':url,'draft_path':target,'post_title':h1[0], 'template':meta['template'], 'stage':stage,'content':payload,'acf':fields,'acf_field_keys':{k:acf_keys[k] for k in fields}, 'seo_title':meta['title_tag'],'seo_description':meta['meta_description'],'publish_blocked':True})
# Neutral draft parent makes the locked hierarchy possible without publishing a utility page.
pages.append({'source':'assembly/intermediate-parent','source_sha256':'','registry_url':None,'draft_path':'/hosting/wordpress/','post_title':'WordPress hosting (structural parent)','template':'structural-parent','stage':6,'content':'<!-- wp:paragraph -->\n<p>Draft structural parent for the approved small-business hosting hierarchy. Do not publish or redirect without separate review.</p>\n<!-- /wp:paragraph -->','acf':{},'acf_field_keys':{},'seo_title':'','seo_description':'','publish_blocked':True})
pages.sort(key=lambda p:(p['draft_path'].count('/'),p['draft_path']))
manifest={'version':1,'description':'Offline staging drafts only; no publication or home settings. Stage 3 pages are scaffolds and 404 is not a page.','pages':pages}
(root/'content/draft-manifest.json').write_text(json.dumps(manifest,indent=2,ensure_ascii=False)+'\n')
print(f'Generated {len(pages)} draft records')
