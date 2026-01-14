# Langkah Instalasi Step 2 - Setup Project di Laptop

Panduan lengkap untuk setup project TKT (Sistem Manajemen Inventory Barang) di laptop yang baru.

## Prasyarat
- PHP 8.2 atau lebih tinggi
- Node.js dan npm
- Composer
- Git
- Database (MySQL/MariaDB) atau SQLite

---

## Step 2: Setup Project

### 1. Clone Repository

Buka terminal/command prompt dan jalankan:

```bash
git clone <repository-url>
cd "TKT  edwrd"
```

### 2. Install PHP Dependencies

```bash
composer install
```

Tunggu sampai semua dependencies selesai terinstall.

### 3. Setup Environment File

```bash
# Copy file .env.example ke .env
cp .env.example .env

# Atau di Windows:
copy .env.example .env
```

Edit file `.env` dan sesuaikan konfigurasi:

```env
APP_NAME="TKT Inventory"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tkt_inventory
DB_USERNAME=root
DB_PASSWORD=

# Mail Configuration
MAIL_MAILER=log
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Setup Database

#### Pilihan A: Menggunakan MySQL/MariaDB

1. Buat database baru:
   ```sql
   CREATE DATABASE tkt_inventory;
   ```

2. Jalankan migration:
   ```bash
   php artisan migrate:fresh --seed
   ```

#### Pilihan B: Menggunakan SQLite (lebih simple)

1. Edit `.env`:
   ```env
   DB_CONNECTION=sqlite
   DB_DATABASE=/full/path/to/database.sqlite
   ```

2. Jalankan migration:
   ```bash
   php artisan migrate:fresh --seed
   ```

### 6. Install Node Dependencies

```bash
npm install
```

### 7. Build Frontend Assets

```bash
# Development (untuk development dengan auto-reload)
npm run dev

# Atau Production build
npm run build
```

Jika menggunakan `npm run dev`, biarkan terminal ini tetap berjalan saat development.

### 8. Start Development Server

Buka terminal baru dan jalankan:

```bash
php artisan serve
```

Server akan berjalan di `http://localhost:8000`

---

## Login Credentials

Setelah seeder berjalan, Anda dapat login dengan:

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@example.com | password |
| User | user@example.com | password |

> Lihat file `database/seeders/UserSeeder.php` untuk daftar lengkap.

---

## Struktur Project

```
├── app/                    # Application code
│   ├── Models/            # Eloquent models
│   ├── Http/              # Controllers
│   ├── Filament/          # Filament admin
│   └── Policies/          # Authorization policies
├── database/
│   ├── migrations/        # Database migrations
│   ├── seeders/           # Database seeders
│   └── factories/         # Model factories
├── resources/
│   ├── views/             # Blade templates
│   └── css/               # Tailwind CSS
├── routes/
│   └── web.php            # Web routes
├── tests/                 # Unit & Feature tests
└── config/                # Configuration files
```

---

## Troubleshooting

### Error: "SQLSTATE[HY000]: General error: 1030..."

**Solusi:** Pastikan database sudah di-create atau gunakan SQLite

### Error: "The Mix manifest does not exist"

**Solusi:** Jalankan `npm run build`

### Error: Permission denied pada file storage/

```bash
# Linux/Mac
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Windows (Administrator)
icacls storage /grant Everyone:F /T
icacls bootstrap/cache /grant Everyone:F /T
```

### Port 8000 sudah digunakan

```bash
php artisan serve --port=8001
```

### Composer install lambat

```bash
# Gunakan mirror Indonesia
composer config -g repo.packagist composer https://packagist.phpcomposer.com
```

---

## Verifikasi Setup

Pastikan semua sudah berjalan dengan baik:

1. ✅ Database berhasil di-migrate
2. ✅ Seeder data sudah terisi
3. ✅ Server Laravel berjalan di http://localhost:8000
4. ✅ Frontend assets sudah di-build
5. ✅ Bisa login dengan akun admin

---

## Commands Penting

```bash
# Database
php artisan migrate              # Run migrations
php artisan migrate:fresh        # Reset database
php artisan migrate:fresh --seed # Reset + seed
php artisan seed:db             # Jalankan seeder

# Development
php artisan serve                # Start dev server
npm run dev                       # Dev frontend
npm run build                     # Build production

# Maintenance
php artisan tinker              # Interactive shell
php artisan cache:clear         # Clear cache
php artisan config:clear        # Clear config cache

# Testing
php artisan test                # Run tests
php artisan test --filter=MethodName
```

---

## Dokumentasi Lebih Lanjut

- [Laravel Docs](https://laravel.com/docs)
- [Filament Admin Docs](https://filamentphp.com/docs)
- [Livewire Docs](https://livewire.laravel.com)
- [Tailwind CSS Docs](https://tailwindcss.com/docs)

---

**Notes:**
- Jangan commit `.env` file ke git
- Selalu jalankan `npm run dev` atau `npm run build` setelah update CSS/JS
- Pastikan PHP version `^8.2` atau lebih tinggi
