@echo off
SetLocal EnableDelayedExpansion

ping -n 31 127.0.0.1 >nul

cd C:\TiVo2\scripts
git pull origin master >nul 2>&1

ping -n 6 127.0.0.1 >nul

start "TiVo2" /D "C:\TiVo2\scripts" /MAX loop.bat 
