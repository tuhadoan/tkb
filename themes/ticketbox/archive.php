<?php
/**
 * The template for displaying Archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each specific one. For example, Dawn
 * already has tag.php for Tag archives, category.php for Category archives,
 * and author.php for Author archives.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package dawn
 */

get_header(); ?>
<div class="container">
	<div class="row">
		<div id="primary" class="content-area col-md-8">
			<div id="content" class="site-content" role="main">

				<?php if ( have_posts() ) : ?>

				<header class="page-header">
					<h1 class="page-title">
						<?php
							if ( is_day() ) :
								printf( __( 'Daily Archives: %s', 'ticketbox' ), get_the_date() );

							elseif ( is_month() ) :
								printf( __( 'Monthly Archives: %s', 'ticketbox' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'ticketbox' ) ) );

							elseif ( is_year() ) :
								printf( __( 'Yearly Archives: %s', 'ticketbox' ), get_the_date( _x( 'Y', 'yearly archives date format', 'ticketbox' ) ) );

							else :
								esc_html_e( 'Archives', 'ticketbox' );

							endif;
						?>
					</h1>
				</header><!-- .page-header -->

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
		</div><!-- #primary -->

<?php
get_sidebar( 'content' );
?>
	</div><!-- .row -->
</div><!-- #container -->
<?php
get_footer();
