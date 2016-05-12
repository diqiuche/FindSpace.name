<?php
/*-----------------------------------------------------------------------------------*/
/*  Do not remove these lines, sky will fall on your head.
/*-----------------------------------------------------------------------------------*/
require_once( dirname( __FILE__ ) . '/theme-options.php' );

if ( ! function_exists( 'mts_setup' ) ) :
function mts_setup() {

if ( ! isset( $content_width ) ) $content_width = 960;

/*-----------------------------------------------------------------------------------*/
/*  Load Translation Text Domain
/*-----------------------------------------------------------------------------------*/
load_theme_textdomain( 'mythemeshop', get_template_directory().'/lang' );
add_theme_support('automatic-feed-links');

/*-----------------------------------------------------------------------------------*/
/*  Post Thumbnail Support
/*-----------------------------------------------------------------------------------*/
add_theme_support( 'post-thumbnails' );
set_post_thumbnail_size( 220, 162, true );
add_image_size( 'featured', 220, 162, true ); //Latest posts thumb
add_image_size( 'carousel', 140, 130, true ); //Bottom featured thumb
add_image_size( 'bigthumb', 620, 315, true ); //Big thumb for featured area
add_image_size( 'mediumthumb', 300, 200, true ); //Medium thumb for featured area
add_image_size( 'smallthumb', 140, 100, true ); //Small thumb for featured area
add_image_size( 'widgetthumb', 60, 57, true ); //widget

/*-----------------------------------------------------------------------------------*/
/*  Custom Menu Support
/*-----------------------------------------------------------------------------------*/
add_theme_support( 'menus' );
if ( function_exists( 'register_nav_menus' ) ) {
    register_nav_menus(
        array(
          'primary-menu' => 'Primary Menu',
		  'footer-menu' => 'Footer Menu'
        )
    );
}

}
endif;
add_action( 'after_setup_theme', 'mts_setup' );

/*-----------------------------------------------------------------------------------*/
/*	Load Menu Description
/*-----------------------------------------------------------------------------------*/
class mts_Walker extends Walker_Nav_Menu
{
	function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
		global $wp_query;
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$class_names = $value = '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
		$class_names = ' class="' . esc_attr( $class_names ) . '"';

		$output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';

		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

		$item_output = $args->before;
		$item_output .= '<a'. $attributes .'>';
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		$item_output .= '<br /><span class="sub">' . $item->description . '</span>';
		$item_output .= '</a>';
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}

/*-----------------------------------------------------------------------------------*/
/*	RTL language support - also in mts_load_footer_scripts()
/*-----------------------------------------------------------------------------------*/
$mts_options = get_option('point');
if(isset($mts_options['mts_rtl'])) { if($mts_options['mts_rtl'] == '1' && $mts_options['mts_rtl'] != '') {
    function mts_rtl() {
        global $wp_locale, $wp_styles;
        $wp_locale->text_direction = 'rtl';
    	if ( ! is_a( $wp_styles, 'WP_Styles' ) ) {
    		$wp_styles = new WP_Styles();
    		$wp_styles->text_direction = 'rtl';
    	}
    }
    add_action( 'init', 'mts_rtl' );
}}

/*-----------------------------------------------------------------------------------*/
/*	Javascsript
/*-----------------------------------------------------------------------------------*/
function mts_add_scripts() {
	$mts_options = get_option('point');
	global $data; //get theme options
	
	wp_enqueue_script('jquery');

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_enqueue_script('customscript', get_stylesheet_directory_uri() . '/js/customscript.js', array(), 'null', true);

}
add_action('wp_enqueue_scripts','mts_add_scripts');

