'use strict';
// Inisialisasi legenda dilakukan sebelum render grafik lainnya.
function syncGenderLegend(){
 const select=document.getElementById('analytics-sex'),panel=document.querySelector('#public-dashboard .gender-panel');
 if(!select || !panel)return;
 const single=select.value==='male'||select.value==='female';
 panel.dataset.singleGender=String(single);
 panel.querySelectorAll('.chart-bars').forEach(legend=>{legend.hidden=single;legend.setAttribute('aria-hidden',String(single));});
}
const publicGenderSelect=document.getElementById('analytics-sex');
if(publicGenderSelect){publicGenderSelect.addEventListener('change',syncGenderLegend);window.addEventListener('pageshow',syncGenderLegend);syncGenderLegend();}
// Embed logo dan favicon dari sumber yang sama; cadangan lokal jika jaringan gagal.
const cityFavicon=document.getElementById('city-favicon');
const useLocalFavicon=()=>{if(cityFavicon && !cityFavicon.dataset.fallbackUsed){cityFavicon.dataset.fallbackUsed='1';cityFavicon.href=cityFavicon.dataset.fallback;}};
if(cityFavicon){const faviconProbe=new Image();faviconProbe.onerror=useLocalFavicon;faviconProbe.src=cityFavicon.href;}
const cityLogo=document.querySelector('.city-logo img[data-fallback]');
if(cityLogo){
 const useLocalLogo=()=>{if(cityLogo.dataset.fallbackUsed)return;cityLogo.dataset.fallbackUsed='1';cityLogo.src=cityLogo.dataset.fallback;useLocalFavicon();};
 cityLogo.addEventListener('error',useLocalLogo,{once:true});
 if(cityLogo.complete && cityLogo.naturalWidth===0)useLocalLogo();
}
// Tidak ada data pegawai atau password yang disimpan ke localStorage.
const all = (s, root=document) => [...root.querySelectorAll(s)];
all('[data-open]').forEach(button => button.addEventListener('click', () => {
  const dialog=document.getElementById(button.dataset.open);
  if(button.hasAttribute('data-new-employee')) {
    const form=document.getElementById('employee-form');
    form.reset();
    ['id','nama','jabatan','nip','mulai_asn_manual','tanggal_lahir_manual','jenis_kelamin_manual'].forEach(n=>form.elements[n].value='');
    form.elements.pendidikan_terakhir.value='auto'; updateEducationPreview();
    form.elements.row_version.value='1'; form.elements.status_kepegawaian.value='PNS';
    document.getElementById('employee-form-title').textContent='Tambah pegawai';
    all('.alert',dialog).forEach(el=>el.remove()); updateFields();
  }
  dialog.showModal();
}));
all('[data-close]').forEach(button=>button.addEventListener('click',()=>button.closest('dialog').close()));
all('dialog[data-auto-open]').forEach(dialog=>dialog.showModal());
all('form[data-confirm]').forEach(form=>form.addEventListener('submit',event=>{ if(!window.confirm(form.dataset.confirm)) event.preventDefault(); }));
all('[data-password]').forEach(button=>button.addEventListener('click',()=>{
  const field=document.getElementById(button.dataset.password), show=field.type==='password';
  field.type=show?'text':'password'; button.textContent=show?'Sembunyikan':'Lihat'; button.setAttribute('aria-label',show?'Sembunyikan password':'Tampilkan password');
}));
const statusSelect=document.getElementById('employee-status');
function updateFields(){
  if(!statusSelect)return;
  const status=statusSelect.value,pjlp=status==='PJLP',pppk=status.startsWith('PPPK');
  const nip=document.getElementById('employee-nip'),asn=document.querySelector('#asn-field input');
  document.getElementById('nip-field').hidden=pjlp; nip.disabled=pjlp; nip.required=!pjlp;
  document.getElementById('nip-label').textContent=pppk?'NIPPPK':'NIP';
  document.getElementById('asn-field').hidden=!pppk; asn.disabled=!pppk; asn.required=pppk;
  all('.pjlp-field').forEach(label=>{ label.hidden=!pjlp; all('input,select',label).forEach(field=>field.disabled=!pjlp); });
  previewNip();
}
function previewNip(){
  if(!statusSelect)return;
  const el=document.getElementById('nip-preview'),nip=document.getElementById('employee-nip').value.replace(/\s/g,'');
  if(statusSelect.value==='PJLP'){el.textContent='PJLP tidak memiliki NIP/NIPPPK. Nomor pegawai dan mulai ASN ditampilkan sebagai “-”.';return;}
  if(!/^\d{18}$/.test(nip)){el.textContent='Tanggal lahir dan jenis kelamin dihitung setelah nomor 18 digit terisi. Validasi akhir dilakukan saat penyimpanan.';return;}
  const y=Number(nip.slice(0,4)),m=Number(nip.slice(4,6)),d=Number(nip.slice(6,8)),date=new Date(y,m-1,d);
  if(date.getFullYear()!==y || date.getMonth()!==m-1 || date.getDate()!==d || !['1','2'].includes(nip[14])){el.textContent='Periksa kembali tanggal lahir atau digit jenis kelamin dalam nomor pegawai.';return;}
  const dob=date.toLocaleDateString('id-ID',{day:'numeric',month:'long',year:'numeric'});
  el.textContent=`Lahir ${dob} · ${nip[14]==='1'?'Laki-laki':'Perempuan'} · ${statusSelect.value==='PNS'?'Mulai ASN diekstrak dari NIP.':'Mulai ASN diisi manual.'}`;
}
if(statusSelect){statusSelect.addEventListener('change',updateFields);document.getElementById('employee-nip').addEventListener('input',previewNip);updateFields();}
const table=document.getElementById('employee-table');
if(table){
  const rows=all('tbody tr',table),search=document.getElementById('table-search'),filter=document.getElementById('status-filter');
  const previous=document.getElementById('prev-page'),next=document.getElementById('next-page');let page=1;const size=10;
  function render(){
    const q=search.value.toLocaleLowerCase('id-ID').trim(),compactQ=q.replace(/\s/g,'');
    const found=rows.filter(row=>(!filter.value||row.dataset.status===filter.value)&&(row.textContent.toLocaleLowerCase('id-ID').includes(q)||(row.querySelector('.nip')?.textContent.replace(/\s/g,'').includes(compactQ)??false)));
    const pages=Math.max(1,Math.ceil(found.length/size));page=Math.min(page,pages);rows.forEach(row=>row.hidden=true);
    found.slice((page-1)*size,page*size).forEach(row=>row.hidden=false);
    document.getElementById('table-info').textContent=found.length?`Menampilkan ${(page-1)*size+1}–${Math.min(page*size,found.length)} dari ${found.length} pegawai`:'0 pegawai';
    document.getElementById('page-info').textContent=`${page} / ${pages}`;
    document.getElementById('empty-state').hidden=found.length>0;previous.disabled=page===1;next.disabled=page>=pages;
  }
  search.addEventListener('input',()=>{page=1;render();});filter.addEventListener('change',()=>{page=1;render();});
  previous.addEventListener('click',()=>{page--;render();});next.addEventListener('click',()=>{page++;render();});render();
}

