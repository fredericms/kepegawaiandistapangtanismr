# Hasil pengujian Pendidikan Terakhir dan dashboard

**208 pemeriksaan lulus, 0 gagal.**

Lingkungan lokal terpisah: PHP 8.3.6, MariaDB 10.11.14, Chromium 153. Sintaks seluruh file PHP dan app.js lulus. Semua suite di tabel berikut dijalankan pada pembaruan ini; tes HTTP menggunakan database uji terpisah.

| Kelompok | Lulus | Gagal |
|---|---:|---:|
| Logika pegawai | 35 | 0 |
| Deteksi dan validasi pendidikan | 31 | 0 |
| SQL dan migrasi pendidikan | 5 | 0 |
| Regresi HTTP, login, CRUD, keamanan | 70 | 0 |
| HTTP pendidikan | 17 | 0 |
| Agregat dashboard | 16 | 0 |
| Browser dan responsivitas | 34 | 0 |

## Hasil utama

- SQL lengkap baru dan jalur migrasi dari versi sebelumnya menghasilkan 129 baris yang identik pada kolom data pegawai dan pendidikan.
- Hanya satu kolom database ditambahkan: pendidikan_terakhir. Login dan hash superadmin tetap sama.
- Deteksi SQL pada migrasi dan deteksi PHP aplikasi sama untuk semua 129 data sumber: 64 terisi otomatis, 65 NULL.
- S.P. + M.Si. memilih S2, termasuk urutan terbalik. Titik/spasi/kapitalisasi ditangani; Ph.D. dan A.Md. juga diuji.
- Seluruh tujuh pilihan manual diuji melalui HTTP. Koreksi manual bertahan saat nama berubah, mode otomatis dapat dipilih ulang, serta input palsu dan array multi-pilihan ditolak.
- Tidak ada kategori Belum diketahui, Di luar X/Y/Z, atau rekap gender per status pada halaman publik.
- Filter status, angka nol, persentase, diagram donat, dan responsivitas tetap lulus; tidak ada error JavaScript atau pelanggaran CSP.
- Nama/tanggal/NIP individual tetap tidak muncul pada halaman publik.

## Batas pengujian

Pengujian tidak dilakukan pada server/domain produksi. MySQL 8, Apache/Nginx produksi, TLS, stress test, dan audit penetrasi menyeluruh tidak dijalankan. Pengenalan gelar memakai whitelist, bukan pembacaan ijazah; singkatan di luar whitelist dan salah ketik perlu koreksi manual.

## Logika pegawai

- 35 pemeriksaan lulus.

## Deteksi dan validasi pendidikan

- LULUS — Budi, S.P
- LULUS — Budi, M.Si
- LULUS — Budi, S.P., M.Si
- LULUS — Budi, M.Si., S.P.
- LULUS — Budi S.P.
- LULUS — Budi, s. p., m. si.
- LULUS — Budi, S.Kom., M.Kom., Ph.D.
- LULUS — Budi, Ph.D., M.Si.
- LULUS — Budi, A.Md
- LULUS — Budi, A.Md.Kep.
- LULUS — Budi, A.Md., S.T.
- LULUS — Budi, S.Tr.Kep.
- LULUS — Budi, S.E., Ak.
- LULUS — Budi, M.M
- LULUS — Budi
- LULUS — dr. Budi
- LULUS — drh. Budi
- LULUS — Drs. Budi
- LULUS — Ir. Budi
- LULUS — Budi, GelarTidakDikenal
- LULUS — S.P. Budi
- LULUS — Budi, <script>alert(1)</script>
- LULUS — Manual: SD/sederajat
- LULUS — Manual: SMP/sederajat
- LULUS — Manual: SMA/sederajat
- LULUS — Manual: Diploma I - Diploma III
- LULUS — Manual: Diploma IV/Sarjana (S1)
- LULUS — Manual: Magister (S2)
- LULUS — Manual: Doktor (S3)
- LULUS — Nilai pendidikan palsu ditolak
- LULUS — Mode otomatis memilih tertinggi

## SQL dan migrasi pendidikan

- LULUS — SQL lengkap dan migrasi menghasilkan 129 baris identik
- LULUS — Deteksi SQL dan PHP konsisten pada seluruh data asli
- LULUS — Hanya satu kolom pendidikan ditambahkan
- LULUS — Migrasi memilih tertinggi, menangani tanpa gelar, menjaga koreksi manual
- LULUS — Akun awal tidak berubah

## Regresi HTTP, login, CRUD, keamanan

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
- LULUS — Identitas dan stored XSS tidak dikirim ke publik
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

## HTTP pendidikan

