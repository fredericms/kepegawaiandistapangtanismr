<?php
declare(strict_types=1);
if(PHP_SAPI!=='cli'){http_response_code(404);exit;}
date_default_timezone_set('Asia/Makassar');
require __DIR__.'/functions.php';
$passed=0;$failed=0;
function test(string $label,callable $fn): void { global $passed,$failed;try{if(!$fn())throw new RuntimeException('assertion false');echo "PASS $label\n";$passed++;}catch(Throwable $e){echo "FAIL $label: ".$e->getMessage()."\n";$failed++;}}
function rejects(array $row): bool {try{validate_employee($row);return false;}catch(DomainException){return true;}}
$base=['nama'=>'Pegawai Uji','jabatan'=>'Analis','status_kepegawaian'=>'PNS','nip'=>'200105132025061007'];
$at=new DateTimeImmutable('2026-09-22');$info=employee_info($base,$at);
test('NIP PNS: tanggal lahir 13 Mei 2001',fn()=>date_id($info['dob'])==='13 Mei 2001');
test('NIP PNS: usia saat 22 September 2026 = 25',fn()=>$info['age']===25);
test('NIP PNS: mulai ASN Juni 2025',fn()=>date_id($info['start'],true)==='Juni 2025');
test('NIP PNS: usia awal ASN angka 24',fn()=>$info['start_age']===24);
test('NIP PNS: gender 1 laki-laki',fn()=>$info['gender']==='Laki-laki');
test('Gender 2 perempuan',fn()=>employee_info([...$base,'nip'=>'200105132025062007'],$at)['gender']==='Perempuan');
test('Spasi NIP dinormalisasi',fn()=>validate_employee([...$base,'nip'=>'20010513 202506 1 007'])[1]==='200105132025061007');
test('NIP dipertahankan sebagai string',fn()=>is_string(validate_employee($base)[1]));
test('NIP 17 digit ditolak',fn()=>rejects([...$base,'nip'=>'20010513202506100']));
test('NIP karakter bukan angka ditolak',fn()=>rejects([...$base,'nip'=>'20010513202506100x']));
test('Gender di luar 1/2 ditolak',fn()=>rejects([...$base,'nip'=>'200105132025063007']));
test('Tanggal lahir 31 Februari ditolak',fn()=>rejects([...$base,'nip'=>'200102312025061007']));
test('Bulan pengangkatan PNS 21 ditolak',fn()=>rejects([...$base,'nip'=>'200105132025211007']));
test('Pengangkatan sebelum kelahiran ditolak',fn()=>rejects([...$base,'nip'=>'200105131999061007']));
test('Pengangkatan masa depan ditolak',fn()=>rejects([...$base,'nip'=>'200105139999061007']));
$pppk=[...$base,'status_kepegawaian'=>'PPPK Paruh Waktu','nip'=>'200105132025211007','mulai_asn_manual'=>'2025-10'];
test('PPPK menerima tengah NIPPPK 202521 dan bulan manual',fn()=>validate_employee($pppk)[4]==='2025-10-01');
test('PPPK penuh waktu menerima bulan manual',fn()=>validate_employee([...$pppk,'status_kepegawaian'=>'PPPK Penuh Waktu'])[4]==='2025-10-01');
test('PPPK mulai ASN tidak diekstrak dari 202521',fn()=>employee_info([...$pppk,'mulai_asn_manual'=>null],$at)['start']===null);
test('PPPK tanggal manual tersimpan dihitung',fn()=>date_id(employee_info([...$pppk,'mulai_asn_manual'=>'2025-10-01'],$at)['start'],true)==='Oktober 2025');
test('PPPK tanpa bulan manual ditolak',fn()=>rejects([...$pppk,'mulai_asn_manual'=>'']));
test('Bulan manual 13 ditolak',fn()=>rejects([...$pppk,'mulai_asn_manual'=>'2025-13']));
$pjlp=[...$base,'status_kepegawaian'=>'PJLP','nip'=>'malicious ignored'];
test('PJLP tidak menggunakan NIP',fn()=>validate_employee($pjlp)[1]==='-');
test('PJLP boleh tanpa tanggal lahir',fn()=>validate_employee($pjlp)[5]===null);
test('PJLP tanggal lahir manual',fn()=>validate_employee([...$pjlp,'tanggal_lahir_manual'=>'1998-02-20','jenis_kelamin_manual'=>'2'])[5]==='1998-02-20');
test('PJLP mulai ASN selalu kosong',fn()=>employee_info([...$pjlp,'mulai_asn_manual'=>'2025-10-01'],$at)['start']===null);
test('Nama kosong ditolak',fn()=>rejects([...$base,'nama'=>' ']));
test('Status palsu ditolak',fn()=>rejects([...$base,'status_kepegawaian'=>'Superadmin']));
test('Nama >150 karakter ditolak',fn()=>rejects([...$base,'nama'=>str_repeat('a',151)]));
test('SQLi pada NIP ditolak',fn()=>rejects([...$base,'nip'=>"' OR 1=1 --"]));
test('Escaping XSS saat output',fn()=>e('<script>alert("x")</script>')==='&lt;script&gt;alert(&quot;x&quot;)&lt;/script&gt;');
test('Tahun kabisat diterima',fn()=>valid_date('2000-02-29')!==null);
test('Bukan tahun kabisat ditolak',fn()=>valid_date('2001-02-29')===null);
test('Usia berubah tepat saat ulang tahun',fn()=>employee_info($base,new DateTimeImmutable('2026-05-12'))['age']===24 && employee_info($base,new DateTimeImmutable('2026-05-13'))['age']===25);
test('Usia awal ASN memakai tanggal 1 bulan pengangkatan',fn()=>employee_info([...$base,'nip'=>'200105132025051007'],$at)['start_age']===23);
test('Data warisan invalid tidak merusak tabel',fn()=>employee_info([...$base,'nip'=>'invalid'],$at)['dob']===null);
echo "\n$passed passed, $failed failed\n";exit($failed?1:0);
