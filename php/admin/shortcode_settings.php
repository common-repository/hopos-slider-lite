<?php
//load options Array
$hp_options = get_option('hp_options');
$action = ( isset($_GET['action']) ) ? $_GET['action'] : 'list';

$url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$urlSplit = explode('&action=', $url);
//echo $urlSplit[0];


// --------------------------
// Google fonts
// --------------------------

/*
//if google fonts does not respond get the default fonts
$google_fonts_list = array('Abel', 'Abril Fatface', 'Aclonica', 'Actor', 'Adamina', 'Aldrich', 'Allerta', 'Amatic SC', 'Andada', 'Andika', 'Annie Use Your Telescope', 'Antic', 'Arapey', 'Architects Daughter', 'Arimo', 'Artifika', 'Arvo', 'Atomic Age', 'Aubrey', 'Bitter', 'Brawler', 'Cabin Condensed', 'Cabin Sketch', 'Cagliostro', 'Calligraffitti', 'Candal', 'Cantarell', 'Cardo', 'Carme', 'Caudex', 'Cedarville Cursive', 'Coda', 'Coda Caption', 'Comfortaa', 'Convergence', 'Copse', 'Cousine', 'Covered By Your Grace', 'Cuprum', 'Dancing Script', 'Didact Gothic', 'Droid Sans', 'Droid Sans Mono', 'Droid Serif', 'EB Garamond', 'Expletus Sans', 'Federo', 'Fjord One', 'Fondamento', 'Forum', 'Francois One', 'Gentium Book Basic', 'Geo', 'Give You Glory', 'Goudy Bookletter 1911', 'Gruppo', 'Hammersmith One', 'IM Fell Double Pica SC', 'Iceland', 'Inconsolata', 'Indie Flower', 'Istok Web', 'Josefin Sans', 'Josefin Slab', 'Judson', 'Julee', 'Jura', 'Just Another Hand', 'Kelly Slab', 'Kranky', 'Kreon', 'La Belle Aurore', 'Lancelot', 'Lato', 'Limelight', 'Linden Hill', 'Marvel', 'Mate', 'Mate SC', 'Maven Pro', 'Monofett', 'Monoton', 'Mountains of Christmas', 'Neucha', 'Neuton', 'News Cycle', 'Nixie One', 'Nobile', 'Nothing You Could Do', 'Nova Cut', 'Nova Round', 'Nova Slim', 'Numans', 'Old Standard TT', 'Open Sans', 'Oswald', 'Over the Rainbow', 'Ovo', 'PT Sans', 'PT Sans Narrow', 'PT Serif', 'PT Serif Caption', 'Petrona', 'Philosopher', 'Pinyon Script', 'Play', 'Playfair Display', 'Podkova', 'Pompiere', 'Prata', 'Quattrocento', 'Quattrocento Sans', 'Questrial', 'Quicksand', 'Rancho', 'Rationale', 'Redressed', 'Ribeye', 'Righteous', 'Rochester', 'Rokkitt', 'Rosario', 'Salsa', 'Satisfy', 'Signika', 'Smythe', 'Snippet', 'Sorts Mill Goudy', 'Spirax', 'Sue Ellen Francisco', 'Sunshiney', 'Supermercado One', 'Swanky and Moo Moo', 'Syncopate', 'Tangerine', 'Tenor Sans', 'Terminal Dosis', 'Tienne', 'Tinos', 'Tulpen One', 'Ubuntu', 'Ubuntu Condensed', 'Ubuntu Mono', 'UnifrakturMaguntia', 'Unkempt', 'Unna', 'VT323', 'Varela', 'Varela Round', 'Vibur', 'Vidaloka', 'Volkhov', 'Voltaire', 'Walter Turncoat', 'Wire One', 'Yanone Kaffeesatz', 'Yellowtail', 'Yeseva One');
		
//sort($google_fonts_list);

//add system fonts
array_push($google_fonts_list, "Arial (system)", "Georgia (system)", "Lucida Sans Unicode (system)", "Tahoma (system)", "Times New Roman (system)", "Trebuchet MS (system)", "Verdana (system)");
*/
	
?>	



