<?php
/**
 * dawn functions and definitions
 *
 * @package tickbox
 */

$themeInfo            =  wp_get_theme();
$themeName            = trim($themeInfo['Title']);
$themeAuthor          = trim($themeInfo['Author']);
$themeAuthor_URI      = trim($themeInfo['AuthorURI']);
$themeVersion         = trim($themeInfo['Version']);

define('DT_THEME_NAME', $themeName);
define('DT_THEME_AUTHOR', $themeAuthor);
define('DT_THEME_AUTHOR_URI', $themeAuthor_URI);
define('DT_THEME_VERSION', $themeVersion);

if(!defined('DT_ADMIN_ASSETS_URI'))
define('DT_ADMIN_ASSETS_URI', get_template_directory_uri() . '/inc/admin/assets');

if(!defined('DT_ASSETS_URI'))
	define('DT_ASSETS_URI', get_template_directory_uri() . '/assets');

/*
 * Require core
 */
do_action('dt_theme_includes');
if(!defined('DAWN_CORE_DIR')){
	include_once (get_template_directory().'/inc/functions.php');
}

// Plugins Required - recommended
$plugin_path = get_template_directory() . '/inc/plugins';
if ( file_exists( $plugin_path . '/tgmpa_register.php' ) ) {
	include_once ( $plugin_path. '/tgm-plugin-activation.php');
	include_once ($plugin_path . '/tgmpa_register.php');
}

/*
 * scripts enqueue for admin
 */
function dawn_admin_js_and_css(){
	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	
}
add_action('admin_enqueue_scripts', 'dawn_admin_js_and_css');

/**
 * Set up the content width value based on the theme's design.
 *
 * @see dawn_content_width()
 *
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640;
}

/**
 * Dawn only works in WordPress 3.6 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '3.6', '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
}

if ( ! function_exists( 'dawn_setup' ) ) :
/**
 * Dawn setup.
 *
 * Set up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support post thumbnails.
 *
 */
function dawn_setup() {

	/*
	 * Make Dawn available for translation.
	 *
	 * Translations can be added to the /languages/ directory.
	 * If you're building a theme based on Dawn, use a find and
	 * replace to change 'ticketbox' to the name of your theme in all
	 * template files.
	 */
	load_theme_textdomain( 'ticketbox', get_template_directory() . '/languages' );

	// This theme styles the visual editor to resemble the theme style.
	add_editor_style('assets/assets/css/editor-style.css');

	// Add RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );
	
	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	// Enable support for Post Thumbnails, and declare two sizes.
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 360, 200, true );
	add_image_size( 'dawn-full-width', 1038, 576, true );

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'primary'   => __( 'Top primary menu', 'ticketbox' ),
		'secondary' => __( 'Secondary menu in Footer', 'ticketbox' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
	) );

	/*
	 * Enable support for Post Formats.
	 * See https://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'audio', 'quote', 'link', 'gallery',
	) );

	// This theme allows users to set a custom background.
	add_theme_support( 'custom-background', apply_filters( 'dawn_custom_background_args', array(
		'default-color' => 'f5f5f5',
	) ) );

	// This theme uses its own gallery styles.
	add_filter( 'use_default_gallery_style', '__return_false' );
}
endif; // dawn_setup
add_action( 'after_setup_theme', 'dawn_setup' );

/**
 * Adjust content_width value for image attachment template.
 *
 */
function dawn_content_width() {
	if ( is_attachment() && wp_attachment_is_image() ) {
		$GLOBALS['content_width'] = 810;
	}
}
add_action( 'template_redirect', 'dawn_content_width' );

/**
 * Register widget areas.
 *
 */
