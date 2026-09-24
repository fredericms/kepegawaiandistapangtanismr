<?php
if(PHP_SAPI!=='cli'){http_response_code(404);exit;}
require __DIR__.'/functions.php';
$cases=[
 ['Budi, S.P','Diploma IV/Sarjana (S1)'],['Budi, M.Si','Magister (S2)'],
 ['Budi, S.P., M.Si','Magister (S2)'],['Budi, M.Si., S.P.','Magister (S2)'],
 ['Budi S.P.','Diploma IV/Sarjana (S1)'],['Budi, s. p., m. si.','Magister (S2)'],
 ['Budi, S.Kom., M.Kom., Ph.D.','Doktor (S3)'],['Budi, Ph.D., M.Si.','Doktor (S3)'],
 ['Budi, A.Md','Diploma I - Diploma III'],['Budi, A.Md.Kep.','Diploma I - Diploma III'],
 ['Budi, A.Md., S.T.','Diploma IV/Sarjana (S1)'],['Budi, S.Tr.Kep.','Diploma IV/Sarjana (S1)'],
 ['Budi, S.E., Ak.','Diploma IV/Sarjana (S1)'],['Budi, M.M','Magister (S2)'],
 ['Budi',null],['dr. Budi',null],['drh. Budi','Program Profesi Dokter Hewan'],['Drs. Budi',null],['Ir. Budi','Diploma IV/Sarjana (S1)'],
 ['Budi, GelarTidakDikenal',null],['S.P. Budi',null],['Budi, <script>alert(1)</script>',null],
 ['Ir Budi', 'Diploma IV/Sarjana (S1)'],
 ['Budi, Ir.', 'Diploma IV/Sarjana (S1)'],
 ['drh Budi', 'Program Profesi Dokter Hewan'],
 ['Budi, drh.', 'Program Profesi Dokter Hewan'],
 ['DRH. Budi, S.P.', 'Program Profesi Dokter Hewan'],
 ['Ir. Budi, M.Si.', 'Magister (S2)'],
 ['drh. Budi, M.Si.', 'Magister (S2)'],
 ['drh. Budi, Ph.D.', 'Doktor (S3)'],
 ['H. Ir. Budi', 'Diploma IV/Sarjana (S1)'],
 ['Ir. drh. Budi', 'Program Profesi Dokter Hewan'],
 ['Irawan', null],
 ['Irma Budi', null],
 ['Budi Irwan', null],
];
$results=[];foreach($cases as [$name,$expected]){
 $ok=infer_education($name)===$expected;$results[]=['test'=>$name,'passed'=>$ok];echo ($ok?'PASS ':'FAIL ').$name."\n";
}
foreach(EDUCATION_LEVELS as $level){$ok=education_value('Budi, Ph.D.',$level)===$level;$results[]=['test'=>'Manual: '.$level,'passed'=>$ok];}
try{education_value('Budi','SD palsu');$ok=false;}catch(DomainException){$ok=true;}$results[]=['test'=>'Nilai pendidikan palsu ditolak','passed'=>$ok];
$ok=education_value('Budi, S.P., M.Si','auto')==='Magister (S2)';$results[]=['test'=>'Mode otomatis memilih tertinggi','passed'=>$ok];
$failed=count(array_filter($results,fn($x)=>!$x['passed']));$passed=count($results)-$failed;
file_put_contents(__DIR__.'/hasil_pendidikan.json',json_encode(compact('passed','failed','results'),JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
echo "$passed passed, $failed failed\n";exit($failed?1:0);
