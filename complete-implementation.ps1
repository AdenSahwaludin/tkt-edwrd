#!/usr/bin/env pwsh

Write-Host "[*] Menjalankan deployment..." -ForegroundColor Cyan
.\deploy-kepala-sekolah.ps1

Write-Host "`n[*] Commit dan push ke GitHub..." -ForegroundColor Cyan
.\git-commit-push.ps1 "Feat: Add Kepala Sekolah role with approval system for incoming inventory transactions"
