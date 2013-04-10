<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Extends the PArser class.
 */
class MY_Parser extends CI_Parser
{
	/**
	 *  Parse a template
	 *
	 * Parses pseudo-variables contained in the specified template,
	 * replacing them with the data in the second param
	 *
	 * @access	public
	 * @param	string
	 * @param	array
	 * @param	bool
	 * @return	string
	 */
	function _parse($template, $data, $return = FALSE)
	{
		if ($template == '')
		{
			return FALSE;
		}

		foreach ($data as $key => $val)
		{
			if (is_array($val))
			{
				$template = $this->_parse_pair($key, $val, $template);
			}

            // Added (!is_object) condition to fix a bug when the parser tries to
            // parse objects.
			elseif (!is_object($val))
			{
				$template = $this->_parse_single($key, (string)$val, $template);
			}
		}

		if ($return == FALSE)
		{
			$CI =& get_instance();
			$CI->output->append_output($template);
		}

		return $template;
	}
}
/* End of file Parser.php */
/* Location: ./system/libraries/Parser.php */
