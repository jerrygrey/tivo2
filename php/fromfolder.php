<?php

define('HANDBRAKE', 'C:'.DIRECTORY_SEPARATOR.'TiVo2'.DIRECTORY_SEPARATOR.'handbrake'.DIRECTORY_SEPARATOR.'handbrake.exe -i "%s" -o "%s.m4v" --main-feature -e x265 --two-pass --audio-lang-list eng --first-audio --normalize-mix 1 --drc 2.5 --keep-display-aspect --native-language eng --native-dub');

define('DIR_AUTOMATIC', 'D:'.DIRECTORY_SEPARATOR.'Automatic'.DIRECTORY_SEPARATOR);
define('DIR_WORKING', 'D:'.DIRECTORY_SEPARATOR.'Working'.DIRECTORY_SEPARATOR);

try {
	
	$directory = new RecursiveDirectoryIterator(DIR_AUTOMATIC, RecursiveDirectoryIterator::SKIP_DOTS);
	$files = new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::SELF_FIRST);
	
} catch (Exception $e) {
	
	exit;
	
}

foreach ($files as $file) {
	
	$where = explode(DIRECTORY_SEPARATOR, $file);
	
	$file = array_pop($where);
	
	$where = implode(DIRECTORY_SEPARATOR, $where);
	
	$output = explode('.', $file);
	
	end($output);
	
	$output = implode('', $output);
	
	$output = preg_replace('#[^a-z0-9]+#is', '', $output);
	
	$directory = DIR_WORKING.$output;
	
	if (file_exists($directory)) {
		
		$counter = 1;
		
		while (file_exists($directory.$counter)) {
			$counter++;
		}
		
		$directory = $directory.$counter;
		
	}
	
	mkdir($directory);
	
	$directory = $directory.DIRECTORY_SEPARATOR;
	
	rename($where.$file, $directory.$file);
	
	shell_exec(sprintf(HANDBRAKE, $directory.$file, $directory.$output));
	
}
