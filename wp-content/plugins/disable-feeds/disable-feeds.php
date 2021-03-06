<?php
/*
Plugin Name: Disable Feeds
Plugin URI: http://wordpress.org/extend/plugins/disable-feeds/
Description: Disable all RSS/Atom feeds on your WordPress site.
Version: 1.2.1
Author: Samir Shah
Author URI: http://rayofsolaris.net/
License: GPLv2 or later
*/

if( !defined( 'ABSPATH' ) )
	exit;

class Disable_Feeds {
	function __construct() {
		if( is_admin() ) {
			add_action( 'admin_init', array( $this, 'admin_setup' ) );
		}
		else {
			add_action( 'wp_loaded', array( $this, 'remove_links' ) );
			add_filter( 'parse_query', array( $this, 'filter_query' ) );
		}
	}
	
	function admin_setup() {
		add_settings_field( 'disable_feeds_redirect', 'Disable Feeds Plugin', array( $this, 'settings_field' ), 'reading' );
		register_setting( 'reading', 'disable_feeds_redirect' );
		register_setting( 'reading', 'disable_feeds_allow_main' );
	}
	
	function settings_field() {
		$redirect = $this->redirect_status();
		echo '<p>The <em>Disable Feeds</em> plugin is active, By default, all feeds are disabled, and all requests for feeds are redirected to the corresponding HTML content. You can tweak this behaviour below.</p>';
		echo '<p><input type="radio" name="disable_feeds_redirect" value="on" id="disable_feeds_redirect_yes" class="radio" ' . checked( $redirect, 'on', false ) . '/><label for="disable_feeds_redirect_yes"> Redirect feed requests to corresponding HTML content</label><br /><input type="radio" name="disable_feeds_redirect" value="off" id="disable_feeds_redirect_no" class="radio" ' . checked( $redirect, 'off', false ) . '/><label for="disable_feeds_redirect_no"> Issue a Page Not Found (404) error for feed requests</label></p>';
		echo '<p><input type="checkbox" name="disable_feeds_allow_main" value="on" id="disable_feeds_allow_main" ' . checked( $this->allow_main(), true, false ) . '/><label for="disable_feeds_allow_main"> Do not disable the <strong>global post feed</strong> and <strong>global comment feed</strong></label></p>';
	}
	
	function remove_links() {
		remove_action( 'wp_head', 'feed_links', 2 );
		remove_action( 'wp_head', 'feed_links_extra', 3 );
	}
	
	function filter_query( $wp_query ) {
		if( !is_feed() )
			return;

		if( $this->allow_main()
			&& ! ( is_singular() || is_archive() || is_date() || is_author() || is_category() || is_tag() || is_tax() || is_search() ) )
			return;

		if( $this->redirect_status() == 'on' ) {
			if( isset( $_GET['feed'] ) ) {
				wp_redirect( remove_query_arg( 'feed' ), 301 );
				exit;
			}

			if( get_query_var( 'feed' ) !== 'old' )	// WP redirects these anyway, and removing the query var will confuse it thoroughly
				set_query_var( 'feed', '' );
			redirect_canonical();	// Let WP figure out the appropriate redirect URL.
		}
		else {
			$wp_query->is_feed = false;
			$wp_query->set_404();
			status_header( 404 );
		}
	}
	
	private function redirect_status() {
		$r = get_option( 'disable_feeds_redirect', 'on' );
		// back compat
		if( is_bool( $r ) ) {
			$r = $r ? 'on' : 'off';
			update_option( 'disable_feeds_redirect', $r );
		}
		return $r;
	}
	
	private function allow_main() {
		return ( get_option( 'disable_feeds_allow_main', 'off' ) == 'on' );
	}
}

new Disable_Feeds();
