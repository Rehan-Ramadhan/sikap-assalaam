# SIKAP Assalaam — Backend

Backend untuk **SIKAP Assalaam (Sistem Informasi Kedisiplinan dan Prestasi Assalaam)**.

Backend dikembangkan menggunakan Laravel dan berfungsi sebagai REST API yang menangani autentikasi, pengelolaan data siswa, pelanggaran, prestasi, poin, pembinaan, serta data yang dibutuhkan oleh frontend.

## Teknologi

- Laravel
- PHP
- MySQL
- Composer

## Struktur Backend

```text
backend/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   └── Models/
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── routes/
├── resources/
├── tests/
└── artisan
```

## Fitur Backend

Backend akan menangani:

- Autentikasi pengguna.
- Manajemen data siswa.
- Manajemen data pelanggaran.
- Manajemen data prestasi.
- Pengelolaan poin.
- Pengelolaan status pembinaan.
- Pencarian dan filter data.
- Penyediaan data dashboard.
- Laporan.

## Instalasi

Clone repository:

```bash
git clone https://github.com/Rehan-Ramadhan/sikap-assalaam.git
```

Masuk ke folder backend:

```bash
cd sikap-assalaam/backend
```

Install dependency:

```bash
composer install
```

Salin file environment:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

Konfigurasikan database pada file `.env`, kemudian jalankan migration:

```bash
php artisan migrate
```

Jalankan server:

```bash
php artisan serve
```

Backend dapat diakses melalui:

```text
http://127.0.0.1:8000
```

## Branch

Pengembangan backend dilakukan pada branch:

```text
backend
```

Perubahan backend kemudian akan diintegrasikan ke branch `main`.

## API

API akan digunakan oleh frontend Vue.js melalui HTTP request menggunakan JSON.

Endpoint API akan didokumentasikan dan diperbarui seiring perkembangan project.

## Status

Backend masih dalam tahap pengembangan.
