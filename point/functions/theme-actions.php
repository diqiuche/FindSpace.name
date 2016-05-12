<?php
$mts_options = get_option('point');	

/*------------[ Meta ]-------------*/
if ( ! function_exists( 'mts_meta' ) ) {
	function mts_meta(){
	global $mts_options
?>
<?php if ($mts_options['mts_favicon'] != ''){ ?>
	<link rel="icon" href="<?php echo $mts_options['mts_favicon']; ?>" type="image/x-icon" />
<?php } ?>
<!--iOS/android/handheld specific -->
<link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/apple-touch-icon.png" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<?php }
}

/*------------[ Head ]-------------*/
if ( ! function_exists( 'mts_head' ) ){
	function mts_head() {
	global $mts_options
?>
<?php echo $mts_options['mts_header_code']; ?>
<?php }
}
add_action('wp_head', 'mts_head');

/*------------[ Copyrights ]-------------*/
if ( ! function_exists( 'mts_copyrights_credit' ) ) {
	function mts_copyrights_credit() { 
	global $mts_options
?>
<!--start copyrights-->
<div class="row" id="copyright-note">
	<?php if ($mts_options['mts_footer_logo'] != '') { ?>
		<?php list($width, $height, $type, $attr) = getimagesize($mts_options['mts_footer_logo']); ?>
		<div class="foot-logo">
			<a href="<?php echo home_url(); ?>" rel="nofollow"><img src="<?php echo $mts_options['mts_footer_logo']; ?>" alt="<?php bloginfo( 'name' ); ?>" <?php echo $attr; ?>></a>
		</div>
	<?php } ?>
	<div class="copyright-left-text">Copyright &copy; <?php echo date("Y") ?> <a href="<?php echo home_url(); ?>" title="<?php bloginfo('description'); ?>" rel="nofollow"><?php bloginfo('name'); ?></a>.</div>
	<div class="copyright-text"><?php echo $mts_options['mts_copyrights']; ?></div>
	<div class="footer-navigation">
		<?php if ( has_nav_menu( 'footer-menu' ) ) { ?>
			<?php wp_nav_menu( array( 'theme_location' => 'footer-menu', 'menu_class' => 'menu', 'container' => '' ) ); ?>
		<?php } else { ?>
			<ul class="menu">
				<?php wp_list_pages('title_li='); ?>
			</ul>
			<?php } ?>
	</div>
	<div class="top"><a href="#top" class="toplink">&nbsp;</a></div>
</div>
<!--end copyrights-->
<?php }
}

?>