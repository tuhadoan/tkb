<?php
/**
 * Custom template tags for Dawn
 *
 * @package dawn
 */

if ( ! function_exists( 'dawn_paging_nav' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 *
 *
 * @global WP_Query   $wp_query   WordPress Query object.
 * @global WP_Rewrite $wp_rewrite WordPress Rewrite object.
 */
function dawn_paging_nav() {
	global $wp_query, $wp_rewrite;

	// Don't print empty markup if there's only one page.
	if ( $wp_query->max_num_pages < 2 ) {
		return;
	}

	$paged        = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
	$pagenum_link = html_entity_decode( get_pagenum_link() );
	$query_args   = array();
	$url_parts    = explode( '?', $pagenum_link );

	if ( isset( $url_parts[1] ) ) {
		wp_parse_str( $url_parts[1], $query_args );
	}

	$pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
	$pagenum_link = trailingslashit( $pagenum_link ) . '%_%';

	$format  = $wp_rewrite->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
	$format .= $wp_rewrite->using_permalinks() ? user_trailingslashit( $wp_rewrite->pagination_base . '/%#%', 'paged' ) : '?paged=%#%';

	// Set up paginated links.
	$links = paginate_links( array(
		'base'     => $pagenum_link,
		'format'   => $format,
		'total'    => $wp_query->max_num_pages,
		'current'  => $paged,
		'mid_size' => 1,
		'add_args' => array_map( 'urlencode', $query_args ),
		'prev_text' => __ ( '&larr; Previous', 'ticketbox' ),
		'next_text' => __ ( 'Next &rarr;', 'ticketbox' ),
	) );

	if ( $links ) :

	?>
	<nav class="navigation paging-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php esc_html_e( 'Posts navigation', 'ticketbox' ); ?></h1>
		<div class="pagination loop-pagination">
			<?php echo $links; ?>
		</div><!-- .pagination -->
	</nav><!-- .navigation -->
	<?php
	endif;
}
endif;

if ( ! function_exists( 'dawn_post_nav' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 *
 */
function dawn_post_nav() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}

	?>
	<nav class="navigation post-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php esc_html_e( 'Post navigation', 'ticketbox' ); ?></h1>
		<div class="nav-links">
			<?php
			if ( is_attachment() ) :
				previous_post_link( '%link', __ ( '<span class="meta-nav">Published In</span>%title', 'ticketbox' ) );
			else :
				previous_post_link( '%link', __ ( '<span class="meta-nav">Previous Post</span>%title', 'ticketbox' ) );
				next_post_link( '%link', __ ( '<span class="meta-nav">Next Post</span>%title', 'ticketbox' ) );
			endif;
			?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'dawn_posted_on' ) ) :
/**
 * Print HTML with meta information for the current post-date/time and author.
 *
 */
function dawn_posted_on() {
	// Set up and print post meta information.
	printf( '<span class="entry-date"><i class="fa fa-calendar"></i> <a href="%1$s" rel="bookmark"><time class="entry-date" datetime="%2$s">%3$s</time></a></span>',
		esc_url( get_permalink() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);
}
endif;

/**
 * Find out if blog has more than one category.
 *
 * @return boolean true if blog has more than 1 category
 */
function dawn_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'dawn_category_count' ) ) ) {
		// Create an array of all the categories that are attached to posts
		$all_the_cool_cats = get_categories( array(
			'hide_empty' => 1,
		) );

		// Count the number of categories that are attached to the posts
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'dawn_category_count', $all_the_cool_cats );
	}

	if ( 1 !== (int) $all_the_cool_cats ) {
		// This blog has more than 1 category so dawn_categorized_blog should return true
		return true;
	} else {
		// This blog has only 1 category so dawn_categorized_blog should return false
		return false;
	}
}

/**
 * Flush out the transients used in dawn_categorized_blog.
 *
 */
function dawn_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'dawn_category_count' );
}
add_action( 'edit_category', 'dawn_category_transient_flusher' );
add_action( 'save_post',     'dawn_category_transient_flusher' );

if ( ! function_exists( 'dawn_post_thumbnail' ) ) :
/**
 * Display an optional post thumbnail.
 *
 * Wraps the post thumbnail in an anchor element on index
 * views, or a div element when on single views.
 */
function dawn_post_thumbnail() {
	if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
		return;
	}

	if ( is_singular() ) :
	?>

	<div class="post-thumbnail">
	<?php
		if ( ( ! is_active_sidebar( 'sidebar-2' ) || is_page_template( 'page-templates/full-width.php' ) ) ) {
			the_post_thumbnail( 'dawn-full-width' );
		} else {
			the_post_thumbnail();
		}
	?>
	</div>

	<?php else : ?>

	<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true">
	<?php
		if ( ( ! is_active_sidebar( 'sidebar-2' ) || is_page_template( 'page-templates/full-width.php' ) ) ) {
			the_post_thumbnail( 'dawn-full-width' );
		} else {
			the_post_thumbnail( 'post-thumbnail', array( 'alt' => get_the_title() ) );
		}
	?>
	</a>

	<?php endif; // End is_singular()
}
endif;

