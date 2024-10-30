<?php
/*
Plugin Name: Hopos Slider Lite
Plugin URI: http://srvalle.com/plugins/hopos/
Description: Hopos Slider Lite - Responsive Wordpress Plugin is highly customizable and flexible. Can be used any post type and any taxonomy. In each post can be configured options such as video, large image, audio, link, lightbox or target page.
Version: 1.0
Author: Sergio Valle
Author URI: http://srvalle.com/
License: GPLv2 or later
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
*/

// Call function when plugin is activated
register_activation_hook(__FILE__,'hopos_lite_install'); 

// Action hook to register option settings
add_action( 'admin_init', 'hopos_lite_register_settings' );

// Action hook to add the menu item
add_action('admin_menu', 'hopos_lite_menu');

// Action hook to initialize the plugin
add_action('admin_init', 'hopos_lite_add_meta');

// Action hook to save the meta box data when the post is saved
add_action('save_post','hopos_lite_save_meta_box');

// Action hook to create hopos shortcode
add_shortcode('hopos', 'hopos_lite_shortcode');


// Action hook to create plugin widget
add_action( 'widgets_init', 'hopos_lite_register_widgets' );

//register widget
function hopos_lite_register_widgets() {
	// Using Shortcodes inside Text Widgets
	add_filter('widget_text', 'do_shortcode');
}



// ---------------------------------------
// admin ajax
// ---------------------------------------

function hopos_lite_admin() {
	include_once( "php/admin/admin.php" );
}
add_action('admin_init', 'hopos_lite_admin');


// ---------------------------------------
// install
// ---------------------------------------

function hopos_lite_install() {	
	include_once( "php/admin/install.php" );
}

// ---------------------------------------
// general settings
// ---------------------------------------

function hopos_lite_general_settings() {
	include_once( "php/admin/general_settings.php" );
}

// ---------------------------------------
// css custom
// ---------------------------------------

function hopos_lite_css_custom() {
	include_once( "php/admin/custom_css.php" );
}

// ---------------------------------------
// hopos shortcode settings
// ---------------------------------------

function hopos_lite_settings_shortcode() {
	include_once( "php/admin/shortcode_settings.php" );
}


// --------------------------------------------------
// create sub-menu > shortcode and general settings
// --------------------------------------------------

function hopos_lite_menu() {
	//MENU
	$icon_url = plugin_dir_url(__FILE__) . "img/hopos_icon.png";
	add_menu_page( 'Hopos Slider Plugin', 'Hopos Lite', 'administrator', 'hopos-lite-slider-plugin', 'hopos_lite_settings_shortcode', $icon_url, '53.4' );
	
	//SUBMENU settings
	add_submenu_page( 'hopos-lite-slider-plugin', 'Hopos Slider Settings', 'Settings', 'administrator', 'hopos-lite-posts-settings', 'hopos_lite_general_settings' );
	
	//SUBMENU custom css
	add_submenu_page( 'hopos-lite-slider-plugin', 'Hopos Slider Custom CSS', 'Custom CSS', 'administrator', 'hopos-lite-css-custom', 'hopos_lite_css_custom' );
}


// ---------------------------------------
// create post Hopos meta box
// ---------------------------------------

function hopos_lite_add_meta() {
	include_once( "php/admin/meta_box.php" );
}



// ---------------------------------------
// Hopos Shortcode
// ---------------------------------------

function hopos_lite_shortcode($atts, $content = null) {
	global $post;
	extract(shortcode_atts(array(
		'id' => 1
	), $atts));
	
	return do_shortcode( getHoposLite($id) );
}


