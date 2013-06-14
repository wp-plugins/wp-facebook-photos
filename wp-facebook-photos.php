<?php
	/*
		Plugin Name: Wordpress Facebook Photos
		Plugin URI: http://wordpress.org/extend/plugins/wp-facebook-photos
		Description: Connect your facebook account and add photo galleries to your posts and pages!
		Version: 0.4
		Author: Patrick Cason
		Author URI: https://www.patrickcason.com
		License: GPL2
		
		Copyright 2013  Patrick Cason  (email : me@patrickcason.com)

		This program is free software; you can redistribute it and/or modify
		it under the terms of the GNU General Public License, version 2, as 
		published by the Free Software Foundation.
	
		This program is distributed in the hope that it will be useful,
		but WITHOUT ANY WARRANTY; without even the implied warranty of
		MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
		GNU General Public License for more details.
	
		You should have received a copy of the GNU General Public License
		along with this program; if not, write to the Free Software
		Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
	*/
	
	error_reporting(0);
	
	require("admin-page.php");
	require("shortcode-gen.php");
	
	$wpfbp_options = get_option('wpfbp_settings');
	$wpfbp_fb_options = get_option('wpfbp_fb_settings');
	$plugin_dir = get_bloginfo('home')."/wp-content/plugins/wp-facebook-photos";
	
	wp_register_style('mainCSS', $plugin_dir."/style.css");
	wp_enqueue_style('mainCSS');
 
	wp_enqueue_script('jquery');
	wp_register_script('mainJS', $plugin_dir."/functions.js", true);
	wp_enqueue_script('mainJS');
?>