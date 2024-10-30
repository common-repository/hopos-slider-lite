<?php
//shortcode
$hp_options_arr = array('hpPostsPerSlide'=>4, 'hpSlideWidth'=>'100%', 'hpSlideHeight'=>400, 'hpPostMargin'=>13, 'hpShowPreviousNext'=>'true', 'hpScrollSpeed'=>600, 'hpAutoSlide'=>'true',
	'hpAutoSlideDelay'=>5000, 'hpShowPlayPause'=>'true', 'hpShowPagination'=>'true', 'hpAlignPagination'=>'center', 'hpSwipeDrag'=>'false', 'hpPageStyle'=>'bullets',
	'hpThumbWidth'=>440, 'hpThumbHeight'=>300, 'hpSkin'=>'basic', 'hpCustomThisSkin'=>'false', 'hpAddExcerpt'=>'false', 'hpShowTitle'=>'true', 'hpAddShadow'=>'false', 'hpExcerptNumWords'=>20, 'hpTitleHeader'=>'Hopos Header', 'hpAmountPosts'=>20,
	'hpCategories'=>array(), 'hpPostType'=>'post', 'hpTaxonomy'=>'category', 'hpName'=>'Hopos Name', 'hpOrderby'=>'date', 'hpOrder'=>'DESC', 'hpResolution960'=>4, 'hpResolution768'=>2, 'hpResolution480'=>1,'hpCustomSkin'=>''
);
 

//general
$hp_general_arr = array("hp_open_hopos_script"=>"open_in_footer", "hpEnableCustomPostType"=>array());

//save default option values
update_option('hp_options', $hp_options_arr);
update_option('hp_general', $hp_general_arr);

//create/update table
global $wpdb;
	
$table_name = $wpdb->base_prefix . 'hopos_lite';

if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {

	$sql = "CREATE TABLE " . $table_name ." (
				  id mediumint(9) NOT NULL AUTO_INCREMENT,
				  name tinytext NOT NULL,
				  settings TEXT NOT NULL,
				  custom_skin TEXT NOT NULL,
				  PRIMARY KEY (id)
			);";	

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
}
?>