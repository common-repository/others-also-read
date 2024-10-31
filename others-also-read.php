<?php
/*
Plugin Name: Others also read
Version:     2.2.1
Plugin URI:  http://ktulu.com.ar/blog/projects/wordpress/others-also-read/
Description: Show a list of posts visitors read after the current post.
Author:      Luis Parravicini
Author URI:  http://ktulu.com.ar/blog
*/

/*  Copyright 2010  Luis Parravicini  (email : lparravi@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if (!defined('ABSPATH')) die("Aren't you supposed to come here via WP-Admin?");
define('OTHERSREAD_DIR', dirname(__FILE__));
define('OTHERSREAD_LOCAL_NAME', 'othersread');

// Pre-2.6 compatibility
if ( ! defined( 'WP_CONTENT_URL' ) )
      define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
if ( ! defined( 'WP_CONTENT_DIR' ) )
      define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
if ( ! defined( 'WP_PLUGIN_URL' ) )
      define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
if ( ! defined( 'WP_PLUGIN_DIR' ) )
      define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );

// Guess the location
$othersread_path = WP_PLUGIN_DIR.'/'.plugin_basename(dirname(__FILE__));
$othersread_url = WP_PLUGIN_URL.'/'.plugin_basename(dirname(__FILE__));

function othersread_init() {
	//* Begin Localization Code */
	$tc_localizationName = OTHERSREAD_LOCAL_NAME;
	$tc_comments_locale = get_locale();
	$tc_comments_mofile = OTHERSREAD_DIR . "/languages/" . $tc_localizationName . "-". $tc_comments_locale.".mo";
	load_textdomain($tc_localizationName, $tc_comments_mofile);
	//* End Localization Code */
}
add_action('init', 'othersread_init');

/*********************************************************************
*				Main Function (Do not edit)							*
********************************************************************/
function othersread() {
	global $wpdb, $post, $single;
	$othersread_settings = othersread_read_options();
	$limit = $othersread_settings['limit'];
	$count = 0;
  $none = true;
	$lpids = get_post_meta($post->ID, 'othersalsoread', true);

	if ($lpids) {
		$output = '<div id="othersread_related">'.$othersread_settings['title'];
	
		$output .= $othersread_settings['before_list'];

		foreach ($lpids as $lpid) {
			$lppost = get_post($lpid);
			if (($lppost->post_type=='page')&&($othersread_settings['exclude_pages'])) continue;
			$count++;
			if ($count > $limit) break;	// exit loop if we cross the max number of iterations
      $none = false;
			$title = trim(stripslashes(get_the_title($lpid)));
			$output .= $othersread_settings['before_list_item'];

			if (($othersread_settings['post_thumb_op']=='inline')||($othersread_settings['post_thumb_op']=='thumbs_only')) {
				$output .= '<a href="'.get_permalink($lpid).'" rel="bookmark">';
				if ((function_exists('has_post_thumbnail')) && (has_post_thumbnail($lpid))) {
					$output .= get_the_post_thumbnail( $lpid, array($othersread_settings[thumb_width],$othersread_settings[thumb_height]), array('title' => $title,'alt' => $title,'class' => 'othersread_thumb'));
				} else {
					$postimage = get_post_meta($lpid, $othersread_settings['thumb_meta'], true);
					if ((!$postimage)&&($othersread_settings['scan_images'])) {
						preg_match_all( '|<img.*?src=[\'"](.*?)[\'"].*?>|i', $lppost->post_content, $matches );
						// any image there?
						if( isset( $matches ) && $matches[1][0] ) {
							$postimage = $matches[1][0]; // we need the first one only!
						}
					}
					if (!$postimage) $postimage = $othersread_settings[thumb_default];
					$output .= '<img src="'.$postimage.'" alt="'.$title.'" title="'.$title.'" width="'.$othersread_settings[thumb_width].'" height="'.$othersread_settings[thumb_height].'" class="othersread_thumb" />';
				}
				$output .= '</a> ';
			}
			if (($othersread_settings['post_thumb_op']=='inline')||($othersread_settings['post_thumb_op']=='text_only')) {
				$output .= '<a href="'.get_permalink($lpid).'" rel="bookmark" class="othersread_title">'.$title.'</a>';
			}
			if ($othersread_settings['show_excerpt']) {
				$output .= '<span class="othersread_excerpt"> '.othersread_excerpt($lppost->post_content,$othersread_settings['excerpt_length']).'</span>';
			}
			$output .= $othersread_settings['after_list_item'];
		}
		$output .= $othersread_settings['after_list'];
		if ($othersread_settings['show_credit']) {
		  $output .= '<span class="othersread_credit">';
			$output .= __('Powered by',OTHERSREAD_LOCAL_NAME);
			$output .= ' <a href="http://ktulu.com.ar/blog/projects/wordpress/others-also-read/">Others also read</a>';
		  $output .= '</span>'; 
		}
		$output .= '</div>';
	}
  if ($none) {
    if (!$othersread_settings['blank_output']) {
      $output = '<div id="othersread_related">';
      $output .= '<p>'.$othersread_settings['blank_output_text'].'</p>'; 
      $output .= '</div>';
    } else
      $output = '';
	}
	
	return $output;
}

