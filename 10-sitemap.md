# 🧭 Sitemap Aplikasi Absensi Fingerprint

Berikut struktur navigasi halaman untuk sistem absensi fingerprint, mencakup dashboard, manajemen data, pengaturan, dan fitur khusus lainnya.

---

## 🏠 Dashboard / Beranda

- 📊 Statistik Umum
  - Total Siswa
  - Jumlah Hadir / Terlambat / Tidak Hadir Hari Ini
  - Grafik Harian / Mingguan / Bulanan
  - Peringkat Disiplin Siswa

- 📆 Rekap Kehadiran
  - Harian
  - Mingguan
  - Bulanan
  - Filter: Kelas / Status / Nama
  - Export PDF / Excel

---

## 👨‍🏫 Manajemen Data

- 🧑‍🎓 Data Siswa
  - List & Detail
  - Kalender Kehadiran
  - Tambah / Edit / Hapus

- 👨‍🏫 Data Guru
  - List & Detail
  - Kalender Kehadiran
  - Tambah / Edit / Hapus

- 📝 Data Izin
  - Izin Siswa (Google Sheets)
  - Izin Guru (Google Sheets)

- 📥 Scan Log
  - Siswa dan Guru
  - Status Scan Masuk / Keluar
  - Berhasil / Gagal

---

## 🔔 Notifikasi

- Kirim Manual WhatsApp
- Log Pengiriman
- Status Terkirim / Gagal

---

## 🛠️ Pengaturan Sistem

- ⚙️ Konfigurasi Umum
  - WhatsApp API (URL, Token, No.)
  - Jam Masuk & Pulang
  - Jadwal Backup Otomatis
  - Nomor WA Kesiswaan
  - Key:Value Settings

- 💾 Backup & Restore
  - Daftar File Backup
  - Restore Manual
  - Log Aktivitas Restore

- 📡 Perangkat Fingerprint
  - Daftar IP + Lokasi + Nama
  - Status Aktif / Nonaktif
  - Tambah / Edit / Hapus Perangkat

- 📜 Aturan Kehadiran
  - Hari / Kelas / Jam Masuk & Pulang
  - Jadwal Khusus (Ujian, Shift Jumat)

---

## 🔐 Login / Akses

- Login / Logout
- Role & Permission (Spatie)
