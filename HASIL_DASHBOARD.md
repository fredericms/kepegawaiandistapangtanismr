> Histori versi sebelum fitur Pendidikan Terakhir. Hasil terkini ada di HASIL_PENDIDIKAN.md; kategori yang ditampilkan sudah berubah.

# Hasil pembaruan tampilan dashboard demografi

**46 pemeriksaan lulus, 0 gagal.** PHP 8.3.6, MariaDB 10.11.14, Chromium 153.

Perubahan aplikasi hanya pada tampilan demografi di index.php/style.css/app.js serta parameter opsional versi aset pada page_top di functions.php. Agregasi dan logika data tidak berubah.

Verifikasi byte: seluruh file Admin, config.php, router.php, .htaccess dan SQL tetap identik dengan ZIP sebelum pembaruan. HTML hero/kartu komposisi pegawai serta CSS di luar demografi tetap identik. Isi functions.php sebelum page_top identik; parameter tambahan tidak mengubah keluaran default untuk Admin.

## Verifikasi tampilan dan interaksi

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
- LULUS — Tabel gender per status tetap lengkap
- LULUS — Baris status terpilih ditandai
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
- LULUS — Manajemen admin tetap dalam halaman Admin
- LULUS — Grafik satu kolom pada mobile
- LULUS — Beranda mobile tidak overflow horizontal
- LULUS — Admin mobile tidak overflow horizontal
- LULUS — Dialog mobile muat dalam viewport
- LULUS — Tidak ada error JavaScript atau pelanggaran CSP

## Verifikasi data

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

## Catatan pemasangan

Ganti index.php, app.js, style.css dan functions.php sekaligus. Tidak perlu impor database. Fingerprint aset menangani cache browser setelah file terbaru terpasang; cache HTML di reverse proxy/CDN perlu dibersihkan jika masih menyajikan HTML lama. Penyebab di server pengguna belum dikonfirmasi langsung.

Pengujian dilakukan lokal, bukan pada domain produksi.