function getHoposLite($id) {
	
	$id = intval($id);	
	global $wpdb;
	global $post;
	$hp_general = get_option('hp_general');
	$plugin_dir = plugin_dir_url(__FILE__);
	$output = "";
				
	$table_name = $wpdb->base_prefix . 'hopos_lite';
	$query = $wpdb->get_results( "SELECT * FROM $table_name WHERE id = $id", ARRAY_A );
	
	
	if (!$query) return "<p>Hopos with ID <strong>$id</strong> not found.</p>";

	foreach ( $query as $row ) {
		$hpName = $row['name'];
		$settings = $row['settings'];
		$custom_skin = $row['custom_skin'];
	}
	
	$set = explode("||", $settings);
	$arr_settings = array();
	
	foreach ( $set as $s ) {
		$s2 = explode("::", $s);
		$arr_settings[$s2[0]] = $s2[1];
	}
	
	// -----------------------------------------------------------
	// UPDATE start
	// -----------------------------------------------------------

	//resolution
	if (!isset($arr_settings['hpResolution960'])) $arr_settings['hpResolution960'] = 4;
	if (!isset($arr_settings['hpResolution768'])) $arr_settings['hpResolution768'] = 2;
	if (!isset($arr_settings['hpResolution480'])) $arr_settings['hpResolution480'] = 1;
	
	//custom skins
	if (!isset($arr_settings['hpCustomThisSkin'])) $arr_settings['hpCustomThisSkin'] = 'false';
	
	//show title
	if (!isset($arr_settings['hpShowTitle'])) $arr_settings['hpShowTitle'] = 'true';
	
	// -----------------------------------------------------------
	// UPDATE end
	// -----------------------------------------------------------
	

	
	$percent_width = strpos($arr_settings['hpSlideWidth'], '%');
	if ($percent_width !== false) { $arr_settings['hpSlideWidth'] = "'" . $arr_settings['hpSlideWidth'] . "'"; }
	if ( !isset($arr_settings['hpSlideHeight']) ) { $arr_settings['hpSlideHeight'] = 'auto'; }
	
	$output .= "<div><script type='text/javascript'>
	/* <![CDATA[ */
		jQuery(window).load(function() {
			jQuery.hoposSlider('#hopos_slider_" . $id . "', {
				slideWidth: " . $arr_settings['hpSlideWidth'] . ",
				slideHeight: '" . $arr_settings['hpSlideHeight'] . "',
				postMargin: " . $arr_settings['hpPostMargin'] . ",
				showPreviousNext: " . $arr_settings['hpShowPreviousNext'] . ",
				currentSlide: " . 0 . ",
				scrollSpeed: " . $arr_settings['hpScrollSpeed'] . ",
				autoSlide: " . $arr_settings['hpAutoSlide'] . ",
				autoSlideDelay: " . $arr_settings['hpAutoSlideDelay'] . ",
				showPlayPause: " . $arr_settings['hpShowPlayPause'] . ",
				showPagination: " . $arr_settings['hpShowPagination'] . ",
				alignPagination: '" . $arr_settings['hpAlignPagination'] . "',
				swipeDrag: " . $arr_settings['hpSwipeDrag'] . ",
				sliderType: '" . 'image_news' . "',
				pageStyle: '" . $arr_settings['hpPageStyle'] . "',
				skin: '" . $arr_settings['hpSkin'] . "',
				resolution: { res960:" . $arr_settings['hpResolution960'] . ", res768:" . $arr_settings['hpResolution768'] . ", res480:" . $arr_settings['hpResolution480'] . " },
				onScrollEvent: " . 'function() {}' . ",
				ajaxEvent: " . 'function() {}' . "
			});
		});
	/* ]]> */
	</script></div>";
	
	
	//custom skin
	
	if ($arr_settings['hpSkin'] == 'custom' && $arr_settings['hpCustomThisSkin'] == 'true') {
		$custom_skin = str_replace("../../", $plugin_dir, $custom_skin);
		$custom_skin = str_replace("../", $plugin_dir, $custom_skin);
		$custom_skin = str_replace("custom", "custom_$id", $custom_skin);
		$arr_settings['hpSkin'] = str_replace($arr_settings['hpSkin'], $arr_settings['hpSkin'] . '_' . $id, $arr_settings['hpSkin']);
		
		//on head
		$output .= "<div><script type='text/javascript'>";
		$custom_skin = str_replace("\n", "", $custom_skin);
		$custom_skin = str_replace("  ", "", $custom_skin);
		$output .= "jQuery(window).load(function() {";
		$output .= "jQuery('head').append('<style>$custom_skin</style>')";
		$output .= "});";
		$output .= "</script></div>";
	}

	
	//echo $arr_settings['hpCustomThisSkin'];
	
	///////////////////////////////// 
	if ($arr_settings['hpCustomThisSkin'] == 'true' && $arr_settings['hpSkin'] != 'custom') {
		$output .= "<div><script type='text/javascript'>";
		$custom_skin = str_replace("\n", "", $custom_skin);
		$custom_skin = str_replace("  ", "", $custom_skin);
		$output .= "jQuery(window).load(function(){";
		$output .= "jQuery('head').append('<style>$custom_skin</style>')";
		$output .= "});";
		$output .= "</script></div>";
	}
	/////////////////////////////////
	
	
	
	if ($arr_settings['hpPostsPerSlide'] < 1) {
		$arr_settings['hpPostsPerSlide'] = 1;
	}

	$hpTitleHeader = stripslashes($arr_settings['hpTitleHeader']);
	$output .= "
	<div class='hp_container skinner' id='hopos_slider_$id'>
		<div class='hp_header skinner'>$hpTitleHeader</div>
		<div class='hp_clear'></div>
		<div class='hp_slider'>
			<div class='hp_items'>";
?>
<?php 
				//load timthumb
				include_once( "php/hp_tt_resize.php" );

				//$arr_settings['hpAmountPosts'] = intval($arr_settings['hpAmountPosts']);
				if ($arr_settings['hpAmountPosts'] < 1) $arr_settings['hpAmountPosts'] = 1;
				
				$hp_wp_query = new WP_Query();
				
				/////////////////////////////////
				$hpTaxonomy = explode(',', $arr_settings['hpTaxonomy']);
				$hpCategories = explode(',', $arr_settings['hpCategories']);
				
				//echo $hpCategories[0];
									
				//echo 'taxonomies: ' . $arr_settings['hpTaxonomy'];
				//echo '<br>';
				//echo 'categories: ' . $arr_settings['hpCategories'];
				//echo '<br>';										

				// hopos - custom post type
								
				if (count($hpTaxonomy) == 1) {
					$hp_wp_query->query( array(
						'post_type' => $arr_settings['hpPostType'], 
						'tax_query' => array(
							//'relation' => 'OR',
							array('taxonomy' => $hpTaxonomy[0], 'field' => 'slug', 'terms' => $hpCategories)
						),
						'post_status' => 'publish', 'posts_per_page' => -1, 'nopaging' => true, 'orderby' => $arr_settings['hpOrderby'], 'order' => $arr_settings['hpOrder']) 
					);
				}
				if (count($hpTaxonomy) == 2) {
					$hp_wp_query->query( array(
						'post_type' => $arr_settings['hpPostType'], 
						'tax_query' => array(
							'relation' => 'OR',
							array('taxonomy' => $hpTaxonomy[0], 'field' => 'slug', 'terms' => $hpCategories),
							array('taxonomy' => $hpTaxonomy[1], 'field' => 'slug', 'terms' => $hpCategories)
						),
						'post_status' => 'publish', 'posts_per_page' => -1, 'nopaging' => true, 'orderby' => $arr_settings['hpOrderby'], 'order' => $arr_settings['hpOrder']) 
					);
				}
				if (count($hpTaxonomy) == 3) {
					$hp_wp_query->query( array(
						'post_type' => $arr_settings['hpPostType'], 
						'tax_query' => array(
							'relation' => 'OR',
							array('taxonomy' => $hpTaxonomy[0], 'field' => 'slug', 'terms' => $hpCategories),
							array('taxonomy' => $hpTaxonomy[1], 'field' => 'slug', 'terms' => $hpCategories),
							array('taxonomy' => $hpTaxonomy[2], 'field' => 'slug', 'terms' => $hpCategories)
						),
						'post_status' => 'publish', 'posts_per_page' => -1, 'nopaging' => true, 'orderby' => $arr_settings['hpOrderby'], 'order' => $arr_settings['hpOrder']) 
					);
				}
				if (count($hpTaxonomy) == 4) {
					$hp_wp_query->query( array(
						'post_type' => $arr_settings['hpPostType'], 
						'tax_query' => array(
							'relation' => 'OR',
							array('taxonomy' => $hpTaxonomy[0], 'field' => 'slug', 'terms' => $hpCategories),
							array('taxonomy' => $hpTaxonomy[1], 'field' => 'slug', 'terms' => $hpCategories),
							array('taxonomy' => $hpTaxonomy[2], 'field' => 'slug', 'terms' => $hpCategories),
							array('taxonomy' => $hpTaxonomy[3], 'field' => 'slug', 'terms' => $hpCategories)
						),
						'post_status' => 'publish', 'posts_per_page' => -1, 'nopaging' => true, 'orderby' => $arr_settings['hpOrderby'], 'order' => $arr_settings['hpOrder']) 
					);
				}
				if (count($hpTaxonomy) == 5) {
					$hp_wp_query->query( array(
						'post_type' => $arr_settings['hpPostType'], 
						'tax_query' => array(
							'relation' => 'OR',
							array('taxonomy' => $hpTaxonomy[0], 'field' => 'slug', 'terms' => $hpCategories),
							array('taxonomy' => $hpTaxonomy[1], 'field' => 'slug', 'terms' => $hpCategories),
							array('taxonomy' => $hpTaxonomy[2], 'field' => 'slug', 'terms' => $hpCategories),
							array('taxonomy' => $hpTaxonomy[3], 'field' => 'slug', 'terms' => $hpCategories),
							array('taxonomy' => $hpTaxonomy[3], 'field' => 'slug', 'terms' => $hpCategories)
						),
						'post_status' => 'publish', 'posts_per_page' => -1, 'nopaging' => true, 'orderby' => $arr_settings['hpOrderby'], 'order' => $arr_settings['hpOrder']) 
					);
				}
				
					
				// 1 taxonomy olny / old version
				//$hp_wp_query->query( array('post_type' => $arr_settings['hpPostType'], $arr_settings['hpTaxonomy'] => $arr_settings['hpCategories'],
				//'post_status' => 'publish', 'posts_per_page' => -1, 'nopaging' => true, 'orderby' => $arr_settings['hpOrderby'], 'order' => $arr_settings['hpOrder']) );
				
			
				$totalPosts = $hp_wp_query->found_posts;
				
				$j = 1; $k = 1;
				$has_social = false;
				
				if ( $hp_wp_query->have_posts() ) : while ( $hp_wp_query->have_posts() ) : $hp_wp_query->the_post();
					
					if ($j == 1) {
						$addSlide = true;
					} elseif ($j > $arr_settings['hpPostsPerSlide']) {
						$addSlide = true; 
						$j = 1; 
					} else { 
						$addSlide = false; 
					}					
			
					
					$hp_product_thumb_url = trim(get_post_meta($post->ID,'_hp_product_thumb_url',true));
					$hp_open_content = get_post_meta($post->ID,'_hp_open_content',true);
					$hp_product_new_window = get_post_meta($post->ID,'_hp_product_new_window',true);
					$hp_custom_link_url = get_post_meta($post->ID,'_hp_custom_link_url',true);
					$hp_large_image_url = trim(get_post_meta($post->ID,'_hp_large_image_url',true));
					$hp_video_url = trim(get_post_meta($post->ID,'_hp_video_url',true));
					$hp_video_type = get_post_meta($post->ID,'_hp_video_type',true);
					$hp_video_width = get_post_meta($post->ID,'_hp_video_width',true);
					$hp_video_height = get_post_meta($post->ID,'_hp_video_height',true);
					$hp_video_size = $hp_video_width . 'x' . $hp_video_height;
					$hp_audio_url = trim(get_post_meta($post->ID,'_hp_audio_url',true));
					
					$hp_extra_info = trim(html_entity_decode(get_post_meta($post->ID,'_hp_extra_info',true)));
					
					//social media
					$hp_social_twitter = trim(get_post_meta($post->ID,'_hp_social_twitter',true));
					$hp_social_facebook = trim(get_post_meta($post->ID,'_hp_social_facebook',true));
					$hp_social_pinterest = trim(get_post_meta($post->ID,'_hp_social_pinterest',true));
					$hp_social_google = trim(get_post_meta($post->ID,'_hp_social_google',true));
					$hp_social_linkedin = trim(get_post_meta($post->ID,'_hp_social_linkedin',true));
					$hp_social_flickr = trim(get_post_meta($post->ID,'_hp_social_flickr',true));
					$hp_social_behance = trim(get_post_meta($post->ID,'_hp_social_behance',true));
					
					$hp_large_image_text_icon = trim(get_post_meta($post->ID,'_hp_large_image_text_icon',true));
					$hp_video_text_icon = trim(get_post_meta($post->ID,'_hp_video_text_icon',true));
					$hp_audio_text_icon = trim(get_post_meta($post->ID,'_hp_audio_text_icon',true));
					
					$hp_lightbox_size = NULL;
					if ($hp_open_content == 'open_lightbox') {
						$hp_lightbox_width = trim(get_post_meta($post->ID,'_hp_lightbox_width',true));
						$hp_lightbox_height = trim(get_post_meta($post->ID,'_hp_lightbox_height',true));
						$hp_lightbox_size = $hp_lightbox_width . 'x' . $hp_lightbox_height;
					} elseif ($hp_open_content == 'custom_link') {
						$theLink = ($hp_custom_link_url == '') ? get_permalink($post->ID) : $hp_custom_link_url;
						$window_target = ($hp_product_new_window == 'yes') ? '_blank': '_self';
					} else {
						$theLink = get_permalink($post->ID);
						$window_target = ($hp_product_new_window == 'yes') ? '_blank': '_self';
					}

					
					$the_id = get_the_ID();
					$the_title = get_the_title();
					
					//custom excerpt
					if ($arr_settings['hpAddExcerpt'] == 'true') { $the_excerpt = hopos_lite_excerpt($arr_settings['hpExcerptNumWords']); }
					
					//enable shortcode
					$the_content = apply_filters('the_content', get_the_content());
					
					if ($hp_product_thumb_url != '') {
						$img_url = $hp_product_thumb_url;
						$img_w = $arr_settings['hpThumbWidth'];
						$img_h = $arr_settings['hpThumbHeight'];
						$img_thumb = true;

					} elseif (has_post_thumbnail($the_id)) { //$post->ID
						//timthumb
						$thumb = wp_get_attachment_image_src(get_post_thumbnail_id($the_id), 'full'); //$post->ID
						$thumb = $thumb[0];
						$tt_image = hp_tt_function($arr_settings['hpThumbHeight'], $arr_settings['hpThumbWidth'], $thumb);
						
						$img_url = $tt_image;
						$img_w = $arr_settings['hpThumbWidth'];
						$img_h = $arr_settings['hpThumbHeight'];
						$img_thumb = true;

					} else {
						$img_thumb = false;	
					}
					
					
					// write post
					if ($addSlide) {
						$output .= "<div class='hp_slide'>";
					}
						$output .= "<div class='hp_item skinner' id='slider" . $id . "_item_" . $k . "'>";
							
							if ($img_thumb) { 
								//$output .= "<div class='hp_thumb skinner'><img src='$img_url' alt='' width='$arr_settings[hpThumbWidth]' height='$arr_settings[hpThumbHeight]' />";
								$output .= "<div class='hp_thumb skinner'><img class='hp_main_image skinner' src='$img_url' alt='' width='$img_w' height='$img_h'></div>";
								$output .="<div class='hp_clear'></div>";
							}
							
							if ($arr_settings['hpShowTitle'] == 'true') {
								if (isset($hp_lightbox_size)) {
									$output .= "<div class='hp_title skinner'><a href='#' class='skinner hp_lightbox' data-id='$the_id' data-post-type='$arr_settings[hpPostType]' data-type='html-content' data-size='$hp_lightbox_size' title='$the_title'>$the_title</a></div>";
								} else {
									$output .= "<div class='hp_title skinner'><a href='$theLink' target='$window_target' class='skinner' data-id='$the_id' title='$the_title'>$the_title</a></div>";
								}
							}
							
							if ($arr_settings['hpAddExcerpt'] == 'true' && $the_excerpt != '') $output .= "<div class='hp_excerpt skinner'>$the_excerpt</div>";
							
							$output .="<div class='hp_clear'></div>";
							
							if ($hp_extra_info != '') {
								$output .= "<div class='hp_extra_info skinner'>" . $hp_extra_info . "</div>";
								$output .="<div class='hp_clear'></div>";
							}
							
							$output .= "<div class='hp_bar skinner'>";
								$output .= "<div class='hp_icons skinner'>";
									if ($hp_large_image_url != '') $output .= "<a href='$hp_large_image_url' title='$hp_large_image_text_icon' class='mmphoto skinner hp_lightbox' data-type='image' data-size='540x303'></a>";
									if ($hp_video_url != '') $output .= "<a href='$hp_video_url' title='$hp_video_text_icon' class='mmvideo skinner hp_lightbox' data-type='$hp_video_type' data-size='$hp_video_size'></a>";
									if ($hp_audio_url != '') $output .= "<a href='$hp_audio_url' title='$hp_audio_text_icon' class='mmaudio skinner hp_lightbox' data-type='audio' data-size='450x50'></a>";
									
									if (isset($hp_lightbox_size)) {
										$output .= "<a href='#' class='mmmore skinner hp_lightbox' data-id='$the_id' data-post-type='$arr_settings[hpPostType]' data-type='html-content' data-size='$hp_lightbox_size' title='$the_title'></a>";
									} else {
										$output .= "<a href='$theLink' target='$window_target' class='mmmore skinner' data-id='$the_id' title='$the_title'></a>";
									}
									
									//share icon
									if ($hp_social_twitter != '' || $hp_social_facebook != '' || $hp_social_pinterest != '' || $hp_social_google != '' || $hp_social_linkedin != '' || $hp_social_flickr != '' || $hp_social_behance != '') {
										$output .= "<a href='#' id='hpshare_$k' class='hp_share skinner' title='$the_title'></a>";
										$has_social = true;
									}
								$output .= "</div>";
							$output .= "</div>";//hp_bar
							
							if ($has_social) {
								$output .= "<div id='hptooltip_$k' class='hp_tooltip skinner'>";
									if ($hp_social_twitter != '') $output .= "<a href='$hp_social_twitter' target='_blank' title='Twitter'><img class='smtwitter' src='" . $plugin_dir . "img/social/twitter-16x16.png'></a>";
									if ($hp_social_facebook != '') $output .= "<a href='$hp_social_facebook' target='_blank' title='Facebook'><img class='smfacebook' src='" . $plugin_dir . "img/social/facebook-16x16.png'></a>";
									if ($hp_social_pinterest != '') $output .= "<a href='$hp_social_pinterest' target='_blank' title='Pinterest'><img class='smpinterest' src='" . $plugin_dir . "img/social/pinterest-16x16.png'></a>";
									if ($hp_social_google != '') $output .= "<a href='$hp_social_google' target='_blank' title='Google+'><img class='smgoogle' src='" . $plugin_dir . "img/social/google+-16x16.png'></a>";
									if ($hp_social_linkedin != '') $output .= "<a href='$hp_social_linkedin' target='_blank' title='Linkedin'><img class='smlinkedin' src='" . $plugin_dir . "img/social/linkedin-16x16.png'></a>";
									if ($hp_social_flickr != '') $output .= "<a href='$hp_social_flickr' target='_blank' title='Flickr'><img class='smflickr' src='" . $plugin_dir . "img/social/flickr-16x16.png'></a>";
									if ($hp_social_behance != '') $output .= "<a href='$hp_social_behance' target='_blank' title='Behance'><img class='smbehance' src='" . $plugin_dir . "img/social/behance-16x16.png'></a>";
								$output .= "</div>";
							}
						
						$output .= "</div>";//hp_item
					
					if ($j == $arr_settings['hpPostsPerSlide'] || $k == $totalPosts || $k == $arr_settings['hpAmountPosts']) {
						$output .= "</div>";//hp_slide
					}
					
					if ($k == $arr_settings['hpAmountPosts']) break;
					
					$j++; $k++;
					
				endwhile; else:
					$output .= "<div style='padding:10px;'>post not found.</div>";
				endif;
				wp_reset_postdata();
?>
<?php
			$output .= "	
			</div><!-- hp_items -->
		</div><!-- hp_slider -->";
		
		if ($arr_settings['hpAddShadow'] == 'true') {
			$output .= "<div class='hp_shadow skinner'><img src='$plugin_dir" . "img/shadow_01.png' width='100%' height='22' /></div>";
		}
		
		if ($arr_settings['hpShowPlayPause'] == 'true' || $arr_settings['hpShowPagination'] == 'true') {
			$output .="<div class='hp_clear'></div>";
			$output .= "<div class='hp_paginate_container skinner'>
				<div class='hp_paginate skinner'>
					<div class='hp_page'></div>
					<div class='hp_control'>
						<a href='#' class='hp_btn hp_play'><img src='" . $plugin_dir . "img/play.png' alt=''></a><a href='#' class='hp_btn hp_pause'><img src='" . $plugin_dir . "img/pause.png' alt=''></a>
					</div>
				</div>
			</div>";
		}//if

	$output .= "
	</div><!-- hp_container -->
	";

	return $output;
	
}//getHoposLite