// Dashboard demografi publik: payload berisi jumlah agregat saja.
(() => {
  const root=document.getElementById('public-dashboard');
  if(!root)return;
  const summary=JSON.parse(root.dataset.summary),select=document.getElementById('demographic-status');
  const nf=new Intl.NumberFormat('id-ID',{maximumFractionDigits:1});let metric='count';
  function renderDemographics(){
    const key=select.value,data=JSON.parse(root.dataset.analytics)[key][document.getElementById('analytics-sex').value],scope=(key==='all'?'Keseluruhan':key)+' · '+document.getElementById('analytics-sex').selectedOptions[0].textContent;
    document.getElementById('demographic-context').textContent=`${scope} · ${data.total} pegawai`;
    const genderTotal=data.genders.male+data.genders.female;
    const generationTotal=Object.values(data.generations).reduce((a,b)=>a+b,0);
    document.getElementById('demographic-total').textContent=genderTotal;
    document.getElementById('generation-coverage').textContent=`Mencakup ${generationTotal} dari ${data.total} pegawai.`;
    document.getElementById('gender-coverage').textContent=`Mencakup ${genderTotal} dari ${data.total} pegawai.`;
    document.getElementById('gender-scope').textContent=scope;
    syncGenderLegend();
    document.getElementById('demographic-empty').hidden=data.total!==0;
    all('.chart-row',root).forEach(row=>{
      const n=data[row.dataset.group][row.dataset.key],denominator=row.dataset.group==='genders'?genderTotal:generationTotal,pct=denominator?100*n/denominator:0;
      const text=metric==='count'?`${n} pegawai`:`${nf.format(pct)}% · ${n} pegawai`;
      row.querySelector('output').textContent=text;
      const bar=row.querySelector('progress');bar.max=Math.max(1,denominator);bar.value=n;
      bar.setAttribute('aria-label',`${row.querySelector('strong').textContent}: ${n} pegawai (${nf.format(pct)}%)`);
      bar.title=`${n} dari ${denominator} pegawai · ${nf.format(pct)}%`;
    });
    let offset=0;
    all('[data-donut]',root).forEach(segment=>{
      const value=genderTotal?100*data.genders[segment.dataset.donut]/genderTotal:0;
      segment.setAttribute('stroke-dasharray',`${value} ${100-value}`);
      segment.setAttribute('stroke-dashoffset',String(-offset));offset+=value;
    });
    const donut=root.querySelector('.gender-donut');
    if(donut)donut.setAttribute('aria-label',`${scope}: ${data.genders.male} laki-laki, ${data.genders.female} perempuan`);
    all('[data-summary-status]',root).forEach(row=>row.classList.toggle('selected-summary',row.dataset.summaryStatus===key));
    all('[data-metric]',root).forEach(button=>button.setAttribute('aria-pressed',String(button.dataset.metric===metric)));
  }
  select.addEventListener('change',renderDemographics);
  document.getElementById('analytics-sex').addEventListener('change',renderDemographics);
  all('[data-metric]',root).forEach(button=>button.addEventListener('click',()=>{metric=button.dataset.metric;renderDemographics();}));
  renderDemographics();
})();

