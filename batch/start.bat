@echo off
SetLocal EnableDelayedExpansion

ping -n 31 127.0.0.1 >nul

start "TiVo2" /D "C:\TiVo2\scripts" /MAX batch\loop.bat
