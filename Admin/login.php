<?php
require dirname(__DIR__).'/config.php'; require dirname(__DIR__).'/functions.php';
if(current_admin($pdo)) redirect('admin/');
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    check_csrf();
    $username=input('username'); $password=password_input();
    $error='Username atau password salah, atau akses sementara tidak tersedia. Silakan coba lagi.';
    if (login_ip_allowed()) {
        try {
            $pdo->beginTransaction();
            $q=$pdo->prepare('SELECT * FROM admin WHERE username=? FOR UPDATE'); $q->execute([$username]); $a=$q->fetch();
            // Hash valid berbiaya sama memastikan username tak dikenal tetap melalui password_verify.
            $hash=$a['password_hash']??'$2b$12$agFgxWqXMShAdldAm7BoHOSqIeogxR9YMxs3Y3qRbFdmH8HDaOJpG';
            $ok=password_verify(strlen($password)<=72 ? $password : '',$hash);
            $locked=$a && $a['locked_until'] && strtotime($a['locked_until'])>time();
            if ($a && $ok && !$locked && $a['aktif']) {
                $hash=password_needs_rehash($a['password_hash'],PASSWORD_BCRYPT,['cost'=>12]) ? password_hash($password,PASSWORD_BCRYPT,['cost'=>12]) : $a['password_hash'];
                $pdo->prepare('UPDATE admin SET failed_attempts=0,locked_until=NULL,last_login=NOW(),password_hash=? WHERE id=?')->execute([$hash,$a['id']]);
                $pdo->commit(); session_regenerate_id(true);
                $_SESSION['admin_id']=$a['id']; $_SESSION['auth_version']=$a['auth_version']; $_SESSION['signed_in']=$_SESSION['last_active']=time(); $_SESSION['csrf']=bin2hex(random_bytes(32));
                $manage=!empty($_SESSION['want_manage']); unset($_SESSION['want_manage']);
                redirect($manage && $a['role']==='superadmin' ? 'admin/?manage=1' : 'admin/');
            }
            if ($a && !$locked) {
                $n=($a['locked_until'] && strtotime($a['locked_until'])<=time() ? 0 : (int)$a['failed_attempts'])+1;
                $pdo->prepare('UPDATE admin SET failed_attempts=?,locked_until=? WHERE id=?')->execute([$n,$n>=5 ? date('Y-m-d H:i:s',time()+900) : null,$a['id']]);
            }
            $pdo->commit();
        } catch(Throwable $ex) { if($pdo->inTransaction()) $pdo->rollBack(); error_log($ex->getMessage()); }
    }
    http_response_code(401);
}
page_top('Login Admin'); ?>
<div class="login-layout"><section class="login-intro"><div class="eyebrow muted">RUANG PENGELOLA</div><h1>Data yang rapi.<br><em>Pelayanan lebih baik.</em></h1><p><?=e(APP_TITLE)?></p><div class="login-art" aria-hidden="true">✦<span>Terhubung. Terkelola. Terjaga.</span></div></section><section class="panel login-card"><div class="stat-symbol symbol-0">⌑</div><h2>Selamat datang kembali</h2><p>Masuk untuk mengelola data kepegawaian.</p><?php if($error): ?><div class="alert error" role="alert"><?=e($error)?></div><?php endif; show_flash(); ?>
<form method="post" action="<?=e(url('admin/login.php'))?>"><?=csrf_field()?><label>Username<input name="username" required maxlength="40" autocomplete="username" value="<?=e(input('username'))?>" autofocus></label><label>Password<div class="password-wrap"><input type="password" id="login-password" name="password" required maxlength="72" autocomplete="current-password"><button type="button" class="password-toggle" data-password="login-password" aria-label="Tampilkan password">Lihat</button></div></label><button class="btn primary full" type="submit">Masuk ke Admin <span>→</span></button></form><div class="login-footer"><a href="<?=e(url('admin/tambah-admin.php'))?>">＋ Tambah admin sistem</a><small>Memerlukan autentikasi superadmin.</small></div></section></div>
<?php page_bottom(); ?>