function hopos_lite_register_settings() {
	//register Array of settings
	register_setting( 'hopos-lite-settings-group', 'hp_options' ); //shortcodes create
	register_setting( 'hopos-lite-general-settings', 'hp_general' ); //general settings
}



// ---------------------------------------------------
// add style/script
// ---------------------------------------------------

function hopos_lite_add_script_style() {
	$plugin_dir = plugin_dir_url(__FILE__);
	//console.log($plugin_dir);
	global $wp_version;
	$hp_general = get_option('hp_general');
	$hp_open_hopos_lite_script = $hp_general['hp_open_hopos_script'];
	
	wp_enqueue_style("hopos-lite-style", $plugin_dir . "css/hopos.css", false, "1.0", "all");
	
	//wp_enqueue_style( 'wp-mediaelement' ); //wp 3.6 or major
	wp_enqueue_style("mediaelementplayer", $plugin_dir . "mep/player/mediaelementplayer.css", false, "1.0", "all");
	

	if ($hp_open_hopos_lite_script == 'open_in_header') {
		//head
		if ( $wp_version >= 3.6 ) {
			wp_enqueue_script( 'wp-mediaelement' );
		} else {
			wp_enqueue_script("mediaelement_and_player", $plugin_dir . "mep/player/mediaelement-and-player.min.js", array('jquery'), "1.0", false);
		}

		wp_enqueue_script("hopos_script", $plugin_dir . "js/jquery.hoposslider.min.js", array('jquery'), "1.0", false);
	} else {
		//footer
		if ( $wp_version >= 3.6 ) {
			wp_enqueue_script( 'wp-mediaelement' );
		} else {
			wp_enqueue_script("mediaelement_and_player", $plugin_dir . "mep/player/mediaelement-and-player.min.js", array('jquery'), "1.0", true);
		}
		
		wp_enqueue_script("hopos_script", $plugin_dir . "js/jquery.hoposslider.min.js", array('jquery'), "1.0", true);
	}
	
	//ajax
	// Get current page protocol
    $protocol = isset( $_SERVER["HTTPS"]) ? 'https://' : 'http://';
    // Output admin-ajax.php URL with same protocol as current page
    $params = array( 'ajaxurl' => admin_url( 'admin-ajax.php', $protocol ) );
    wp_localize_script( 'hopos_script', 'hopos_script', $params );
	
	//send variable to javascript
	$plugin_dir_param = array('plugin_dir' => $plugin_dir);
	wp_localize_script( 'hopos_script', 'hopos_plugin_dir_param', $plugin_dir_param );
}
add_action('wp_enqueue_scripts', 'hopos_lite_add_script_style');


