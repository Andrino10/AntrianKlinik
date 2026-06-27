@echo off
REM =====================================================
REM Smart Queue System - Automatic Setup Script
REM Windows Batch Script untuk setup aplikasi
REM =====================================================

echo.
echo ╔════════════════════════════════════════════════════════╗
echo ║   Smart Queue System - Automatic Setup Wizard           ║
echo ║   Sistem Antrean Digital Klinik/Puskesmas              ║
echo ╚════════════════════════════════════════════════════════╝
echo.

REM Check if running as Administrator
net session >nul 2>&1
if %errorLevel% neq 0 (
    echo.
    echo ⚠️  WARNING: Script harus dijalankan sebagai Administrator!
    echo.
    echo Solusi:
    echo 1. Buka Command Prompt/PowerShell sebagai Administrator
    echo 2. Jalankan script ini lagi
    echo.
    pause
    exit /b 1
)

REM Step 1: Check XAMPP
echo [STEP 1] Checking XAMPP Installation...
if exist "C:\xampp\apache\bin\httpd.exe" (
    echo ✅ XAMPP found at C:\xampp
) else (
    echo ❌ XAMPP not found at C:\xampp
    echo.
    echo Solusi: 
    echo 1. Download XAMPP dari https://www.apachefriends.org/
    echo 2. Install di C:\xampp (default location)
    echo 3. Jalankan setup script ini lagi
    echo.
    pause
    exit /b 1
)

REM Step 2: Check Project Folder
echo.
echo [STEP 2] Checking Project Files...
if exist "C:\xampp\htdocs\IPPL_INDIVIDU1\index.php" (
    echo ✅ Project folder found at C:\xampp\htdocs\IPPL_INDIVIDU1\
) else (
    echo ❌ Project folder not found!
    echo.
    echo Solusi:
    echo 1. Copy folder IPPL_INDIVIDU1 ke C:\xampp\htdocs\
    echo 2. Jalankan setup script ini lagi
    echo.
    pause
    exit /b 1
)

REM Step 3: Start Apache
echo.
echo [STEP 3] Starting Apache & MySQL Services...
echo Checking if services are already running...

REM Try to start Apache
tasklist /FI "IMAGENAME eq httpd.exe" 2>NUL | find /I /N "httpd.exe">NUL
if "%ERRORLEVEL%"=="0" (
    echo ✅ Apache already running
) else (
    echo Starting Apache...
    cd /d "C:\xampp\apache\bin"
    httpd.exe
    timeout /t 2 >nul
    echo ✅ Apache started
)

REM Try to start MySQL
tasklist /FI "IMAGENAME eq mysqld.exe" 2>NUL | find /I /N "mysqld.exe">NUL
if "%ERRORLEVEL%"=="0" (
    echo ✅ MySQL already running
) else (
    echo Starting MySQL...
    cd /d "C:\xampp\mysql\bin"
    mysqld.exe --defaults-file="C:\xampp\mysql\bin\my.ini"
    timeout /t 2 >nul
    echo ✅ MySQL started
)

REM Step 4: Display URLs
echo.
echo [STEP 4] Setup Complete! ✅
echo.
echo ╔════════════════════════════════════════════════════════╗
echo ║            AKSES APLIKASI DI URL BERIKUT:              ║
echo ╠════════════════════════════════════════════════════════╣
echo ║                                                        ║
echo ║  🌐 Monitoring Publik (Tanpa Login):                  ║
echo ║     http://localhost/IPPL_INDIVIDU1/                  ║
echo ║                                                        ║
echo ║  🔐 Login:                                             ║
echo ║     http://localhost/IPPL_INDIVIDU1/auth/login.php   ║
echo ║                                                        ║
echo ║  🗄️  phpMyAdmin (Database):                           ║
echo ║     http://localhost/phpmyadmin                        ║
echo ║                                                        ║
echo ╚════════════════════════════════════════════════════════╝
echo.

REM Step 5: Create Database (Optional)
echo.
set /p create_db="Apakah Anda ingin membuat database otomatis? (y/n): "
if /i "%create_db%"=="y" (
    echo.
    echo [STEP 5] Creating Database...
    cd /d "C:\xampp\mysql\bin"
    
    REM Import SQL script
    mysql -u root < "C:\xampp\htdocs\IPPL_INDIVIDU1\database\smart_queue.sql"
    
    if %errorLevel% equ 0 (
        echo ✅ Database created successfully!
    ) else (
        echo ❌ Database creation failed
        echo.
        echo Solusi: Import database manual melalui phpMyAdmin
        echo 1. Buka http://localhost/phpmyadmin
        echo 2. Tab Import
        echo 3. Pilih file: database/smart_queue.sql
    )
)

echo.
echo [INFO] Test Accounts:
echo.
echo Pasien:  pasien@smartqueue.local / pasien123456
echo Petugas: petugas1@smartqueue.local / petugas123456
echo Dokter:  dokter1@smartqueue.local / dokter123456
echo Admin:   admin@smartqueue.local / admin123456
echo.

REM Step 6: Open Browser
echo.
set /p open_browser="Apakah ingin buka aplikasi di browser? (y/n): "
if /i "%open_browser%"=="y" (
    start http://localhost/IPPL_INDIVIDU1/
)

echo.
echo ════════════════════════════════════════════════════════
echo  Setup selesai! Smart Queue System siap digunakan.
echo  Jika ada masalah, baca dokumentasi di QUICK_START.md
echo ════════════════════════════════════════════════════════
echo.

pause
