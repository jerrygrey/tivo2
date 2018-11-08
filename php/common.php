function scan_directory ( $item ) {
	
	if (is_dir($item)) {
		
		$array = [];
		
		$files = glob($item.'*', GLOB_MARK);
		
		foreach ($files as $file) {
			$array = $array + scan_directory($file);
		}
		
		return $array;
		
	}
	
	return $item;
	
}
