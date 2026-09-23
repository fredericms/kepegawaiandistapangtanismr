# Kepegawaian Distapangtani Kota Samarinda

Aplikasi PHP native, PDO MySQL/MariaDB, CSS dan JavaScript lokal. Tidak menggunakan Laravel, CodeIgniter, Composer, npm, framework backend, atau CDN. Antarmuka dashboard responsif menggunakan CSS khusus agar seluruh aset dapat berjalan tanpa internet. JavaScript harus aktif untuk dialog, pencarian, filter, dan pagination.

Judul sistem: **Kepegawaian Dinas Ketahanan Pangan dan Pertanian (Distapangtani) Kota Samarinda**.

## Isi dan struktur

Hanya terdapat dua direktori: `Kepegawaian/` dan `Kepegawaian/Admin/`. Semua aset, SQL, panduan, hasil pengujian, dan skrip pengujian disimpan langsung pada direktori utama; tidak ada direktori bertingkat tambahan.

| Lokasi | Fungsi |
|---|---|
| `index.php` | Beranda publik: statistik dan direktori pegawai |
| `Admin/index.php` | Satu halaman Admin: tabel pegawai, dialog tambah/edit, manajemen admin, ganti password |
| `Admin/login.php` | Login dengan pesan kesalahan umum |
| `Admin/tambah-admin.php` | Pintasan penambahan admin yang meminta autentikasi superadmin |
| `Admin/logout.php` | Logout melalui POST + CSRF |
| `config.php` | Koneksi PDO, sesi, header keamanan, konfigurasi |
| `functions.php` | Validasi, ekstraksi NIP, perhitungan usia, otorisasi |
| `style.css`, `app.js` | Tampilan dan interaksi lokal |
| `.htaccess` | Alias `/admin` → folder `Admin`, larangan akses berkas internal |
| `router.php` | Router server pengembangan `php -S` |
| `kepegawaian_distapangtani.sql` | SQL lengkap untuk instalasi baru di database kosong |
| `migrasi.sql` | Perubahan satu kali untuk database asli yang telah diimpor |
| `tests.php`, `test_http.py` | Pengujian logika dan HTTP |
| `HASIL_PENGUJIAN.md`, `hasil_*.json` | Laporan hasil pengujian aktual |

## Instalasi cepat — Apache / XAMPP

1. Siapkan PHP **8.2 atau lebih baru** dengan ekstensi `pdo_mysql` dan `mbstring`, serta MariaDB 10.11 atau MySQL 8. Aplikasi diuji pada PHP 8.3.6 + MariaDB 10.11.14; MySQL 8 belum diuji langsung.
2. Ekstrak `Kepegawaian.zip`. Salin direktori `Kepegawaian` ke lokasi aplikasi.
3. Pilih **salah satu** jalur import berikut melalui phpMyAdmin atau CLI:
   - **Database kosong / instalasi baru:** impor `kepegawaian_distapangtani.sql`. Skrip membuat database jika belum ada, mengimpor seluruh **129 pegawai asli**, menambah kolom pendukung, dan membuat tabel `admin` beserta akun Eric.
   - **Database asli sudah diimpor:** backup database, kemudian impor **hanya** `migrasi.sql`. Jangan mengimpor SQL lengkap atau mengulang migrasi pada struktur yang sudah diperbarui.
4. Konfigurasi default pada `config.php` telah mengikuti permintaan:
   ```php
   $host = 'localhost';
   $user = 'root';
   $pass = 'Ericaja13.';
   $db   = 'kepegawaian_distapangtani';
   ```
   Nilai yang sama dipakai sebagai default di kode, dengan opsi override environment `DB_HOST`, `DB_USER`, `DB_PASS`, `DB_NAME`. Password database berbeda dari password login aplikasi.
5. Aktifkan Apache `mod_rewrite` dan `AllowOverride All` pada direktori aplikasi. Pastikan `.htaccess` ikut disalin. Pada produksi, atur **DocumentRoot langsung ke direktori Kepegawaian**.
6. Akses publik: `https://kepegawaiandistapangtanismr.fredericms.com/`. Akses privat: `https://kepegawaiandistapangtanismr.fredericms.com/admin`.
7. Login awal: username **Eric**, password **ericaja13**. Akun ini berperan sebagai **superadmin**. Gunakan tombol **Ganti password** setelah pemasangan. Password baru admin minimal 10 byte, maksimal 72 byte; password awal sesuai permintaan tetap dapat digunakan.

