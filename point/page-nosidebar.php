<?php
/**
 * Template Name: Page Without Sidebar
 */
?>
<?php get_header(); ?>
<div id="page" class="single">
	<div class="content">
		<article class="ss-full-width">
			<div id="content_box" >
				<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
					<div id="post-<?php the_ID(); ?>" <?php post_class('g post'); ?>>
						<header>
							<h1 class="title"><?php the_title(); ?></h1>
						</header>
						<div class="post-content box mark-links">
							<?php the_content(); ?>
							<?php wp_link_pages(array('before' => '<div class="pagination">', 'after' => '</div>', 'link_before'  => '<span class="current"><span class="currenttext">', 'link_after' => '</span></span>', 'next_or_number' => 'next_and_number', 'nextpagelink' => __('Next','mythemeshop'), 'previouspagelink' => __('Previous','mythemeshop'), 'pagelink' => '%','echo' => 1 )); ?>
						</div><!--.post-content box mark-links -->
					</div><!--.g post-->
					<?php comments_template( '', true ); ?>
				<?php endwhile; ?>
			</div>
		</article>
		<?php get_footer(); ?>