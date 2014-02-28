<?php

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
      if( !class_exists() ){
        return new WP_Error('broke', __("I've fallen and can't get up"));
      } else {
        return true;
      }
    }
  }
}
