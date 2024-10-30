<?php
// ---------------------------------------
// Ajax handler taxonomy/posttype query
// ---------------------------------------

//add_action('wp_ajax_nopriv_hp_taxonomy_ajax', 'hp_taxonomy_ajax');
add_action('wp_ajax_hp_lite_taxonomy_ajax', 'hp_lite_taxonomy_ajax');
function hp_lite_taxonomy_ajax() {

	$post_type = $_REQUEST['post_type'];
	$id = $_REQUEST['id'];
	
	//database query
	
	if (isset($id) && $id != '') {

		global $wpdb;
		$table_name = $wpdb->base_prefix . 'hopos_lite';
		
		$query = $wpdb->get_results( "SELECT * FROM $table_name WHERE id = $id", ARRAY_A );
		
		if ($query) {
			foreach ( $query as $row ) {
				$settings = $row['settings'];
			}
		}
		
		$set = explode("||", $settings);
		$arr_cats = array();
		
		foreach ( $set as $s ) {
			$s2 = explode("::", $s);
			
			if ($s2[0] == 'hpCategories') {
				$arr_cats = explode(",", $s2[1]);
				break;
			}
		}
	}
	
	
	if ($post_type != 'post') {
		
		//get public taxonomies
		////$args = array('public' => true, '_builtin' => false, 'object_type' => array($post_type)); 
		////$nam_obj = 'names'; // names or objects, note names is the default
		////$operator = 'and'; // 'and' or 'or'
		////$taxonomies = get_taxonomies($args,$nam_obj,$operator);
		$taxonomies = get_object_taxonomies($post_type);
		
		if ($taxonomies) { 
			$taxName = implode(",", $taxonomies);
		}
		
	} else {
		$taxName = 'category';
	}

	echo "Taxonomies associated with <strong><u>$post_type</u></strong> post type: <input style='background:#f9f9f9;color:#777;' type='text' name='hpTaxonomy' id='hpTaxonomy' value='$taxName' size='80' disabled='disabled' /><br><br>";
	
	
	if ($taxName == "category") {
		
		$categories = get_categories();
		
		echo '<div style="background:#777; color:#fff; font-size:14px; padding:4px; margin-bottom:5px; text-align:center;">' . $taxName . '</div>';
		

		//get categories from post-type / taxonomy
		
		$count = count($categories); 
		//$hp_options = get_option('hp_options');
		
		if ( $count > 0 ) {
	
			foreach ($categories as $cat) {
				$option = '<input type="checkbox" name="hp_options[hpCategories][]"';
				
				if (isset($id) && $id != '') {
					if (is_array($arr_cats)) {
						foreach ($arr_cats as $cats) {
							if($cats == $cat->slug) { //$cat->term_id
								$option = $option.' checked="checked"';
							}
						}
					}
				}
	
				$option .= ' value="' . $cat->slug . '" />';
				$option .= '&nbsp;' . $cat->name . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
				echo $option;
			}//foreach
		}
		
		echo '<br><br>';

	} else {
		
		// other taxonomies
		
		foreach ($taxonomies as $taxonomy) {
			
			$taxName = $taxonomy;
			$categories = get_terms($taxName);
			
			
			$option = '<div style="background:#777; color:#fff; font-size:14px; padding:4px; margin-bottom:5px; text-align:center;">' . $taxName . '</div>';
			
			//get categories from post-type / taxonomy
		
			$count = count($categories); 
			//$hp_options = get_option('hp_options');
			
			if ( $count > 0 ) {
		
				foreach ($categories as $cat) {
					$option .= '<input type="checkbox" name="hp_options[hpCategories][]"';
					
					if (isset($id) && $id != '') {
						if (is_array($arr_cats)) {
							foreach ($arr_cats as $cats) {
								if($cats == $cat->slug) { //$cat->term_id
									$option = $option.' checked="checked"';
								}
							}
						}
					}
		
					$option .= ' value="' . $cat->slug . '" />';
					$option .= '&nbsp;' . $cat->name . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
					//echo $option;
				}//foreach
			}
			
			$option .= '<br /><br />';
			echo $option;
			
		}//foreach

	}
		
	die(); //eliminate the 0
}//hp_taxonomy_ajax




// ---------------------------------------
// Ajax handler save options
// ---------------------------------------

