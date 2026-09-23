<?php
declare(strict_types=1);
// PDO native: nilai default mengikuti konfigurasi yang diminta.
$host = getenv('DB_HOST') ?: 'localhost';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') !== false ? getenv('DB_PASS') : 'Ericaja13.';
$db   = getenv('DB_NAME') ?: 'kepegawaian_distapangtani';
define('APP_TITLE', 'Kepegawaian Dinas Ketahanan Pangan dan Pertanian (Distapangtani) Kota Samarinda');
define('BASE_PATH', rtrim(getenv('APP_BASE_PATH') ?: '', '/'));
date_default_timezone_set('Asia/Makassar');
ini_set('display_errors', '0');
ini_set('log_errors', '1');
ini_set('session.use_strict_mode', '1');
ini_set('session.use_only_cookies', '1');
$secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || getenv('APP_HTTPS') === '1';
if (PHP_SAPI !== 'cli') {
    header('Content-Type: text/html; charset=utf-8');
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');
    header('Referrer-Policy: same-origin');
    header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
    header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self'; img-src 'self' data:https://thumb.wikimedia.org https://upload.wikimedia.org; font-src 'self'; connect-src 'self'; frame-ancestors 'none'; base-uri 'self'; form-action 'self'");
    header('Cache-Control: no-store, private');
    if ($secure) header('Strict-Transport-Security: max-age=31536000');
}
if (session_status() === PHP_SESSION_NONE) {
    session_name('DISTAPANGTANI_SESSION');
    session_set_cookie_params(['lifetime'=>0, 'path'=>BASE_PATH.'/', 'secure'=>$secure, 'httponly'=>true, 'samesite'=>'Lax']);
    session_start();
}
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES=>false,
    ]);
    $pdo->exec("SET time_zone = '+08:00'");
} catch (PDOException $e) {
    error_log('Database connection failed: '.$e->getMessage());
    http_response_code(503);
    exit('Layanan belum tersedia. Silakan hubungi pengelola sistem.');
}
if (!function_exists('sanitize')) {
    function sanitize($data): string { return htmlspecialchars(strip_tags(trim((string)$data)), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
}
