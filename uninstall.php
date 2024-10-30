<?php
/**
 * Uninstall
 *
 * @package Child Pages Card
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

$option_name = 'childpagescard_settings';
$option_name2 = 'childpagescard_template';

/* For Single site */
if ( ! is_multisite() ) {
	delete_option( $option_name );
	delete_option( $option_name2 );
} else {
	/* For Multisite */
	global $wpdb;
	$blog_ids = $wpdb->get_col( "SELECT blog_id FROM {$wpdb->prefix}blogs" );
	$original_blog_id = get_current_blog_id();
	foreach ( $blog_ids as $blogid ) {
		switch_to_blog( $blogid );
		delete_option( $option_name );
		delete_option( $option_name2 );
	}
	switch_to_blog( $original_blog_id );

	/* For site options. */
	delete_site_option( $option_name );
	delete_site_option( $option_name2 );
}
