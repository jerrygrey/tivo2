<?php

require 'C:\TiVo2\scripts\php\common.php';

$rawdrives = shell_exec('wmic logicaldisk get deviceid,drivetype 2>&1');

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
	
	$output = shell_exec("dir {$disc} 2>&1");
	
	if (strpos($output, 'not ready') !== false) {
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
		
		$output = shell_exec("dir {$drive} 2>&1");
		
		if (strpos($output, 'not ready') !== false) {
			continue;
		}
		
		$file = DIR_TEMPORARY.'drive-'.substr($drive, 0, 1);
		$hash = hash('sha1', $output);
		
		if (file_exists($file)) {
			
			$contents = file_get_contents($file);
			
			$contents = explode('-', $contents);
			
			$contents[0] = intval($contents[0]);
			var_dump($contents, $contents[0] < (time()-(60*60)), $contents[1] === $hash);
			if ($contents[0] < (time()-(60*60)) and $contents[1] === $hash) {
				continue;
			} else {
				unlink($file);
			}
			
		}
		
		file_put_contents($file, time().'-'.$hash);
		continue;
		$directory = new RecursiveDirectoryIterator($drive.DIRECTORY_SEPARATOR, RecursiveDirectoryIterator::SKIP_DOTS);
		$files = new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::SELF_FIRST);
		
		$eject = true;
		
		echo PHP_EOL.'Media inserted...';
		
		foreach ($files as $file) {
			
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
			
			echo PHP_EOL.'Copying '.$file.'...';
			
			copy($where.DIRECTORY_SEPARATOR.$file, DIR_AUTOMATIC.$output.'.'.$format);
			
			echo ' Done!';
			
		}
		
	} catch (Exception $e) {
		
		continue;
		
	}
	
}

if ($eject) {
	
	echo PHP_EOL.'All done, ejecting...';
	
	$discs = array_diff($discs, $dvds, EXCLUDED_DRIVES);
	
	foreach ($discs as $disc) {
		shell_exec(sprintf(CSCRIPT, DIR_SCRIPTS.'ejectdisc.vbs').' '.$disc);
	}
	
}
