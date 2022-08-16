<?php
	function slug($string){
		$string = preg_replace('~[^\\pL\d]+~u', '-', $string);
		$string = trim($string, '-');
		$string = iconv('utf-8', 'us-ascii//TRANSLIT', $string);
		$string = strtolower($string);
		$string = preg_replace('~[^-\w]+~', '', $string);

  		if (empty($string))
  		{
    		return 'n-a';
  		}

  		return $string;
	}

	function file_upload_path($orginal_filename){		
		return dirname(__FILE__) . DIRECTORY_SEPARATOR . 'uploads'. DIRECTORY_SEPARATOR. basename($orginal_filename);
	}

	function file_is_allowed_image($tmp_path, $new_path){
		$allowed_file_extensions = ['jpg', 'png', 'gif', 'pdf'];
    	$allowed_mime_types = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];

		$actual_file_extension = pathinfo($new_path, PATHINFO_EXTENSION);
		$actual_mime_type = mime_content_type($tmp_path);
	
		$file_extension_is_valid = in_array($actual_file_extension, $allowed_file_extensions);
    	$mime_type_is_valid = in_array($actual_mime_type, $allowed_mime_types);
       
    	return $file_extension_is_valid && $mime_type_is_valid;
	}
	
	function alert($msg) {
	    echo "<script type='text/javascript'>alert('$msg');</script>";
	}
?>