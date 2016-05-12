<?php $mts_options = get_option('point'); ?>
		</div><!--.content-->
	</div><!--#page-->
<footer>
	<?php if(isset($mts_options['mts_featured_carousel'])) { if($mts_options['mts_featured_carousel'] == '1' && $mts_options['mts_featured_carousel'] != '') { ?>
		<div class="carousel">
			<h3 class="frontTitle"><div class="latest"><?php $first_cat = $mts_options['mts_featured_carousel_cat']; echo get_cat_name( $first_cat ); ?></div></h3>
			<?php $i = 1; $my_query = new wp_query( 'cat='.$mts_options['mts_featured_carousel_cat'].'&posts_per_page=6&ignore_sticky_posts=1' );
				while ($my_query->have_posts()) : $my_query->the_post(); ?>
					<div class="excerpt">
						<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" rel="nofollow" id="footer-thumbnail">
							<div>
								<div class="hover"><span class="icon-link"></span></div>
								<?php if ( has_post_thumbnail() ) { ?> 
									<?php the_post_thumbnail('carousel',array('title' => '')); ?>
								<?php } else { ?>
									<div class="featured-thumbnail">
										<img src="<?php echo get_template_directory_uri(); ?>/images/footerthumb.png" class="attachment-featured wp-post-image" alt="<?php the_title(); ?>">
									</div>
								<?php } ?>
							</div>
							<p class="footer-title">
								<span class="featured-title"><?php the_title(); ?></span>
							</p>
						</a>
					</div><!--.post excerpt-->                
			<?php endwhile; wp_reset_query(); ?> 
		</div>
	<?php }} ?>
</footer><!--footer-->
<div class="copyrights"><?php mts_copyrights_credit(); ?></div>
<?php wp_footer(); ?>

</div><!--.main-container-->
<script>
   hljs.initHighlightingOnLoad();
  </script>
<!--百度统计-->
<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "//hm.baidu.com/hm.js?544c08b0d6ddd7518044f1dbcadf1e68";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>
</body>
</html>