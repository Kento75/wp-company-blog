<?php
/**
 * Skip links
 *
 * @package Pacific
 */
?>

<?php if( has_nav_menu( 'menu-1' ) ) :?>
	<a class="skip-link screen-reader-text" href="#site-navigation"><?php esc_html_e( 'Skip to navigation', 'pacific' ); ?></a>
<?php endif;?>

<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'pacific' ); ?></a>

<?php if( is_active_sidebar( 'sidebar-1' ) ) :?>
	<a class="skip-link screen-reader-text" href="#secondary"><?php esc_html_e( 'Skip to Footer', 'pacific' ); ?></a>
<?php endif;?>
