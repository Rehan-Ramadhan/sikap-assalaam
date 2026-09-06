# Vue 3 + Vite

This template should help get you started developing with Vue 3 in Vite. The template uses Vue 3 `<script setup>` SFCs, check out the [script setup docs](https://v3.vuejs.org/api/sfc-script-setup.html#sfc-script-setup) to learn more.

<<<<<<< Updated upstream
Learn more about IDE Support for Vue in the [Vue Docs Scaling up Guide](https://vuejs.org/guide/scaling-up/tooling.html#ide-support).
=======
Frontend dikembangkan menggunakan Vue.js dan berfungsi sebagai antarmuka pengguna untuk mengakses fitur-fitur SIKAP Assalaam.

## Teknologi

- Vue.js
- Vite
- Axios
- Vue Router
- JavaScript
- HTML
- CSS

## Struktur Frontend

```text
frontend/
├── public/
├── src/
│   ├── views/
│   │   ├── Public/
│   │   ├── Auth/
│   │   ├── Kesiswaan/
│   │   └── Siswa/
│   ├── router/
│   ├── utils/
│   ├── components/
│   ├── App.vue
│   ├── main.js
│   └── style.css
├── index.html
├── package.json
└── vite.config.js
```

## Fitur Frontend

Frontend akan menyediakan halaman untuk:

- Login.
- Dashboard Kesiswaan.
- Dashboard Siswa.
- Data siswa.
- Data pelanggaran.
- Data prestasi.
- Informasi poin.
- Riwayat kedisiplinan.
- Status pembinaan.
- Pencarian dan filter data.

## Instalasi

Clone repository:

```bash
git clone -b frontend https://github.com/Rehan-Ramadhan/sikap-assalaam.git
```

Masuk ke folder frontend:

```bash
cd sikap-assalaam/frontend
```

Install dependency:

```bash
npm install
```

Jalankan development server:

```bash
npm run dev
```

Frontend dapat diakses melalui:

```text
http://localhost:5173
```

## Koneksi ke Backend

Frontend akan berkomunikasi dengan backend Laravel melalui REST API menggunakan Axios.

Backend secara lokal berjalan pada:

```text
http://127.0.0.1:8000
```

## Branch

Pengembangan frontend dilakukan pada branch:

```text
frontend
```

Perubahan frontend kemudian akan diintegrasikan ke branch `main`.

## Status

Frontend masih dalam tahap pengembangan.
