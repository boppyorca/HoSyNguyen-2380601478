@echo off
REM Script để chạy CLI tools trên Windows

:menu
cls
echo.
echo ╔════════════════════════════════════════════════╗
echo ║        WEBBANHANG - CLI TOOLS                 ║
echo ╚════════════════════════════════════════════════╝
echo.
echo 1 - Nhập danh mục từ bàn phím
echo 2 - Nhập sản phẩm từ bàn phím
echo 3 - Xem cấu trúc dự án
echo 4 - Thoát
echo.

set /p choice="Chọn tùy chọn (1-4): "

if "%choice%"=="1" goto import_category
if "%choice%"=="2" goto import_product
if "%choice%"=="3" goto show_structure
if "%choice%"=="4" goto exit_program
echo.
echo Tùy chọn không hợp lệ!
echo.
timeout /t 2 /nobreak
goto menu

:import_category
cls
echo.
echo 🚀 Khởi động nhập danh mục...
echo.
php import-category.php
echo.
pause
goto menu

:import_product
cls
echo.
echo 🚀 Khởi động nhập sản phẩm...
echo.
php import-product.php
echo.
pause
goto menu

:show_structure
cls
echo.
echo 📁 Cấu trúc dự án:
echo.
tree /F
echo.
pause
goto menu

:exit_program
echo.
echo 👋 Tạm biệt!
echo.
exit /b 0