add_action('wp_ajax_hp_lite_save_ajax', 'hp_lite_save_ajax');
function hp_lite_save_ajax() {
	
	if (!is_admin() || !current_user_can("manage_options")) {
		echo 'permission denied';
		die();
	}
	
	global $wpdb;
	$table_name = $wpdb->base_prefix . 'hopos_lite';
	
	$hp_action = $_REQUEST['hp_action'];
	$hp_id = $_REQUEST['hp_id'];
	$name = trim($_REQUEST['hp_name']);
	if ($name == '') $name = 'Hopos Name';
	
	//echo $hp_id; exit;
	
	$settings = '';
	
	$settings .= 'hpPostsPerSlide' . '::' . $_REQUEST['hpPostsPerSlide'] . '||';
	$settings .= 'hpSlideWidth' . '::' . $_REQUEST['hpSlideWidth'] . '||';
	$settings .= 'hpSlideHeight' . '::' . $_REQUEST['hpSlideHeight'] . '||';
	$settings .= 'hpPostMargin' . '::' . $_REQUEST['hpPostMargin'] . '||';
	$settings .= 'hpShowPreviousNext' . '::' . $_REQUEST['hpShowPreviousNext'] . '||';
	$settings .= 'hpScrollSpeed' . '::' . $_REQUEST['hpScrollSpeed'] . '||';
	$settings .= 'hpAutoSlide' . '::' . $_REQUEST['hpAutoSlide'] . '||';
	$settings .= 'hpAutoSlideDelay' . '::' . $_REQUEST['hpAutoSlideDelay'] . '||';
	$settings .= 'hpShowPlayPause' . '::' . $_REQUEST['hpShowPlayPause'] . '||';
	$settings .= 'hpShowPagination' . '::' . $_REQUEST['hpShowPagination'] . '||';
	$settings .= 'hpAlignPagination' . '::' . $_REQUEST['hpAlignPagination'] . '||';
	$settings .= 'hpSwipeDrag' . '::' . $_REQUEST['hpSwipeDrag'] . '||';
	$settings .= 'hpPageStyle' . '::' . $_REQUEST['hpPageStyle'] . '||';
	$settings .= 'hpThumbWidth' . '::' . $_REQUEST['hpThumbWidth'] . '||';
	$settings .= 'hpThumbHeight' . '::' . $_REQUEST['hpThumbHeight'] . '||';
	$settings .= 'hpSkin' . '::' . $_REQUEST['hpSkin'] . '||';
	$settings .= 'hpCustomThisSkin' . '::' . $_REQUEST['hpCustomThisSkin'] . '||';
	$settings .= 'hpAddExcerpt' . '::' . $_REQUEST['hpAddExcerpt'] . '||';
	$settings .= 'hpExcerptNumWords' . '::' . $_REQUEST['hpExcerptNumWords'] . '||';
	$settings .= 'hpShowTitle' . '::' . $_REQUEST['hpShowTitle'] . '||';
	$settings .= 'hpAddShadow' . '::' . $_REQUEST['hpAddShadow'] . '||';
	$settings .= 'hpTitleHeader' . '::' . $_REQUEST['hpTitleHeader'] . '||';
	$settings .= 'hpAmountPosts' . '::' . $_REQUEST['hpAmountPosts'] . '||';
	$settings .= 'hpCategories' . '::' . $_REQUEST['hpCategories'] . '||';
	$settings .= 'hpPostType' . '::' . $_REQUEST['hpPostType'] . '||';
	$settings .= 'hpTaxonomy' . '::' . $_REQUEST['hpTaxonomy'] . '||';
	$settings .= 'hpOrderby' . '::' . $_REQUEST['hpOrderby'] . '||';
	$settings .= 'hpOrder' . '::' . $_REQUEST['hpOrder'] . '||';
	$settings .= 'hpResolution960' . '::' . $_REQUEST['hpResolution960'] . '||';
	$settings .= 'hpResolution768' . '::' . $_REQUEST['hpResolution768'] . '||';
	$settings .= 'hpResolution480' . '::' . $_REQUEST['hpResolution480'];


	$hpCustomSkin = $_REQUEST['hpCustomSkin'];
	
	if ($hpCustomSkin == '') {
		$hpCustomSkin = '';
	}

	
	if ($hp_action == 'add') {
		
		$wpdb->insert( 
			$table_name, 
			array(
				'name'=>$name,
				'settings'=>$settings,
				'custom_skin'=>$hpCustomSkin),	
			array(
				'%s',
				'%s',
				'%s')
		);
		
		if ($wpdb->insert_id) {
			echo 'saved||' . $wpdb->insert_id;
			die();
		}
	
	} elseif ($hp_action == 'edit') {
		
		$result = $wpdb->update(
			$table_name,
			array(
				'name'=>$name,
				'settings'=>$settings,
				'custom_skin'=>$hpCustomSkin),
			array( 'id' => $hp_id ),
			array( 
				'%s',
				'%s',
				'%s'),
			array('%d')
		);
				
		echo 'saved';
		die();
	}
}//save



// ---------------------------------------
// Ajax handler delete
// ---------------------------------------

//add_action('wp_ajax_nopriv_hp_lite_delete_ajax', 'hp_lite_delete_ajax');
add_action('wp_ajax_hp_lite_delete_ajax', 'hp_lite_delete_ajax');
function hp_lite_delete_ajax() {

	if (!is_admin() || !current_user_can("manage_options")) {
		echo 'permission denied';
		die();
	}
	
	global $wpdb;
	$table_name = $wpdb->base_prefix . 'hopos_lite';
	
	$hp_action = $_REQUEST['hp_action'];
	$hp_id = $_REQUEST['hp_id'];
	
	if ($hp_action == 'delete') {
		$wpdb->query( "DELETE FROM " . $table_name . " WHERE id = $hp_id" );
		echo 'deleted';
		die();	
	}
}//delete
?>