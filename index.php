<?php

/**
 * @package Scroll_Up_jquery
 * @version 1.0
 */
/*
Plugin Name: Scroll Up jquery
Plugin URI: http://www.sbtechbd.com/
Description: This plugin will add a button that allows users to scroll smoothly to the top of the page.
Author: Subrata Deb nath
Version: 1.0
Author URI: https://subrata6630.github.io/
*/


/* Adding latest jqurey from from wordpress */


define('SUBRATA_SCROLL_PLUGIN_URL', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );

/*load essential script and css*/

function subrata_scroll_register_jquery_scripts() {

	wp_enqueue_script('subrata-scrollUp-up-jquery', SUBRATA_SCROLL_PLUGIN_URL.'js/jquery.scrollUp.js', array('jquery'));
	wp_enqueue_style('subrata-scroll-css', SUBRATA_SCROLL_PLUGIN_URL.'css/style.css');
		
}
add_action('init', 'subrata_scroll_register_jquery_scripts');

// Active scroll to top plugin in here

function scroll_to_top_jquery_active () {?>

	<script type="text/javascript">
	
		<?php global $subrata_scroll_top_options; $ppmscrollbar_settings = get_option( 'subrata_scroll_top_options', $subrata_scroll_top_options ); ?>
		jQuery(document).ready(function(){
			jQuery.scrollUp({
				scrollName: 'scrollUp',      // Element ID
				scrollDistance: <?php echo $ppmscrollbar_settings['scroll_distance']; ?>,         // Distance from top/bottom before showing element (px)
				scrollFrom: 'top',           // 'top' or 'bottom'
				scrollSpeed: <?php echo $ppmscrollbar_settings['scroll_speed']; ?>,            // Speed back to top (ms)
				easingType: 'linear',        // Scroll to top easing (see http://easings.net/)
				animation: 'fade',           // Fade, slide, none
				animationSpeed: 200,         // Animation speed (ms)
				scrollTrigger: false,        // Set a custom triggering element. Can be an HTML string or jQuery object
				scrollTarget: false,         // Set a custom target element for scrolling to. Can be element or number
				scrollText: '', // Text for element, can contain HTML
				scrollTitle: false,          // Set a custom <a> title if required.
				scrollImg: false,            // Set true to use image
				activeOverlay: false,        // Set CSS color to display scrollUp active point, e.g '#00FFFF'
				zIndex: 2147483647           // Z-Index for the overlay
			});
		}); 	
	</script>
<?php
}
add_action('wp_head','scroll_to_top_jquery_active');

// Register a menu for this plugin

function add_scroll_plugin_menu() {  
	add_menu_page('scroll option panel', 'Scroll Top', 'manage_options', 'scroll-panel-option', 'scroll_option_function', $icon_url = 'dashicons-arrow-up-alt', 85 );	
}  
add_action('admin_menu', 'add_scroll_plugin_menu');


// Default options values

$subrata_scroll_top_options = array(
	'scroll_distance' => '300',
	'scroll_speed' => '250'
);

if ( is_admin() ) : // Load only if we are viewing an admin page

function subrata_scroll_register_settings() {
	// Register settings and call sanitation functions
	register_setting( 'scroll_top_options', 'subrata_scroll_top_options', 'scroll_top_validate_options' );
}

add_action( 'admin_init', 'subrata_scroll_register_settings' );


// Function to generate options page

function scroll_option_function() {
	global $subrata_scroll_top_options;

	if ( ! isset( $_REQUEST['updated'] ) )
		$_REQUEST['updated'] = false; // This checks whether the form has just been submitted. ?>

	<div class="wrap">

	
	<h2>Custom Scroll To Top Options</h2>
	
	<?php if( isset($_GET['settings-updated']) ) { ?>
    <div id="message" class="updated">
        <p><strong><?php _e('Options saved.') ?></strong></p>
    </div>
	<?php } ?>
	
	<form method="post" action="options.php">

	<?php $settings = get_option( 'subrata_scroll_top_options', $subrata_scroll_top_options ); ?>
	
	<?php settings_fields( 'scroll_top_options' );
	/* This function outputs some hidden fields required by the form,
	including a nonce, a unique number used to ensure the form has been submitted from the admin page
	and not somewhere else, very important for security */ ?>

	
	<table class="form-table"><!-- Grab a hot cup of coffee, yes we're using tables! -->

		<tr valign="top">
			<th scope="row"><label for="scroll_distance">Distance From Top</label></th>
			<td>
				<input id="scroll_distance" type="text" name="subrata_scroll_top_options[scroll_distance]" value="<?php echo stripslashes($settings['scroll_distance']); ?>" class="my-color-field" /><p class="description">Write Distance from top/bottom before showing element in pixel. Example: 250</p>
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row"><label for="scroll_speed">Scroll Top Speeed</label></th>
			<td>
				<input id="scroll_speed" type="text" name="subrata_scroll_top_options[scroll_speed]" value="<?php echo stripslashes($settings['scroll_speed']); ?>" /><p class="description">Write Speed back to top in milisecond. Example: 500</p>
			</td>
		</tr>
	</table>

	<p class="submit"><input type="submit" class="button-primary" value="Save Options" /></p>

	</form>

	</div>

	<?php
}

function scroll_top_validate_options( $input ) {
	global $subrata_scroll_top_options;

	$settings = get_option( 'subrata_scroll_top_options', $subrata_scroll_top_options );
	
	// We strip all tags from the text field, to avoid vulnerablilties like XSS

	$input['scroll_distance'] = wp_filter_post_kses( $input['scroll_distance'] );
	$input['scroll_speed'] = wp_filter_post_kses( $input['scroll_speed'] );

	return $input;
}

endif;  // EndIf is_admin()

?>