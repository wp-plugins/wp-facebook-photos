=== WordPress Facebook Photos ===
Contributors: cereallarceny
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6TGRAF3RLECQ4
Tags: facebook, photos, gallery
Requires at least: 3.0.1
Tested up to: 3.5.1
Stable tag: 0.4
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Now there's an easy way to embed custom Facebook galleries in your Wordpress installation from your profile or pages!

== Description ==

WordPress Facebook Photos provides an easy way for you to create custom galleries for your WordPress blog.  Simply create a [Facebook app](https://developers.facebook.com/apps), then connect your account and you're ready to go. All of your photos from your Facebook profile as well as all the pages you manage will immediately be available to choose from. After you're done choosing then hit "Create" and you'll be given a shortcode with your gallery inside. You can also embed as many galleries as you want on any page or post.

If you have any comments, questions, or thoughts, put them in the comments [at this blog post](https://www.patrickcason.com/wordpress-facebook-photo-galleries/).

[Check out my website](https://www.patrickcason.com/) too for other projects I'm working on!

== Installation ==

1. Upload `plugin-name.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to Settings -> Facebook Photos
1. Create a [Facebook app](https://developers.facebook.com/apps) and enter in the proper credentials
1. After you plug in the information, log in to Facebook through the Facebook Photos page
1. Begin creating your galleries

== Frequently Asked Questions ==

= I can't view any previous galleries I've created, why? =

That feature is on its way and is planned for the next release!

= How can I include custom styles? =

The point of this plugin, stylistically, was to create a barebones class structure that you can build on accordingly. The way to add styles (currently) is to add them in your theme's stylesheet. In the next release, I plan to allow for you to drop in custom styles of your own.

= What about creating a Javascript lightbox thing? =

In future releases I plan to package a few lightboxes with the plugin itself. However, for now I've created it to work seamlessly with any existing lightbox you have. You'll need to add your own Javascript to get it working, but all the HTML syntax is there. Eventually you'll be able to add any custom Javascript you want as well.

= What about Facebook comments or tags? =

They aren't in this release, they will be in future releases. Truth be told, they weight down load times enough, but they can indeed be implemented.

= Why does my page load so slow? =

The plugin has to get information from Facebook, it could be downloading 200 images from their servers in a matter of seconds. The fact that it happens as quick as it does is somewhat of a miracle. No worries though, I have plans to optimize the front-end of the plugin in the future to allow AJAX loading of photos... this way your page will load and images will then pop in as they're downloaded from Facebook's servers.

== Changelog ==

= 0.4 =
* Re-programmed entire plugin using the Javascript, for asynchronous loading, removed PHP SDK
* Fixed bugs with authentication and loading of images
* Added support for custom CSS
* Added support for custom Javascript

= 0.2 =
* Initial release

== Roadmap ==

* Tags
* Comments
* Built-in lightboxes
* Preview of your gallery
* Reordering of your gallery
* Editing of existing galleries