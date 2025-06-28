# Pengaturan Aplikasi

Dokumen ini berisi daftar lengkap pengaturan sistem absensi sidik jari yang dapat dikonfigurasi melalui Panel Admin. Seluruh pengaturan disimpan dalam bentuk **key : value** di tabel `settings`.

---

## 📌 Daftar Pengaturan

| Key                         | Value Contoh                        | Deskripsi / Keterangan                                    |
| --------------------------- | ----------------------------------- | --------------------------------------------------------- |
| `backup_schedule_time`      | `0 11 * * 5`                        | Jadwal cron backup otomatis (Jumat 11:00)                 |
| `whatsapp_api_url`          | `https://api.wa.my.id/send-message` | Alamat endpoint API WhatsApp                              |
| `whatsapp_api_token`        | `secret_token`                      | Token akses ke API WhatsApp                               |
| `whatsapp_sender_number`    | `+6281234567890`                    | Nomor pengirim pesan WhatsApp                             |
| `notif_to_parents`          | `true`                              | Aktifkan notifikasi ke orang tua                          |
| `notif_to_walikelas`        | `true`                              | Kirim laporan PDF ke wali kelas                           |
| `notif_to_kesiswaan`        | `true`                              | Kirim notifikasi ke kesiswaan jika keterlambatan berulang |
| `kesiswaan_whatsapp`        | `+6281122334455`                    | Nomor WhatsApp kesiswaan                                  |
| `late_threshold_minutes`    | `15`                                | Toleransi keterlambatan dalam menit                       |
| `notif_schedule_morning`    | `08:00`                             | Waktu notifikasi pagi                                     |
| `notif_schedule_afternoon`  | `13:00`                             | Waktu notifikasi siang                                    |
| `weekly_recap_schedule`     | `0 18 * * 6`                        | Jadwal rekap mingguan (Sabtu 18:00)                       |
| `auto_disable_time`         | `14:00`                             | Waktu sistem fingerprint mati otomatis                    |
| `auto_enable_time`          | `06:00`                             | Waktu sistem fingerprint hidup otomatis                   |
| `default_attendance_status` | `tidak_hadir`                       | Status default jika tidak scan sama sekali                |
| `student_calendar_enabled`  | `true`                              | Aktifkan tampilan kalender kehadiran di scan view siswa   |

---

## 🛠️ Catatan

* Semua data disimpan dalam tabel `settings` dengan sistem key : value
* Pengaturan dapat diubah oleh admin melalui panel Filament sesuai hak akses
* Jika key tertentu tidak tersedia, sistem akan menggunakan nilai default
* Untuk pengaturan waktu (jadwal) gunakan format cron atau jam `HH:mm` sesuai konteks