/*-----------------------------------------------------------------------------------*/
/* Enqueue CSS
/*-----------------------------------------------------------------------------------*/
function mts_enqueue_css() {
    $mts_options = get_option('point');
    global $data;
	
	wp_enqueue_style('stylesheet', get_stylesheet_directory_uri() . '/style.css','style');
	
	//Responsive
    if($mts_options['mts_responsive'] == '1') {
        wp_enqueue_style('responsive', get_template_directory_uri() . '/css/responsive.css', 'style');
    }

    // RTL
    if(isset($mts_options['mts_rtl'])) { if($mts_options['mts_rtl'] == '1' && $mts_options['mts_rtl'] != '') {
		wp_register_style( 'mts_rtl', get_template_directory_uri() . '/css/rtl.css', 'style', true );
		wp_enqueue_style( 'mts_rtl' );
	}}
	
	$mts_sclayout = '';
	$mts_bg = '';
	if ($mts_options['mts_bg_pattern_upload'] != '') {
		$mts_bg = $mts_options['mts_bg_pattern_upload'];
	}
	if($mts_options['mts_layout'] == 'sclayout') {
		$mts_sclayout = '
			.article { float: right;}
			.sidebar.c-4-12 { float: left; padding-left: 0; padding-right: 2%; }';
	}
	
	$custom_css = "
		body {background-color:{$mts_options['mts_bg_color']}; }
		body {background-image: url({$mts_bg});}
		input#author:focus, input#email:focus, input#url:focus, #commentform textarea:focus, .widget .wpt_widget_content #tags-tab-content ul li a { border-color:{$mts_options['mts_color_scheme']};}
		a:hover, .menu .current-menu-item > a, .menu .current-menu-item, .current-menu-ancestor > a.sf-with-ul, .current-menu-ancestor, footer .textwidget a, .single_post a, #commentform a, .copyrights a:hover, a, footer .widget li a:hover, .menu > li:hover > a, .single_post .post-info a, .post-info a, .readMore a, .reply a, .fn a, .carousel a:hover, .single_post .related-posts a:hover, .sidebar.c-4-12 .textwidget a, footer .textwidget a, .sidebar.c-4-12 a:hover { color:{$mts_options['mts_color_scheme']}; }	
		.nav-previous a, .nav-next a, .header-button, .sub-menu, #commentform input#submit, .tagcloud a, #tabber ul.tabs li a.selected, .featured-cat, .mts-subscribe input[type='submit'], .pagination a, .widget .wpt_widget_content #tags-tab-content ul li a, .latestPost-review-wrapper { background-color:{$mts_options['mts_color_scheme']}; color: #fff; }
		{$mts_sclayout}
		{$mts_options['mts_custom_css']}
			";
	wp_add_inline_style( 'stylesheet', $custom_css );
}
add_action('wp_enqueue_scripts', 'mts_enqueue_css', 99);