// Pilihan manual selalu dipertahankan; deteksi otomatis hanya pada opsi auto.
function updateEducationPreview(){
  const select=document.getElementById('employee-education');if(!select)return;
  const hint=document.getElementById('education-preview');
  if(select.value!=='auto'){hint.textContent='Pilihan manual: '+select.value+'. Nilai ini dipertahankan saat nama diubah.';return;}
  const name=document.querySelector('#employee-form [name=nama]').value.trim().replace(/\./g,'').toLocaleUpperCase('id-ID');
  const patterns=JSON.parse(select.dataset.patterns);let detected=null;
  for(const [level,pattern] of Object.entries(patterns)){
    if(new RegExp(pattern.replaceAll('[:space:]','\\s'),'u').test(name)){detected=level;break;}
  }
  hint.textContent=detected?'Terdeteksi: '+detected+'. Disimpan saat Anda menekan Simpan pegawai.':'Gelar belum dikenali. Pilih jenjang sesuai ijazah; jika tetap otomatis, nilai disimpan kosong.';
}
const educationSelect=document.getElementById('employee-education');
if(educationSelect){educationSelect.addEventListener('change',updateEducationPreview);document.querySelector('#employee-form [name=nama]').addEventListener('input',updateEducationPreview);updateEducationPreview();}

