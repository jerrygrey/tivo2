<?php

define('FILE_FORMATS', ['avi','mp4','m4v','mpg','mov']);

define('NEW_LINES', ["\r\n", "\n\r", "\n", "\r"]);

define('EXCLUDED_DRIVES', ['C:','D:']);

define('DIR_SCRIPTS', 'C:'.DIRECTORY_SEPARATOR.'TiVo2'.DIRECTORY_SEPARATOR.'scripts'.DIRECTORY_SEPARATOR.'vbscripts'.DIRECTORY_SEPARATOR);
define('DIR_AUTOMATIC', 'D:'.DIRECTORY_SEPARATOR.'Automatic'.DIRECTORY_SEPARATOR);
define('DIR_WORKING', 'D:'.DIRECTORY_SEPARATOR.'Working'.DIRECTORY_SEPARATOR);

define('HANDBRAKE_FILE', 'C:'.DIRECTORY_SEPARATOR.'TiVo2'.DIRECTORY_SEPARATOR.'handbrake'.DIRECTORY_SEPARATOR.'handbrake.exe -i "%s" -o "%s.m4v" --main-feature -e x265 --two-pass --audio-lang-list eng --first-audio --normalize-mix 1 --drc 2.5 --keep-display-aspect --native-language eng --native-dub');
define('HANDBRAKE_SCAN', 'C:'.DIRECTORY_SEPARATOR.'TiVo2'.DIRECTORY_SEPARATOR.'handbrake'.DIRECTORY_SEPARATOR.'handbrake.exe -i "%s" --title 0 -e x265 --min-duration 1200 --two-pass --audio-lang-list eng --first-audio --normalize-mix 1 --drc 2.5 --keep-display-aspect --native-language eng --native-dub');
define('HANDBRAKE_DVD', 'C:'.DIRECTORY_SEPARATOR.'TiVo2'.DIRECTORY_SEPARATOR.'handbrake'.DIRECTORY_SEPARATOR.'handbrake.exe -i "%s" -o "%s.m4v" --title %d -e x265 --two-pass --audio-lang-list eng --first-audio --normalize-mix 1 --drc 2.5 --keep-display-aspect --native-language eng --native-dub');

define('CSCRIPT', 'C:'.DIRECTORY_SEPARATOR.'Windows'.DIRECTORY_SEPARATOR.'SysWoW64'.DIRECTORY_SEPARATOR.'cscript /nologo "%s"');

function shell_clean () {
	
}
