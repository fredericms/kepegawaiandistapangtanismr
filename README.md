# Kepegawaian Distapangtani Kota Samarinda

## Pembaruan teks Beranda

Ganti **index.php**, **functions.php**, dan **style.css** dari paket ini, lalu muat ulang Beranda. Lima teks pada navbar publik, judul/subjudul hero, dan judul komposisi status telah diperbarui sesuai permintaan. Nama lengkap dinas dapat membungkus pada layar kecil. Tidak diperlukan migrasi database. Sintaks PHP lolos pemeriksaan; bagian navbar dan hero diperiksa pada lebar 1440, 390, dan 320 piksel tanpa tumpang tindih navigasi atau luapan horizontal.

## Pembaruan legenda gender dan favicon

Ganti **functions.php**, **app.js**, dan **style.css** secara bersamaan dari paket terbaru, lalu muat ulang Beranda dengan Ctrl+F5. Tidak ada perubahan database atau fitur lainnya. Pertahankan lambang-kota-samarinda.jpg sebagai cadangan. Apabila versi lama masih tampil, periksa apakah ketiga file ditimpa di folder yang benar-benar dilayani domain; paket unduhan ini tidak otomatis mengubah hosting.

Inisialisasi penyembunyian legenda dilakukan lebih awal, terpisah dari perhitungan grafik. Saat filter male/female dipilih, kedua baris legenda laki-laki/perempuan di bawah diagram lingkaran disembunyikan memakai atribut hidden dan aturan CSS khusus. Saat kembali ke semua jenis kelamin, keduanya tampil lagi. Jumlah tengah lingkaran, tulisan pegawai, serta catatan cakupan tetap terlihat. Status ini diselaraskan kembali pada pageshow, termasuk saat kembali melalui navigasi browser.

Logo navbar publik dan favicon pada tab browser memakai URL yang sama persis:
https://upload.wikimedia.org/wikipedia/commons/3/3b/Lambang_Kota_Samarinda.jpg?utm_source=commons.wikimedia.org&utm_campaign=index&utm_content=original

Favicon dipasang melalui link rel=icon dengan tipe image/jpeg pada head. Jika gambar eksternal gagal, JavaScript memindahkan logo dan favicon ke cadangan lokal satu kali. Sumber/atribusi Commons tetap pada tautan logo. Ukuran navbar tetap sama. Favicon berlaku pada halaman sistem yang menggunakan page_top; navbar Admin lainnya tidak diubah. Izin img-src Wikimedia pada konfigurasi paket tetap diperlukan untuk embed. Cache favicon browser dapat memerlukan penutupan dan pembukaan kembali tab setelah file baru diunggah.

Pengujian: 14 pemeriksaan browser untuk filter/render tetap lulus. Dua skenario tambahan menggunakan HTML page_top asli juga lulus: logo dan favicon sama-sama menunjuk URL persis di atas ketika respons gambar berhasil disimulasikan; keduanya berpindah ke gambar lokal ketika jaringan eksternal diblokir. Ini memverifikasi markup dan mekanisme cadangan, bukan jaminan ketersediaan Wikimedia. Sintaks PHP dan JavaScript diperiksa. Laporan sebelumnya bersifat historis.

## Pembaruan pendidikan, legenda gender, embed logo, dan rincian fungsional

Ganti **index.php**, **functions.php**, dan **app.js** secara bersamaan dari paket ini. Pertahankan **lambang-kota-samarinda.jpg** di sebelah index.php sebagai gambar cadangan. Tidak ada migrasi database atau perubahan Admin. Muat ulang Beranda sesudah penggantian; JavaScript memakai fingerprint versi.

