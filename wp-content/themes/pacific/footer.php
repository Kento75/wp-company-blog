<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Pacific
 */

?>

	</div><!-- #content -->

	<?php get_sidebar();?>

	<footer id="colophon" class="site-footer">
		<div class="wrap">
			<?php
			if ( has_nav_menu( 'menu-2' ) ) : ?>
				<nav class="social-navigation" role="navigation" aria-label="<?php echo esc_html__( 'Footer Social Links Menu', 'pacific' ); ?>">
					<?php
						wp_nav_menu( array(
							'theme_location' => 'menu-2',
							'menu_class'     => 'social-links-menu',
							'depth'          => 1,
							'link_before'    => '<span class="screen-reader-text">',
							'link_after'     => '</span>' . pacific_get_svg( array( 'icon' => 'share' ) ),
						) );
					?>
				</nav><!-- .social-navigation -->
			<?php endif;

			pacific_do_footer_copyright();
			?>

		</div><!-- .wrap -->
	</footer><!-- #colophon -->
</div><!-- #page -->
<?php
pacific_return_to_top();
wp_footer(); ?>

</body>
</html>
