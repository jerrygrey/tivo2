<?php

require 'C:\TiVo2\scripts\php\common.php';

$rawdrives = shell_exec('wmic logicaldisk get deviceid,drivetype');

$rawdrives = shell_clean_up($rawdrives);

array_shift($rawdrives);

$drives = [];
$discs = [];

foreach ($rawdrives as $rawdrive) {
	
	try {
		
		[$drive, $type] = explode(':', $rawdrive);
		
		$drive = trim($drive).':';
		$type = trim($type);
		
		switch ($type) {
			
			case '5':
				$discs[] = $drive;
				
			case '2':
				$drives[] = $drive;
				break;
			
			default:
				continue;
			
		}
		
		
		
	} catch (Exception $e) {
		
		continue;
		
	}
	
}

$dvds = [];

foreach ($discs as $disc) {
	
	exec('dir '.$disc, $output, $error);
	
	if ($error !== 0) {
		continue;
	}
	
	if (file_exists($disc.DIRECTORY_SEPARATOR.'VIDEO_TS')) {
		$dvds[] = $disc;
	}
	
}

$drives = array_diff($drives, $dvds, EXCLUDED_DRIVES);

$eject = false;

foreach ($drives as $drive) {
	
	try {
		
		exec('dir '.$drive, $output, $error);
		
		if ($error !== 0) {
			continue;
		}
		
		$directory = new RecursiveDirectoryIterator($drive.DIRECTORY_SEPARATOR, RecursiveDirectoryIterator::SKIP_DOTS);
		$files = new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::SELF_FIRST);
		
		$eject = true;
		
		foreach ($files as $file) {
			var_dump('------------------------', $file, strpos($file, DIRECTORY_SEPARATOR.'.'));
			if (strpos($file, DIRECTORY_SEPARATOR.'.') !== false) {
				continue;
			}
			
			$where = explode(DIRECTORY_SEPARATOR, $file);
			
			$file = array_pop($where);
			
			$where = implode(DIRECTORY_SEPARATOR, $where);
			
			$output = explode('.', $file);
			
			$format = array_pop($output);
			
			$format = strtolower($format);
			
			if (!in_array($format, FILE_FORMATS, true)) {
				continue;
			}
			
			$output = implode('', $output);
			
			$output = file_clearance($output, $format, DIR_AUTOMATIC);
			
			var_dump($where.DIRECTORY_SEPARATOR.$file, DIR_AUTOMATIC.$output.'.'.$format);
			
			/*copy($where.DIRECTORY_SEPARATOR.$file, DIR_AUTOMATIC.$output.'.'.$format);*/
			
		}
		
	} catch (Exception $e) {
		
		continue;
		
	}
	
}

if ($eject) {
	
	$discs = array_diff($discs, $dvds, EXCLUDED_DRIVES);
	
	foreach ($discs as $disc) {
		shell_exec(sprintf(CSCRIPT, DIR_SCRIPTS.'ejectdisc.vbs').' '.$disc);
	}
	
}