- Pendidikan: hanya kategori dengan jumlah positif yang tampil, baik pada HTML awal dari PHP maupun sesudah filter JavaScript. Kategori yang terisi setelah pembaruan data akan tampil saat Beranda dimuat ulang.
- Diagram lingkaran: daftar/legenda laki-laki dan perempuan di bawah lingkaran tersembunyi ketika salah satu jenis kelamin dipilih. Saat filter kembali ke Semua jenis kelamin, legenda tampil lagi. Jumlah di tengah lingkaran, label pegawai, dan cakupan data tetap terlihat.
- Logo: embed memakai berkas gambar resmi `https://upload.wikimedia.org/wikipedia/commons/3/3b/Lambang_Kota_Samarinda.jpg`, dengan tautan sumber Commons/atribusi pada navbar. Jika gambar gagal dimuat, app.js beralih satu kali ke gambar lokal. Ukuran tetap 44 × 48 px desktop dan 34 × 39 px mobile. CSP versi paket sudah mengizinkan domain tersebut; jangan menghapus file cadangan lokal. Pengguna tanpa JavaScript tetap memperoleh embed, tetapi mekanisme cadangan memerlukan JavaScript.
- Judul rincian menjadi **Rincian Kelompok Jabatan Fungsional**. Pelaksana tidak masuk rincian; hanya ditampilkan dalam diagram komposisi utama. Persentase rincian menggunakan jumlah pegawai fungsional saja sesuai filter. Label jenjang lainnya tetap.

Validasi: **16 pemeriksaan khusus lulus** (14 browser, 2 PHP) dengan fixture terkontrol: legenda kedua jenis kelamin dan pemulihannya, total lingkaran, pendidikan nol serta kemunculan setelah data terisi, penghapusan pelaksana dari rincian, penyebut persentase, pengelompokan PHP, dan simulasi embed berhasil/gagal. Logo cadangan benar-benar dimuat dari server lokal ketika jaringan eksternal diblokir. Jalur embed berhasil diuji memakai respons gambar terkontrol, bukan klaim ketersediaan jaringan Wikimedia saat ini. Sintaks PHP/JavaScript lulus. Laporan versi sebelumnya tetap merupakan catatan historis.

## Pembaruan penyaringan PHP dan logo lokal

**Paket terbaru:** penyaringan rincian fungsional/pelaksana sekarang dilakukan di PHP melalui `public_job_details()`, disertakan dalam agregat setiap status/gender, dan dirender di HTML awal. JavaScript menggunakan hasil penyaringan PHP ketika filter berubah. Rincian tidak memuat kepala dinas, sekretaris, kepala bidang, kepala subbagian, PLT, maupun kepala UPTD. Komposisi jabatan utama tetap mencakup struktural.

Semua label baru dipertahankan, termasuk Terampil ditampilkan sebagai **Jabatan Fungsional Keterampilan tingkat Pertama** sesuai permintaan pengguna (data jabatan asli tidak diganti). Kategori bernilai nol/kosong pada diagram pendidikan serta komposisi/rincian jabatan tidak dibuat sebagai baris grafik. Kategori akan muncul kembali setelah datanya terisi dan Beranda dimuat ulang. Ringkasan statistik dan kartu jumlah pegawai tetap seperti sebelumnya. Persentase rincian dihitung hanya dari pegawai fungsional dan pelaksana.

### File yang harus diterapkan bersama

1. Backup aplikasi, lalu timpa **index.php**, **functions.php**, dan **app.js** dari paket ini pada folder aplikasi yang dilayani domain.
2. Salin **lambang-kota-samarinda.jpg** ke folder yang SAMA dengan index.php, bukan ke folder Admin. Logo dimuat dari URL lokal; tidak perlu menghubungi Wikimedia saat halaman dibuka.
3. Jika memakai server pengembangan `php -S`, ganti juga **router.php**, karena router harus mengizinkan gambar lokal. Untuk Nginx dengan daftar aset terbatas, tambahkan `lambang-kota-samarinda.jpg` pada aturan aset seperti contoh konfigurasi di bawah. Apache dengan .htaccess bawaan sudah dapat melayaninya.
4. Muat ulang Beranda dengan Ctrl+F5. Buka Dashboard Demografi → Jabatan. Rincian hanya berisi fungsional/pelaksana dengan label panjang. Jangan mengimpor ulang database. File PHP dan app.js harus berasal dari paket yang sama; fingerprint CSS/JS diperbarui otomatis.

