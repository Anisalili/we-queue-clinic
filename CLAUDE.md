# Web Queue Clinic - Business Flow Documentation

## Project Overview
Sistem manajemen booking dan antrian klinik untuk **single dokter** dengan fitur booking online, walk-in registration, dan monitoring real-time.

---

## 1. Actors & Roles

### Owner (Dokter)
- Konfigurasi sistem (jadwal default, kuota slot)
- Override jadwal harian (ubah jam, kuota, atau tutup hari)
- Monitoring dashboard real-time
- Akses laporan & statistik
- Kontrol penuh terhadap sistem

### Admin (Resepsionis)
- Registrasi pasien walk-in
- Input data pasien ke sistem
- Pilih kategori pasien (BPJS/Umum) saat input
- Update status antrian pasien
- Konfirmasi kehadiran pasien (via telpon untuk no-show)
- Batalkan/hapus booking pasien yang tidak datang
- Kirim notifikasi manual jika diperlukan

### Pasien
- Booking online mandiri
- Pilih kategori (BPJS/Umum) saat booking
- Cek status antrian
- Lihat riwayat kunjungan
- Batalkan booking sendiri (minimal 2 jam sebelum jadwal)

---

## 2. Status Antrian

```
booking → menunggu → berlangsung → selesai
              ↘ batal
```

| Status | Deskripsi | Trigger |
|--------|-----------|---------|
| **booking** | Pasien sudah melakukan booking online tapi belum check-in/hadir | Pasien submit booking online |
| **menunggu** | Pasien sudah hadir di klinik, menunggu giliran dipanggil | Check-in oleh admin atau auto-check jika walk-in |
| **berlangsung** | Pasien sedang dalam proses pelayanan dokter | Admin/Owner mulai pelayanan |
| **selesai** | Pasien telah selesai dilayani | Admin/Owner selesai pelayanan |
| **batal** | Booking dibatalkan, slot dikembalikan ke sistem | Pasien cancel manual, admin cancel, atau auto-cancel (no-show) |

### Status Transition Rules:
- `booking` → `menunggu`: Ketika pasien check-in/hadir
- `booking` → `batal`: Pasien cancel (min. 2 jam sebelum), admin cancel, atau auto-cancel jika jadwal hari itu habis & pasien tidak datang
- `menunggu` → `berlangsung`: Admin/Owner mulai layani pasien
- `menunggu` → `batal`: Admin batalkan (pasien tidak jadi/pulang)
- `berlangsung` → `selesai`: Pelayanan selesai
- `berlangsung` → `batal`: Jarang terjadi, tapi bisa jika pasien tiba-tiba cancel saat pelayanan

---

## 3. Slot Management

### Konsep Slot:
- **1 slot = 1 pasien**
- Slot dikonfigurasi per hari oleh Owner
- Contoh: Jika kuota slot = 30, maka maksimal 30 pasien per hari
- Slot digunakan oleh **online booking** dan **walk-in patient** dari pool yang sama

### Slot Availability Logic:
1. **Ketika booking baru masuk**: Slot berkurang 1
2. **Ketika pasien cancel**: Slot otomatis kembali (baik cancel manual atau no-show)
3. **Ketika slot penuh**: Sistem menolak booking baru dengan pesan "Slot penuh, silakan pilih tanggal lain"
4. **Real-time update**: Jika ada cancel, slot langsung tersedia untuk booking berikutnya

### Override Slot:
- Owner/Admin bisa override kuota slot untuk tanggal tertentu
- Contoh: Hari biasa 30 slot, tapi tanggal 15 diubah jadi 20 slot karena jadwal lebih singkat

---

## 4. Patient Categories (Kategori Pasien)

### Kategori Pasien:
Sistem mendukung 2 kategori pasien:
- **BPJS**: Pasien dengan jaminan BPJS Kesehatan
- **Umum**: Pasien dengan pembayaran mandiri (tunai/asuransi swasta)

### Aturan Kategori:
1. **Antrian digabung** - BPJS dan Umum menggunakan **satu antrian yang sama**
2. **FIFO tetap berlaku** - Tidak ada prioritas khusus, semua pasien dilayani sesuai urutan booking
3. **Kategori hanya untuk tracking** - Berguna untuk:
   - Laporan statistik (berapa pasien BPJS vs Umum per hari)
   - Administrasi keuangan (pembayaran berbeda)
   - Filter di dashboard admin/owner

### Pemilihan Kategori:
- **Saat booking online**: Pasien pilih kategori (BPJS/Umum) via dropdown/radio button
- **Saat walk-in**: Admin pilih kategori saat input data pasien
- **Tidak bisa diubah** setelah booking dikonfirmasi (kecuali oleh Admin/Owner)

---

## 5. Queue Number (Nomor Antrian)

### Penentuan Nomor Antrian:
- **Berdasarkan urutan booking** (FIFO - First In First Out)
- **BPJS dan Umum gabung dalam 1 antrian** - tidak ada pemisahan nomor
- Sistem generate otomatis saat booking dikonfirmasi
- Format: `001, 002, 003, ...` (increment per hari, reset setiap hari baru)