<?php
if ($action == 'list') {
	global $wpdb;
	$table_name = $wpdb->base_prefix . 'hopos_lite';
	$query = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY id ASC", ARRAY_A );
	
	///////////////////////////
	////echo count($query);
?>

<div class="wrap" style="padding:0px; margin-top:0px; font-family:Arial, Helvetica, sans-serif !important;">

	<h2>
	<?php _e('Hopos Slider', 'znews-plugin') ?>
    <?php 
	if (count($query) < 1) {
	?>
    	<a class="add-new-h2" href="<?php echo $urlSplit[0] ?>&action=add">Add New</a>
    <?php 
	} else {
		echo '<div class="add-new-h2" style="margin:10px auto; font-size:13px;">The Lite version is limited with only one slider. In the <a href="http://codecanyon.net/item/hopos-slider-responsive-wordpress-plugin/4559806" target="_blank">Premium version</a> is allowed unlimited sliders.</div>';
	}
	?>
    
    </h2>
    <?php if ( isset($_REQUEST['settings-updated']) ) echo '<div id="message" class="updated fade"><p><strong>' . 'Hopos' . '&nbsp;-&nbsp;' . __('Settings Saved.','znews-plugin') . '</strong></p></div>'; ?>
    
    <table class="wp-list-table widefat fixed pages" cellspacing="0">
        <tdead>
            <tr>
                <td width="5%">ID</td>
                <td width="50%">Name</td>
                <td width="30%">Actions</td>
                <td width="20%">Shortcode</td>
            </tr>
        </thead>
        
        <tbody>
        	<?php
			if ($query) {
				foreach ( $query as $row ) {
			?>
                    <tr>
                        <td><?php echo $row['id'] ?></td>
                        <td><a href="<?php echo $urlSplit[0] ?>&action=edit&id=<?php echo $row['id'] ?>"><?php echo $row['name'] ?></a></td>
                        <td><a href="<?php echo $urlSplit[0] ?>&action=edit&id=<?php echo $row['id'] ?>">Edit</a> | <a href="#" id="del_<?php echo $row['id'] ?>" class="delete_hopos" data-name="<?php echo $row['name'] ?>">Delete</a></td>
                        <td><input type="text" value="[hopos id='<?php echo $row['id'] ?>']"></td>
                        
                     </tr>
			<?php
				}
			}
			?>
        </tbody>
    
        <tfoot>
            <tr>
                <td width="5%">ID</td>
                <td width="50%">Name</td>
                <td width="30%">Actions</td>
                <td width="20%">Shortcode</td>
            </tr>
        </tfoot>

    </table>
    <div id="info_shortcode">
    	<h3>INFO:</h3>
        Click Add New to create a new Hopos slider. Configure the options and then return to this page.
        <br /><br />
    	Copy and paste the shortcode that was created within a page or post. It is possible to use any number of Sliders
        <br /><br />
        You can also use Hopos Slider as a widget. Follow the steps below:<br />
        1 - In the Wordpress menu, select Appearance > Widgets.<br />
        2 - Drag a widget of type "Text" for any sidebar. Paste the Hopos shortcode and save.
        <br /><br />
        It is also possible use the shortcode within a php page: Example:<br /> &lt;?php echo do_shortcode("[hopos id='x']"); ?&gt;
    </div>
    <input type="hidden" id="hp_url_list" value="<?php echo $urlSplit[0] ?>" />
</div>

<?php
}
?>







