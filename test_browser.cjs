const {chromium}=require('playwright');
const fs=require('fs');
const root=process.cwd();
if(process.env.RUN_BROWSER_TESTS!=='1'){console.error('Gunakan hanya pada server lokal dengan database uji. Set RUN_BROWSER_TESTS=1.');process.exit(1);}
const checks=[];
function check(name,pass){checks.push({test:name,passed:!!pass});console.log((pass?'PASS ':'FAIL ')+name);if(!pass)throw new Error(name);}
if(process.env.PUBLIC_ANALYTICS_TESTS!=='1') (async()=>{
 const browser=await chromium.launch({headless:true,...(process.env.CHROMIUM_PATH ? {executablePath:process.env.CHROMIUM_PATH} : {}),args:['--disable-dev-shm-usage']});
 try{
  const context=await browser.newContext({viewport:{width:1440,height:1100},locale:'id-ID'});
  const page=await context.newPage();let errors=[];page.on('pageerror',e=>errors.push(e.message));
  page.on('console',msg=>{if(msg.type()==='error')errors.push(msg.text());});
  await page.goto('http://127.0.0.1:8080/');
  check('Direktori individual tidak tampil',await page.locator('#employee-table').count()===0 && await page.locator('#table-search').count()===0);
  const publicHtml=await page.content();
  check('Nama dan NIP tidak ada di HTML publik',!publicHtml.includes('Frederic Morado') && !publicHtml.includes('200105132025061007'));
  const data=JSON.parse(await page.locator('#public-dashboard').getAttribute('data-summary'));
  check('Total data sumber 129 pegawai',data.all.total===129);
  for(const [key,value] of Object.entries(data)){
    await page.locator('#demographic-status').selectOption(key);
    check('Filter dashboard '+key,Number(await page.locator('#demographic-total').innerText())===value.total);
    for(const group of ['generations','genders'])for(const [bucket,n] of Object.entries(value[group])){
      const text=await page.locator(`[data-group="${group}"][data-key="${bucket}"] output`).innerText();
      if(text!==`${n} pegawai`)throw new Error('Nilai grafik tidak sesuai '+key+group+bucket);
    }
  }
  check('PJLP kosong menampilkan empty state',await page.locator('#demographic-empty').isVisible());
  await page.locator('[data-metric=percent]').click();
  check('Persentase status kosong bernilai 0%',(await page.locator('[data-group="genders"][data-key="male"] output').innerText()).startsWith('0%'));
  await page.locator('#demographic-status').selectOption('PNS');
  const expected=new Intl.NumberFormat('id-ID',{maximumFractionDigits:1}).format(100*data.PNS.genders.male/data.PNS.total);
  check('Persentase memakai denominator status terpilih',(await page.locator('[data-group="genders"][data-key="male"] output').innerText()).startsWith(expected+'%'));
  check('Rekap gender per status dihapus',await page.locator('#gender-summary-table').count()===0);
  check('Grafik hanya memuat X Y Z dan dua gender',await page.locator('[data-group=generations]').count()===3 && await page.locator('[data-group=genders]').count()===2);
  await page.locator('#demographic-status').selectOption('all');await page.locator('[data-metric=count]').click();
  check('CSS Beranda memiliki fingerprint versi',/style\.css\?v=[a-f0-9]{12}$/.test(await page.locator('link[rel=stylesheet]').getAttribute('href')));
  check('JavaScript Beranda memiliki fingerprint versi',/app\.js\?v=[a-f0-9]{12}$/.test(await page.locator('script[src]').getAttribute('src')));
  check('Grafik berdampingan pada desktop',await page.locator('.demographic-grid').evaluate(el=>getComputedStyle(el).display==='grid' && getComputedStyle(el).gridTemplateColumns.split(' ').length===2));
  check('Filter memiliki padding yang termuat',await page.locator('.dashboard-controls').evaluate(el=>parseFloat(getComputedStyle(el).paddingLeft)>=20));
  const maleArc=parseFloat(await page.locator('[data-donut=male]').getAttribute('stroke-dasharray'));
  check('Proporsi diagram donat sesuai agregat',Math.abs(maleArc-100*data.all.genders.male/data.all.total)<0.001);
  await page.locator('#demographic-status').selectOption('PJLP');
  check('Diagram donat kosong tidak menghasilkan NaN',await page.locator('[data-donut=male]').getAttribute('stroke-dasharray')==='0 100');
  await page.locator('#demographic-status').selectOption('all');
  await page.screenshot({path:root+'/beranda-preview.png',fullPage:true});
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
  await page.locator('#employee-form [name=nama]').fill('TEST Pendidikan, S.P., M.Si');
  check('Preview gelar memilih S2',(await page.locator('#education-preview').innerText()).includes('Magister (S2)'));
  for(const [name,level] of [['Ir. Budi','Diploma IV/Sarjana (S1)'],['drh. Budi','Program Profesi Dokter Hewan'],['drh. Budi, M.Si.','Magister (S2)']]) {
    await page.locator('#employee-form [name=nama]').fill(name);
    check('Preview '+name,(await page.locator('#education-preview').innerText()).includes(level));
  }
  await page.locator('#employee-education').selectOption('SD/sederajat');
  await page.locator('#employee-form [name=nama]').fill('TEST Pendidikan, Ph.D.');
  check('Pilihan manual bertahan saat nama berubah',await page.locator('#employee-education').inputValue()==='SD/sederajat');
  check('Dropdown delapan pilihan pendidikan ditambah mode otomatis',await page.locator('#employee-education option').count()===9);
  await page.locator('#employee-dialog').screenshot({path:root+'/form-preview.png'});
  await page.locator('#employee-dialog [data-close]').first().click();
  await page.getByRole('link',{name:'Kelola admin'}).click();check('Manajemen admin tetap dalam halaman Admin',page.url().includes('/admin/?manage=1') && await page.locator('#admin-dialog').isVisible());
  await page.locator('#admin-dialog [data-close]').click();
  await page.setViewportSize({width:390,height:844});await page.goto('http://127.0.0.1:8080/');
  await page.screenshot({path:root+'/mobile-preview.png',fullPage:true});
  check('Grafik satu kolom pada mobile',await page.locator('.demographic-grid').evaluate(el=>getComputedStyle(el).gridTemplateColumns.split(' ').length===1));
  check('Beranda mobile tidak overflow horizontal',await page.evaluate(()=>document.documentElement.scrollWidth<=window.innerWidth));
  await page.goto('http://127.0.0.1:8080/admin/');
  check('Admin mobile tidak overflow horizontal',await page.evaluate(()=>document.documentElement.scrollWidth<=window.innerWidth));
  await page.getByRole('button',{name:'Tambah pegawai'}).click();
  check('Dialog mobile muat dalam viewport',await page.locator('#employee-dialog').evaluate(el=>{const r=el.getBoundingClientRect();return r.left>=0&&r.right<=window.innerWidth}));
  check('Tidak ada error JavaScript atau pelanggaran CSP',errors.length===0);
 }finally{await browser.close();fs.writeFileSync(root+'/hasil_browser.json',JSON.stringify({passed:checks.filter(x=>x.passed).length,failed:checks.filter(x=>!x.passed).length,results:checks},null,2));}
})().catch(e=>{console.error(e);process.exit(1)});

