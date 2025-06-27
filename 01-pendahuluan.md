# 📘 Pendahuluan

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
- [1. Latar Belakang](#1-latar-belakang)
- [2. Tujuan Proyek](#2-tujuan-proyek)
- [3. Sasaran Pengguna](#3-sasaran-pengguna)
- [4. Ruang Lingkup Umum Aplikasi](#4-ruang-lingkup-umum-aplikasi)

---

## 1. Latar Belakang

Proses pencatatan kehadiran siswa dan guru di sekolah sering kali masih dilakukan secara manual menggunakan tanda tangan atau daftar absen harian. Metode ini memiliki banyak kekurangan, seperti potensi kecurangan, kehilangan data, keterlambatan rekap, dan kurangnya notifikasi kepada pihak terkait seperti wali kelas atau orang tua.

Dengan perkembangan teknologi digital, terutama di bidang otomasi dan integrasi sistem, hadir kebutuhan untuk membangun sistem **absensi digital otomatis** yang mampu:
- Mencatat kehadiran secara real-time
- Memberi notifikasi langsung ke pihak terkait
- Menyediakan data statistik untuk evaluasi

---

## 2. Tujuan Proyek

Tujuan dari proyek aplikasi Absensi STM ini adalah untuk:

- 🔒 **Meningkatkan keamanan dan keakuratan data absensi**
- 📲 **Memberikan notifikasi kehadiran otomatis melalui WhatsApp**
- 🗂️ **Menyediakan rekap kehadiran secara rutin dan otomatis**
- 👨‍🏫 **Memudahkan guru, wali kelas, dan kesiswaan dalam memantau kehadiran**
- ⏱️ **Mengurangi waktu dan beban administrasi manual**
- 🧠 **Memberikan insight (peringkat, statistik, grafik) tentang kedisiplinan siswa**

---

## 3. Sasaran Pengguna

Berikut adalah pengguna utama dari aplikasi ini dan perannya masing-masing:

| Pengguna       | Peran & Akses                                                                          |
|----------------|------------------------------------------------------------------------------------------|
| **Siswa**      | Melakukan absensi menggunakan fingerprint, melihat riwayat kehadiran pribadi.           |
| **Guru**       | Melakukan absensi, melihat riwayat pribadinya (tanpa rekap otomatis).                   |
| **Orang Tua**  | Menerima notifikasi kehadiran anak melalui WhatsApp setiap hari.                        |
| **Wali Kelas** | Menerima laporan rekap PDF otomatis dan bisa memantau absensi siswa di kelasnya.        |
| **Admin**      | Mengelola seluruh data sistem, menambahkan perangkat, mengatur waktu & backup.          |
| **Kesiswaan**  | Menerima notifikasi jika siswa sering terlambat/alpa, dan memantau grafik disiplin.     |

---

## 4. Ruang Lingkup Umum Aplikasi

Aplikasi ini mencakup berbagai modul yang saling terintegrasi untuk mendukung proses absensi secara otomatis, akurat, dan real-time. Berikut adalah ruang lingkup umum fitur yang tersedia:

- 📌 **Absensi Otomatis dengan Sidik Jari**
- 📸 **Verifikasi Foto dan Suara “Selamat Datang”**
- ⏱️ **Countdown Batas Waktu Absen** (misal: 06.00 – 09.00)
- 📊 **Dashboard Statistik Harian, Mingguan, Bulanan**
- 📆 **Kalender Kehadiran Siswa di Halaman Scan View**
- 📤 **Notifikasi WhatsApp Otomatis ke Orang Tua & Wali Kelas**
- ✅ **Rekapan PDF Otomatis via WhatsApp**
- 🧾 **Pengajuan Izin Otomatis melalui Google Form**
- 🧍‍♂️ **Manajemen Data Siswa Lengkap** (NIS, Nama, Kelas, WA Orang Tua, ID Sidik Jari, Foto)
- 📈 **Peringkat Disiplin Siswa Otomatis di Dashboard**
- ⚙️ **Pengaturan Jam Masuk/Pulang, Waktu Hidup/Mati Otomatis, Perangkat, dan API**
- 📡 **Pengaturan API WhatsApp dan Template Pesan**
- 🧠 **Notifikasi Internal jika Siswa Terlambat Lebih dari 3x**
- 🔁 **Backup dan Restore Otomatis**


---

[Selanjutnya → Fitur Aplikasi](02-fitur-aplikasi.md)