### Contoh Skenario:
1. Pasien A (BPJS) booking online jam 08:00 untuk tanggal 10 Nov → dapat nomor antrian **001**
2. Pasien B (Umum) booking online jam 09:00 untuk tanggal 10 Nov → dapat nomor antrian **002**
3. Pasien C (BPJS) walk-in jam 10:00 (admin input) untuk tanggal 10 Nov → dapat nomor antrian **003**
4. Jika Pasien B cancel → Slot kembali, tapi nomor antrian **002 tetap kosong** (tidak diisi ulang)
5. Pasien D (Umum) booking setelah Pasien B cancel → dapat nomor antrian **004** (bukan 002)

**Catatan**: Kategori BPJS/Umum **tidak mempengaruhi** nomor antrian. Semua pasien diurutkan berdasarkan waktu booking saja.

### Walk-in Patient Priority:
- Walk-in patient diinputkan admin **sesuai antrian di sistem**
- Jika pasien dengan antrian lebih awal belum hadir, bisa di-skip sementara
- Contoh: Antrian 001 booking tapi belum datang, antrian 002 (walk-in) sudah hadir → 002 bisa dilayani duluan
- **PENTING**: Status pasien 001 tetap `booking` sampai:
  - Pasien hadir → ubah jadi `menunggu`
  - Jadwal hari itu habis & tidak datang → auto-cancel jadi `batal`
  - Admin konfirmasi via telpon → jika tidak ada kabar, admin hapus manual

---

## 6. Booking Rules & Constraints

### Booking Time Range:
- **Maksimal 7 hari ke depan** dari hari ini
- **Same-day booking**: Diperbolehkan selama:
  - Slot masih tersedia
  - Booking pertama pasien sudah selesai (tidak boleh 2 booking bersamaan)

### Booking Restrictions:
1. **Satu pasien tidak boleh punya 2 booking aktif bersamaan**
   - Harus tunggu booking pertama selesai baru bisa booking lagi
   - Cek: Apakah pasien punya status `booking` atau `menunggu` yang masih aktif?
   
2. **Booking tergantung slot availability**
   - Tidak ada cut-off time (boleh booking sampai slot penuh)
   - Jika slot penuh, booking otomatis ditolak

3. **Cancellation deadline**: Minimal 2 jam sebelum jadwal booking
   - Contoh: Booking jam 14:00, pasien bisa cancel sampai jam 12:00
   - Setelah deadline, pasien tidak bisa cancel sendiri (harus hubungi admin)

---

## 7. Business Flow - Step by Step

### A. Owner Setup / Override

#### Initial Configuration (One-time Setup):
1. Owner login ke sistem
2. Set **jadwal default praktik**:
   - Hari operasional (Senin-Sabtu)
   - Jam praktik (contoh: 08:00 - 15:00)
   - Kuota slot default per hari (contoh: 30 pasien)
3. Sistem menyimpan konfigurasi sebagai template

#### Daily Override (Optional):
1. Owner/Admin bisa override untuk tanggal tertentu:
   - Ubah jam praktik (contoh: tanggal 15 Nov tutup jam 12:00)
   - Ubah maksimal slot (contoh: dari 30 jadi 20)
   - Tutup hari (holiday/cuti dokter)
2. Sistem menyimpan override sebagai **record harian** di database
3. Override ini mempengaruhi:
   - Slot availability untuk booking
   - Tampilan kalender di halaman pasien

**Database Logic**:
```
Cek jadwal untuk tanggal X:
- Jika ada override → gunakan override
- Jika tidak ada → gunakan jadwal default
```

---

### B. Pasien Booking Online

#### Step-by-step Flow:

1. **Login/Registrasi**:
   - Pasien baru → registrasi (input data lengkap)
   - Pasien lama → login dengan credentials

2. **Pilih Kategori Pasien**:
   - Sistem tampilkan pilihan:
     - ○ BPJS
     - ○ Umum
   - Pasien pilih salah satu (mandatory field)
   - Info text: "Kategori ini akan digunakan untuk administrasi. Antrian BPJS dan Umum digabung."

3. **Pilih Tanggal**:
   - Sistem tampilkan kalender 7 hari ke depan
   - Tanggal yang **tidak tersedia** (merah/disabled):
     - Hari libur (override oleh Owner)
     - Slot sudah penuh
     - Hari sudah lewat
   - Tanggal yang **tersedia** (hijau/enabled):
     - Ada slot available
     - Sesuai jadwal praktik

4. **Validasi Booking**:
   - **Cek 1**: Apakah pasien punya booking aktif?
     - Jika ada status `booking` atau `menunggu` → Tolak, tampilkan pesan "Anda masih punya booking aktif, silakan selesaikan dulu"
   - **Cek 2**: Apakah slot masih tersedia?
     - Query: `COUNT(bookings WHERE date = X AND status != 'batal') < max_slot`
     - Jika penuh → Tolak, tampilkan pesan "Slot penuh untuk tanggal ini"
   - **Cek 3**: Apakah tanggal dalam range 7 hari?
     - Jika lebih dari 7 hari → Tolak

5. **Booking Diterima**:
   - Generate **nomor antrian otomatis** (increment dari booking terakhir di tanggal tersebut)
   - Set status = `booking`
   - Simpan data booking ke database (termasuk kategori BPJS/Umum)
   - Kurangi slot available: `available_slots - 1`

6. **Kirim Notifikasi WA**:
   - Trigger notifikasi otomatis:
     - **Booking confirmation**: "Booking Anda untuk tanggal X, nomor antrian XXX berhasil. Harap datang tepat waktu."
   - Simpan log notifikasi

