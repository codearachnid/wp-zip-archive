<?php

/**
 * WordPress Zip Archive Class
 * 
 * @hattip inspiration from https://github.com/bradvin/wp-zip-generator
 */

if ( !class_exists( 'WP_Zip_Archive' ) ) {
  class WP_Zip_Archive{
    $errors = array();
    $settings = array();
    function __constructor( $args = null ){
      $defaults = array(
				'archive_filename'     => '',
				'file_list'            => array(),
				'scan_dir'             => array(),
				'tmp_dir'              => '',
				'download_filename'    => '', // if different than archive_filename
				'exclude'              => array('.git', '.svn', '.DS_Store', '.gitignore', '.', '..')
			);
      $this->settings = wp_parse_args( $args, $defaults );
    }
    function check_compatibility(){
      if( $missing_lib = $this->required_lib_exists() )
        $this->errors[] = $missing_lib;
      return empty( $this->errors );
    }
    function required_lib_exists(){
    	// require the ZipArchive class and that PHP version be above 5.2.0
      if( !class_exists('ZipArchive') && version_compare( PHP_VERSION, '5.2.0', '<' ) ){
        return new WP_Error('broke', __( 'WP_Zip_Archive requires that the PHP version be above 5.2.0 and ZipArchive is available', 'wp-zip-archive' ) );
      } else {
        return true;
      }
    }
  }
}
