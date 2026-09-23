<?php
require dirname(__DIR__).'/config.php'; require dirname(__DIR__).'/functions.php';
$a=current_admin($pdo);
if(!$a) { $_SESSION['want_manage']=true; flash('Masuk sebagai superadmin untuk menambahkan admin sistem.','info'); redirect('admin/login.php'); }
require_super($a); redirect('admin/?manage=1');
