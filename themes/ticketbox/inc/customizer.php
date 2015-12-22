<?php
/**
 * Dawn Customizer support
 *
 * @package WordPress
 * @subpackage Dawn
 * @since Dawn 1.0
 */

/**
 * Implement Customizer additions and adjustments.
 *
 * @since Dawn 1.0
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function dawn_customize_register( $wp_customize ) {
	// Add postMessage support for site title and description.
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	// Rename the label to "Site Title Color" because this only affects the site title in this theme.
	$wp_customize->get_control( 'header_textcolor' )->label = esc_html_e( 'Site Title Color', 'ticketbox' );

	// Rename the label to "Display Site Title & Tagline" in order to make this option extra clear.
	$wp_customize->get_control( 'display_header_text' )->label = esc_html_e( 'Display Site Title &amp; Tagline', 'ticketbox' );

	// Add custom description to Colors and Background controls or sections.
	if ( property_exists( $wp_customize->get_control( 'background_color' ), 'description' ) ) {
		$wp_customize->get_control( 'background_color' )->description = esc_html_e( 'May only be visible on wide screens.', 'ticketbox' );
		$wp_customize->get_control( 'background_image' )->description = esc_html_e( 'May only be visible on wide screens.', 'ticketbox' );
	} else {
		$wp_customize->get_section( 'colors' )->description           = esc_html_e( 'Background may only be visible on wide screens.', 'ticketbox' );
		$wp_customize->get_section( 'background_image' )->description = esc_html_e( 'Background may only be visible on wide screens.', 'ticketbox' );
	}
	
}
add_action( 'customize_register', 'dawn_customize_register' );

/**
 * Sanitize the Featured Content layout value.
 *
 * @since Dawn 1.0
 *
 * @param string $layout Layout type.
 * @return string Filtered layout type (grid|slider).
 */
function dawn_sanitize_layout( $layout ) {
	if ( ! in_array( $layout, array( 'grid', 'slider' ) ) ) {
		$layout = 'grid';
	}

	return $layout;
}

/**
 * Bind JS handlers to make Customizer preview reload changes asynchronously.
 *
 * @since Dawn 1.0
 */
function dawn_customize_preview_js() {
	wp_enqueue_script( 'dawn_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20131205', true );
}
add_action( 'customize_preview_init', 'dawn_customize_preview_js' );

/**
 * Add contextual help to the Themes and Post edit screens.
 *
 * @since Dawn 1.0
 */
function dawn_contextual_help() {
	if ( 'admin_head-edit.php' === current_filter() && 'post' !== $GLOBALS['typenow'] ) {
		return;
	}

	get_current_screen()->add_help_tab( array(
		'id'      => 'ticketbox',
		'title'   => esc_html_e( 'Dawn', 'ticketbox' ),
		'content' =>
			'<ul>' .
				'<li>' . sprintf( __ ( 'The home page features your choice of up to 6 posts prominently displayed in a grid or slider, controlled by a <a href="%1$s">tag</a>; you can change the tag and layout in <a href="%2$s">Appearance &rarr; Customize</a>. If no posts match the tag, <a href="%3$s">sticky posts</a> will be displayed instead.', 'ticketbox' ), esc_url( add_query_arg( 'tag', _x( 'featured', 'featured content default tag slug', 'ticketbox' ), admin_url( 'edit.php' ) ) ), admin_url( 'customize.php' ), admin_url( 'edit.php?show_sticky=1' ) ) . '</li>' .
				'<li>' . sprintf( __ ( 'Enhance your site design by using <a href="%s">Featured Images</a> for posts you&rsquo;d like to stand out (also known as post thumbnails). This allows you to associate an image with your post without inserting it. Dawn uses featured images for posts and pages&mdash;above the title&mdash;and in the Featured Content area on the home page.', 'ticketbox' ), 'https://codex.wordpress.org/Post_Thumbnails#Setting_a_Post_Thumbnail' ) . '</li>' .
				'<li>' . sprintf( __ ( 'For an in-depth tutorial, and more tips and tricks, visit the <a href="%s">Dawn documentation</a>.', 'ticketbox' ), 'https://codex.wordpress.org/Dawn' ) . '</li>' .
			'</ul>',
	) );
}
add_action( 'admin_head-themes.php', 'dawn_contextual_help' );
add_action( 'admin_head-edit.php',   'dawn_contextual_help' );
