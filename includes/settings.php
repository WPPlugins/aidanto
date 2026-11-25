<?php
defined( 'ABSPATH' ) || exit;

define( 'AIDANTO_SETTINGS_PAGE', 'aidanto' );

function aidanto_register_settings() {
	register_setting(
		AIDANTO_SETTINGS_PAGE,
		AIDANTO_OPTION,
		array(
			'type'              => 'array',
			'sanitize_callback' => 'aidanto_sanitize_settings',
			'show_in_rest'      => false,
		)
	);
	add_settings_section( 'aidanto_chat', '', '__return_false', AIDANTO_SETTINGS_PAGE );
	add_settings_field(
		'aidanto_site_id',
		__( 'Website code or site ID', 'aidanto' ),
		'aidanto_render_site_id_field',
		AIDANTO_SETTINGS_PAGE,
		'aidanto_chat',
		array( 'label_for' => 'aidanto_site_id' )
	);
	add_settings_field(
		'aidanto_enabled',
		__( 'Chat on this site', 'aidanto' ),
		'aidanto_render_enabled_field',
		AIDANTO_SETTINGS_PAGE,
		'aidanto_chat'
	);
}
add_action( 'admin_init', 'aidanto_register_settings' );

function aidanto_add_settings_page() {
	add_options_page(
		__( 'Aidanto', 'aidanto' ),
		__( 'Aidanto', 'aidanto' ),
		'manage_options',
		AIDANTO_SETTINGS_PAGE,
		'aidanto_render_settings_page'
	);
}
add_action( 'admin_menu', 'aidanto_add_settings_page' );

function aidanto_action_links( $links ) {
	$url = admin_url( 'options-general.php?page=' . AIDANTO_SETTINGS_PAGE );
	array_unshift( $links, '<a href="' . esc_url( $url ) . '">' . esc_html__( 'Settings', 'aidanto' ) . '</a>' );

	return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( AIDANTO_PLUGIN_FILE ), 'aidanto_action_links' );

// Accepts a bare site ID or the whole code from Aidanto; only the production chat host is trusted.
function aidanto_extract_site_id( $value ) {
	if ( preg_match( AIDANTO_SITE_ID_PATTERN, $value ) ) {
		return $value;
	}

	$tags = new WP_HTML_Tag_Processor( $value );
	if ( $tags->next_tag( 'script' ) ) {
		if ( AIDANTO_SCRIPT_URL !== $tags->get_attribute( 'src' ) ) {
			return new WP_Error(
				'aidanto_other_address',
				__( 'This code does not load the Aidanto chat. Copy the code from the “Widget on your website” card in Aidanto and paste it here.', 'aidanto' )
			);
		}
		$site_id = $tags->get_attribute( 'data-site-id' );
		if ( is_string( $site_id ) && preg_match( AIDANTO_SITE_ID_PATTERN, $site_id ) ) {
			return $site_id;
		}
	}

	return new WP_Error(
		'aidanto_invalid_site_id',
		__( 'This is not an Aidanto website code or site ID. Copy the code from the “Widget on your website” card in Aidanto and paste it whole, or paste only the site ID that starts with “site_”.', 'aidanto' )
	);
}

// WordPress runs this twice when the option is first created, so its result must pass through unchanged.
function aidanto_sanitize_settings( $input ) {
	$saved   = aidanto_get_settings();
	$input   = is_array( $input ) ? $input : array();
	$value   = isset( $input['site_id'] ) && is_string( $input['site_id'] ) ? trim( $input['site_id'] ) : '';
	$site_id = '' === $value ? '' : aidanto_extract_site_id( $value );

	if ( is_wp_error( $site_id ) ) {
		add_settings_error( AIDANTO_OPTION, $site_id->get_error_code(), $site_id->get_error_message() );
		$site_id = $saved['site_id'];
	}

	return array(
		'site_id' => $site_id,
		'enabled' => ! empty( $input['enabled'] ),
	);
}