Aplikasi ini disediakan sebagai kode siap dipasang. Domain, DNS, sertifikat TLS, hosting, dan database produksi belum dikonfigurasi atau dipublikasikan dari paket ini.

### Contoh Apache VirtualHost

Sesuaikan jalur document root dengan server. Konfigurasi sertifikat TLS mengikuti sertifikat/domain pada server Anda.

```apache
<VirtualHost *:80>
    ServerName kepegawaiandistapangtanismr.fredericms.com
    DocumentRoot /var/www/Kepegawaian
    <Directory /var/www/Kepegawaian>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Aktifkan HTTPS pada VirtualHost TLS dan alihkan HTTP ke HTTPS sebelum akses produksi. Header HSTS aktif saat PHP menerima HTTPS. Bila TLS berakhir di reverse proxy/Cloudflare Tunnel, set **APP_HTTPS=1** di environment PHP untuk cookie Secure dan HSTS, serta pastikan origin hanya menerima trafik proxy yang dipercaya. Aplikasi tidak mempercayai `X-Forwarded-Proto`/`X-Forwarded-For` dari permintaan pengguna. Karena pembatasan login IP memakai `REMOTE_ADDR`, gunakan konfigurasi real-IP pada web server untuk proxy tepercaya; tanpa itu beberapa pengguna dapat berbagi batas IP yang sama.

### Lokal di subfolder XAMPP

Jika URL lokal adalah `http://localhost/Kepegawaian/`, set `APP_BASE_PATH=/Kepegawaian`. Bisa melalui environment server atau mengganti ekspresi default `BASE_PATH` di `config.php` menjadi `/Kepegawaian`. Login menjadi `http://localhost/Kepegawaian/admin`. Untuk domain produksi dengan DocumentRoot langsung ke Kepegawaian, biarkan `APP_BASE_PATH` kosong.

### Lokal dengan server bawaan PHP

Dari direktori `Kepegawaian`:

```sh
php -S 127.0.0.1:8080 router.php
```

Buka `http://127.0.0.1:8080/` dan `http://127.0.0.1:8080/admin`. `router.php` diperlukan supaya URL huruf kecil mengarah ke folder fisik **Admin** di Linux. Server bawaan PHP hanya digunakan untuk pengembangan.

### Alternatif Nginx + PHP-FPM

Nginx tidak membaca `.htaccess`. Gunakan allowlist endpoint seperti contoh berikut; sesuaikan versi/socket PHP dan konfigurasi TLS.

```nginx
server {
    listen 80;
    server_name kepegawaiandistapangtanismr.fredericms.com;
    root /var/www/Kepegawaian;
    index index.php;
    autoindex off;

    location = / { rewrite ^ /index.php last; }
    location = /admin { rewrite ^ /Admin/index.php last; }
    location = /admin/ { rewrite ^ /Admin/index.php last; }
    location ~ ^/admin/(index|login|logout|tambah-admin)\.php$ {
        rewrite ^/admin/(.*)$ /Admin/$1 last;
    }
    location ~ ^/(?:index\.php|Admin/(?:index|login|logout|tambah-admin)\.php)$ {
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        # Jika TLS berakhir di reverse proxy tepercaya:
        # fastcgi_param APP_HTTPS 1;
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
    }
    location ~ ^/(?:style\.css|app\.js)$ { try_files $uri =404; }
    location / { return 404; }
}
```

Untuk PHP-FPM, environment bisa disetel melalui `env[APP_HTTPS] = 1`, `env[DB_HOST]`, dan nilai lainnya pada pool FPM. Parameter FastCGI contoh di atas perlu diverifikasi sesuai cara PHP membaca environment pada konfigurasi server yang dipakai. Jangan menyajikan folder hanya sebagai static hosting: aplikasi memerlukan PHP dan MySQL/MariaDB.

## Perilaku halaman

**Beranda** tidak memiliki tautan, tombol, atau pintasan Admin. Publik memperoleh empat kartu statistik dan direktori nama, jabatan, serta status kepegawaian. Publik tidak memperoleh NIP, tanggal lahir lengkap, atau akun admin melalui HTML/JavaScript. Tidak ada API publik yang memutasi data. Publik dapat mencari, memfilter status, dan berpindah halaman (10 baris per halaman).

**Admin** terdiri dari satu dashboard tabel pegawai. Form tambah/edit pegawai, kelola admin, dan ganti password berupa dialog pada halaman yang sama. Ada tombol Edit dan Hapus per baris serta Tambah pegawai di bawah tabel. Hapus meminta konfirmasi. Seluruh mutasi memakai POST, validasi server, pemeriksaan sesi, dan token CSRF.

