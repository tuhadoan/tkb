<?php
/**
 * The Header for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package dawn
 */

$page_heading = dt_get_post_meta('page_heading',get_the_ID(),'heading');
$is_sticky_menu = dt_get_theme_option('sticky_menu','yes') == 'yes' ? ' is_sticky_menu' : '';

?>
<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) & !(IE 8)]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="sidebar-offcanvas" class="sidebar-offcanvas">
          <!-- BEGIN: Sidebar Offcanvas Header -->
          <div class="sidebar-offcanvas-header">
            <!-- BEGIN: User Panel -->
            <div class="user-panel pull-left">
              <div class="ava">
                <i class="fa fa-user"></i>
              </div>
              <div class="user-welcome">Hello, <a href="#">Admin</a></div>
            </div><!-- END: User Panel -->
            <!-- BEGIN: Search Box -->
            <form id="search-box" action="#" method="post" class="search-box" tabindex="-1">
              <i class="fa fa-search search-icon"></i>
              <input type="text" value="" placeholder="What do you want to find?" class="keywords">
            </form><!-- END: Search Box -->            
            <!-- BEGIN: Toggle Wrap -->
            <div class="toggle-wrap pull-right">
              <span class="toggle-icon search-toggle"><i class="fa fa-search"></i></span>
              <span class="toggle-icon offcanvas-toggle"><i class="fa fa-bars"></i></span>
            </div><!-- END: Toggle Wrap -->
          </div><!-- END: Sidebar Offcanvas Header -->
          <!-- BEGIN: User Menu -->
          <div class="box-user-menu">
            <ul class="user-menu">
              <li><a href="#"><i class="fa fa-pencil-square-o menu-item-icon"></i> Profile <i class="fa fa-angle-right menu-item-arrow"></i></a></li>
              <li><a href="#"><i class="fa fa-clock-o menu-item-icon"></i> Order History <i class="fa fa-angle-right menu-item-arrow"></i></a></li>
              <li><a href="#"><i class="fa fa-ticket menu-item-icon"></i> My Tickets <i class="fa fa-angle-right menu-item-arrow"></i></a></li>
              <li><a href="#"><i class="fa fa-money menu-item-icon"></i> Account Balance <i class="fa fa-angle-right menu-item-arrow"></i></a></li>
              <li><a href="#"><i class="fa fa-cog menu-item-icon"></i> Setting <i class="fa fa-angle-right menu-item-arrow"></i></a></li>
            </ul>
          </div><!-- END: User Menu -->
          <!-- BEGIN: Create an Event -->
          <div class="box-create-event">
            <button class="button-create-event" data-toggle="modal" data-target="#creat-event-modal"><i class="fa fa-plus-square"></i> Create an Event</button>
          </div><!-- END: Create an Event-->
          <!-- BEGIN: Cart -->
          <div class="box-cart">
            <h3 class="cart-title"><i class="fa fa-shopping-cart"></i> Cart</h3>
            <a class="cart-view" href="#">
              Have 1 item(s).  Total: <strong>$42.00</strong>
            </a>
          </div><!-- END: Cart -->
          <!-- BEGIN: Categories -->
          <div class="box-categories">
            <h3 class="box-categories-title"><i class="fa fa-folder-o"></i> Categories</h3>
            <ul class="cat-menu">
              <li><a href="#">All <span class="count">18</span> <i class="fa fa-angle-right menu-item-arrow"></i></a></li>
              <li><a href="#">Entertaiment <span class="count">08</span> <i class="fa fa-angle-right menu-item-arrow"></i></a></li>
              <li><a href="#">Networking &amp; Meetup <i class="fa fa-angle-right menu-item-arrow"></i></a></li>
              <li><a href="#">Education <span class="count">04</span> <i class="fa fa-angle-right menu-item-arrow"></i></a></li>
              <li><a href="#">Community &amp; Charity <span class="count">03</span> <i class="fa fa-angle-right menu-item-arrow"></i></a></li>
              <li><a href="#">Seminars &amp; Workshops <i class="fa fa-angle-right menu-item-arrow"></i></a></li>
              <li><a href="#">Sport <span class="count">03</span> <i class="fa fa-angle-right menu-item-arrow"></i></a></li>
              <li><a href="#">Exhibitions <i class="fa fa-angle-right menu-item-arrow"></i></a></li>
            </ul>
          </div><!-- END: Categories -->
</div>

<div id="page" class="hfeed site">
	<header id="header" class="site-header <?php echo esc_attr( $is_sticky_menu );?>" role="banner">
		<div class="container">
				<div class="header-main">
					<div class="header-left">
						<span class="sidebar-offcanvas-toggle"><i class="fa fa-bars"></i></span>
						<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><img class="logo" src="<?php echo dt_get_theme_option('logo','');?>"/></a></h1>
					</div>
					<div class="header-right">
						<nav id="primary-navigation" class="site-navigation primary-navigation" role="navigation">
							<button class="menu-toggle"><?php esc_html_e( 'Primary Menu', 'ticketbox' ); ?></button>
							<a class="screen-reader-text skip-link" href="#content"><?php esc_html_e( 'Skip to content', 'ticketbox' ); ?></a>
							<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-menu', 'menu_id' => 'primary-menu' ) ); ?>
						</nav>
						
					</div>
				</div>
		</div>
		
	</header><!-- #header -->
	<?php if($page_heading === 'rev' && ($rev_alias = dt_get_post_meta('rev_alias'))):?>
	<div id="dt-slides" class="wrap">
		<?php echo do_shortcode('[rev_slider '.$rev_alias.']')?>
	</div>
	<?php endif; ?>
	<?php $no_padding = dt_get_post_meta('no_padding'); ?>
	<div id="main" class="site-main <?php echo (!empty($no_padding) ? ' no-padding':'') ?>">
