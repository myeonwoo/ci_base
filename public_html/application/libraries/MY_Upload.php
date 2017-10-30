<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	upload.php ÀçÁ¤ÀÇ
*/
class MY_Upload extends CI_Upload {
	function __construct($props = array()) {
		parent::__construct($props);
	}

	/**
	 * Verify that the filetype is allowed
	 *
	 * @return	bool
	 */
	public function is_allowed_filetype($ignore_mime = FALSE)
	{
		$dangi_allowed_types = $this->get_dangi_allowed_types();

		$ext = strtolower(ltrim($this->file_ext, '.'));

		if ( ! in_array($ext, $dangi_allowed_types))
		{
			return FALSE;
		}
		
		if ($this->allowed_types == '*')
		{
			return TRUE;
		}

		if (count($this->allowed_types) == 0 OR ! is_array($this->allowed_types))
		{
			$this->set_error('upload_no_file_types');
			return FALSE;
		}


		if ( ! in_array($ext, $this->allowed_types))
		{
			return FALSE;
		}

		// Images get some additional checks
		$image_types = array('gif', 'jpg', 'jpeg', 'png', 'jpe');

		if (in_array($ext, $image_types))
		{
			if (getimagesize($this->file_temp) === FALSE)
			{
				return FALSE;
			}
		}

		if ($ignore_mime === TRUE)
		{
			return TRUE;
		}

		$mime = $this->mimes_types($ext);

		if (is_array($mime))
		{
			if (in_array($this->file_type, $mime, TRUE))
			{
				return TRUE;
			}
		}
		elseif ($mime == $this->file_type)
		{
				return TRUE;
		}

		return FALSE;
	}

	/**
	 * List of dangi allowed types
	 *
	 *
	 * @param	string
	 * @return	array
	 */
	public function get_dangi_allowed_types()
	{
		$this->CI =& get_instance();
		$this->CI->config->load('allowed_types');
		return $this->CI->config->item('dangi_allowed_types');
	}
}