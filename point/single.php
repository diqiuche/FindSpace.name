<?php get_header(); ?>
<?php $mts_options = get_option('point'); ?>
<div id="page" class="single">
	<div class="content">
		<!-- Start Article -->
		<article class="article">		
			<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
				<div id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>
					<div class="single_post">
						<header>
							<!-- Start Title -->
							<h1 class="title single-title"><?php the_title(); ?></h1>
							<!-- End Title -->
							<!-- Start Post Meta -->
							<div class="post-info"><span class="theauthor"><?php the_views(); ?></span> | <span class="thetime"><?php the_time( get_option( 'date_format' ) ); ?></span> | <span class="thecategory"><?php the_category(', ') ?></span> | <span class="thecomment"><a href="<?php comments_link(); ?>"><?php comments_number();?></a></span></div>
							<!-- End Post Meta -->
						</header>
						<!-- Start Content -->
						<div class="post-single-content box mark-links">
							<?php if ($mts_options['mts_posttop_adcode'] != '') { ?>
								<?php $toptime = $mts_options['mts_posttop_adcode_time']; if (strcmp( date("Y-m-d", strtotime( "-$toptime day")), get_the_time("Y-m-d") ) >= 0) { ?>
									<div class="topad">
										<?php echo $mts_options['mts_posttop_adcode']; ?>
									</div>
								<?php } ?>
							<?php } ?>
							<?php the_content(); ?>
							<?php wp_link_pages(array('before' => '<div class="pagination">', 'after' => '</div>', 'link_before'  => '<span class="current"><span class="currenttext">', 'link_after' => '</span></span>', 'next_or_number' => 'next_and_number', 'nextpagelink' => __('Next','mythemeshop'), 'previouspagelink' => __('Previous','mythemeshop'), 'pagelink' => '%','echo' => 1 )); ?>
							<?php if ($mts_options['mts_postend_adcode'] != '') { ?>
								<?php $endtime = $mts_options['mts_postend_adcode_time']; if (strcmp( date("Y-m-d", strtotime( "-$endtime day")), get_the_time("Y-m-d") ) >= 0) { ?>
									<div class="bottomad">
										<?php echo $mts_options['mts_postend_adcode'];?>
									</div>
								<?php } ?>
							<?php } ?> 
							<?php if($mts_options['mts_tags'] == '1') { ?>
								<!-- Start Tags -->
								<div class="tags"><?php the_tags('<span class="tagtext">'.__('Tags','mythemeshop').':</span>',', ') ?></div>
								<!-- End Tags -->
							<?php } ?>
						</div>
						<!-- End Content -->
						<!--@Find start Baidu related posts-->
						<?php if ( function_exists( 'echo_ald_crp' ) ) echo_ald_crp(); ?>
						<!-- <div id="hm_t_75133"></div> -->
						<!--@Find end related posts-->
						<?php if($mts_options['mts_author_box'] == '1') { ?>
							<!-- Start Author Box -->
							<div class="postauthor-container">
								<h4><?php _e('About The Author', 'mythemeshop'); ?></h4>
								<div class="postauthor">
									<?php if(function_exists('get_avatar')) { echo get_avatar( get_the_author_meta('email'), '100' );  } ?>
									<h5><?php the_author_meta( 'nickname' ); ?></h5>
									<p><?php the_author_meta('description') ?></p>
								</div>
							</div>
							<!-- End Author Box -->
						<?php }?>  
					</div>
				</div>
				<?php comments_template( '', true ); ?>
			<?php endwhile; ?>
		</article>
<script>window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"2","bdMiniList":false,"bdPic":"","bdStyle":"1","bdSize":"32"},"share":{}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];</script>
		<!-- End Article -->
		<!-- Start Sidebar -->
		<?php get_sidebar(); ?>
		<!-- End Sidebar -->
		<?php get_footer(); ?>
