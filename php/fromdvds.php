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
		
		if (!file_exists($disc.DIRECTORY_SEPARATOR.'VIDEO_TS')) {
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
