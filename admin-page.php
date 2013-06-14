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
		
		ob_start(); ?>
        <div class="wrap">
			<div id="fb-root"></div>
            <script>initFacebook(<?php echo "'".$wpfbp_fb_options['FBappId']."'"; ?>, <?php echo "'".$plugin_dir."/facebookSDK/channel.html"."'"; ?>);</script>
            <div class="token hidden"></div>
        	<h2>Wordpress Facebook Photos</h2>
			<form method="post" action="options.php">
            	<?php settings_fields('wpfbp_fb_settings_group'); ?>
     
     			<h3><?php _e('Enter in your Facebook credentials', 'wpfbp_domain'); ?></h3>
                <h4><?php _e('If you have not created an application for your website yet, please do so here: <a href="https://developers.facebook.com/apps/" target="_blank">https://developers.facebook.com/apps/</a>.', 'wpfbp_domain'); ?></h4>
                <p>
                    <label class="description" for="wpfbp_fb_settings[FBappId]"><?php _e('App ID', 'wpfbp_domain'); ?></label>
                    <input id="wpfbp_fb_settings[FBappId]" name="wpfbp_fb_settings[FBappId]" type="text" value="<?php echo $wpfbp_fb_options['FBappId']; ?>"/>
                </p>
                <p>
                    <label class="description" for="wpfbp_fb_settings[FBsecret]"><?php _e('App Secret', 'wpfbp_domain'); ?></label>
                    <input id="wpfbp_fb_settings[FBsecret]" name="wpfbp_fb_settings[FBsecret]" type="text" value="<?php echo $wpfbp_fb_options['FBsecret']; ?>"/>
                </p>
				<?php if($wpfbp_fb_options['FBappId'] == "" && $wpfbp_fb_options['FBsecret'] == "") { ?><div class="hidden"><?php } ?>
				<p>
					<label class="description" for="wpfbp_fb_settings[customCSS]"><?php _e('Custom CSS', 'wpfbp_domain'); ?></label><br>
					<textarea id="wpfbp_fb_settings[customCSS]" class="customCSS" name="wpfbp_fb_settings[customCSS]"><?php if($wpfbp_fb_options['customCSS'] != "") { echo $wpfbp_fb_options['customCSS']; } else { ?>
/* Default CSS */
.facebookGallery { overflow: hidden; list-style-type: none; padding-left: 0px; }
.facebookGallery li { float: left; width: 22.5%; height: 22.5%; margin: 1.25%; }
.facebookGallery a { position: relative; display: block; height: 130px; background: #FFF; padding: 5% 2.5%; }
.facebookGallery a:hover { background: #EEE; }
.facebookGallery img { position: absolute; left: 50%; top: 50%; max-width: 130px; max-height: 130px; }
.facebookErrors { text-align: center; }<?php } ?></textarea>
				</p>
				<p>
					<label class="description" for="wpfbp_fb_settings[customJS]"><?php _e('Custom Javascript', 'wpfbp_domain'); ?></label><br>
					<textarea id="wpfbp_fb_settings[customJS]" class="customJS" name="wpfbp_fb_settings[customJS]"><?php echo $wpfbp_fb_options['customJS']; ?></textarea>
				</p>
				<?php if($wpfbp_fb_options['FBappId'] == "" && $wpfbp_fb_options['FBsecret'] == "") { ?></div><?php } ?>
                <p class="submit">
                    <input type="submit" class="button-primary" value="<?php _e('Save Options', 'wpfbp_domain'); ?>" />
                </p>
            </form>
			<div class="loginButton">
				<fb:login-button size="xlarge" width="400" max-rows="1" scope="manage_pages,user_photos"></fb:login-button>
			</div>
            <?php if($galleryIndex > 0) { ?><div class="updated"><p><b>Last gallery created:</b> [wp-facebook-photos id='<?php echo $galleryIndex-1; ?>']</p></div><?php } ?>
            <h3><?php _e('Create a new gallery from:', 'wpfbp_domain'); ?></h3>
            <select class="albumSelector">
                <option disabled<?php if(!isset($_GET['theID'])) { ?> selected<?php } ?>>Choose a profile...</option>
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