/*-----------------------------------------------------------------------------------*/
/*  Enable Widgetized sidebar
/*-----------------------------------------------------------------------------------*/
function mts_widgets_init() {
	register_sidebar(array(
		'name'=>'Sidebar',
		'description'   => __( 'Appears on posts and pages', 'mythemeshop' ),
		'before_widget' => '<li id="%1$s" class="widget widget-sidebar %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
}
add_action( 'widgets_init', 'mts_widgets_init' );

/*-----------------------------------------------------------------------------------*/
/*  Load Widgets & Shortcodes
/*-----------------------------------------------------------------------------------*/
// Add the 125x125 Ad Block Custom Widget
include("functions/widget-ad125.php");

// Add the 300x250 Ad Block Custom Widget
include("functions/widget-ad300.php");

// Add Facebook Like box Widget
include("functions/widget-fblikebox.php");

// Add Subscribe Widget
include("functions/widget-subscribe.php");

// Add Social Profile Widget
include("functions/widget-social.php");

// Add Welcome message
include("functions/welcome-message.php");

// Recommended Plugin Activation
include( "functions/plugin-activation.php" );

// Theme Functions
include("functions/theme-actions.php");

/*-----------------------------------------------------------------------------------*/
/*	Filters customize wp_title
/*-----------------------------------------------------------------------------------*/
function mts_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( __( 'Page %s', 'mythemeshop' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'mts_wp_title', 10, 2 );

/*-----------------------------------------------------------------------------------*/
/*  Filters that allow shortcodes in Text Widgets
/*-----------------------------------------------------------------------------------*/
add_filter('widget_text', 'shortcode_unautop');
add_filter('widget_text', 'do_shortcode');
add_filter('the_content_rss', 'do_shortcode');

/*-----------------------------------------------------------------------------------*/
/*	Custom Gravatar Support
/*-----------------------------------------------------------------------------------*/
if( !function_exists( 'mts_custom_gravatar' ) ) {
    function mts_custom_gravatar( $avatar_defaults ) {
        $mts_avatar = get_template_directory_uri() . '/images/gravatar.png';
        $avatar_defaults[$mts_avatar] = 'Custom Gravatar (/images/gravatar.png)';
        return $avatar_defaults;
    }
    add_filter( 'avatar_defaults', 'mts_custom_gravatar' );
}

/*-----------------------------------------------------------------------------------*/
/*	Remove more link from the_content and use custom read more
/*-----------------------------------------------------------------------------------*/
add_filter( 'the_content_more_link', 'mts_remove_more_link', 10, 2 );
function mts_remove_more_link( $more_link, $more_link_text ) {
	return '';
}
// shorthand function to check for more tag in post
function mts_post_has_moretag() {
    global $post;
    return strpos( $post->post_content, '<!--more-->' );
}

/*-----------------------------------------------------------------------------------*/
/*	Custom Comments template
/*-----------------------------------------------------------------------------------*/
function mts_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
		<div id="comment-<?php comment_ID(); ?>" style="position:relative;">
			<div class="comment-author vcard">
				<?php echo get_avatar( $comment->comment_author_email, 70 ); ?>
				<div class="comment-metadata">
				<?php printf(__('<span class="fn">%s</span>', 'mythemeshop'), get_comment_author_link()) ?>
				<time><?php comment_date(get_option( 'date_format' )); ?></time>
				<span class="comment-meta">
					<?php edit_comment_link(__('(Edit)', 'mythemeshop'),'  ','') ?>
				</span>
				<span class="reply">
					<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
				</span>
				</div>
			</div>
			<?php if ($comment->comment_approved == '0') : ?>
				<em><?php _e('Your comment is awaiting moderation.', 'mythemeshop') ?></em>
				<br />
			<?php endif; ?>
			<div class="commentmetadata">
				<?php comment_text() ?>
			</div>
		</div>
	</li>
<?php }

/*-----------------------------------------------------------------------------------*/
/*	Short Post Title
/*-----------------------------------------------------------------------------------*/
function mts_short_title($after = '', $length){
	$mytitle = get_the_title();
	if ( strlen($mytitle) > $length ){
		$mytitle = substr($mytitle,0,$length);
		echo $mytitle . $after; 
	}
	else { echo $mytitle; }
}

/*-----------------------------------------------------------------------------------*/
/*  excerpt
/*-----------------------------------------------------------------------------------*/
function mts_excerpt($limit) {
  $excerpt = explode(' ', get_the_excerpt(), $limit);
  if (count($excerpt)>=$limit) {
    array_pop($excerpt);
    $excerpt = implode(" ",$excerpt);
  } else {
    $excerpt = implode(" ",$excerpt);
  }
  $excerpt = preg_replace('`[[^]]*]`','',$excerpt);
  return $excerpt;
}

/*-----------------------------------------------------------------------------------*/
/* nofollow to next/previous links
/*-----------------------------------------------------------------------------------*/
function mts_pagination_add_nofollow($content) {
    return 'rel="nofollow"';
}
add_filter('next_posts_link_attributes', 'mts_pagination_add_nofollow' );
add_filter('previous_posts_link_attributes', 'mts_pagination_add_nofollow' );

/*-----------------------------------------------------------------------------------*/
/* Nofollow to category links
/*-----------------------------------------------------------------------------------*/
add_filter( 'the_category', 'mts_add_nofollow_cat' ); 
function mts_add_nofollow_cat( $text ) {
$text = str_replace('rel="category tag"', 'rel="nofollow"', $text); return $text; }

/*-----------------------------------------------------------------------------------*/ 
/* nofollow post author link
/*-----------------------------------------------------------------------------------*/
add_filter('the_author_posts_link', 'mts_nofollow_the_author_posts_link');
function mts_nofollow_the_author_posts_link ($link) {
return str_replace('<a href=', '<a rel="nofollow" href=',$link); }

/*-----------------------------------------------------------------------------------*/ 
/* nofollow to reply links
/*-----------------------------------------------------------------------------------*/
function mts_add_nofollow_to_reply_link( $link ) {
return str_replace( '")\'>', '")\' rel=\'nofollow\'>', $link );
}
add_filter( 'comment_reply_link', 'mts_add_nofollow_to_reply_link' );
    
/*-----------------------------------------------------------------------------------*/
/* Removes Trackbacks from the comment count
/*-----------------------------------------------------------------------------------*/
add_filter('get_comments_number', 'mts_comment_count', 0);
function mts_comment_count( $count ) {
    if ( ! is_admin() ) {
        global $id;
        $comments_by_type = &separate_comments(get_comments('status=approve&post_id=' . $id));
        return count($comments_by_type['comment']);
    } else {
        return $count;
    }
}

/*-----------------------------------------------------------------------------------*/
/* adds a class to the post if there is a thumbnail
/*-----------------------------------------------------------------------------------*/
function has_thumb_class($classes) {
    global $post;
    if( has_post_thumbnail($post->ID) ) { $classes[] = 'has_thumb'; }
        return $classes;
}
add_filter('post_class', 'has_thumb_class');

/*-----------------------------------------------------------------------------------*/ 
/* Pagination
/*-----------------------------------------------------------------------------------*/
function mts_pagination($pages = '', $range = 3) { 
    $showitems = ($range * 3)+1;
    global $paged; if(empty($paged)) $paged = 1;
    if($pages == '') {
        global $wp_query; $pages = $wp_query->max_num_pages; 
        if(!$pages){ $pages = 1; } 
    }
    if(1 != $pages) { 
        echo "<div class='pagination'><ul>";
        if($paged > 2 && $paged > $range+1 && $showitems < $pages) 
            echo "<li><a rel='nofollow' href='".get_pagenum_link(1)."'>&laquo; First</a></li>";
        if($paged > 1 && $showitems < $pages) 
            echo "<li><a rel='nofollow' href='".get_pagenum_link($paged - 1)."' class='inactive'>&lsaquo; Previous</a></li>";
        for ($i=1; $i <= $pages; $i++){ 
            if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems )) { 
                echo ($paged == $i)? "<li class='current'><span class='currenttext'>".$i."</span></li>":"<li><a rel='nofollow' href='".get_pagenum_link($i)."' class='inactive'>".$i."</a></li>";
            } 
        } 
        if ($paged < $pages && $showitems < $pages) 
            echo "<li><a rel='nofollow' href='".get_pagenum_link($paged + 1)."' class='inactive'>Next &rsaquo;</a></li>";
        if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) 
            echo "<li><a rel='nofollow' class='inactive' href='".get_pagenum_link($pages)."'>Last &raquo;</a></li>";
            echo "</ul></div>"; 
    }
}

