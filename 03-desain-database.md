# 🗃️ Desain Database

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

## 📚 Daftar Isi Halaman
- [1. Struktur Tabel Utama](#1-struktur-tabel-utama)
- [2. Relasi Antar Tabel](#2-relasi-antar-tabel)


## 1. Ringkasan Tabel Utama

![Diagram ERD Absensi](ERD-Absensi.png)


| Tabel                                | Deskripsi Ringkas                                                                 |
|--------------------------------------|------------------------------------------------------------------------------------|
| [`students`](#tabel%3A-students)        | Menyimpan data lengkap siswa, termasuk kelas dan jurusan terpisah                 |
| [`teachers`](#tabel%3A-teachers)        | Menyimpan data guru yang menggunakan fingerprint                                  |
| [`attendances`](#tabel%3A-attendances)  | Mencatat kehadiran harian siswa dan guru, termasuk waktu masuk/keluar, status     |
| [`student_leave_requests`](#tabel%3A-student_leave_requests) | Menyimpan pengajuan izin siswa dari Google Form                        |
| [`notifications`](#tabel%3A-notifications) | Menyimpan log pengiriman pesan WA ke orang tua, wali kelas, dan kesiswaan      |
| [`discipline_rankings`](#tabel%3A-discipline_rankings) | Peringkat disiplin bulanan siswa                                  |
| [`users`](#tabel%3A-users)              | Akun login untuk admin, guru, dan petugas lainnya                                 |
| [`roles`](#tabel%3A-roles), [`permissions`](#tabel%3A-permissions) | Sistem hak akses (Spatie Role & Permission)            |
| [`model_has_roles`](#tabel%3A-model_has_roles), [`model_has_permissions`](#tabel%3A-model_has_permissions) | Relasi user dengan role/permission langsung |
| [`role_has_permissions`](#tabel%3A-role_has_permissions) | Relasi antara role dan permission                                   |
| [`backups`](#tabel%3A-backups)          | Data backup mingguan dan restore sistem                                           |
| [`settings`](#tabel%3A-settings)        | Semua konfigurasi sistem (termasuk jam absen & WA API)                           |
| [`devices`](#tabel%3A-devices)          | Info perangkat fingerprint (IP, status, lokasi)                                  |
| [`attendance_rules`](#tabel%3A-attendance_rules) | Rentang jam masuk dan pulang untuk absensi                           |
| [`scan_logs`](#tabel%3A-scan_logs) | Audit log aktivitas scan sidik jari siswa                            |


## 2. Struktur dan Relasi Detail

Bagian ini menjelaskan secara lengkap struktur basis data aplikasi Absensi STM.  
Setiap tabel yang digunakan dijabarkan dalam bentuk:

- 📌 Nama kolom dan tipe datanya
- 💬 Deskripsi singkat fungsi kolom
- 🔖 Sifat khusus seperti: Primary Key (PK), Foreign Key (FK), Unique, Enum, dll

Struktur ini disusun agar bisa:
- 💻 Diterapkan langsung dalam perancangan database (MySQL/PostgreSQL)
- 🧠 Dipahami baik oleh pengembang maupun pembaca non-teknis
- 🔁 Mendukung fitur otomatisasi, keamanan data, dan fleksibilitas sistem absensi

---


### Tabel: `students`

Tabel ini menyimpan seluruh data siswa yang akan dicatat kehadirannya menggunakan fingerprint scanner.  
Digunakan sebagai acuan utama untuk:

- Absensi
- Perizinan
- Notifikasi ke orang tua
- Peringkat kedisiplinan

**Fungsi-fungsi penting:**
- Menghubungkan data siswa ke semua tabel absensi dan izin
- Menyimpan ID fingerprint untuk identifikasi biometrik
- Nomor WA digunakan untuk pengiriman notifikasi otomatis

| Kolom             | Tipe Data   | Keterangan                              | Sifat Khusus        |
|-------------------|-------------|------------------------------------------|----------------------|
| `id`              | BIGINT      | ID unik siswa                            | PK                   |
| `name`            | VARCHAR     | Nama lengkap siswa                       |                      |
| `nis`             | VARCHAR     | Nomor Induk Siswa                        | UNIQUE               |
| `class`           | VARCHAR     | Kelas siswa (contoh: X, XI, XII)         |                      |
| `major`           | VARCHAR     | Jurusan siswa (contoh: RPL, TKJ)         |                      |
| `gender`          | ENUM        | Jenis kelamin siswa                      | ENUM (`L`, `P`)      |
| `fingerprint_id`  | VARCHAR     | ID fingerprint (dari mesin absensi)      |                      |
| `photo`           | VARCHAR     | Path/URL ke file foto siswa              |                      |
| `parent_whatsapp` | VARCHAR     | Nomor WhatsApp orang tua                 |                      |
| `created_at`      | TIMESTAMP   | Waktu data dibuat                        |                      |
| `updated_at`      | TIMESTAMP   | Waktu data terakhir diperbarui           |                      |

---
### Tabel: `teachers`

Tabel ini menyimpan data guru yang juga menggunakan fingerprint.  
Biasanya digunakan jika guru juga diminta melakukan absensi secara digital.

**Fungsi-fungsi penting:**
- Mendukung kehadiran guru di tabel `attendances`
- Bisa dikembangkan untuk melihat kehadiran per guru
- Memberikan fleksibilitas agar sistem bisa digunakan lintas peran

| Kolom            | Tipe Data   | Keterangan                                     | Sifat Khusus |
|------------------|-------------|------------------------------------------------|--------------|
| `id`             | BIGINT      | ID unik guru                                   | PK           |
| `name`           | VARCHAR     | Nama lengkap guru                              |              |
| `nip`            | VARCHAR     | Nomor Induk Pegawai (jika ada)                |              |
| `fingerprint_id` | VARCHAR     | ID fingerprint (jika guru menggunakan absen)   |              |
| `photo`          | VARCHAR     | Path/URL ke foto guru                          |              |
| `created_at`     | TIMESTAMP   | Tanggal data dibuat                            |              |
| `updated_at`     | TIMESTAMP   | Tanggal data diperbarui terakhir               |              |
---
### Tabel: `attendances`

Tabel ini merupakan **inti sistem absensi**. Menyimpan semua aktivitas scan masuk dan keluar, serta status kehadiran.

**Fungsi-fungsi penting:**
- Menyimpan kehadiran harian siswa dan guru
- Menentukan status secara otomatis: `hadir`, `terlambat`, `tidak_hadir`, `izin`
- Mengambil data dari scan fingerprint, webcam (foto), dan IP perangkat
- Digunakan untuk rekap kehadiran harian, bulanan, dan grafik


| Kolom         | Tipe Data | Keterangan                                                  | Sifat Khusus  |
|---------------|-----------|--------------------------------------------------------------|----------------|
| `id`          | BIGINT    | ID unik absensi                                              | PK             |
| `user_id`     | BIGINT    | ID dari siswa atau guru yang absen                          | FK → `students.id` / `teachers.id` |
| `user_type`   | ENUM      | Menandakan tipe user: `student` atau `teacher`              | ENUM           |
| `date`        | DATE      | Tanggal kehadiran                                            |                |
| `time_in`     | TIME      | Waktu masuk                                                 |                |
| `time_out`    | TIME      | Waktu pulang                                                |                |
| `status`      | ENUM      | Status kehadiran: `hadir`, `terlambat`, `tidak_hadir`, `izin` | ENUM        |
| `is_late`     | BOOLEAN   | Penanda apakah siswa telat                                  |                |
| `photo_in`    | VARCHAR   | Path foto saat scan fingerprint                             |                |
| `device_ip`   | VARCHAR   | IP dari alat fingerprint yang digunakan                     |                |
| `created_at`  | TIMESTAMP | Waktu pencatatan dibuat                                     |                |
| `updated_at`  | TIMESTAMP | Waktu pencatatan terakhir diperbarui                        |                |
---
### Tabel: `student_leave_requests`
Tabel ini menyimpan data izin siswa yang diajukan melalui Google Form, WhatsApp, atau cara lain.

**Fungsi-fungsi penting:**
- Mendokumentasikan semua pengajuan izin
- Membantu sistem mengabaikan keterlambatan/ketidakhadiran jika izin valid
- Dihubungkan ke Google Sheets menggunakan API untuk pengambilan otomatis
- Digunakan oleh admin untuk verifikasi dan rekap alasan izin

| Kolom         | Tipe Data | Keterangan                                                            | Sifat Khusus       |
|---------------|-----------|------------------------------------------------------------------------|--------------------|
| `id`          | BIGINT    | ID unik permintaan izin                                                | PK                 |
| `student_id`  | BIGINT    | ID siswa yang mengajukan izin                                          | FK → `students.id` |
| `date`        | DATE      | Tanggal yang diajukan untuk izin                                       |                    |
| `reason`      | TEXT      | Alasan izin yang diisi dalam form                                     |                    |
| `submitted_by`| VARCHAR   | Nama pengisi form (bisa siswa atau orang tua)                         |                    |
| `via`         | ENUM      | Media pengajuan: `form_online` atau lainnya jika ditambahkan          | ENUM               |
| `created_at`  | TIMESTAMP | Waktu pengajuan izin masuk ke sistem                                  |                    |
---
### Tabel: `notifications`
Tabel ini menyimpan semua log pengiriman pesan WhatsApp yang dilakukan sistem.

**Fungsi-fungsi penting:**
- Merekam semua pesan WA: keterlambatan, tidak hadir, izin, dan rekap PDF
- Menunjukkan siapa penerima pesan: orang tua, wali kelas, atau kesiswaan
- Menyimpan status kirim (`sent`, `failed`) dan waktu pengiriman (`sent_at`)
- Membantu debugging jika pesan tidak sampai

| Kolom         | Tipe Data | Keterangan                                                            | Sifat Khusus       |
|---------------|-----------|------------------------------------------------------------------------|--------------------|
| `id`          | BIGINT    | ID unik notifikasi                                                     | PK                 |
| `student_id`  | BIGINT    | ID siswa yang menjadi subjek notifikasi                                | FK → `students.id` |
| `type`        | ENUM      | Jenis notifikasi: `izin`, `tidak_hadir`, `keterlambatan`, `rekap`      | ENUM               |
| `recipient`   | ENUM      | Penerima pesan: `orang_tua`, `wali_kelas`, `kesiswaan`                 | ENUM               |
| `content`     | TEXT      | Isi pesan yang dikirim melalui WhatsApp atau sistem lainnya            |                    |
| `status`      | ENUM      | Status pengiriman: `sent`, `failed`                                    | ENUM               |
| `sent_at`     | TIMESTAMP | Waktu saat pesan dikirim                                               |                    |
---
### Tabel: `discipline_rankings`

Tabel ini menyimpan rekap kedisiplinan siswa dalam satu bulan.

**Fungsi-fungsi penting:**
- Menghitung jumlah kehadiran, keterlambatan, dan ketidakhadiran tiap siswa
- Menghasilkan skor kedisiplinan otomatis
- Digunakan untuk dashboard peringkat atau sistem reward/sanksi siswa
- Bisa jadi dasar seleksi siswa berprestasi non-akademik

| Kolom          | Tipe Data | Keterangan                                                           | Sifat Khusus       |
|----------------|-----------|-----------------------------------------------------------------------|--------------------|
| `id`           | BIGINT    | ID unik peringkat disiplin                                           | PK                 |
| `student_id`   | BIGINT    | ID siswa yang direkap                                                | FK → `students.id` |
| `month`        | VARCHAR   | Bulan perhitungan (misal: `2024-06`)                                 |                    |
| `total_present`| INT       | Jumlah hari hadir                                                    |                    |
| `total_late`   | INT       | Jumlah hari terlambat                                                |                    |
| `total_absent` | INT       | Jumlah hari tidak hadir (tidak izin dan tidak scan)                 |                    |
| `score`        | FLOAT     | Skor akhir disiplin (formula fleksibel, tergantung aturan sekolah)   |                    |
| `created_at`   | TIMESTAMP | Waktu data ini dibuat                                                |                    |
---
### Tabel: `users`

Tabel ini menyimpan akun pengguna sistem (admin, guru, operator, dsb).

**Fungsi-fungsi penting:**
- Menyimpan data login seperti `email`, `password`, dan nomor WhatsApp
- Kolom `class_scope` bisa digunakan untuk membatasi akses user ke kelas tertentu (misalnya wali kelas hanya bisa lihat kelasnya)
- Semua user bisa dikaitkan ke `roles` dan `permissions` melalui tabel pivot


| Kolom             | Tipe Data | Keterangan                                          | Sifat Khusus |
|-------------------|-----------|------------------------------------------------------|--------------|
| `id`              | BIGINT    | ID unik pengguna sistem                             | PK           |
| `name`            | VARCHAR   | Nama pengguna                                        |              |
| `email`           | VARCHAR   | Email login (harus unik jika digunakan)             |              |
| `password`        | VARCHAR   | Password terenkripsi                                |              |
| `class_scope`     | VARCHAR   | Ruang lingkup kelas (opsional, misal: kelas wali)   |              |
| `whatsapp_number` | VARCHAR   | Nomor WhatsApp user (misal admin, guru, dsb)        |              |
| `created_at`      | TIMESTAMP | Tanggal dibuat                                       |              |
| `updated_at`      | TIMESTAMP | Tanggal terakhir diperbarui                          |              |

---
### Tabel: `roles`

Tabel ini menyimpan daftar peran atau jabatan yang dapat dimiliki user, seperti `admin`, `wali_kelas`, `kesiswaan`, dll.

**Fungsi-fungsi penting:**
- Peran digunakan untuk mengatur akses halaman & fitur di Filament Panel
- Satu user bisa memiliki lebih dari satu role


| Kolom         | Tipe Data | Keterangan                  | Sifat Khusus |
|---------------|-----------|------------------------------|--------------|
| `id`          | BIGINT    | ID peran                    | PK           |
| `name`        | VARCHAR   | Nama peran (`admin`, `guru`, `wali_kelas`, dll) |  |
| `guard_name`  | VARCHAR   | Nama guard (biasanya `web`) |              |
| `created_at`  | TIMESTAMP |                             |              |
| `updated_at`  | TIMESTAMP |                             |              |

---
### Tabel: `permissions`
Tabel ini menyimpan hak akses detail, misalnya:
- `view_attendance`, `edit_settings`, `send_notification`, dll.

**Fungsi-fungsi penting:**
- Bisa dihubungkan langsung ke user (`model_has_permissions`) atau melalui role (`role_has_permissions`)
- Memungkinkan sistem akses yang fleksibel dan aman


| Kolom         | Tipe Data | Keterangan                   | Sifat Khusus |
|---------------|-----------|-------------------------------|--------------|
| `id`          | BIGINT    | ID hak akses                 | PK           |
| `name`        | VARCHAR   | Nama permission (`lihat_absen`, dll) |          |
| `guard_name`  | VARCHAR   | Guard (biasanya `web`)       |              |
| `created_at`  | TIMESTAMP |                               |              |
| `updated_at`  | TIMESTAMP |                               |              |

---
### Tabel: `model_has_roles`
Tabel pivot yang menghubungkan user ke role-nya.

**Fungsi-fungsi penting:**
- 1 user bisa punya banyak role
- Role bisa digunakan untuk filter data, akses menu, dsb.


| Kolom        | Tipe Data | Keterangan                              | Sifat Khusus        |
|--------------|-----------|------------------------------------------|---------------------|
| `role_id`    | BIGINT    | ID peran                                 | FK → `roles.id`     |
| `model_type` | VARCHAR   | Tipe model (biasanya `App\\Models\\User`)|                     |
| `model_id`   | BIGINT    | ID user                                  | FK → `users.id`     |
| `created_at` | TIMESTAMP | Tanggal assign                           |                     |
| **PK**       | Composite | (`role_id`, `model_type`, `model_id`)    | PK                  |

---
### Tabel: `model_has_permissions`
Tabel pivot yang menghubungkan user ke permission spesifik, tanpa lewat role.

**Fungsi-fungsi penting:**
- Untuk memberi akses unik ke satu user tanpa mengubah role


| Kolom           | Tipe Data | Keterangan                           | Sifat Khusus        |
|-----------------|-----------|---------------------------------------|---------------------|
| `permission_id` | BIGINT    | ID permission                         | FK → `permissions.id` |
| `model_type`    | VARCHAR   | Tipe model (`User`)                   |                     |
| `model_id`      | BIGINT    | ID model                              | FK → `users.id`     |
| `created_at`    | TIMESTAMP |                                       |                     |
| **PK**          | Composite | (`permission_id`, `model_type`, `model_id`) | PK           |

---
### Tabel: `role_has_permissions`
Tabel pivot yang menghubungkan role dengan permission.

**Fungsi-fungsi penting:**
- Semua user yang punya role tertentu otomatis mewarisi permission yang terhubung
- Ini yang membuat sistem hak akses bisa “bertingkat” dan fleksibel

| Kolom           | Tipe Data | Keterangan                          | Sifat Khusus        |
|-----------------|-----------|--------------------------------------|---------------------|
| `permission_id` | BIGINT    | ID permission                        | FK → `permissions.id` |
| `role_id`       | BIGINT    | ID role                              | FK → `roles.id`     |
| `created_at`    | TIMESTAMP |                                      |                     |
| **PK**          | Composite | (`permission_id`, `role_id`)         | PK                  |

---
### Tabel: `backups`
Tabel ini mencatat data backup yang dibuat secara otomatis maupun manual.

**Fungsi-fungsi penting:**
- Menyimpan path file backup `.sql`, `.zip`, atau data absensi mingguan
- Menunjukkan apakah file tersebut sudah pernah digunakan untuk restore
- Meningkatkan keamanan data dan pemulihan bencana (disaster recovery)

| Kolom         | Tipe Data | Keterangan                                  | Sifat Khusus |
|---------------|-----------|----------------------------------------------|--------------|
| `id`          | BIGINT    | ID unik backup                              | PK           |
| `file_path`   | VARCHAR   | Lokasi file backup disimpan (ZIP, SQL, dsb) |              |
| `restored`    | BOOLEAN   | Status apakah backup ini pernah di-restore  |              |
| `created_at`  | TIMESTAMP | Tanggal backup dibuat                       |              |

---
### Tabel: `settings`
Tabel ini menyimpan konfigurasi sistem dalam bentuk **key-value** yang fleksibel.

**Fungsi-fungsi penting:**
- Menyimpan jam operasional, API WhatsApp, countdown, jam aktif alat, dll
- Konfigurasi dapat diubah dari panel admin Filament tanpa ubah kode
- Bisa menyimpan data sederhana atau kompleks (misalnya IP dalam bentuk JSON)


| Kolom         | Tipe Data | Keterangan                                                 | Sifat Khusus |
|---------------|-----------|-------------------------------------------------------------|--------------|
| `id`          | BIGINT    | ID konfigurasi                                             | PK           |
| `key`         | VARCHAR   | Nama pengaturan (misalnya: `wa_api_key`, `device_ips`)     | UNIQUE       |
| `value`       | TEXT      | Nilai pengaturan (string atau JSON)                        |              |
| `created_at`  | TIMESTAMP | Waktu konfigurasi disimpan                                 |              |

---
### Tabel: `devices`

Tabel ini menyimpan daftar perangkat fingerprint yang terhubung ke sistem.

**Fungsi-fungsi penting:**
- Mengatur IP perangkat fingerprint secara fleksibel
- Menyimpan lokasi fisik tiap alat (misalnya: Ruang XI RPL, Lab TKJ)
- Menentukan perangkat mana yang aktif/tidak aktif
- Membantu jika ada alat lebih dari satu di sekolah

| Kolom         | Tipe Data | Keterangan                                     | Sifat Khusus |
|---------------|-----------|-------------------------------------------------|--------------|
| `id`          | BIGINT    | ID perangkat fingerprint                        | PK           |
| `name`        | VARCHAR   | Nama perangkat (misal: Ruang A, Ruang Lab)     |              |
| `ip_address`  | VARCHAR   | Alamat IP alat fingerprint                     | UNIQUE       |
| `location`    | VARCHAR   | Lokasi fisik alat                              |              |
| `is_active`   | BOOLEAN   | Status perangkat (aktif/nonaktif)              |              |
| `created_at`  | TIMESTAMP | Tanggal data dibuat                            |              |

---
### Tabel: `attendance_rules`
Tabel ini menyimpan aturan waktu masuk dan pulang dalam bentuk rentang waktu.

**Fungsi-fungsi penting:**
- Digunakan sistem untuk menentukan status hadir, terlambat, tidak hadir
- Jam masuk dan jam pulang tidak ditulis dalam waktu tetap, tapi dalam **range**
- Bisa digunakan untuk membuat pengaturan waktu berbeda untuk shift atau kelas tertentu
- Sistem bisa menyesuaikan logika absensinya berdasarkan aturan ini


| Kolom           | Tipe Data | Keterangan                                              | Sifat Khusus |
|------------------|-----------|----------------------------------------------------------|--------------|
| `id`             | BIGINT    | ID aturan absensi                                       | PK           |
| `time_in_start`  | TIME      | Batas awal absensi masuk (contoh: 06:30)                |              |
| `time_in_end`    | TIME      | Batas akhir absensi masuk (contoh: 07:30)               |              |
| `time_out_start` | TIME      | Batas awal absensi pulang (contoh: 13:00)               |              |
| `time_out_end`   | TIME      | Batas akhir absensi pulang (contoh: 15:00)              |              |
| `created_at`     | TIMESTAMP | Tanggal aturan dibuat                                   |              |
---
### Tabel: `scan_logs`

Tabel ini digunakan untuk mencatat **aktivitas setiap kali sidik jari di-scan**.

**Fungsi-fungsi penting:**
- Mencatat jejak scan sukses atau gagal
- Menyimpan waktu, tipe scan (`scan_in` atau `scan_out`), dan IP alat
- Digunakan untuk audit dan troubleshooting sistem fingerprint
- Bermanfaat jika absensi tidak tercatat dan perlu dicek manual


| Kolom         | Tipe Data | Keterangan                                              | Sifat Khusus       |
|---------------|-----------|----------------------------------------------------------|--------------------|
| `id`          | BIGINT    | ID log aktivitas scan                                   | PK                 |
| `student_id`  | BIGINT    | ID siswa yang melakukan scan                            | FK → `students.id` |
| `event_type`  | ENUM      | Jenis event: `scan_in`, `scan_out`                      | ENUM               |
| `scanned_at`  | TIMESTAMP | Waktu scan dilakukan                                    |                    |
| `device_ip`   | VARCHAR   | IP perangkat yang digunakan                             |                    |
| `result`      | ENUM      | Hasil scan: `success`, `fail`                          | ENUM               |



### 📌 ENUM yang digunakan:

- `status`: hadir, terlambat, tidak_hadir, izin
- `user_type`: student, teacher
- `event_type`: scan_in, scan_out
- `notification.type`: izin, tidak_hadir, keterlambatan, rekap
- `notification.recipient`: orang_tua, wali_kelas, kesiswaan
- `result`: success, fail



## 4. Penjelasan Relasi Antar Tabel


### 🔗 `students` ↔ `attendances`
- **Relasi:** Satu siswa dapat memiliki banyak catatan absensi.
- **Koneksi melalui:** `attendances.user_id` + `user_type = 'student'`
- **Fungsi:** Menghubungkan identitas siswa ke histori kehadiran harian.

---

### 🔗 `teachers` ↔ `attendances`
- **Relasi:** Satu guru juga dapat memiliki catatan absensi.
- **Koneksi melalui:** `attendances.user_id` + `user_type = 'teacher'`
- **Fungsi:** Mendukung sistem absensi fingerprint untuk guru.

---

### 🔗 `students` ↔ `student_leave_requests`
- **Relasi:** Satu siswa bisa mengajukan banyak izin.
- **Koneksi melalui:** `student_leave_requests.student_id`
- **Fungsi:** Menyimpan data izin sebagai pengecualian dari kehadiran reguler.

---

### 🔗 `students` ↔ `notifications`
- **Relasi:** Satu siswa bisa memiliki banyak notifikasi.
- **Koneksi melalui:** `notifications.student_id`
- **Fungsi:** Menyimpan riwayat pesan WA yang dikirim oleh sistem.

---

### 🔗 `students` ↔ `discipline_rankings`
- **Relasi:** Satu siswa memiliki satu rekap per bulan.
- **Koneksi melalui:** `discipline_rankings.student_id`
- **Fungsi:** Menilai kedisiplinan bulanan berdasarkan data kehadiran.

---

### 🔗 `students` ↔ `scan_logs`
- **Relasi:** Satu siswa bisa memiliki banyak log scan fingerprint.
- **Koneksi melalui:** `scan_logs.student_id`
- **Fungsi:** Audit aktivitas scan untuk validasi dan troubleshooting.

---

### 🔗 `users` ↔ `roles`, `permissions`
- **Relasi:**
  - `model_has_roles`: menghubungkan user ke role
  - `model_has_permissions`: menghubungkan user ke permission langsung
  - `role_has_permissions`: menghubungkan role ke daftar permission
- **Fungsi:** Menyusun sistem hak akses berbasis peran.

---

### 🔗 `devices` ↔ `attendances` / `scan_logs`
- **Relasi tidak langsung:** melalui kolom `device_ip`
- **Fungsi:** Mengetahui aktivitas scan berasal dari perangkat mana.

---

### 🔗 `attendance_rules`, `settings`, `backups`
- **Tidak memiliki FK langsung**, namun:
  - `attendance_rules`: dipakai untuk menentukan status kehadiran otomatis
  - `settings`: menyimpan konfigurasi sistem (jam, WA API, countdown, dsb)
  - `backups`: mencatat file cadangan yang disiapkan untuk restore otomatis

---



[← Sebelumnya: Fitur Aplikasi](02-fitur-aplikasi.md) | [Selanjutnya → Alur Aplikasi](04-alur-aplikasi.md)
