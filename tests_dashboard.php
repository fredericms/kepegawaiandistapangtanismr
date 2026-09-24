<?php
// Pengujian integrasi dashboard; hanya database lokal dengan akhiran _test.
if(PHP_SAPI!=='cli'){http_response_code(404);exit;}
if(!str_ends_with(getenv('DB_NAME')?:'', '_test') || getenv('RUN_DASHBOARD_TESTS')!=='1') exit("Gunakan DB_NAME berakhiran _test dan RUN_DASHBOARD_TESTS=1.\n");
require __DIR__.'/config.php'; require __DIR__.'/functions.php';
$passed=0;$failed=0;$created=[];$results=[];
function verify_dashboard($label,$condition){global $passed,$failed,$results;$results[]=['test'=>$label,'passed'=>(bool)$condition];if($condition){$passed++;echo "PASS $label\n";}else{$failed++;throw new RuntimeException($label);}}
function read_dashboard():array {
    $html=file_get_contents('http://127.0.0.1:8080/');
    if(!preg_match('/data-summary="([^"]+)"/',$html,$m))throw new RuntimeException('Payload dashboard tidak ditemukan');
    return [json_decode(html_entity_decode($m[1],ENT_QUOTES|ENT_HTML5,'UTF-8'),true,512,JSON_THROW_ON_ERROR),$html];
}
try {
    [$before,$html]=read_dashboard();
    verify_dashboard('Kategori tersembunyi dan rekap tidak dirender',!str_contains($html,'Belum diketahui') && !str_contains($html,'Di luar X') && !str_contains($html,'gender-summary-table'));
    verify_dashboard('HTML publik tidak memuat direktori pegawai',!str_contains($html,'id="employee-table"') && !str_contains($html,'id="table-search"'));
    verify_dashboard('Identitas individual tidak dikirim ke publik',!str_contains($html,'Frederic Morado') && !str_contains($html,'200105132025061007') && !str_contains($html,'Penata Kelola Sistem'));
    verify_dashboard('Payload hanya memuat jumlah agregat',!preg_match('/"(?:nama|nip|jabatan|tanggal_lahir_manual)"/',json_encode($before)));
    foreach($before as $group=>$data) verify_dashboard('Total generasi dan gender konsisten: '.$group,array_sum($data['generations'])<=$data['total'] && array_sum($data['genders'])<=$data['total']);
    foreach([
        [[],['n'=>0,'min'=>null,'mean'=>null,'median'=>null,'max'=>null]],
        [[20],['n'=>1,'min'=>20,'mean'=>20,'median'=>20,'max'=>20]],
        [[40,20,30],['n'=>3,'min'=>20,'mean'=>30,'median'=>30,'max'=>40]],
        [[21,20,40,30],['n'=>4,'min'=>20,'mean'=>27.75,'median'=>25.5,'max'=>40]],
    ] as [$values,$expectedStats]) verify_dashboard('Statistik usia: '.json_encode($values),public_age_stats($values)==$expectedStats);
    foreach(['Kepala Dinas'=>'str_head','Sekretaris Dinas'=>'str_secretary','Kabid Pangan'=>'str_division','Kepala Sub Bagian Umum'=>'str_subdivision','Kasubag. Tata Usaha'=>'str_subdivision','Kepala UPTD'=>'str_unit','Plt. Kabid Pangan'=>'str_acting','Analis Ketahanan Pangan Muda (Penyetaraan)'=>'fun_young','Medik Veteriner Ahli Pertama'=>'fun_first','Analis Ketahanan Pangan Ahli Madya'=>'fun_middle','Paramedik Veteriner Terampil'=>'fun_skilled','Paramedik Veteriner Mahir'=>'fun_proficient','Pengendali Organisme Penggangu Tumbuhan (Penyetraan)'=>'fun_unspecified','Pengolah Data dan Informasi'=>'executive',''=>'empty'] as $title=>$expectedJob) verify_dashboard('Kelompok jabatan: '.$title,(public_job_group($title)??'empty')===$expectedJob);
    $fixture=['nama'=>'PRIVATE FIXTURE','jabatan'=>'Plt. Kabid Pangan','pendidikan_terakhir'=>'SD/sederajat','status_kepegawaian'=>'PNS','nip'=>'200005132025061001'];
    $samples=[$fixture,array_merge($fixture,['status_kepegawaian'=>'PPPK Penuh Waktu','nip'=>'200005132025212001','mulai_asn_manual'=>'2024-05-01']),array_merge($fixture,['status_kepegawaian'=>'PPPK Paruh Waktu','nip'=>'200005132025211002','mulai_asn_manual'=>null]),array_merge($fixture,['status_kepegawaian'=>'PJLP','tanggal_lahir_manual'=>'2000-05-13','jenis_kelamin_manual'=>'2'])];
    $agg=public_analytics($samples,new DateTimeImmutable('2026-09-22'));
    verify_dashboard('PNS memakai bulan NIP; PPPK memakai tanggal manual',$agg['all']['all']['ages']==['n'=>2,'min'=>23,'mean'=>24,'median'=>24,'max'=>25]);
    verify_dashboard('PJLP dan tanggal kosong tidak dianggap usia nol',$agg['PJLP']['all']['ages']['n']===0 && $agg['PPPK Paruh Waktu']['all']['ages']['n']===0);
    verify_dashboard('Irisan status dan gender benar',$agg['PPPK Penuh Waktu']['female']['ages']['mean']==23 && $agg['PNS']['female']['total']===0);
    verify_dashboard('Pendidikan memakai nilai tersimpan, bukan gelar',$agg['all']['all']['education']['SD/sederajat']===4);
    verify_dashboard('PLT dihitung satu kali',$agg['all']['all']['jobs']['str_acting']===4 && array_sum($agg['all']['all']['jobs'])===4);
    foreach(['invalid','1999-01-01','2099-01-01'] as $bad){$sample=$samples[1];$sample['mulai_asn_manual']=$bad;verify_dashboard('Tanggal pengangkatan tidak layak dikeluarkan: '.$bad,public_analytics([$sample],new DateTimeImmutable('2026-09-22'))['all']['all']['ages']['n']===0);}
    $sample=$samples[0];$sample['nip']='200002302025061001';verify_dashboard('Tanggal lahir tidak valid dikeluarkan',public_analytics([$sample])['all']['all']['ages']['n']===0);
    preg_match('/data-analytics="([^\"]+)"/',$html,$match);$public=json_decode(html_entity_decode($match[1],ENT_QUOTES|ENT_HTML5,'UTF-8'),true);
    verify_dashboard('Payload analitik tidak memuat identitas atau usia individual',!preg_match('/"(?:nama|nip|jabatan|tanggal_lahir_manual)"/',json_encode($public)) && isset($public['all']['all']['ages']['n']));
    foreach($public as $status=>$g){foreach($g as $gender=>$d) verify_dashboard('Konsistensi agregat '.$status.' / '.$gender,array_sum($d['education'])<=$d['total'] && array_sum($d['jobs'])<=$d['total'] && $d['ages']['n']<=$d['total']);}

    $fixtures=[['1964-12-31','1','other'],['1965-01-01','2','X'],['1980-12-31','1','X'],['1981-01-01','2','Y'],['1996-12-31','1','Y'],['1997-01-01','2','Z'],['2012-12-31','1','Z'],['2013-01-01','2','other'],[null,null,'unknown'],['2099-01-01','1','unknown']];
    $expected=$before;
    foreach($fixtures as [$date,$sex,$generation]) {
        $pdo->prepare("INSERT INTO pegawai_distapangtani (nama,nip,jabatan,status_kepegawaian,tanggal_lahir_manual,jenis_kelamin_manual) VALUES ('TEST Dashboard Private','-','TEST PRIVATE','PJLP',?,?)")->execute([$date,$sex]); $created[]=$pdo->lastInsertId();
        $gender=$sex==='1'?'male':($sex==='2'?'female':'unknown');
        foreach(['all','PJLP'] as $g){$expected[$g]['total']++;if(isset($expected[$g]['generations'][$generation]))$expected[$g]['generations'][$generation]++;if(isset($expected[$g]['genders'][$gender]))$expected[$g]['genders'][$gender]++;}
    }
    $pdo->exec("INSERT INTO pegawai_distapangtani(nama,nip,jabatan,status_kepegawaian) VALUES ('TEST Invalid NIP','INVALID-PUBLIC-TEST','PRIVATE','PNS')");$created[]=$pdo->lastInsertId();
    foreach(['all','PNS'] as $g){$expected[$g]['total']++;}
    $pdo->exec("INSERT INTO pegawai_distapangtani(nama,nip,jabatan,status_kepegawaian) VALUES ('TEST PPPK Private','200105132025211999','PRIVATE','PPPK Paruh Waktu')");$created[]=$pdo->lastInsertId();
    foreach(['all','PPPK Paruh Waktu'] as $g){$expected[$g]['total']++;$expected[$g]['generations']['Z']++;$expected[$g]['genders']['male']++;}
    [$after,$html]=read_dashboard();
    foreach($expected as $g=>$data) verify_dashboard('Batas generasi, PJLP manual, NIPPPK dan data kosong: '.$g,$after[$g]===$data);
    verify_dashboard('Identitas fixture tidak muncul pada HTML',!str_contains($html,'TEST Dashboard Private') && !str_contains($html,'TEST PPPK Private') && !str_contains($html,'200105132025211999'));
} catch(Throwable $ex){$failed=max(1,$failed);echo 'FAIL '.$ex->getMessage()."\n";} finally {
    foreach($created as $id)$pdo->prepare('DELETE FROM pegawai_distapangtani WHERE no=?')->execute([$id]);
    [$restored]=read_dashboard();verify_dashboard('Data uji dibersihkan dan agregat dipulihkan',$restored===$before);
    file_put_contents(__DIR__.'/hasil_dashboard.json',json_encode(['passed'=>$passed,'failed'=>$failed,'results'=>$results],JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
}
echo "$passed passed, $failed failed\n";exit($failed?1:0);
