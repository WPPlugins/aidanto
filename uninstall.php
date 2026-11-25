<?php
defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

// The main plugin file is not loaded during uninstall, so the option name is repeated here.
if ( is_multisite() ) {
	foreach ( get_sites( array( 'fields' => 'ids', 'number' => 0 ) ) as $aidanto_site ) {
		switch_to_blog( $aidanto_site );
		delete_option( 'aidanto_settings' );
		restore_current_blog();
	}
} else {
	delete_option( 'aidanto_settings' );
}
