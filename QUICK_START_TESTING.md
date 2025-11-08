# 🚀 Quick Start - Schedule Module Testing

## Start Testing in 3 Steps

### Step 1: Start Server
```bash
cd /Users/macbook/Documents/PROJECT/FULLSTACK/web-queue-clinic
php artisan serve
```

### Step 2: Login
- URL: `http://localhost:8000/login`
- Email: `owner@clinic.test`
- Password: `password`

### Step 3: Navigate to Schedule Module
- Click sidebar: **"Manajemen Jadwal"** → **"Jadwal Praktik"**
- Or direct URL: `http://localhost:8000/schedules`

---

## 5-Minute Quick Test

### ✅ Test 1: View Schedule Index (30 seconds)
**Expected to see:**
- 7 days listed (Monday - Sunday)
- Monday-Saturday: Green "Aktif" badge, time 08:00-15:00, 30 slots
- Sunday: Gray "Tutup" badge
- 1 upcoming override (2025-11-11)
- 1 upcoming holiday (2025-11-15)

---

### ✅ Test 2: Edit Default Schedule (1 minute)
1. Click **"Edit"** button on Monday
2. Modal opens
3. Change **Max Slots** from `30` to `25`
4. Click **"Simpan Perubahan"**

**Expected:**
- ✅ Green toast notification: "Jadwal Senin berhasil diupdate!"
- ✅ Table shows 25 slots for Monday

---

### ✅ Test 3: Create Override (1.5 minutes)
1. Click **"Tambah Override"** button
2. Fill form:
   - **Date**: Tomorrow's date
   - **Klinik Tutup Total**: Leave UNCHECKED
   - **Jam Mulai**: `10:00`
   - **Jam Selesai**: `14:00`
   - **Kuota Slot**: `15`
   - **Alasan**: `Testing create override`
3. Click **"Simpan Override"**

**Expected:**
- ✅ Green toast: "Override jadwal berhasil ditambahkan!"
- ✅ Redirected to override list
- ✅ New override appears with yellow "Override" badge

---

### ✅ Test 4: Delete Override (1 minute)
1. Go to **"Override Jadwal"** (sidebar submenu)
2. Click **trash icon** on any override
3. SweetAlert popup appears
4. Click **"Ya, Hapus!"**

**Expected:**
- ✅ SweetAlert2 confirmation dialog (red/blue buttons)
- ✅ Green toast: "Override jadwal berhasil dihapus!"
- ✅ Override removed from list

---

### ✅ Test 5: Create Holiday (1.5 minutes)
1. Go to **"Hari Libur"** (sidebar submenu)
2. Click **"Tambah Hari Libur"**
3. Fill form:
   - **Date**: `2025-12-25`
   - **Name**: `Hari Natal`
   - **Type**: `Hari Libur Nasional`
   - **Description**: `Libur Nasional`
4. Click **"Simpan Hari Libur"**

**Expected:**
- ✅ Green toast: "Hari libur berhasil ditambahkan!"
- ✅ Holiday appears with blue "Nasional" badge

---

## ✅ Success Criteria

All tests pass if you see:
1. ✅ All pages load without errors
2. ✅ Green toast notifications on all success actions
3. ✅ SweetAlert2 confirmations on delete actions
4. ✅ Data persists after page refresh
5. ✅ Sidebar submenu expands/collapses correctly
6. ✅ Active states highlight current page

---

## 🐛 If You Find Issues

Check:
- Browser console (F12) for JavaScript errors
- Laravel logs: `storage/logs/laravel.log`
- Network tab (F12) for failed API calls

Common issues:
- 404 errors → Check routes: `php artisan route:list --path=schedules`
- 500 errors → Check `storage/logs/laravel.log`
- Blank page → Check browser console for JS errors

---

## 📝 Report Results

After testing, report:
- ✅ Which tests passed
- ❌ Which tests failed (with screenshots/error messages)
- 📸 Screenshots of toast notifications
- 📸 Screenshots of SweetAlert2 dialogs

---

**Happy Testing! 🎉**

For detailed test scenarios, see: `SCHEDULE_MODULE_TEST.md`
