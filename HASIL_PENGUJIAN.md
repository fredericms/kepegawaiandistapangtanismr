# Hasil Pengujian Kepegawaian Distapangtani — Versi Awal

Catatan: laporan ini menyimpan hasil versi awal sebelum direktori publik diganti dashboard. Hasil pembaruan terkini ada di **HASIL_DASHBOARD.md**; bagian tes direktori publik dalam laporan historis ini sudah tidak berlaku.

Tanggal verifikasi: 22 September 2026. Pengujian memakai salinan database lokal terpisah; tidak menggunakan database produksi.

**Hasil akhir: 124 pemeriksaan lulus, 0 gagal.** Selain itu, lint seluruh 9 file PHP dan pemeriksaan sintaks JavaScript lulus.

| Kelompok | Lulus | Gagal | Lingkungan |
|---|---:|---:|---|
| White-box logika/validasi | 35 | 0 | PHP CLI 8.3.6 |
| Black-box HTTP dan kontrol akses | 70 | 0 | PHP HTTP server + PDO + MariaDB 10.11.14 |
| Browser dan responsivitas | 16 | 0 | Chromium 153 headless + Playwright; 1440×1100 dan 390×844 |
| Database / migrasi | 3 | 0 | SQL lengkap dan migrasi SQL sumber di database terpisah |

## Temuan dan perbaikan

- Mempertahankan ID pegawai saat formulir edit mengalami validasi gagal, sehingga penyimpanan ulang tetap mengedit pegawai yang sama.
- Mengoreksi lebar kartu statistik dan navigasi Admin pada ponsel. Setelah perbaikan, Beranda/Admin/dialog tidak melampaui viewport; tabel tetap bisa digeser di dalam kontainernya.
- Menetapkan bulan pengangkatan PPPK sebagai data manual. 45 data awal masih kosong dan tidak dianggap bulan 21.
- Migrasi dan instalasi penuh menghasilkan 129 baris dengan kelima kolom asli identik secara byte: no, nama, nip, jabatan, status_kepegawaian.
- Pembatasan akses dan validasi diuji melalui request langsung, termasuk parameter role yang dimanipulasi.

## Rincian pemeriksaan

### Logika

- LULUS — NIP PNS: tanggal lahir 13 Mei 2001
- LULUS — NIP PNS: usia saat 22 September 2026 = 25
- LULUS — NIP PNS: mulai ASN Juni 2025
- LULUS — NIP PNS: usia awal ASN angka 24
- LULUS — NIP PNS: gender 1 laki-laki
- LULUS — Gender 2 perempuan
- LULUS — Spasi NIP dinormalisasi
- LULUS — NIP dipertahankan sebagai string
- LULUS — NIP 17 digit ditolak
- LULUS — NIP karakter bukan angka ditolak
- LULUS — Gender di luar 1/2 ditolak
- LULUS — Tanggal lahir 31 Februari ditolak
- LULUS — Bulan pengangkatan PNS 21 ditolak
- LULUS — Pengangkatan sebelum kelahiran ditolak
- LULUS — Pengangkatan masa depan ditolak
- LULUS — PPPK menerima tengah NIPPPK 202521 dan bulan manual
- LULUS — PPPK penuh waktu menerima bulan manual
- LULUS — PPPK mulai ASN tidak diekstrak dari 202521
- LULUS — PPPK tanggal manual tersimpan dihitung
- LULUS — PPPK tanpa bulan manual ditolak
- LULUS — Bulan manual 13 ditolak
- LULUS — PJLP tidak menggunakan NIP
- LULUS — PJLP boleh tanpa tanggal lahir
- LULUS — PJLP tanggal lahir manual
- LULUS — PJLP mulai ASN selalu kosong
- LULUS — Nama kosong ditolak
- LULUS — Status palsu ditolak
- LULUS — Nama >150 karakter ditolak
- LULUS — SQLi pada NIP ditolak
- LULUS — Escaping XSS saat output
- LULUS — Tahun kabisat diterima
- LULUS — Bukan tahun kabisat ditolak
- LULUS — Usia berubah tepat saat ulang tahun
- LULUS — Usia awal ASN memakai tanggal 1 bulan pengangkatan
- LULUS — Data warisan invalid tidak merusak tabel

### HTTP

