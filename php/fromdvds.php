<?php

require 'C:\TiVo2\scripts\php\common.php';

$discs = shell_exec(sprintf(CSCRIPT, DIR_SCRIPTS.'listdrives.vbs'));

$discs = shell_clean_up($discs);

foreach ($discs as $disc) {
	
	try {
		
		exec('dir '.$disc, $output, $error);
		
		if ($error !== 0) {
			continue;
		}
		
		unset($output, $error);
		
		if (!file_exists($disc.DIRECTORY_SEPARATOR.'VIDEO_TS')) {
			continue;
		}
		
		$label = shell_exec('vol '.$disc);
		
		$label = shell_clean_up($label);
		
		$label = substr($label[0], 21);
		
		$label = file_clearance($label);
		
		mkdir(DIR_WORKING.$label);
		
		$directory = DIR_WORKING.$label.DIRECTORY_SEPARATOR;
		
		exec(sprintf(HANDBRAKE_SCAN, $disc), $output);
		
		var_dump($output);
		
		$output = preg_split('#found [\d]+ valid title\(s\)#is', $output, 2);
		
		var_dump($output);exit;
		
		if (count($output) < 2) {
			continue;
		}
		
		preg_match_all('#\+ title ([\d]+)\:#is', $output[1], $titles);
		
		$titles = $titles[1];
		
		foreach ($titles as $title) {
			shell_exec(sprintf(HANDBRAKE_DVD, $disc, $directory.$title, $title));
		}
		
		shell_exec(sprintf(CSCRIPT, DIR_SCRIPTS.'ejectdisc.vbs').' '.$disc);
		
	} catch (Exception $e) {
		
		continue;
		
	}
	
}
