<?php

define('FILE_FORMATS', ['avi','mp4','m4v','mpg','mov']);

define('HANDBRAKE', 'C:'.DIRECTORY_SEPARATOR.'TiVo2'.DIRECTORY_SEPARATOR.'handbrake'.DIRECTORY_SEPARATOR.'handbrake.exe -i "%s" -o "%s.m4v" --main-feature -e x265 --two-pass --audio-lang-list eng --first-audio --normalize-mix 1 --drc 2.5 --keep-display-aspect --native-language eng --native-dub');

define('DIR_AUTOMATIC', 'D:'.DIRECTORY_SEPARATOR.'Automatic'.DIRECTORY_SEPARATOR);
define('DIR_WORKING', 'D:'.DIRECTORY_SEPARATOR.'Working'.DIRECTORY_SEPARATOR);

$drives = shell_exec('devcon find *USB*');






$directory = new RecursiveDirectoryIterator(DIR_AUTOMATIC, RecursiveDirectoryIterator::SKIP_DOTS);
$files = new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::SELF_FIRST);

foreach ($files as $file) {
	
	$directory = DIR_WORKING.'Automatic';
	$file = DIRECTORY_SEPARATOR.$file;
	
	if (file_exists($directory)) {
		
		$counter = 1;
		
		while (file_exists($directory.$counter)) {
			$counter++;
		}
		
		$directory = $directory.$counter;
		
	}
	
	mkdir($directory);
	
	rename(DIR_AUTOMATIC.$file, $directory.$file);
	
	shell_exec(sprintf(HANDBRAKE, DIR_AUTOMATIC.$file, $directory.$file));
	
}
