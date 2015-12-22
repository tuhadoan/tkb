<?php
/**
 * The template part for displaying content
 *
 * @subpackage Dawn
 */
?>
<?php 
$layout = dt_get_theme_option('blog-layout','full-width');
$show_readmore = dt_get_theme_option('blogs-show-readmore','') == '1' ? 'yes':'';
?>
<?php 
// Define post layout
$post_col = 'col-md-4 col-sm-6'; // full-width
if( $layout == 'right-sidebar' || $layout == 'left-sidebar'){
	$post_col = 'col-md-12 col-sm-6';
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $post_col ); ?> itemscope="">
	
	<?php if(has_post_thumbnail()): ?>
	<div class="post-thumbnail">
		<a href="<?php echo get_permalink();?>" title="<?php echo get_the_title();?>">
		<?php
		the_post_thumbnail( 'post-thumbnail', array( 'alt' => get_the_title() ) ); ?>
		</a>
		<?php 
		printf( '<span class="entry-date"><a href="%1$s" rel="bookmark"><time class="entry-date" datetime="%2$s"><span class="date">%3$s</span><span class="month">%4$s</span></time></a></span>',
			esc_url( get_permalink() ),
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date('j') ),
			esc_html( get_the_date('M') )
		);
	?>
		
	</div>
	<?php
	else:
	dawn_posted_on();
	endif;
	?>
	
	<header class="entry-header">
		<?php	
			the_title( '<h2 class="entry-title" data-itemprop="name"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		?>
		<div class="entry-meta">
			<?php
			printf('<span class="byline"><span class="author vcard"><i class="fa fa-user"></i> <a class="url fn n" href="%1$s" rel="author">%2$s</a></span></span>',
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			get_the_author()
			);
			
			?>
			<?php if ( in_array( 'category', get_object_taxonomies( get_post_type() ) ) && dawn_categorized_blog() ) : ?>
				<span class="cat-links"><i class="fa fa-folder-o"></i><?php echo get_the_category_list( esc_html_x( ', ', 'Used between list items, there is a space after the comma.', 'ticketbox' ) ); ?></span>
			<?php
			endif;
			?>
			
			<?php
				if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) :
			?>
			<span class="comments-link"><i class="fa fa-comment-o"></i><?php comments_popup_link( esc_html__( 'Leave a comment', 'ticketbox' ), esc_html__( '1 Comment', 'ticketbox' ), esc_html__( '% Comments', 'ticketbox' ) ); ?></span>
			<?php
				endif;

			?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->
	
	<?php if( has_excerpt() ){
		dt_excerpt();
	}else{?>
	<div class="entry-content">
		<?php
			/* translators: %s: Name of current post */
			the_content( sprintf(
				__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'ticketbox' ),
				get_the_title()
			) );

			wp_link_pages( array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'ticketbox' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'ticketbox' ) . ' </span>%',
				'separator'   => '<span class="screen-reader-text">, </span>',
			) );
		?>
		
		<?php edit_post_link( esc_html__( 'Edit', 'ticketbox' ), '<div class="edit-link">', '</div>' ); ?>
	</div>
	<?php
	}
	?>
	
	<?php if($show_readmore == 'yes'):?>
	<div class="readmore-link">
		<a href="<?php the_permalink()?>" class="more-link"><?php esc_html_e("Read More", 'woow');?><span class="meta-nav"></span></a>
	</div>
	<?php endif;?>
	
	<?php the_tags( '<footer class="entry-meta"><span class="tag-links"><i class="fa fa-tag"></i>', ', ', '</span></footer>' ); ?>
</article><!-- #post-## -->
