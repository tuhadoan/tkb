<?php
/**
 * The template for displaying image attachments
 *
 * @package dawn
 */

// Retrieve attachment metadata.
$metadata = wp_get_attachment_metadata();

get_header();
?>
<div class="container">
		<div class="row">
			<section id="primary" class="content-area image-attachment col-md-8">
				<div id="content" class="site-content" role="main">
		
			<?php
				// Start the Loop.
				while ( have_posts() ) : the_post();
			?>
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<header class="entry-header">
							<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		
							<div class="entry-meta">
		
								<span class="entry-date"><time class="entry-date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></span>
		
								<span class="full-size-link"><a href="<?php echo esc_url( wp_get_attachment_url() ); ?>"><?php echo esc_html( $metadata['width'] ); ?> &times; <?php echo esc_html( $metadata['height'] ); ?></a></span>
		
								<span class="parent-post-link"><a href="<?php echo esc_url( get_permalink( $post->post_parent ) ); ?>" rel="gallery"><?php echo get_the_title( $post->post_parent ); ?></a></span>
								<?php edit_post_link( __( 'Edit', 'ticketbox' ), '<span class="edit-link">', '</span>' ); ?>
							</div><!-- .entry-meta -->
						</header><!-- .entry-header -->
		
						<div class="entry-content">
							<div class="entry-attachment">
								<div class="attachment">
									<?php dawn_the_attached_image(); ?>
								</div><!-- .attachment -->
		
								<?php if ( has_excerpt() ) : ?>
								<div class="entry-caption">
									<?php the_excerpt(); ?>
								</div><!-- .entry-caption -->
								<?php endif; ?>
							</div><!-- .entry-attachment -->
		
							<?php
								the_content();
								wp_link_pages( array(
									'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'ticketbox' ) . '</span>',
									'after'       => '</div>',
									'link_before' => '<span>',
									'link_after'  => '</span>',
								) );
							?>
						</div><!-- .entry-content -->
					</article><!-- #post-## -->
		
					<nav id="image-navigation" class="navigation image-navigation">
						<div class="nav-links">
						<?php previous_image_link( false, '<div class="previous-image">' . __( 'Previous Image', 'ticketbox' ) . '</div>' ); ?>
						<?php next_image_link( false, '<div class="next-image">' . __( 'Next Image', 'ticketbox' ) . '</div>' ); ?>
						</div><!-- .nav-links -->
					</nav><!-- #image-navigation -->
		
					<?php comments_template(); ?>
		
				<?php endwhile; // end of the loop. ?>
		
				</div><!-- #content -->
			</section><!-- #primary -->
<?php
get_sidebar( 'content' );
?>
	</div><!-- .row -->
</div><!-- #container -->
<?php
get_footer();
