 # 05 - Teknologi dan Implementasi
## 📚 Daftar Isi
- [01 Pendahuluan](01-pendahuluan.md)
- [02 Fitur Aplikasi](02-fitur-aplikasi.md)
- [03 Desain Database](03-desain-database.md)
- [04 Alur Aplikasi](04-alur-aplikasi.md)
- [05 Teknologi](05-teknologi.md)
- [06 Pengembangan](06-pengembangan.md)
- [07 Pengujian](07-pengujian.md)
- [08 Deploy](08-deploy.md)


Dokumen ini menjelaskan stack teknologi, arsitektur, integrasi eksternal, serta sistem keamanan dan rekomendasi deployment untuk sistem absensi fingerprint.

---

## 1. Stack Teknologi yang Digunakan

| Komponen               | Teknologi                   |
| ---------------------- | --------------------------- |
| Backend                | Laravel 12                  |
| Admin Panel            | Filament v3                 |
| Database               | MySQL / MariaDB             |
| Cronjob Scheduler      | Laravel Task Scheduler      |
| Notifikasi Otomatis    | WhatsApp API (pihak ketiga) |
| Google Form + Sheets   | Google Sheets API           |
| UI Template (jika ada) | Filament UI & Tailwind CSS  |
| Absensi Fisik          | Fingerprint Scanner (IP)    |

---

## 2. Arsitektur Sistem

Diagram sederhana (tidak dalam gambar):

```
[Fingerprint Scanner] --(scan+id)--> [Server Laravel] -->
  ├─ Simpan data ke DB
  ├─ Tentukan status (hadir/terlambat/dll)
  ├─ Kirim notifikasi WA
  └─ Tampilkan status ke Dashboard Admin
```

---

## 3. Ketergantungan Eksternal

* **Google Sheets API**: Digunakan untuk membaca data izin dari Google Form siswa dan guru.
* **WhatsApp API**: Untuk pengiriman notifikasi ke orang tua, wali kelas, dan kesiswaan.
* **Cronjob / Scheduler**: Untuk menjalankan proses otomatis seperti backup, rekap, notifikasi.
* **Sensor Sidik Jari**: Perangkat absensi fisik terhubung ke server melalui IP dan device\_id.

---

## 4. Keamanan dan Validasi

* **Validasi Fingerprint ID**:

  * Data unik dan dipetakan ke siswa/guru
* **Permission dan Role**:

  * Menggunakan Spatie Role & Permission untuk dashboard Filament
* **Token API**:

  * Token WA disimpan di tabel settings dan tidak hardcoded
* **Sanitasi Izin**:

  * Validasi data dari Google Sheets sebelum disimpan ke DB
* **Absensi via IP tertentu**:

  * Device hanya diizinkan akses jika IP-nya terdaftar di tabel `devices`

---

## 5. Rekomendasi Deployment

* OS: Ubuntu Server 20.04+ / Debian
* Web server: Nginx / Apache
* PHP 8.2+
* Gunakan Supervisor untuk menjalankan queue worker dan cron
* Setup cron untuk Laravel Scheduler (`* * * * * php artisan schedule:run`)
* Backup otomatis disimpan mingguan, konfigurasi di tabel `settings`
* Simpan log WA dan log error ke storage/logs untuk audit

---

> Dokumentasi ini akan terus diperbarui seiring pengembangan sistem.


---

[← Sebelumnya](04-alur-aplikasi.md) | [Selanjutnya →](06-pengembangan.md)