function dawn_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Main Sidebar', 'ticketbox' ),
		'id'            => 'main-sidebar',
		'description'   => __( 'Main sidebar that appears on the right.', 'ticketbox' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
	register_sidebar( array(
		'name'          => __( 'Footer Widget Area', 'ticketbox' ),
		'id'            => 'footer-sidebar',
		'description'   => __( 'Appears in the footer section of the site.', 'ticketbox' ),
		'before_widget' => '<div class="col-md-15"><aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside></div>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'dawn_widgets_init' );

/**
 * Register Lato Google font
 * @return string
 */
function dawn_font_url() {
	$font_url = '';
	/*
	 * Translators: If there are characters in your language that are not supported
	 * by Lato, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Lato font: on or off', 'ticketbox' ) ) {
		$query_args = array(
			'family' => urlencode( 'Lato:300,400,700,900,300italic,400italic,700italic' ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);
		$font_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return $font_url;
}

/**
 * Enqueue scripts and styles for the front end.
 *
 */
function dawn_scripts() {
	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	
	// Add Lato font, used in the main stylesheet.
	wp_enqueue_style( 'ticketbox-lato', dawn_font_url(), array(), null );

	// Add Awesome font, used in the main stylesheet.
	wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css', array(), '3.3.5' );
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/assets/css/fonts/font-awesome/css/font-awesome.min.css', array(), '4.4.0');
	
	wp_enqueue_style( 'animate', get_template_directory_uri() . '/assets/css/animate.css', array());

	// Load our main stylesheet.
	wp_enqueue_style( 'ticketbox-style', get_stylesheet_uri() );
	wp_enqueue_style( 'ticketbox-theme-style', get_template_directory_uri() . '/assets/css/styles.css' );

	// Load the Internet Explorer specific stylesheet.
	wp_enqueue_style( 'ticketbox-ie', get_template_directory_uri() . '/assets/css/ie.css', array( 'dawn-style' ), '20131205' );
	wp_style_add_data( 'ticketbox-ie', 'conditional', 'lt IE 9' );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'ticketbox-keyboard-image-navigation', get_template_directory_uri() . '/assets/js/keyboard-image-navigation.js', array( 'jquery' ), '20130402' );
	}

	if ( is_active_sidebar( 'sidebar-3' ) ) {
		wp_enqueue_script( 'jquery-masonry' );
	}
	
	wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array('jquery'), '3.5.5', true );
	
	wp_enqueue_script( 'hover-intent', get_template_directory_uri() . '/assets/js/jquery.hoverIntent.js', array('jquery'), '', true );

	wp_enqueue_script( 'ticketbox-script', get_template_directory_uri() . '/assets/js/functions.js', array( 'jquery' ), '20150315', true );
}

add_action( 'wp_enqueue_scripts', 'dawn_scripts' );

/**
 * Enqueue Google fonts style to admin screen for custom header display.
 *
 */
function dawn_admin_fonts() {
	wp_enqueue_style( 'ticketbox-lato', dawn_font_url(), array(), null );
}
add_action( 'admin_print_scripts-appearance_page_custom-header', 'dawn_admin_fonts' );

if ( ! function_exists( 'dawn_the_attached_image' ) ) :
/**
 * Print the attached image with a link to the next attached image.
 */
function dawn_the_attached_image() {
	$post = get_post();
	/**
	 * Filter the default attachment size.
	 *
	 * @param array $dimensions {
	 *     An array of height and width dimensions.
	 *
	 *     @type int $height Height of the image in pixels. Default 810.
	 *     @type int $width  Width of the image in pixels. Default 810.
	 * }
	 */
	$attachment_size     = apply_filters( 'dawn_attachment_size', array( 810, 810 ) );
	$next_attachment_url = wp_get_attachment_url();

	/*
	 * Grab the IDs of all the image attachments in a gallery so we can get the URL
	 * of the next adjacent image in a gallery, or the first image (if we're
	 * looking at the last image in a gallery), or, in a gallery of one, just the
	 * link to that image file.
	 */
	$attachment_ids = get_posts( array(
		'post_parent'    => $post->post_parent,
		'fields'         => 'ids',
		'numberposts'    => -1,
		'post_status'    => 'inherit',
		'post_type'      => 'attachment',
		'post_mime_type' => 'image',
		'order'          => 'ASC',
		'orderby'        => 'menu_order ID',
	) );

	// If there is more than 1 attachment in a gallery...
	if ( count( $attachment_ids ) > 1 ) {
		foreach ( $attachment_ids as $attachment_id ) {
			if ( $attachment_id == $post->ID ) {
				$next_id = current( $attachment_ids );
				break;
			}
		}

		// get the URL of the next image attachment...
		if ( $next_id ) {
			$next_attachment_url = get_attachment_link( $next_id );
		}

		// or get the URL of the first image attachment.
		else {
			$next_attachment_url = get_attachment_link( reset( $attachment_ids ) );
		}
	}

	printf( '<a href="%1$s" rel="attachment">%2$s</a>',
		esc_url( $next_attachment_url ),
		wp_get_attachment_image( $post->ID, $attachment_size )
	);
}
endif;

if ( ! function_exists( 'dawn_list_authors' ) ) :
/**
 * Print a list of all site contributors who published at least one post.
 */
function dawn_list_authors() {
	$contributor_ids = get_users( array(
		'fields'  => 'ID',
		'orderby' => 'post_count',
		'order'   => 'DESC',
		'who'     => 'authors',
	) );

	foreach ( $contributor_ids as $contributor_id ) :
		$post_count = count_user_posts( $contributor_id );

		// Move on if user has not published a post (yet).
		if ( ! $post_count ) {
			continue;
		}
	?>

	<div class="contributor">
		<div class="contributor-info">
			<div class="contributor-avatar"><?php echo get_avatar( $contributor_id, 132 ); ?></div>
			<div class="contributor-summary">
				<h2 class="contributor-name"><?php echo get_the_author_meta( 'display_name', $contributor_id ); ?></h2>
				<p class="contributor-bio">
					<?php echo get_the_author_meta( 'description', $contributor_id ); ?>
				</p>
				<a class="button contributor-posts-link" href="<?php echo esc_url( get_author_posts_url( $contributor_id ) ); ?>">
					<?php printf( _n( '%d Article', '%d Articles', $post_count, 'ticketbox' ), $post_count ); ?>
				</a>
			</div><!-- .contributor-summary -->
		</div><!-- .contributor-info -->
	</div><!-- .contributor -->

	<?php
	endforeach;
}
endif;

/**
 * Extend the default WordPress body classes.
 *
 * Adds body classes to denote:
 * 1. Single or multiple authors.
 * 2. Presence of header image except in Multisite signup and activate pages.
 * 3. Index views.
 * 4. Full-width content layout.
 * 5. Presence of footer widgets.
 * 6. Single views.
 * 7. Featured content layout.
 *
 * @param array $classes A list of existing body class values.
 * @return array The filtered body class list.
 */
function dawn_body_classes( $classes ) {
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	if ( get_header_image() ) {
		$classes[] = 'header-image';
	} elseif ( ! in_array( $GLOBALS['pagenow'], array( 'wp-activate.php', 'wp-signup.php' ) ) ) {
		$classes[] = 'masthead-fixed';
	}

	if ( is_archive() || is_search() || is_home() ) {
		$classes[] = 'dawn-posts-list list-view';
	}

	if ( ( ! is_active_sidebar( 'sidebar-2' ) )
		|| is_page_template( 'page-templates/full-width.php' )
		|| is_page_template( 'page-templates/front-page.php' )
		|| is_attachment() ) {
		$classes[] = 'full-width';
	}

	if ( is_active_sidebar( 'sidebar-3' ) ) {
		$classes[] = 'footer-widgets';
	}

	if ( is_singular() && ! is_front_page() ) {
		$classes[] = 'singular';
	}
	
	// Sticky menu
	if( dt_get_theme_option('sticky-menu', '1') == '1' ){
		$classes[]	= 'sticky-menu';
	}

	return $classes;
}
add_filter( 'body_class', 'dawn_body_classes' );

/**
 * Extend the default WordPress post classes.
 *
 * Adds a post class to denote:
 * Non-password protected page with a post thumbnail.
 *
 * @param array $classes A list of existing post class values.
 * @return array The filtered post class list.
 */
function dawn_post_classes( $classes ) {
	if ( ! post_password_required() && ! is_attachment() && has_post_thumbnail() ) {
		$classes[] = 'has-post-thumbnail';
	}

	return $classes;
}
add_filter( 'post_class', 'dawn_post_classes' );

/**
 * Create a nicely formatted and more specific title element text for output
 * in head of document, based on current view.
 *
 * @global int $paged WordPress archive pagination page count.
 * @global int $page  WordPress paginated post page count.
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
function dawn_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() ) {
		return $title;
	}

	// Add the site name.
	$title .= get_bloginfo( 'name', 'display' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title = "$title $sep $site_description";
	}

	// Add a page number if necessary.
	if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
		$title = "$title $sep " . sprintf( __( 'Page %s', 'ticketbox' ), max( $paged, $page ) );
	}

	return $title;
}
add_filter( 'wp_title', 'dawn_wp_title', 10, 2 );

// Implement Custom Header features.
require get_template_directory() . '/inc/custom-header.php';

// Custom template tags for this theme.
require get_template_directory() . '/inc/template-tags.php';

// Add Customizer functionality.
require get_template_directory() . '/inc/customizer.php';


