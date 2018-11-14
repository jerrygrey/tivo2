@echo off
SetLocal EnableDelayedExpansion

:check

cd C:\TiVo2\scripts
git pull origin master

ping -n 6 127.0.0.1 > nul

C:\TiVo2\php\php.exe C:\TiVo2\scripts\php\fromdvd.php
C:\TiVo2\php\php.exe C:\TiVo2\scripts\php\fromfolder.php
C:\TiVo2\php\php.exe C:\TiVo2\scripts\php\fromusb.php

goto check
