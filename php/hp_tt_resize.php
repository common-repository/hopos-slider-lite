<?php
// ----------------------------------------------------- //
// --- TIMTHUMB --- //
// ----------------------------------------------------- //
if ( !function_exists('hp_tt_function') ) {
	function hp_tt_function($img_h, $img_w, $img_url) {
		$plug = plugin_dir_url(__FILE__);
		$new_url = $plug . "/timthumb.php?src=" . $img_url . "&amp;h=" . $img_h . "&amp;w=" . $img_w . "&amp;zc=1&amp;q=100";
		return $new_url;
	}
}
?>