@echo off
SetLocal EnableDelayedExpansion

ping -n 30 127.0.0.1 >nul

:loop

cd D:\TiVo2\scripts
git pull origin master >nul 2>&1

ping -n 120 127.0.0.1 >nul

D:\TiVo2\php\php.exe D:\TiVo2\scripts\php\fromdvds.php
D:\TiVo2\php\php.exe D:\TiVo2\scripts\php\fromdrives.php

ping -n 600 127.0.0.1 >nul

goto loop