Tidak ada perubahan data pegawai, struktur database, atau halaman Admin. Logo masih berukuran 44 × 48 px desktop dan 34 × 39 px mobile dengan object-fit: contain. Logo D pada halaman privat tetap seperti sebelumnya karena lingkup permintaan adalah Beranda publik.

### Sumber gambar

Berkas asli `Lambang_Kota_Samarinda.jpg` (583 × 599 piksel), Government of Samarinda City, CC BY-SA 3.0, melalui Wikimedia Commons:
- https://commons.wikimedia.org/wiki/File:Lambang_Kota_Samarinda.jpg
- https://upload.wikimedia.org/wikipedia/commons/3/3b/Lambang_Kota_Samarinda.jpg
- https://creativecommons.org/licenses/by-sa/3.0/

Gambar diimpor tanpa modifikasi piksel; hanya ukuran tampilannya disesuaikan melalui CSS. Tautan sumber dan atribusi tetap ada pada logo navbar. Catatan kegagalan embed di versi terdahulu tidak berlaku untuk gambar lokal ini.

### Validasi pembaruan ini

**22 pemeriksaan khusus lulus:** 4 pemeriksaan PHP untuk penyaringan/HTML awal, 13 pemeriksaan render/filter browser, dan 5 pemeriksaan HTML awal/logo lokal desktop/mobile dengan jaringan eksternal diblokir. Mencakup pengecualian struktural, label baru, denominator persentase, kategori nol, kemunculan setelah perubahan data, penggunaan payload PHP, pemuatan gambar lokal, dan lebar layar mobile. Sintaks PHP dan JavaScript juga diperiksa. Pengujian memakai fixture terkontrol, bukan database produksi. Laporan dan pratinjau umum dari versi terdahulu merupakan catatan historis.

## Pembaruan rincian kelompok jabatan

Untuk versi sebelumnya, ganti hanya **functions.php** dan **app.js**. Tidak ada perubahan database, Admin, atau konfigurasi. Muat ulang Beranda setelah penggantian (CSS/JS memakai fingerprint).

Rincian kelompok jabatan kini hanya mencakup fungsional dan pelaksana. Kepala dinas, sekretaris, kepala bidang, kepala subbagian, PLT, dan kepala UPTD tetap dihitung dalam diagram komposisi utama, tetapi tidak muncul dalam rincian. Persentase rincian memakai total fungsional + pelaksana sesuai filter, sementara diagram utama memakai total seluruh jabatan terisi.

Label keahlian: Jabatan Fungsional Keahlian tingkat Pertama/Muda/Madya/Utama. Label keterampilan: Jabatan Fungsional Keterampilan tingkat Pertama/Mahir/Penyelia/Pemula. Sesuai permintaan pengguna, kategori sumber Terampil ditampilkan sebagai tingkat Pertama; ini hanya perubahan label antarmuka, bukan penggantian jenjang pada database. Label tanpa jenjang: Jabatan Fungsional (jenjang belum tercatat).

Kategori pada rincian dengan jumlah nol/kosong disembunyikan. Setelah data diperbarui melalui Admin dan Beranda dimuat ulang, kategori yang memiliki jumlah positif muncul kembali. Filter status dan jenis kelamin juga memperbarui kategori yang terlihat. Jika seluruh rincian kosong, tampil pesan kosong tanpa batang nol.

Pengujian khusus perubahan ini: **11 pemeriksaan browser lulus** menggunakan fixture agregat dan kode render app.js aktual: pengecualian struktural dari rincian, total struktural tetap pada diagram utama, kategori positif saja, ketiga label baru, penyebut persentase rincian, filter gender, keadaan hanya struktural, kemunculan setelah perubahan data, dan label panjang pada mobile. Sintaks PHP/JS lulus. Laporan lainnya adalah pengujian versi terdahulu; tidak dijalankan ulang pada pembaruan terbatas ini.

## Pembaruan 23 September 2026 — Dashboard Demografi

