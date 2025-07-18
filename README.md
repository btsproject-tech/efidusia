# Aplikasi DFidusia

Aplikasi DFidusia ini adalah sistem berbasis web untuk pengelolaan proses fidusia secara digital, dibangun dengan PHP dan framework Laravel.

## Fitur Utama

- **Pendaftaran Fidusia Online**
- **Verifikasi Dokumen Digital**
- **Tracking Proses Fidusia**
- **Notifikasi Otomatis**
- **Laporan dan Statistik**
- **Manajemen Pengguna**

## Persyaratan Sistem

- PHP 7.4
- Composer
- MySQL 8.0 atau MariaDB 10.3+
- Web Server (Nginx)
- Node.js (untuk asset compilation)

## Instalasi

1. Clone repository:
```bash
git clone https://github.com/username/aplikasi-fidusia-online.git
cd aplikasi-fidusia-online
```

2. Install dependencies:
```bash
composer install
npm install
```

3. Buat file `.env` dari template:
```bash
cp .env.example .env
```

4. Generate application key:
```bash
php artisan key:generate
```

5. Konfigurasi database di file `.env`:
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=username_db
DB_PASSWORD=password_db
```

6. Jalankan migrasi dan seeder:
```bash
php artisan migrate --seed
```

7. Compile assets:
```bash
npm run dev
```

8. Jalankan server development:
```bash
php artisan serve
```

## Struktur Proyek

```
aplikasi-fidusia-online/
├── app/                  # Logic aplikasi
│   ├── Models/           # Model Eloquent
│   ├── Http/             # Controller, Middleware
│   └── ...               # Lainnya
├── config/               # File konfigurasi
├── database/             # Migrasi dan seeder
├── public/               # Asset publik
├── resources/            # View dan asset mentah
├── routes/               # Definisi route
├── storage/              # File yang di-generate
└── tests/                # Test cases
```

## Dokumentasi API

Aplikasi menyediakan API untuk integrasi dengan sistem lain. Dokumentasi API lengkap tersedia di [API Documentation](docs/api.md).

## Kontribusi

1. Fork project
2. Buat branch fitur (`git checkout -b fitur-baru`)
3. Commit perubahan (`git commit -am 'Tambahkan fitur baru'`)
4. Push ke branch (`git push origin fitur-baru`)
5. Buat Pull Request

## Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).

## Kontak Pemilik

- Nama Pemilik: Berkat Terang Solusindo
- Email: info@berkatterangsolusindo.com

---

**Catatan untuk Pengembang Selanjutnya:**
- Dokumentasi lengkap termasuk diagram alur proses fidusia tersedia di folder `/docs`
- Untuk environment staging, gunakan branch `staging`
- Selalu update dokumentasi ketika menambah/modifikasi fitur
- Test case harus diupdate sesuai perubahan kode
