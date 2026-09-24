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
try:
    public=Client();code,s,h=public.get();check('Beranda publik dapat diakses',code==200)
    check('Beranda tidak memuat pintasan Admin', '/admin' not in s and '/Admin' not in s)
    check('NIP dan tanggal lahir lengkap tidak bocor di HTML publik','200105132025061007' not in s and '20010513 202506 1 007' not in s and '13 Mei 2001' not in s)
    check('Publik tidak menerima mutasi',public.post('/',{'action':'delete_employee','id':1})[0]==405)
    check('Admin tanpa login diarahkan ke login',public.get('/admin/')[0]==303)
    check('CRUD tanpa autentikasi ditolak',public.post('/admin/',{'action':'delete_employee','id':1})[0]==303)
    check('Pintasan tambah admin membutuhkan login',public.get('/admin/tambah-admin.php')[0]==303)
    for path in ['/config.php','/functions.php','/kepegawaian_distapangtani.sql','/migrasi.sql','/tests.php','/test_http.py']:
        check('Berkas internal tidak tersedia: '+path,public.get(path)[0] in (403,404))
    check('Header CSP, anti-frame dan nosniff tersedia','frame-ancestors \'none\'' in h.get('Content-Security-Policy','') and h.get('X-Frame-Options')=='DENY' and h.get('X-Content-Type-Options')=='nosniff')
    a=Client();_,page,h=a.get('/admin/login.php');cookie_before=list(a.cookies)[0].value
    check('Cookie HttpOnly dan SameSite=Lax','HttpOnly' in h.get('Set-Cookie','') and 'SameSite=Lax' in h.get('Set-Cookie',''))
    check('Login tanpa CSRF ditolak',a.post('/admin/login.php',{'username':'Eric','password':'ericaja13'})[0]==403)
    wrong=login(a,'Eric','wrong'); unknown=login(a,'DoesNotExist','wrong')
    generic='Username atau password salah, atau akses sementara tidak tersedia. Silakan coba lagi.'
    check('Pesan generik sama untuk username/password salah',wrong[0]==unknown[0]==401 and generic in wrong[1] and generic in unknown[1])
    check('SQL injection login gagal',login(a,"' OR 1=1 --",'wrong')[0]==401)
    check('Login superadmin bawaan berhasil',login(a)[0]==303)
    check('Session ID berubah setelah login',list(a.cookies)[0].value!=cookie_before)
    code,s,_=a.get('/admin/');check('Tabel privat mengekstrak NIP contoh',code==200 and '13 Mei 2001' in s and 'Juni 2025' in s and '20010513 202506 1 007' in s)
    check('NIPPPK 202521 menunggu input manual','Belum diisi' in s)
    check('CRUD tanpa CSRF ditolak',a.post('/admin/',{'action':'delete_employee','id':1})[0]==403)
    record={'action':'save_employee','id':'','row_version':1,'nama':'TEST PNS','jabatan':'Penguji','status_kepegawaian':'PNS','nip':'20010513 202506 1 998'}
    check('Tambah PNS berhasil',action(a,record)[0]==303);r=employee(a,'TEST PNS')
    check('NIP duplikat ditolak',action(a,dict(record,nama='TEST Duplikat'))[0]==422)
    check('NIP 17 digit ditolak server',action(a,dict(record,nip='20010513202506100'))[0]==422)
    check('Tanggal NIP tidak valid ditolak',action(a,dict(record,nip='200102312025061998'))[0]==422)
    edited=dict(record,id=r['id'],row_version=r['row_version'],nama='TEST PPPK',nip='200105132025211998',status_kepegawaian='PPPK Penuh Waktu',mulai_asn_manual='')
    bad=action(a,edited);check('PPPK tanpa bulan ditolak dan ID edit dipertahankan',bad[0]==422 and f'name="id" value="{r["id"]}"' in bad[1])
    edited['mulai_asn_manual']='2025-07';check('Ubah PNS ke PPPK dengan bulan manual',action(a,edited)[0]==303)
    check('Usia/tanggal pengangkatan PPPK tampil benar','Juli 2025' in row(a.get('/admin/')[1],'TEST PPPK'))
    check('Edit dari versi lama ditolak',action(a,edited)[0]==422)
    check('Hapus dari versi lama ditolak',action(a,{'action':'delete_employee','id':r['id'],'row_version':r['row_version']})[0]==422)
    r=employee(a,'TEST PPPK');check('Hapus pegawai berhasil',action(a,dict(r,action='delete_employee'))[0]==303)
    for name in ['TEST PJLP A','TEST PJLP B']:
        check('Tambah '+name+' tanpa NIP',action(a,dict(record,nama=name,status_kepegawaian='PJLP',nip=''))[0]==303)
    check('PJLP menampilkan tanda strip di nomor','class="nip">-</td>' in row(a.get('/admin/')[1],'TEST PJLP A'))
    payload='TEST <script>alert(1)</script>'
    check('Nama tersimpan aman sebagai teks',action(a,dict(record,nama=payload,status_kepegawaian='PJLP',nip=''))[0]==303)
    check('Stored XSS di Admin di-escape',payload not in a.get('/admin/')[1] and '&lt;script&gt;' in a.get('/admin/')[1])
    check('Identitas dan stored XSS tidak dikirim ke publik',payload not in public.get()[1] and '&lt;script&gt;' not in public.get()[1])
    for name in ['TEST PJLP A','TEST PJLP B',payload]:action(a,dict(employee(a,name),action='delete_employee'))
    addadmin={'action':'save_admin','id':'','auth_version':1,'nama_admin':'TEST Operator','username':'test.operator','password':'TestPassword123!','aktif':'1','role':'superadmin'}
    check('Superadmin dapat menambahkan admin',action(a,addadmin)[0]==303)
    check('Username duplikat ditolak',action(a,addadmin)[0]==422)
    b=Client();check('Admin biasa dapat login',login(b,'test.operator','TestPassword123!')[0]==303)
    check('Admin biasa tidak mendapat kontrol kelola admin','Kelola admin' not in b.get('/admin/')[1])
    check('Hak superadmin dari parameter role diabaikan',b.get('/admin/?manage=1')[0]==403)
    check('Admin biasa tidak dapat membuat admin',action(b,addadmin)[0]==403)
    check('Admin biasa dapat CRUD pegawai',action(b,dict(record,nama='TEST Operator CRUD',status_kepegawaian='PJLP'))[0]==303)
    action(b,dict(employee(b,'TEST Operator CRUD'),action='delete_employee'))
    aid=account(a,'TEST Operator');check('Admin dinonaktifkan',action(a,dict(addadmin,id=aid['id'],auth_version=aid['auth_version'],password='',aktif='0'))[0]==303)
    check('Sesi admin nonaktif langsung dicabut',b.get('/admin/')[0]==303)
    check('Admin nonaktif tidak dapat login',login(b,'test.operator','TestPassword123!')[0]==401)
    aid=account(a,'TEST Operator');action(a,dict(addadmin,id=aid['id'],auth_version=aid['auth_version'],password='',aktif='1'))
    check('Admin aktif kembali dapat login',login(b,'test.operator','TestPassword123!')[0]==303)
    check('Ganti password dengan password lama salah ditolak',action(b,{'action':'change_password','old_password':'wrong','password':'NewPassword123!'})[0]==422)
    b2=Client();login(b2,'test.operator','TestPassword123!')
    check('Ganti password sendiri berhasil',action(b,{'action':'change_password','old_password':'TestPassword123!','password':'NewPassword123!'})[0]==303)
    check('Ganti password mencabut sesi lain',b2.get('/admin/')[0]==303)
    check('Sesi pengubah password tetap berlaku',b.get('/admin/')[0]==200)
    # Akun superadmin (id 1) tidak memiliki tombol hapus; kirim request langsung.
    protect={'action':'delete_admin','id':1,'auth_version':1}
    check('Superadmin terakhir tidak dapat dihapus',action(a,protect)[0]==422)
    check('Superadmin tidak dapat dinonaktifkan',action(a,{'action':'save_admin','id':1,'auth_version':1,'nama_admin':'Eric','username':'Eric','password':'','aktif':'0'})[0]==422)
    check('Logout lewat GET ditolak',b.get('/admin/logout.php')[0]==405)
    _,s,_=b.get('/admin/');check('Logout POST berhasil',b.post('/admin/logout.php',{'csrf':csrf(s)})[0]==303)
    check('Halaman Admin tidak tersedia setelah logout',b.get('/admin/')[0]==303)
    for i in range(5):check('Gagal login berurutan '+str(i+1),login(b,'test.operator','wrong')[0]==401)
    check('Password benar ditolak selama akun terkunci',login(b,'test.operator','NewPassword123!')[0]==401)
    aid=account(a,'TEST Operator');check('Superadmin dapat membuka kunci akun',action(a,dict(aid,action='unlock_admin'))[0]==303)
    check('Akun dapat login setelah dibuka',login(b,'test.operator','NewPassword123!')[0]==303)
    aid=account(a,'TEST Operator');check('Superadmin dapat menghapus admin',action(a,dict(aid,action='delete_admin'))[0]==303)
    check('Sesi akun terhapus dicabut',b.get('/admin/')[0]==303)
    # Melebihi jendela IP dengan username tak dikenal; tidak mengunci akun Eric.
    for i in range(31):login(Client(),'unknown.rate','wrong')
    check('Batas IP berlaku lintas sesi dan username',login(Client())[0]==401)
except Exception as ex:
    print('ERROR',repr(ex),flush=True)
    results.append({'test':'Unhandled failure','passed':False,'detail':str(ex)})
finally:
    out={'passed':sum(x['passed'] for x in results),'failed':sum(not x['passed'] for x in results),'results':results}
    open(os.getenv('HTTP_TEST_REPORT','hasil_http.json'),'w').write(json.dumps(out,indent=2,ensure_ascii=False))
    print(f"\n{out['passed']} passed, {out['failed']} failed")
    sys.exit(1 if out['failed'] else 0)
