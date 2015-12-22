<?php
/**
 * The template for displaying 404 pages (Not Found)
 *
 *@package dawn
 */

get_header(); ?>
	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
			<header class="page-header">
				<div class="container">
					<div class="page-title-sub"><?php esc_html_e( 'We are sorry', 'ticketbox' ); ?></div>
					<h1 class="page-title"><?php esc_html_e( 'Page Not Found', 'ticketbox' ); ?></h1>
				</div>
			</header>
			<div class="page-content">
				<div class="container">
					<div class="not-found-content">
						<h2><?php esc_html_e( "What's next ?", 'ticketbox' ); ?></h2>
						<div class="not-found-desc">
						<?php printf( __('<span>Back to</span> <i class="fa fa-home"></i> <a href="%1$s">Home Page</a> <span>or</span> <i class="fa fa-search"></i> <a href="&2$s">Discovery</a> <span>Events</span>', 'ticketbox'),
						esc_url( home_url( '/' ) ),
						'#'
						);?>
						</div>
					</div>

					<?php get_search_form(); ?>
				</div>
			</div><!-- .page-content -->

		</div><!-- #content -->
	</div><!-- #primary -->

<?php
get_footer();
