<?php
$hp_general = get_option('hp_general');
$hp_open_hopos_script = $hp_general['hp_open_hopos_script'];
?>
<style>
.widefat th { font-family:Arial, Helvetica, sans-serif; font-size:12px; }
</style>
<div class="wrap" style="padding:0px; margin-top:20px;">

<h2><?php _e('Hopos Slider - General Settings', 'hopos-plugin') ?></h2>
<?php if (isset($_REQUEST['settings-updated'])) echo '<div id="message" class="updated fade"><p><strong>' . 'General Settings' . '&nbsp;-&nbsp;' . __(' Saved.','hopos-plugin') . '</strong></p></div>'; ?>

<form method="post" action="options.php">
	<?php settings_fields( 'hopos-lite-general-settings' ); ?>
	<table class="form-table">
		
		<tr valign="top" class="widefat alternate" style="border-top:#666 solid 2px;">
		<th scope="row"><?php _e('Hopos Script', 'hopos-plugin') ?></th>
		<td>
			<select style="width:150px;" name="hp_general[hp_open_hopos_script]">
				<option value="open_in_header" <?php if($hp_open_hopos_script == "open_in_header") { echo "selected='selected'"; } else { echo ""; } ?>><?php _e('Open in the Header', 'hopos-plugin') ?></option>
				<option value="open_in_footer" <?php if($hp_open_hopos_script == "open_in_footer") { echo "selected='selected'"; } else { echo ""; }?>><?php _e('Open in the Footer', 'hopos-plugin') ?></option>
			</select>
		</td>
		</tr>
        
        <tr valign="top" class="widefat" style="border-bottom:#666 solid 2px;">
            <th scope="row"><?php _e('Enable Hopos Panel in <br>Custom Post Types', 'hopos-plugin') ?></th>
            <td>            
            <?php
			//get public custom post types
			$args = array('public' => true, '_builtin' => false); 
			$nam_obj = 'names'; // names or objects, note names is the default
			$operator = 'and'; // 'and' or 'or'
			$post_types=get_post_types($args,$nam_obj,$operator);
			?>
            
			<?php

			if ($post_types) {
				foreach ($post_types as $ptype) {
					$option = '<input type="checkbox" name="hp_general[hpEnableCustomPostType][]"';
					
					if (isset($hp_general['hpEnableCustomPostType'])) {
						foreach ($hp_general['hpEnableCustomPostType'] as $pt) {
							if($pt == $ptype) {
								$option = $option.' checked="checked"';
							}
						}
					}
					$option .= ' value="' . $ptype . '" />';
					$option .= '&nbsp;' . $ptype . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	
					echo $option;
				}//foreach
			} else {
				echo "<p style='color:blue'>If you are seeing this text, it is because there is no <strong>custom post types</strong> on your site.</p>";
			}

            ?>
            </td>
        </tr>

	</table>
	
	<br>
	
	<p class="submit">
	<input type="submit" name="save" class="button-primary" value="<?php _e('Save Changes', 'hopos-plugin'); ?>" />
	</p>

</form>
</div>