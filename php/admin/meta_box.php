<?php
// ---------------------------------------
// create Hopos meta box
// ---------------------------------------
function hopos_lite_add_meta_box() {
	//create custom meta box (default post) 
	add_meta_box('hp-lite-meta',__('Hopos Slider Lite - Options', 'hopos-plugin'), 'hopos_lite_meta_box', 'post', 'normal', 'high');
	
	//enable custom meta box (Hopos panel) in custom post types
	$hp_general = get_option('hp_general');
	
	$i=1;

	if (isset($hp_general['hpEnableCustomPostType'])) {
		foreach ($hp_general['hpEnableCustomPostType'] as $pt) {
			add_meta_box('hp-lite-meta' . $i, __('Hopos Slider Lite - Options', 'hopos-plugin'), 'hopos_lite_meta_box', $pt, 'normal', 'high');
		}
	}
}
hopos_lite_add_meta_box();


// ---------------------------------------
// build hopos meta box
// ---------------------------------------

function hopos_lite_meta_box($post,$box) {
	
	// retrieve custom meta box values
	$hp_product_thumb_url = get_post_meta($post->ID,'_hp_product_thumb_url',true);
	$hp_open_content = get_post_meta($post->ID,'_hp_open_content',true);
	$hp_product_new_window = get_post_meta($post->ID,'_hp_product_new_window',true);
	$hp_lightbox_width = get_post_meta($post->ID,'_hp_lightbox_width',true);
	$hp_lightbox_height = get_post_meta($post->ID,'_hp_lightbox_height',true);
	$hp_custom_link_url = get_post_meta($post->ID,'_hp_custom_link_url',true);
	$hp_large_image_url = get_post_meta($post->ID,'_hp_large_image_url',true);
	$hp_video_url = get_post_meta($post->ID,'_hp_video_url',true);
	$hp_video_type = get_post_meta($post->ID,'_hp_video_type',true);
	$hp_video_width = get_post_meta($post->ID,'_hp_video_width',true);
	$hp_video_height = get_post_meta($post->ID,'_hp_video_height',true);	
	$hp_audio_url = get_post_meta($post->ID,'_hp_audio_url',true);
	
	$hp_extra_info = get_post_meta($post->ID,'_hp_extra_info',true); 
	
	$hp_large_image_text_icon = get_post_meta($post->ID,'_hp_large_image_text_icon',true);
	$hp_video_text_icon = get_post_meta($post->ID,'_hp_video_text_icon',true);
	$hp_audio_text_icon = get_post_meta($post->ID,'_hp_audio_text_icon',true);
	
	$hp_social_twitter = get_post_meta($post->ID,'_hp_social_twitter',true);
	$hp_social_facebook = get_post_meta($post->ID,'_hp_social_facebook',true);
	$hp_social_pinterest = get_post_meta($post->ID,'_hp_social_pinterest',true);
	$hp_social_google = get_post_meta($post->ID,'_hp_social_google',true);
	$hp_social_linkedin = get_post_meta($post->ID,'_hp_social_linkedin',true);
	$hp_social_flickr = get_post_meta($post->ID,'_hp_social_flickr',true);
	$hp_social_behance = get_post_meta($post->ID,'_hp_social_behance',true);

	// display meta box form
	echo '<br><br>';
	echo '<table>';

	echo '<tr>';
	echo '<td>' .__('Thumbnail Image URL', 'hopos-plugin'). ':</td><td><input type="text" name="_hp_product_thumb_url" value="'.esc_attr($hp_product_thumb_url).'" size="60">&nbsp;<small>' . __('Optional', 'hopos-plugin') . '</small></td><td>&nbsp;<a href="#" class="hp_info_btn" id="btn1"></a><div class="hp_info">Paste here the url of the thumbnail. Or use "Featured Image" (leaving this field empty) and the image is automatically cropped to fit the width and height set in the shortcode settings for thumbnail.</div></td>';
	echo '</tr>';
	
	echo '<tr><td colspan="3"><hr noshade="noshade" color="#dddddd"></td></tr>';
	
	echo '<tr>';
	echo '<td>' . __('Open Content', 'hopos-plugin') . ':</td><td><select name="_hp_open_content" id="_hp_open_content" style="width:150px;">
			<option value="open_lightbox" ' . (is_null($hp_open_content) || $hp_open_content == "open_lightbox" ? 'selected="selected" ' : '').'>On the lightbox</option>
			<option value="open_page" ' . ($hp_open_content == "open_page" ? 'selected="selected" ' : '').'>On the page</option>
			<option value="custom_link" ' . ($hp_open_content == "custom_link" ? 'selected="selected" ' : '').'>Custom link</option>
		</select>&nbsp;<small>' . __('Required', 'hopos-plugin') . '</small></td><td>&nbsp;<a href="#" class="hp_info_btn" id="btn3"></a><div class="hp_info">On the lightbox => Opens this post content in lightbox.<br>On the page => Open this post content on the site.<br>Custom Link => Link to other content within or outside of this site.<br><br>Note: Image, Video and Audio defined below opens only in the lightbox.</div></td>';
	echo '</tr>';
		
	echo '<tr id="field_open_in_new_window">';
	echo '<td>' . __('Open in New Window', 'hopos-plugin') . ':</td><td><select name="_hp_product_new_window" id="_hp_product_new_window" style="width:150px;">
			<option value="yes" ' . ($hp_product_new_window == "yes" ? 'selected="selected" ' : '').'>Yes</option>
			<option value="no" ' . (is_null($hp_product_new_window) || $hp_product_new_window == "no" ? 'selected="selected" ' : '').'>No</option>
		</select></td><td>&nbsp;</td>';
	echo '</tr>';
	
	$hp_lightbox_width = (trim($hp_lightbox_width) == '') ? 560 : $hp_lightbox_width;
	$hp_lightbox_height = (trim($hp_lightbox_height) == '') ? 300 : $hp_lightbox_height;
	
	echo '<tr id="field_lightbox_size">';
	echo '<td>' . __('Lightbox Size', 'hopos-plugin') . ':</td><td>
			<input type="text" name="_hp_lightbox_width" id="_hp_lightbox_width" value="'.esc_attr($hp_lightbox_width).'" size="5">&nbsp;Width&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="text" name="_hp_lightbox_height" id="_hp_lightbox_height" value="'.esc_attr($hp_lightbox_height).'" size="5">&nbsp;Height
			</td><td>&nbsp;</td>';
	echo '</tr>';

	echo '<tr id="field_custom_link_url">';
	echo '<td>' .__('Custom Link URL', 'hopos-plugin'). ':</td><td><input type="text" name="_hp_custom_link_url" value="'.esc_attr($hp_custom_link_url).'" size="60">&nbsp;<small>' . __('Required', 'hopos-plugin') . '</small></td><td>&nbsp;<a href="#" class="hp_info_btn" id="btn4"></a><div class="hp_info">Can be any page on the site or even an external site.<br>Example: http://www.somedomain.com/</div></td>';
	echo '</tr>';
	
	echo '<tr><td colspan="3"><hr noshade="noshade" color="#dddddd"></td></tr>';
	
	echo '<tr><td colspan="3"><hr noshade="noshade" color="#f6f6f6"></td></tr>';
	echo '<tr><td colspan="3" style="background:#ddd; font-size:14px; padding:6px;">Media Options</td></tr>';
	echo '<tr><td colspan="3"><hr noshade="noshade" color="#f6f6f6"></td></tr>';
	
	echo '<tr>';
	echo '<td>' .__('Large Image URL', 'hopos-plugin'). ':</td><td><input type="text" name="_hp_large_image_url" value="'.esc_attr($hp_large_image_url).'" size="60">&nbsp;<small>' . __('Optional', 'hopos-plugin') . '</small></td><td>&nbsp;<a href="#" class="hp_info_btn" id="btn5"></a><div class="hp_info">Formats: jpg, png, gif, bmp. <br>Ex: http://www.yoursite.com/images/photo.jpg</div></td>';
	echo '</tr>';
	
	echo '<tr>';
	echo '<td>' .__('Large Image Text Icon', 'hopos-plugin'). ':</td><td><input type="text" name="_hp_large_image_text_icon" value="'.esc_attr($hp_large_image_text_icon).'" size="20">&nbsp;<small>' . __('Optional', 'hopos-plugin') . '</small></td><td></td>';
	echo '</tr>';
	
	echo '<tr><td colspan="3"><hr noshade="noshade" color="#dddddd"></td></tr>';
	
	echo '<tr>';
	echo '<td>' .__('Video URL', 'hopos-plugin'). ':</td><td><input type="text" name="_hp_video_url" id="_hp_video_url" value="'.esc_attr($hp_video_url).'" size="60">&nbsp;<small>' . __('Optional', 'hopos-plugin') . '</small></td><td>&nbsp;<a href="#" class="hp_info_btn" id="btn6"></a><div class="hp_info">Examples:<br>Youtube => http://www.youtube.com/watch?v=THE_VIDEO_ID<br>Vimeo => http://vimeo.com/THE_VIDEO_ID<br>MP4 => http://yoursite.com/videos/filename.mp4<br>FLV => http://yoursite.com/videos/filename.flv<br></div></td>';
	echo '</tr>';
	
	echo '<tr>';
	echo '<td>' .__('Video Text Icon', 'hopos-plugin'). ':</td><td><input type="text" name="_hp_video_text_icon" value="'.esc_attr($hp_video_text_icon).'" size="20">&nbsp;<small>' . __('Optional', 'hopos-plugin') . '</small></td><td></td>';
	echo '</tr>';
		
	echo '<tr id="field_video_type">';
	echo '<td>' . __('Video Type', 'hopos-plugin') . ':</td><td><select name="_hp_video_type" id="_hp_video_type" style="width:150px;">
			<option value="video-youtube" ' . (is_null($hp_video_type) || $hp_video_type == "video-youtube" ? 'selected="selected" ' : '').'>' .__('Youtube', 'hopos-plugin'). '</option>
			<option value="video-vimeo" ' . ($hp_video_type == "video-vimeo" ? 'selected="selected" ' : '') . '>' . __('Vimeo', 'hopos-plugin') . '</option>
			<option value="video-mp4" ' . ($hp_video_type == "video-mp4" ? 'selected="selected" ' : '').'>' .__('MP4', 'hopos-plugin'). '</option>
			<option value="video-flv" ' . ($hp_video_type == "video-flv" ? 'selected="selected" ' : '').'>' .__('FLV', 'hopos-plugin'). '</option>
		</select></td><td>&nbsp;<a href="#" class="hp_info_btn" id="btn7"></a><div class="hp_info">Choose the type of video according to the url above.</div></td>';
	echo '</tr>';
	
	$hp_video_width = (trim($hp_video_width) == '') ? 560 : $hp_video_width;
	$hp_video_height = (trim($hp_video_height) == '') ? 300 : $hp_video_height;
	
	echo '<tr>';
	echo '<td>' . __('Video Size', 'hopos-plugin') . ':</td><td>
			<input type="text" name="_hp_video_width" id="_hp_lightbox_width" value="'.esc_attr($hp_video_width).'" size="5">&nbsp;Width&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="text" name="_hp_video_height" id="_hp_video_height" value="'.esc_attr($hp_video_height).'" size="5">&nbsp;Height
			</td><td>&nbsp;</td>';
	echo '</tr>';
	
	echo '<tr><td colspan="3"><hr noshade="noshade" color="#dddddd"></td></tr>';
	
	
	echo '<tr>';
	echo '<td>' .__('Audio URL', 'hopos-plugin'). ':</td><td><input type="text" name="_hp_audio_url" value="'.esc_attr($hp_audio_url).'" size="60">&nbsp;<small>' . __('Optional', 'hopos-plugin') . '</small></td><td>&nbsp;<a href="#" class="hp_info_btn" id="btn8"></a><div class="hp_info">MP3 format. Ex: http://www.yoursite.com/audio/file.mp3</div></td>';
	echo '</tr>';
	
	echo '<tr>';
	echo '<td>' .__('Audio Text Icon', 'hopos-plugin'). ':</td><td><input type="text" name="_hp_audio_text_icon" value="'.esc_attr($hp_audio_text_icon).'" size="20">&nbsp;<small>' . __('Optional', 'hopos-plugin') . '</small></td><td></td>';
	echo '</tr>';
	
	echo '<tr><td colspan="3"><hr noshade="noshade" color="#dddddd"></td></tr>';

	
	echo '<tr><td colspan="3"><hr noshade="noshade" color="#f6f6f6"></td></tr>';
	echo '<tr><td colspan="3" style="background:#ddd; font-size:14px; padding:6px;">Extra information - allowed to use html and shortcodes</td></tr>';
	echo '<tr><td colspan="3"><hr noshade="noshade" color="#f6f6f6"></td></tr>';
	
	echo '<tr>';
	echo '<td>' .__('Extra Info', 'hopos-plugin'). ':</td><td><textarea name="_hp_extra_info" cols="60" rows="3">' . stripslashes($hp_extra_info) . '</textarea>&nbsp;<small>' . __('Optional', 'hopos-plugin') . '</small></td><td>&nbsp;<a href="#" class="hp_info_btn" id="btn9"></a><div class="hp_info">Use this field for any additional information. See the following example:<br>&#60;span style="font-weight:bold; font-size:14px;color:#333;"&#62;Text 1&#60;/span&#62; - &#60;span style="font-style:italic"&#62;Extra information&#60;/span&#62;</div></td>';
	echo '</tr>';
	
	echo '<tr><td colspan="3"><hr noshade="noshade" color="#f6f6f6"></td></tr>';
	echo '<tr><td colspan="3" style="background:#ddd; font-size:14px; padding:6px;">Social Media Options</td></tr>';
	echo '<tr><td colspan="3"><hr noshade="noshade" color="#f6f6f6"></td></tr>';
	
	echo '<tr>';
	echo '<td>' .__('Twitter', 'hopos-plugin'). ':</td><td><input type="text" name="_hp_social_twitter" value="'.esc_attr($hp_social_twitter).'" size="60">&nbsp;<small>' . __('Optional', 'hopos-plugin') . '</small></td><td>&nbsp;<a href="#" class="hp_info_btn" id="btn10"></a><div class="hp_info">Leave empty if you do not use this option.</div></td>';
	echo '</tr>';
	
	echo '<tr><td colspan="3"><hr noshade="noshade" color="#dddddd"></td></tr>';
	
	echo '<tr>';
	echo '<td>' .__('Facebook', 'hopos-plugin'). ':</td><td><input type="text" name="_hp_social_facebook" value="'.esc_attr($hp_social_facebook).'" size="60">&nbsp;<small>' . __('Optional', 'hopos-plugin') . '</small></td><td>&nbsp;<a href="#" class="hp_info_btn" id="btn11"></a><div class="hp_info">Leave empty if you do not use this option.</div></td>';
	echo '</tr>';
	
	echo '<tr><td colspan="3"><hr noshade="noshade" color="#dddddd"></td></tr>';
	
	echo '<tr>';
	echo '<td>' .__('Pinterest', 'hopos-plugin'). ':</td><td><input type="text" name="_hp_social_pinterest" value="'.esc_attr($hp_social_pinterest).'" size="60">&nbsp;<small>' . __('Optional', 'hopos-plugin') . '</small></td><td>&nbsp;<a href="#" class="hp_info_btn" id="btn12"></a><div class="hp_info">Leave empty if you do not use this option.</div></td>';
	echo '</tr>';
	
	echo '<tr><td colspan="3"><hr noshade="noshade" color="#dddddd"></td></tr>';
	
	echo '<tr>';
	echo '<td>' .__('Google+', 'hopos-plugin'). ':</td><td><input type="text" name="_hp_social_google" value="'.esc_attr($hp_social_google).'" size="60">&nbsp;<small>' . __('Optional', 'hopos-plugin') . '</small></td><td>&nbsp;<a href="#" class="hp_info_btn" id="btn13"></a><div class="hp_info">Leave empty if you do not use this option.</div></td>';
	echo '</tr>';
	
	echo '<tr><td colspan="3"><hr noshade="noshade" color="#dddddd"></td></tr>';
	
	echo '<tr>';
	echo '<td>' .__('Linkedin', 'hopos-plugin'). ':</td><td><input type="text" name="_hp_social_linkedin" value="'.esc_attr($hp_social_linkedin).'" size="60">&nbsp;<small>' . __('Optional', 'hopos-plugin') . '</small></td><td>&nbsp;<a href="#" class="hp_info_btn" id="btn14"></a><div class="hp_info">Leave empty if you do not use this option.</div></td>';
	echo '</tr>';
	
	echo '<tr><td colspan="3"><hr noshade="noshade" color="#dddddd"></td></tr>';
	
	echo '<tr>';
	echo '<td>' .__('Flickr', 'hopos-plugin'). ':</td><td><input type="text" name="_hp_social_flickr" value="'.esc_attr($hp_social_flickr).'" size="60">&nbsp;<small>' . __('Optional', 'hopos-plugin') . '</small></td><td>&nbsp;<a href="#" class="hp_info_btn" id="btn15"></a><div class="hp_info">Leave empty if you do not use this option.</div></td>';
	echo '</tr>';
	
	echo '<tr><td colspan="3"><hr noshade="noshade" color="#dddddd"></td></tr>';
	
	echo '<tr>';
	echo '<td>' .__('Behance', 'hopos-plugin'). ':</td><td><input type="text" name="_hp_social_behance" value="'.esc_attr($hp_social_behance).'" size="60">&nbsp;<small>' . __('Optional', 'hopos-plugin') . '</small></td><td>&nbsp;<a href="#" class="hp_info_btn" id="btn16"></a><div class="hp_info">Leave empty if you do not use this option.</div></td>';
	echo '</tr>';
	
	echo '<tr><td colspan="3"><hr noshade="noshade" color="#dddddd"></td></tr>';

	echo '</table>';
	echo '<br><br>';
}//hopos_meta_box


