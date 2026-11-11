# -*- coding: utf-8 -*-
"""ZV_BALIKOVNY.csv -> balikovna-mista.json za temo.
Izpusti depoje in prazne vrstice; obdrzi poste, partnerje in bokse."""
import csv, io, json, sys, os, unicodedata
VIR = sys.argv[1] if len(sys.argv)>1 else 'balikovny/ZV_BALIKOVNY.csv'
CILJ= sys.argv[2] if len(sys.argv)>2 else 'balikovna-mista.json'
DNEVI=['PO','UT','ST','CT','PA','SO','NE']
def brez_sumnikov(s):
    return ''.join(c for c in unicodedata.normalize('NFD', s.lower()) if unicodedata.category(c)!='Mn')
raw=open(VIR,'rb').read().decode('cp1250')
ven=[]
for v in csv.DictReader(io.StringIO(raw), delimiter=';'):
    tip=(v.get('TYP') or '').strip()
    if tip in ('', 'depo'): continue          # depoji niso prevzemna mesta
    ure=[]
    for d in DNEVI:
        okna=[]
        for i in (1,2,3):
            od=(v.get('%s_OD%d'%(d,i)) or '').strip(); do=(v.get('%s_DO%d'%(d,i)) or '').strip()
            if od and do: okna.append(od+'-'+do)
        ure.append(','.join(okna))
    naziv=(v['NAZEV'] or '').strip(); naslov=(v['ADRESA'] or '').strip()
    ven.append({
        'id':   v['PSC'].strip(),
        'n':    naziv,
        'a':    naslov,
        't':    tip,
        'h':    ure,                                   # 7 nizov, prazen = zaprto
        'x':    v.get('SOUR_X','').strip(),
        'y':    v.get('SOUR_Y','').strip(),
        's':    brez_sumnikov(naziv+' '+naslov),       # polje za iskanje brez sumnikov
    })
json.dump(ven, open(CILJ,'w',encoding='utf-8'), ensure_ascii=False, separators=(',',':'))
print('zapisanih mest:', len(ven))
print('velikost:', round(os.path.getsize(CILJ)/1024/1024, 2), 'MB')
