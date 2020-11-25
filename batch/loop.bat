@echo off
SetLocal EnableDelayedExpansion

ping -n 6 127.0.0.1 >nul

:loop

cd D:\TiVo2\scripts
git pull origin master >nul 2>&1

ping -n 36 127.0.0.1 >nul

D:\TiVo2\php\php.exe D:\TiVo2\scripts\php\fromdrives.php
D:\TiVo2\php\php.exe D:\TiVo2\scripts\php\fromdvds.php

ping -n 6 127.0.0.1 >nul

goto loop

D:\TiVo2\php\php.exe D:\TiVo2\scripts\php\fromfolder.php
