<?php

require 'D:\TiVo2\scripts\php\common.php';

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
		
		file_put_contents($directory.'dvd.log', var_export($output,true));
		
		preg_match_all('#\+ title ([\d]+):.*duration: ([\d]{2}:[\d]{2}:[\d]{2})#Uis', $output[1], $output);
		
		$titles = [];
		
		foreach ($output[1] as $id => $title) {
			
			$raw = explode(':', $output[2][$id]);
			
			$raw[0] = intval($raw[0])*(60*60);
			$raw[1] = intval($raw[1])*60;
			$raw[2] = intval($raw[2]);
			
			$time = $raw[2] + $raw[1] + $raw[0];
			
			$titles[] = [
				'number' => $title,
				'time' => $time
			];
			
		}
		
		if (substr($label, 0, 10) === 'DAZD0499D1') {
			
			$titles = [
				['number' => '5'],
				['number' => '6']
			];
			
		}
		
		if (substr($label, 0, 10) === 'DAZD0499D2') {
			
			$titles = [ ['number' => '6'] ];
			
		}
		
		if (substr($label, 0, 10) === 'DAZD0499D3') {
			
			$titles = [ ['number' => '5'] ];
			
		}
		
		file_put_contents($directory.'filter.log', var_export($titles, true));
		
		echo PHP_EOL.'Ripping DVD...';
		
		foreach ($titles as $title) {
			
			$log = shell_exec(sprintf(
				HANDBRAKE_DVD, $disc,
				$directory.$label.'t'.$title['number'],
				$title['number']
			));
			
			file_put_contents($directory.$label.'t'.$title['number'].'.log', $log);
			
		}
		
		echo ' Done!'.PHP_EOL.'Ejecting...';
		
		shell_exec(sprintf(CSCRIPT, DIR_SCRIPTS.'ejectdisc.vbs').' '.$disc);
		
	} catch (Exception $e) {
		
		continue;
		
	}
	
}
