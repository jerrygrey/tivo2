@echo off
SetLocal EnableDelayedExpansion

:loop

cd C:\TiVo2\scripts
git pull origin master >nul 2>&1

ping -n 6 127.0.0.1 >nul

C:\TiVo2\php\php.exe C:\TiVo2\scripts\php\fromdrives.php
C:\TiVo2\php\php.exe C:\TiVo2\scripts\php\fromdvds.php
C:\TiVo2\php\php.exe C:\TiVo2\scripts\php\fromfolder.php

goto loop