7. **Tampilan Konfirmasi**:
   - Halaman sukses dengan detail:
     - Nomor booking
     - Tanggal booking
     - Kategori pasien (BPJS/Umum)
     - Nomor antrian
     - Estimasi waktu pelayanan (optional)
     - Tombol: "Lihat Status" dan "Cancel Booking"

---

### C. Admin / Walk-In Patient

#### Skenario: Pasien datang langsung tanpa booking

1. **Pasien Tiba di Klinik**:
   - Admin sambut pasien

2. **Cek Status Pasien**:
   - **Pasien sudah booking online?**
     - Jika ya → Cukup ubah status dari `booking` ke `menunggu` (check-in)
     - Jika tidak → Lanjut proses walk-in
   
3. **Input Data Pasien**:
   - **Pasien baru**:
     - Buat profil baru
     - Input data lengkap (nama, no HP, alamat, dll)
     - Simpan ke database
   - **Pasien lama**:
     - Cari di database (by nama atau no HP)
     - Pilih profil pasien

4. **Pilih Kategori Pasien**:
   - Admin pilih kategori:
     - ○ BPJS
     - ○ Umum
   - Wajib dipilih sebelum lanjut (mandatory field)

5. **Cek Slot Availability**:
   - Query: `COUNT(bookings WHERE date = TODAY AND status != 'batal') < max_slot`
   - Jika slot penuh → Informasikan ke pasien, tawarkan reschedule
   - Jika slot tersedia → Lanjut

6. **Generate Antrian**:
   - Generate nomor antrian otomatis (increment dari antrian terakhir hari ini)
   - Set status = `menunggu` (karena pasien sudah hadir)
   - Simpan booking record (termasuk kategori BPJS/Umum)
   - Kurangi slot: `available_slots - 1`

7. **Konfirmasi**:
   - Print/tampilkan nomor antrian untuk pasien
   - Pasien menunggu di ruang tunggu

**Note**: Walk-in patient langsung dapat status `menunggu` (bukan `booking`) karena mereka sudah hadir.

---

### D. Pelayanan Pasien

#### Flow Pelayanan:

1. **Pemanggilan Pasien**:
   - Admin/Owner lihat daftar antrian dengan status `menunggu`
   - Panggil pasien sesuai nomor antrian (FIFO)
   - **Jika pasien nomor lebih awal belum hadir**:
     - Bisa skip sementara, panggil nomor berikutnya
     - Pasien yang di-skip tetap di antrian (status tetap `menunggu` atau `booking`)

2. **Mulai Pelayanan**:
   - Admin/Owner klik tombol "Mulai Pelayanan" di sistem
   - Status berubah: `menunggu` → `berlangsung`
   - Timer mulai (optional, untuk tracking durasi pelayanan)
   - Display di layar ruang tunggu (optional): "Nomor XXX sedang dilayani"

3. **Proses Pelayanan**:
   - Dokter melayani pasien (pemeriksaan, konsultasi, dll)
   - Sistem tidak ikut campur proses medis (hanya tracking status)

4. **Selesai Pelayanan**:
   - Admin/Owner klik tombol "Selesai"
   - Status berubah: `berlangsung` → `selesai`
   - Timer stop (catat durasi pelayanan untuk statistik)
   - Slot **tidak** kembali (karena pasien sudah dilayani)

5. **Pasien Batal di Tengah Jalan** (Edge case):
   - Jika pasien tiba-tiba batal saat sudah `berlangsung`:
     - Admin klik "Batalkan"
     - Status berubah: `berlangsung` → `batal`
     - Slot **kembali** available (karena pasien tidak jadi dilayani)

---

### E. Pembatalan Booking

#### Skenario 1: Pasien Cancel Sendiri (Self-Service)

1. **Pasien Login ke Sistem**:
   - Lihat halaman "Booking Saya"
   - Tampil daftar booking aktif (status `booking`)

2. **Klik "Cancel Booking"**:
   - Sistem cek deadline: `booking_time - current_time > 2 hours`
   - **Jika masih dalam deadline** (> 2 jam):
     - Tampilkan konfirmasi: "Yakin ingin membatalkan?"
     - Pasien konfirmasi
     - Status berubah: `booking` → `batal`
     - Slot kembali: `available_slots + 1`
     - Kirim notifikasi WA: "Booking Anda untuk tanggal X telah dibatalkan"
   - **Jika sudah lewat deadline** (< 2 jam):
     - Tampilkan pesan: "Tidak bisa cancel, silakan hubungi admin"
     - Tombol "Cancel" disabled

---

#### Skenario 2: Admin Cancel (Manual)

**Situasi**: Pasien hubungi klinik untuk cancel, atau admin perlu cancel atas permintaan dokter

1. **Admin Login ke Dashboard**:
   - Lihat daftar booking hari ini atau tanggal tertentu

2. **Cari Pasien**:
   - Filter by nama/nomor antrian/tanggal

3. **Klik "Batalkan Booking"**:
   - Tampilkan konfirmasi + alasan pembatalan (optional)
   - Admin konfirmasi
   - Status berubah: `booking`/`menunggu` → `batal`
   - Slot kembali: `available_slots + 1`
   - (Optional) Kirim notifikasi WA ke pasien: "Booking Anda telah dibatalkan oleh admin"

---

#### Skenario 3: No-Show Patient (Pasien Tidak Datang)

**Situasi**: Pasien booking online tapi tidak datang di hari H