- LULUS — Login pengelola untuk pendidikan
- LULUS — Tambah pendidikan otomatis berhasil
- LULUS — Pendidikan S2 disimpan dan tampil
- LULUS — Koreksi manual berhasil
- LULUS — Manual mengalahkan gelar S2
- LULUS — Nama berubah tanpa menimpa pilihan manual
- LULUS — Mode otomatis dapat diterapkan ulang
- LULUS — Nilai pendidikan palsu ditolak
- LULUS — Pilihan multiple array ditolak
- LULUS — Simpan pilihan SD/sederajat
- LULUS — Simpan pilihan SMP/sederajat
- LULUS — Simpan pilihan SMA/sederajat
- LULUS — Simpan pilihan Diploma I - Diploma III
- LULUS — Simpan pilihan Diploma IV/Sarjana (S1)
- LULUS — Simpan pilihan Magister (S2)
- LULUS — Simpan pilihan Doktor (S3)
- LULUS — Tanpa gelar tidak dianggap pendidikan tertentu

## Agregat dashboard

- LULUS — Kategori tersembunyi dan rekap tidak dirender
- LULUS — HTML publik tidak memuat direktori pegawai
- LULUS — Identitas individual tidak dikirim ke publik
- LULUS — Payload hanya memuat jumlah agregat
- LULUS — Total generasi dan gender konsisten: all
- LULUS — Total generasi dan gender konsisten: PNS
- LULUS — Total generasi dan gender konsisten: PPPK Penuh Waktu
- LULUS — Total generasi dan gender konsisten: PPPK Paruh Waktu
- LULUS — Total generasi dan gender konsisten: PJLP
- LULUS — Batas generasi, PJLP manual, NIPPPK dan data kosong: all
- LULUS — Batas generasi, PJLP manual, NIPPPK dan data kosong: PNS
- LULUS — Batas generasi, PJLP manual, NIPPPK dan data kosong: PPPK Penuh Waktu
- LULUS — Batas generasi, PJLP manual, NIPPPK dan data kosong: PPPK Paruh Waktu
- LULUS — Batas generasi, PJLP manual, NIPPPK dan data kosong: PJLP
- LULUS — Identitas fixture tidak muncul pada HTML
- LULUS — Data uji dibersihkan dan agregat dipulihkan

## Browser dan responsivitas

- LULUS — Direktori individual tidak tampil
- LULUS — Nama dan NIP tidak ada di HTML publik
- LULUS — Total data sumber 129 pegawai
- LULUS — Filter dashboard all
- LULUS — Filter dashboard PNS
- LULUS — Filter dashboard PPPK Penuh Waktu
- LULUS — Filter dashboard PPPK Paruh Waktu
- LULUS — Filter dashboard PJLP
- LULUS — PJLP kosong menampilkan empty state
- LULUS — Persentase status kosong bernilai 0%
- LULUS — Persentase memakai denominator status terpilih
- LULUS — Rekap gender per status dihapus
- LULUS — Grafik hanya memuat X Y Z dan dua gender
- LULUS — CSS Beranda memiliki fingerprint versi
- LULUS — JavaScript Beranda memiliki fingerprint versi
- LULUS — Grafik berdampingan pada desktop
- LULUS — Filter memiliki padding yang termuat
- LULUS — Proporsi diagram donat sesuai agregat
- LULUS — Diagram donat kosong tidak menghasilkan NaN
- LULUS — URL /admin huruf kecil membuka login
- LULUS — Dialog tambah pegawai tampil
- LULUS — PJLP menyembunyikan serta menonaktifkan kolom NIP
- LULUS — PJLP menyediakan tanggal lahir manual
- LULUS — PPPK menampilkan NIPPPK dan bulan mulai ASN
- LULUS — Pratinjau NIP menampilkan tanggal lahir
- LULUS — Preview gelar memilih S2
- LULUS — Pilihan manual bertahan saat nama berubah
- LULUS — Dropdown tujuh jenjang ditambah mode otomatis
- LULUS — Manajemen admin tetap dalam halaman Admin
- LULUS — Grafik satu kolom pada mobile
- LULUS — Beranda mobile tidak overflow horizontal
- LULUS — Admin mobile tidak overflow horizontal
- LULUS — Dialog mobile muat dalam viewport
- LULUS — Tidak ada error JavaScript atau pelanggaran CSP

## Menjalankan tes

Logika tanpa database: `php tests.php` dan `php tests_pendidikan.php`.

HTTP pendidikan: pada server localhost yang memakai database uji, jalankan `RUN_HTTP_TESTS=1 python3 test_pendidikan_http.py http://127.0.0.1:8080`. Skrip melakukan mutasi fixture TEST lalu menghapusnya. Jangan arahkan ke database produksi.

Tes agregat: `DB_NAME=kepegawaian_distapangtani_test RUN_DASHBOARD_TESTS=1 php tests_dashboard.php` dengan environment koneksi yang sama dengan server lokal. Browser: `RUN_BROWSER_TESTS=1 node test_browser.cjs` dengan Playwright dan Chromium yang tersedia.