Tab **Statistik umum**, **Pendidikan**, dan **Jabatan** sekarang berada DI DALAM bagian Dashboard Demografi, di bawah judul Profil pegawai dalam angka. Statistik umum mempertahankan diagram generasi dan jenis kelamin serta grafik usia awal ASN. Grafik usia awal ASN dirender langsung oleh PHP untuk tampilan awal, lalu diperbarui oleh JavaScript saat filter berubah. Filter status dan jenis kelamin tetap berlaku untuk semua grafik.

Teks jenis kelamin menjadi **Komposisi pegawai berdasarkan jenis kelamin**; label total menjadi **pegawai**. Lambang Kota Samarinda menggunakan embed Wikimedia yang diberikan pengguna, tautan atribusi tetap disertakan. Logo hanya diganti pada Beranda publik: ukurannya 44 × 48 px desktop dan 34 × 39 px mobile, dengan rasio gambar tetap terjaga. Logo pada Admin/login tidak diubah.

### Memasang pembaruan pada aplikasi yang sudah ada

1. Backup file aplikasi, lalu timpa **index.php**, **functions.php**, **app.js**, dan **style.css** dari folder Kepegawaian pada ZIP ini ke folder aplikasi yang benar-benar dilayani domain publik. Jangan menyalin folder Kepegawaian menjadi subfolder kedua secara tidak sengaja.
2. Pada **config.php**, pertahankan pengaturan database/server Anda. Ubah hanya bagian `img-src` pada header Content-Security-Policy menjadi `img-src 'self' data: https://thumb.wikimedia.org https://upload.wikimedia.org;` seperti versi paket. Izin script/style dan aturan lain tetap sama. Ini diperlukan agar embed gambar tidak diblokir CSP.
3. Tidak ada impor SQL atau migrasi database untuk pembaruan ini. Jangan mengimpor SQL lengkap ke database yang sedang dipakai.
4. Muat ulang Beranda dengan Ctrl+F5. Cari judul **DASHBOARD DEMOGRAFI** dan tiga tab tepat di dalam kotaknya. Paket memakai fingerprint CSS/JS untuk memperbarui cache. Jika masih melihat versi lama, periksa apakah domain memakai folder index.php yang baru; jika server mempertahankan OPcache, lakukan reload PHP melalui panel hosting.

Logo adalah gambar eksternal dan memerlukan akses browser ke Wikimedia. Dalam lingkungan pengujian ini request gambarnya menerima HTTP 403; markup, tautan, ukuran, serta izin CSP diverifikasi, tetapi pemuatan gambar aktual belum berhasil diverifikasi (`external_logo_loaded: false` pada laporan). Hal ini tidak menghambat grafik dan tab.

**132 pemeriksaan aplikasi lulus:** 60 hitungan/integrasi database dan 72 browser. Cakupan tambahan: tab berada di dalam Dashboard Demografi, kedua teks baru, ukuran logo desktop/mobile, izin CSP, dan grafik usia yang sudah tersedia dalam HTML awal. Pemeriksaan gambar eksternal dilaporkan terpisah dan tidak dinyatakan lulus. Laporan terkini ada pada hasil_dashboard.json dan hasil_browser.json; pratinjau-beranda.png menunjukkan tampilan uji termasuk keterbatasan pemuatan logo tersebut.

## Pembaruan analitik publik: tiga tab

Untuk memperbarui versi sebelumnya yang sudah memiliki fitur pendidikan dan gelar Ir./drh., backup aplikasi lalu ganti **index.php**, **functions.php**, **app.js**, dan **style.css**. Tidak ada migrasi atau impor database untuk pembaruan ini. Semua tab tetap pada Beranda yang sama; tidak ada halaman, endpoint, atau folder aplikasi baru. Admin dan SQL tetap sama.

