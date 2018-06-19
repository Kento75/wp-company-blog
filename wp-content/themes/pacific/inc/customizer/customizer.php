<?php
/**
 * Pacific Theme Customizer
 *
 * @package Pacific
 */

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function pacific_customize_preview_js() {

	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	wp_enqueue_script( 'pacific_customizer', get_parent_theme_file_uri( "/assets/js/customizer{$suffix}.js" ), array( 'customize-preview', 'customize-selective-refresh' ), '20151215', true );

	$css_selector = pacific_css_color_selector();

	$output = array(
		'primary_color_background' 			=> $css_selector['primary_color_background'],
		'primary_color_border' 				=> $css_selector['primary_color_border'],
		'primary_color_text' 				=> $css_selector['primary_color_text'],
		'secondary_color_background' 		=> $css_selector['secondary_color_background'],
		'secondary_color_text' 				=> $css_selector['secondary_color_text'],
	);
	wp_localize_script( 'pacific_customizer', 'PacificCustomizerl10n', $output );

}
add_action( 'customize_preview_init', 'pacific_customize_preview_js' );

/**
 * Additional customizer control scripts.
 */
function pacific_customizer_control() {

	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	wp_enqueue_style( 'pacific-customizer-control', get_parent_theme_file_uri( "/assets/css/customizer-control$suffix.css" ), array(), time(), 'all' );
	wp_enqueue_script( 'pacific-customizer-control', get_parent_theme_file_uri( "/assets/js/customizer-control$suffix.js" ), array(), time(), true );
}
add_action( 'customize_controls_enqueue_scripts', 'pacific_customizer_control', 15 );

/**
 * [pacific_setting_default description]
 * @return [type] [description]
 */
function pacific_setting_default(){
	$settings = array(
		'primary_color'		=> '#ff9800',
		'secondary_color'	=> '#ef6c00',
		'post_date'			=> true,
		'post_author'		=> true,
		'post_cat'			=> true,
		'post_tag'			=> true,
		'post_comments'		=> true,
		'author_display'	=> true,
		'excerpt_length'	=> 20,
		'posts_navigation'	=> 'posts_navigation',
		'return_top'		=> true,
		'theme_designer'	=> true
	);

	return apply_filters( 'pacific_setting_default', $settings );
}

/**
 * [pacific_css_color_selector description]
 * @return [type] [description]
 */
function pacific_css_color_selector(){
	$pacific_css_color_selector = array(
		'primary_color_background' => '
			button,
			input[type="button"],
			input[type="reset"],
			input[type="submit"],
			.screen-reader-text:focus,
			.sticky-label,
			.post-edit-link:hover,
			.post-edit-link:focus,
			.masonry-container a.more-link:hover,
			.masonry-container a.more-link:focus,
			.comment-body > .reply a:hover,
			.comment-body > .reply a:focus,
			#cancel-comment-reply-link:hover,
			#cancel-comment-reply-link:focus,
			.posts-navigation .nav-previous a:hover,
			.posts-navigation .nav-previous a:focus,
			.posts-navigation .nav-next a:hover,
			.posts-navigation .nav-next a:focus,
			.post-navigation .nav-previous a:hover,
			.post-navigation .nav-previous a:focus,
			.post-navigation .nav-next a:hover,
			.post-navigation .nav-next a:focus,
			.page-numbers:hover:not(.current),
			.page-numbers:focus:not(.current),
			#secondary .widget_tag_cloud a:hover,
			#secondary .widget_tag_cloud a:focus,
			.return-to-top:hover,
			.return-to-top:focus
		',
		'primary_color_text'	=> '
			a,
			.main-navigation ul.menu .sub-menu .menu-item a:hover,
			.main-navigation ul.menu .sub-menu .menu-item a:focus,
			.entry-meta a:hover,
			.entry-meta a:focus,
			.entry-title a:hover,
			.entry-title a:focus,
			.entry-footer a:hover,
			.entry-footer a:focus,
			.author-title a:hover,
			.author-title a:focus,
			.comment-meta a:hover,
			.comment-meta a:focus,
			.footer-widgets a:hover,
			.footer-widgets a:focus,
			.site-footer a:hover,
			.site-footer a:focus
		',
		'primary_color_border'	=> '
			.post-edit-link:hover,
			.post-edit-link:focus,
			.masonry-container a.more-link:hover,
			.masonry-container a.more-link:focus,
			.comment-body > .reply a:hover,
			.comment-body > .reply a:focus,
			.page-numbers:hover:not(.current),
			.page-numbers:focus:not(.current),
			#secondary .widget_tag_cloud a:hover,
			#secondary .widget_tag_cloud a:focus,
			.return-to-top:hover,
			.return-to-top:focus
		',

		'secondary_color_background'	=> '
			button:hover,
			button:active,
			button:focus,
			input[type="button"]:hover,
			input[type="button"]:active,
			input[type="button"]:focus,
			input[type="reset"]:hover,
			input[type="reset"]:active,
			input[type="reset"]:focus,
			input[type="submit"]:hover,
			input[type="submit"]:active,
			input[type="submit"]:focus
		',

		'secondary_color_text'		=> '
			a:hover,
			a:focus
		',
	);

	return apply_filters( 'pacific_css_color_selector', $pacific_css_color_selector );
}

/**
 * Load Customizer Setting.
 */
require get_template_directory() . '/inc/customizer/sanitization-callbacks.php';
require get_template_directory() . '/inc/customizer/settings.php';
require get_template_directory() . '/inc/customizer/output.php';
