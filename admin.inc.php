<?php
/**********************************************************************
*					Admin Page										*
*********************************************************************/
function othersread_options() {
	
	global $wpdb;
    $poststable = $wpdb->posts;

	$othersread_settings = othersread_read_options();

	if(isset($_POST['othersread_save'])) {
    //TODO refactor this. could use a loop
		$othersread_settings['title'] = ($_POST['title']);
		$othersread_settings['limit'] = intval($_POST['limit']);
		$othersread_settings['add_to_content'] = isset($_POST['add_to_content']);
		$othersread_settings['add_to_feed'] = isset($_POST['add_to_feed']);
		$othersread_settings['wg_in_admin'] = isset($_POST['wg_in_admin']);
		$othersread_settings['show_credit'] = isset($_POST['show_credit']);
		$othersread_settings['exclude_pages'] = isset($_POST['exclude_pages']);
		$othersread_settings['blank_output'] = ($_POST['blank_output'] == 'blank');
		$othersread_settings['blank_output_text'] = $_POST['blank_output_text'];

		$othersread_settings['post_thumb_op'] = $_POST['post_thumb_op'];
		$othersread_settings['before_list'] = $_POST['before_list'];
		$othersread_settings['after_list'] = $_POST['after_list'];
		$othersread_settings['before_list_item'] = $_POST['before_list_item'];
		$othersread_settings['after_list_item'] = $_POST['after_list_item'];
		$othersread_settings['thumb_meta'] = $_POST['thumb_meta'];
		$othersread_settings['thumb_default'] = $_POST['thumb_default'];
		$othersread_settings['thumb_height'] = intval($_POST['thumb_height']);
		$othersread_settings['thumb_width'] = intval($_POST['thumb_width']);
		$othersread_settings['scan_images'] = isset($_POST['scan_images']);
		$othersread_settings['show_excerpt'] = isset($_POST['show_excerpt']);
		$othersread_settings['excerpt_length'] = intval($_POST['excerpt_length']);
		$othersread_settings['ignore_auth_users'] = isset($_POST['ignore_auth_users']);

		update_option('othersread_settings', $othersread_settings);
		
		$str = '<div id="message" class="updated fade"><p>'. __('Options saved successfully.',OTHERSREAD_LOCAL_NAME) .'</p></div>';
		echo $str;
	}
	
	if (isset($_POST['othersread_default'])) {
		delete_option('othersread_settings');
		$othersread_settings = othersread_default_options();
		update_option('othersread_settings', $othersread_settings);
		
		$str = '<div id="message" class="updated fade"><p>'. __('Options set to Default.',OTHERSREAD_LOCAL_NAME) .'</p></div>';
		echo $str;
	}

	if (isset($_POST['othersread_reset'])) {
		// Delete meta
		$str = '<div id="message" class="updated fade"><p>'. __('All visitor browsing data captured by the plugin has been deleted!',OTHERSREAD_LOCAL_NAME) .'</p></div>';
		$sql = "DELETE FROM ".$wpdb->postmeta." WHERE `meta_key` = 'othersalsoread'";
		$wpdb->query($sql);
	
		echo $str;
	}
?>

<div class="wrap">
  <h2>Others also read</h2>
  <div id="options-div">
    <p><em>Like Others also read? Help support it by <a href="http://ktulu.com.ar/blog/projects/wordpress/donate/">donating to the developer</a>. This helps cover the cost of maintaining the plugin and development time toward new features. Every donation, no matter how small, is appreciated.</em></p>
  <form method="post" id="othersread_options" name="othersread_options">
    <fieldset class="options">
    <legend>
    <h3>
      <?php _e('Options:',OTHERSREAD_LOCAL_NAME); ?>
    </h3>
    </legend>
    <p>
      <label>
      <?php _e('Number of posts to display: ',OTHERSREAD_LOCAL_NAME); ?>
      <input type="textbox" name="limit" id="limit" value="<?php echo stripslashes($othersread_settings['limit']); ?>" size="3" maxlength="3">
      </label>
    </p>
    <p>
      <label>
      <input type="checkbox" name="add_to_content" id="add_to_content" <?php if ($othersread_settings['add_to_content']) echo 'checked="checked"' ?> />
      <?php _e('Add list of posts to the post content on single posts. <br />If you choose to disable this, please add <code>&lt;?php if(function_exists(\'echo_othersread\')) echo_othersread(); ?&gt;</code> to your template file where you want it displayed',OTHERSREAD_LOCAL_NAME); ?>
      </label>
    </p>
    <p>
      <label>
      <input type="checkbox" name="add_to_feed" id="add_to_feed" <?php if ($othersread_settings['add_to_feed']) echo 'checked="checked"' ?> />
      <?php _e('Add list of posts to feed',OTHERSREAD_LOCAL_NAME); ?>
      </label>
    </p>
    <p>
      <label>
      <input type="checkbox" name="wg_in_admin" id="wg_in_admin" <?php if ($othersread_settings['wg_in_admin']) echo 'checked="checked"' ?> />
      <?php _e('Display list of posts in Edit Posts / Pages',OTHERSREAD_LOCAL_NAME); ?>
      </label>
    </p>
    <p>
      <label>
      <input type="checkbox" name="ignore_auth_users" id="ignore_auth_users" <?php if ($othersread_settings['ignore_auth_users']) echo 'checked="checked"' ?> />
      <?php _e("Ignore authenticated users when counting visits",OTHERSREAD_LOCAL_NAME); ?>
      </label>
    </p>
    <p>
      <label>
      <input type="checkbox" name="show_credit" id="show_credit" <?php if ($othersread_settings['show_credit']) echo 'checked="checked"' ?> />
      <?php _e('Append link to this plugin as item. Optional, but would be nice to give me some link love',OTHERSREAD_LOCAL_NAME); ?>
      </label>
    </p>
    <h4>
      <?php _e('Output Options:',OTHERSREAD_LOCAL_NAME); ?>
    </h4>
    <p>
      <label>
      <?php _e('Title of posts: ',OTHERSREAD_LOCAL_NAME); ?>
      <input type="textbox" name="title" id="title" value="<?php echo stripslashes($othersread_settings['title']); ?>">
      </label>
    </p>
    <p>
      <label>
      <input type="checkbox" name="exclude_pages" id="exclude_pages" <?php if ($othersread_settings['exclude_pages']) echo 'checked="checked"' ?> />
      <?php _e('Exclude pages from the post list',OTHERSREAD_LOCAL_NAME); ?>
      </label>
    </p>
    <p>
      <label>
      <input type="checkbox" name="show_excerpt" id="show_excerpt" <?php if ($othersread_settings['show_excerpt']) echo 'checked="checked"' ?> />
      <strong><?php _e('Show post excerpt in list?',OTHERSREAD_LOCAL_NAME); ?></strong>
      </label>
    </p>
    <p>
      <label>
      <?php _e('Length of excerpt (in words): ',OTHERSREAD_LOCAL_NAME); ?>
      <input type="textbox" name="excerpt_length" id="excerpt_length" value="<?php echo stripslashes($othersread_settings['excerpt_length']); ?>" size="5" maxlength="5">
      </label>
    </p>
	<h4><?php _e('Customize the output:',OTHERSREAD_LOCAL_NAME); ?></h4>
	<p>
      <label>
      <?php _e('HTML to display before the list of posts: ',OTHERSREAD_LOCAL_NAME); ?>
      <input type="textbox" name="before_list" id="before_list" value="<?php echo attribute_escape(stripslashes($othersread_settings['before_list'])); ?>">
      </label>
	</p>
	<p>
      <label>
      <?php _e('HTML to display before each list item: ',OTHERSREAD_LOCAL_NAME); ?>
      <input type="textbox" name="before_list_item" id="before_list_item" value="<?php echo attribute_escape(stripslashes($othersread_settings['before_list_item'])); ?>">
      </label>
	</p>
	<p>
      <label>
      <?php _e('HTML to display after each list item: ',OTHERSREAD_LOCAL_NAME); ?>
      <input type="textbox" name="after_list_item" id="after_list_item" value="<?php echo attribute_escape(stripslashes($othersread_settings['after_list_item'])); ?>">
      </label>
	</p>
	<p>
      <label>
      <?php _e('HTML to display after the list of posts: ',OTHERSREAD_LOCAL_NAME); ?>
      <input type="textbox" name="after_list" id="after_list" value="<?php echo attribute_escape(stripslashes($othersread_settings['after_list'])); ?>">
      </label>
	</p>
	<p><strong><?php _e('When there are no posts, what should be shown?',OTHERSREAD_LOCAL_NAME); ?></strong><br />
		<label>
		<input type="radio" name="blank_output" value="blank" id="blank_output_0" <?php if ($othersread_settings['blank_output']) echo 'checked="checked"' ?> />
		<?php _e('Blank Output',OTHERSREAD_LOCAL_NAME); ?></label>
		<br />
		<label>
		<input type="radio" name="blank_output" value="noposts" id="blank_output_1" <?php if (!$othersread_settings['blank_output']) echo 'checked="checked"' ?> />
		<?php _e('Display custom text: ',OTHERSREAD_LOCAL_NAME); ?></label><br />
		<textarea name="blank_output_text" id="blank_output_text" cols="50" rows="5"><?php echo htmlspecialchars(stripslashes($othersread_settings['blank_output_text'])); ?></textarea>
		<br />
	</p>
	<h4><?php _e('Post thumbnail options:',OTHERSREAD_LOCAL_NAME); ?></h4>
	<p>
		<label>
		<input type="radio" name="post_thumb_op" value="inline" id="post_thumb_op_0" <?php if ($othersread_settings['post_thumb_op']=='inline') echo 'checked="checked"' ?> />
		<?php _e('Display thumbnails inline with posts',OTHERSREAD_LOCAL_NAME); ?></label>
		<br />
		<label>
		<input type="radio" name="post_thumb_op" value="thumbs_only" id="post_thumb_op_1" <?php if ($othersread_settings['post_thumb_op']=='thumbs_only') echo 'checked="checked"' ?> />
		<?php _e('Display only thumbnails, no text',OTHERSREAD_LOCAL_NAME); ?></label>
		<br />
		<label>
		<input type="radio" name="post_thumb_op" value="text_only" id="post_thumb_op_2" <?php if ($othersread_settings['post_thumb_op']=='text_only') echo 'checked="checked"' ?> />
		<?php _e('Do not display thumbnails, only text.',OTHERSREAD_LOCAL_NAME); ?></label>
		<br />
	</p>
    <p>
      <label>
      <?php _e('Post thumbnail meta field (the meta should point contain the image source): ',OTHERSREAD_LOCAL_NAME); ?>
      <input type="textbox" name="thumb_meta" id="thumb_meta" value="<?php echo attribute_escape(stripslashes($othersread_settings['thumb_meta'])); ?>">
      </label>
    </p>
    <p>
      <label>
      <input type="checkbox" name="scan_images" id="scan_images" <?php if ($othersread_settings['scan_images']) echo 'checked="checked"' ?> />
      <?php _e('If the postmeta is not set, then should the plugin extract the first image from the post. This can slow down the loading of your post if the first image in the related posts is large in file-size',OTHERSREAD_LOCAL_NAME); ?>
      </label>
    </p>
    <p><strong><?php _e('Thumbnail dimensions:',OTHERSREAD_LOCAL_NAME); ?></strong><br />
      <label>
      <?php _e('Max width: ',OTHERSREAD_LOCAL_NAME); ?>
      <input type="textbox" name="thumb_width" id="thumb_width" value="<?php echo attribute_escape(stripslashes($othersread_settings['thumb_width'])); ?>" size="3" maxlength="3">px
      </label>
	  <br />
      <label>
      <?php _e('Max height: ',OTHERSREAD_LOCAL_NAME); ?>
      <input type="textbox" name="thumb_height" id="thumb_height" value="<?php echo attribute_escape(stripslashes($othersread_settings['thumb_height'])); ?>" size="3" maxlength="3">px
      </label>
    </p>
	<p><?php _e('The plugin will first check if the post contains a thumbnail. If it doesn\'t then it will check the meta field. If this is not available, then it will show the default image as specified below:',OTHERSREAD_LOCAL_NAME); ?>
	<input type="textbox" name="thumb_default" id="thumb_default" value="<?php echo attribute_escape(stripslashes($othersread_settings['thumb_default'])); ?>" style="width:500px">
	</p>
    <p>
      <input type="submit" name="othersread_save" class="button" id="othersread_save" value="Save Options"/>
      <input name="othersread_default" type="submit" id="othersread_default" value="Default Options" class="button" onclick="if (!confirm('<?php _e('Do you want to set options to Default?',OTHERSREAD_LOCAL_NAME); ?>')) return false;" />
    </p>
    <p><?php _e('Reset all content? This will purge WordPress of all visitor browsing information captured by this plugin. There is no going back if you hit the button.',OTHERSREAD_LOCAL_NAME); ?><br />
      <input name="othersread_reset" type="submit" id="othersread_reset" value="Reset browsing data" class="button" onclick="if (!confirm('<?php _e('This will delete all user data',OTHERSREAD_LOCAL_NAME); ?>')) return false;" />
    </p>
    </fieldset>
  </form>
  </div>

<!-- TODO enable it later
	<div class="side-widget">
		<span class="title"><?php _e('Support the development',OTHERSREAD_LOCAL_NAME) ?></span>
		<div id="donate-form">
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
			<input type="hidden" name="cmd" value="_xclick">
			<input type="hidden" name="business" value="">
			<input type="hidden" name="lc" value="IN">
			<input type="hidden" name="item_name" value="Donation for 'Others also read'">
			<input type="hidden" name="item_number" value="whergo">
			<strong><?php _e('Enter amount in USD: ',OTHERSREAD_LOCAL_NAME) ?></strong> <input name="amount" value="10.00" size="6" type="text"><br />
			<input type="hidden" name="currency_code" value="USD">
			<input type="hidden" name="button_subtype" value="services">
			<input type="hidden" name="bn" value="PP-BuyNowBF:btn_donate_LG.gif:NonHosted">
			<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="<?php _e('Send your donation to the author of',OTHERSREAD_LOCAL_NAME) ?> Others also read">
			<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
			</form>
		</div>
	</div>
-->
  
</div>
<?php

}