/*-----------------------------------------------------------------------------------*/
/* Single Post Pagination
/*-----------------------------------------------------------------------------------*/
function mts_wp_link_pages_args_prevnext_add($args)
{
    global $page, $numpages, $more, $pagenow;
    if (!$args['next_or_number'] == 'next_and_number')
        return $args; 
    $args['next_or_number'] = 'number'; 
    if (!$more)
        return $args; 
    if($page-1) 
        $args['before'] .= _wp_link_page($page-1)
        . $args['link_before']. $args['previouspagelink'] . $args['link_after'] . '</a>'
    ;
    if ($page<$numpages) 
    
        $args['after'] = _wp_link_page($page+1)
        . $args['link_before'] . $args['nextpagelink'] . $args['link_after'] . '</a>'
        . $args['after']
    ;
    return $args;
}
add_filter('wp_link_pages_args', 'mts_wp_link_pages_args_prevnext_add');

/*-----------------------------------------------------------------------------------*/
/* add <!-- next-page --> button to tinymce
/*-----------------------------------------------------------------------------------*/
add_filter('mce_buttons','mts_wysiwyg_editor');
function mts_wysiwyg_editor($mce_buttons) {
   $pos = array_search('wp_more',$mce_buttons,true);
   if ($pos !== false) {
       $tmp_buttons = array_slice($mce_buttons, 0, $pos+1);
       $tmp_buttons[] = 'wp_page';
       $mce_buttons = array_merge($tmp_buttons, array_slice($mce_buttons, $pos+1));
   }
   return $mce_buttons;
}

