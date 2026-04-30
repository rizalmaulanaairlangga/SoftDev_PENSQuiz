# Changelog

Semua perubahan penting pada proyek **PENSQuiz** akan didokumentasikan di file ini.

Format pencatatan ini didasarkan pada standar [Keep a Changelog](https://keepachangelog.com/en/1.0.0/), dan proyek ini menganut [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [Unreleased]

### Added (Ditambahkan)
- Implementasi alur kuiz SPA‑like (Play → Check → Sure‑Submit → Result → Review‑Grid → Review‑Detail).
- Logika pemilihan jawaban multiple dengan batas maksimal sesuai `correct_count`.
- Fitur timer dengan indikator warna (biru, kuning, merah) dan auto‑submit saat waktu habis.
- Penyimpanan jawaban secara asinkron via endpoint `attempt.saveAnswer`.
- UI review menampilkan warna hijau untuk jawaban benar dan merah untuk salah.
- Modal retake dengan konfirmasi dan tombol start quiz.
- Penghapusan menu *Courses* pada header.
- Penyesuaian desain gradient, spasi vertikal, dan styling tombol.

### Changed (Diubah)
- Memperbaiki bug `stdClass::$id_quiz` menjadi `quiz_id` pada form retake.
- Memperbarui logika toggle multiple‑answer sehingga pilihan pertama otomatis dilepas ketika batas tercapai.
- Penyempurnaan tampilan header back button dengan margin lebih kecil.
- Pembaruan teks instruksi pada pilihan multiple answer untuk menampilkan `correct_count`.

### Fixed (Diperbaiki)
- Bug tampilan nomor soal dengan gradient yang tidak konsisten.
- Penggunaan tombol finish tanpa emoji centang, menggunakan garis berbentuk centang.
- Hapus notifikasi web lokal saat submit quiz.
- Perbaikan layout pada review detail sehingga heading instruksi dapat berubah dinamis.

---

## [1.0.0] - 2026-04-30

### Added
- Versi awal PENSQuiz rilis dengan seluruh alur kuiz dan UI yang stabil.

[Unreleased]: #
