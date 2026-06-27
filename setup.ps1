# =====================================================
# Smart Queue System - Automatic Setup Script (PowerShell)
# =====================================================

param(
    [switch]$AutoDatabase = $false,
    [switch]$OpenBrowser = $false
)

# Function untuk print header
function Print-Header {
    Write-Host ""
    Write-Host "╔════════════════════════════════════════════════════════╗" -ForegroundColor Cyan
    Write-Host "║   Smart Queue System - Automatic Setup Wizard           ║" -ForegroundColor Cyan
    Write-Host "║   Sistem Antrean Digital Klinik/Puskesmas              ║" -ForegroundColor Cyan
    Write-Host "╚════════════════════════════════════════════════════════╝" -ForegroundColor Cyan
    Write-Host ""
}

# Function untuk print success
function Print-Success {
    param([string]$Message)
    Write-Host "✅ $Message" -ForegroundColor Green
}

# Function untuk print error
function Print-Error {
    param([string]$Message)
    Write-Host "❌ $Message" -ForegroundColor Red
}

# Function untuk print warning
function Print-Warning {
    param([string]$Message)
    Write-Host "⚠️  $Message" -ForegroundColor Yellow
}

# Function untuk print info
function Print-Info {
    param([string]$Message)
    Write-Host "ℹ️  $Message" -ForegroundColor Cyan
}

# Main Script
Print-Header

# Check if running as Administrator
$admin = [bool](([System.Security.Principal.WindowsIdentity]::GetCurrent()).groups -match "S-1-5-32-544")
if (-not $admin) {
    Print-Warning "Script harus dijalankan sebagai Administrator!"
    Write-Host ""
    Write-Host "Solusi:" -ForegroundColor Yellow
    Write-Host "1. Buka PowerShell sebagai Administrator"
    Write-Host "2. Jalankan: Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser"
    Write-Host "3. Jalankan script ini lagi"
    Write-Host ""
    Read-Host "Tekan Enter untuk keluar"
    exit
}

# STEP 1: Check XAMPP
Write-Host "[STEP 1] Checking XAMPP Installation..." -ForegroundColor Yellow
if (Test-Path "C:\xampp\apache\bin\httpd.exe") {
    Print-Success "XAMPP found at C:\xampp"
} else {
    Print-Error "XAMPP not found at C:\xampp"
    Write-Host ""
    Write-Host "Solusi:" -ForegroundColor Yellow
    Write-Host "1. Download XAMPP dari https://www.apachefriends.org/"
    Write-Host "2. Install di C:\xampp (default location)"
    Write-Host "3. Jalankan setup script ini lagi"
    Write-Host ""
    Read-Host "Tekan Enter untuk keluar"
    exit
}

# STEP 2: Check Project Folder
Write-Host ""
Write-Host "[STEP 2] Checking Project Files..." -ForegroundColor Yellow
if (Test-Path "C:\xampp\htdocs\IPPL_INDIVIDU1\index.php") {
    Print-Success "Project folder found at C:\xampp\htdocs\IPPL_INDIVIDU1\"
} else {
    Print-Error "Project folder not found!"
    Write-Host ""
    Write-Host "Solusi:" -ForegroundColor Yellow
    Write-Host "1. Copy folder IPPL_INDIVIDU1 ke C:\xampp\htdocs\"
    Write-Host "2. Jalankan setup script ini lagi"
    Write-Host ""
    Read-Host "Tekan Enter untuk keluar"
    exit
}

# STEP 3: Check MySQL
Write-Host ""
Write-Host "[STEP 3] Checking MySQL Installation..." -ForegroundColor Yellow
if (Test-Path "C:\xampp\mysql\bin\mysql.exe") {
    Print-Success "MySQL found"
} else {
    Print-Error "MySQL not found in XAMPP"
    exit
}

# STEP 4: Create Database
Write-Host ""
Write-Host "[STEP 4] Database Setup..." -ForegroundColor Yellow

$checkDB = & "C:\xampp\mysql\bin\mysql.exe" -u root -e "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME='smart_queue'" 2>$null

