<?php
/**
 * Plugin Name:       Aidanto – AI Customer Service Chat
 * Plugin URI:        https://aidanto.com/website-chat
 * Description:       Adds the Aidanto website chat to your site: an AI employee that answers your customers, books appointments and hands the conversation to you.
 * Version:           1.0.0
 * Requires at least: 6.4
 * Requires PHP:      7.4
 * Author:            Aidanto
 * Author URI:        https://aidanto.com/
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       aidanto
 *
 * @package Aidanto
 */

defined( 'ABSPATH' ) || exit;

define( 'AIDANTO_PLUGIN_FILE', __FILE__ );
define( 'AIDANTO_OPTION', 'aidanto_settings' );
define( 'AIDANTO_SCRIPT_URL', 'https://channels.aidanto.com/embed/v1.js' );
define( 'AIDANTO_SCRIPT_HANDLE', 'aidanto-chat' );
define( 'AIDANTO_SITE_ID_PATTERN', '/\Asite_[A-Za-z0-9_-]{24,120}\z/' );

// A stored value is checked again on read: the option can be changed outside the settings page.
function aidanto_get_settings() {
	$saved   = get_option( AIDANTO_OPTION );
	$saved   = is_array( $saved ) ? $saved : array();
	$site_id = isset( $saved['site_id'] ) && is_string( $saved['site_id'] ) ? $saved['site_id'] : '';

	return array(
		'site_id' => preg_match( AIDANTO_SITE_ID_PATTERN, $site_id ) ? $site_id : '',
		'enabled' => ! isset( $saved['enabled'] ) || (bool) $saved['enabled'],
	);
}

function aidanto_enqueue_chat() {
	$settings = aidanto_get_settings();
	if ( ! $settings['enabled'] || '' === $settings['site_id'] ) {
		return;
	}

	// phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion -- The address is versioned by its path.
	wp_enqueue_script( AIDANTO_SCRIPT_HANDLE, AIDANTO_SCRIPT_URL, array(), null, array( 'in_footer' => true ) );
}
add_action( 'wp_enqueue_scripts', 'aidanto_enqueue_chat' );

function aidanto_script_attributes( $attributes ) {
	if ( isset( $attributes['id'] ) && AIDANTO_SCRIPT_HANDLE . '-js' === $attributes['id'] ) {
		$settings                   = aidanto_get_settings();
		$attributes['data-site-id'] = $settings['site_id'];
	}

	return $attributes;
}
add_filter( 'wp_script_attributes', 'aidanto_script_attributes' );

if ( is_admin() ) {
	require_once __DIR__ . '/includes/settings.php';
}
