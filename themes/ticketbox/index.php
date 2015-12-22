<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme and one
 * of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query,
 * e.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package dawn
 */
$main_class = dt_get_main_class();

get_header(); ?>

<div id="main-content" class="main-content">
	<div class="container">
		<div class="row">
			<section id="primary" class="content-area <?php echo esc_attr($main_class)?>">
				<div id="content" class="site-content" role="main">
					<div class="row">
						<?php
						if ( have_posts() ) :
							// Start the Loop.
							while ( have_posts() ) : the_post(); ?>
								<?php get_template_part( 'template-parts/content', get_post_format() ); ?>
							<?php
							endwhile;
						else :
							// If no content, include the "No posts found" template.
							get_template_part( 'template-parts/content', 'none' );
	
						endif;
					?>
					</div><!-- /.row -->
				</div><!-- #content -->
				<?php 
				// Previous/next post navigation.
				dawn_paging_nav();
				?>
			</section><!-- #primary -->
		<?php do_action('dt_right_sidebar') ?>

	</div><!-- .row -->
</div><!-- #container -->

</div><!-- #main-content -->

<?php
get_footer();
