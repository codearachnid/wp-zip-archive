<?php

/**
 * WordPress Zip Archive Class
 * 
 * @hattip inspiration from https://github.com/bradvin/wp-zip-generator
 */

// Don't load directly
if ( !defined( 'ABSPATH' ) )
    die( '-1' );

if ( !class_exists( 'WP_Zip_Archive' ) ) {
    class WP_Zip_Archive{

        private $errors = null;
        private $settings = array();

        function __construct( $args = null ){

            $this->errors = new WP_Error();
            $this->upload_dir = wp_upload_dir();
            $defaults = array(
                'archive_name'  => null, // required
                'file_list'     => array(),
                'scan_dir'      => array(),
                'save_dir'      => trailingslashit( $this->upload_dir['basedir'] ) . 'zip-archive/',
                'save_file'     => null,
                'exclude'       => array('.git', '.svn', '.DS_Store', '.gitignore', '.', '..')
        		);
            $this->settings = wp_parse_args( $args, $defaults );

            $this->check_compatibility();
            $this->validate_settings();

        }

        function validate_settings(){
            // validate the archive filename
            if( empty( $this->settings['archive_name'] ) ) {
                $this->errors->add('fatal', __( 'WP_Zip_Archive requires an archive filename to be set.', 'wp-zip-archive' ) );
            } else {
                $this->settings['archive_name'] = sanitize_title_with_dashes( $this->settings['archive_name'] );

                // add .zip extension if it doesn't exist
                if( ! $this->file_has_ext( $this->settings['archive_name'], 'zip') )
                    $this->settings['archive_name'] .= '.zip';
            }

            if( empty( $this->settings['save_dir'] ) ){
                $this->errors->add('fatal', __( 'WP_Zip_Archive requires a directory to save the zip to (at least temporarily.', 'wp-zip-archive' ) );
            } else {
                if( wp_mkdir_p( $this->settings['save_dir'] ) && empty( $this->settings['save_file'] ) ){
                    $this->settings['save_file'] = trailingslashit( $this->settings['save_dir'] ) . $this->settings['archive_name'];
                }
            }

            if( empty( $this->settings['file_list'] ) && empty( $this->settings[ 'scan_dir' ] ) ){
                $this->errors->add('fatal', __( 'WP_Zip_Archive requires either a file list or folder(s) to add files to the zip.', 'wp-zip-archive' ) );
            }

            return $this->has_errors();
        }

        function create(){
            if( ! $this->has_errors() ){
                $zip = new ZipArchive;
                // print_r($this->get_errors()->errors);
                echo  $this->settings['save_file'];
                $zip->open( $this->settings['save_file'], ZipArchive::CREATE && ZipArchive::OVERWRITE );
                $zip->close();
            } else {
                $this->errors->add('fatal', __( 'WP_Zip_Archive cannot continue because settings do not validate.', 'wp-zip-archive' ) );
            }
        }

        function has_errors(){
            return ! empty( $this->get_errors()->errors );
        }

        function get_errors(){
            return $this->errors;
        }

        /**
         * Send the download headers to the browser
         * @param bool $delete
         */
        function download( $delete = true ) {
            header( 'Content-type: application/zip' );
            header( sprintf( 'Content-Disposition: attachment; filename="%s"', $this->settings['download_filename'] ) );
            readfile( $this->settings['zip_temp_filename'] );

            // remove file if true
            if ( $delete )
                unlink( $this->settings['zip_temp_filename'] );
        }

        /**
         * determine if file has a requested file extension
         * @param  string $file
         * @param  string $ext
         * @return bool
         */
        function file_has_ext( $file, $ext = null ) {
            return substr(strrchr($file,'.'),1) === $ext;
        }

        function check_compatibility(){
            // require the ZipArchive class and that PHP version be above 5.2.0
            if( !class_exists('ZipArchive') && version_compare( PHP_VERSION, '5.2.0', '<' )  )
                $this->errors->add( 'fatal', __( 'WP_Zip_Archive requires that the PHP version be above 5.2.0 and ZipArchive is available', 'wp-zip-archive' ) );

            return $this->has_errors();
        }
    }
}
