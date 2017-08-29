<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package vantage
 * @since vantage 1.0
 * @license GPL 2.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="entry-main">

		<?php do_action('vantage_entry_main_top') ?>

		<header class="entry-header">
	            <?php if( has_post_thumbnail() && siteorigin_setting('blog_featured_image_type') == 'large' ): ?>
			<div class="entry-thumbnail">
				<a href="<?php the_permalink() ?>"><?php the_post_thumbnail( 'vantage-thumbnail-no-sidebar' ) ?></a>
			</div>
		    <?php endif; ?>

		    <h1 class="entry-title"></h1>

		    <div class="entry-meta">
			<?php vantage_posted_on(); ?>
		    </div><!-- .entry-meta -->
		</header><!-- .entry-header -->

		<div class="entry-content">
			<?php the_content( __( 'Lees verder <span class="meta-nav">&rarr;</span>', 'vantage' ) ); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'vantage' ), 'after' => '</div>' ) ); ?>
		</div><!-- .entry-content -->

		<?php do_action('vantage_entry_main_bottom') ?>

	</div>

</article><!-- #post-<?php the_ID(); ?> -->