// Function that adds othersread code to the post content
add_filter('the_content', 'othersread_content');
function othersread_content($content) {
	global $post, $wpdb, $single, $othersread_url, $whergo_id;
	$othersread_settings = othersread_read_options();
	
	if (($othersread_settings['add_to_feed'])||($othersread_settings['add_to_content'])) $output_list = othersread();	// Get the list
	
	if(is_single() || is_page()) {
		$whergo_id = intval($post->ID);		// Make the $othersread_id global for detection in the footer.
	}
	
    if((is_feed())&&($othersread_settings['add_to_feed'])) {
        return $content.$output_list;
    } elseif(($single)&&($othersread_settings['add_to_content'])) {
        return $content.$output_list;
	} else {
        return $content;
    }
}

// Function to display the list
function echo_othersread() {
	$output = othersread();
	echo $output;
}

// Function to update Where Go count
add_action('wp_footer','add_othersread_count');
function add_othersread_count() {
  #TODO verify if any of this globals are used (this line is copied almost in every function)
	global $post, $wpdb, $single, $whergo_id;
	$othersread_settings = othersread_read_options();
	
  if ($othersread_settings['ignore_auth_users'] && is_user_logged_in())
    return;

  #TODO I think it's unnecessary to track visitors using ajax, check wp docs if it could be done when rendering the post.
	if(is_single() || is_page()) {
		$id = $whergo_id;
?>
		<!-- Start of Where Go JS -->
		<?php wp_print_scripts(array('sack')); ?>
		<script type="text/javascript">
		//<![CDATA[
			others_read_count = new sack("<?php bloginfo( 'wpurl' ); ?>/index.php");    
			others_read_count.setVar( "othersread_id", <?php echo $id ?> );
			others_read_count.setVar( "othersread_sitevar", document.referrer );
			others_read_count.method = 'GET';
			others_read_count.onError = function() { alert('Ajax error' )};
			others_read_count.runAJAX();
			others_read_count = null;
		//]]>
		</script>
		<!-- Start of Where Go JS -->
<?php
	}
}

// Functions to add and read to queryvars
add_action('wp', 'othersread_parse_request');
add_filter('query_vars', 'othersread_query_vars');
function othersread_query_vars($vars) {
	//add these to the list of queryvars that WP gathers
	$vars[] = 'othersread_id';
	$vars[] = 'othersread_sitevar';
	return $vars;
}

function othersread_parse_request($wp) {
   	global $wpdb;
	$othersread_settings = othersread_read_options();
	$maxLinks = $othersread_settings['limit']*2;
	$siteurl = get_option('siteurl');

	//check to see if the page called has 'othersread_id' and 'othersread_sitevar' in the $_GET[] array
    // i.e., if the URL looks like this 'http://example.com/index.php?othersread_id=28&othersread_sitevar=http://somesite.com' 
    if (array_key_exists('othersread_id', $wp->query_vars) && array_key_exists('othersread_sitevar', $wp->query_vars) && $wp->query_vars['othersread_id'] != '') {
		//count the page
		$id = intval($wp->query_vars['othersread_id']);
		$sitevar = attribute_escape($wp->query_vars['othersread_sitevar']);
		Header("content-type: application/x-javascript");
		//...put the rest of your count script here....

		$tempsitevar =  $sitevar;
		$siteurl = str_replace("http://","",$siteurl);
		$siteurls = explode("/",$siteurl);
		$siteurl = $siteurls[0];
		$sitevar = str_replace("/","\/",$sitevar);
		$matchvar = preg_match("/$siteurl/i", $sitevar);
		if (isset($id) && $id > 0 && $matchvar) {
			// Now figure out the ID of the post the author came from, this might be hokey at first
			// Text search within code is your friend!
			$postIDcamefrom = url_to_postid($tempsitevar);
			if ('' != $postIDcamefrom && $id != $postIDcamefrom && '' != $id) {
				$gotmeta = '';
				$linkpostids = get_post_meta($postIDcamefrom, 'othersalsoread', true);
				if ($linkpostids && '' != $linkpostids) {
					$gotmeta = true;
				}
				else {
					$gotmeta = false;
					$linkpostids = array();
				}
				
				if (is_array($linkpostids) && !in_array($id,$linkpostids) && $gotmeta) {
					array_unshift($linkpostids,$id);
				}		
				elseif (is_array($linkpostids) && !$gotmeta)    {
					$linkpostids[0] = $id;
				}

				//Make sure we only keep maxLinks number of links
				if (count($linkpostids) > $maxLinks) {
					$linkpostids = array_slice($linkpostids, 0, $maxLinks);
				}
				$linkpostidsserialized = $linkpostids;
				if ($gotmeta && !empty($linkpostids))
					update_post_meta($postIDcamefrom, 'othersalsoread', $linkpostidsserialized);
				else
					add_post_meta($postIDcamefrom, 'othersalsoread', $linkpostidsserialized);
			}		
		}
			
		
		//stop anything else from loading as it is not needed.
		exit; 
	}else{
		return;
	}
}

