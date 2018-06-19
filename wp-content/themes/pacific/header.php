<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Pacific
 */

?><!doctype html>
<html <?php language_attributes(); ?> class="no-js no-svg">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">

	<?php get_template_part( 'template-parts/skip', 'links' );?>

	<header id="masthead" class="site-header">
		<div class="wrap">
			<div class="site-branding">
				<?php
				the_custom_logo();
				if ( is_front_page() && is_home() ) : ?>
					<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<?php else : ?>
					<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
				<?php
				endif;

				$description = get_bloginfo( 'description', 'display' );
				if ( $description || is_customize_preview() ) : ?>
					<p class="site-description"><?php echo $description; /* WPCS: xss ok. */ ?></p>
				<?php
				endif; ?>
			</div><!-- .site-branding -->

			<?php if( has_nav_menu( 'menu-1') ) : ?>
				<nav id="site-navigation" class="main-navigation" role="navigation">
					<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
						<?php
						echo pacific_get_svg( array( 'icon' => 'menu' ) ); /* WPCS: xss ok. */
						echo pacific_get_svg( array( 'icon' => 'close' ) ); /* WPCS: xss ok. */
						?>
						<span class="span screen-reader-text"><?php esc_html_e( 'Navigation', 'pacific' );?></span>
					</button>
					<?php
						wp_nav_menu( array(
							'theme_location' 	=> 'menu-1',
							'menu_id'        	=> 'primary-menu',
							'container_class' 	=> 'wrap'
						) );
					?>
				</nav><!-- #site-navigation -->
			<?php endif;?>
		</div><!-- .wrap -->
	</header><!-- #masthead -->

	<?php get_template_part( 'template-parts/content', 'hero' );?>

	<?php pacific_breadcrumb();?>

	<div id="content" class="site-content">
