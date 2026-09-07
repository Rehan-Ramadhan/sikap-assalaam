# SIKAP Assalaam — Frontend

Frontend untuk **SIKAP Assalaam (Sistem Informasi Kedisiplinan dan Prestasi Assalaam)**.

Frontend dikembangkan menggunakan Vue.js dan berfungsi sebagai antarmuka pengguna untuk mengakses sistem SIKAP Assalaam. Frontend terhubung dengan backend Laravel melalui REST API untuk menangani autentikasi, data siswa, pelanggaran, prestasi, pembinaan, dashboard, dan fitur lainnya.

## Teknologi

- Vue.js
- Vite
- Vue Router
- Axios
- JavaScript
- CSS
- Lucide Vue Next

## Struktur Frontend

```text
frontend/
├── public/
├── src/
│   ├── assets/
│   ├── components/
│   ├── router/
│   │   └── index.js
│   ├── utils/
│   │   └── api.js
│   ├── views/
│   │   ├── Auth/
│   │   ├── Kesiswaan/
│   │   └── Siswa/
│   ├── App.vue
│   ├── main.js
│   └── style.css
├── .env
├── package.json
└── vite.config.js
```

## Fitur Frontend

Frontend akan menyediakan:

- Halaman login.
- Autentikasi pengguna menggunakan API Laravel.
- Penyimpanan token autentikasi.
- Route protection menggunakan Vue Router.
- Dashboard untuk Kesiswaan.
- Dashboard untuk Siswa.
- Manajemen data siswa.
- Manajemen data pelanggaran.
- Manajemen data prestasi.
- Informasi pembinaan.
- Notifikasi.
- Dashboard dan laporan.

## Instalasi

Clone repository:

```bash
git clone https://github.com/Rehan-Ramadhan/sikap-assalaam.git
```

Masuk ke folder frontend:

```bash
cd sikap-assalaam/frontend
```

Install dependency:

```bash
npm install
```

## Konfigurasi Environment

Buat file `.env` di dalam folder frontend:

```text
frontend/.env
```

Tambahkan konfigurasi URL API backend:

```env
VITE_API_URL=http://127.0.0.1:8000/api
```

Pastikan backend Laravel sudah berjalan pada:

```text
http://127.0.0.1:8000
```

## Menjalankan Frontend

Jalankan development server:

```bash
npm run dev
```

Frontend biasanya dapat diakses melalui:

```text
http://localhost:5173
```

Port dapat berbeda tergantung konfigurasi atau port yang sedang digunakan.

## Koneksi API

Frontend menggunakan Axios untuk berkomunikasi dengan backend Laravel.

Konfigurasi Axios terdapat pada:

```text
src/utils/api.js
```

Axios menggunakan `VITE_API_URL` sebagai base URL API dan secara otomatis mengirimkan token autentikasi jika token tersedia di `localStorage`.

Contoh alur autentikasi:

```text
Vue Frontend
      ↓
Axios Request
      ↓
Laravel API
      ↓
Laravel Sanctum
      ↓
Response JSON
      ↓
Token disimpan di localStorage
      ↓
Vue Router
      ↓
Dashboard sesuai role pengguna
```

## Routing

Routing dikelola menggunakan Vue Router.

Konfigurasi routing terdapat pada:

```text
src/router/index.js
```

Beberapa route menggunakan authentication guard untuk memastikan pengguna yang belum login tidak dapat mengakses halaman tertentu.

Contoh:

```text
/login
/kesiswaan
/kesiswaan/siswa
/siswa
```

Pengguna akan diarahkan ke halaman berdasarkan role yang dimiliki.

## Branch

Pengembangan frontend dilakukan pada branch:

```text
frontend
```

Perubahan frontend kemudian akan diintegrasikan ke branch `main`.

## Backend

Frontend terhubung dengan backend Laravel melalui REST API.

Repository project:

```text
https://github.com/Rehan-Ramadhan/sikap-assalaam
```

Backend dijalankan dari folder:

```text
sikap-assalaam/backend
```

## Status

Frontend masih dalam tahap pengembangan.