- **Statistik umum:** demografi sebelumnya ditambah usia awal ASN (minimum, mean, median, maksimum). Desktop memakai batang horizontal; layar sampai 760 px memakai kolom vertikal. Tabel pembanding seluruh status mengikuti filter jenis kelamin.
- **Pendidikan:** delapan pilihan pendidikan terakhir yang tersimpan, tanpa menebak ulang dari nama. Diagram mendukung jumlah/persentase.
- **Jabatan:** kelompok struktural, fungsional, dan pelaksana serta rincian jenjang. Ini jabatan saat ini, bukan riwayat perpindahan jabatan karena database tidak menyimpan riwayat tersebut.

Filter status dan jenis kelamin berlaku bersama di ketiga tab. Kartu komposisi pegawai di atas filter tetap menjadi ringkasan seluruh organisasi. Navigasi tab mendukung klik, panah kiri/kanan, Home, dan End. JavaScript diperlukan untuk tab serta grafik tambahan; ringkasan demografi keseluruhan tetap tersedia tanpa JavaScript.

### Aturan perhitungan

Usia awal ASN menggunakan `employee_info()` yang sama dengan Admin: tahun penuh pada awal bulan pengangkatan. PNS memakai bulan/tahun NIP; PPPK memakai `mulai_asn_manual`. Tanggal tidak valid/kosong, tanggal di masa depan, atau pengangkatan sebelum lahir tidak dihitung. PJLP tidak termasuk ASN. Mean dihitung dari semua usia valid (bukan rata-rata antarstatus); median diurutkan dan mengambil rata-rata dua nilai tengah untuk jumlah genap. Nilai kosong ditampilkan sebagai tanda kosong/pesan, bukan angka nol. Nilai statistik dibulatkan saat ditampilkan, maksimum satu desimal.

Pendidikan menggunakan pilihan tersimpan termasuk koreksi manual. Nilai pendidikan kosong tidak masuk penyebut persentase. Data tanpa jenis kelamin tetap masuk Keseluruhan tetapi tidak masuk filter laki-laki/perempuan. Setiap grafik mencantumkan cakupan datanya.

PLT dihitung sekali sebagai struktural. Kepala UPTD/unit juga struktural. Fungsional dikenali berdasarkan jenjang dan rumpun yang ada pada data; bentuk tanpa kata Ahli seperti "Penyuluh Pertanian Muda" tetap dikenali. Terampil, Mahir, dan jenjang yang belum tercatat dipisahkan. Jabatan nonkosong di luar aturan tersebut masuk pelaksana. Aturan teks ada di `public_job_group()` dan dapat diperluas saat rumpun jabatan baru muncul; ini bukan penetapan jabatan administratif. Data kosong tidak dianggap pelaksana. Seluruh pengelompokan saling eksklusif.

Pada 129 data sumber: usia valid 84 (minimum 19, mean 29,3, median 29, maksimum 41); pendidikan terisi 72; struktural 11, fungsional 31, pelaksana 87. Nilai pada produksi akan mengikuti isi database terkini. Semua payload publik berisi agregat, tanpa nama, NIP, tanggal individual, atau daftar usia individual.

### Pengujian pembaruan ini

**Pengujian versi 22 September: 124 pemeriksaan lulus:** 60 pengujian hitungan/integrasi database dan 64 browser. PHP 8.3.6, MariaDB 10.11.14, Chromium 153; database uji terpisah. Pengujian mencakup median ganjil/genap/kosong, tanggal tidak valid, irisan filter, penghitungan PLT satu kali, nilai pendidikan manual, privasi payload, seluruh kombinasi status/gender, navigasi keyboard, grafik mobile, overflow 320/390/768 px, serta galat JavaScript/CSP. Sintaks PHP dan JavaScript juga diperiksa. Laporan: `hasil_dashboard.json` dan `hasil_browser.json`. Laporan lain adalah hasil versi sebelumnya.

Pengujian dapat diulang pada server lokal di port 8080 yang menggunakan database uji. Jalankan `tests_dashboard.php` dengan `DB_NAME` berakhiran `_test` dan `RUN_DASHBOARD_TESTS=1`. Jalankan `test_browser.cjs` dengan `RUN_BROWSER_TESTS=1 PUBLIC_ANALYTICS_TESTS=1` dari folder aplikasi (Playwright dan Chromium diperlukan; lokasi browser dapat diatur melalui `CHROMIUM_PATH`). Hilangkan `PUBLIC_ANALYTICS_TESTS` untuk suite browser versi sebelumnya.

