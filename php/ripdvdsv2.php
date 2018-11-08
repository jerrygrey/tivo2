<?php

define('NEW_LINES', ["\r\n", "\n\r", "\n", "\r"]);

define('HANDBRAKE_SCAN', 'C:'.DIRECTORY_SEPARATOR.'TiVo2'.DIRECTORY_SEPARATOR.'handbrake'.DIRECTORY_SEPARATOR.'handbrake.exe -i "%s" --title 0 -e x265 --min-duration 1200 --two-pass --audio-lang-list eng --first-audio --normalize-mix 1 --drc 2.5 --keep-display-aspect --native-language eng --native-dub');
define('HANDBRAKE_RIP', 'C:'.DIRECTORY_SEPARATOR.'TiVo2'.DIRECTORY_SEPARATOR.'handbrake'.DIRECTORY_SEPARATOR.'handbrake.exe -i "%s" -o "%s.m4v" --title %d -e x265 --two-pass --audio-lang-list eng --first-audio --normalize-mix 1 --drc 2.5 --keep-display-aspect --native-language eng --native-dub');
define('CSCRIPT', 'C:'.DIRECTORY_SEPARATOR.'Windows'.DIRECTORY_SEPARATOR.'SysWoW64'.DIRECTORY_SEPARATOR.'cscript /nologo "%s"');

define('DIR_SCRIPTS', 'C:'.DIRECTORY_SEPARATOR.'TiVo2'.DIRECTORY_SEPARATOR.'scripts'.DIRECTORY_SEPARATOR.'vbscripts'.DIRECTORY_SEPARATOR);
define('DIR_WORKING', 'D:'.DIRECTORY_SEPARATOR.'Working'.DIRECTORY_SEPARATOR);

$drives = shell_exec(sprintf(CSCRIPT, DIR_SCRIPTS.'listdrives.vbs'));

$drives = trim($drives);

$drives = str_replace(NEW_LINES, "\n", $drives);

$drives = explode("\n", $drives);

foreach ($drives as $letter) {
	
	$letter = trim($letter);
	
	exec('dir '.$letter, $output, $error);
	
	if ($error !== 0) {
		continue;
	}
	
	$label = shell_exec('vol '.$letter);
	
	$label = trim($label);
	
	$label = str_replace(NEW_LINES, "\n", $label);
	
	$label = explode("\n", $label);
	
	$label = trim($label[0]);
	
	$label = substr($label, 21);
	
	if (file_exists(DIR_WORKING.$label)) {
		
		$counter = 1;
		
		while (file_exists(DIR_WORKING.$label.$counter)) {
			$counter++;
		}
		
		$label = $label.$counter;
		
	}
	
	mkdir(DIR_WORKING.$label);
	
	$directory = DIR_WORKING.$label.DIRECTORY_SEPARATOR;
	
	$output = shell_exec(sprintf(HANDBRAKE_SCAN, $letter));
	
	$output = preg_split('#found [\d]+ valid title\(s\)#', $output);
	
	preg_match_all('#\+ title ([\d]+)\:#', $output, $titles);
	
	$titles = $titles[1];
	
	foreach ($titles as $title) {
		shell_exec(sprintf(HANDBRAKE_RIP, $letter, $directory.$title, $title));
	}
	
	shell_exec(sprintf(CSCRIPT, DIR_SCRIPTS.'ejectdisc.vbs').' '.$letter);
	
}