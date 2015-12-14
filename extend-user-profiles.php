<?php
/*
 * Plugin Name: Extend User Profiles
 * Version: 1.0
 * Plugin URI: http://www.dfwbuddhist.com/
 * Description: Extends WordPress user profiles to include additional data such as phone number, address and etc.
 * Author: Keith Wickramasekara
 * Author URI: http://www.keithw.me/
 * Requires at least: 4.0
 * Tested up to: 4.0
 *
 * Text Domain: extend-user-profiles
 * Domain Path: /lang/
 *
 * @package WordPress
 * @author Keith Wickramasekara
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Load plugin class files
require_once( 'includes/class-extend-user-profiles.php' );

/**
 * Returns the main instance of Extend_User_Profiles to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object Extend_User_Profiles
 */
function Extend_User_Profiles () {
	$instance = Extend_User_Profiles::instance( __FILE__, '1.0.0' );

	return $instance;
}

Extend_User_Profiles();