/*-----------------------------------------------------------------------------------*/
/*  WP Mega Menu Configuration
/*-----------------------------------------------------------------------------------*/
function megamenu_parent_element( $selector ) {
    return '.main-header';
}
add_filter( 'wpmm_container_selector', 'megamenu_parent_element' );

function menu_item_color( $item_output, $item_color, $item, $depth, $args ) {
    $mts_options = get_option('point');
    if (!empty($item_color))
        return $item_output.'<style>.menu-item-'. $item->ID . '-megamenu .wpmm-posts p.wpmm-post-excerpt, .menu-item-'. $item->ID . '-megamenu .wpmm-posts .wpmm-entry-date, #wpmm-megamenu.menu-item-'. $item->ID . '-megamenu .wpmm-posts .wpmm-entry-title a, #wpmm-megamenu.menu-item-'. $item->ID . '-megamenu .wpmm-posts .wpmm-entry-author, #wpmm-megamenu.menu-item-'. $item->ID . '-megamenu .wpmm-posts .wpmm-entry-author a, #wpmm-megamenu.menu-item-'. $item->ID . '-megamenu .wpmm-subcategories a { color: #fff!important; }.menu-item-'. $item->ID . '-megamenu, .wpmm-megamenu-showing { background-color: ' . $mts_options['mts_color_scheme'] . ' !important; color: #fff; } #wpmm-megamenu.wpmm-light-scheme .wpmm-3-posts { border-left: 1px solid rgba(255, 255, 255, 0.24); } #wpmm-megamenu.menu-item-'. $item->ID . '-megamenu.wpmm-visible { border-top: 1px solid rgba(255, 255, 255, 0.24);} #wpmm-megamenu .review-total-only { background:' . $mts_options['mts_color_scheme'] . '; color: #fff; }</style>';
    else
        return $item_output;
}
add_filter( 'wpmm_color_output', 'menu_item_color', 10, 5 );

function megamenu_exclude( $exclude, $args ) {
    if ( $args['theme_location'] == 'footer-menu' )
        $exclude = true;

    return $exclude;
}
add_filter( 'wpmm_exclude_menu', 'megamenu_exclude', 10, 2 );

/* Change image size */
function megamenu_thumbnails( $thumbnail_html, $post_id ) {
	$thumbnail_html = '<div class="wpmm-thumbnail">';
	$thumbnail_html .= '<a title="'.get_the_title( $post_id ).'" href="'.get_permalink( $post_id ).'">';
	if(has_post_thumbnail($post_id)):
		$thumbnail_html .= get_the_post_thumbnail($post_id, 'featured', array('title' => ''));
	else:
		$thumbnail_html .= '<img src="'.get_template_directory().'/images/nothumb-widgetfull.png" alt="'.__('No Preview', 'wpmm').'"  class="wp-post-image" />';
	endif;
	$thumbnail_html .= '</a>';
	
	// WP Review
	$thumbnail_html .= (function_exists('wp_review_show_total') ? wp_review_show_total(false) : '');
	
	$thumbnail_html .= '</div>';

	return $thumbnail_html;
}
add_filter( 'wpmm_thumbnail_html', 'megamenu_thumbnails', 10, 2 );

/*控制摘要字数*/
function new_excerpt_length($length) {
return 200;
}
add_filter("excerpt_length", "new_excerpt_length");

/*add ads*/
function showads() { return '
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- text200x90 -->
<ins class="adsbygoogle"
     style="display:inline-block;width:200px;height:90px"
     data-ad-client="ca-pub-8527554614606787"
     data-ad-slot="4467426357"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
'; } add_shortcode('text200x90', 'showads');
/*所有链接都在新窗口打开*/
function autoblank($text) {
	$return = str_replace('<a', '<a target="_blank"', $text);
	return $return;
}
add_filter('the_content', 'autoblank');

?>