function othersread_reset() {
	global $wpdb;

	// Delete meta
	$allposts = get_posts('numberposts=0&post_type=post&post_status=');
	foreach( $allposts as $postinfo) {
		delete_post_meta($postinfo->ID, 'othersalsoread');
	}
	$allposts = get_posts('numberposts=0&post_type=page&post_status=');
	foreach( $allposts as $postinfo) {
		delete_post_meta($postinfo->ID, 'othersalsoread');
	}


}

function othersread_adminmenu() {
	if (function_exists('current_user_can')) {
		// In WordPress 2.x
		if (current_user_can('manage_options')) {
			$othersread_is_admin = true;
		}
	} else {
		// In WordPress 1.x
		global $user_ID;
		if (user_can_edit_user($user_ID, 0)) {
			$othersread_is_admin = true;
		}
	}

	if ((function_exists('add_options_page'))&&($othersread_is_admin)) {
		$plugin_page = add_options_page("Others also read","Others read", 9, 'othersread_options', 'othersread_options');
		add_action( 'admin_head-'. $plugin_page, 'othersread_adminhead' );
		}
}
add_action('admin_menu', 'othersread_adminmenu');

function othersread_adminhead() {
	global $othersread_url;

	echo '<link rel="stylesheet" type="text/css" href="'.$othersread_url.'/admin-styles.css" />';
}