## Pembaruan Ir. dan profesi dokter hewan

Untuk memperbarui versi yang **sudah mempunyai kolom pendidikan_terakhir**:

1. Backup database dan berkas aplikasi.
2. Impor **migrasi_gelar_profesi.sql**. Skrip memperluas pilihan ENUM pada kolom yang sama dan mendeteksi gelar untuk nilai yang masih NULL.
3. Ganti **functions.php**, **app.js**, dan **Admin/index.php** dari paket terbaru.
4. Nilai yang sudah terisi tetap dipertahankan. Untuk menghitung ulang satu pegawai, buka Edit, pilih **Otomatis dari gelar pada nama**, lalu Simpan.

Ir./Ir → Diploma IV/Sarjana (S1). drh./drh → Program Profesi Dokter Hewan. Keduanya dikenali di depan atau di akhir nama. Contoh: Ir. Budi → S1; drh. Budi → Program Profesi Dokter Hewan; drh. Budi, M.Si. → S2. Prioritas deteksi aplikasi: S3, S2, profesi dokter hewan, S1, diploma. Pilihan manual tetap diutamakan. Nama seperti Irma dan Irawan tidak dianggap gelar Ir.

Laporan pengujian pembaruan ini: **HASIL_GELAR_PROFESI.md**. Tampilan publik dan fitur lain tidak diubah.

## Pembaruan pendidikan terakhir dan penyederhanaan dashboard

### Memperbarui aplikasi versi sebelumnya

1. Backup database dan berkas aplikasi.
2. Jika memakai versi lama yang BELUM memiliki kolom pendidikan_terakhir (sudah memiliki tabel admin dan kolom tanggal manual), impor **migrasi_pendidikan.sql satu kali**. Skrip hanya menambah satu kolom `pendidikan_terakhir` dan mengisi hasil deteksi gelar pada data lama. Jangan mengimpor SQL lengkap atau `migrasi.sql` pada jalur ini.
3. Ganti empat file aplikasi: **index.php**, **functions.php**, **app.js**, dan **Admin/index.php**. `style.css` sama dengan versi sebelumnya; konfigurasi, login, dan logout tidak perlu diganti.
4. Buka Admin, periksa kolom Pendidikan terakhir, dan koreksi sesuai ijazah melalui Edit. Beranda dan Admin memakai fingerprint aset agar JavaScript terbaru termuat.

Untuk instalasi baru pada database kosong, impor **kepegawaian_distapangtani.sql** (sudah mencakup pendidikan). Untuk database sumber SQL awal yang belum pernah dimigrasikan, impor **migrasi.sql** terbaru saja. Ketiga jalur ini alternatif; jangan menjalankannya berurutan pada database yang sama.

### Pilihan pendidikan

Dropdown di Admin hanya dapat memilih satu jenjang:

- SD/sederajat
- SMP/sederajat
- SMA/sederajat
- Diploma I - Diploma III
- Diploma IV/Sarjana (S1)
- Magister (S2)
- Doktor (S3)
- Program Profesi Dokter Hewan

Ada satu mode tambahan **Otomatis dari gelar pada nama**. Ini mode pengisian, bukan pilihan pendidikan tersendiri. Server menyimpan hasil deteksi tertinggi dalam kolom ENUM, atau NULL jika tidak dapat disimpulkan. Dropdown dan preview hanya membantu input; validasi dan keputusan akhir dilakukan kembali di server.

Contoh: S.P., S.T., S.E., S.Kom. → S1; M.Si., M.M., M.P., M.Kom. → S2; Ph.D./D.Phil. → S3; A.Md. → kelompok Diploma I–III. S.P., M.Si. maupun M.Si., S.P. sama-sama menghasilkan S2. Perbedaan titik, spasi, dan kapitalisasi ditangani. Rangkaian gelar akademik dikenali di akhir nama, sedangkan Ir. dan drh. juga dikenali di depan nama; whitelist gelar dapat dilihat di EDUCATION_PATTERNS pada functions.php.

