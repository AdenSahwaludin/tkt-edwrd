#!/usr/bin/env pwsh

<#
.SYNOPSIS
    Commit, add, dan push changes ke GitHub dalam satu command.

.PARAMETER message
    Pesan commit yang ingin disimpan. Jika tidak diberikan, akan diminta interaktif.

.EXAMPLE
    .\git-commit-push.ps1 "Fix: Perbaikan menu order"
    .\git-commit-push.ps1
#>

param(
    [string]$message = ""
)

# Jika message kosong, minta user input
if ([string]::IsNullOrWhiteSpace($message)) {
    $message = Read-Host "Masukkan pesan commit"
}

# Validasi pesan commit
if ([string]::IsNullOrWhiteSpace($message)) {
    Write-Host "❌ Pesan commit tidak boleh kosong!" -ForegroundColor Red
    exit 1
}

try {
    Write-Host "[*] Menambahkan perubahan..." -ForegroundColor Cyan
    git add .
    
    Write-Host "[*] Melakukan commit..." -ForegroundColor Cyan
    git commit -m "$message"
    
    Write-Host "[*] Melakukan push ke GitHub..." -ForegroundColor Cyan
    git push
    
    Write-Host "[OK] Semua perubahan berhasil di-push ke GitHub!" -ForegroundColor Green
}
catch {
    Write-Host "[ERROR] Terjadi error: $_" -ForegroundColor Red
    exit 1
}
