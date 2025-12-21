#!/usr/bin/env pwsh

Write-Host "[*] Running migrations..." -ForegroundColor Cyan
php artisan migrate

Write-Host "`n[*] Seeding database..." -ForegroundColor Cyan
php artisan db:seed --class=RolePermissionSeeder
php artisan db:seed --class=UserSeeder

Write-Host "`n[*] Clearing cache..." -ForegroundColor Cyan
php artisan optimize:clear

Write-Host "`n[OK] Implementation complete!" -ForegroundColor Green
Write-Host "`n=== Test Users ===" -ForegroundColor Yellow
Write-Host "Kepala Sekolah: kepala@inventaris.test / password" -ForegroundColor White
Write-Host "Petugas Inventaris: petugas@inventaris.test / password" -ForegroundColor White
Write-Host "Admin: admin@inventaris.test / password" -ForegroundColor White