// ---------------------------------------
// save meta box data
// ---------------------------------------

function hopos_lite_save_meta_box($post_id) {
	global $post;
	
	// if post is a revision skip saving meta box data
	//if($post->post_type == 'revision') { return; }

	// process form data if $_POST is set
	if(isset($_POST['_hp_video_type']) && $_POST['_hp_video_type'] != '') {

		// save the meta box data as post meta using the post ID as a unique prefix
		update_post_meta($post_id,'_hp_product_thumb_url', esc_attr($_POST['_hp_product_thumb_url']));
		update_post_meta($post_id,'_hp_open_content', esc_attr($_POST['_hp_open_content']));
		update_post_meta($post_id,'_hp_product_new_window', esc_attr($_POST['_hp_product_new_window']));
		update_post_meta($post_id,'_hp_lightbox_width', esc_attr($_POST['_hp_lightbox_width']));
		update_post_meta($post_id,'_hp_lightbox_height', esc_attr($_POST['_hp_lightbox_height']));
		update_post_meta($post_id,'_hp_custom_link_url', esc_attr($_POST['_hp_custom_link_url']));
		update_post_meta($post_id,'_hp_large_image_url', esc_attr($_POST['_hp_large_image_url']));
		update_post_meta($post_id,'_hp_video_url', esc_attr($_POST['_hp_video_url']));
		update_post_meta($post_id,'_hp_video_type', esc_attr($_POST['_hp_video_type']));
		update_post_meta($post_id,'_hp_video_width', esc_attr($_POST['_hp_video_width']));
		update_post_meta($post_id,'_hp_video_height', esc_attr($_POST['_hp_video_height']));
		update_post_meta($post_id,'_hp_large_image_text_icon', esc_attr($_POST['_hp_large_image_text_icon']));
		update_post_meta($post_id,'_hp_video_text_icon', esc_attr($_POST['_hp_video_text_icon']));
		update_post_meta($post_id,'_hp_audio_url', esc_attr($_POST['_hp_audio_url']));
		update_post_meta($post_id,'_hp_audio_text_icon', esc_attr($_POST['_hp_audio_text_icon']));
		update_post_meta($post_id,'_hp_extra_info', esc_attr($_POST['_hp_extra_info']));
		
		//social
		update_post_meta($post_id,'_hp_social_twitter', esc_attr($_POST['_hp_social_twitter']));
		update_post_meta($post_id,'_hp_social_facebook', esc_attr($_POST['_hp_social_facebook']));
		update_post_meta($post_id,'_hp_social_pinterest', esc_attr($_POST['_hp_social_pinterest']));
		update_post_meta($post_id,'_hp_social_google', esc_attr($_POST['_hp_social_google']));
		update_post_meta($post_id,'_hp_social_linkedin', esc_attr($_POST['_hp_social_linkedin']));
		update_post_meta($post_id,'_hp_social_flickr', esc_attr($_POST['_hp_social_flickr']));
		update_post_meta($post_id,'_hp_social_behance', esc_attr($_POST['_hp_social_behance']));	
	}
}
?>