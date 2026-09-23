<?php
declare(strict_types=1);
const STATUSES = ['PNS', 'PPPK Penuh Waktu', 'PPPK Paruh Waktu', 'PJLP'];
const MONTHS = [1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
function e(mixed $value): string { return htmlspecialchars((string)($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function url(string $path=''): string { return BASE_PATH.'/'.$path; }
function redirect(string $path): never { header('Location: '.url($path), true, 303); exit; }
function input(string $key): string { return isset($_POST[$key]) && is_string($_POST[$key]) ? trim($_POST[$key]) : ''; }
function password_input(): string { return isset($_POST['password']) && is_string($_POST['password']) ? $_POST['password'] : ''; }
function csrf(): string { return $_SESSION['csrf'] ??= bin2hex(random_bytes(32)); }
function csrf_field(): string { return '<input type="hidden" name="csrf" value="'.e(csrf()).'">'; }
function check_csrf(): void {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); header('Allow: POST'); exit('Metode tidak diizinkan.'); }
    if (!hash_equals(csrf(), input('csrf'))) { http_response_code(403); exit('Permintaan tidak valid. Muat ulang halaman dan coba lagi.'); }
}
function flash(string $message, string $type='success'): void { $_SESSION['flash'] = [$message,$type]; }
function show_flash(): void {
    if (isset($_SESSION['flash'])) { [$message,$type] = $_SESSION['flash']; unset($_SESSION['flash']); echo '<div class="alert '.e($type).'" role="status">'.e($message).'</div>'; }
}
function clear_auth(): void { unset($_SESSION['admin_id'],$_SESSION['auth_version'],$_SESSION['last_active'],$_SESSION['signed_in']); session_regenerate_id(true); }
function current_admin(PDO $pdo): ?array {
    if (empty($_SESSION['admin_id'])) return null;
    if (time()-(int)($_SESSION['last_active']??0)>1800 || time()-(int)($_SESSION['signed_in']??0)>28800) { clear_auth(); return null; }
    $q=$pdo->prepare('SELECT * FROM admin WHERE id=?'); $q->execute([$_SESSION['admin_id']]); $a=$q->fetch();
    if (!$a || !$a['aktif'] || (int)$a['auth_version']!==(int)($_SESSION['auth_version']??0)) { clear_auth(); return null; }
    $_SESSION['last_active']=time(); return $a;
}
function require_admin(PDO $pdo): array {
    $a=current_admin($pdo); if (!$a) redirect('admin/login.php'); return $a;
}
function require_super(array $a): void { if ($a['role']!=='superadmin') { http_response_code(403); exit('Akses hanya untuk superadmin.'); } }
function valid_date(string $s): ?DateTimeImmutable {
    $d=DateTimeImmutable::createFromFormat('!Y-m-d',$s);
    return $d && $d->format('Y-m-d')===$s ? $d : null;
}
function normalize_nip(string $s): string { return preg_replace('/\s+/u','',$s) ?? ''; }
function format_nip(string $nip): string { return strlen($nip)===18 ? substr($nip,0,8).' '.substr($nip,8,6).' '.substr($nip,14,1).' '.substr($nip,15,3) : '-'; }
function date_id(?DateTimeImmutable $d, bool $month=false): string { return $d ? ($month ? '' : $d->format('j').' ').MONTHS[(int)$d->format('n')].' '.$d->format('Y') : '-'; }
function employee_info(array $row, ?DateTimeImmutable $today=null): array {
    $today ??= new DateTimeImmutable('today');
    $nip=normalize_nip($row['nip']??''); $pjlp=$row['status_kepegawaian']==='PJLP'; $dob=null; $start=null; $gender=null;
    if (!$pjlp && preg_match('/^\d{18}$/D',$nip)) {
        $dob=valid_date(substr($nip,0,4).'-'.substr($nip,4,2).'-'.substr($nip,6,2));
        $gender=in_array($nip[14],['1','2'],true) ? $nip[14] : null;
        if ($row['status_kepegawaian']==='PNS') $start=valid_date(substr($nip,8,4).'-'.substr($nip,12,2).'-01');
    }
    if ($pjlp) { $dob=valid_date($row['tanggal_lahir_manual']??''); $gender=$row['jenis_kelamin_manual']??null; }
    elseif ($row['status_kepegawaian']!=='PNS') $start=valid_date($row['mulai_asn_manual']??'');
    $dob=$dob && $dob<=$today ? $dob : null;
    $start=$start && $start<=$today && (!$dob || $start>=$dob) ? $start : null;
    return ['dob'=>$dob,'start'=>$start,'age'=>$dob ? $dob->diff($today)->y : null,
        'start_age'=>$dob && $start ? $dob->diff($start)->y : null,
        'gender'=>$gender==='1' ? 'Laki-laki' : ($gender==='2' ? 'Perempuan' : '-'),
        'gender_code'=>$gender,'nip'=>$pjlp ? '-' : format_nip($nip)];
}
const EDUCATION_LEVELS = ['SD/sederajat', 'SMP/sederajat', 'SMA/sederajat', 'Diploma I - Diploma III', 'Diploma IV/Sarjana (S1)', 'Magister (S2)', 'Doktor (S3)', 'Program Profesi Dokter Hewan'];
const EDUCATION_PATTERNS = [
    'Doktor (S3)'=>'(^|[,;[:space:]])(P[[:space:]]*H[[:space:]]*D|D[[:space:]]*P[[:space:]]*H[[:space:]]*I[[:space:]]*L)([,;[:space:]]+(A[[:space:]]*M[[:space:]]*D[[:space:]]*F[[:space:]]*A[[:space:]]*R[[:space:]]*M|S[[:space:]]*T[[:space:]]*R[[:space:]]*K[[:space:]]*E[[:space:]]*P|A[[:space:]]*M[[:space:]]*D[[:space:]]*K[[:space:]]*O[[:space:]]*M|A[[:space:]]*M[[:space:]]*D[[:space:]]*K[[:space:]]*E[[:space:]]*P|D[[:space:]]*P[[:space:]]*H[[:space:]]*I[[:space:]]*L|S[[:space:]]*F[[:space:]]*A[[:space:]]*R[[:space:]]*M|S[[:space:]]*S[[:space:]]*T[[:space:]]*A[[:space:]]*T|A[[:space:]]*M[[:space:]]*D[[:space:]]*A[[:space:]]*K|M[[:space:]]*K[[:space:]]*O[[:space:]]*M|M[[:space:]]*S[[:space:]]*O[[:space:]]*S|M[[:space:]]*H[[:space:]]*U[[:space:]]*M|M[[:space:]]*K[[:space:]]*E[[:space:]]*S|M[[:space:]]*E[[:space:]]*N[[:space:]]*G|S[[:space:]]*K[[:space:]]*O[[:space:]]*M|S[[:space:]]*S[[:space:]]*O[[:space:]]*S|S[[:space:]]*H[[:space:]]*U[[:space:]]*T|S[[:space:]]*K[[:space:]]*E[[:space:]]*P|S[[:space:]]*A[[:space:]]*R[[:space:]]*S|P[[:space:]]*H[[:space:]]*D|M[[:space:]]*S[[:space:]]*I|M[[:space:]]*A[[:space:]]*P|M[[:space:]]*P[[:space:]]*D|M[[:space:]]*A[[:space:]]*G|M[[:space:]]*K[[:space:]]*M|M[[:space:]]*B[[:space:]]*A|M[[:space:]]*S[[:space:]]*C|M[[:space:]]*P[[:space:]]*A|D[[:space:]]*R[[:space:]]*H|S[[:space:]]*P[[:space:]]*T|S[[:space:]]*A[[:space:]]*G|S[[:space:]]*P[[:space:]]*D|S[[:space:]]*T[[:space:]]*P|S[[:space:]]*K[[:space:]]*M|S[[:space:]]*I[[:space:]]*P|S[[:space:]]*A[[:space:]]*P|S[[:space:]]*I[[:space:]]*K|S[[:space:]]*S[[:space:]]*I|S[[:space:]]*A[[:space:]]*K|S[[:space:]]*D[[:space:]]*S|S[[:space:]]*T[[:space:]]*R|A[[:space:]]*M[[:space:]]*D|A[[:space:]]*M[[:space:]]*P|A[[:space:]]*M[[:space:]]*A|C[[:space:]]*P[[:space:]]*A|A[[:space:]]*P[[:space:]]*T|M[[:space:]]*M|M[[:space:]]*P|M[[:space:]]*T|M[[:space:]]*H|M[[:space:]]*A|M[[:space:]]*S|S[[:space:]]*P|S[[:space:]]*T|S[[:space:]]*E|S[[:space:]]*H|S[[:space:]]*S|I[[:space:]]*R|A[[:space:]]*K|C[[:space:]]*A|N[[:space:]]*S|G[[:space:]]*R))*[,;[:space:]]*$',
    'Magister (S2)'=>'(^|[,;[:space:]])(M[[:space:]]*S[[:space:]]*I|M[[:space:]]*M|M[[:space:]]*A[[:space:]]*P|M[[:space:]]*P|M[[:space:]]*P[[:space:]]*D|M[[:space:]]*K[[:space:]]*O[[:space:]]*M|M[[:space:]]*T|M[[:space:]]*H|M[[:space:]]*A[[:space:]]*G|M[[:space:]]*S[[:space:]]*O[[:space:]]*S|M[[:space:]]*H[[:space:]]*U[[:space:]]*M|M[[:space:]]*K[[:space:]]*E[[:space:]]*S|M[[:space:]]*K[[:space:]]*M|M[[:space:]]*B[[:space:]]*A|M[[:space:]]*S[[:space:]]*C|M[[:space:]]*E[[:space:]]*N[[:space:]]*G|M[[:space:]]*P[[:space:]]*A|M[[:space:]]*A|M[[:space:]]*S)([,;[:space:]]+(A[[:space:]]*M[[:space:]]*D[[:space:]]*F[[:space:]]*A[[:space:]]*R[[:space:]]*M|S[[:space:]]*T[[:space:]]*R[[:space:]]*K[[:space:]]*E[[:space:]]*P|A[[:space:]]*M[[:space:]]*D[[:space:]]*K[[:space:]]*O[[:space:]]*M|A[[:space:]]*M[[:space:]]*D[[:space:]]*K[[:space:]]*E[[:space:]]*P|D[[:space:]]*P[[:space:]]*H[[:space:]]*I[[:space:]]*L|S[[:space:]]*F[[:space:]]*A[[:space:]]*R[[:space:]]*M|S[[:space:]]*S[[:space:]]*T[[:space:]]*A[[:space:]]*T|A[[:space:]]*M[[:space:]]*D[[:space:]]*A[[:space:]]*K|M[[:space:]]*K[[:space:]]*O[[:space:]]*M|M[[:space:]]*S[[:space:]]*O[[:space:]]*S|M[[:space:]]*H[[:space:]]*U[[:space:]]*M|M[[:space:]]*K[[:space:]]*E[[:space:]]*S|M[[:space:]]*E[[:space:]]*N[[:space:]]*G|S[[:space:]]*K[[:space:]]*O[[:space:]]*M|S[[:space:]]*S[[:space:]]*O[[:space:]]*S|S[[:space:]]*H[[:space:]]*U[[:space:]]*T|S[[:space:]]*K[[:space:]]*E[[:space:]]*P|S[[:space:]]*A[[:space:]]*R[[:space:]]*S|P[[:space:]]*H[[:space:]]*D|M[[:space:]]*S[[:space:]]*I|M[[:space:]]*A[[:space:]]*P|M[[:space:]]*P[[:space:]]*D|M[[:space:]]*A[[:space:]]*G|M[[:space:]]*K[[:space:]]*M|M[[:space:]]*B[[:space:]]*A|M[[:space:]]*S[[:space:]]*C|M[[:space:]]*P[[:space:]]*A|D[[:space:]]*R[[:space:]]*H|S[[:space:]]*P[[:space:]]*T|S[[:space:]]*A[[:space:]]*G|S[[:space:]]*P[[:space:]]*D|S[[:space:]]*T[[:space:]]*P|S[[:space:]]*K[[:space:]]*M|S[[:space:]]*I[[:space:]]*P|S[[:space:]]*A[[:space:]]*P|S[[:space:]]*I[[:space:]]*K|S[[:space:]]*S[[:space:]]*I|S[[:space:]]*A[[:space:]]*K|S[[:space:]]*D[[:space:]]*S|S[[:space:]]*T[[:space:]]*R|A[[:space:]]*M[[:space:]]*D|A[[:space:]]*M[[:space:]]*P|A[[:space:]]*M[[:space:]]*A|C[[:space:]]*P[[:space:]]*A|A[[:space:]]*P[[:space:]]*T|M[[:space:]]*M|M[[:space:]]*P|M[[:space:]]*T|M[[:space:]]*H|M[[:space:]]*A|M[[:space:]]*S|S[[:space:]]*P|S[[:space:]]*T|S[[:space:]]*E|S[[:space:]]*H|S[[:space:]]*S|I[[:space:]]*R|A[[:space:]]*K|C[[:space:]]*A|N[[:space:]]*S|G[[:space:]]*R))*[,;[:space:]]*$',
    'Program Profesi Dokter Hewan'=>'((^|[,;[:space:]])(D[[:space:]]*R[[:space:]]*H)([,;[:space:]]+(A[[:space:]]*M[[:space:]]*D[[:space:]]*F[[:space:]]*A[[:space:]]*R[[:space:]]*M|S[[:space:]]*T[[:space:]]*R[[:space:]]*K[[:space:]]*E[[:space:]]*P|A[[:space:]]*M[[:space:]]*D[[:space:]]*K[[:space:]]*O[[:space:]]*M|A[[:space:]]*M[[:space:]]*D[[:space:]]*K[[:space:]]*E[[:space:]]*P|D[[:space:]]*P[[:space:]]*H[[:space:]]*I[[:space:]]*L|S[[:space:]]*F[[:space:]]*A[[:space:]]*R[[:space:]]*M|S[[:space:]]*S[[:space:]]*T[[:space:]]*A[[:space:]]*T|A[[:space:]]*M[[:space:]]*D[[:space:]]*A[[:space:]]*K|M[[:space:]]*K[[:space:]]*O[[:space:]]*M|M[[:space:]]*S[[:space:]]*O[[:space:]]*S|M[[:space:]]*H[[:space:]]*U[[:space:]]*M|M[[:space:]]*K[[:space:]]*E[[:space:]]*S|M[[:space:]]*E[[:space:]]*N[[:space:]]*G|S[[:space:]]*K[[:space:]]*O[[:space:]]*M|S[[:space:]]*S[[:space:]]*O[[:space:]]*S|S[[:space:]]*H[[:space:]]*U[[:space:]]*T|S[[:space:]]*K[[:space:]]*E[[:space:]]*P|S[[:space:]]*A[[:space:]]*R[[:space:]]*S|P[[:space:]]*H[[:space:]]*D|M[[:space:]]*S[[:space:]]*I|M[[:space:]]*A[[:space:]]*P|M[[:space:]]*P[[:space:]]*D|M[[:space:]]*A[[:space:]]*G|M[[:space:]]*K[[:space:]]*M|M[[:space:]]*B[[:space:]]*A|M[[:space:]]*S[[:space:]]*C|M[[:space:]]*P[[:space:]]*A|D[[:space:]]*R[[:space:]]*H|S[[:space:]]*P[[:space:]]*T|S[[:space:]]*A[[:space:]]*G|S[[:space:]]*P[[:space:]]*D|S[[:space:]]*T[[:space:]]*P|S[[:space:]]*K[[:space:]]*M|S[[:space:]]*I[[:space:]]*P|S[[:space:]]*A[[:space:]]*P|S[[:space:]]*I[[:space:]]*K|S[[:space:]]*S[[:space:]]*I|S[[:space:]]*A[[:space:]]*K|S[[:space:]]*D[[:space:]]*S|S[[:space:]]*T[[:space:]]*R|A[[:space:]]*M[[:space:]]*D|A[[:space:]]*M[[:space:]]*P|A[[:space:]]*M[[:space:]]*A|C[[:space:]]*P[[:space:]]*A|A[[:space:]]*P[[:space:]]*T|M[[:space:]]*M|M[[:space:]]*P|M[[:space:]]*T|M[[:space:]]*H|M[[:space:]]*A|M[[:space:]]*S|S[[:space:]]*P|S[[:space:]]*T|S[[:space:]]*E|S[[:space:]]*H|S[[:space:]]*S|I[[:space:]]*R|A[[:space:]]*K|C[[:space:]]*A|N[[:space:]]*S|G[[:space:]]*R))*[,;[:space:]]*$)|(^((PROF|H|HJ|IR|DRH)[[:space:]]+)*DRH[[:space:]]+[^[:space:]])',
    'Diploma IV/Sarjana (S1)'=>'((^|[,;[:space:]])(S[[:space:]]*P|S[[:space:]]*P[[:space:]]*T|S[[:space:]]*K[[:space:]]*O[[:space:]]*M|S[[:space:]]*T|S[[:space:]]*E|S[[:space:]]*H|S[[:space:]]*A[[:space:]]*G|S[[:space:]]*S[[:space:]]*O[[:space:]]*S|S[[:space:]]*P[[:space:]]*D|S[[:space:]]*T[[:space:]]*P|S[[:space:]]*H[[:space:]]*U[[:space:]]*T|S[[:space:]]*K[[:space:]]*M|S[[:space:]]*I[[:space:]]*P|S[[:space:]]*A[[:space:]]*P|S[[:space:]]*I[[:space:]]*K|S[[:space:]]*S|S[[:space:]]*S[[:space:]]*I|S[[:space:]]*K[[:space:]]*E[[:space:]]*P|S[[:space:]]*F[[:space:]]*A[[:space:]]*R[[:space:]]*M|S[[:space:]]*A[[:space:]]*K|S[[:space:]]*D[[:space:]]*S|S[[:space:]]*S[[:space:]]*T[[:space:]]*A[[:space:]]*T|S[[:space:]]*A[[:space:]]*R[[:space:]]*S|S[[:space:]]*T[[:space:]]*R|S[[:space:]]*T[[:space:]]*R[[:space:]]*K[[:space:]]*E[[:space:]]*P|I[[:space:]]*R)([,;[:space:]]+(A[[:space:]]*M[[:space:]]*D[[:space:]]*F[[:space:]]*A[[:space:]]*R[[:space:]]*M|S[[:space:]]*T[[:space:]]*R[[:space:]]*K[[:space:]]*E[[:space:]]*P|A[[:space:]]*M[[:space:]]*D[[:space:]]*K[[:space:]]*O[[:space:]]*M|A[[:space:]]*M[[:space:]]*D[[:space:]]*K[[:space:]]*E[[:space:]]*P|D[[:space:]]*P[[:space:]]*H[[:space:]]*I[[:space:]]*L|S[[:space:]]*F[[:space:]]*A[[:space:]]*R[[:space:]]*M|S[[:space:]]*S[[:space:]]*T[[:space:]]*A[[:space:]]*T|A[[:space:]]*M[[:space:]]*D[[:space:]]*A[[:space:]]*K|M[[:space:]]*K[[:space:]]*O[[:space:]]*M|M[[:space:]]*S[[:space:]]*O[[:space:]]*S|M[[:space:]]*H[[:space:]]*U[[:space:]]*M|M[[:space:]]*K[[:space:]]*E[[:space:]]*S|M[[:space:]]*E[[:space:]]*N[[:space:]]*G|S[[:space:]]*K[[:space:]]*O[[:space:]]*M|S[[:space:]]*S[[:space:]]*O[[:space:]]*S|S[[:space:]]*H[[:space:]]*U[[:space:]]*T|S[[:space:]]*K[[:space:]]*E[[:space:]]*P|S[[:space:]]*A[[:space:]]*R[[:space:]]*S|P[[:space:]]*H[[:space:]]*D|M[[:space:]]*S[[:space:]]*I|M[[:space:]]*A[[:space:]]*P|M[[:space:]]*P[[:space:]]*D|M[[:space:]]*A[[:space:]]*G|M[[:space:]]*K[[:space:]]*M|M[[:space:]]*B[[:space:]]*A|M[[:space:]]*S[[:space:]]*C|M[[:space:]]*P[[:space:]]*A|D[[:space:]]*R[[:space:]]*H|S[[:space:]]*P[[:space:]]*T|S[[:space:]]*A[[:space:]]*G|S[[:space:]]*P[[:space:]]*D|S[[:space:]]*T[[:space:]]*P|S[[:space:]]*K[[:space:]]*M|S[[:space:]]*I[[:space:]]*P|S[[:space:]]*A[[:space:]]*P|S[[:space:]]*I[[:space:]]*K|S[[:space:]]*S[[:space:]]*I|S[[:space:]]*A[[:space:]]*K|S[[:space:]]*D[[:space:]]*S|S[[:space:]]*T[[:space:]]*R|A[[:space:]]*M[[:space:]]*D|A[[:space:]]*M[[:space:]]*P|A[[:space:]]*M[[:space:]]*A|C[[:space:]]*P[[:space:]]*A|A[[:space:]]*P[[:space:]]*T|M[[:space:]]*M|M[[:space:]]*P|M[[:space:]]*T|M[[:space:]]*H|M[[:space:]]*A|M[[:space:]]*S|S[[:space:]]*P|S[[:space:]]*T|S[[:space:]]*E|S[[:space:]]*H|S[[:space:]]*S|I[[:space:]]*R|A[[:space:]]*K|C[[:space:]]*A|N[[:space:]]*S|G[[:space:]]*R))*[,;[:space:]]*$)|(^((PROF|H|HJ|IR|DRH)[[:space:]]+)*IR[[:space:]]+[^[:space:]])',
    'Diploma I - Diploma III'=>'(^|[,;[:space:]])(A[[:space:]]*M[[:space:]]*D|A[[:space:]]*M[[:space:]]*D[[:space:]]*K[[:space:]]*O[[:space:]]*M|A[[:space:]]*M[[:space:]]*D[[:space:]]*K[[:space:]]*E[[:space:]]*P|A[[:space:]]*M[[:space:]]*D[[:space:]]*A[[:space:]]*K|A[[:space:]]*M[[:space:]]*D[[:space:]]*F[[:space:]]*A[[:space:]]*R[[:space:]]*M|A[[:space:]]*M[[:space:]]*P|A[[:space:]]*M[[:space:]]*A)([,;[:space:]]+(A[[:space:]]*M[[:space:]]*D[[:space:]]*F[[:space:]]*A[[:space:]]*R[[:space:]]*M|S[[:space:]]*T[[:space:]]*R[[:space:]]*K[[:space:]]*E[[:space:]]*P|A[[:space:]]*M[[:space:]]*D[[:space:]]*K[[:space:]]*O[[:space:]]*M|A[[:space:]]*M[[:space:]]*D[[:space:]]*K[[:space:]]*E[[:space:]]*P|D[[:space:]]*P[[:space:]]*H[[:space:]]*I[[:space:]]*L|S[[:space:]]*F[[:space:]]*A[[:space:]]*R[[:space:]]*M|S[[:space:]]*S[[:space:]]*T[[:space:]]*A[[:space:]]*T|A[[:space:]]*M[[:space:]]*D[[:space:]]*A[[:space:]]*K|M[[:space:]]*K[[:space:]]*O[[:space:]]*M|M[[:space:]]*S[[:space:]]*O[[:space:]]*S|M[[:space:]]*H[[:space:]]*U[[:space:]]*M|M[[:space:]]*K[[:space:]]*E[[:space:]]*S|M[[:space:]]*E[[:space:]]*N[[:space:]]*G|S[[:space:]]*K[[:space:]]*O[[:space:]]*M|S[[:space:]]*S[[:space:]]*O[[:space:]]*S|S[[:space:]]*H[[:space:]]*U[[:space:]]*T|S[[:space:]]*K[[:space:]]*E[[:space:]]*P|S[[:space:]]*A[[:space:]]*R[[:space:]]*S|P[[:space:]]*H[[:space:]]*D|M[[:space:]]*S[[:space:]]*I|M[[:space:]]*A[[:space:]]*P|M[[:space:]]*P[[:space:]]*D|M[[:space:]]*A[[:space:]]*G|M[[:space:]]*K[[:space:]]*M|M[[:space:]]*B[[:space:]]*A|M[[:space:]]*S[[:space:]]*C|M[[:space:]]*P[[:space:]]*A|D[[:space:]]*R[[:space:]]*H|S[[:space:]]*P[[:space:]]*T|S[[:space:]]*A[[:space:]]*G|S[[:space:]]*P[[:space:]]*D|S[[:space:]]*T[[:space:]]*P|S[[:space:]]*K[[:space:]]*M|S[[:space:]]*I[[:space:]]*P|S[[:space:]]*A[[:space:]]*P|S[[:space:]]*I[[:space:]]*K|S[[:space:]]*S[[:space:]]*I|S[[:space:]]*A[[:space:]]*K|S[[:space:]]*D[[:space:]]*S|S[[:space:]]*T[[:space:]]*R|A[[:space:]]*M[[:space:]]*D|A[[:space:]]*M[[:space:]]*P|A[[:space:]]*M[[:space:]]*A|C[[:space:]]*P[[:space:]]*A|A[[:space:]]*P[[:space:]]*T|M[[:space:]]*M|M[[:space:]]*P|M[[:space:]]*T|M[[:space:]]*H|M[[:space:]]*A|M[[:space:]]*S|S[[:space:]]*P|S[[:space:]]*T|S[[:space:]]*E|S[[:space:]]*H|S[[:space:]]*S|I[[:space:]]*R|A[[:space:]]*K|C[[:space:]]*A|N[[:space:]]*S|G[[:space:]]*R))*[,;[:space:]]*$',
];
// Prioritas deteksi: S3, S2, profesi dokter hewan, S1, diploma. Ir/drh juga dikenali di depan nama.
function infer_education(string $name): ?string {
    $normalized=mb_strtoupper(str_replace('.', '', trim($name)),'UTF-8');
    foreach(EDUCATION_PATTERNS as $level=>$pattern) if(preg_match('~'.$pattern.'~u',$normalized)) return $level;
    return null;
}
function education_value(string $name, string $choice): ?string {
    if($choice==='auto') return infer_education($name);
    if(!in_array($choice,EDUCATION_LEVELS,true)) throw new DomainException('Pilihan pendidikan terakhir tidak valid.');
    return $choice;
}

function validate_employee(array $data): array {
    $name=trim($data['nama']??''); $job=trim($data['jabatan']??''); $status=$data['status_kepegawaian']??'';
    if ($name==='' || mb_strlen($name)>150) throw new DomainException('Nama wajib diisi, maksimal 150 karakter.');
    if (mb_strlen($job)>255) throw new DomainException('Jabatan maksimal 255 karakter.');
    if (!in_array($status,STATUSES,true)) throw new DomainException('Status pegawai tidak valid.');
    $nip=$status==='PJLP' ? '-' : normalize_nip($data['nip']??'');
    $birth=null; $sex=null; $manual=null; $today=new DateTimeImmutable('today');
    if ($status!=='PJLP') {
        if (!preg_match('/^\d{18}$/D',$nip)) throw new DomainException('NIP/NIPPPK harus terdiri dari 18 digit angka.');
        $birth=valid_date(substr($nip,0,4).'-'.substr($nip,4,2).'-'.substr($nip,6,2));
        if (!$birth || $birth>$today) throw new DomainException('Tanggal lahir dalam NIP/NIPPPK tidak valid.');
        if (!in_array($nip[14],['1','2'],true)) throw new DomainException('Digit jenis kelamin harus 1 atau 2.');
        if ($status==='PNS') $start=valid_date(substr($nip,8,4).'-'.substr($nip,12,2).'-01');
        else {
            $m=$data['mulai_asn_manual']??'';
            if (!preg_match('/^\d{4}-\d{2}$/D',$m)) throw new DomainException('Bulan dan tahun mulai ASN wajib diisi untuk PPPK.');
            $start=valid_date($m.'-01'); $manual=$start ? $start->format('Y-m-d') : null;
        }
        if (!$start || $start>$today || $start<$birth) throw new DomainException('Bulan mulai ASN tidak valid atau berada di masa depan/sebelum kelahiran.');
    } else {
        $b=$data['tanggal_lahir_manual']??''; $g=$data['jenis_kelamin_manual']??'';
        if ($b!=='') { $birth=valid_date($b); if (!$birth || $birth>$today) throw new DomainException('Tanggal lahir tidak valid.'); }
        if ($g!=='' && !in_array($g,['1','2'],true)) throw new DomainException('Jenis kelamin tidak valid.');
        $sex=$g!=='' ? $g : null;
    }
    return [$name,$nip,$job,$status,$manual,$status==='PJLP' && $birth ? $birth->format('Y-m-d') : null,$sex,education_value($name,$data['pendidikan_terakhir']??'auto')];
}
function validate_account(string $name,string $username,string $password,bool $new): void {
    if ($name==='' || mb_strlen($name)>100) throw new DomainException('Nama admin wajib diisi, maksimal 100 karakter.');
    if (!preg_match('/^[A-Za-z0-9_.-]{3,40}$/D',$username)) throw new DomainException('Username 3–40 karakter: huruf, angka, titik, garis bawah, atau tanda hubung.');
    if (($new || $password!=='') && (strlen($password)<10 || strlen($password)>72)) throw new DomainException('Password baru harus 10–72 byte.');
}
// Pembatasan berbasis IP di direktori temporer sistem, bukan di document root.
// REMOTE_ADDR dipercaya; header forwarded dari klien tidak dipercaya.
function login_ip_allowed(): bool {
    $key=hash('sha256',__DIR__.'|'.($_SERVER['REMOTE_ADDR']??'cli'));
    $file=sys_get_temp_dir().'/distapangtani-login-'.$key;
    $fp=fopen($file,'c+'); if (!$fp || !flock($fp,LOCK_EX)) { if ($fp) fclose($fp); return false; }
    chmod($file,0600);
    $state=json_decode(stream_get_contents($fp)?:'[]',true);
    if (!is_array($state) || (int)($state['until']??0)<=time()) $state=['count'=>0,'until'=>time()+900];
    $state['count']++; $allowed=$state['count']<=30;
    ftruncate($fp,0); rewind($fp); fwrite($fp,json_encode($state)); fflush($fp); flock($fp,LOCK_UN); fclose($fp); return $allowed;
}
function page_top(string $section, ?array $admin=null, string $assetVersion=''): void {
$emblemUrl='https://upload.wikimedia.org/wikipedia/commons/3/3b/Lambang_Kota_Samarinda.jpg?utm_source=commons.wikimedia.org&utm_campaign=index&utm_content=original'; ?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title><?=e($section)?> · Kepegawaian Distapangtani</title><link id="city-favicon" rel="icon" type="image/jpeg" href="<?=e($emblemUrl)?>" data-fallback="<?=e(url('lambang-kota-samarinda.jpg'))?>"><link rel="stylesheet" href="<?=e(url('style.css').($assetVersion!==''?'?v='.rawurlencode($assetVersion):''))?>"><script src="<?=e(url('app.js').($assetVersion!==''?'?v='.rawurlencode($assetVersion):''))?>" defer></script></head><body>
<header class="topbar"><?php if($section==='Beranda'): ?><div class="brand"><a class="city-logo" title="Government of Samarinda City, CC BY-SA 3.0 &lt;https://creativecommons.org/licenses/by-sa/3.0&gt;, via Wikimedia Commons" href="https://commons.wikimedia.org/wiki/File:Lambang_Kota_Samarinda.jpg" target="_blank" rel="noopener noreferrer"><img width="44" height="48" alt="Lambang Kota Samarinda" src="<?=e($emblemUrl)?>" data-fallback="<?=e(url('lambang-kota-samarinda.jpg'))?>"></a><a href="<?=e(url())?>">DISTAPANGTANI<small>KOTA SAMARINDA</small></a></div><?php else: ?><a class="brand" href="<?=e(url())?>"><span class="brand-icon" aria-hidden="true">D<span>✦</span></span><span>DISTAPANGTANI<small>KOTA SAMARINDA</small></span></a><?php endif ?><nav class="<?= $admin ? 'private-nav' : 'public-nav' ?>" aria-label="Navigasi utama"><?php if ($admin): ?><span class="nav-active">Administrasi</span><span class="account"><?=e($admin['nama'])?> <small><?=e($admin['role'])?></small></span><form action="<?=e(url('admin/logout.php'))?>" method="post"><?=csrf_field()?><button class="btn quiet" type="submit">Keluar ↗</button></form><?php else: ?><a class="nav-active" href="<?=e(url())?>">Beranda</a><span class="portal-label">Portal kepegawaian</span><?php endif ?></nav></header>
<main class="shell"><?php }
function page_bottom(): void { ?><footer><span>Distapangtani · Pemerintah Kota Samarinda</span><span>Data kepegawaian • <?=date('Y')?></span></footer></main></body></html><?php }

// Analitik publik: hanya agregat; satu pegawai tepat satu kelompok jabatan.
const PUBLIC_JOB_LABELS = [
 'str_head'=>'Kepala dinas','str_secretary'=>'Sekretaris','str_division'=>'Kepala bidang',
 'str_subdivision'=>'Kepala subbagian','str_acting'=>'PLT','str_unit'=>'Kepala UPTD / unit',
 'fun_first'=>'Jabatan Fungsional Keahlian tingkat Pertama','fun_young'=>'Jabatan Fungsional Keahlian tingkat Muda','fun_middle'=>'Jabatan Fungsional Keahlian tingkat Madya',
 'fun_top'=>'Jabatan Fungsional Keahlian tingkat Utama','fun_skilled'=>'Jabatan Fungsional Keterampilan tingkat Pertama','fun_proficient'=>'Jabatan Fungsional Keterampilan tingkat Mahir',
 'fun_supervisor'=>'Jabatan Fungsional Keterampilan tingkat Penyelia','fun_beginner'=>'Jabatan Fungsional Keterampilan tingkat Pemula',
 'fun_unspecified'=>'Jabatan Fungsional (jenjang belum tercatat)','executive'=>'Pelaksana'];
function public_job_group(string $title): ?string {
 $s=mb_strtolower(trim($title),'UTF-8'); $s=preg_replace('/[.\s]+/u',' ',$s);
 if($s==='' || $s==='-') return null;
 if(preg_match('/\b(plt|pelaksana tugas)\b/u',$s)) return 'str_acting';
 foreach(['str_head'=>'kepala dinas|kadis','str_secretary'=>'sekretaris|sekdis',
 'str_division'=>'kepala bidang|kabid','str_subdivision'=>'kepala sub[ -]?bagian|kasubbag|kasubag',
 'str_unit'=>'kepala uptd?|kepala unit'] as $key=>$pattern) if(preg_match('/\b('.$pattern.')\b/u',$s)) return $key;
 // Whitelist rumpun yang ada pada data; hindari "muda" pada nama jabatan pelaksana biasa.
 $family='analis ketahanan pangan|analis keuangan|analis pasar hasil|medik veteriner|paramedik veteriner|pengawas (alat|benih|bibit|mutu)|pengendali organisme|penyuluh pertanian';
 if(preg_match('/\b(ahli|terampil|mahir|penyelia|pemula)\b/u',$s) || preg_match('/^('.$family.')\b/u',$s)) {
  foreach(['first'=>'pertama','young'=>'muda','middle'=>'madya','top'=>'utama','skilled'=>'terampil','proficient'=>'mahir','supervisor'=>'penyelia','beginner'=>'pemula'] as $key=>$word)
   if(preg_match('/\b'.$word.'\b/u',$s)) return 'fun_'.$key;
  return 'fun_unspecified';
 }
 return 'executive';
}
function public_age_stats(array $values): array {
 sort($values,SORT_NUMERIC); $n=count($values);
 return ['n'=>$n,'min'=>$n?$values[0]:null,'mean'=>$n?array_sum($values)/$n:null,
 'median'=>$n?($n%2?$values[intdiv($n,2)]:($values[$n/2-1]+$values[$n/2])/2):null,'max'=>$n?$values[$n-1]:null];
}
function public_analytics(array $rows, ?DateTimeImmutable $today=null): array {
 $today??=new DateTimeImmutable('today');
 $empty=['total'=>0,'generations'=>['X'=>0,'Y'=>0,'Z'=>0],'genders'=>['male'=>0,'female'=>0],
 'education'=>array_fill_keys(EDUCATION_LEVELS,0),'jobs'=>array_fill_keys(array_keys(PUBLIC_JOB_LABELS),0),'ages'=>[]];
 $data=[];foreach(array_merge(['all'],STATUSES) as $status) foreach(['all','male','female'] as $sex) $data[$status][$sex]=$empty;
 foreach($rows as $row) {
  $info=employee_info($row,$today);$sex=$info['gender_code']==='1'?'male':($info['gender_code']==='2'?'female':null);
  $year=$info['dob']?(int)$info['dob']->format('Y'):null;
  $gen=$year>=1965&&$year<=1980?'X':($year>=1981&&$year<=1996?'Y':($year>=1997&&$year<=2012?'Z':null));
  $job=public_job_group($row['jabatan']??''); $education=$row['pendidikan_terakhir']??null;
  $statuses=['all'];if(in_array($row['status_kepegawaian'],STATUSES,true))$statuses[]=$row['status_kepegawaian'];
  foreach($statuses as $status) foreach($sex?['all',$sex]:['all'] as $gender) {
   $d=&$data[$status][$gender];$d['total']++;
   if($gen)$d['generations'][$gen]++;if($sex)$d['genders'][$sex]++;
   if(isset($d['education'][$education??'']))$d['education'][$education]++;
   if($job)$d['jobs'][$job]++;
   if($row['status_kepegawaian']!=='PJLP' && $info['start_age']!==null)$d['ages'][]=$info['start_age'];
   unset($d);
  }
 }
 foreach($data as &$group)foreach($group as &$d){$d['ages']=public_age_stats($d['ages']);$d['job_details']=public_job_details($d['jobs']);}unset($group,$d);
 return $data;
}

// Whitelist rincian diterapkan di server, terpisah dari komposisi jabatan utama.
function public_job_details(array $jobs): array {
 $details=[];
 foreach(PUBLIC_JOB_LABELS as $key=>$label) {
  if(!str_starts_with($key,'fun_'))continue;
  $count=$jobs[$key]??0;
  if(is_numeric($count) && (int)$count>0)$details[$label]=(int)$count;
 }
 return $details;
}
function public_render_job_details(array $details): void {
 $total=array_sum($details);
 if(!$total){echo '<p class="empty-analytics">Belum ada pegawai fungsional pada pilihan ini.</p>';return;}
 foreach($details as $label=>$count){
  echo '<div class="distribution-row"><div class="distribution-label"><span>'.e($label).'</span><strong>'.e($count).' pegawai</strong></div><progress value="'.e($count).'" max="'.e($total).'" aria-label="'.e($label).': '.e($count).' pegawai"></progress></div>';
 }
}