<?php
if ($action == 'add' || $action == 'edit') {
	
	//edit
	if($action == 'edit') {
		
		global $wpdb;
		$table_name = $wpdb->base_prefix . 'hopos_lite';
		$id = $_REQUEST['id'];
		
		//echo $id;

		$query = $wpdb->get_results( "SELECT * FROM $table_name WHERE id = $id", ARRAY_A );
		
		if ($query) {
			foreach ( $query as $row ) {
				$hpName = $row['name'];
				$settings = $row['settings'];
				$custom_skin = stripslashes($row['custom_skin']);
			}
		}

		$set = explode("||", $settings);
		$arr_settings = array();
		
		foreach ( $set as $s ) {
			$s2 = explode("::", $s);
			$arr_settings[$s2[0]] = $s2[1];
		}
		
		////$arr_settings['hpTitleHeader'] = stripslashes($arr_settings['hpTitleHeader']);
	}//edit
	
	
	//add
	if ($action == 'add') {
		$hpName = $hp_options['hpName'];
	}
	
	
	// -----------------------------------------------------------
	// UPDATE start
	// -----------------------------------------------------------

	//resolution
	if (!isset($arr_settings['hpResolution960'])) $arr_settings['hpResolution960'] = $hp_options['hpResolution960'];
	if (!isset($arr_settings['hpResolution768'])) $arr_settings['hpResolution768'] = $hp_options['hpResolution768'];
	if (!isset($arr_settings['hpResolution480'])) $arr_settings['hpResolution480'] = $hp_options['hpResolution480'];
	
	//custom skins
	if (!isset($arr_settings['hpCustomThisSkin'])) $arr_settings['hpCustomThisSkin'] = $hp_options['hpCustomThisSkin'];
	
	//show title
	if (!isset($arr_settings['hpShowTitle'])) $arr_settings['hpShowTitle'] = $hp_options['hpShowTitle'];
	
	// -----------------------------------------------------------
	// UPDATE end
	// -----------------------------------------------------------
	
?>

    <br>
    <a href="<?php echo $urlSplit[0] ?>&action=list">Back to Hopos List</a><br>
  
        
    <div class="wrap">
    
    	<!-- <form method="post" action="options.php" id="form_shortcode"> -->
        <form id="post_form" method="post" name="post_form">
        
		<div id="poststuf">
        
        	<div id="post-body" class="metabox-holder columns-2" style="margin-right:300px; padding:0;">
        
                <div id="post-body-content">
            
                    <h2><?php _e("Hopos Slider - $action", 'hopos-plugin') ?></h2>
                    
                    
                    <?php if ( isset($_REQUEST['settings-updated']) ) echo '<div id="message" class="updated fade"><p><strong>' . 'Hopos' . '&nbsp;-&nbsp;' . __('Settings Saved.','hopos-plugin') . '</strong></p></div>'; ?>
                
                    <div id="titlediv">
                        <div id="titlewrap">
                            <label id="hp-title-prompt-text" class="" for="title"><?php if ($hpName == '') echo 'Enter Hopos name here'; ?></label>
                            <input id="title" type="text" autocomplete="off" value="<?php echo $hpName; ?>" size="30">
                        </div>
                    </div>
                    
                   
                        <?php settings_fields( 'hopos-lite-settings-group' ); ?>
                        
                        <table class="form-table">
                        
                            <tr valign="top" class="widefat" style="border-top:#888 solid 2px;">
                            <td scope="row"><?php _e('Choose the Post Type', 'hopos-plugin') ?></td>
                            <td>            
                            <?php
                            //get public custom post types
                            $args = array('public' => true, '_builtin' => false); 
                            $nam_obj = 'names'; // names or objects, note names is the default
                            $operator = 'and'; // 'and' or 'or'
                            $post_types=get_post_types($args,$nam_obj,$operator);
                            ?>
                            
                            <select id="hpPostType" name="hpPostType">
                            <option value='post'>post</option>
                            <?php
                            foreach ($post_types as $ptype) {
                                if ($action == 'add') {
                                    if ($hp_options['hpPostType'] == $ptype) {
                                        echo "<option value='$ptype' selected='selected'>$ptype</option>";
                                    } else {
                                        echo "<option value='$ptype'>$ptype</option>";
                                    }
                                    
                                } else {
            
                                    if ($arr_settings['hpPostType'] == $ptype) {
                                        echo "<option value='$ptype' selected='selected'>$ptype</option>";
                                    } else {
                                        echo "<option value='$ptype'>$ptype</option>";
                                    }
                                }
                            }
                            ?>
                            </select>
                            </td>
                            </tr>
                
                            <tr valign="top" class="widefat alternate">
                            <td scope="row"><?php _e('Choose the Categories', 'hopos-plugin') ?></td>
                            <td>
                                <div id="field_tax_categories" style="clear:both;"></div>
                            </td>
                            </tr>
                            
                            <tr valign="top" class="widefat">
                            <td scope="row"><?php _e('Title Header', 'hopos-plugin') ?></td>
                            <td><input type="text" id="hpTitleHeader" name="hpTitleHeader" value="<?php if ($action == 'add') { echo $hp_options['hpTitleHeader']; } else { echo $arr_settings['hpTitleHeader']; } ?>" size="80" /></td>
                            </tr>
                            
                            <tr valign="top" class="widefat alternate">
                            <td scope="row"><?php _e('Amount of Posts', 'hopos-plugin') ?></td>
                            <td><input type="text" id="hpAmountPosts" name="hpAmountPosts" value="<?php if ($action == 'add') { echo $hp_options['hpAmountPosts']; } else { echo $arr_settings['hpAmountPosts']; } ?>" size="3" maxlength="4" />&nbsp;<small><?php _e('Show how many posts.', 'hopos-plugin') ?></small></td>
                            </tr>
                            
                            <tr valign="top" class="widefat">
                            <td scope="row"><?php _e('Posts per Slide', 'hopos-plugin') ?></td>
                            <td><input type="text" id="hpPostsPerSlide" name="hpPostsPerSlide" value="<?php if ($action == 'add') { echo $hp_options['hpPostsPerSlide']; } else { echo $arr_settings['hpPostsPerSlide']; } ?>" size="3" maxlength="4" /></td>
                            </tr>
                
                            <tr valign="top" class="widefat alternate">
                            <td scope="row"><?php _e('Slider Width', 'hopos-plugin') ?></td>
                            <td><input type="text" id="hpSlideWidth" name="hpSlideWidth" value="<?php if ($action == 'add') { echo $hp_options['hpSlideWidth']; } else { echo $arr_settings['hpSlideWidth']; } ?>" size="3" maxlength="4" />&nbsp;<small><?php _e('px (Also allowed percentage value. e.g. 100% - Use the percent symbol).', 'hopos-plugin') ?></small></td>
                            </tr>
                            
                            <?php if ( !isset($arr_settings['hpSlideHeight']) ) { $arr_settings['hpSlideHeight'] = 'auto'; } ?>
                            
                            <tr valign="top" class="widefat">
                            <td scope="row"><?php _e('Slider Height', 'hopos-plugin') ?></td>
                            <td><input type="text" id="hpSlideHeight" name="hpSlideHeight" value="<?php if ($action == 'add') { echo $hp_options['hpSlideHeight']; } else { echo $arr_settings['hpSlideHeight']; } ?>" size="3" maxlength="4" />&nbsp;<small><?php _e('"auto" (without quotes) or some number, e.g. 400. Use number to set each item with the same height', 'hopos-plugin') ?></small></td>
                            </tr>
                            
                            <tr valign="top" class="widefat alternate">
                            <td scope="row"><?php _e('Post Margin', 'hopos-plugin') ?></td>
                            <td><input type="text" id="hpPostMargin" name="hpPostMargin" value="<?php if ($action == 'add') { echo $hp_options['hpPostMargin']; } else { echo $arr_settings['hpPostMargin']; } ?>" size="3" maxlength="4" />&nbsp;<small><?php _e('px (Spacing between posts)', 'hopos-plugin') ?></small></td>
                            </tr>
                            
                            <tr valign="top" class="widefat">
                            <td scope="row"><?php _e('Show Previous Next', 'hopos-plugin') ?></td>
                            <td>
                            <select style="width:120px;" id="hpShowPreviousNext" name="hpShowPreviousNext">
                                <?php if ($action == 'add') { ?>
                                <option value="true" <?php if($hp_options['hpShowPreviousNext'] == "true") { echo "selected='selected'"; } else { echo ""; } ?>>True</option>
                                <option value="false" <?php if($hp_options['hpShowPreviousNext'] == "false") { echo "selected='selected'"; } else { echo ""; }?>>False</option>
                                <?php } else { ?>
                                <option value="true" <?php if($arr_settings['hpShowPreviousNext'] == "true") { echo "selected='selected'"; } else { echo ""; } ?>>True</option>
                                <option value="false" <?php if($arr_settings['hpShowPreviousNext'] == "false") { echo "selected='selected'"; } else { echo ""; }?>>False</option>
                                <?php } ?>
                            </select>
                            </td>
                            </tr>
                            
                            <tr valign="top" class="widefat alternate">
                            <td scope="row"><?php _e('Scroll Speed', 'hopos-plugin') ?></td>
                            <td><input type="text" id="hpScrollSpeed" name="hpScrollSpeed" value="<?php if ($action == 'add') { echo $hp_options['hpScrollSpeed']; } else { echo $arr_settings['hpScrollSpeed']; } ?>" size="3" maxlength="4" />&nbsp;<small><?php _e('Milliseconds', 'hopos-plugin') ?></small></td>
                            </tr>
                            
                            <tr valign="top" class="widefat">
                            <td scope="row"><?php _e('Auto Slide', 'hopos-plugin') ?></td>
                            <td>
                            <select style="width:120px;" name="hpAutoSlide" id="hpAutoSlide">
                                <?php if ($action == 'add') { ?>
                                <option value="true" <?php if($hp_options['hpAutoSlide'] == "true") { echo "selected='selected'"; } else { echo ""; } ?>>True</option>
                                <option value="false" <?php if($hp_options['hpAutoSlide'] == "false") { echo "selected='selected'"; } else { echo ""; }?>>False</option>
                                <?php } else { ?>
                                <option value="true" <?php if($arr_settings['hpAutoSlide'] == "true") { echo "selected='selected'"; } else { echo ""; } ?>>True</option>
                                <option value="false" <?php if($arr_settings['hpAutoSlide'] == "false") { echo "selected='selected'"; } else { echo ""; }?>>False</option>
                                <?php } ?>
                            </select>
                            </td>
                            </tr>
                            
                            <tr valign="top" class="widefat alternate">
                            <td scope="row"><?php _e('Auto Slide Delay', 'hopos-plugin') ?></td>
                            <td>
                            <input type="text" id="hpAutoSlideDelay" name="hpAutoSlideDelay" value="<?php if ($action == 'add') { echo $hp_options['hpAutoSlideDelay']; } else { echo $arr_settings['hpAutoSlideDelay']; }?>" size="3" maxlength="4" />&nbsp;<small><?php _e('Milliseconds', 'hopos-plugin') ?></small>
                            </td>
                            </tr>
                            
                            <tr valign="top" class="widefat">
                            <td scope="row"><?php _e('Show Play Pause', 'hopos-plugin') ?></td>
                            <td>
                            <select style="width:120px;" id="hpShowPlayPause" name="hpShowPlayPause">
                                <?php if ($action == 'add') { ?>
                                <option value="true" <?php if($hp_options['hpShowPlayPause'] == "true") { echo "selected='selected'"; } else { echo ""; } ?>>True</option>
                                <option value="false" <?php if($hp_options['hpShowPlayPause'] == "false") { echo "selected='selected'"; } else { echo ""; }?>>False</option>
                                <?php } else { ?>
                                <option value="true" <?php if($arr_settings['hpShowPlayPause'] == "true") { echo "selected='selected'"; } else { echo ""; } ?>>True</option>
                                <option value="false" <?php if($arr_settings['hpShowPlayPause'] == "false") { echo "selected='selected'"; } else { echo ""; }?>>False</option>
                                <?php } ?>
                            </select>
                            </td>
                            </tr>
                            
                            <tr valign="top" class="widefat alternate">
                            <td scope="row"><?php _e('Show Pagination', 'hopos-plugin') ?></td>
                            <td>
                            <select style="width:120px;" id="hpShowPagination" name="hpShowPagination">
                                <?php if ($action == 'add') { ?>
                                <option value="true" <?php if($hp_options['hpShowPagination'] == "true") { echo "selected='selected'"; } else { echo ""; } ?>>True</option>
                                <option value="false" <?php if($hp_options['hpShowPagination'] == "false") { echo "selected='selected'"; } else { echo ""; }?>>False</option>
                                <?php } else { ?>
                                <option value="true" <?php if($arr_settings['hpShowPagination'] == "true") { echo "selected='selected'"; } else { echo ""; } ?>>True</option>
                                <option value="false" <?php if($arr_settings['hpShowPagination'] == "false") { echo "selected='selected'"; } else { echo ""; }?>>False</option>
                                <?php } ?>
                            </select>
                            </td>
                            </tr>
                            
                            <tr valign="top" class="widefat">
                            <td scope="row"><?php _e('Align Pagination', 'hopos-plugin') ?></td>
                            <td>
                            <select style="width:120px;" id="hpAlignPagination" name="hpAlignPagination">
                                <?php if ($action == 'add') { ?>
                                <option value="left" <?php if($hp_options['hpAlignPagination'] == "left") { echo "selected='selected'"; } else { echo ""; } ?>>left</option>
                                <option value="center" <?php if($hp_options['hpAlignPagination'] == "center") { echo "selected='selected'"; } else { echo ""; }?>>center</option>
                                <option value="right" <?php if($hp_options['hpAlignPagination'] == "right") { echo "selected='selected'"; } else { echo ""; }?>>right</option>
                                <?php } else { ?>
                                <option value="left" <?php if($arr_settings['hpAlignPagination'] == "left") { echo "selected='selected'"; } else { echo ""; } ?>>left</option>
                                <option value="center" <?php if($arr_settings['hpAlignPagination'] == "center") { echo "selected='selected'"; } else { echo ""; }?>>center</option>
                                <option value="right" <?php if($arr_settings['hpAlignPagination'] == "right") { echo "selected='selected'"; } else { echo ""; }?>>right</option>
                                <?php } ?>
                            </select>
                            </td>
                            </tr>
                            
                            <tr valign="top" class="widefat alternate">
                            <td scope="row"><?php _e('Drag Enable', 'hopos-plugin') ?></td>
                            <td>
                            <select style="width:120px;" id="hpSwipeDrag" name="hpSwipeDrag">
                                <?php if ($action == 'add') { ?>
                                <option value="true" <?php if($hp_options['hpSwipeDrag'] == "true") { echo "selected='selected'"; } else { echo ""; } ?>>True</option>
                                <option value="false" <?php if($hp_options['hpSwipeDrag'] == "false") { echo "selected='selected'"; } else { echo ""; }?>>False</option>
                                <?php } else { ?>
                                <option value="true" <?php if($arr_settings['hpSwipeDrag'] == "true") { echo "selected='selected'"; } else { echo ""; } ?>>True</option>
                                <option value="false" <?php if($arr_settings['hpSwipeDrag'] == "false") { echo "selected='selected'"; } else { echo ""; }?>>False</option>
                                <?php } ?>
                            </select>
                            </td>
                            </tr>
                            
                            <tr valign="top" class="widefat">
                            <td scope="row"><?php _e('Pagination Style', 'hopos-plugin') ?></td>
                            <td>
                            <select style="width:120px;" id="hpPageStyle" name="hpPageStyle">
                                <?php if ($action == 'add') { ?>
                                <option value="bullets" <?php if($hp_options['hpPageStyle'] == "bullets") { echo "selected='selected'"; } else { echo ""; } ?>>Bullets</option>
                                <option value="numbers" <?php if($hp_options['hpPageStyle'] == "numbers") { echo "selected='selected'"; } else { echo ""; }?>>Numbers</option>
                                <?php } else { ?>
                                <option value="bullets" <?php if($arr_settings['hpPageStyle'] == "bullets") { echo "selected='selected'"; } else { echo ""; } ?>>Bullets</option>
                                <option value="numbers" <?php if($arr_settings['hpPageStyle'] == "numbers") { echo "selected='selected'"; } else { echo ""; }?>>Numbers</option>
                                <?php } ?>
                            </select>
                            </td>
                            </tr>
                            
                            <tr valign="top" class="widefat alternate">
                            <td scope="row"><?php _e('Thumbnail Size', 'hopos-plugin') ?></td>
                            <td>
                            <input type="text" id="hpThumbWidth" name="hpThumbWidth" value="<?php if ($action == 'add') { echo $hp_options['hpThumbWidth']; } else { echo $arr_settings['hpThumbWidth']; } ?>" size="3" maxlength="4" />&nbsp;X&nbsp;<input type="text" id="hpThumbHeight" name="hpThumbHeight" value="<?php if ($action == 'add') { echo $hp_options['hpThumbHeight']; } else { echo $arr_settings['hpThumbHeight']; } ?>" size="3" maxlength="4" />&nbsp;<small><?php _e('width x height (in pixels)', 'hopos-plugin') ?></small>
                            </td>
                            </tr>
                            
                            
           					 <tr valign="top" class="widefat">
                            <td scope="row"><?php _e('Add Excerpt', 'hopos-plugin') ?></td>
                            <td>
                            <select style="width:120px;" id="hpAddExcerpt" name="hpAddExcerpt">
                                <?php if ($action == 'add') { ?>
                                <option value="true" <?php if($hp_options['hpAddExcerpt'] == "true") { echo "selected='selected'"; } else { echo ""; } ?>>True</option>
                                <option value="false" <?php if($hp_options['hpAddExcerpt'] == "false") { echo "selected='selected'"; } else { echo ""; }?>>False</option>
                                <?php } else { ?>
                                <option value="true" <?php if($arr_settings['hpAddExcerpt'] == "true") { echo "selected='selected'"; } else { echo ""; } ?>>True</option>
                                <option value="false" <?php if($arr_settings['hpAddExcerpt'] == "false") { echo "selected='selected'"; } else { echo ""; }?>>False</option>
                                <?php } ?>
                            </select>
                            <div id="field_hpExcerptNumWords" style="display:inline"><input type="text" id="hpExcerptNumWords" value="<?php if ($action == 'add') {  echo $hp_options['hpExcerptNumWords']; } else { echo $arr_settings['hpExcerptNumWords']; } ?>" size="3" maxlength="4" />&nbsp;<small><?php _e('Number of words', 'hopos-plugin') ?></small></div>
                            </td>
                            </tr>
                            
                            <tr valign="top" class="widefat alternate">
                            <td scope="row"><?php _e('Show Title', 'hopos-plugin') ?></td>
                            <td>
                            <select style="width:120px;" id="hpShowTitle" name="hpShowTitle">
                                <?php if ($action == 'add') { ?>
                                <option value="true" <?php if($hp_options['hpShowTitle'] == "true") { echo "selected='selected'"; } else { echo ""; } ?>>True</option>
                                <option value="false" <?php if($hp_options['hpShowTitle'] == "false") { echo "selected='selected'"; } else { echo ""; }?>>False</option>
                                <?php } else { ?>
                                <option value="true" <?php if($arr_settings['hpShowTitle'] == "true") { echo "selected='selected'"; } else { echo ""; } ?>>True</option>
                                <option value="false" <?php if($arr_settings['hpShowTitle'] == "false") { echo "selected='selected'"; } else { echo ""; }?>>False</option>
                                <?php } ?>
                            </select>
                            </td>
                            </tr>
                            
                            <tr valign="top" class="widefat alternate">
                            <td scope="row"><?php _e('Add Shadow', 'hopos-plugin') ?></td>
                            <td>
                            <select style="width:120px;" id="hpAddShadow" name="hpAddShadow">
                                <?php if ($action == 'add') { ?>
                                <option value="true" <?php if($hp_options['hpAddShadow'] == "true") { echo "selected='selected'"; } else { echo ""; } ?>>True</option>
                                <option value="false" <?php if($hp_options['hpAddShadow'] == "false") { echo "selected='selected'"; } else { echo ""; }?>>False</option>
                                <?php } else { ?>
                                <option value="true" <?php if($arr_settings['hpAddShadow'] == "true") { echo "selected='selected'"; } else { echo ""; } ?>>True</option>
                                <option value="false" <?php if($arr_settings['hpAddShadow'] == "false") { echo "selected='selected'"; } else { echo ""; }?>>False</option>
                                <?php } ?>
                            </select>
                            </td>
                            </tr>
                            
                            <tr valign="top" class="widefat">
                            <td scope="row"><?php _e('Order By', 'hopos-plugin') ?></td>
                            <td>
                            <select style="width:120px;" id="hpOrderby" name="hpOrderby">
                                <?php if ($action == 'add') { ?>
                                <option value="ID" <?php if($hp_options['hpOrderby'] == "ID") { echo "selected='selected'"; } else { echo ""; } ?>>ID</option>
                                <option value="author" <?php if($hp_options['hpOrderby'] == "author") { echo "selected='selected'"; } else { echo ""; }?>>author</option>
                                <option value="title" <?php if($hp_options['hpOrderby'] == "title") { echo "selected='selected'"; } else { echo ""; }?>>title</option>
                                <option value="name" <?php if($hp_options['hpOrderby'] == "name") { echo "selected='selected'"; } else { echo ""; }?>>name</option>
                                <option value="date" <?php if($hp_options['hpOrderby'] == "date") { echo "selected='selected'"; } else { echo ""; }?>>date</option>
                                <option value="modified" <?php if($hp_options['hpOrderby'] == "modified") { echo "selected='selected'"; } else { echo ""; }?>>modified</option>
                                <option value="rand" <?php if($hp_options['hpOrderby'] == "rand") { echo "selected='selected'"; } else { echo ""; }?>>rand</option>
                                <option value="comment_count" <?php if($hp_options['hpOrderby'] == "comment_count") { echo "selected='selected'"; } else { echo ""; }?>>comment_count</option>
                                <?php } else { ?>
                                <option value="ID" <?php if($arr_settings['hpOrderby'] == "ID") { echo "selected='selected'"; } else { echo ""; } ?>>ID</option>
                                <option value="author" <?php if($arr_settings['hpOrderby'] == "author") { echo "selected='selected'"; } else { echo ""; }?>>author</option>
                                <option value="title" <?php if($arr_settings['hpOrderby'] == "title") { echo "selected='selected'"; } else { echo ""; }?>>title</option>
                                <option value="name" <?php if($arr_settings['hpOrderby'] == "name") { echo "selected='selected'"; } else { echo ""; }?>>name</option>
                                <option value="date" <?php if($arr_settings['hpOrderby'] == "date") { echo "selected='selected'"; } else { echo ""; }?>>date</option>
                                <option value="modified" <?php if($arr_settings['hpOrderby'] == "modified") { echo "selected='selected'"; } else { echo ""; }?>>modified</option>
                                <option value="rand" <?php if($arr_settings['hpOrderby'] == "rand") { echo "selected='selected'"; } else { echo ""; }?>>rand</option>
                                <option value="comment_count" <?php if($arr_settings['hpOrderby'] == "comment_count") { echo "selected='selected'"; } else { echo ""; }?>>comment_count</option>
                                <?php } ?>
                            </select>
                            </td>
                            </tr>
                            
                            <tr valign="top" class="widefat alternate">
                            <td scope="row"><?php _e('Order', 'hopos-plugin') ?></td>
                            <td>
                            <select style="width:120px;" id="hpOrder" name="hpOrder">
                                <?php if ($action == 'add') { ?>
                                <option value="DESC" <?php if($hp_options['hpOrder'] == "DESC") { echo "selected='selected'"; } else { echo ""; } ?>>DESC</option>
                                <option value="ASC" <?php if($hp_options['hpOrder'] == "ASC") { echo "selected='selected'"; } else { echo ""; }?>>ASC</option>
                                <?php } else { ?>
                                <option value="DESC" <?php if($arr_settings['hpOrder'] == "DESC") { echo "selected='selected'"; } else { echo ""; } ?>>DESC</option>
                                <option value="ASC" <?php if($arr_settings['hpOrder'] == "ASC") { echo "selected='selected'"; } else { echo ""; }?>>ASC</option>
                                <?php } ?>
                            </select>
                            </td>
                            </tr>
            
                            
                            
                            <tr valign="top" class="widefat" style="border-bottom:#666 solid 2px;">
                            <td scope="row"><?php _e('Skin', 'hopos-plugin') ?></td>
                            <td>
                            <select style="width:140px;" id="hpSkin" name="hpSkin">
                                <?php if ($action == 'add') { ?>
                                <option value="basic" <?php if($hp_options['hpSkin'] == "basic") { echo "selected='selected'"; } else { echo ""; } ?>>Basic</option>
                                <?php } else { ?>
                                <option value="basic" <?php if($arr_settings['hpSkin'] == "basic") { echo "selected='selected'"; } else { echo ""; } ?>>Basic</option>
                                <?php } ?>
                            </select>
                            
                            <?php 
								if ($arr_settings['hpCustomThisSkin'] == 'true' || $arr_settings['hpCustomThisSkin'] == 'on') {
									//echo $arr_settings['hpCustomThisSkin'];
									$opt_value = 'true';
									$opt_checked = ' checked="checked"';
								} else {
									//echo $arr_settings['hpCustomThisSkin'];
									$opt_value = 'false';
									$opt_checked = '';
								}
							?>
                            &nbsp;<span style="display:inline; font-weight:bold; margin-left:10px;"><?php _e('Customising this Skin?', 'hopos-plugin') ?>&nbsp;&nbsp;<input id="hpCustomThisSkin" name="hpCustomThisSkin" value="<?php echo $opt_value; ?>" type="checkbox"<?php echo $opt_checked; ?>></span>
                            
                            <span style="padding:5px; background:#FFC; border:#eee solid 1px; margin-left:25px;">In the <a href="http://codecanyon.net/item/hopos-slider-responsive-wordpress-plugin/4559806" target="_blank">Premium version</a> there are 13 skins available.</span>
                            
                            <div id="hpCustomSkinContainer" style="margin-top:15px;">
                            <p>
                            <?php
								$linkCustomCss = "http://" . $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
								$customCssSplit = explode('page=', $linkCustomCss);
								$linkCustomCss = $customCssSplit[0] . "page=hopos-lite-css-custom"; 
							?>
                            <strong>Note:</strong> See all available classes on the side menu <a href="<?php echo $linkCustomCss; ?>">Hopos > Custom CSS</a>. Replace the word "CUSTOM" for the word that represents the chosen class.<br />
                            For example: If you choose the Skin <strong>Basic</strong>, replace "CUSTOM" by "basic", without the quotes.<br />
                            <em style="color:#03F">Please note that on the lite version is available only the Basic skin</em>
                            <div id="hp_class_info"></div><br />
                            <strong>Add below your custom css</strong>
                            </p>
                            <textarea id="hpCustomSkin" cols="100" rows="15" style="font-size:12px; line-height:14px;"><?php if ($action == 'add') { echo $hp_options['hpCustomSkin']; } else { echo $custom_skin; } ?></textarea>
                            </div>
                            
                            </td>
                            </tr>                
                            
                        </table>
                        
                        <div class="clear"></div>
                        
                        <br><br>
                <a href="<?php echo $urlSplit[0] ?>&action=list">Back to Hopos List</a>
                        
                        <br><br><br>
                    
                    </div><!-- post-body-content / left col -->
                    
                    
                    
                    <div id="postbox-container-1" class="postbox-container" style="margin-top:62px;">
                    
                        <div class="postbox">
                            <h3 class="hndle" style="cursor:auto">
                                <span>Resolution setting - columns</span>
                            </h3>
                            
                            <div class="inside">
                                <table>
                                	<tbody>
                                    	<tr class="field-row">
                                        	<td>
                                            	<label for="scroll-speed">Resolution 960px</label>
                                            </td>
                                        	<td>
                                            	<input id="hpResolution960" type="text" size="3" value="<?php if ($action == 'add') { echo $hp_options['hpResolution960']; } else { echo $arr_settings['hpResolution960']; } ?>">
                                                <span>Columns</span>
                                            </td>
                                        </tr>
                                        
                                        <tr class="field-row">
                                        	<td>
                                            	<label for="scroll-speed">Resolution 768px</label>
                                            </td>
                                        	<td>
                                                <input id="hpResolution768" type="text" size="3" value="<?php if ($action == 'add') { echo $hp_options['hpResolution768']; } else { echo $arr_settings['hpResolution768']; } ?>">
                                                <span>Columns</span>
                                            </td>
                                        </tr>
                                        
                                        <tr class="field-row">
                                        	<td>
                                            	<label for="scroll-speed">Resolution 480px</label>
                                            </td>
                                        	<td>
                                                <input id="hpResolution480" type="text" size="3" value="<?php if ($action == 'add') { echo $hp_options['hpResolution480']; } else { echo $arr_settings['hpResolution480']; } ?>">
                                                <span>Columns</span>
                                            </td>
                                        </tr>
                                        
                                        
                                    </tbody>
                                </table>
                                
                                <p>
                                	NOTE: The resolution settings works associated with the "Posts per Slide" and "Thumbnail Size". Set these items together to get a proper result for all resolutions.
                                </p>
                            </div>
                        </div><!-- postbox -->
                        
                        <div class="postbox">
                            <h3 class="hndle" style="cursor:auto">
                                <span>Publish</span>
                            </h3>
                            
                            <div class="inside">
                                <div class="hpSubmit">
                            		<input type="button" name="save" id="save_hopos_lite" class="button-primary" value="<?php _e('Save Changes', 'hopos-plugin'); ?>" /><div class="hpSaved">Saved</div><div class="hpPreload"></div>
                            		<input type="hidden" id="hp_action" value="<?php echo $action ?>" />
                            		<input type="hidden" id="hp_id" value="<?php if( isset($_REQUEST['id']) ) echo $_REQUEST['id'] ?>" />
                            		<input type="hidden" id="hp_url" value="<?php echo $urlSplit[0] ?>" />
                        		</div>
                            </div>
                        </div><!-- postbox -->
                    
                    </div><!-- .postbox-container -->

                
                </div><!-- post-body -->
                
		</div><!-- poststuf -->
        
         </form>
                
    </div><!-- .wrap add/edit -->
    

<?php
}
?>