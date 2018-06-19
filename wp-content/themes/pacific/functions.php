<?php
/**
 * Pacific functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Pacific
 */

/**
 * Pacific only works in WordPress 4.7 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '4.7-alpha', '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
	return;
}

if ( ! function_exists( 'pacific_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function pacific_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Pacific, use a find and replace
	 * to change 'pacific' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'pacific', trailingslashit( WP_LANG_DIR ) . 'themes/' );
	load_theme_textdomain( 'pacific', get_stylesheet_directory() . '/languages' );
	load_theme_textdomain( 'pacific', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	set_post_thumbnail_size( 780, 320, true );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'menu-1' => esc_html__( 'Primary', 'pacific' ),
		'menu-2' => esc_html__( 'Social Link', 'pacific' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'pacific_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, icons, and column width.
	 */
	add_editor_style( array( 'assets/css/editor-style.min.css', pacific_fonts_url() ) );

	// This theme uses its own gallery styles.
	add_filter( 'use_default_gallery_style', '__return_false' );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support( 'custom-logo', array(
		'height'      => 250,
		'width'       => 250,
		'flex-width'  => true,
		'flex-height' => true,
	) );
}
endif;
add_action( 'after_setup_theme', 'pacific_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function pacific_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'pacific_content_width', 640 );
}
add_action( 'after_setup_theme', 'pacific_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function pacific_widgets_init() {

	register_sidebar( array(
		'name'          => esc_html__( 'Footer Widgets', 'pacific' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'pacific' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

}
add_action( 'widgets_init', 'pacific_widgets_init' );

/**
 * Register custom fonts.
 */
function pacific_fonts_url() {
	$fonts_url = '';

	/*
	 * Translators: If there are characters in your language that are not
	 * supported by Montserrat, translate this to 'off'. Do not translate
	 * into your own language.
	 */
	$montserrat = _x( 'on', 'Montserrat font: on or off', 'pacific' );

	if ( 'off' !== $montserrat ) {
		$font_families = array();

		$font_families[] = 'Montserrat:400,400i,700,700i';

		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);

		$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return esc_url_raw( $fonts_url );
}

/**
 * Add preconnect for Google Fonts.
 *
 * @param array  $urls           URLs to print for resource hints.
 * @param string $relation_type  The relation type the URLs are printed.
 * @return array $urls           URLs to print for resource hints.
 */
function pacific_resource_hints( $urls, $relation_type ) {
	if ( wp_style_is( 'pacific-fonts', 'queue' ) && 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
	}

	return $urls;
}
add_filter( 'wp_resource_hints', 'pacific_resource_hints', 10, 2 );

/**
 * Handles JavaScript detection.
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 */
function pacific_javascript_detection() {
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action( 'wp_head', 'pacific_javascript_detection', 0 );

/**
 * Enqueue scripts and styles.
 */
function pacific_scripts() {

	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	wp_dequeue_style( 'contact-form-7' );

	// Add custom fonts, used in the main stylesheet.
	if ( ! class_exists( 'Easy_Google_Fonts' ) ) {
		wp_enqueue_style( 'pacific-fonts', pacific_fonts_url(), array(), null );
	}

	wp_enqueue_style( 'pacific-style', get_theme_file_uri( "/style{$suffix}.css" ) );
	wp_style_add_data( 'pacific-style', 'rtl', 'replace' );

	wp_enqueue_script( 'html5', get_theme_file_uri( "/assets/vendor/js/html5{$suffix}.js" ), array(), '3.7.3' );
	wp_script_add_data( 'html5', 'conditional', 'lt IE 9' );

	wp_enqueue_script( 'respond', get_theme_file_uri( "/assets/vendor/js/respond{$suffix}.js" ), array(), '1.4.2' );
	wp_script_add_data( 'respond', 'conditional', 'lt IE 9' );

	wp_enqueue_script( 'nwmatcher', get_theme_file_uri( "/assets/vendor/js/nwmatcher{$suffix}.js" ), array(), '1.4.1' );
	wp_script_add_data( 'nwmatcher', 'conditional', 'lt IE 9' );

	wp_enqueue_script( 'selectivizr', get_theme_file_uri( "/assets/vendor/js/selectivizr{$suffix}.js" ), array(), '1.0.2' );
	wp_script_add_data( 'selectivizr', 'conditional', 'lt IE 9' );

	wp_enqueue_script( 'jquery-fitvids', get_template_directory_uri() . "/assets/js/fitvids/jquery.fitvids{$suffix}.js", array( 'jquery' ), '1.2.0', true );
	wp_enqueue_script( 'jquery-stickit', get_template_directory_uri() . "/assets/js/stickit/jquery.stickit{$suffix}.js", array( 'jquery' ), '0.2.13', true );
	wp_enqueue_script( 'pacific-script', get_template_directory_uri() . "/assets/js/frontend{$suffix}.js", array( 'jquery', 'jquery-masonry' ), '20151215', true );

	$output = array(
		'expandMenu' 	=> pacific_get_svg( array( 'icon' => 'expand' ) ),
		'collapseMenu' 	=> pacific_get_svg( array( 'icon' => 'collapse' ) ),
		'subNav' 		=> '<span class="screen-reader-text">' . esc_html__( 'Sub Navigation', 'pacific' ) . '</span>',
	);
	wp_localize_script( 'pacific-script', 'Pacificl10n', $output );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

}
add_action( 'wp_enqueue_scripts', 'pacific_scripts' );

/**
 * Helper functions.
 */
require get_template_directory() . '/inc/utility.php';

/**
 * Vendor plugin.
 */
require get_template_directory() . '/inc/vendor/vendor.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * SVG icons functions and filters.
 */
require get_template_directory() . '/inc/icon-functions.php';

/**
 * Widgets function and filters.
 */
require get_template_directory() . '/inc/widgets.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer/customizer.php';

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';
