<?php
/*
 * 
 * Require the framework class before doing anything else, so we can use the defined urls and dirs
 *
 */
require_once( dirname( __FILE__ ) . '/options/options.php' );

/*
 * 
 * Custom function for filtering the sections array given by theme, good for child themes to override or add to the sections.
 * Simply include this function in the child themes functions.php file.
 *
 * NOTE: the defined constansts for urls, and dir will NOT be available at this point in a child theme, so you must use
 * get_template_directory_uri() if you want to use any of the built in icons
 *
 */
function add_another_section($sections){
	
	//$sections = array();
	$sections[] = array(
				'title' => __('A Section added by hook', 'mythemeshop'),
				'desc' => __('<p class="description">This is a section created by adding a filter to the sections array, great to allow child themes, to add/remove sections from the options.</p>', 'mythemeshop'),
				//all the glyphicons are included in the options folder, so you can hook into them, or link to your own custom ones.
				//You dont have to though, leave it blank for default.
				'icon' => trailingslashit(get_template_directory_uri()).'options/img/glyphicons/glyphicons_062_attach.png',
				//Lets leave this as a blank section, no options just some intro text set above.
				'fields' => array()
				);
	
	return $sections;
	
}//function
//add_filter('nhp-opts-sections-twenty_eleven', 'add_another_section');


/*
 * 
 * Custom function for filtering the args array given by theme, good for child themes to override or add to the args array.
 *
 */
function change_framework_args($args){
	
	//$args['dev_mode'] = false;
	
	return $args;
	
}//function
//add_filter('nhp-opts-args-twenty_eleven', 'change_framework_args');

/*
 * This is the meat of creating the optons page
 *
 * Override some of the default values, uncomment the args and change the values
 * - no $args are required, but there are to be overridden if needed.
 *
 *
 */

