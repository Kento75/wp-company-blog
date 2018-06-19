<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Pacific
 */

get_header(); ?>

<div class="content-sidebar-wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main">

		<?php
		while ( have_posts() ) : the_post();

			get_template_part( 'template-parts/content', get_post_format() );

			if ( get_theme_mod( 'author_display', true ) == true ) {
				get_template_part( 'template-parts/biography' );
			}

			the_post_navigation( array(
			    'prev_text'                  => __( '<span>&larr; previous post</span> %title', 'pacific' ),
			    'next_text'                  => __( '<span>next post &rarr;</span> %title', 'pacific' ),
			    'screen_reader_text'		 => __( 'Continue Reading', 'pacific' ),
			) );

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; // End of the loop.
		?>

		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- .content-sidebar-wrap -->

<?php
get_footer();
