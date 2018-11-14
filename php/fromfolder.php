<?php

require 'common.php';

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
	
	$directory = file_clearance($output);
	
	mkdir(DIR_WORKING.$directory);
	
	$directory = DIR_WORKING.$directory.DIRECTORY_SEPARATOR;
	
	rename($where.$file, $directory.$file);
	
	shell_exec(sprintf(HANDBRAKE_FILE, $directory.$file, $directory.$output));
	
}

if (!empty($files)) {
	
	$discs = shell_exec(sprintf(CSCRIPT, DIR_SCRIPTS.'listdrives.vbs'));
	
	$discs = shell_clean_up($discs);
	
	foreach ($discs as $disc) {
		shell_exec(sprintf(CSCRIPT, DIR_SCRIPTS.'ejectdisc.vbs').' '.$disc);
	}
	
}
