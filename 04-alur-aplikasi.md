# 04. Alur Aplikasi
## 📂 Daftar Halaman Dokumentasi
- [01 - Pendahuluan](01-pendahuluan.md)
- [02 - Fitur Aplikasi](02-fitur-aplikasi.md)
- [03 - Desain Database](03-desain-database.md)
- [04 - Alur Aplikasi](04-alur-aplikasi.md)
- [05 - Teknologi](05-teknologi.md)
- [06 - Pengembangan](06-pengembangan.md)
- [07 - Pengujian](07-pengujian.md)
- [08 - Deploy dan Maintenance](08-deploy.md)
- [09 - Wireframe](09-wireframe/README.md)


## 📑 Daftar Isi

- [1. Pendahuluan](#1.-pendahuluan)
- [2. Alur Utama Aplikasi](#2.-alur-utama-aplikasi)
  - [2.1. Proses Absensi Siswa](#2.1.-proses-absensi-siswa)
  - [2.2. Proses Absensi Guru](#2.2.-proses-absensi-guru)
  - [2.3. Penentuan Status Kehadiran Otomatis](#2.3.-penentuan-status-kehadiran-otomatis)
  - [2.4. Notifikasi WhatsApp Otomatis](#2.4.-notifikasi-whatsapp-otomatis)
  - [2.5. Proses Izin Siswa via Google Form](#2.5.-proses-izin-siswa-via-google-form)
  - [2.6. Penilaian Peringkat Disiplin](#2.6.-penilaian-peringkat-disiplin)
  - [2.7. Rekapan Otomatis Harian dan Mingguan](#2.7.-rekapan-otomatis-harian-dan-mingguan)
  - [2.8. Pengabaian Proses Saat Hari Libur](#2.8-pengabaian-proses-saat-hari-libur)
- [3. Alur Admin Panel (Filament)](#3.-alur-admin-panel-filament)
  - [3.1. Manajemen Data Master](#3.1.-manajemen-data-master)
  - [3.2. Pengaturan Jadwal Absensi (Rules)](#3.2.-pengaturan-jadwal-absensi-rules)
  - [3.3. Pengaturan Perangkat dan API WhatsApp](#3.3.-pengaturan-perangkat-dan-api-whatsapp)
- [4. Alur Backup dan Restore](#4-alur-backup-dan-restore)
- [5. Diagram Flow](#5-diagram-flow-opsional)
- [🔁 Navigasi](#-navigasi)


## 1. Pendahuluan

Dokumen ini menjelaskan alur kerja lengkap dari sistem aplikasi absensi berbasis sidik jari yang digunakan oleh siswa dan guru.  
Tujuan utama dokumentasi ini adalah untuk memberikan gambaran menyeluruh kepada tim pengembang mengenai:

- Alur teknis tiap fitur utama
- Interaksi antara pengguna, perangkat, dan sistem backend
- Proses otomatisasi (notifikasi, perhitungan status, rekap)
- Integrasi dengan layanan eksternal seperti WhatsApp dan Google Forms

Struktur ini dirancang agar dapat digunakan sebagai acuan saat melakukan pengembangan backend, debugging, maupun ekspansi fitur lanjutan.


## 2. Alur Utama Aplikasi

### 2.1. Proses Absensi Siswa

1. **Siswa menempelkan sidik jari ke alat fingerprint**
2. **Perangkat fingerprint mengirimkan data fingerprint_id + timestamp + IP alat ke sistem**
3. **Sistem melakukan pencocokan fingerprint_id ke tabel `students`**
   - Jika tidak ditemukan, data ditolak
4. **Jika ditemukan:**
   - Data disimpan ke tabel `student_attendances`
   - `device_id` ditentukan dari IP alat
   - Sistem mengambil aturan absensi (`attendance_rules`) berdasarkan:
     - Hari (`day_of_week`)
     - Tanggal override (jika ada)
     - Kelas siswa (`class_id`)
5. **Sistem menentukan apakah scan tersebut adalah:**
   - Masuk (time_in)
   - Pulang (time_out)
6. **Sistem memeriksa apakah terlambat**
   - Jika waktu scan melebihi batas `time_in_end`, status = `terlambat`
   - Jika tidak scan sama sekali hingga `time_out_end`, status = `tidak_hadir`
7. **Jika scan berhasil:**
   - Sistem menyimpan foto dari webcam (jika aktif) ke `photo_in`
   - Memutar audio “Selamat Datang, [Nama]” di layar

> Seluruh proses ini bersifat **otomatis dan real-time** setelah siswa melakukan scan.

---

### 2.2. Proses Absensi Guru

1. **Guru menempelkan sidik jari ke alat fingerprint**
2. **Fingerprint scanner mengirim data `fingerprint_id` + timestamp ke sistem**
3. **Sistem mencocokkan `fingerprint_id` ke tabel `teachers`**
   - Jika tidak ditemukan → scan ditolak
4. **Jika ditemukan:**
   - Sistem mencatat kehadiran di tabel `teacher_attendances`
   - Menentukan apakah itu `time_in` atau `time_out` berdasarkan riwayat scan hari itu
   - Menyimpan foto hasil verifikasi (jika tersedia)
   - `device_id` diambil dari alat yang digunakan
5. **Status kehadiran (`status`) dan keterlambatan (`is_late`) dihitung dengan aturan umum (belum berbasis per kelas)**

> Absensi guru tidak bergantung pada `attendance_rules`, tapi waktu keterlambatan bisa diatur secara global.

---

### 2.3. Penentuan Status Kehadiran Otomatis

Setelah scan masuk dan/atau keluar berhasil disimpan, sistem akan menghitung status absensi berdasarkan:

#### Siswa:
- Jika scan masuk antara `time_in_start` dan `time_in_end` → `hadir`
- Jika scan masuk melebihi `time_in_end` → `terlambat`
- Jika tidak scan masuk hingga `time_out_end` → `tidak_hadir`
- Jika ada izin yang valid pada hari tersebut → `izin`

#### Guru:
- Jika scan masuk sebelum jam global yang ditentukan → `hadir`
- Jika terlambat → `terlambat`
- Jika tidak scan sama sekali dan tidak ada izin → `tidak_hadir`
- Jika ada izin valid di hari tersebut (via tabel `teacher_leave_requests`) → `izin`


> Semua status ini akan dihitung dan disimpan secara otomatis oleh backend saat absensi tercatat.

---
### 2.4. Notifikasi WhatsApp Otomatis

Sistem akan mengirim notifikasi WhatsApp otomatis berdasarkan kondisi kehadiran siswa setiap hari. Proses ini berjalan otomatis melalui API WhatsApp yang telah dikonfigurasi.

#### Notifikasi Harian:

- **Jika siswa terlambat**
  - Kirim pesan ke nomor WhatsApp orang tua
- **Jika siswa tidak hadir (tanpa izin)**
  - Kirim pesan ke orang tua
  - Kirim laporan PDF harian ke wali kelas
- **Jika siswa izin**
  - Kirim pesan konfirmasi ke wali kelas

#### Jalur Pengiriman:

- API WhatsApp digunakan untuk mengirim pesan teks dan file PDF
- Nomor tujuan ditentukan berdasarkan:
  - `students.parent_whatsapp`
  - Nomor wali kelas (dari konfigurasi `settings`)
- Template pesan disiapkan di backend dan bisa dikustomisasi

> Notifikasi juga bisa dikirim ke kesiswaan jika siswa terlambat lebih dari 3x (trigger internal)

---

### 2.5. Proses Izin Siswa dan Guru

#### a) Izin Siswa (via Google Form)

1. Orang tua atau siswa mengisi Google Form khusus pengajuan izin
2. Data otomatis masuk ke Google Sheets
3. Backend Laravel membaca data dari Google Sheets menggunakan Google Sheets API
4. Data disimpan ke tabel `student_leave_requests`
5. Validasi dilakukan untuk tanggal dan NIS
6. Jika izin valid:
   - Sistem menandai status siswa sebagai `izin` pada hari itu
   - Mengirim notifikasi WhatsApp ke wali kelas siswa tersebut

#### b) Izin Guru (via Google Form)

1. Guru mengisi form izin khusus untuk guru (Google Form berbeda)
2. Data masuk ke Google Sheets yang terhubung ke sistem
3. Backend Laravel membaca data dari Sheet menggunakan Google Sheets API
4. Data izin disimpan ke tabel `teacher_leave_requests`
5. Jika data valid:
   - Sistem menandai guru sebagai `izin` tanpa perlu scan
   - Status tersebut digunakan saat rekap harian dan mingguan

> Admin/operator tetap dapat melihat, memverifikasi, atau mengedit data izin dari panel Filament.
---
### 2.6. Penilaian Peringkat Disiplin

Sistem secara otomatis menghitung peringkat disiplin siswa berdasarkan data absensi yang telah direkap bulanan.

#### Parameter Perhitungan:

- Total kehadiran (`total_present`)
- Total keterlambatan (`total_late`)
- Total ketidakhadiran tanpa keterangan (`total_absent`)
- Nilai skor dihitung dengan formula tertentu, misalnya:
`skor = (total_present * 1) + (total_late * 0.5) + (total_absent * -1)`
#### Alur:

1. Setiap awal bulan, sistem menghitung rekap absensi bulan sebelumnya
2. Data disimpan ke tabel `discipline_rankings`
3. Data ini digunakan untuk:
 - Menampilkan daftar siswa paling disiplin
 - Mendeteksi siswa dengan absensi buruk
 - Menampilkan leaderboard di dashboard admin/kesiswaan

> Peringkat ini hanya berlaku untuk siswa. Jika diinginkan, sistem bisa dikembangkan untuk guru juga.

---

### 2.7. Rekapan Otomatis Harian dan Mingguan

Sistem menjalankan cronjob untuk merekap data absensi dan mengirimkannya ke pihak terkait secara otomatis.

#### Rekapan Harian:

- ✅ Jam 13:00 sistem akan memproses seluruh data kehadiran hari itu
- 📤 Mengirimkan:
- Rekapan per kelas ke wali kelas dalam bentuk PDF
- Pesan individual ke orang tua jika siswa tidak hadir

#### Rekapan Mingguan:

- 📅 Setiap hari Sabtu pukul 18:00, sistem merekap seluruh kehadiran selama 1 minggu terakhir
- Menghasilkan laporan PDF yang bisa diunduh dari panel admin

#### Mekanisme:

- Data absensi diambil dari `student_attendances` dan `teacher_attendances`
- Diformat menjadi file PDF menggunakan package Laravel (dompdf/snipdf)
- Dikirim melalui API WhatsApp ke nomor wali kelas dan orang tua

> Semua jadwal rekap diatur menggunakan Laravel Scheduler dan bisa dimodifikasi oleh admin jika diperlukan.

---

### 2.8 Pengabaian Proses Saat Hari Libur

Sistem secara otomatis akan **mengabaikan semua proses absensi, notifikasi, dan rekap** pada hari-hari yang ditandai sebagai libur.

#### Alur Kerja:

1. Setiap kali sistem akan memproses:
   - Absensi siswa
   - Absensi guru
   - Notifikasi WhatsApp
   - Rekap harian atau mingguan
2. Sistem akan terlebih dahulu mengecek apakah **tanggal hari ini** masuk dalam rentang libur di tabel `holidays`

```php
$isHoliday = Holiday::where('start_date', '<=', today())
                    ->where('end_date', '>=', today())
                    ->exists();

if ($isHoliday) {
    // Lewati proses absensi, notifikasi, dan rekap
    return;
}
```

#### Sumber Data:
- Data libur dimasukkan oleh admin melalui Panel Filament
- Bisa diatur untuk satu hari atau rentang hari (libur panjang)


#### Dampak dari Hari Libur:
- Siswa dan guru tidak akan mendapatkan status kehadiran
- Sistem tidak akan mengirim notifikasi atau laporan rekap
- Data hari libur tidak memengaruhi peringkat disiplin siswa

> Dengan adanya mekanisme ini, sistem menjadi lebih akurat dan efisien, serta mengurangi kesalahan pengolahan data pada hari-hari tidak aktif sekolah.

<br><br>

## 3. Alur Admin Panel (Filament)

Panel admin menggunakan Laravel Filament sebagai antarmuka backend utama untuk mengelola data, konfigurasi, dan pemantauan sistem. Berikut alur-alur pengelolaan yang tersedia:


### 3.1. Manajemen Data Master

Admin/operator memiliki akses penuh untuk mengelola seluruh data dasar sistem, seperti:

- 👤 **Siswa**
  - Tambah/edit data siswa: NIS, nama, kelas, ID fingerprint, foto, nomor WA orang tua
  - Hapus data siswa jika lulus/keluar
- 👨‍🏫 **Guru**
  - Tambah/edit data guru: NIP, nama, fingerprint ID, foto
- 🏫 **Kelas**
  - Menentukan level (X, XI, XII), jurusan, dan nama kelas
- 🖐️ **Fingerprint**
  - Registrasi fingerprint ID dilakukan di alat, lalu dicocokkan ke user manual lewat panel

Semua data master dapat disaring berdasarkan kelas, jurusan, atau nama, dan dapat diimpor/ekspor bila dibutuhkan.

---

### 3.2. Pengaturan Jadwal Absensi (Rules)

Admin dapat mengatur jam masuk dan keluar berdasarkan:

- ⏰ Hari (Senin s.d. Minggu)
- 📅 Tanggal spesifik (misalnya untuk jadwal ujian)
- 🧑‍🏫 Per kelas (melalui relasi `class_id`)
- Range waktu:
  - `time_in_start` – `time_in_end`
  - `time_out_start` – `time_out_end`

Pengaturan ini digunakan oleh sistem untuk menentukan status (`hadir`, `terlambat`, `tidak_hadir`) saat siswa melakukan scan.

> Jadwal yang lebih spesifik (override tanggal) akan diutamakan daripada jadwal mingguan default.

---

### 3.3. Pengaturan Perangkat dan API WhatsApp

Melalui menu pengaturan, admin dapat:

- 🧠 **Menambahkan perangkat fingerprint**
  - Nama alat
  - IP address
  - Lokasi penempatan
- ✅ **Mengaktifkan / menonaktifkan alat**
- 🔐 **Mengatur API WhatsApp**
  - Token API
  - Nomor pengirim (sender)
  - Format pesan notifikasi
- ⚙️ **Mengatur konfigurasi sistem lainnya**
  - Nomor WA kesiswaan
  - Jam pengiriman notifikasi
  - Batas keterlambatan
  - Cron waktu rekap otomatis

Semua pengaturan ini disimpan di tabel `devices` dan `settings` yang bisa diakses melalui Filament Panel.



## 4. Alur Backup dan Restore

Fitur ini dirancang untuk menjaga integritas dan keamanan data dengan melakukan backup secara otomatis dan menyediakan fitur restore dari panel admin.

### 4.1. Backup Otomatis

Sistem menjalankan backup mingguan secara otomatis menggunakan Laravel Scheduler.

#### Proses:
1. Setiap hari Jumat pukul 11:00, sistem akan:
   - Mengekspor seluruh database dalam format `.sql`
   - Menyimpan file backup ke storage (misal: `storage/backups/backup-YYYY-MM-DD.sql`)
   - Mengarsipkan foto-foto absen jika diperlukan

2. Informasi file backup disimpan ke tabel `backups`:
   - Path file backup
   - Status pemulihan (`restored = false`)
   - Timestamp waktu backup

3. Admin dapat melihat daftar backup dari panel Filament

---

### 4.2. Restore Manual via Panel

Jika terjadi kehilangan data atau kerusakan sistem, admin dapat melakukan restore:

1. Admin membuka halaman daftar backup
2. Pilih salah satu backup yang tersedia
3. Tekan tombol **Restore**
4. Sistem akan:
   - Menonaktifkan seluruh proses absensi sementara
   - Menjalankan perintah import SQL ke database
   - Menampilkan notifikasi berhasil/gagal
   - Menandai backup sebagai `restored = true`

> ⚠️ Proses restore harus dilakukan dengan hati-hati dan hanya oleh admin teknis yang berwenang.

---

### 4.3. Rekomendasi Teknis

- Backup harus disimpan secara terpisah dari server utama (misalnya ke Google Drive atau S3)
- Log restore harus dicatat untuk keperluan audit
- Backup juga bisa dijalankan secara manual dari CLI (`php artisan backup:run`)

---


[← Sebelumnya](03-desain-database.md) | [Selanjutnya →](05-teknologi.md)