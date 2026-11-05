@echo off
title May chu Mo phong VNeID - Port 8000
chcp 65001 >nul
cls
echo ===================================================
echo     HE THONG MO PHONG UNG DUNG VNeID
echo ===================================================
echo.
echo [*] Dang khoi dong may chu PHP tren http://localhost:8000 ...
echo [*] Luu y: Hay bat MySQL trong XAMPP Control Panel truoc!
echo [*] De dung may chu, nhan to hop phim Ctrl + C
echo.
echo ---------------------------------------------------
echo Link truy cap: http://localhost:8000/the_can_cuoc/danh_sach
echo ---------------------------------------------------
echo.
C:\xampp\php\php.exe -S localhost:8000 index.php
if %ERRORLEVEL% NEQ 0 (
    echo.
    echo [!] Khong the khoi dong bang C:\xampp\php\php.exe, dang thu voi lenh 'php' he thong...
    php -S localhost:8000 index.php
)
pause
