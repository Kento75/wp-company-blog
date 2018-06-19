<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Pacific
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php if( pacific_is_sticky() ) :?>
		<div class="sticky-label">
			<?php echo pacific_get_svg( array( 'icon' => 'star' ) ); /* WPCS: xss ok. */?>
		</div>
	<?php endif;?>

	<?php if( ! is_singular() && has_post_thumbnail() ) : ?>
		<div class="post-thumbnail">
			<a href="<?php the_permalink();?>">
				<?php the_post_thumbnail( 'post-thumbnail' );?>
			</a>
		</div><!-- .post-thumbnail -->
	<?php endif;?>

	<header class="entry-header">
		<?php if ( 'post' === get_post_type() && ! pacific_is_sticky() ) : ?>
		<div class="entry-meta">
			<?php pacific_posted_on(); ?>
		</div><!-- .entry-meta -->
		<?php endif;?>
		<?php
		if ( is_singular() ) :
			the_title( '<h1 class="entry-title screen-reader-text">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;
		?>
	</header><!-- .entry-header -->

	<?php if ( is_singular() || post_password_required() ) : ?>
	<div class="entry-content">
		<?php
			the_content( sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'pacific' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			) );

			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'pacific' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->
	<?php else :?>
	<div class="entry-summary">
		<?php the_excerpt();?>
	</div><!-- .entry-summary -->
	<?php endif;?>

	<?php if ( ! pacific_is_sticky() ) : ?>
		<footer class="entry-footer">
			<?php pacific_entry_footer(); ?>
		</footer><!-- .entry-footer -->
	<?php endif;?>

</article><!-- #post-<?php the_ID(); ?> -->
