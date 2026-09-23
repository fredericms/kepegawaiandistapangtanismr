const {chromium}=require('playwright');
const fs=require('fs');
const root=process.cwd();
if(process.env.RUN_BROWSER_TESTS!=='1'){console.error('Gunakan hanya pada server lokal dengan database uji. Set RUN_BROWSER_TESTS=1.');process.exit(1);}
const checks=[];
function check(name,pass){checks.push({test:name,passed:!!pass});console.log((pass?'PASS ':'FAIL ')+name);if(!pass)throw new Error(name);}
(async()=>{
 const browser=await chromium.launch({headless:true,...(process.env.CHROMIUM_PATH ? {executablePath:process.env.CHROMIUM_PATH} : {}),args:['--disable-dev-shm-usage']});
 try{
  const context=await browser.newContext({viewport:{width:1440,height:1100},locale:'id-ID'});
  const page=await context.newPage();let errors=[];page.on('pageerror',e=>errors.push(e.message));
  page.on('console',msg=>{if(msg.type()==='error')errors.push(msg.text());});
  await page.goto('http://127.0.0.1:8080/');
  check('Tabel publik menampilkan 10 baris per halaman',await page.locator('#employee-table tbody tr:visible').count()===10);
  await page.screenshot({path:root+'/beranda-preview.png',fullPage:true});
  await page.locator('#table-search').fill('Frederic');
  check('Pencarian nama interaktif',await page.locator('#employee-table tbody tr:visible').count()===1);
  await page.locator('#table-search').fill('');await page.locator('#status-filter').selectOption('PPPK Paruh Waktu');
  check('Filter status menampilkan 28 pegawai',(await page.locator('#table-info').innerText()).includes('28 pegawai'));
  await page.locator('#next-page').click();check('Navigasi halaman berikutnya',(await page.locator('#page-info').innerText())==='2 / 3');
  await page.locator('#table-search').fill('tidak-ada-hasil-pencarian');check('Empty state terlihat',await page.locator('#empty-state').isVisible());
  await page.goto('http://127.0.0.1:8080/admin');
  check('URL /admin huruf kecil membuka login',page.url().endsWith('/admin/login.php'));
  await page.screenshot({path:root+'/login-preview.png',fullPage:true});
  await page.locator('[name=username]').fill('Eric');await page.locator('[name=password]').fill('ericaja13');
  await page.getByRole('button',{name:'Masuk ke Admin'}).click();await page.waitForURL('**/admin/');
  await page.screenshot({path:root+'/admin-preview.png',fullPage:true});
  await page.getByRole('button',{name:'Tambah pegawai'}).click();
  check('Dialog tambah pegawai tampil',await page.locator('#employee-dialog').isVisible());
  await page.locator('#employee-status').selectOption('PJLP');
  check('PJLP menyembunyikan serta menonaktifkan kolom NIP',!await page.locator('#nip-field').isVisible() && await page.locator('#employee-nip').isDisabled());
  check('PJLP menyediakan tanggal lahir manual',await page.locator('[name=tanggal_lahir_manual]').isVisible());
  await page.locator('#employee-status').selectOption('PPPK Penuh Waktu');
  check('PPPK menampilkan NIPPPK dan bulan mulai ASN',await page.locator('#nip-label').innerText()==='NIPPPK' && await page.locator('#asn-field').isVisible());
  await page.locator('#employee-status').selectOption('PNS');await page.locator('#employee-nip').fill('20010513 202506 1 007');
  check('Pratinjau NIP menampilkan tanggal lahir',(await page.locator('#nip-preview').innerText()).includes('13 Mei 2001'));
  await page.locator('#employee-dialog').screenshot({path:root+'/form-preview.png'});
  await page.locator('#employee-dialog [data-close]').first().click();
  await page.getByRole('link',{name:'Kelola admin'}).click();check('Manajemen admin tetap dalam halaman Admin',page.url().includes('/admin/?manage=1') && await page.locator('#admin-dialog').isVisible());
  await page.locator('#admin-dialog [data-close]').click();
  await page.setViewportSize({width:390,height:844});await page.goto('http://127.0.0.1:8080/');
  await page.screenshot({path:root+'/mobile-preview.png',fullPage:true});
  check('Beranda mobile tidak overflow horizontal',await page.evaluate(()=>document.documentElement.scrollWidth<=window.innerWidth));
  await page.goto('http://127.0.0.1:8080/admin/');
  check('Admin mobile tidak overflow horizontal',await page.evaluate(()=>document.documentElement.scrollWidth<=window.innerWidth));
  await page.getByRole('button',{name:'Tambah pegawai'}).click();
  check('Dialog mobile muat dalam viewport',await page.locator('#employee-dialog').evaluate(el=>{const r=el.getBoundingClientRect();return r.left>=0&&r.right<=window.innerWidth}));
  check('Tidak ada error JavaScript atau pelanggaran CSP',errors.length===0);
 }finally{await browser.close();fs.writeFileSync(root+'/hasil_browser.json',JSON.stringify({passed:checks.filter(x=>x.passed).length,failed:checks.filter(x=>!x.passed).length,results:checks},null,2));}
})().catch(e=>{console.error(e);process.exit(1)});
