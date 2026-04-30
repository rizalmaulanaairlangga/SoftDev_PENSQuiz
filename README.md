# PENSQuiz

**PENSQuiz** adalah aplikasi kuis daring yang dibangun dengan **Laravel 8**, Blade, Tailwind CSS, dan vanilla JavaScript. Aplikasi ini menyediakan alur kuis “SPA‑like” (tanpa reload halaman) yang meliputi **Play**, **Check**, **Sure‑Submit**, **Result**, **Review‑Grid**, dan **Review‑Detail**. Semua logika pemilihan jawaban, timer, dan penyimpanan berlangsung secara asinkron melalui API Laravel.

---

## 📂 Struktur Proyek

```
pensquiz/
├─ app/                     # Kode bisnis Laravel (Controller, Model, Service)
│   └─ Http/Controllers/
│        └─ AttemptController.php
├─ resources/
│   ├─ views/
│   │   ├─ pages/
│   │   │   ├─ quiz/
│   │   │   │   └─ show.blade.php         # Tampilan daftar kuis
│   │   │   └─ attempt/
│   │   │       └─ play.blade.php         # Alur kuis lengkap (play‑check‑…‑review)
│   │   └─ components/
│   │       └─ auth-top-nav.blade.php    # Header navigasi
│   └─ css/
│       └─ app.css                       # Tailwind + custom utilities
├─ public/
│   └─ ...                               # Asset statis, manifest, dll.
├─ routes/
│   └─ web.php                           # Definisi route (quiz, attempt, dll.)
├─ database/
│   └─ migrations/                       # Skema DB (quiz, question, attempt, …)
├─ package.json                            # NPM scripts (Vite, Tailwind)
├─ composer.json                           # Dependensi PHP
└─ README.md                               # ← Anda sedang membacanya
```

> **Catatan:** Semua UI berada di dalam satu Blade file (`play.blade.php`) dan dikelola oleh satu state manager JavaScript, sehingga tidak ada alert browser atau reload halaman.

---

## 🚀 Panduan Memulai (Getting Started)

### 1. Prasyarat
Pastikan sistem Anda telah terpasang:

| Tool | Versi (rekomendasi) |
|------|---------------------|
| **PHP** | `8.2` atau lebih tinggi |
| **Composer** | `2.x` |
| **Node.js** | `18.x` atau lebih tinggi |
| **npm** | `9.x` |
| **Laravel Installer** | `8.x` (opsional) |
| **Git** | `2.x` |
| **Database** | MySQL 8 / MariaDB 10.6 (atau SQLite untuk dev cepat) |

### 2. Clone & Install Dependensi

```bash
# Clone repository
git clone https://github.com/your-org/pensquiz.git
cd pensquiz

# Install paket PHP
composer install

# Salin file .env contoh & sesuaikan (DB, APP_KEY, dll.)
cp .env.example .env
php artisan key:generate

# Install paket Node & compile assets
npm ci               # atau `npm install`
npm run dev          # jalankan Vite (hot‑reloading) selama development
```

### 3. Migrasi & Seed Database (opsional)

```bash
php artisan migrate          # membuat tabel
php artisan db:seed          # (jika ada data contoh) 
```

### 4. Jalankan Server Development

```bash
# Laravel development server
php artisan serve

# Vite dev server (otomatis dijalankan oleh `npm run dev`)
```

Buka browser dan kunjungi `http://127.0.0.1:8000`. Anda akan melihat halaman daftar kuis, kemudian dapat memulai alur **Play → Check → Sure‑Submit → Result → Review**.

---

## 🛠️ Pengembangan Lanjutan

| Area | Penjelasan | Lokasi File |
|------|------------|-------------|
| **Alur Kuiz (JS)** | State manager, render‑quiz, render‑review, timer | `resources/views/pages/attempt/play.blade.php` (script di bagian bawah) |
| **Routing** | Route resource untuk quiz & attempt | `routes/web.php` |
| **Controller** | Logika penyimpanan jawaban, submit, fetch‑attempt | `app/Http/Controllers/AttemptController.php` |
| **Styling** | Tailwind + custom gradient, warna status (benar = green, salah = red) | `resources/css/app.css` (atau `tailwind.config.js`) |
| **Model & Migration** | `Quiz`, `Question`, `Option`, `Attempt`, `Answer` | `app/Models/…` & `database/migrations/…` |

> **Tip:** Karena UI berada dalam satu file Blade, gunakan `view‑source` di browser bila ingin memeriksa markup yang di‑render.

---

## 📋 Aturan Kontribusi & Changelog

1. **Fork** repository, buat branch fitur (`feature/...`) atau perbaikan (`fix/...`).
2. **Commit** dengan format **Conventional Commits**:  
   - `feat: tambahkan alur review`  
   - `fix: perbaiki batas jawaban multiple`  
   - `docs: perbarui README`  
   - `style: rapikan Tailwind class`  
   - `refactor: ubah logika timer`  
3. **Pull Request**: deskripsikan perubahan, lampirkan screenshot bila UI berubah.
4. **Changelog**: setiap PR yang masuk akan secara otomatis ditambahkan ke `CHANGELOG.md` oleh skrip CI (jika di‑setup). Pastikan setiap entry mengikuti format di atas.

---

## 📦 Build & Deploy (opsional)

```bash
# Optimasi assets untuk production
npm run build          # Vite menghasilkan bundle minified di public/build

# Cache config, queue worker, dll.
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Deploy ke server LAMP/NGINX standar dengan `DocumentRoot` mengarah ke `public/`. Pastikan `.env` berisi `APP_ENV=production` dan `APP_DEBUG=false`.

---

## 🧪 Testing (jika tersedia)

Proyek belum memiliki suite unit/feature test lengkap, namun Anda dapat menambahkan:

```bash
# Unit test (PHPUnit)
php artisan test

# Browser test (Laravel Dusk) – bila Dusk di‑install
php artisan dusk
```

---

## 📄 Lisensi

PENSQuiz dilisensikan di bawah **MIT License** – lihat file `LICENSE` untuk detail lengkap.

---

### 🎉 Selamat mencoba!

Jika ada pertanyaan atau kesulitan, buka *issue* di GitHub atau hubungi tim pengembang melalui Slack/Discord. Happy coding!
