# Laravel Vue CMS

Website dan panel admin CMS untuk LSP Abdi Nusantara (lembaga sertifikasi profesi). Website publik menampilkan
slide, skema sertifikasi, alur sertifikasi, berita, dan profil lembaga. Panel admin mengelola
berita, kategori, slide beranda, halaman Tentang, pengguna, peran, dan pengaturan sistem.

**Status:** isi konten saat ini adalah **data contoh** dari `app/Support/Demo/DemoContent.php`.
Perubahan di panel admin belum tersimpan ke database. Ganti dengan data sungguhan sebelum
diluncurkan.

Stack: Laravel 13, Inertia 3, Vue 3, TypeScript, Tailwind CSS 4, Laravel Fortify (login, 2FA,
passkey di pengaturan akun), SQLite untuk pengembangan.

## Menjalankan di komputer lokal

Prasyarat: PHP 8.3 atau lebih baru, Composer, dan Node.js (versi LTS).

```bash
composer setup            # install dependensi, buat .env, kunci aplikasi, migrasi, build aset
php artisan db:seed       # membuat akun admin (lihat bagian di bawah)
composer dev              # server Laravel + Vite
```

Buka `http://localhost:8000`. Tanpa `composer dev`, jalankan `php artisan serve` lalu
`npm run dev` di terminal terpisah (atau `npm run build` sekali untuk aset produksi).

## Masuk ke panel admin

1. Buka `http://localhost:8000/login`. Dari website, tombol **Masuk** di pojok kanan atas
   menuju halaman yang sama.
2. Isi kredensial akun seed:

    | Kolom      | Nilai               |
    | ---------- | ------------------- |
    | Email      | `admin@example.com` |
    | Kata sandi | `password`          |

3. Setelah masuk Anda diarahkan ke **Dashboard** (`/dashboard`). Menu panel ada di bilah atas:
   Dashboard, CMS, Master Data, Sistem.

Akun ini dibuat oleh `database/seeders/DatabaseSeeder.php` dan hanya untuk pengembangan lokal.
**Ganti kata sandi atau hapus akun ini sebelum aplikasi bisa diakses dari internet.** Jalankan
`php artisan db:seed` hanya sekali, karena menjalankannya lagi mencoba membuat email yang sama.

Halaman login memvalidasi isian di browser (zod) sebelum dikirim. Autentikasi dua faktor dan
passkey diatur dari **Pengaturan akun > Keamanan** setelah masuk.

Lupa kata sandi: pakai tautan "Lupa kata sandi?" di halaman login. Email keluar memakai driver
yang diatur di `.env` (default `log`, jadi tautan reset muncul di `storage/logs/laravel.log`).

### Catatan keamanan

- Registrasi publik aktif (`Features::registration()` di `config/fortify.php`) dan semua
  pengguna yang sudah memverifikasi email bisa membuka seluruh halaman admin. Nonaktifkan
  registrasi atau tambahkan pembatasan peran sebelum produksi.
- `APP_DEBUG=true` di `.env.example` hanya untuk pengembangan.

## Struktur singkat

| Lokasi                                   | Isi                                                             |
| ---------------------------------------- | --------------------------------------------------------------- |
| `routes/website.php`, `routes/admin.php` | Rute website publik dan panel admin                             |
| `app/Http/Controllers/Website`, `Admin`  | Controller yang merender halaman Inertia                        |
| `app/Support/Demo/DemoContent.php`       | Semua data contoh (berita, skema, slide, pengguna, log)         |
| `resources/js/pages`                     | Halaman Vue: `website/`, `admin/`, `auth/`, `settings/`         |
| `resources/js/layouts`                   | Layout website, panel admin, auth, dan pengaturan               |
| `resources/js/components`                | `ui/` (primitif), `common/` (tabel, form), `website/`, `admin/` |
| `resources/css/app.css`                  | Token warna, bentuk, dan gerak                                  |
| `DESIGN.md`                              | Fondasi desain: palet, tipografi, tata letak, gerak             |

## Perintah yang sering dipakai

```bash
npm run build                          # build aset produksi
npm run check                          # lint dan format frontend
npm run types:check                    # type check Vue/TypeScript
php artisan test                       # tes backend
composer test                          # lint PHP, PHPStan, lalu tes
php artisan wayfinder:generate --with-form   # buat ulang helper rute TypeScript
```

Setelah menambah atau mengubah rute, jalankan perintah `wayfinder:generate` di atas dengan
`--with-form`. Tanpa opsi itu, pemanggilan `.form()` di komponen gagal type check.

## Desain

Semua keputusan visual ada di [DESIGN.md](DESIGN.md). Aturan ringkasnya: palet biru dan emas dari
logo lembaga (satu aksen emas), font Plus Jakarta Sans, motif tepi ombak, panel admin dengan sidebar
shadcn-vue, dan tanpa angka atau klaim yang tidak punya sumber. Gambar logo belum ada di repositori:
letakkan berkasnya di `public/` lalu ganti isi `resources/js/components/AppLogo.vue`.
