<?php

require 'C:\TiVo2\scripts\php\common.php';

$discs = shell_exec(sprintf(CSCRIPT, DIR_SCRIPTS.'listdiscs.vbs'));

$discs = shell_clean_up($discs);

foreach ($discs as $disc) {
	
	try {
		
		$output = shell_exec("dir {$disc} 2>&1");
		
		if (strpos($output, 'not ready') !== false) {
			continue;
		}
		
		if (!file_exists($disc.DIRECTORY_SEPARATOR.'VIDEO_TS')) {
			continue;
		}
		
		echo PHP_EOL.'Found DVD, scanning...';
		
		$output = shell_exec(sprintf(HANDBRAKE_SCAN, $disc));
		
		$output = preg_split('#found [\d]+ valid title\(s\)#is', $output, 2);
		
		if (count($output) < 2) {
			
			echo PHP_EOL.'Nothing to do, ejecting...';
			
			shell_exec(sprintf(CSCRIPT, DIR_SCRIPTS.'ejectdisc.vbs').' '.$disc);
			
			continue;
			
		}
		
		$label = shell_exec('vol '.$disc);
		
		$label = shell_clean_up($label);
		
		$label = substr($label[0], 21);
		
		$label = file_clearance($label);
		
		mkdir(DIR_WORKING.$label);
		
		$directory = DIR_WORKING.$label.DIRECTORY_SEPARATOR;
		
		file_put_contents($directory.'handbrake.log', var_export($output, true));
		
		preg_match_all('#\+ title ([\d]+)\:#is', $output[1], $titles);
		
		$titles = $titles[1];
		
		echo PHP_EOL.'Ripping DVD...';
		
		foreach ($titles as $title) {
			shell_exec(sprintf(HANDBRAKE_DVD, $disc, $directory.$title, $title));
		}
		
		echo ' Done!'.PHP_EOL.'Ejecting...';
		
		shell_exec(sprintf(CSCRIPT, DIR_SCRIPTS.'ejectdisc.vbs').' '.$disc);
		
	} catch (Exception $e) {
		
		continue;
		
	}
	
}