function hopos_lite_add_script_style_adm() {
	$plugin_dir = plugin_dir_url(__FILE__);
	wp_enqueue_style("hopos_lite_util", $plugin_dir . "css/hopos_lite_util.css", false, "1.0", "all");
	wp_enqueue_script("hopos_lite_util", $plugin_dir . "js/hopos_lite_util.js", array('jquery'), "1.0", false);
	
	//ajax
	// Get current page protocol
    $protocol = isset( $_SERVER["HTTPS"]) ? 'https://' : 'http://';
    // Output admin-ajax.php URL with same protocol as current page
    $params = array( 'ajaxurl' => admin_url( 'admin-ajax.php', $protocol ), 'hopos_plugin_dir' => plugin_dir_url(__FILE__) );
    wp_localize_script( 'hopos_lite_util', 'hopos_lite_util', $params );
}
add_action('admin_init', 'hopos_lite_add_script_style_adm');




// ---------------------------------------
// Ajax handler / lightbox content
// ---------------------------------------

add_action('wp_ajax_nopriv_hp_lite_query_ajax', 'hp_lite_query_ajax');
add_action('wp_ajax_hp_lite_query_ajax', 'hp_lite_query_ajax');
function hp_lite_query_ajax() {

    // setup Query
	query_posts('p=' . absint( $_REQUEST['post_id'] ) . '&post_type=' . $_REQUEST['post_type']);

    if ( have_posts() ) : while ( have_posts() ) : the_post();
        the_content();
    endwhile; else:
        echo "post not found.";
    endif;

    wp_reset_query(); 
	die();
}

// ---------------------------------------
// excerpt
// ---------------------------------------

function hopos_lite_excerpt($limit) {
	$excerpt = explode(' ', get_the_excerpt(), $limit);
	
	if (count($excerpt)>=$limit) {
		array_pop($excerpt);
		$excerpt = implode(" ",$excerpt).'...';
	} else {
		$excerpt = implode(" ",$excerpt);
	}
	
	$excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);
	return $excerpt;
}
?>