// Mode pengujian khusus analitik publik; suite Admin sebelumnya tetap tersedia.
if(process.env.PUBLIC_ANALYTICS_TESTS==='1') (async()=>{let imageLoaded=false;const browser=await chromium.launch({...(process.env.CHROMIUM_PATH ? {executablePath:process.env.CHROMIUM_PATH} : {}),args:['--no-sandbox','--disable-dev-shm-usage']});try{
const page=await browser.newPage({viewport:{width:1440,height:1100}}),errors=[],externalImageErrors=[];page.on('pageerror',e=>errors.push(e.message));page.on('console',m=>{if(m.type()==='error'){if(m.text().startsWith('Failed to load resource') && /^https:\/\/(thumb|upload)\.wikimedia\.org\//.test(m.location().url))externalImageErrors.push(m.text());else errors.push(m.text());}});
await page.goto('http://127.0.0.1:8080/');
check('Tiga tab tersedia',await page.getByRole('tab').count()===3);
check('Tab berada dalam Dashboard Demografi',await page.locator('#public-dashboard .public-tabs [role=tab]').count()===3);
check('Judul jenis kelamin sesuai permintaan',(await page.locator('#gender-heading').innerText())==='Komposisi pegawai berdasarkan jenis kelamin');
check('Label total adalah pegawai',(await page.locator('.gender-total span').innerText())==='pegawai');
check('Logo kota menggantikan D di Beranda',await page.locator('.city-logo img').count()===1 && await page.locator('.topbar .brand-icon').count()===0);
check('Ukuran logo desktop dipertahankan',await page.locator('.city-logo').evaluate(e=>e.offsetWidth===44&&e.offsetHeight===48));
const publicResponse=await page.request.get('http://127.0.0.1:8080/');
check('CSP mengizinkan gambar Wikimedia',publicResponse.headers()['content-security-policy'].includes('https://thumb.wikimedia.org'));
const responseHtml=await publicResponse.text();
check('Grafik usia tersedia langsung pada HTML',responseHtml.includes('class="age-desktop"')&&responseHtml.includes('class="age-mobile"')&&responseHtml.includes('Dihitung dari 84 dari 129'));

check('Grafik horizontal desktop aktif',await page.locator('.age-desktop').isVisible()&&!await page.locator('.age-mobile').isVisible());
const payload=await page.locator('#public-dashboard').evaluate(e=>JSON.parse(e.dataset.analytics));
check('Seluruh pegawai tetap 129',payload.all.all.total===129);
check('Tidak ada pintasan Admin publik',await page.locator('a[href*="admin"]').count()===0);
for(const status of Object.keys(payload))for(const sex of ['all','male','female']){
 await page.locator('#demographic-status').selectOption(status);await page.locator('#analytics-sex').selectOption(sex);
 const a=payload[status][sex].ages;
 check(`Legenda gender ${status}/${sex}`,await page.locator('.gender-panel .chart-bars').isVisible()===(sex==='all'));
 check(`Usia dan cakupan ${status}/${sex}`,(await page.locator('#age-coverage').innerText()).includes(`Dihitung dari ${a.n} dari ${payload[status][sex].total}`));
 await page.getByRole('tab',{name:'Pendidikan',exact:true}).click();
 check(`Tab pendidikan ${status}/${sex}`,await page.locator('#panel-education').isVisible()&&!await page.locator('#panel-general').isVisible()&&await page.locator('#education-chart .distribution-row').count()===Object.values(payload[status][sex].education).filter(n=>n>0).length);
 await page.getByRole('tab',{name:'Jabatan',exact:true}).click();check(`Tab jabatan ${status}/${sex}`,await page.locator('#job-chart .distribution-row').count()===['str_','fun_','executive'].filter(prefix=>Object.entries(payload[status][sex].jobs).some(([key,n])=>key.startsWith(prefix)&&n>0)).length);
 await page.getByRole('tab',{name:'Statistik umum',exact:true}).click();
}
await page.locator('#demographic-status').selectOption('all');await page.locator('#analytics-sex').selectOption('all');
await page.getByRole('button',{name:'Persentase',exact:true}).click();await page.getByRole('tab',{name:'Pendidikan',exact:true}).click();check('Mode persentase berlaku pendidikan',(await page.locator('#education-chart').innerText()).includes('%'));
await page.getByRole('tab',{name:'Pendidikan',exact:true}).press('ArrowRight');check('Keyboard panah mengganti tab',await page.getByRole('tab',{name:'Jabatan',exact:true}).getAttribute('aria-selected')==='true');
await page.getByRole('tab',{name:'Jabatan',exact:true}).press('Home');check('Keyboard Home kembali ke umum',await page.locator('#panel-general').isVisible());
await page.getByRole('button',{name:'Jumlah',exact:true}).click();
await page.screenshot({path:root+'/pratinjau-beranda.png',fullPage:true});
for(const width of [390,320,768]){
 await page.setViewportSize({width,height:900});
 for(const tab of ['Statistik umum','Pendidikan','Jabatan']){
  await page.getByRole('tab',{name:tab,exact:true}).click();check(`Tidak overflow ${width}px ${tab}`,await page.evaluate(()=>document.documentElement.scrollWidth<=innerWidth));
 }
}
await page.setViewportSize({width:390,height:844});await page.getByRole('tab',{name:'Statistik umum',exact:true}).click();
check('Ukuran logo mobile dipertahankan',await page.locator('.city-logo').evaluate(e=>e.offsetWidth===34&&e.offsetHeight===39));
check('Mobile menggunakan grafik kolom',await page.locator('.age-mobile').isVisible()&&!await page.locator('.age-desktop').isVisible());
await page.screenshot({path:require('os').tmpdir()+'/mobile-analytics.png',fullPage:true});
await page.getByRole('tab',{name:'Pendidikan',exact:true}).click();await page.screenshot({path:require('os').tmpdir()+'/education-analytics.png',fullPage:true});
await page.setViewportSize({width:1440,height:1100});await page.getByRole('tab',{name:'Jabatan',exact:true}).click();await page.screenshot({path:require('os').tmpdir()+'/jobs-analytics.png',fullPage:true});
check('Pelaksana tidak masuk rincian fungsional',!(await page.locator('#job-detail').innerText()).includes('Pelaksana'));
check('Rincian tidak memuat jabatan struktural',!/(Kepala dinas|Sekretaris|Kepala bidang|Kepala subbagian|PLT|Kepala UPTD)/.test(await page.locator('#job-detail').innerText()));
check('Rincian jabatan menampilkan batang',await page.locator('#job-detail progress').first().isVisible());
check('Tidak ada galat JS/CSP',errors.length===0);
imageLoaded=await page.locator('.city-logo img').evaluate(e=>e.complete&&e.naturalWidth>0);
console.log('Pemuatan gambar eksternal:',externalImageErrors.length?'belum terverifikasi; kegagalan jaringan Wikimedia':'tidak ada galat jaringan tercatat');
}finally{fs.writeFileSync(root+'/hasil_browser.json',JSON.stringify({external_logo_loaded:imageLoaded,passed:checks.filter(x=>x.passed).length,failed:checks.filter(x=>!x.passed).length,results:checks},null,2));await browser.close();}})().catch(e=>{console.error(e);process.exit(1)});
