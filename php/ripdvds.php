<?php

define('NO_DISC', 'the device is not ready.');

define('HANDBRAKE', 'C:\TiVo2\HandBrake.exe -i "%s" -o "D:\Temp\%s-%d.m4v" -e x265 --min-duration 1200 --two-pass --audio-lang-list eng --first-audio --normalize-mix 1 --drc 2.5 --keep-display-aspect --native-language eng --native-dub');

$drives = shell_exec('%windir%\SysWoW64\cscript /nologo "C:\TiVo2\scripts\vbscripts\listdrives.vbs"');

$drives = trim($drives);

$drives = explode(PHP_EOL, $drives);

foreach ($drives as $letter) {
	
	$letter = trim($letter);
	
	exec('dir '.$letter, $output, $error);
	
	if ($error !== 0) {
		continue;
	}
	
	$label = shell_exec('vol '.$letter);
	
	$label = trim($label);
	
	$label = explode(PHP_EOL, $label);
	
	$label = trim($label[0]);
	
	$label = substr($label, 22);
	var_dump($label);exit;
	if ($label === NO_DISC) {
		continue;
	}
	
	for ($i = 0; $i <= 10; $i++) {
		shell_exec(sprintf(HANDBRAKE, $letter, $label, $i));
	}
	
	shell_exec('%windir%\SysWoW64\cscript /nologo "C:\TiVo2\scripts\vbscripts\ejectdisc.vbs" {$letter}');
	
}
