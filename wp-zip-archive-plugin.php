<?php
/*
Plugin Name: Zip Archive Class
Plugin URI: 
Description: This plugin is a wrapper for the class mechanism to make bundling your archive files easier.
Version: 1.0
Author: Timothy Wood @codearachnid
Author URI: http://www.codearachnid.com
License: GPL v3

WordPress Reset Slugs
Copyright (C) 2014, Timothy Wood - tim@imaginesimplicity.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

// Don't load directly
if ( !defined( 'ABSPATH' ) )
	die( '-1' );

add_action( 'admin_init', 'wp_zip_archive_plugin' );
function wp_zip_archive_plugin(){
	if( !class_exists('WP_Zip_Archive'))
		include_once 'class.wp-zip-archive.php';

	// setup your archive arguments
	$args = array(
		'archive_name' => 'sample.zip' 
		);

	// init the class
	$zip = new WP_Zip_Archive( $args );

	// generate the zip
	$zip->create();

	// download
	// $zip->download();
}