if ($checkDB) {
    Print-Info "Database 'smart_queue' already exists"
} else {
    if ($AutoDatabase) {
        Write-Host "Creating database automatically..."
        $sqlFile = "C:\xampp\htdocs\IPPL_INDIVIDU1\database\smart_queue.sql"
        if (Test-Path $sqlFile) {
            & "C:\xampp\mysql\bin\mysql.exe" -u root < $sqlFile
            if ($LASTEXITCODE -eq 0) {
                Print-Success "Database created successfully!"
            } else {
                Print-Error "Database creation failed"
            }
        }
    } else {
        Write-Host ""
        Write-Host "Database 'smart_queue' tidak ditemukan." -ForegroundColor Yellow
        $createDB = Read-Host "Apakah Anda ingin membuat database sekarang? (y/n)"
        
        if ($createDB -eq 'y') {
            $sqlFile = "C:\xampp\htdocs\IPPL_INDIVIDU1\database\smart_queue.sql"
            if (Test-Path $sqlFile) {
                Write-Host "Importing database..."
                & "C:\xampp\mysql\bin\mysql.exe" -u root < $sqlFile
                if ($LASTEXITCODE -eq 0) {
                    Print-Success "Database created successfully!"
                } else {
                    Print-Error "Database creation failed"
                }
            } else {
                Print-Error "SQL file not found: $sqlFile"
            }
        }
    }
}

# STEP 5: Display URLs
Write-Host ""
Write-Host "[STEP 5] Setup Complete! ✅" -ForegroundColor Green
Write-Host ""
Write-Host "╔════════════════════════════════════════════════════════╗" -ForegroundColor Green
Write-Host "║            AKSES APLIKASI DI URL BERIKUT:              ║" -ForegroundColor Green
Write-Host "╠════════════════════════════════════════════════════════╣" -ForegroundColor Green
Write-Host "║                                                        ║" -ForegroundColor Green
Write-Host "║  🌐 Monitoring Publik (Tanpa Login):                  ║" -ForegroundColor Green
Write-Host "║     http://localhost/IPPL_INDIVIDU1/                  ║" -ForegroundColor Green
Write-Host "║                                                        ║" -ForegroundColor Green
Write-Host "║  🔐 Login:                                             ║" -ForegroundColor Green
Write-Host "║     http://localhost/IPPL_INDIVIDU1/auth/login.php   ║" -ForegroundColor Green
Write-Host "║                                                        ║" -ForegroundColor Green
Write-Host "║  📊 Admin Dashboard:                                   ║" -ForegroundColor Green
Write-Host "║     http://localhost/IPPL_INDIVIDU1/admin/dashboard   ║" -ForegroundColor Green
Write-Host "║                                                        ║" -ForegroundColor Green
Write-Host "║  🗄️  phpMyAdmin (Database):                           ║" -ForegroundColor Green
Write-Host "║     http://localhost/phpmyadmin                        ║" -ForegroundColor Green
Write-Host "║                                                        ║" -ForegroundColor Green
Write-Host "╚════════════════════════════════════════════════════════╝" -ForegroundColor Green
Write-Host ""

# Display Test Accounts
Write-Host ""
Write-Host "Test Accounts:" -ForegroundColor Cyan
Write-Host ""
Write-Host "👤 Pasien:   pasien@smartqueue.local          / pasien123456" -ForegroundColor White
Write-Host "👤 Petugas:  petugas1@smartqueue.local        / petugas123456" -ForegroundColor White
Write-Host "👨‍⚕️  Dokter:   dokter1@smartqueue.local         / dokter123456" -ForegroundColor White
Write-Host "👨‍💼 Admin:    admin@smartqueue.local            / admin123456" -ForegroundColor White
Write-Host ""

# Open Browser
if ($OpenBrowser) {
    Write-Host "Opening browser..."
    Start-Process "http://localhost/IPPL_INDIVIDU1/"
}

Write-Host ""
Write-Host "════════════════════════════════════════════════════════" -ForegroundColor Green
Write-Host " Setup selesai! Smart Queue System siap digunakan." -ForegroundColor Green
Write-Host " Dokumentasi: QUICK_START.md" -ForegroundColor Green
Write-Host "════════════════════════════════════════════════════════" -ForegroundColor Green
Write-Host ""