**Pintasan Tambah admin sistem** tersedia di bagian bawah login. Jika belum login, pintasan meminta login superadmin; setelah login, dialog manajemen admin terbuka. Pendaftaran admin anonim tidak diaktifkan agar siapa pun tidak dapat membuat akun pengelola. Admin baru selalu mendapat role `admin`, meskipun request dimodifikasi untuk mengirim role lain.

Superadmin bisa menambah, mengedit nama/username, mengatur ulang password, menonaktifkan, membuka kunci, dan menghapus admin. Username Eric serta status aktif akun superadmin utama dilindungi; akun ini tidak bisa dihapus melalui aplikasi. Nama tampil dan passwordnya tetap bisa diubah. Admin biasa hanya mengelola pegawai dan mengganti passwordnya sendiri.

## Interpretasi nomor dan usia

NIP/NIPPPK diperlakukan sebagai **string**, bukan angka atau bilangan JavaScript, untuk mempertahankan 18 digit secara utuh. Spasi saat input dihapus. Format tampilan: `20010513 202506 1 007`.

| Status | Nomor | Tanggal lahir dan gender | Mulai ASN | Usia awal ASN |
|---|---|---|---|---|
| PNS | NIP 18 digit wajib | Delapan digit awal + digit ke-15 (1 laki-laki, 2 perempuan) | Digit 9–14, YYYYMM | Dihitung dari kelahiran ke tanggal 1 bulan ASN |
| PPPK Penuh Waktu | NIPPPK 18 digit wajib | Delapan digit awal + digit ke-15 | **Diisi manual**, tidak membaca YYYYMM dari tengah NIPPPK | Dihitung setelah bulan manual diisi |
| PPPK Paruh Waktu | NIPPPK 18 digit wajib | Delapan digit awal + digit ke-15 | **Diisi manual** | Dihitung setelah bulan manual diisi |
| PJLP | Form disembunyikan; output `-` | Input manual opsional | `-` | `-` |

- Usia sekarang dihitung dinamis pada tanggal akses dengan zona waktu **Asia/Makassar (WITA)**.
- Usia awal ASN berupa **angka saja**, tanpa akhiran “tahun”. Karena sumber hanya bulan/tahun, perhitungan memakai tanggal **1** bulan tersebut. Ini bukan jaminan usia pada tanggal pengangkatan aktual jika SK memuat tanggal lain.
- NIP contoh `200105132025061007` menghasilkan: 13 Mei 2001, Juni 2025, laki-laki, dan usia awal ASN **24**. Pada 22 September 2026, usia sekarang **25**.
- Bagian tengah NIPPPK pada data asli banyak berisi `202521`; nilai `21` tidak dianggap bulan. **45 data PPPK** belum memiliki bulan mulai ASN dari berkas sumber. Awalnya tabel menampilkan “Belum diisi” dan usia awal `-`; edit masing-masing berdasarkan SK.
- Data awal: **84 PNS, 17 PPPK Penuh Waktu, 28 PPPK Paruh Waktu, 0 PJLP**.
- Data asli tidak dihapus atau dikarang ulang. Nomor dan nama asli dipertahankan. Kolom tanggal manual pada data impor awal dibiarkan `NULL`.
- Validasi server menolak tanggal kalender tidak valid, tanggal lahir/ASN di masa depan, ASN sebelum kelahiran, nomor bukan 18 digit, serta kode gender selain 1/2.
- Jabatan dapat diisi dan diedit. Isian kosong ditampilkan sebagai `-`.

## Perubahan database

Lima kolom asli (`no`, `nama`, `nip`, `jabatan`, `status_kepegawaian`) tetap digunakan. `no` menjadi AUTO_INCREMENT agar penambahan aman saat ada beberapa pengguna. Encoding tabel dinaikkan ke utf8mb4.

Tambahan pada `pegawai_distapangtani`:

| Kolom | Kegunaan |
|---|---|
| `mulai_asn_manual` | DATE opsional PPPK; selalu tanggal 1 sesuai input bulan |
| `tanggal_lahir_manual` | DATE opsional PJLP |
| `jenis_kelamin_manual` | Enum 1/2 opsional PJLP |
| `row_version` | Pencegah edit/hapus dengan versi lama |
| `nip_unik` | Generated column untuk indeks unik NIP/NIPPPK; NULL bagi PJLP agar beberapa PJLP sama-sama boleh bernomor `-` |

