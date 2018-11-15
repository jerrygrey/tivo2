@echo off
SetLocal EnableDelayedExpansion

ping -n 2 127.0.0.1 > nul

:check

cd C:\TiVo2\scripts
git pull origin master

ping -n 6 127.0.0.1 > nul

C:\TiVo2\php\php.exe C:\TiVo2\scripts\php\fromdrives.php
C:\TiVo2\php\php.exe C:\TiVo2\scripts\php\fromdvds.php
C:\TiVo2\php\php.exe C:\TiVo2\scripts\php\fromfolder.php

goto check
