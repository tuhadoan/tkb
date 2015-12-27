<?php
/**
 * Dawn back compat functionality
 *
 * Prevents Dawn from running on WordPress versions prior to 3.6,
 * since this theme is not meant to be backward compatible beyond that
 * and relies on many newer functions and markup changes introduced in 3.6.
 *
 * @package WordPress
 * @subpackage Dawn
 * @since Dawn 1.0
 */

/**
 * Prevent switching to Dawn on old versions of WordPress.
 *
 * Switches to the default theme.
 *
 * @since Dawn 1.0
 */
function dawn_switch_theme() {
	switch_theme( WP_DEFAULT_THEME, WP_DEFAULT_THEME );
	unset( $_GET['activated'] );
	add_action( 'admin_notices', 'dawn_upgrade_notice' );
}
add_action( 'after_switch_theme', 'dawn_switch_theme' );

/**
 * Add message for unsuccessful theme switch.
 *
 * Prints an update nag after an unsuccessful attempt to switch to
 * Dawn on WordPress versions prior to 3.6.
 *
 * @since Dawn 1.0
 */
function dawn_upgrade_notice() {
	$message = sprintf( esc_html_e( 'Dawn requires at least WordPress version 3.6. You are running version %s. Please upgrade and try again.', 'ticketbox' ), $GLOBALS['wp_version'] );
	printf( '<div class="error"><p>%s</p></div>', $message );
}

/**
 * Prevent the Customizer from being loaded on WordPress versions prior to 3.6.
 *
 * @since Dawn 1.0
 */
function dawn_customize() {
	wp_die( sprintf( esc_html_e( 'Dawn requires at least WordPress version 3.6. You are running version %s. Please upgrade and try again.', 'ticketbox' ), $GLOBALS['wp_version'] ), '', array(
		'back_link' => true,
	) );
}
add_action( 'load-customize.php', 'dawn_customize' );

/**
 * Prevent the Theme Preview from being loaded on WordPress versions prior to 3.4.
 *
 * @since Dawn 1.0
 */
function dawn_preview() {
	if ( isset( $_GET['preview'] ) ) {
		wp_die( sprintf( esc_html_e( 'Dawn requires at least WordPress version 3.6. You are running version %s. Please upgrade and try again.', 'ticketbox' ), $GLOBALS['wp_version'] ) );
	}
}
add_action( 'template_redirect', 'dawn_preview' );
