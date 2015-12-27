<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package dawn
 */
?>
		</div><!-- #main -->

		<footer id="footer" class="site-footer" role="contentinfo">
			<?php if(dt_get_theme_option('back-to-top',1)): ?>
			<a id="scroll-to-top" href="" class="back-to-top"><i class="fa fa-arrow-up"></i></a>
			<?php endif;?>
			<?php 
			if(is_active_sidebar('footer-sidebar')): ?>
			<div id="supplementary">
				<div id="footer-sidebar" class="footer-sidebar widget-area" role="complementary">
					<div class="container">
						<div class="row">
						<?php dynamic_sidebar( 'footer-sidebar' ); ?>
						</div>
					</div>
				</div><!-- #footer-sidebar -->
			</div><!-- #supplementary -->
			<?php
			endif;
			?>
			 <div class="copyright-section">
					<div class="container">
						<div class="row">	
							<div class="col-md-6">
								<div class="site-info">
									<?php do_action( 'dawn_credits' ); ?>
									<span><?php echo dt_get_theme_option('footer-copyright',''); ?></a>
								</div><!-- .site-info -->
							</div>
							<div class="col-md-6">
								<?php if ( has_nav_menu( 'secondary' ) ) : ?>
								<nav role="navigation" class="navigation footer-navigation">
									<?php wp_nav_menu( array( 'theme_location' => 'secondary','depth' => 1 ) ); ?>
								</nav><!-- .Footer menu -->
								<?php endif; ?>
							</div>
						</div><!-- .row -->
						
					</div>
			</div><!-- .copyright-section -->
		</footer><!-- #footer -->
	</div><!-- #page -->

	<?php wp_footer(); ?>
</body>
</html>