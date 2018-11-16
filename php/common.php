<?php

define('FILE_FORMATS', ['avi','mp4','m4v','mkv','mpg','mov']);
define('NEW_LINES', ["\r\n", "\n\r", "\n", "\r"]);
define('EXCLUDED_DRIVES', ['C:','D:']);

define('DIR_SCRIPTS', 'C:'.DIRECTORY_SEPARATOR.'TiVo2'.DIRECTORY_SEPARATOR.'scripts'.DIRECTORY_SEPARATOR.'vbscripts'.DIRECTORY_SEPARATOR);
define('DIR_TEMPORARY', 'C:'.DIRECTORY_SEPARATOR.'TiVo2'.DIRECTORY_SEPARATOR.'scripts'.DIRECTORY_SEPARATOR.'temporary'.DIRECTORY_SEPARATOR);
define('DIR_AUTOMATIC', 'D:'.DIRECTORY_SEPARATOR.'Automatic'.DIRECTORY_SEPARATOR);
define('DIR_WORKING', 'D:'.DIRECTORY_SEPARATOR.'Working'.DIRECTORY_SEPARATOR);

define('HANDBRAKE_FILE', 'C:'.DIRECTORY_SEPARATOR.'TiVo2'.DIRECTORY_SEPARATOR.'handbrake'.DIRECTORY_SEPARATOR.'handbrake.exe -i "%s" -o "%s.m4v" --main-feature -e x265 --two-pass --audio-lang-list eng --first-audio --normalize-mix 1 --drc 1.5 --keep-display-aspect --native-language eng --native-dub 2>&1');
define('HANDBRAKE_SCAN', 'C:'.DIRECTORY_SEPARATOR.'TiVo2'.DIRECTORY_SEPARATOR.'handbrake'.DIRECTORY_SEPARATOR.'handbrake.exe -i "%s" --title 0 -e x265 --min-duration 1200 --two-pass --audio-lang-list eng --first-audio --normalize-mix 1 --drc 1.5 --keep-display-aspect --native-language eng --native-dub 2>&1');
define('HANDBRAKE_DVD', 'C:'.DIRECTORY_SEPARATOR.'TiVo2'.DIRECTORY_SEPARATOR.'handbrake'.DIRECTORY_SEPARATOR.'handbrake.exe -i "%s" -o "%s.m4v" --title %d -e x265 --two-pass --audio-lang-list eng --first-audio --normalize-mix 1 --drc 1.5 --keep-display-aspect --native-language eng --native-dub 2>&1');

define('CSCRIPT', 'C:'.DIRECTORY_SEPARATOR.'Windows'.DIRECTORY_SEPARATOR.'SysWoW64'.DIRECTORY_SEPARATOR.'cscript /nologo "%s"');

function shell_clean_up ( $output ) {
	
	$output = strtoupper($output);
	
	$output = trim($output);
	
	$output = str_replace(NEW_LINES, "\n", $output);
	
	$output = explode("\n", $output);
	
	return array_map('trim', $output);
	
}

function file_clearance ( $name, $format = '', $directory = DIR_WORKING ) {
	
	if (!empty($format)) {
		$format = '.'.$format;
	}
	
	$name = preg_replace('#[^a-z0-9]+#is', '', $name);
	
	if (file_exists($directory.$name.$format)) {
		
		$counter = 1;
		
		while (file_exists($directory.$name.$counter.$format)) {
			$counter++;
		}
		
		$name = $name.$counter;
		
	}
	
	return $name;
	
}
