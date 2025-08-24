@echo off
cls
echo ===============================================
echo    🌐 SERVIDOR LARAVEL CON HTTP
echo ===============================================
echo.
echo ✅ Servidor iniciado en: http://localhost:8000
echo.
echo 📋 Rutas disponibles:
echo    👤 Login:     http://localhost:8000/login
echo    📊 Dashboard: http://localhost:8000/admin/dashboard
echo    🏠 Inicio:    http://localhost:8000/
echo.
echo 🛑 Para detener: Ctrl+C
echo ===============================================
echo.

php artisan serve --host=127.0.0.1 --port=8000
pause