// Default Options
function othersread_default_options() {
	global $othersread_url;
	$title = __('<h3>Other also read:</h3>',OTHERSREAD_LOCAL_NAME);
	$blank_output_text = __('Visitors have not browsed from this post. Become the first by continue reading this blog',OTHERSREAD_LOCAL_NAME);
	$thumb_default = $othersread_url.'/default.png';

	$othersread_settings = 	Array (
						'title' => $title,			// Add before the content
						'add_to_content' => true,		// Add related posts to content (only on single pages)
						'add_to_feed' => true,		// Add related posts to feed
						'wg_in_admin' => true,		// Add related posts to feed
						'limit' => '5',				// How many posts to display?
						'show_credit' => false,		// Link to this plugin's page?
						'exclude_pages' => true,		// Exclude pages
						'blank_output' => true,		// Blank output?
						'blank_output_text' => $blank_output_text,	// Text to display in blank output
						'before_list' => '<ul>',			// Before the entire list
						'after_list' => '</ul>',			// After the entire list
						'before_list_item' => '<li>',		// Before each list item
						'after_list_item' => '</li>',		// After each list item
						'post_thumb_op' => 'text_only',	// Display only text in posts
						'thumb_height' => '50',			// Height of thumbnails
						'thumb_width' => '50',			// Width of thumbnails
						'thumb_meta' => 'post-image',		// Meta field that is used to store the location of default thumbnail image
						'thumb_default' => $thumb_default,	// Default thumbnail image
						'scan_images' => false,			// Scan post for images
						'show_excerpt' => false,			// Show description in list item
						'excerpt_length' => '10',		// Length of characters
            'ignore_auth_users' => true // don't count visits by authenticaded users
						);
	return $othersread_settings;
}

// Function to read options from the database
function othersread_read_options() {
	$othersread_settings_changed = false;
	
	//othersread_activate();
	
	$defaults = othersread_default_options();
	
	$othersread_settings = array_map('stripslashes',(array)get_option('othersread_settings'));
	unset($othersread_settings[0]); // produced by the (array) casting when there's nothing in the DB
	
	foreach ($defaults as $k=>$v) {
		if (!isset($othersread_settings[$k]))
			$othersread_settings[$k] = $v;
		$othersread_settings_changed = true;	
	}
	if ($othersread_settings_changed == true)
		update_option('othersread_settings', $othersread_settings);
	
	return $othersread_settings;

}

function othersread_excerpt($content,$excerpt_length){
	$out = strip_tags($content);
	$blah = explode(' ',$out);
	if (!$excerpt_length) $excerpt_length = 10;
	if(count($blah) > $excerpt_length){
		$k = $excerpt_length;
		$use_dotdotdot = 1;
	}else{
		$k = count($blah);
		$use_dotdotdot = 0;
	}
	$excerpt = '';
	for($i=0; $i<$k; $i++){
		$excerpt .= $blah[$i].' ';
	}
	$excerpt .= ($use_dotdotdot) ? '...' : '';
	$out = $excerpt;
	return $out;
}

// This function adds an Options page in WP Admin
if (is_admin() || strstr($_SERVER['PHP_SELF'], 'wp-admin/')) {
	require_once(OTHERSREAD_DIR . "/admin.inc.php");
// Add meta links
function othersread_plugin_actions( $links, $file ) {
	$plugin = plugin_basename(__FILE__);
 
	// create link
	if ($file == $plugin) {
		$links[] = '<a href="' . admin_url( 'options-general.php?page=othersread_options' ) . '">' . __('Settings', OTHERSREAD_LOCAL_NAME ) . '</a>';
# TODO enabled it later
#		$links[] = '<a href="http://paypal.com/blahblah">' . __('Donate', OTHERSREAD_LOCAL_NAME ) . '</a>';
	}
	return $links;
}
global $wp_version;
if ( version_compare( $wp_version, '2.8alpha', '>' ) )
	add_filter( 'plugin_row_meta', 'othersread_plugin_actions', 10, 2 ); // only 2.8 and higher
else add_filter( 'plugin_action_links', 'othersread_plugin_actions', 10, 2 );

}


?>
