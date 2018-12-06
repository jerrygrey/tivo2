<?php

require 'C:\TiVo2\scripts\php\common.php';

try {
	
	$directory = new RecursiveDirectoryIterator(DIR_AUTOMATIC, RecursiveDirectoryIterator::SKIP_DOTS);
	$files = new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::SELF_FIRST);
	
} catch (Exception $e) {
	
	exit;
	
}

var_dump($files);exit;

if (!empty($files)) {
	
	echo PHP_EOL.'Found files in folder...';
	
}

foreach ($files as $file) {
	
	$where = explode(DIRECTORY_SEPARATOR, $file);
	
	$file = array_pop($where);
	
	$where = implode(DIRECTORY_SEPARATOR, $where);
	
	$file = explode('.', $file);
	
	$format = array_pop($file);
	
	$format = '.'.$format;
	
	$file = implode('.', $file);
	
	$directory = file_clearance($file);
	
	mkdir(DIR_WORKING.$directory);
	
	$directory = DIR_WORKING.$directory.DIRECTORY_SEPARATOR;
	
	$where = $where.DIRECTORY_SEPARATOR;
	
	echo PHP_EOL.'Converting '.$file.$format.'...';
	
	shell_exec(sprintf(HANDBRAKE_FILE, $where.$file.$format, $directory.$file));
	
	rename($where.$file.$format, $directory.$file.'.original'.$format);
	
	echo ' Done!';
	
}
