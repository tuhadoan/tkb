<?php
/**
 * The Template for displaying all single posts
 *
 * @package dawn
 */

get_header(); ?>

<div class="container">
	<div class="row">
		<div id="primary" class="content-area col-md-8">
			<div id="content" class="site-content dawn-single-post" role="main">
				<?php
					// Start the Loop.
					while ( have_posts() ) : the_post();?>
							
						<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
							<?php dawn_post_thumbnail(); ?>

							<header class="entry-header">
								<?php if ( in_array( 'category', get_object_taxonomies( get_post_type() ) ) && dawn_categorized_blog() ) : ?>
								<div class="entry-meta">
									<span class="cat-links"><?php echo get_the_category_list( esc_html_x( ', ', 'Used between list items, there is a space after the comma.', 'ticketbox' ) ); ?></span>
								</div>
								<?php
									endif;

									the_title( '<h1 class="entry-title">', '</h1>' );
									
								?>

								<div class="entry-meta">
									<?php
										if ( 'post' == get_post_type() )
											dawn_posted_on();

										if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) :
									?>
									<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'ticketbox' ), __( '1 Comment', 'ticketbox' ), __( '% Comments', 'ticketbox' ) ); ?></span>
									<?php
										endif;

										edit_post_link( __( 'Edit', 'ticketbox' ), '<span class="edit-link">', '</span>' );
									?>
								</div><!-- .entry-meta -->
							</header><!-- .entry-header -->

							<?php if ( is_search() ) : ?>
							<div class="entry-summary">
								<?php the_excerpt(); ?>
							</div><!-- .entry-summary -->
							<?php else : ?>
							<div class="entry-content">
								<?php
									/* translators: %s: Name of current post */
									the_content( sprintf(
										__( 'Read More %s <span class="meta-nav">&rarr;</span>', 'ticketbox' ),
										the_title( '<span class="screen-reader-text">', '</span>', false )
									) );

									wp_link_pages( array(
										'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'ticketbox' ) . '</span>',
										'after'       => '</div>',
										'link_before' => '<span>',
										'link_after'  => '</span>',
									) );
								?>
							</div><!-- .entry-content -->
							<?php endif; ?>

							<?php the_tags( '<footer class="entry-meta"><span class="tag-links">', '', '</span></footer>' ); ?>
						</article><!-- #post-## -->
						

						<?php
						// Previous/next post navigation.
						dawn_post_nav();

						// If comments are open or we have at least one comment, load up the comment template.
						if ( comments_open() || get_comments_number() ) {
							comments_template();
						}
					endwhile;
				?>
			</div><!-- #content -->
	</div><!-- #primary -->

<?php
get_sidebar( 'content' );
?>
	</div><!-- .row -->
</div><!-- #container -->
<?php
get_footer();
