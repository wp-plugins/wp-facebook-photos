<?php
	function doWPFBPshortcode($atts) {
		global $wpfbp_options;
		global $wpfbp_fb_options;
		
		if(isset($wpfbp_fb_options['FBappId']) && isset($wpfbp_fb_options['FBsecret'])) {
			require_once("facebookSDK/facebook.php");
		
			$facebook = new Facebook(array(
				'appId'  => $wpfbp_fb_options['FBappId'],
				'secret' => $wpfbp_fb_options['FBsecret'],
				'cookie' => true
			));
		}
		
		extract(shortcode_atts(array(
			"id" => 0
		), $atts));
		
		$retString = "";
		$willFinish = true;
		
		foreach($wpfbp_options as $key => $theOption) {
			if("gallery_".$id == $key) {
				$retString .= '<ul class="facebookGallery">';
				
				$theIDS = explode(',', $theOption);
				$origID = $id;
				
				foreach($theIDS as $id) {
					try {
						$photos = $facebook->api(array(
							'method' => 'fql.query',
							'query' => "SELECT src_height, src_width, src, src_big FROM photo WHERE object_id = ".$id,
						));
						
						foreach($photos as $photo) {
							$ratio = $photo['src_height']/$photo['src_width'];
							
							if($ratio < 1) {
								$marginTop = (130-($ratio*130))/2;
								$retString .= "<li data-photoID='".$id."'><a href='".$photo['src_big']."' rel='fbGallery".$origID."' target='_blank'><img src='".$photo['src_big']."' style='margin-top: ".$marginTop."px;' alt='".$id."'></a></li>";
							}
							else
								$retString .= "<li data-photoID='".$id."'><a href='".$photo['src_big']."' rel='fbGallery".$origID."' target='_blank'><img src='".$photo['src_big']."' alt='".$id."'></a></li>";
						}
					}
					catch(FacebookApiException $e) { $willFinish = false; }
				}
				
				$retString .= '</ul>';
			}
		}
		
		if(!$willFinish)
			$retString = '<p class="facebookErrors">Sorry, we couldn\'t get the Facebook photos at this time. Try refreshing!</p>';
		
		return $retString;
	}

	add_shortcode("wp-facebook-photos", "doWPFBPshortcode"); 
?>