/* Display page views on the Edit Posts / Pages screen */
// Add an extra column
function othersread_column($cols) {
	$othersread_settings = othersread_read_options();
	
	if ($othersread_settings['wg_in_admin'])	$cols['othersread'] = 'Others read';
	return $cols;
}

// Display page views for each column
function othersread_value($column_name, $id) {
	$othersread_settings = othersread_read_options();
	if (($column_name == 'othersread')&&($othersread_settings['wg_in_admin'])) {
		global $wpdb, $post, $single;
		$limit = $othersread_settings['limit'];
		$lpids = get_post_meta($post->ID, 'othersalsoread', true);

		if ($lpids) {
			foreach ($lpids as $lpid) {
				$output .= '<a href="'.get_permalink($lpid).'" title="'.get_the_title($lpid).'">'.$lpid.'</a>, ';
			}
		} else {
			$output = 'None';
		}
		

		echo $output;
	}
}

// Output CSS for width of new column
function othersread_css() {
?>
<style type="text/css">
	#othersread { width: 50px; }
</style>
<?php	
}

// Actions/Filters for various tables and the css output
add_filter('manage_posts_columns', 'othersread_column');
add_action('manage_posts_custom_column', 'othersread_value', 10, 2);
add_filter('manage_pages_columns', 'othersread_column');
add_action('manage_pages_custom_column', 'othersread_value', 10, 2);
add_filter('manage_media_columns', 'othersread_column');
add_action('manage_media_custom_column', 'othersread_value', 10, 2);
add_filter('manage_link-manager_columns', 'othersread_column');
add_action('manage_link_custom_column', 'othersread_value', 10, 2);
add_action('admin_head', 'othersread_css');


?>
