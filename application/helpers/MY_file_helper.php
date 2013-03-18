<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// --------------------------------------------------------------------

/**
 * Get Directory File Information
 *
 * Reads the specified directory and builds an array containing the filenames,
 * filesize, dates, and permissions
 *
 * Any sub-folders contained within the specified path are read as well.
 *
 * @access	public
 * @param	string	path to source
 * @param	bool	Look only at the top level directory specified?
 * @param	bool	internal variable to determine recursion status - do not use in calls
 * @return	array
 */
if ( ! function_exists('get_dir_file_info'))
{
	function get_dir_file_info($source_dir, $top_level_only = TRUE, $_recursion = FALSE)
	{
		static $_filedata = array();
		$relative_path = $source_dir;

		if ($fp = @opendir($source_dir))
		{
			// reset the array and make sure $source_dir has a trailing slash on the initial call
			if ($_recursion === FALSE)
			{
				$_filedata = array();
				$source_dir = rtrim(realpath($source_dir), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
			}

			// foreach (scandir($source_dir, 1) as $file) // In addition to being PHP5+, scandir() is simply not as fast
			while (FALSE !== ($file = readdir($fp)))
			{
				if (@is_dir($source_dir.$file) AND strncmp($file, '.', 1) !== 0 AND $top_level_only === FALSE)
				{
					get_dir_file_info($source_dir.$file.DIRECTORY_SEPARATOR, $top_level_only, TRUE);
				}
				elseif (strncmp($file, '.', 1) !== 0)
				{

                    /**
                     * Changed the $filedata array to a non-indexed array to be
                     * customized because files with duplicate names were being
                     * excluded.
                     */
                    $_filedata[] = get_file_info($source_dir.$file);

                    //$_filedata[$file] = get_file_info($source_dir.$file);
					//$_filedata[$file]['relative_path'] = $relative_path;

				}
			}

            // Set the array to be customized.
            $new_filedata = array();

            /**
             * Customize the array to have a custom relative path as the key.
             */
            foreach ($_filedata as $key => $value)
            {
                // Changed 'relative_path' to exclude the $source_dir.
                $relative_path = str_replace($source_dir, '', $_filedata[$key]['server_path']);

                // Set the new array index to ralative path.
                $new_filedata[$relative_path] = $_filedata[$key];

                // add relative path as index.
                $new_filedata[$relative_path]['relative_path'] = $relative_path;
            }

            /**
             * Return custom array.
             */
            return $new_filedata;

			//return $_filedata;
		}
		else
		{
			return FALSE;
		}
	}
}

/* End of file MY_file_helper.php */
/* Location: ./application/helpers/MY_file_helper.php */