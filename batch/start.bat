@echo off
SetLocal EnableDelayedExpansion

ping -n 31 127.0.0.1 >nul

start "TiVo2" /D "D:\TiVo2\scripts" /MAX D:\TiVo2\scripts\batch\loop.bat
