# Pengujian pembaruan gelar Ir. dan drh.

Tanggal: 22 September 2026. Lingkungan: PHP 8.3.6, MariaDB 10.11.14, Chromium 153. Pengujian menggunakan database terpisah, bukan server produksi.

| Pengujian | Lulus | Gagal |
|---|---:|---:|
| Deteksi gelar dan pilihan manual PHP | 45 | 0 |
| Instalasi SQL, migrasi dan kesesuaian data | 7 | 0 |
| Penyimpanan pendidikan melalui HTTP Admin | 21 | 0 |
| Agregat dan privasi dashboard | 16 | 0 |
| Browser desktop/mobile dan pratinjau gelar | 37 | 0 |
| **Total** | **126** | **0** |

Pemeriksaan sintaks functions.php, Admin/index.php, dan app.js juga lulus.

Ir./Ir dan drh./drh diuji di awal serta akhir nama, dengan variasi huruf. Irma/Irawan tidak terdeteksi sebagai Ir. Gelar S2/S3 tetap lebih diutamakan dalam mode otomatis. Pilihan manual, termasuk profesi dokter hewan, berhasil disimpan. Nilai palsu dan input pilihan berbentuk array ditolak.

SQL instalasi baru, migrasi dari versi sebelum pendidikan, dan migrasi dari versi pendidikan sebelumnya menghasilkan 129 pegawai identik. SQL dan PHP menyepakati 72 hasil pendidikan terdeteksi dan 57 nilai kosong. Migrasi tidak menimpa pendidikan yang telah terisi; pengulangan migrasi juga diuji. Jumlah kolom tetap 11 dan akun awal tidak berubah.

Antarmuka browser menyediakan delapan pilihan pendidikan dan mode otomatis. Pratinjau Ir., drh., dan drh. dengan M.Si. sesuai hasil server. Dashboard publik tetap hanya mengirim agregat. Tidak ada galat JavaScript/CSP pada pemeriksaan browser.

Laporan JSON terkait disertakan. Laporan pengujian fitur lain dari versi sebelumnya merupakan catatan historis; suite keamanan HTTP umum tidak dijalankan ulang karena pembaruan ini terbatas pada aturan pendidikan dan pilihan ENUM.