1. **Deteksi No-Show**:
   - Sistem cek di akhir hari praktik (contoh: jam 15:00)
   - Query: `SELECT * FROM bookings WHERE date = TODAY AND status = 'booking'`
   - Pasien dengan status `booking` = belum check-in = no-show

2. **Admin Konfirmasi via Telpon**:
   - Admin telepon pasien untuk konfirmasi:
     - **Pasien confirm akan datang terlambat**:
       - Biarkan status tetap `booking`
       - Pasien masih bisa dilayani jika datang (fleksibel)
     - **Pasien confirm tidak jadi datang**:
       - Admin ubah status: `booking` → `batal`
       - Slot kembali
     - **Tidak ada kabar / tidak diangkat**:
       - Admin klik "Hapus Booking" atau "Cancel No-Show"
       - Status: `booking` → `batal`
       - Slot kembali

3. **Auto-Cancel (Optional)**:
   - Jika tidak ada konfirmasi dari admin, sistem bisa **auto-cancel** di akhir hari:
     - Scheduled job: Jam 23:59 setiap hari
     - Update status `booking` yang belum check-in jadi `batal`
     - Slot otomatis kembali untuk besok

---

### F. Notifikasi WA (WhatsApp)

#### Teknologi:
- Gunakan layanan WhatsApp Gateway **gratis**:
  - **Fonnte** (free tier)
  - **Wablas** (free tier)
  - **WA Web API** (unofficial, via Baileys/Whatsapp-web.js)
- Integrasikan via API webhook

#### Jenis Notifikasi:

| Event | Waktu Kirim | Isi Pesan |
|-------|-------------|-----------|
| **Booking Confirmation** | Setelah booking sukses | "Hai [Nama], booking Anda untuk tanggal [Tanggal], nomor antrian [No] berhasil. Harap datang tepat waktu. Klinik [Nama Klinik]" |
| **Reminder H-1** | 1 hari sebelum booking (jam 18:00) | "Hai [Nama], reminder: Besok Anda ada jadwal di klinik jam [Jam], nomor antrian [No]. Jangan lupa ya!" |
| **Reminder H-0** | Pagi hari booking (jam 08:00) | "Hai [Nama], hari ini Anda ada jadwal di klinik jam [Jam], nomor antrian [No]. Sampai jumpa!" |
| **Giliran Hampir Tiba** (Optional) | Saat 2 antrian sebelumnya | "Hai [Nama], antrian Anda hampir tiba (nomor [No]). Mohon bersiap." |
| **Perubahan Jadwal Dokter** | Saat Owner override jadwal | "Hai [Nama], jadwal praktik dokter untuk tanggal [Tanggal] berubah menjadi [Jam Baru]. Mohon sesuaikan." |
| **Booking Cancelled** | Saat pasien/admin cancel | "Booking Anda untuk tanggal [Tanggal] telah dibatalkan. Terima kasih." |

#### Notifikasi Manual:
- Admin bisa kirim pesan custom ke pasien tertentu via dashboard
- Template pesan bisa disimpan untuk efisiensi

---

### G. Monitoring & Laporan (Owner Dashboard)

#### Real-time Dashboard:

**Today's Overview** (Hari Ini):
- Total pasien hari ini: `[Angka]`
- Breakdown status:
  - Booking: `[X]` pasien
  - Menunggu: `[X]` pasien
  - Berlangsung: `[X]` pasien (highlight)
  - Selesai: `[X]` pasien
  - Batal: `[X]` pasien
- Slot tersisa: `[X] / [Max Slot]`
- Pasien terakhir dilayani: `[Nama]`

**Queue List** (Daftar Antrian):
- Table dengan kolom:
  - Nomor Antrian
  - Nama Pasien
  - Kategori (Badge: BPJS/Umum)
  - Status
  - Waktu Booking
  - Aksi (Mulai/Selesai/Batalkan)
- Filter: 
  - By Status: Semua / Menunggu / Berlangsung / Selesai
  - By Kategori: Semua / BPJS / Umum
- Search by nama/nomor antrian

**Calendar View**:
- Kalender bulanan dengan indikator:
  - Hijau: Slot tersedia
  - Kuning: Slot hampir penuh (< 30%)
  - Merah: Slot penuh
  - Abu-abu: Hari libur / override tutup
- Klik tanggal → lihat detail booking hari itu

---

#### Laporan & Statistik:

**Filter Options**:
- Rentang tanggal (dari - sampai)
- Status (semua / selesai / batal)
- Export format (Excel / PDF)

**Report Content**:
1. **Summary Report**:
   - Total pasien dalam periode
   - Breakdown by kategori:
     - Total pasien BPJS: `[X]` pasien (`[X]%`)
     - Total pasien Umum: `[X]` pasien (`[X]%`)
   - Rata-rata pasien per hari
   - Tingkat pembatalan (cancellation rate)
   - Tingkat no-show

2. **Detailed Report**:
   - List semua booking dengan detail:
     - Tanggal, Nomor Antrian, Nama Pasien, Kategori, Status, Waktu Booking, Waktu Pelayanan
   - Filter by kategori (BPJS/Umum)
   - Export ke Excel untuk analisis lebih lanjut

3. **Performance Report** (Optional):
   - Durasi rata-rata pelayanan per pasien
   - Durasi rata-rata per kategori (BPJS vs Umum)
   - Peak hours (jam tersibuk)
   - Trend booking (grafik)
   - Statistik kategori pasien (pie chart: BPJS vs Umum)

---

