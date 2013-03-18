<?php

require_once 'rain.tpl.class.php';

/**
 * This file extends RainTPL 2.7.2.
 */
class Ext_raintpl extends Raintpl
{

    /**
	 * Draw the module template
	 * eg. 	$html = $tpl->draw( 'demo', TRUE ); // return template in string
	 * or 	$tpl->draw( $tpl_name ); // echo the template
	 *
	 * @param string $tpl_name  template to load
	 * @param boolean $return_string  true=return a string, false=echo the template
	 * @return string
	 */

	function module_draw( $tpl_name, $return_string = false ){

		try {
			// compile the template if necessary and set the template filepath
			$this->check_template( $tpl_name );
		} catch (RainTpl_Exception $e) {
			$output = $this->printDebug($e);
			die($output);
		}

		// Cache is off and, return_string is false
        // Rain just echo the template

        if( !$this->cache && !$return_string ){
            extract( $this->var );
            include $this->tpl['compiled_filename'];
            unset( $this->tpl );
        }


		// cache or return_string are enabled
        // rain get the output buffer to save the output in the cache or to return it as string

        else{

            //----------------------
            // get the output buffer
            //----------------------
                ob_start();
                extract( $this->var );
                include $this->tpl['compiled_filename'];
                $raintpl_contents = ob_get_clean();
            //----------------------


            // save the output in the cache
            if( $this->cache )
                file_put_contents( $this->tpl['cache_filename'], "<?php if(!class_exists('raintpl')){exit;}?>" . $raintpl_contents );

            // free memory
            unset( $this->tpl );

            // return or print the template
            if( $return_string ) return $raintpl_contents; else echo $raintpl_contents;

        }

	}

	/**
	 * replace the path of image src, link href and a href.
	 * url => template_dir/url
	 * url# => url
	 * http://url => http://url
	 *
	 * @param string $html
	 * @return string html sostituito
	 */
	protected function path_replace( $html, $tpl_basedir ){

		if( self::$path_replace ){

			//$tpl_dir = self::$base_url . self::$tpl_dir . $tpl_basedir;
            $tpl_dir = self::$base_url . '/';

			// reduce the path
			$path = $this->reduce_path($tpl_dir);

			$exp = $sub = array();

			if( in_array( "img", self::$path_replace_list ) ){
				$exp = array( '/<img(.*?)src=(?:")(http|https)\:\/\/([^"]+?)(?:")/i', '/<img(.*?)src=(?:")([^"]+?)#(?:")/i', '/<img(.*?)src="(.*?)"/', '/<img(.*?)src=(?:\@)([^"]+?)(?:\@)/i' );
				$sub = array( '<img$1src=@$2://$3@', '<img$1src=@$2@', '<img$1src="' . $path . '$2"', '<img$1src="$2"' );
			}

			if( in_array( "script", self::$path_replace_list ) ){
				$exp = array_merge( $exp , array( '/<script(.*?)src=(?:")(http|https)\:\/\/([^"]+?)(?:")/i', '/<script(.*?)src=(?:")([^"]+?)#(?:")/i', '/<script(.*?)src="(.*?)"/', '/<script(.*?)src=(?:\@)([^"]+?)(?:\@)/i' ) );
				$sub = array_merge( $sub , array( '<script$1src=@$2://$3@', '<script$1src=@$2@', '<script$1src="' . $path . '$2"', '<script$1src="$2"' ) );
			}

			if( in_array( "link", self::$path_replace_list ) ){
				$exp = array_merge( $exp , array( '/<link(.*?)href=(?:")(http|https)\:\/\/([^"]+?)(?:")/i', '/<link(.*?)href=(?:")([^"]+?)#(?:")/i', '/<link(.*?)href="(.*?)"/', '/<link(.*?)href=(?:\@)([^"]+?)(?:\@)/i' ) );
				$sub = array_merge( $sub , array( '<link$1href=@$2://$3@', '<link$1href=@$2@' , '<link$1href="' . $path . '$2"', '<link$1href="$2"' ) );
			}

			if( in_array( "a", self::$path_replace_list ) ){
				$exp = array_merge( $exp , array( '/<a(.*?)href=(?:")(http\:\/\/|https\:\/\/|javascript:)([^"]+?)(?:")/i', '/<a(.*?)href="(.*?)"/', '/<a(.*?)href=(?:\@)([^"]+?)(?:\@)/i'  ) );
				$sub = array_merge( $sub , array( '<a$1href=@$2$3@', '<a$1href="' . self::$base_url . '$2"', '<a$1href="$2"' ) );
			}

			if( in_array( "input", self::$path_replace_list ) ){
				$exp = array_merge( $exp , array( '/<input(.*?)src=(?:")(http|https)\:\/\/([^"]+?)(?:")/i', '/<input(.*?)src=(?:")([^"]+?)#(?:")/i', '/<input(.*?)src="(.*?)"/', '/<input(.*?)src=(?:\@)([^"]+?)(?:\@)/i' ) );
				$sub = array_merge( $sub , array( '<input$1src=@$2://$3@', '<input$1src=@$2@', '<input$1src="' . $path . '$2"', '<input$1src="$2"' ) );
			}

			return preg_replace( $exp, $sub, $html );

		}
		else
			return $html;

	}
}

// -- end
