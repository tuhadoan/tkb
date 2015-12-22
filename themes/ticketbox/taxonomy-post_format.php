<?php
/**
 * The template for displaying Post Format pages
 *
 * Used to display archive-type pages for posts with a post format.
 * If you'd like to further customize these Post Format views, you may create a
 * new template file for each specific one.
 *
 * @todo https://core.trac.wordpress.org/ticket/23257: Add plural versions of Post Format strings
 * and remove plurals below.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package dawn
 */

get_header(); ?>
<div class="container">
	<div class="row">
		<section id="primary" class="content-area col-md-8">
			<div id="content" class="site-content" role="main">

				<?php if ( have_posts() ) : ?>

				<header class="archive-header">
					<h1 class="archive-title">
						<?php
							if ( is_tax( 'post_format', 'post-format-aside' ) ) :
								_e( 'Asides', 'ticketbox' );

							elseif ( is_tax( 'post_format', 'post-format-image' ) ) :
								_e( 'Images', 'ticketbox' );

							elseif ( is_tax( 'post_format', 'post-format-video' ) ) :
								_e( 'Videos', 'ticketbox' );

							elseif ( is_tax( 'post_format', 'post-format-audio' ) ) :
								_e( 'Audio', 'ticketbox' );

							elseif ( is_tax( 'post_format', 'post-format-quote' ) ) :
								_e( 'Quotes', 'ticketbox' );

							elseif ( is_tax( 'post_format', 'post-format-link' ) ) :
								_e( 'Links', 'ticketbox' );

							elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) :
								_e( 'Galleries', 'ticketbox' );

							else :
								_e( 'Archives', 'ticketbox' );

							endif;
						?>
					</h1>
				</header><!-- .archive-header -->

				<?php
						// Start the Loop.
						while ( have_posts() ) : the_post();

							/*
							 * Include the post format-specific template for the content. If you want to
							 * use this in a child theme, then include a file called called content-___.php
							 * (where ___ is the post format) and that will be used instead.
							 */
							get_template_part( 'html/loop/content', get_post_format() );

						endwhile;
						// Previous/next page navigation.
						dawn_paging_nav();

					else :
						// If no content, include the "No posts found" template.
						get_template_part( 'html/loop/content', 'none' );

					endif;
				?>
			</div><!-- #content -->
	</section><!-- #primary -->

<?php
get_sidebar( 'content' );
?>
	</div><!-- .row -->
</div><!-- #container -->
<?php
get_footer();