Nama tanpa gelar atau singkatan yang belum dikenali dibiarkan kosong. dr. dan Drs. tidak dipetakan otomatis. SD/SMP/SMA diisi manual. Hasil deteksi perlu diverifikasi admin terhadap ijazah.

**Pilihan manual selalu menang atas deteksi gelar.** Mengubah nama tidak menimpa nilai pendidikan yang sedang dipilih. Hasil otomatis yang telah disimpan akan muncul sebagai jenjang terpilih saat Edit; pilih kembali mode otomatis jika ingin menghitung ulang berdasarkan nama terbaru. Tidak ada mekanisme yang menimpa koreksi manual saat membuka halaman. Pendidikan ditampilkan hanya di portal Admin.

### Dashboard publik

Kategori Belum diketahui untuk kelahiran/jenis kelamin, kategori Di luar generasi X/Y/Z, serta kotak Jenis kelamin per status sudah dihapus dari tampilan. Data pegawai tidak dihapus. Total status tetap menghitung seluruh pegawai; grafik hanya menampilkan kategori yang tersedia. Persentase dihitung dari jumlah kategori yang ditampilkan pada masing-masing grafik, dan keterangan “Mencakup N dari M pegawai” menjaga kejelasan cakupan. Diagram donat memakai jumlah laki-laki + perempuan sebagai total grafik. Filter status tetap aktif.

Laporan sebelumnya: **HASIL_PENDIDIKAN.md**. Laporan lain mencatat pengujian versi sebelumnya.

Aplikasi PHP native, PDO MySQL/MariaDB, CSS dan JavaScript lokal. Tidak menggunakan Laravel, CodeIgniter, Composer, npm, framework backend, atau CDN. Antarmuka dashboard responsif menggunakan CSS khusus agar CSS dan JavaScript berjalan tanpa internet; logo publik memuat gambar dari Wikimedia. JavaScript harus aktif untuk dialog, pencarian, filter, dan pagination.

Judul sistem: **Kepegawaian Dinas Ketahanan Pangan dan Pertanian (Distapangtani) Kota Samarinda**.

## Isi dan struktur

Hanya terdapat dua direktori: `Kepegawaian/` dan `Kepegawaian/Admin/`. Semua aset, SQL, panduan, hasil pengujian, dan skrip pengujian disimpan langsung pada direktori utama; tidak ada direktori bertingkat tambahan.

| Lokasi | Fungsi |
|---|---|
| `index.php` | Beranda publik: statistik agregat generasi dan jenis kelamin |
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
    location ~ ^/(?:style\.css|app\.js|lambang-kota-samarinda\.jpg)$ { try_files $uri =404; }
    location / { return 404; }
}
```

Untuk PHP-FPM, environment bisa disetel melalui `env[APP_HTTPS] = 1`, `env[DB_HOST]`, dan nilai lainnya pada pool FPM. Parameter FastCGI contoh di atas perlu diverifikasi sesuai cara PHP membaca environment pada konfigurasi server yang dipakai. Jangan menyajikan folder hanya sebagai static hosting: aplikasi memerlukan PHP dan MySQL/MariaDB.

## Perilaku halaman

**Beranda** tidak memiliki tautan Admin. Publik memperoleh kartu statistik status serta grafik generasi X/Y/Z dan jenis kelamin (laki-laki/perempuan). Filter status dan tombol Jumlah/Persentase tetap aktif. Tabel Jenis kelamin per status tidak ditampilkan. Nama, jabatan, NIP, tanggal lahir individual, pendidikan individual, dan akun admin tidak dikirim ke HTML/JavaScript publik. Payload hanya berisi agregat.

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
| `pendidikan_terakhir` | ENUM tujuh jenjang, nullable; hasil deteksi gelar atau pilihan manual admin |
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

Lihat `HASIL_PENDIDIKAN.md` untuk hasil pengujian terkini; laporan lain bersifat historis.

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
