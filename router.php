<?php
// Router khusus php -S; produksi menggunakan Apache/.htaccess atau contoh Nginx.
$path=parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH);
$base=rtrim(getenv('APP_BASE_PATH')?:'','/');
if($base && !str_starts_with($path,$base.'/') && $path!==$base) { http_response_code(404); exit('Tidak ditemukan.'); }
$path=substr($path,strlen($base));
$routes=['/'=>'index.php',''=>'index.php','/index.php'=>'index.php','/admin'=>'Admin/index.php','/admin/'=>'Admin/index.php','/admin/index.php'=>'Admin/index.php','/admin/login.php'=>'Admin/login.php','/admin/logout.php'=>'Admin/logout.php','/admin/tambah-admin.php'=>'Admin/tambah-admin.php'];
if(isset($routes[$path])) { require __DIR__.'/'.$routes[$path]; return true; }
if(in_array($path,['/style.css','/app.js'],true)) { header('Content-Type: '.($path==='/style.css'?'text/css':'text/javascript').'; charset=utf-8'); header('X-Content-Type-Options: nosniff'); readfile(__DIR__.$path); return true; }
if($path==='/lambang-kota-samarinda.jpg'){header('Content-Type: image/jpeg');header('X-Content-Type-Options: nosniff');readfile(__DIR__.'/lambang-kota-samarinda.jpg');return true;}
http_response_code(404); echo 'Tidak ditemukan.';
