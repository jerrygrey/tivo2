<?php

define('NO_DISC', 'the device is not ready.');

define('HANDBRAKE', 'C:\TiVo2\HandBrake.exe -i "%s" -o "D:\Temp\%s-%d.m4v" -e x265 --min-duration 1200 --two-pass --audio-lang-list eng --first-audio --normalize-mix 1 --drc 2.5 --keep-display-aspect --native-language eng --native-dub');

$drives = `%windir%\SysWoW64\cscript /nologo "C:\TiVo2\scripts\vbscripts\listdrives.vbs"`;
var_dump($drives);exit;
$drives = trim($drives);

$drives = explode(PHP_EOL, $optical_drives);

foreach ($drives as $letter) {
	
	$letter = trim($letter);
	
	$label = `vol {$letter}`;
	
	$label = trim($label);
	
	$label = strtolower($label);
	
	if ($label === NO_DISC) {
		continue;
	}
	
	for ($i = 0; $i <= 10; $i++) {
		shell_exec(sprintf(HANDBRAKE, $letter, $label, $i));
	}
	
	`%windir%\SysWoW64\cscript /nologo "C:\TiVo2\scripts\vbscripts\ejectdisc.vbs" {$letter}`;
	
}