function setup_framework_options(){
$args = array();

//Set it to dev mode to view the class settings/info in the form - default is false
$args['dev_mode'] = false;
//Remove the default stylesheet? make sure you enqueue another one all the page will look whack!
//$args['stylesheet_override'] = true;

//Add HTML before the form
//$args['intro_text'] = __('<p>This is the HTML which can be displayed before the form, it isnt required, but more info is always better. Anything goes in terms of markup here, any HTML.</p>', 'mythemeshop');

//Setup custom links in the footer for share icons
$args['share_icons']['twitter'] = array(
										'link' => 'http://twitter.com/mythemeshopteam',
										'title' => 'Follow Us on Twitter', 
										'img' => NHP_OPTIONS_URL.'img/glyphicons/twitter.png'
										);
$args['share_icons']['linked_in'] = array(
										'link' => 'http://www.facebook.com/mythemeshop',
										'title' => 'Like us on Facebook', 
										'img' => NHP_OPTIONS_URL.'img/glyphicons/facebook.png'
										);

//Choose to disable the import/export feature
//$args['show_import_export'] = false;

//Choose a custom option name for your theme options, the default is the theme name in lowercase with spaces replaced by underscores
$args['opt_name'] = 'point';

//Custom menu icon
//$args['menu_icon'] = '';

//Custom menu title for options page - default is "Options"
$args['menu_title'] = __('Theme Options', 'mythemeshop');

//Custom Page Title for options page - default is "Options"
$args['page_title'] = __('Theme Options', 'mythemeshop');

//Custom page slug for options page (wp-admin/themes.php?page=***) - default is "nhp_theme_options"
$args['page_slug'] = 'theme_options';

//Custom page capability - default is set to "manage_options"
//$args['page_cap'] = 'manage_options';

//page type - "menu" (adds a top menu section) or "submenu" (adds a submenu) - default is set to "menu"
//$args['page_type'] = 'submenu';

//parent menu - default is set to "themes.php" (Appearance)
//the list of available parent menus is available here: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
//$args['page_parent'] = 'themes.php';

//custom page location - default 100 - must be unique or will override other items
$args['page_position'] = 62;

//Custom page icon class (used to override the page icon next to heading)
//$args['page_icon'] = 'icon-themes';
		
//Set ANY custom page help tabs - displayed using the new help tab API, show in order of definition		
$args['help_tabs'][] = array(
							'id' => 'nhp-opts-1',
							'title' => __('Support', 'mythemeshop'),
							'content' => __('<p>If you are facing any problem with our theme or theme option panel, head over to our <a href="http://mythemeshop.com/support">Knowledge Base</a></p>', 'mythemeshop')
							);
$args['help_tabs'][] = array(
							'id' => 'nhp-opts-3',
							'title' => __('Credit', 'mythemeshop'),
							'content' => __('<p>Options Panel created using the <a href="http://leemason.github.com/NHP-Theme-Options-Framework/" target="_blank">NHP Theme Options Framework</a> Version 1.0.5</p>', 'mythemeshop')
							);
$args['help_tabs'][] = array(
							'id' => 'nhp-opts-2',
							'title' => __('Earn Money', 'mythemeshop'),
							'content' => __('<p>Earn <strong>70%</strong> commision on every sale by refering your friends and readers. Join our <a href="http://mythemeshop.com/affiliate-program/">Affiliate Program</a>.</p>', 'mythemeshop')
							);

//Set the Help Sidebar for the options page - no sidebar by default										
//$args['help_sidebar'] = __('<p>This is the sidebar content, HTML is allowed.</p>', 'mythemeshop');



$sections = array();

$sections[] = array(
				'icon' => NHP_OPTIONS_URL.'img/glyphicons/gift.png',
				'title' => __('Get FREE Support', 'mythemeshop'),
				'desc' => '<p>Many thanks for trying a MyThemeShop theme and having a look around. We hope you liked what you\'ve seen so far. We pour everything we\'ve got into making it the best WordPress theme available on WordPress.org, and nothing makes us happier than seeing other people love it.</p>
					<p><strong>1</strong> Please signup for <b>FREE</b> using the link below:<br>
					<a href="https://mythemeshop.com/go/signup/free" target="_blank">https://mythemeshop.com/go/signup/free</a> (No Credit Card Required)</p>
					<p><strong>2</strong> Once you are registered, please feel free to open a new thread in our community forums and one of our Developers would be more than happy to help you:<br>
					<a href="http://community.mythemeshop.com/" target="_blank">http://community.mythemeshop.com/</a></p>
					<p>We also offer Premium WordPress themes with many premium features and optimizations, you could check them all by following this link: <a href="https://mythemeshop.com/themes/" target="_blank">https://mythemeshop.com/themes/</a></p>
					<p>Thanks again for trying us out.',
				);
$sections[] = array(
				'icon' => NHP_OPTIONS_URL.'img/glyphicons/generalsetting.png',
				'title' => __('General Settings', 'mythemeshop'),
				'desc' => __('<p class="description">This tab contains common setting options which will be applied to whole theme.</p>', 'mythemeshop'),
				'fields' => array(
				
					array(
						'id' => 'mts_logo',
						'type' => 'upload',
						'title' => __('Logo Image', 'mythemeshop'), 
						'sub_desc' => __('Upload your logo <strong>(Recommended size 152x60px)</strong> using the Upload Button or insert image URL.', 'mythemeshop'),
						),
					array(
						'id' => 'mts_footer_logo',
						'type' => 'upload',
						'title' => __('Footer Logo Image', 'mythemeshop'), 
						'sub_desc' => __('Upload your footer logo <strong>(Recommended size 152x43px)</strong> using the Upload Button or insert image URL.', 'mythemeshop'),
						),
					array(
						'id' => 'mts_favicon',
						'type' => 'upload',
						'title' => __('Favicon', 'mythemeshop'), 
						'sub_desc' => __('Upload a <strong>16 x 16 px</strong> image that will represent your website\'s favicon. You can refer to this link for more information on how to make it: <a href="http://www.favicon.cc/" target="blank" rel="nofollow">http://www.favicon.cc/</a>', 'mythemeshop'),
						),
					array(
						'id' => 'mts_header_code',
						'type' => 'textarea',
						'title' => __('Header Code', 'mythemeshop'), 
						'sub_desc' => __('Enter the code which you need to place <strong>before closing </head> tag</strong>. (ex: Google Webmaster Tools verification, Bing Webmaster Center, BuySellAds Script, Alexa verification etc.)', 'mythemeshop')
						),
					array(
						'id' => 'mts_copyrights',
						'type' => 'textarea',
						'title' => __('Copyrights Text', 'mythemeshop'), 
						'sub_desc' => __('You can change or remove MyThemeShop link from footer and use your own custom text. <strong>Tip:</strong> You can use our affiliate link and earn 60% commission on every sale.', 'mythemeshop'),
						'std' => 'Theme by <a href="http://mythemeshop.com/">MyThemeShop</a>.'
						),
					array(
						'id' => 'mts_trending_articles',
						'type' => 'checkbox_hide_below',
						'title' => __('Trending', 'nhp-opts'), 
						'sub_desc' => __('<strong>Enable or Disable</strong> a top trending section by using this check box. This slider will show 4 recent articles from the selected category.', 'nhp-opts'),
						'std' => '1'
						),
						array(
						'id' => 'mts_trending_articles_cat',
						'type' => 'cats_select',
						'title' => __('Trending Category', 'nhp-opts'), 
						'sub_desc' => __('Select a category from the drop-down menu, latest four articles from this category will be shown in trending section.', 'nhp-opts'),
						'args' => array('number' => '100'),
						'std' => '0'
						),
					array(
						'id' => 'mts_featured_slider',
						'type' => 'checkbox_hide_below',
						'title' => __('Homepage Featured Section', 'mythemeshop'), 
						'sub_desc' => __('<strong>Enable or Disable</strong> a homepage featured section by using this check box. This section will show 4 recent articles from the selected category.', 'mythemeshop'),
						'std' => '1',
						),
						array(
						'id' => 'mts_featured_slider_cat',
						'type' => 'cats_multi_select',
						'title' => __('Featured Category', 'mythemeshop'), 
						'sub_desc' => __('Select a category from the menu, latest 4 articles from this category will be shown <strong>in featured section</strong>. Use ctrl key to select more than one category.', 'mythemeshop'),
						'args' => array('number' => '100'),
						'std' => '0'
						),
					array(
						'id' => 'mts_featured_carousel',
						'type' => 'checkbox_hide_below',
						'title' => __('Footer Featured', 'mythemeshop'), 
						'sub_desc' => __('<strong>Enable or Disable</strong> a Footer Featured section by using this check box. This section will show 6 recent articles from the selected category.', 'mythemeshop'),
						'std' => '1',
						),
						array(
						'id' => 'mts_featured_carousel_cat',
						'type' => 'cats_select',
						'title' => __('Carousel Category', 'mythemeshop'), 
						'sub_desc' => __('Select a category from the menu, latest 6 articles from this category will be shown <strong>in footer featured section</strong>.', 'mythemeshop'),
						'args' => array('number' => '100'),
						'std' => '0'
						),
					array(
						'id' => 'mts_pagenavigation',
						'type' => 'checkbox',
						'title' => __('Pagination', 'mythemeshop'),
						'sub_desc' => __('Enable or disable paginated navigation, which replaces the <strong>"Older Posts"</strong> and <strong>"Newer Posts"</strong> links with helpful numbered page links.', 'mythemeshop'),
						'std' => '1'
						),
					array(
						'id' => 'mts_rtl',
						'type' => 'checkbox',
						'title' => __('Right To Left Language Support', 'mythemeshop'), 
						'options' => array('0' => 'Off','1' => 'On'),
						'sub_desc' => __('Enable this option for right-to-left sites.', 'mythemeshop'),
						'std' => '0'
						),			
					)
				);
$sections[] = array(
				'icon' => NHP_OPTIONS_URL.'img/glyphicons/stylesetting.png',
				'title' => __('Styling Options', 'mythemeshop'),
				'desc' => __('<p class="description">Control the visual appearance of your theme, such as colors, layout and patterns, from here.</p>', 'mythemeshop'),
				'fields' => array(
					array(
						'id' => 'mts_color_scheme',
						'type' => 'color',
						'title' => __('Primary Color Scheme', 'mythemeshop'), 
						'sub_desc' => __('Theme comes with unlimited color schemes for your theme\'s styling.', 'mythemeshop'),
						'std' => '#38b7ee'
						),
					array(
						'id' => 'mts_layout',
						'type' => 'radio_img',
						'title' => __('Layout', 'mythemeshop'), 
						'sub_desc' => __('Choose from <strong>2 different homepage layouts</strong> for your site.', 'mythemeshop'),
						'options' => array(
										'cslayout' => array('img' => NHP_OPTIONS_URL.'img/layouts/cs.png'),
										'sclayout' => array('img' => NHP_OPTIONS_URL.'img/layouts/sc.png')
											),
						'std' => 'cslayout'
						),
                    array(
                        'id' => 'mts_full_posts',
                        'type' => 'button_set',
                        'title' => __('Posts on blog pages', 'mythemeshop'), 
						'options' => array('0' => 'Excerpts','1' => 'Full posts'),
						'sub_desc' => __('Show post excerpts or full posts on the homepage and other archive pages.', 'mythemeshop'),
						'std' => '0',
                        'class' => 'green'
                        ),
					array(
						'id' => 'mts_bg_color',
						'type' => 'color',
						'title' => __('Background Color', 'mythemeshop'), 
						'sub_desc' => __('Pick any color using the <strong>color picker</strong>, or enter a hex color value in the input field to make it the site background color for your theme.', 'mythemeshop'),
						'std' => '#e7e5e6'
						),
					array(
						'id' => 'mts_bg_pattern_upload',
						'type' => 'upload',
						'title' => __('Custom Background Image', 'mythemeshop'), 
						'sub_desc' => __('Upload your own custom background image or pattern.', 'mythemeshop')
						),
					array(
						'id' => 'mts_custom_css',
						'type' => 'textarea',
						'title' => __('Custom CSS', 'mythemeshop'), 
						'sub_desc' => __('You can enter your own custom CSS here to further customize your theme. This will override the default CSS used on your site.', 'mythemeshop')
						),
					array(
						'id' => 'mts_responsive',
						'type' => 'button_set',
						'title' => __('Responsiveness', 'mythemeshop'),
						'options' => array('0' => 'Off','1' => 'On'),
						'sub_desc' => __('MyThemeShop themes are responsive, which means they adapt to tablet and mobile devices, ensuring that your content is always displayed beautifully no matter what device visitors are using. Enable or disable responsiveness using this option.', 'mythemeshop'),
						'std' => '1'
						),																	
					)
				);
$sections[] = array(
				'icon' => NHP_OPTIONS_URL.'img/glyphicons/singlepost.png',
				'title' => __('Single Posts', 'mythemeshop'),
				'desc' => __('<p class="description">From here, you can control the appearance and functionality of your single posts page.</p>', 'mythemeshop'),
				'fields' => array(
					array(
						'id' => 'mts_tags',
						'type' => 'button_set',
						'title' => __('Tag Links', 'mythemeshop'), 
						'options' => array('0' => 'Off','1' => 'On'),
						'sub_desc' => __('Use this button if you want to show a tag cloud below the related posts.', 'mythemeshop'),
						'std' => '0'
						),
					array(
						'id' => 'mts_related_posts',
						'type' => 'button_set',
						'title' => __('Related Posts', 'mythemeshop'), 
						'options' => array('0' => 'Off','1' => 'On'),
						'sub_desc' => __('Use this button to show related posts with thumbnails below the content area in a post.', 'mythemeshop'),
						'std' => '1'
						),
					array(
						'id' => 'mts_author_box',
						'type' => 'button_set',
						'title' => __('Author Box', 'mythemeshop'), 
						'options' => array('0' => 'Off','1' => 'On'),
						'sub_desc' => __('Use this button if you want to display author information below the article.', 'mythemeshop'),
						'std' => '1'
						)
					)
				);
$sections[] = array(
				'icon' => NHP_OPTIONS_URL.'img/glyphicons/adsetting.png',
				'title' => __('Ad Management', 'mythemeshop'),
				'desc' => __('<p class="description">Now, ad management is easy with our options panel. You can control everything from here, without using separate plugins.</p>', 'mythemeshop'),
				'fields' => array(	
					array(
						'id' => 'mts_header_adcode',
						'type' => 'textarea',
						'title' => __('Header Banner', 'mythemeshop'), 
						'sub_desc' => __('Paste your Adsense, BSA or other ad code here to show ads below header on single posts.', 'mythemeshop'),
					),
					array(
						'id' => 'mts_posttopleft_adcode',
						'type' => 'textarea',
						'title' => __('Below Header Right Side', 'mythemeshop'), 
						'sub_desc' => __('Show call to action button to the right side of header Ad. (This button will be shown only if you are showing Header Ad.)', 'mythemeshop'),
						'std' => '<a href="button link" class="header-button">Button Text!</a> Submit  original article and get paid. <a href="#">Find out</a> More'
							),
					array(
						'id' => 'mts_posttop_adcode',
						'type' => 'textarea',
						'title' => __('Below Post Title', 'mythemeshop'), 
						'sub_desc' => __('Paste your Adsense, BSA or other ad code here to show ads below your article title on single posts.', 'mythemeshop')
						),
					array(
						'id' => 'mts_posttop_adcode_time',
						'type' => 'text',
						'title' => __('Show After X Days', 'mythemeshop'), 
						'sub_desc' => __('Enter the number of days after which you want to show the Below Post Title Ad before it expires. Enter 0 to disable this feature.', 'mythemeshop'),
						'validate' => 'numeric',
						'std' => '0',
						'class' => 'small-text'
						),
					array(
						'id' => 'mts_postend_adcode',
						'type' => 'textarea',
						'title' => __('Below Post Content', 'mythemeshop'), 
						'sub_desc' => __('Paste your Adsense, BSA or other ad code here to show ads below the post content on single posts.', 'mythemeshop')
						),
					array(
						'id' => 'mts_postend_adcode_time',
						'type' => 'text',
						'title' => __('Show After X Days', 'mythemeshop'), 
						'sub_desc' => __('Enter the number of days after which you want to show the Below Post Title Ad before it expires. Enter 0 to disable this feature.', 'mythemeshop'),
						'validate' => 'numeric',
						'std' => '0',
						'class' => 'small-text'
						),					
					)
				);
$sections[] = array(
				'icon' => NHP_OPTIONS_URL.'img/glyphicons/navsetting.png',
				'title' => __('Navigation', 'mythemeshop'),
				'desc' => __('<p class="description"><div class="controls"><b>Navigation settings can now be modified from the <a href="nav-menus.php">Menus Section</a>.</b><br></div></p>', 'mythemeshop')
				);
				
				
	$tabs = array();

	global $NHP_Options;
	$NHP_Options = new NHP_Options($sections, $args, $tabs);

}//function
add_action('init', 'setup_framework_options', 0);

/*
 * 
 * Custom function for the callback referenced above
 *
 */
function my_custom_field($field, $value){
	print_r($field);
	print_r($value);

}//function

/*
 * 
 * Custom function for the callback validation referenced above
 *
 */
function validate_callback_function($field, $value, $existing_value){
	
	$error = false;
	$value =  'just testing';
	/*
	do your validation
	
	if(something){
		$value = $value;
	}elseif(somthing else){
		$error = true;
		$value = $existing_value;
		$field['msg'] = 'your custom error message';
	}
	*/
	
	$return['value'] = $value;
	if($error == true){
		$return['error'] = $field;
	}
	return $return;
	
}//function

?>