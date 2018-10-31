@echo off
SetLocal EnableDelayedExpansion

:check

ping -n 6 127.0.0.1 > nul

php D:\Scripts\php\ripsdvds.php

goto check