## 7. Data Model (Database Schema Guidance)

### Tabel Utama:

#### `users` (Pasien, Admin, Owner)
- `id` (PK)
- `name` (varchar)
- `email` (varchar, unique)
- `phone` (varchar)
- `password` (hash)
- `role` (enum: 'patient', 'admin', 'owner')
- `address` (text)
- `date_of_birth` (date)
- `created_at`, `updated_at`

**Note**: Tidak simpan data privasi sensitif seperti KTP, rekam medis. Kategori BPJS/Umum disimpan di tabel `bookings`, bukan di `users`, karena pasien bisa menggunakan BPJS untuk kunjungan tertentu dan Umum untuk kunjungan lainnya.

---

#### `schedule_defaults` (Jadwal Default Praktik)
- `id` (PK)
- `day_of_week` (enum: 'monday', 'tuesday', ..., 'sunday')
- `start_time` (time)
- `end_time` (time)
- `max_slots` (int)
- `is_active` (boolean)
- `created_at`, `updated_at`

**Contoh Data**:
```
| day_of_week | start_time | end_time | max_slots | is_active |
|-------------|------------|----------|-----------|-----------|
| monday      | 08:00:00   | 15:00:00 | 30        | true      |
| tuesday     | 08:00:00   | 15:00:00 | 30        | true      |
| sunday      | NULL       | NULL     | 0         | false     |
```

---

#### `schedule_overrides` (Override Jadwal untuk Tanggal Tertentu)
- `id` (PK)
- `date` (date, unique)
- `start_time` (time, nullable)
- `end_time` (time, nullable)
- `max_slots` (int, nullable)
- `is_closed` (boolean, default: false)
- `reason` (text, nullable) - "Cuti", "Hari Libur Nasional", dll
- `created_at`, `updated_at`

**Logic**:
- Jika `is_closed = true` → Klinik tutup total (tidak terima booking)
- Jika `is_closed = false` → Gunakan `start_time`, `end_time`, `max_slots` yang di-override
- Jika field nullable = NULL → Gunakan nilai dari `schedule_defaults`

**Contoh Data**:
```
| date       | start_time | end_time | max_slots | is_closed | reason          |
|------------|------------|----------|-----------|-----------|-----------------|
| 2025-11-15 | 08:00:00   | 12:00:00 | 20        | false     | Jadwal singkat  |
| 2025-11-17 | NULL       | NULL     | NULL      | true      | Cuti dokter     |
```

---

#### `bookings` (Data Booking & Antrian)
- `id` (PK)
- `user_id` (FK → users.id)
- `booking_date` (date)
- `queue_number` (int) - Nomor antrian (001, 002, ...)
- `patient_category` (enum: 'bpjs', 'umum') - **FIELD BARU**
- `status` (enum: 'booking', 'menunggu', 'berlangsung', 'selesai', 'batal')
- `booking_type` (enum: 'online', 'walk-in')
- `check_in_time` (datetime, nullable) - Waktu pasien hadir
- `service_start_time` (datetime, nullable) - Waktu mulai dilayani
- `service_end_time` (datetime, nullable) - Waktu selesai dilayani
- `cancelled_at` (datetime, nullable)
- `cancellation_reason` (text, nullable)
- `created_at`, `updated_at`

**Indexes**:
- `booking_date` + `queue_number` (unique composite)
- `user_id`
- `status`
- `patient_category` - untuk filter dan laporan

**Note**: `patient_category` disimpan per booking karena pasien bisa berganti kategori antar kunjungan (misalnya kunjungan pertama BPJS, kunjungan kedua Umum).

---

#### `notifications` (Log Notifikasi WA)
- `id` (PK)
- `booking_id` (FK → bookings.id)
- `type` (enum: 'booking_confirmation', 'reminder_h1', 'reminder_h0', 'queue_alert', 'cancellation', 'schedule_change')
- `phone_number` (varchar)
- `message` (text)
- `sent_at` (datetime)
- `status` (enum: 'pending', 'sent', 'failed')
- `error_message` (text, nullable)
- `created_at`, `updated_at`

---

## 8. Key Features Checklist

### For Patients:
- [ ] Registrasi & Login
- [ ] Booking online (pilih kategori BPJS/Umum, pilih tanggal, validasi slot)
- [ ] Lihat status booking aktif (dengan info kategori)
- [ ] Batalkan booking (dengan deadline)
- [ ] Riwayat kunjungan (dengan info kategori)
- [ ] Notifikasi WA otomatis (confirmation, reminder)

### For Admin:
- [ ] Dashboard antrian real-time (dengan badge kategori BPJS/Umum)
- [ ] Registrasi pasien walk-in (pilih kategori BPJS/Umum)
- [ ] Update status pasien (menunggu → berlangsung → selesai)
- [ ] Batalkan booking pasien
- [ ] Konfirmasi no-show via telpon
- [ ] Kirim notifikasi WA manual
- [ ] Cari/filter pasien (by nama, nomor antrian, kategori)

