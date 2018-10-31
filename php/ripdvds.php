<?php

define('NEW_LINES', ["\r\n", "\n\r", "\n", "\r"]);

define('HANDBRAKE', 'C:'.DIRECTORY_SEPARATOR.'TiVo2'.DIRECTORY_SEPARATOR.'HandBrake.exe -i "%s" -o "%s" -e x265 --min-duration 1200 --two-pass --audio-lang-list eng --first-audio --normalize-mix 1 --drc 2.5 --keep-display-aspect --native-language eng --native-dub');
define('CSCRIPT', '%windir%'.DIRECTORY_SEPARATOR.'SysWoW64'.DIRECTORY_SEPARATOR.'cscript /nologo "%s"');

define('DIR_SCRIPTS', 'C:'.DIRECTORY_SEPARATOR.'TiVo2'.DIRECTORY_SEPARATOR.'scripts'.DIRECTORY_SEPARATOR.'vbscripts'.DIRECTORY_SEPARATOR);
define('DIR_WORKING', 'D:'.DIRECTORY_SEPARATOR.'Working'.DIRECTORY_SEPARATOR);

$drives = shell_exec(sprintf(CSCRIPT, DIR_SCRIPTS.'listdrives.vbs'));

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
	
	var_dump($label);exit;
	for ($i = 0; $i <= 10; $i++) {
		shell_exec(sprintf(HANDBRAKE, $letter, DIR_WORKING.$label'.DIRECTORY_SEPARATOR.'$i.'.m4v'));
	}
	
	shell_exec(sprintf(CSCRIPT, DIR_SCRIPTS.'ejectdisc.vbs').' '.$letter);
	
}
