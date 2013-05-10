<?php
	function wpfbp_options_page() {
		global $wpfbp_options;
		global $wpfbp_fb_options;
		global $plugin_dir;
		
		$galleryIndex = 0;
		foreach($wpfbp_options as $key => $theOption) {
			if(strstr($key, "gallery_") != false)
				$galleryIndex++;
		}
		
		if(isset($wpfbp_fb_options['FBappId']) && isset($wpfbp_fb_options['FBsecret'])) {
			require_once("facebookSDK/facebook.php");
		
			$facebook = new Facebook(array(
				'appId'  => $wpfbp_fb_options['FBappId'],
				'secret' => $wpfbp_fb_options['FBsecret'],
				'cookie' => true
			));
			
			$user = $facebook->getUser();
		
			if($user) {
				try {
					$user_profile = $facebook->api('/me');
				}
				catch(FacebookApiException $e) {
					error_log($e);
					$user = null;
				}
			}
			
			if($user)
				$logoutUrl = $facebook->getLogoutUrl();
			else {
				$params = array(
					'scope' => 'user_photos, manage_pages'
				);
				
				$loginUrl = $facebook->getLoginUrl($params);
			}
		}
		
		ob_start(); ?>
        
        <div class="wrap">
        	<h2>Wordpress Facebook Photos</h2>
            <div id="fb-root"></div>
            <script>initFacebook('<?php echo $facebook->getAppID() ?>', '<?php echo $plugin_dir."/facebookSDK/channel.html"; ?>');</script>
            <div class="token hidden"><?php echo $facebook->getAccessToken(); ?></div>
            <?php if(!isset($wpfbp_fb_options['FBappId']) && !isset($wpfbp_fb_options['FBsecret'])) { ?>
            	<form method="post" action="options.php">
                	<?php settings_fields('wpfbp_fb_settings_group'); ?>
         
         			<h3><?php _e('Enter in your Facebook credentials', 'wpfbp_domain'); ?></h3>
                    <h4><?php _e('If you have not created an application for your website yet, please do so here: <a href="https://developers.facebook.com/apps/" target="_blank">https://developers.facebook.com/apps/</a>', 'wpfbp_domain'); ?></h4>
                    <p>
                        <label class="description" for="wpfbp_fb_settings[FBappId]"><?php _e('App ID', 'wpfbp_domain'); ?></label>
                        <input id="wpfbp_fb_settings[FBappId]" name="wpfbp_fb_settings[FBappId]" type="text" value="<?php echo $wpfbp_fb_options['FBappId']; ?>"/>
                    </p>
                    <p>
                        <label class="description" for="wpfbp_fb_settings[FBsecret]"><?php _e('App Secret', 'wpfbp_domain'); ?></label>
                        <input id="wpfbp_fb_settings[FBsecret]" name="wpfbp_fb_settings[FBsecret]" type="text" value="<?php echo $wpfbp_fb_options['FBsecret']; ?>"/>
                    </p>
                    <p class="submit">
                        <input type="submit" class="button-primary" value="<?php _e('Connect to Facebook App', 'wpfbp_domain'); ?>" />
                    </p>
                </form>
            <?php } else { ?>
            	<?php if(!$user) { ?>
            		<a href="<?php echo $loginUrl; ?>"><img src="<?php echo $plugin_dir; ?>/images/facebook-connect.png" style="width: 150px; margin-top: 20px;" alt="Connect to Facebook"></a>
                <?php } else { ?>
                	<?php if($galleryIndex > 0) { ?><div class="updated"><p><b>Last gallery created:</b> [wp-facebook-photos id='<?php echo $galleryIndex-1; ?>']</p></div><?php } ?>
                    <h3><?php _e('Create a new gallery from:', 'wpfbp_domain'); ?></h3>
                    <select class="albumSelector">
                        <option disabled<?php if(!isset($_GET['theID'])) { ?> selected<?php } ?>></option>
                        <?php
                            try {
                                $me = $facebook->api('/me', 'GET');
								$selected = "";
								
								if($me['id'] == $_GET['theID'])
									$selected = " selected";
                                
                                echo "<option value='".$me['id']."'".$selected.">".$me['name']."</option>";
                            }
                            catch(FacebookApiException $e) { }
                            
                            try {
                                $myPages = $facebook->api('/me/accounts', 'GET');
                                
                                foreach($myPages['data'] as $page) {
									$selected = "";
									
									if($page['id'] == $_GET['theID'])
										$selected = " selected";
										
                                    echo "<option value='".$page['id']."'".$selected.">".$page['name']."</option>";
                                }
                            }
                            catch(FacebookApiException $e) { }
                        ?>
                    </select>
                    <div class="photos"></div>
                    <div class="hidden createGalleryHold postbox">
                    	<h3 class="hndle"><span><?php _e('Wordpress Facebook Photos', 'wpfbp_domain'); ?></span></h3>
                        <p class="selDesel"><a href="#">Select/Deselect All</a></p>
                        <form method="post" class="inside createGallery" action="options.php">
                            <?php settings_fields('wpfbp_settings_group'); ?>
                            <?php
                                foreach($wpfbp_options as $key => $theOption) {
                                    if(strstr($key, "gallery_") != false) {
                                        echo "<input id='wpfbp_settings[".$key."]' name='wpfbp_settings[".$key."]' type='hidden' value='".$theOption."'>";
                                    }
                                }
                            ?>
                            <input id="wpfbp_settings[gallery_<?php echo $galleryIndex; ?>]" class="theNewGallery" name="wpfbp_settings[gallery_<?php echo $galleryIndex; ?>]" type="hidden" value=""/>
                            <p class="submit">
                                <input type="submit" class="button-primary" value="<?php _e('Create New Gallery', 'wpfbp_domain'); ?>" />
                            </p>
                        </form>
                    </div>
                    <div class="loader"></div>
                <?php } ?>
            <?php } ?>
        </div>
        
        <?php echo ob_get_clean();
	}

	function wpfbp_add_admin_menu() {
		add_options_page('Wordpress Facebook Photos', 'Facebook Photos', 'manage_options', 'wp-facebook-photos.php', 'wpfbp_options_page');
	}
	
	add_action('admin_menu', 'wpfbp_add_admin_menu');
	
	function wpfbp_register_settings() {
		register_setting('wpfbp_fb_settings_group', 'wpfbp_fb_settings');
		register_setting('wpfbp_settings_group', 'wpfbp_settings');
	}
	
	add_action('admin_init', 'wpfbp_register_settings');
?>