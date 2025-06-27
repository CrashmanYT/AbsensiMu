# 🧩 Fitur Aplikasi

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
- [1. Modul Fungsional](#1-modul-fungsional)
- [2. Fitur Otomatisasi](#2-fitur-otomatisasi)
- [3. Fitur Khusus Admin](#3-fitur-khusus-admin)
- [4. Hak Akses Pengguna](#4-hak-akses-pengguna)

---

## 1. Modul Fungsional

### 📘 A. Modul Data Siswa
- Data lengkap: NIS, Nama, Kelas, Jurusan, WA Orang Tua, ID Sidik Jari, Foto
- Terhubung dengan tabel absensi dan peringkat

### 📘 B. Modul Absensi Sidik Jari
- Integrasi fingerprint scanner
- Catat waktu masuk dan keluar
- Deteksi otomatis: Hadir, Terlambat, Tidak Hadir
- Countdown batas waktu absen (misal: 06:00–09:00)

### 📘 C. Modul Verifikasi Foto & Suara
- Ambil foto dari webcam saat absen → simpan ke storage
- Putar audio: “Selamat datang, [Nama Siswa]”

### 📘 D. Modul Statistik & Dashboard
- Statistik harian, mingguan, bulanan, per siswa
- Tampilan grafik (Filament Charts)
- Filter berdasarkan kelas, jurusan, tanggal, dan status

### 📘 E. Modul Peringkat Disiplin
- Hitung otomatis siswa paling rajin & sering alpa
- Tampil di dashboard peringkat disiplin

### 📘 F. Modul Izin Siswa
- Izin via Google Form tanpa login
- Laravel membaca data dari Google Sheets API
- Simpan otomatis ke database
- Bisa diverifikasi lewat panel Filament
- Kirim WA otomatis ke wali kelas

### 📘 G. Modul Rekapan Kehadiran
- Cronjob pagi kirim chat WA ke orang tua (08:00)
- Cronjob siang kirim PDF rekap ke wali kelas (13:00)

### 📘 H. Modul Notifikasi Keterlambatan
- Jika **tidak absen masuk** → status otomatis: "Tidak Hadir"
- Jika **tidak absen pulang** → status tetap: "Hadir"
- Jika lewat batas jam masuk → status: "Terlambat"
- Kirim WA ke orang tua dan PDF ke wali kelas


### 📘 I. Modul Notifikasi Internal
- Jika siswa terlambat lebih dari 3x → munculkan notifikasi ke panel admin dan kirim WA ke kesiswaan

### 📘 J. Modul Backup & Restore
- Backup database mingguan otomatis (SQL + storage)
- Restore langsung dari panel admin

### 📘 K. Modul Pengaturan Sistem
- Atur jam masuk/pulang & countdown keterlambatan
- Atur jumlah perangkat fingerprint & IP-nya
- Jadwal hidup/mati otomatis alat fingerprint
- Input API WhatsApp, endpoint, template pesan
- Tambahkan Nomor WhatsApp Kesiswaan/BK untuk notifikasi internal


---

## 2. Fitur Otomatisasi (Referensi Non-Teknis)

Fitur-fitur yang **berjalan otomatis di latar belakang**:

- 🔁 Rekapan kehadiran otomatis (pagi & siang)
- 🔁 WhatsApp blast otomatis jika tidak hadir atau izin
- 🔁 Penarikan data izin dari Google Form (Sheets API)
- 🔁 Deteksi keterlambatan & absensi pulang
- 🔁 Notifikasi internal untuk siswa yang sering telat
- 🔁 Backup database otomatis setiap minggu

---

## 3. Fitur Khusus Admin (Referensi Non-Teknis)

Fitur-fitur yang hanya dapat diakses dan dikontrol oleh **admin**:

- ⚙️ Manajemen data siswa dan guru
- ⚙️ Pengaturan jam masuk/pulang dan countdown
- ⚙️ Manajemen perangkat fingerprint (jumlah & IP)
- ⚙️ Penjadwalan hidup/mati perangkat fingerprint otomatis
- ⚙️ Input Nomor WhatsApp Kesiswaan/BK
- ⚙️ Pengaturan API WhatsApp dan pesan template
- ⚙️ Fitur backup dan restore langsung dari panel

---

## 4. Hak Akses Pengguna

| Peran        | Hak Akses Utama                                                  |
|--------------|------------------------------------------------------------------|
| **Siswa**    | Absensi fingerprint, lihat kalender kehadiran pribadi           |
| **Guru**     | Absensi fingerprint, lihat riwayat pribadinya                   |
| **Wali Kelas** | Terima rekap PDF, pantau absensi siswa kelasnya              |
| **Orang Tua**| Terima WhatsApp notifikasi hadir/tidak hadir/izin              |
| **Kesiswaan**| Terima notifikasi internal jika siswa sering terlambat/alpa    |
| **Admin**    | Kelola sistem, perangkat, data, pengaturan, backup, restore    |

---

[← Sebelumnya: Pendahuluan](01-pendahuluan.md) | [Selanjutnya → Desain Database](03-desain-database.md)
