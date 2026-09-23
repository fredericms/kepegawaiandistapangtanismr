<?php
require dirname(__DIR__).'/config.php'; require dirname(__DIR__).'/functions.php';
check_csrf(); $_SESSION=[];
if(ini_get('session.use_cookies')) { $p=session_get_cookie_params(); setcookie(session_name(),'', ['expires'=>time()-42000,'path'=>$p['path'],'secure'=>$p['secure'],'httponly'=>true,'samesite'=>'Lax']); }
session_destroy(); redirect('admin/login.php');