// Tab dan analitik publik. Seluruh payload merupakan ringkasan, tanpa record individu.
(() => {
 const root=document.getElementById('public-dashboard');if(!root)return;
 const data=JSON.parse(root.dataset.analytics),labels=JSON.parse(root.dataset.jobLabels);
 const status=document.getElementById('demographic-status'),sex=document.getElementById('analytics-sex');
 const nf=new Intl.NumberFormat('id-ID',{maximumFractionDigits:1});
 const el=(tag,cls,text)=>{const n=document.createElement(tag);if(cls)n.className=cls;if(text!==undefined)n.textContent=text;return n;};
 const svgEl=(tag,attrs)=>{const n=document.createElementNS('http://www.w3.org/2000/svg',tag);Object.entries(attrs).forEach(([k,v])=>n.setAttribute(k,v));return n;};
 const metrics={min:'Minimum',mean:'Rata-rata',median:'Median',max:'Maksimum'};
 function bars(target,values,denominator){
  const box=document.getElementById(target);box.replaceChildren();
  const percent=root.querySelector('[data-metric="percent"]').getAttribute('aria-pressed')==='true';
  Object.entries(values).filter(([,n])=>Number.isFinite(n) && n>0).forEach(([label,n])=>{
   const row=el('div','distribution-row'),head=el('div','distribution-label');
   head.append(el('span','',label),el('strong','',percent?`${nf.format(denominator?100*n/denominator:0)}% · ${n}`:`${n} pegawai`));
   const bar=el('progress');bar.max=Math.max(1,denominator);bar.value=n;bar.setAttribute('aria-label',`${label}: ${n} pegawai`);
   row.append(head,bar);box.append(row);
  });
  if(!denominator)box.append(el('p','empty-analytics','Belum ada data yang dapat ditampilkan pada pilihan ini.'));
 }
 function render(){
  const d=data[status.value][sex.value],a=d.ages,box=document.getElementById('age-chart');box.replaceChildren();
  if(!a.n)box.append(el('p','empty-analytics',status.value==='PJLP'?'Tidak berlaku: PJLP bukan ASN.':'Belum ada tanggal lahir dan pengangkatan ASN yang valid pada pilihan ini.'));
  else {
   const desktop=el('div','age-desktop');
   Object.entries(metrics).forEach(([key,label])=>{
    const row=el('div','age-row'),bar=el('progress');bar.max=Math.max(1,a.max);bar.value=a[key];bar.setAttribute('aria-label',`${label}: ${nf.format(a[key])} tahun`);
    row.append(el('span','',label),bar,el('strong','',nf.format(a[key])));desktop.append(row);
   });box.append(desktop);
   // Grafik kolom vertikal untuk layar kecil, dengan skala bersama dari nol.
   const mobile=el('div','age-mobile'),svg=svgEl('svg',{viewBox:'0 0 320 205',role:'img','aria-label':Object.entries(metrics).map(([k,v])=>`${v} ${nf.format(a[k])} tahun`).join(', ')});
   svg.append(svgEl('line',{x1:12,y1:165,x2:308,y2:165,stroke:'#cbd8cf'}));
   Object.entries(metrics).forEach(([key,label],i)=>{
    const h=120*a[key]/Math.max(1,a.max),x=24+76*i;
    svg.append(svgEl('rect',{x,y:165-h,width:44,height:h,rx:6,fill:['#245b49','#739c80','#b08754','#435f73'][i]}));
    for(const [y,text,size] of [[153-h,nf.format(a[key]),16],[187,label,11]]){const t=svgEl('text',{x:x+22,y,'text-anchor':'middle','font-size':size,fill:'#233e35'});t.textContent=text;svg.append(t);}
   });mobile.append(svg);box.append(mobile);
  }
  document.getElementById('age-coverage').textContent=`Dihitung dari ${a.n} dari ${d.total} pegawai pada pilihan ini. Nilai kosong tidak dianggap 0. Angka ditampilkan hingga satu desimal.`;
  const comparison=document.getElementById('age-comparison');comparison.replaceChildren();
  const table=el('table');table.append(el('caption','',`Usia awal ASN · ${sex.selectedOptions[0].textContent} · tahun`));
  const head=el('thead'),hr=el('tr');['Status','Data valid','Minimum','Rata-rata','Median','Maksimum'].forEach(x=>{const th=el('th','',x);th.scope='col';hr.append(th);});head.append(hr);table.append(head);
  const body=el('tbody');Object.entries(data).forEach(([key,group])=>{const tr=el('tr'),s=group[sex.value].ages,th=el('th','',key==='all'?'Keseluruhan':key);th.scope='row';tr.append(th,el('td','',`${s.n} / ${group[sex.value].total}`));Object.keys(metrics).forEach(k=>tr.append(el('td','',s[k]===null?'—':nf.format(s[k]))));body.append(tr);});table.append(body);comparison.append(table);
  const eduN=Object.values(d.education).reduce((a,b)=>a+b,0);bars('education-chart',d.education,eduN);
  document.getElementById('education-coverage').textContent=`Mencakup ${eduN} dari ${d.total} pegawai pada pilihan ini; ${d.total-eduN} belum memiliki pilihan pendidikan yang valid.`;
  const groups={Struktural:0,Fungsional:0,Pelaksana:0},details=Object.fromEntries(Object.entries(d.job_details || {}).filter(([label,n])=>label.startsWith('Jabatan Fungsional') && n>0));
  Object.entries(d.jobs).forEach(([key,n])=>{groups[key.startsWith('str_')?'Struktural':key.startsWith('fun_')?'Fungsional':'Pelaksana']+=n;if(!d.job_details && n>0 && key.startsWith('fun_'))details[labels[key]]=n;});
  const jobN=Object.values(groups).reduce((a,b)=>a+b,0);bars('job-chart',groups,jobN);const detailN=Object.values(details).reduce((a,b)=>a+b,0);bars('job-detail',details,detailN);
  document.getElementById('job-coverage').textContent=`Mencakup ${jobN} dari ${d.total} pegawai. Setiap pegawai dihitung sekali. Diagram utama mencakup seluruh jabatan terisi. Rincian mencakup ${detailN} pegawai fungsional; persentase rincian dihitung dari kelompok tersebut. Kategori dengan jumlah 0 disembunyikan dari rincian.`;
 }
 const tabs=Array.from(document.querySelectorAll('[data-public-tab]'));
 function activate(tab){tabs.forEach(t=>{const active=t===tab;t.setAttribute('aria-selected',String(active));t.tabIndex=active?0:-1;document.getElementById('panel-'+t.dataset.publicTab).hidden=!active;});}
 tabs.forEach((t,i)=>{t.addEventListener('click',()=>activate(t));t.addEventListener('keydown',e=>{let next;if(e.key==='ArrowRight')next=(i+1)%tabs.length;if(e.key==='ArrowLeft')next=(i+tabs.length-1)%tabs.length;if(e.key==='Home')next=0;if(e.key==='End')next=tabs.length-1;if(next!==undefined){e.preventDefault();activate(tabs[next]);tabs[next].focus();}});});
 status.addEventListener('change',render);sex.addEventListener('change',render);root.querySelectorAll('[data-metric]').forEach(b=>b.addEventListener('click',render));render();
})();