### For Owner:
- [ ] Semua akses Admin +
- [ ] Konfigurasi jadwal default (jam, kuota slot)
- [ ] Override jadwal untuk tanggal tertentu
- [ ] Tutup hari (holiday)
- [ ] Dashboard monitoring (today's stats, calendar view)
- [ ] Laporan & statistik (breakdown BPJS vs Umum, export Excel/PDF)
- [ ] Riwayat booking lengkap (dengan filter kategori)

### System Features:
- [ ] Auto-generate nomor antrian (FIFO, gabung BPJS & Umum)
- [ ] Slot management (kurangi/tambah otomatis)
- [ ] Auto-cancel booking untuk no-show (end of day)
- [ ] Validasi booking (cek double booking, slot penuh)
- [ ] Patient category tracking (BPJS/Umum per booking)
- [ ] WhatsApp integration (via gateway gratis)
- [ ] Responsive design (mobile-friendly)
- [ ] Role-based access control (RBAC)

---

## 9. Technical Stack Recommendation

### Backend:
- **Framework**: Laravel 10/11 (sudah sesuai dengan project saat ini)
- **Database**: MySQL / PostgreSQL
- **Authentication**: Laravel Sanctum (untuk API) atau Laravel Breeze/Jetstream (untuk web)

### Frontend:
- **Blade Templates** (default Laravel) + **Alpine.js** (untuk interaktivity)
- Atau **Inertia.js + Vue 3** (jika ingin SPA)
- **Tailwind CSS** (untuk styling)

### WhatsApp Integration:
- **Fonnte** / **Wablas** (API gratis dengan limitasi)
- Atau **Baileys** (library Node.js untuk WA Web API - self-hosted)

### Job Queue:
- **Laravel Queue** (Redis/Database driver)
- Untuk scheduled tasks:
  - Reminder H-1 (cron: daily jam 18:00)
  - Reminder H-0 (cron: daily jam 08:00)
  - Auto-cancel no-show (cron: daily jam 23:59)

### Deployment:
- **Localhost**: Laravel Valet / Laragon / Docker
- **Production**: VPS (DigitalOcean, Vultr) + Nginx + MySQL

---

## 10. Development Priority (Roadmap)

### Phase 1: Core Functionality (MVP)
1. Authentication (Login/Register untuk Pasien, Admin, Owner)
2. Database schema & migrations
3. Jadwal default setup (Owner)
4. Booking online (Pasien)
5. Validasi slot & nomor antrian
6. Status update (Admin: menunggu → berlangsung → selesai)
7. Basic dashboard (list antrian hari ini)

### Phase 2: Cancellation & Notifications
1. Pasien cancel booking (self-service)
2. Admin cancel booking
3. WhatsApp integration (booking confirmation)
4. Reminder H-1 dan H-0
5. No-show handling (manual oleh admin)

### Phase 3: Monitoring & Reports
1. Owner dashboard (statistics, calendar view)
2. Laporan & export (Excel/PDF)
3. Override jadwal untuk tanggal tertentu
4. Riwayat booking (Pasien & Admin)

### Phase 4: Enhancements (Optional)
1. Auto-cancel no-show (scheduled job)
2. Notifikasi giliran hampir tiba
3. Walk-in analytics
4. Multi-language support
5. SMS notification (fallback jika WA gagal)

---

## 11. Important Notes

### Business Logic:
- **Satu pasien hanya boleh 1 booking aktif** → Cegah double booking
- **Slot bersama untuk online & walk-in** → Hindari overbooking
- **FIFO strict untuk nomor antrian** → Adil untuk semua pasien
- **Fleksibilitas untuk skip antrian** → Jika pasien belum hadir, bisa skip
- **No-show handling harus manual confirm** → Hindari auto-cancel yang merugikan pasien

### Security:
- **Role-based access**: Pasien tidak boleh akses dashboard admin
- **CSRF protection**: Laravel default sudah ada
- **Rate limiting**: Batasi API booking untuk cegah spam
- **Input validation**: Validasi semua input (tanggal, slot, dll)

### UX Considerations:
- **Mobile-first design**: Pasien mayoritas akses dari HP
- **Clear error messages**: "Slot penuh" lebih baik dari "Booking gagal"
- **Confirmation dialogs**: Setiap aksi penting (cancel, selesai) harus confirm dulu
- **Real-time updates** (optional): Gunakan Livewire/WebSocket untuk update status tanpa refresh

---

## 12. FAQ & Edge Cases

### Q: Bagaimana jika pasien booking tapi lupa cancel, kemudian tidak datang?
**A**: Admin akan konfirmasi via telpon. Jika tidak ada kabar, admin hapus manual dan slot kembali. Optional: Implementasi auto-cancel di akhir hari.

### Q: Apakah walk-in patient bisa "nyerobot" pasien yang booking online?
**A**: Tidak. Sistem menggunakan **nomor antrian FIFO**. Walk-in dapat nomor sesuai urutan input di sistem. Jika ada pasien booking lebih dulu (nomor lebih kecil) tapi belum hadir, walk-in tetap menunggu atau admin bisa skip sementara.

### Q: Bagaimana jika slot tiba-tiba penuh karena banyak walk-in?
**A**: Slot online dan walk-in menggunakan pool yang sama. Jika slot penuh (dari manapun sumbernya), sistem tolak booking baru sampai ada yang cancel.

### Q: Apakah pasien bisa lihat estimasi waktu tunggu?
**A**: (Optional feature) Bisa dihitung dari:
- Rata-rata durasi pelayanan per pasien (contoh: 15 menit)
- Jumlah antrian yang masih menunggu
- Formula: `(queue_number - current_queue) * average_duration`
- Tampilkan sebagai estimasi: "Perkiraan waktu tunggu: 45 menit"

### Q: Bagaimana jika dokter tiba-tiba tidak bisa praktik (emergency)?
**A**: Owner/Admin bisa:
1. Set override untuk hari itu: `is_closed = true`
2. Sistem auto-cancel semua booking yang masih `booking` (belum hadir)
3. Kirim notifikasi WA ke semua pasien yang terdampak
4. Tawarkan reschedule via admin

---

## 13. Success Metrics (KPI)

Track these metrics untuk evaluate sistem:
- **Booking success rate**: Persentase booking yang berhasil vs ditolak
- **Cancellation rate**: Persentase booking yang dibatalkan
- **No-show rate**: Persentase pasien yang tidak datang
- **Average wait time**: Rata-rata waktu tunggu pasien
- **Average service time**: Rata-rata durasi pelayanan (total & per kategori)
- **Daily patient count**: Jumlah pasien per hari (trend)
- **Patient category ratio**: Rasio pasien BPJS vs Umum (untuk perencanaan kapasitas)
- **Slot utilization**: Persentase slot terpakai vs total slot

---

## Appendix: Status Flow Diagram

```
┌─────────┐
│ BOOKING │ ← Online booking / Admin input walk-in (belum hadir)
└────┬────┘
     │
     ├─→ CANCEL (pasien cancel / admin cancel / auto-cancel)
     │      ↓
     │   Slot +1 (kembali)
     │
     ├─→ MENUNGGU ← Pasien check-in / Walk-in langsung
     │      │
     │      ├─→ CANCEL (pasien pulang sebelum dilayani)
     │      │      ↓
     │      │   Slot +1
     │      │
     │      └─→ BERLANGSUNG ← Dokter mulai layani
     │             │
     │             ├─→ SELESAI ← Pelayanan done
     │             │      ↓
     │             │   (Slot tidak kembali)
     │             │
     │             └─→ CANCEL (pasien cancel di tengah pelayanan)
     │                    ↓
     │                 Slot +1
     │
     └─→ (End of day) AUTO-CANCEL jika tidak datang
            ↓
         Slot +1
```

---

---

## 15. RBAC (Role-Based Access Control) Implementation

### Architecture: Permission-Based, NOT Role-Based

Sistem menggunakan **granular permission-based RBAC** menggunakan Spatie Laravel Permission. Folder dan routes **tidak** dinamai berdasarkan role, melainkan **feature-based**.

### Permission Structure

Total **36 permissions** yang di-group berdasarkan fitur:

#### Dashboard Permissions
- `view.dashboard.v1` - Patient dashboard (booking status, history)
- `view.dashboard.v2` - Admin dashboard (queue management)
- `view.dashboard.v3` - Owner dashboard (analytics, reports)

#### Booking Permissions
- `booking.create` - Buat booking baru
- `booking.view.own` - Lihat booking sendiri
- `booking.view.all` - Lihat semua booking
- `booking.cancel.own` - Cancel booking sendiri
- `booking.cancel.any` - Cancel booking siapa saja
- `booking.update` - Update booking

#### Queue Management Permissions
- `queue.view` - Lihat antrian
- `queue.manage` - Kelola antrian (mulai, selesai, skip)
- `queue.call` - Panggil pasien

#### Patient Management Permissions
- `patient.register` - Daftarkan pasien walk-in
- `patient.view.own` - Lihat data diri sendiri
- `patient.view.all` - Lihat data semua pasien
- `patient.update.own` - Update data diri sendiri
- `patient.update.any` - Update data pasien lain

#### Schedule Permissions
- `schedule.view` - Lihat jadwal
- `schedule.configure` - Konfigurasi jadwal default
- `schedule.override` - Override jadwal untuk tanggal tertentu

#### Report Permissions
- `report.view` - Lihat laporan
- `report.export` - Export laporan (Excel/PDF)
- `report.analytics` - Akses analytics & statistik

#### Notification Permissions
- `notification.send.manual` - Kirim notifikasi manual
- `notification.view.log` - Lihat log notifikasi

#### User Management Permissions (Future)
- `user.view` - Lihat daftar user
- `user.create` - Buat user baru
- `user.update` - Update user
- `user.delete` - Hapus user

---

### Role Definitions

#### 1. Patient Role
**Permissions** (6):
- `view.dashboard.v1`
- `booking.create`
- `booking.view.own`
- `booking.cancel.own`
- `patient.view.own`
- `patient.update.own`

**Access**:
- Dashboard v1 (patient view)
- Buat & cancel booking sendiri
- Lihat riwayat booking
- Update profil sendiri

---

#### 2. Admin Role
**Permissions** (13):
- `view.dashboard.v2`
- `booking.view.all`
- `booking.cancel.any`
- `booking.update`
- `queue.view`
- `queue.manage`
- `queue.call`
- `patient.register`
- `patient.view.all`
- `patient.update.any`
- `schedule.view`
- `notification.send.manual`
- `notification.view.log`

**Access**:
- Dashboard v2 (admin view)
- Kelola semua booking & antrian
- Daftarkan pasien walk-in
- Lihat & update data semua pasien
- Kirim notifikasi manual

---

#### 3. Owner Role
**Permissions**: **ALL** (36 permissions)

**Access**:
- Dashboard v3 (owner view)
- Semua akses Admin +
- Konfigurasi jadwal
- Override jadwal
- Laporan & analytics
- User management (future)

---

### Folder Structure (Feature-Based)

```
app/Http/Controllers/
├── Dashboard/
│   ├── DashboardV1Controller.php  (patient dashboard)
│   ├── DashboardV2Controller.php  (admin dashboard)
│   └── DashboardV3Controller.php  (owner dashboard)
├── Booking/
│   ├── BookingController.php
│   └── BookingCancellationController.php
├── Queue/
│   └── QueueController.php
├── Patient/
│   └── PatientController.php
├── Schedule/
│   └── ScheduleController.php
└── Report/
    └── ReportController.php

resources/views/
├── layouts/
│   └── app.blade.php (base Mazer template)
├── components/
│   └── dashboard/
│       ├── sidebar.blade.php (dynamic menu based on permissions)
│       ├── header.blade.php
│       └── footer.blade.php
├── dashboard/
│   ├── v1.blade.php (patient dashboard)
│   ├── v2.blade.php (admin dashboard)
│   └── v3.blade.php (owner dashboard)
├── booking/
│   ├── index.blade.php
│   ├── create.blade.php
│   └── mine.blade.php
├── queue/
│   └── index.blade.php
├── patient/
│   └── register.blade.php
├── schedule/
│   └── index.blade.php
└── report/
    └── index.blade.php
```

**NOTE**: Tidak ada folder `admin/`, `owner/`, atau `patient/`. Semua folder dinamai berdasarkan **fitur**.

---

### Route Implementation

Routes menggunakan **permission middleware**, bukan role middleware:

```php
// ✅ CORRECT - Permission-based
Route::get('/dashboard/v1', [DashboardV1Controller::class, 'index'])
    ->middleware('permission:view.dashboard.v1')
    ->name('dashboard.v1');

// ❌ WRONG - Role-based (jangan gunakan ini)
Route::get('/admin/dashboard', ...)
    ->middleware('role:admin');
```

### Dynamic Dashboard Redirect

Route `/dashboard` akan redirect otomatis berdasarkan permission tertinggi user:

```php
if ($user->can('view.dashboard.v3')) {
    return redirect()->route('dashboard.v3');  // Owner
} elseif ($user->can('view.dashboard.v2')) {
    return redirect()->route('dashboard.v2');  // Admin
} elseif ($user->can('view.dashboard.v1')) {
    return redirect()->route('dashboard.v1');  // Patient
}
```

---

### Sidebar Menu (Dynamic)

Sidebar menggunakan `@can` directive untuk show/hide menu berdasarkan permission:

```blade
@can('booking.create')
<li class="sidebar-item">
    <a href="{{ route('booking.create') }}">
        <i class="bi bi-calendar-plus"></i>
        <span>Buat Booking</span>
    </a>
</li>
@endcan
```

Menu akan otomatis muncul/hilang sesuai permission user yang login.

---

### Test Accounts

Gunakan akun berikut untuk testing:

| Role    | Email                  | Password | Dashboard |
|---------|------------------------|----------|-----------|
| Owner   | owner@clinic.test      | password | v3        |
| Admin   | admin@clinic.test      | password | v2        |
| Patient | patient@clinic.test    | password | v1        |

---

### Adding New Permissions (Future Development)

Jika ingin menambah permission baru:

1. **Tambahkan permission di seeder**:
   ```php
   // database/seeders/PermissionSeeder.php
   $permissions = [
       // ... existing permissions
       'appointment.reschedule',  // New permission
   ];
   ```

2. **Assign ke role yang sesuai**:
   ```php
   $adminRole->givePermissionTo('appointment.reschedule');
   ```

3. **Gunakan di route**:
   ```php
   Route::post('/booking/reschedule', ...)
       ->middleware('permission:appointment.reschedule');
   ```

4. **Gunakan di view**:
   ```blade
   @can('appointment.reschedule')
       <button>Reschedule</button>
   @endcan
   ```

---

### Adding New Roles (Future Development)

Jika ingin menambah role baru (misal: **Nurse**):

1. **Buat role di seeder**:
   ```php
   $nurseRole = Role::create(['name' => 'nurse']);
   ```

2. **Assign permissions**:
   ```php
   $nurseRole->givePermissionTo([
       'view.dashboard.v2',  // Bisa pakai dashboard admin
       'queue.view',
       'queue.call',
       'patient.view.all',
   ]);
   ```

3. **Tidak perlu buat folder/route baru** - cukup assign permission yang sudah ada!

---

**Last Updated**: 2025-11-08  
**Version**: 1.2  
**Changelog**: 
- v1.2: Menambahkan dokumentasi lengkap RBAC implementation
- v1.1: Menambahkan fitur kategori pasien (BPJS/Umum) dengan antrian gabung
- v1.0: Dokumentasi awal business flow

**Maintained by**: Development Team

---

## Notes untuk Developer:

Gunakan dokumentasi ini sebagai **single source of truth** untuk semua keputusan development. Jika ada pertanyaan atau edge case yang tidak tercakup, diskusikan dengan Owner terlebih dahulu sebelum implementasi.

**Prinsip Development**:
1. **Follow the pattern**: Gunakan best practice Laravel & clean code
2. **Keep it simple**: Jangan over-engineering
3. **User-first**: Prioritaskan UX yang baik
4. **Test thoroughly**: Semua fitur harus di-test sebelum deploy
5. **Document changes**: Update CLAUDE.md jika ada perubahan flow bisnis
6. **Permission-based, NOT role-based**: Selalu gunakan permission untuk access control
7. **Feature-based naming**: Folder/file tidak boleh dinamai dengan role (admin/owner/patient)

Happy coding!
