@echo off
SetLocal EnableDelayedExpansion

:check

ping -n 6 127.0.0.1 > nul

cd C:\TiVo2\scripts
git pull origin master

ping -n 6 127.0.0.1 > nul

C:\TiVo2\php\php.exe C:\TiVo2\scripts\php\ripdvds.php

goto check
