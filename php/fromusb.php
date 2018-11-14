<?php

define('NEW_LINES', ["\r\n", "\n\r", "\n", "\r"]);
define('FILE_FORMATS', ['avi','mp4','m4v','mpg','mov']);

define('HANDBRAKE', 'C:'.DIRECTORY_SEPARATOR.'TiVo2'.DIRECTORY_SEPARATOR.'handbrake'.DIRECTORY_SEPARATOR.'handbrake.exe -i "%s" -o "%s.m4v" --main-feature -e x265 --two-pass --audio-lang-list eng --first-audio --normalize-mix 1 --drc 2.5 --keep-display-aspect --native-language eng --native-dub');

define('DIR_AUTOMATIC', 'D:'.DIRECTORY_SEPARATOR.'Automatic'.DIRECTORY_SEPARATOR);
define('DIR_WORKING', 'D:'.DIRECTORY_SEPARATOR.'Working'.DIRECTORY_SEPARATOR);

$drives = shell_exec('wmic logicaldisk get caption');

$drives = strtolower($drives);
$drives = substr($drives, 7);
$drives = trim($drives);

$drives = str_replace(NEW_LINES, "\n", $drives);

$drives = explode("\n", $drives);

$drives = array_map('trim', $drives);

$drives = array_diff($drives, ['c:','d:']);

foreach ($drives as $drive) {
	
	try {
		
		$directory = new RecursiveDirectoryIterator($drive.DIRECTORY_SEPARATOR, RecursiveDirectoryIterator::SKIP_DOTS);
		$files = new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::SELF_FIRST);
		
	} catch (Exception $e) {
		
		continue;
		
	}
	
	foreach ($files as $file) {
		
		$where = explode(DIRECTORY_SEPARATOR, $file);
		
		$file = array_pop($where);
		
		$where = implode(DIRECTORY_SEPARATOR, $file);
		
		$output = explode('.', $file);
		
		$format = end($output);
		
		$format = strtolower($format);
		
		if (!in_array($format, FILE_FORMATS, true)) {
			continue;
		}
		
		$output = implode('-', $output);
		
		$directory = DIR_WORKING.$output;
		
		if (file_exists($directory)) {
			
			$counter = 1;
		
			while (file_exists($directory.$counter)) {
				$counter++;
			}
			
			$directory = $directory.$counter;
			
		}
		
		/*mkdir($directory);*/
		
		$directory = $directory.DIRECTORY_SEPARATOR;
		var_dump($file,$where,$directory,$output);
		/*rename($where.$file, $directory.$file);
		
		shell_exec(sprintf(HANDBRAKE, $directory.$file, $directory.$output));*/
		
	}
	
}