function aidanto_site_address() {
	$parts = wp_parse_url( home_url() );
	if ( empty( $parts['scheme'] ) || empty( $parts['host'] ) ) {
		return '';
	}

	return $parts['scheme'] . '://' . $parts['host'] . ( empty( $parts['port'] ) ? '' : ':' . $parts['port'] );
}

function aidanto_render_site_id_field() {
	$settings = aidanto_get_settings();
	?>
	<textarea id="aidanto_site_id" name="<?php echo esc_attr( AIDANTO_OPTION ); ?>[site_id]" rows="3" class="large-text code" spellcheck="false" aria-describedby="aidanto_site_id_description"><?php echo esc_textarea( $settings['site_id'] ); ?></textarea>
	<p class="description" id="aidanto_site_id_description">
		<?php esc_html_e( 'Paste the code from the “Widget on your website” card in Aidanto, or only the site ID that starts with “site_”.', 'aidanto' ); ?>
	</p>
	<?php
}

function aidanto_render_enabled_field() {
	$settings = aidanto_get_settings();
	?>
	<label for="aidanto_enabled">
		<input type="checkbox" id="aidanto_enabled" name="<?php echo esc_attr( AIDANTO_OPTION ); ?>[enabled]" value="1" <?php checked( $settings['enabled'] ); ?> />
		<?php esc_html_e( 'Show the chat on this site', 'aidanto' ); ?>
	</label>
	<p class="description">
		<?php esc_html_e( 'Clear this box to hide the chat. The code stays saved.', 'aidanto' ); ?>
	</p>
	<?php
}

function aidanto_render_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$address = aidanto_site_address();
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<p>
			<?php esc_html_e( 'Aidanto is an AI employee that answers your customers in the chat on your website, books appointments and hands the conversation to you when a person is needed.', 'aidanto' ); ?>
			<?php
			printf(
				/* translators: %s: link to the Aidanto website. */
				esc_html__( 'No Aidanto account yet? %s.', 'aidanto' ),
				'<a href="' . esc_url( 'https://aidanto.com/' ) . '" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Start a free 14-day trial', 'aidanto' ) . '</a>'
			);
			?>
		</p>

		<?php if ( 0 !== strpos( $address, 'https://' ) ) : ?>
			<div class="notice notice-warning inline">
				<p><?php esc_html_e( 'The address of this site does not start with https. Aidanto accepts only https website addresses, so the chat will not open here. For a local or test copy of the site, use the test code from “More settings” in Aidanto.', 'aidanto' ); ?></p>
			</div>
		<?php endif; ?>

		<form action="options.php" method="post">
			<?php
			settings_fields( AIDANTO_SETTINGS_PAGE );
			do_settings_sections( AIDANTO_SETTINGS_PAGE );
			submit_button();
			?>
		</form>

		<h2><?php esc_html_e( 'Where to get the code', 'aidanto' ); ?></h2>
		<ol>
			<li><?php esc_html_e( 'In Aidanto, open “AI employees”, choose an employee and go to the “Channels” tab.', 'aidanto' ); ?></li>
			<li><?php esc_html_e( 'In the “Web chat” block, click “Turn on web chat”.', 'aidanto' ); ?></li>
			<li>
				<?php
				printf(
					/* translators: %s: address of this WordPress site. */
					esc_html__( 'In the “Widget on your website” card, enter the address of this site in “Your website URL” and click “Prepare code”: %s', 'aidanto' ),
					'<code>' . esc_html( $address ) . '</code>'
				);
				?>
			</li>
			<li><?php esc_html_e( 'Click “Copy code”, paste the code into the field above and save.', 'aidanto' ); ?></li>
		</ol>
		<p><?php esc_html_e( 'The chat opens only on the website address you entered in Aidanto, with or without “www”. If the address there is different, visitors see the chat button, but the chat window stays empty.', 'aidanto' ); ?></p>
		<p><?php esc_html_e( 'If you click “New website code” in Aidanto, the old code stops working. Paste the new code here.', 'aidanto' ); ?></p>
	</div>
	<?php
}
