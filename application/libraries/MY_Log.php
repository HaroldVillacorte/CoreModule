<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Extends the CodeIgniter Log class.
 */
class MY_Log extends CI_Log {

    /**
     * Array of view files that have been loaded.
     *
     * @var type
     */
    public $view_files_loaded;

	/**
	 * Write Log File
	 *
	 * Generally this function will be called using the global log_message() function
	 *
	 * @param	string	the error level
	 * @param	string	the error message
	 * @param	bool	whether the error is a native PHP error
	 * @return	bool
	 */
	public function write_log($level = 'error', $msg, $php_error = FALSE)
	{
        //------ The extension.  Adds view files to the public $file_array.--------//
        if (strstr($msg, 'File loaded') && strstr($msg, 'views'))
        {
            $this->file_array[] = $msg;
        }
        //------End extension------------------------------------------------------//

		if ($this->_enabled === FALSE)
		{
			return FALSE;
		}

		$level = strtoupper($level);

		if ( ! isset($this->_levels[$level]) OR ($this->_levels[$level] > $this->_threshold))
		{
			return FALSE;
		}

		$filepath = $this->_log_path.'log-'.date('Y-m-d').'.php';
		$message  = '';

		if ( ! file_exists($filepath))
		{
			$message .= "<"."?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?".">\n\n";
		}

		if ( ! $fp = @fopen($filepath, FOPEN_WRITE_CREATE))
		{
			return FALSE;
		}

		$message .= $level.' '.(($level == 'INFO') ? ' -' : '-').' '.date($this->_date_fmt). ' --> '.$msg."\n";

		flock($fp, LOCK_EX);
		fwrite($fp, $message);
		flock($fp, LOCK_UN);
		fclose($fp);

		@chmod($filepath, FILE_WRITE_MODE);
		return TRUE;
	}

}
// END MY_Log Class

/* End of file MY_Log.php */
/* Location: ./application/libraries/Log.php */