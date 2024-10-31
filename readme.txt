=== Others also read ===
Tags: related posts, visitors, browsing, visitors, tracking
Contributors: xrm0, Ajay, Mark Ghosh
Donate link: http://ktulu.com.ar/blog/projects/wordpress/donate/
Stable tag: trunk
Requires at least: 2.9.2
Tested up to: 3.0


Show a list of posts visitors read after the current post.

== Description ==

This plugin will show a list of posts visitors have read after navigating away from the current post.

Based on "Where did they go from here" v1.4.2 by Ajay D'Souza.

= Features =
* Display related posts according to visitors reading habits automatically in content / feed, no need to edit template files
* Tracks visitors movement along your site
* You can manually add the related posts list where you want them displayed
* Exclude pages from the list of posts
* Display post thumbnails in the list. The plugin has support for WordPress 2.9 thumbnails, use of postmeta and also the ability to grab the first image in the post


== Installation ==

1. Download the plugin
2. Extract the contents of others-also-read.zip to wp-content/plugins/ folder. You should get a folder called others-also-read
3. Activate the Plugin in WP-Admin
4. Goto Settings > Others read to configure

== Changelog ==

= 2.2.1 - 2010-07-03 =
* Added a donate link

= 2.2 - 2010-07-02 =
* Changed the Settings page to make it more "wordpressy"
* Inputs for numbers in the Settings page have now a maximum length

= 2.1 - 2010-07-01 =
* bugfix: 'Blank Output' option was not working

= 2.01 - 2010-06-25 =
* Fixed links to the plugin home page

= 2.0 - 2010-06-25 =
* First release of "Others also read". Based on where-did-they-go-from-here v1.4.2. I'll leave the other plugin changelog for historical purposes
* Added an option to avoid tracking a user navigation if it's authenticated
* Changed behaviour: When 'Blank Output' is selected, not even the div is printed
* Bugfix: Fixed error when uninstalling plugin (no global wpdb was used)
* Removed all PHP warnings
* Removed sidebar containing links to other plugins from Ajay in settings page.
* The credit shown (if it's enabled) now shows after the list of posts in a separate span with it's own css class.
* Changed list title to 'Others also read'
* Changed behaviour: 'Show credit' option is now disabled be default

= 1.4.2 =
* Fixed: Languages were not detected properly. Added Italian language

= 1.4.1 =
* Fixed: Minor compatibility issue with other plugins

= 1.4 =
* New: Implementation for tracking hits even on blogs with non-standard WordPress installs
* New: Reset button to reset all browsing data
* New: Option to exclude pages in post list
* New: Choose if you want to blank out display or display a custom message
* New: The plugin extracts the first image in the post and displays that if the post thumbnail and the post-image meta field is missing
* Fixed: Postmeta detection for thumbnails
* Fixed: Compatibility with caching plugins like W3 Total Cache and WP Super Cache
* Some optimisation and code cleaning for better performance

= 1.3.1 =
* Fixed problem where plugin was not tracking visits properly

= 1.3 =
* Added localisation support
* Better support for blogs where wp-content folder has been moved
* Added support for post thumbnails
* Added option to display the post excerpt in the list
* All parts of the list are now wrapped in classes for easy CSS customisation
* Uninstall will clean up the meta tables

= 1.2.1 =
* Fixed compatibility issues with WordPress 2.9

= 1.2 =
* Fixed a bug with posts not being tracked on blogs hosted in a folder

= 1.1 =
* Compatible with caching plugins. Tweaks that should improve tracking.
* Display the list of posts in Edit pages / posts of WP-Admin
* Blanked out display when no related posts are found instead of #N/A

= 1.0 =
* Release


== Frequently Asked Questions ==

= What are the requirements for this plugin? =

I've tested it on WordPress 2.9.2 and 3.0. The plugin it's based on, "Where did they do from here" works on 2.5+ so this plugin may work on earlier versions.

= Can I customize what is displayed? =

All options can be customized within the Options page in WP-Admin itself

You can customise the CSS output. This plugin uses the following CSS classes:
* `othersread_related` in the `div` that surrounds the list items
* `othersread_thumb` is the class that is used for the thumbnail / post image
* `othersread_title` is the class that is used for the title / text
* `othersread_excerpt` is the class that is used for the excerpt
* `othersread_credit` is the class that is used for the credit, when activated

You can add code to your *style.css* file of your theme to style the related posts list.

For more information, please visit http://ktulu.com.ar/blog/projects/wordpress/others-also-read

= Support =

Go to http://ktulu.com.ar/blog/projects/wordpress/others-also-read