**Satu tabel baru `admin`** menyimpan hash password bcrypt, role, status aktif, versi sesi, waktu login, serta counter dan masa kunci login. Password plaintext tidak disimpan ke tabel. SQL instalasi dan migrasi masing-masing berisi hash siap dipakai untuk akun Eric. Tidak ada halaman setup publik.

DDL MySQL/MariaDB dapat melakukan implicit commit. Backup database sebelum migrasi. Jika migrasi gagal di tengah, jangan mengeksekusi ulang secara buta; pulihkan backup atau periksa kolom/tabel mana yang sudah terbentuk. SQL ini sengaja tidak memakai DROP TABLE untuk instalasi normal.

## Keamanan yang diterapkan

- PDO prepared statements native; input tidak disisipkan ke SQL.
- Semua teks HTML di-escape pada output. `sanitize()` dipertahankan sesuai konfigurasi, tetapi escaping output dan prepared statements menjadi pengamanan utama.
- `password_hash`/`password_verify` bcrypt cost 12; hash dummy dipakai untuk username tidak dikenal.
- Pesan login umum yang sama untuk username/password salah, akun nonaktif, serta akun/IP terkunci.
- Token CSRF seluruh request mutasi, termasuk login/logout; tidak ada hapus melalui GET.
- Session ID diregenerasi saat login dan ganti password. Cookie HttpOnly + SameSite=Lax, dan Secure saat HTTPS. Timeout idle 30 menit, durasi maksimal sesi 8 jam.
- Otorisasi diperiksa ulang dari database pada setiap request Admin. Menonaktifkan, menghapus, atau mengganti password admin mencabut sesi lama melalui `auth_version`.
- Akun terkunci 15 menit setelah 5 kegagalan; batas IP 30 percobaan/15 menit lintas sesi/username. State IP disimpan dengan file lock dan permission 0600 di temporary directory sistem, di luar dua direktori proyek.
- CSP dengan aset lokal, anti-clickjacking, nosniff, no-store, serta pesan koneksi database umum tanpa mengungkap password atau stack trace.
- Indeks unik mencegah nomor ASN/username ganda; pengecekan `row_version` menghindari perubahan lama menimpa data yang telah diedit admin lain.
- `.htaccess`/contoh Nginx/router menutup file SQL, konfigurasi internal, laporan dan tes dari akses HTTP.

Sebelum produksi: gunakan HTTPS, ganti password awal yang sudah diketahui, dan sebaiknya gunakan akun database aplikasi dengan izin SELECT/INSERT/UPDATE/DELETE alih-alih root. Import/migrasi dilakukan oleh akun administrator database. Berkas SQL dan tes dapat dipindahkan keluar document root setelah pemasangan. Aplikasi ini belum mencakup backup otomatis, audit aktivitas, MFA, atau workflow persetujuan publikasi data.

## Pengujian

Lihat `HASIL_PENGUJIAN.md` untuk hasil aktual dan batas pengujiannya.

Tes logika tanpa koneksi database:

```sh
php tests.php
```

Tes black-box HTTP **melakukan mutasi**. Gunakan database terpisah `kepegawaian_distapangtani_test`; jangan arahkan ke database kantor. Buat salinan SQL dan ganti semua referensi database `kepegawaian_distapangtani` dengan `kepegawaian_distapangtani_test` sebelum import. Contoh environment server pada Linux:

```sh
DB_NAME=kepegawaian_distapangtani_test php -S 127.0.0.1:8080 router.php
```

Pada terminal lain:

```sh
RUN_HTTP_TESTS=1 python3 test_http.py http://127.0.0.1:8080
```

Suite membutuhkan akun Eric dengan password awal, akan membuat data uji berawalan TEST, menghapusnya kembali bila semua langkah berhasil, dan menutup dengan tes rate limit IP. Untuk menjalankan ulang segera, gunakan instalasi/salinan aplikasi uji baru, atau tunggu 15 menit; jangan mengubah pembatasan keamanan pada aplikasi produksi. Jika pengujian gagal di tengah, pulihkan database uji dari SQL awal sebelum mengulang. `hasil_http.json` dapat ditimpa oleh tes.

Dokumentasi teknis rujukan: [PHP password_hash](https://www.php.net/password-hash), [PHP session_set_cookie_params](https://www.php.net/session-set-cookie-params), [PDO setAttribute](https://www.php.net/pdo.setattribute.php).
