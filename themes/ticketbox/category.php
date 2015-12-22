<?php
/**
 * The template for displaying Category pages
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
					<h1 class="archive-title"><?php printf( __( 'Category Archives: %s', 'ticketbox' ), single_cat_title( '', false ) ); ?></h1>

					<?php
						// Show an optional term description.
						$term_description = term_description();
						if ( ! empty( $term_description ) ) :
							printf( '<div class="taxonomy-description">%s</div>', $term_description );
						endif;
					?>
				</header><!-- .archive-header -->

				<?php
						// Start the Loop.
						while ( have_posts() ) : the_post();

						get_template_part( 'template-parts/content', get_post_format() );

						endwhile;
						// Previous/next page navigation.
						dawn_paging_nav();

					else :
						// If no content, include the "No posts found" template.
						get_template_part( 'template-parts/content', 'none' );

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
