<?php
if ( !defined('ABSPATH') && !defined('WP_UNINSTALL_PLUGIN') ) {
    exit();
}
	delete_option('othersread_settings');

  global $wpdb;
	// Delete meta
	$sql = "DELETE FROM ".$wpdb->postmeta." WHERE `meta_key` = 'othersalsoread'";
	$wpdb->query($sql);
?>
