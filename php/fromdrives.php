<?php

require 'common.php';

$drives = shell_exec('wmic logicaldisk get caption');

$drives = shell_clean_up($drives);

$discs = shell_exec(sprintf(CSCRIPT, DIR_SCRIPTS.'listdrives.vbs'));

$discs = shell_clean_up($discs);

$dvds = [];

foreach ($discs as $disc) {
	
	exec('dir '.$drive, $output, $error);
	
	if ($error !== 0) {
		continue;
	}
	
	if (file_exists($disc.DIRECTORY_SEPARATOR.'VIDEO_TS')) {
		$dvds[] = $disc;
	}
	
}

$drives = array_diff($drives, $dvds, EXCLUDED_DRIVES);

foreach ($drives as $drive) {
	
	try {
		
		exec('dir '.$drive, $output, $error);
		
		if ($error !== 0) {
			continue;
		}
		
		$directory = new RecursiveDirectoryIterator($drive.DIRECTORY_SEPARATOR, RecursiveDirectoryIterator::SKIP_DOTS);
		$files = new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::SELF_FIRST);
		
		foreach ($files as $file) {
			
			$where = explode(DIRECTORY_SEPARATOR, $file);
			
			$file = array_pop($where);
			
			$where = implode(DIRECTORY_SEPARATOR, $where);
			
			$output = explode('.', $file);
			
			$format = end($output);
			
			$format = strtolower($format);
			
			if (!in_array($format, FILE_FORMATS, true)) {
				continue;
			}
			
			$output = implode('', $output);
			
			$output = file_clearance($output, $format, DIR_AUTOMATIC);
			
			rename($where.$file, DIR_AUTOMATIC.$output.$format);
			
		}
		
	} catch (Exception $e) {
		
		continue;
		
	}
	
}

foreach ($discs as $disc) {
	
	try {
		
		exec('dir '.$disc, $output, $error);
		
		if ($error !== 0) {
			continue;
		}
		
		$label = shell_exec('vol '.$drive);
		
		$label = shell_clean_up($label);
		
		$label = substr($label[0], 21);
		
		$label = file_clearance($label);
		
		mkdir(DIR_WORKING.$label);
		
		$directory = DIR_WORKING.$label.DIRECTORY_SEPARATOR;
		
		$output = shell_exec(sprintf(HANDBRAKE_SCAN, $drive));
		
		$output = preg_split('#found [\d]+ valid title\(s\)#', $output);
		
		preg_match_all('#\+ title ([\d]+)\:#', $output, $titles);
		
		$titles = $titles[1];
		
		foreach ($titles as $title) {
			shell_exec(sprintf(HANDBRAKE_DVD, $drive, $directory.$title, $title));
		}
		
		shell_exec(sprintf(CSCRIPT, DIR_SCRIPTS.'ejectdisc.vbs').' '.$drive);
		
	} catch (Exception $e) {
		
		continue;
		
	}
	
}
