<?php

define('NEW_LINES', ["\r\n", "\n\r", "\n", "\r"]);
define('EXCLUDED', ['c:','d:']);

$drives = shell_exec('wmic logicaldisk get caption');

$drives = strtolower($drives);

$drives = substr($drives, 7);

$drives = trim($drives);

$drives = str_replace(NEW_LINES, "\n", $drives);

$drives = explode("\n", $drives);

$drives = array_map('trim', $drives);

$drives = array_diff($drives, EXCLUDED);
