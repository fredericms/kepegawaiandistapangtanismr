"""Black-box regression suite. Jalankan HANYA pada database pengujian terpisah.
Python 3 stdlib; tidak memerlukan paket tambahan.
Contoh: RUN_HTTP_TESTS=1 python3 test_http.py http://127.0.0.1:8080
Membuat/mengubah/menghapus data uji dan menguji rate limit.
"""
import os,sys,re,json,html,urllib.request,urllib.parse,urllib.error,http.cookiejar
from html.parser import HTMLParser
if os.getenv('RUN_HTTP_TESTS')!='1':
    sys.exit('Set RUN_HTTP_TESTS=1 setelah server diarahkan ke database uji kosong yang diimpor dari SQL paket.')
BASE=(sys.argv[1] if len(sys.argv)>1 else 'http://127.0.0.1:8080').rstrip('/')
if urllib.parse.urlparse(BASE).hostname not in ('127.0.0.1','localhost'):
    sys.exit('Pengujian mutasi dibatasi ke localhost.')
class NoRedirect(urllib.request.HTTPRedirectHandler):
    def redirect_request(self,*args,**kwargs): return None
class Client:
    def __init__(self):
        self.cookies=http.cookiejar.CookieJar(); self.opener=urllib.request.build_opener(urllib.request.ProxyHandler({}),urllib.request.HTTPCookieProcessor(self.cookies),NoRedirect())
    def req(self,path='/',data=None):
        req=urllib.request.Request(BASE+path,data=urllib.parse.urlencode(data).encode() if data is not None else None)
        try:r=self.opener.open(req)
        except urllib.error.HTTPError as ex:r=ex
        return r.code,r.read().decode(),r.headers
    def get(self,path='/'):return self.req(path)
    def post(self,path,data):return self.req(path,data)
class Inputs(HTMLParser):
    def __init__(self,s): super().__init__();self.fields={};self.feed(s)
    def handle_starttag(self,tag,attrs):
        a=dict(attrs)
        if tag=='input' and a.get('name'):self.fields[a['name']]=a.get('value','')
def csrf(s): return re.search(r'name="csrf" value="([a-f0-9]+)"',s).group(1)
def row(s,name):
    for r in re.findall(r'<tr\b[^>]*>.*?</tr>',s,re.S):
        if html.escape(name,quote=True) in r or name in html.unescape(r):return r
    raise AssertionError('Row missing: '+name)
def hidden(s):return Inputs(s).fields
def login(c,user='Eric',password='ericaja13'):
    _,s,_=c.get('/admin/login.php');return c.post('/admin/login.php',{'csrf':csrf(s),'username':user,'password':password})
def action(c,data):
    code,s,_=c.get('/admin/');assert code==200
    return c.post('/admin/',dict(data,csrf=csrf(s)))
def employee(c,name):
    code,s,_=c.get('/admin/');assert code==200;return hidden(row(s,name))
def account(c,name):
    code,s,_=c.get('/admin/?manage=1');assert code==200;return hidden(row(s,name))
results=[]
def check(label,condition):
    results.append({'test':label,'passed':bool(condition)});print(('PASS ' if condition else 'FAIL ')+label,flush=True)
    if not condition:raise AssertionError(label)
a=Client();record=None
try:
    check('Login pengelola untuk pendidikan',login(a)[0]==303)
    record={'action':'save_employee','id':'','row_version':1,'nama':'TEST Pendidikan, S.P., M.Si','jabatan':'Penguji','status_kepegawaian':'PJLP','nip':'','pendidikan_terakhir':'auto'}
    check('Tambah pendidikan otomatis berhasil',action(a,record)[0]==303)
    r=employee(a,record['nama'])
    check('Pendidikan S2 disimpan dan tampil','Magister (S2)' in row(a.get('/admin/')[1],record['nama']))
    record.update(id=r['id'],row_version=r['row_version'],pendidikan_terakhir='SD/sederajat')
    check('Koreksi manual berhasil',action(a,record)[0]==303)
    check('Manual mengalahkan gelar S2','SD/sederajat' in row(a.get('/admin/')[1],record['nama']))
    r=employee(a,record['nama']);record.update(row_version=r['row_version'],nama='TEST Pendidikan, Ph.D.')
    check('Nama berubah tanpa menimpa pilihan manual',action(a,record)[0]==303 and 'SD/sederajat' in row(a.get('/admin/')[1],record['nama']))
    r=employee(a,record['nama']);record.update(row_version=r['row_version'],pendidikan_terakhir='auto')
    check('Mode otomatis dapat diterapkan ulang',action(a,record)[0]==303 and 'Doktor (S3)' in row(a.get('/admin/')[1],record['nama']))
    r=employee(a,record['nama']);record.update(row_version=r['row_version'])
    check('Nilai pendidikan palsu ditolak',action(a,dict(record,pendidikan_terakhir='POSTDOC'))[0]==422)
    invalid=dict(record);invalid.pop('pendidikan_terakhir');invalid['pendidikan_terakhir[]']='SD/sederajat'
    check('Pilihan multiple array ditolak',action(a,invalid)[0]==422)
    for name,level in [('Ir. TEST Pendidikan','Diploma IV/Sarjana (S1)'),('drh. TEST Pendidikan','Program Profesi Dokter Hewan'),('drh. TEST Pendidikan, M.Si.','Magister (S2)')]:
        r=employee(a,record['nama']);record.update(row_version=r['row_version'],nama=name,pendidikan_terakhir='auto')
        check('Simpan otomatis '+name,action(a,record)[0]==303 and level in row(a.get('/admin/')[1],record['nama']))
    for level in ['SD/sederajat','SMP/sederajat','SMA/sederajat','Diploma I - Diploma III','Diploma IV/Sarjana (S1)','Magister (S2)','Doktor (S3)','Program Profesi Dokter Hewan']:
        r=employee(a,record['nama']);record.update(row_version=r['row_version'],pendidikan_terakhir=level)
        check('Simpan pilihan '+level,action(a,record)[0]==303 and level in row(a.get('/admin/')[1],record['nama']))
    r=employee(a,record['nama']);record.update(row_version=r['row_version'],pendidikan_terakhir='auto',nama='TEST Pendidikan Tanpa Gelar')
    check('Tanpa gelar tidak dianggap pendidikan tertentu',action(a,record)[0]==303 and '<td>-</td>' in row(a.get('/admin/')[1],record['nama']))
except Exception as ex:
    results.append({'test':'Unhandled failure','passed':False,'detail':str(ex)});print('FAIL',ex)
finally:
    if record and record.get('id'):
        try:action(a,dict(employee(a,record['nama']),action='delete_employee'))
        except Exception:pass
    out={'passed':sum(x['passed'] for x in results),'failed':sum(not x['passed'] for x in results),'results':results}
    open(os.getenv('EDUCATION_HTTP_REPORT','hasil_pendidikan_http.json'),'w').write(json.dumps(out,indent=2,ensure_ascii=False))
    print(out['passed'],'passed,',out['failed'],'failed');sys.exit(1 if out['failed'] else 0)
