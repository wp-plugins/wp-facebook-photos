<?php
	function doWPFBPshortcode($atts) {
		global $wpfbp_options;
		global $wpfbp_fb_options;
		global $plugin_dir;
		
		extract(shortcode_atts(array(
			"id" => 0
		), $atts));
		
		$retString .= '<div id="fb-root"></div>';
        $retString .= "<script>initFacebook('".$wpfbp_fb_options['FBappId']."', '".$plugin_dir."/facebookSDK/channel.html"."', true);</script>";

		$retString .= '<style type="text/css">'.$wpfbp_fb_options['customCSS'].'</style>';
		
		foreach($wpfbp_options as $key => $theOption) {
			if("gallery_".$id == $key)
				$retString .= '<ul class="facebookGallery '.$key.'" data-gallery-id="'.$key.'" style="display: none;">'.$theOption.'</ul>';
		}
		
		if($wpfbp_fb_options['customJS'] != "")
			$retString .= '<script>'.$wpfbp_fb_options['customJS'].'</script>';
		
		return $retString;
	}

	add_shortcode("wp-facebook-photos", "doWPFBPshortcode"); 
?>