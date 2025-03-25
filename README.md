# Emenu - Laravel & Filament

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Emenu

Emenu adalah aplikasi berbasis web yang dikembangkan menggunakan **Laravel** dan **Filament**. Laravel digunakan sebagai backend framework yang powerful, sedangkan **Filament** digunakan untuk membangun admin panel yang modern dan efisien.

### Teknologi yang Digunakan
- **Laravel**: Framework PHP untuk membangun backend yang cepat dan aman.
- **Filament**: Admin panel yang berbasis Livewire untuk manajemen data secara interaktif.
- **Livewire**: Library untuk membangun komponen UI reaktif tanpa menggunakan JavaScript secara langsung.
- **TailwindCSS**: Untuk desain antarmuka yang modern dan responsif.

## Fitur
- **Manajemen Data** dengan **Filament Admin Panel**
- **CRUD Produk & Kategori** dengan interface yang interaktif
- **Dashboard Statistik** untuk monitoring performa bisnis
- **Autentikasi dan Otorisasi** berbasis Laravel & Filament
- **Notifikasi & Alerts** untuk informasi penting

## Instalasi
### Persyaratan
Sebelum memulai instalasi, pastikan Anda telah menginstal:
- PHP >= 8.1
- Composer
- Node.js & NPM
- Database (MySQL / PostgreSQL / SQLite)

### Langkah-langkah Instalasi
1. Clone repository ini:
   ```sh
   git clone https://github.com/username/emenu-laravel.git
   cd emenu-laravel
   ```
2. Install dependency Laravel:
   ```sh
   composer install
   ```
3. Buat file **.env** dan sesuaikan konfigurasi database:
   ```sh
   cp .env.example .env
   php artisan key:generate
   ```
4. Jalankan migrasi database:
   ```sh
   php artisan migrate --seed
   ```
5. Install Filament:
   ```sh
   composer require filament/filament
   ```
6. Buat user admin untuk Filament:
   ```sh
   php artisan make:filament-user
   ```
7. Jalankan aplikasi:
   ```sh
   php artisan serve
   ```
   Admin panel dapat diakses di: `http://127.0.0.1:8000/admin`

## Dokumentasi
- [Laravel Documentation](https://laravel.com/docs)
- [Filament Documentation](https://filamentphp.com/docs)

## Kontribusi
Silakan ajukan pull request jika ingin berkontribusi dalam proyek ini.

## License
Proyek ini berlisensi di bawah [MIT license](https://opensource.org/licenses/MIT).

