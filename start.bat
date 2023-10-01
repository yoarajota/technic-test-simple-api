@echo off

echo Iniciando servidores!

echo URL PADRAO FRONTEND = localhost:4173
echo URL PADRAO BACKEND = localhost:8000
echo ps: Por favor, leia com atenção o retorno dos terminais. 


cd boot
start "Frontend Server" vue.bat
start "Backend Server" lumen.bat
start "Serviço de Job Queue" job.bat

pause