- LULUS — Beranda publik dapat diakses
- LULUS — Beranda tidak memuat pintasan Admin
- LULUS — NIP dan tanggal lahir lengkap tidak bocor di HTML publik
- LULUS — Publik tidak menerima mutasi
- LULUS — Admin tanpa login diarahkan ke login
- LULUS — CRUD tanpa autentikasi ditolak
- LULUS — Pintasan tambah admin membutuhkan login
- LULUS — Berkas internal tidak tersedia: /config.php
- LULUS — Berkas internal tidak tersedia: /functions.php
- LULUS — Berkas internal tidak tersedia: /kepegawaian_distapangtani.sql
- LULUS — Berkas internal tidak tersedia: /migrasi.sql
- LULUS — Berkas internal tidak tersedia: /tests.php
- LULUS — Berkas internal tidak tersedia: /test_http.py
- LULUS — Header CSP, anti-frame dan nosniff tersedia
- LULUS — Cookie HttpOnly dan SameSite=Lax
- LULUS — Login tanpa CSRF ditolak
- LULUS — Pesan generik sama untuk username/password salah
- LULUS — SQL injection login gagal
- LULUS — Login superadmin bawaan berhasil
- LULUS — Session ID berubah setelah login
- LULUS — Tabel privat mengekstrak NIP contoh
- LULUS — NIPPPK 202521 menunggu input manual
- LULUS — CRUD tanpa CSRF ditolak
- LULUS — Tambah PNS berhasil
- LULUS — NIP duplikat ditolak
- LULUS — NIP 17 digit ditolak server
- LULUS — Tanggal NIP tidak valid ditolak
- LULUS — PPPK tanpa bulan ditolak dan ID edit dipertahankan
- LULUS — Ubah PNS ke PPPK dengan bulan manual
- LULUS — Usia/tanggal pengangkatan PPPK tampil benar
- LULUS — Edit dari versi lama ditolak
- LULUS — Hapus dari versi lama ditolak
- LULUS — Hapus pegawai berhasil
- LULUS — Tambah TEST PJLP A tanpa NIP
- LULUS — Tambah TEST PJLP B tanpa NIP
- LULUS — PJLP menampilkan tanda strip di nomor
- LULUS — Nama tersimpan aman sebagai teks
- LULUS — Stored XSS di Admin di-escape
- LULUS — Stored XSS di Publik di-escape
- LULUS — Superadmin dapat menambahkan admin
- LULUS — Username duplikat ditolak
- LULUS — Admin biasa dapat login
- LULUS — Admin biasa tidak mendapat kontrol kelola admin
- LULUS — Hak superadmin dari parameter role diabaikan
- LULUS — Admin biasa tidak dapat membuat admin
- LULUS — Admin biasa dapat CRUD pegawai
- LULUS — Admin dinonaktifkan
- LULUS — Sesi admin nonaktif langsung dicabut
- LULUS — Admin nonaktif tidak dapat login
- LULUS — Admin aktif kembali dapat login
- LULUS — Ganti password dengan password lama salah ditolak
- LULUS — Ganti password sendiri berhasil
- LULUS — Ganti password mencabut sesi lain
- LULUS — Sesi pengubah password tetap berlaku
- LULUS — Superadmin terakhir tidak dapat dihapus
- LULUS — Superadmin tidak dapat dinonaktifkan
- LULUS — Logout lewat GET ditolak
- LULUS — Logout POST berhasil
- LULUS — Halaman Admin tidak tersedia setelah logout
- LULUS — Gagal login berurutan 1
- LULUS — Gagal login berurutan 2
- LULUS — Gagal login berurutan 3
- LULUS — Gagal login berurutan 4
- LULUS — Gagal login berurutan 5
- LULUS — Password benar ditolak selama akun terkunci
- LULUS — Superadmin dapat membuka kunci akun
- LULUS — Akun dapat login setelah dibuka
- LULUS — Superadmin dapat menghapus admin
- LULUS — Sesi akun terhapus dicabut
- LULUS — Batas IP berlaku lintas sesi dan username

### Browser

- LULUS — Tabel publik menampilkan 10 baris per halaman
- LULUS — Pencarian nama interaktif
- LULUS — Filter status menampilkan 28 pegawai
- LULUS — Navigasi halaman berikutnya
- LULUS — Empty state terlihat
- LULUS — URL /admin huruf kecil membuka login
- LULUS — Dialog tambah pegawai tampil
- LULUS — PJLP menyembunyikan serta menonaktifkan kolom NIP
- LULUS — PJLP menyediakan tanggal lahir manual
- LULUS — PPPK menampilkan NIPPPK dan bulan mulai ASN
- LULUS — Pratinjau NIP menampilkan tanggal lahir
- LULUS — Manajemen admin tetap dalam halaman Admin
- LULUS — Beranda mobile tidak overflow horizontal
- LULUS — Admin mobile tidak overflow horizontal
- LULUS — Dialog mobile muat dalam viewport
- LULUS — Tidak ada error JavaScript atau pelanggaran CSP

### Migrasi

- LULUS — Import SQL lengkap berhasil
- LULUS — Migrasi di atas SQL asli berhasil
- LULUS — 129 baris cocok pada seluruh lima kolom asli

## Batas hasil dan verifikasi saat pemasangan

- Hasil ini bukan audit penetrasi menyeluruh dan bukan jaminan bebas seluruh kerentanan.
- Routing `/admin` diuji melalui router PHP lokal. Konfigurasi Apache `.htaccess` dan contoh Nginx disediakan, tetapi belum dijalankan pada hosting/domain tujuan. Periksa rewrite, permission, dan versi PHP-FPM saat pemasangan.
- Flag HttpOnly/SameSite, rotasi sesi, pencabutan sesi, CSRF, XSS, SQL injection, dan rate limit diuji. Cookie Secure/HSTS pada TLS produksi serta timeout 30 menit/8 jam ditinjau pada kode, belum diuji dengan koneksi TLS atau penantian durasi penuh.
- Uji browser memakai Chromium; Safari/Firefox dan pengujian aksesibilitas menyeluruh belum dijalankan.
- Tidak menjalankan load test, uji konkurensi multi-host, MFA, audit log, backup/restore produksi, atau konfigurasi DNS/sertifikat.
- Data ASN diimpor sebagaimana berkas sumber; kebenaran administratifnya dan bulan pengangkatan PPPK harus diverifikasi oleh pengelola.
- Aset UI lokal, pencarian, pagination, dialog dinamis, dan halaman mobile diuji tanpa error JavaScript atau pelanggaran CSP.

## Menjalankan tes browser kembali

Dari direktori aplikasi uji, gunakan Node.js dan Playwright yang dipasang di lingkungan pengujian tersendiri. Server harus berada di `http://127.0.0.1:8080` dan menggunakan database uji dengan akun Eric bawaan.

```sh
RUN_BROWSER_TESTS=1 node test_browser.cjs
```

Sediakan modul Playwright melalui lingkungan Node (misalnya NODE_PATH) dan browser Chromium. Jika memakai executable sendiri, set `CHROMIUM_PATH`. Suite menyimpan tangkapan layar pada direktori kerja; jangan mengunggah tangkapan layar Admin ke lokasi publik.
