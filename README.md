<p align="center">
    <a href="https://laravel.com" target="_blank">
    <img src="https://github.com/iLoveOrca1/sirkula-laravel-app/blob/main/public/logo.png?raw=true" width="400" alt="Sirkula Logo">
    </a>
</p>

<p align="center">
<a href="https://github.com/iLoveOrca1/sirkula-laravel-app"><img src="https://img.shields.io/badge/GitHub-repo-blue?style=for-the-badge&logo=github" alt="Github Repo"></a>
<a href="https://www.youtube.com/@SIRKULA_TRPL2026"><img src="https://img.shields.io/badge/YouTube-ch-red?style=for-the-badge&logo=youtube" alt="YouTube"></a>
<a href="https://sirkula.sbs/"><img src="https://img.shields.io/badge/Websites-Link-green?style=for-the-badge" alt="Sites"></a>
</p>

# About SIRKULA

> SIRKULA adalah sebuah platform digital untuk menemukan, menghubungkan, dan mensirkulasikan kembali limbah-limbah dari berbagai sumber. Mulai dari eksplorasi material, diskusi langsung via room chat, hingga pantau data lewat dashboard—semua ada dalam satu ekosistem.

# Setup for Dev

## Requirement
- Docker
- Laravel 13

## Quick Setup

### Jalankan docker

```bash
sudo docker compose up -d
```

### Install dependency laravel

```bash
# install pgsql extension
sudo apt install php-pgsql

# Download package laravel
composer install
```

### Install frontend dependency
```bash
# Installing the frontend asset
npm ci
```

### Copy .env file
```bash
# Creating .env example
cp .env.example .env
```

### Laravel Setup
```bash
# Generate app key
php artisan key:generate

# Create storage symlink
php artisan storage:link
```

```bash
# Migrate ke pgsql
php artisan migrate

# Menjalankan DatabaseSeeder utama
php artisan db:seed

# Reset ulang database dari awal sekaligus menjalankan seeder
php artisan migrate:fresh --seed

```

### Jalankan Laravel
```bash
composer run dev
```
