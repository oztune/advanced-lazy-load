<?php
/*
 * Plugin Name: Advanced lazy load
 * Plugin URI: http://www.lookingimage.com/wordpress-plugin/wordpress-advanced-lazy-load/
 * Description: By using this pulgin, reduce the loading time while opening your wordpress website, accelerate page openning time. Loading image in the last and display base on user screen scrolling.
 * Author: kasonzhao
 * Version: 1.4.2
 * Author URI: http://www.lookingimage.com/wordpress-plugin/wordpress-advanced-lazy-load/
 * License: GPLv2 or later.
 */
 
 
 
/*Call 'LZ_option_link' function to Add a submenu link under Profile tab.*/
add_action('admin_menu', 'LZ_option_link');



/**
 * Function Name: lz_install
 * Description: Save data to database at the time of plugin install.
 *
 */
register_activation_hook( __FILE__, 'lz_install' ); 
function lz_install() {
	$ifd_pixel 		= (get_option('ifd_pixel') != '') ? get_option('ifd_pixel') : '0' ;
	update_option('ifd_pixel', $ifd_pixel);
	
		$ifd_duration 		= (get_option('ifd_duration') != '') ? get_option('ifd_duration') : '1000' ;
	update_option('ifd_duration', $ifd_duration);
}



/**
 * Function Name: LZ_option_link
 * Description: Add a submenu under Settings tab.
 *
 */
function LZ_option_link()
  {
    add_options_page('Advanced lazy load', 'Advanced lazy load', 'manage_options', 'Advanced-lazy-load', 'Advancedlazyload_form');
  }
//the 'Advanced-lazy-load_form' content


function Advancedlazyload_form()
  {
    echo '<h2>Welcome to use Advanced lazy load</h2>';
    echo '<p>By using ths plugin for wordpress, it will reduce the loading time while opening your wordpress website</p>';
    echo '<p>This is initial edition of Advanced lazy load, many features will be added in future versions, compatible with all current browsers.</p>';
	echo '<p>Please visit <a href="http://www.lookingimage.com/wordpress-plugin/wordpress-advanced-lazy-load/" target="_blank" >Advanced lazy load</a> for guidance/questions/news, enjoy! If you want me add you as demo website? Leave your comments! Next strong release will come soon!</p>';
    /**
     * Check whether the form submitted or not.
     */
    if (isset($_POST['option-save']))
      {
        echo "<p><strong>Options saved!</strong></p>";
        $ddsadsa = trim($_POST['ifd_pixel']);
        update_option('ifd_pixel', $ddsadsa);
		
		  $duration = trim($_POST['ifd_duration']);
        update_option('ifd_duration', $duration);
		
		
      }
?>

	<form id="option-form" method="post" name="option-form">
		<table id="aws-option-table">
			<tr>
				<td>Pixel:</td>
				<td><input type="text"  maxlength="10" size="10" name="ifd_pixel" value="<?php
    echo get_option('ifd_pixel');
?>" />px</td>
			</tr>
			
			
			<tr>
				<td>Fading in duration:</td>
				<td><input type="text"  maxlength="10" size="10" name="ifd_duration" value="<?php
    echo get_option('ifd_duration');
?>" />millisecond</td>
			</tr>
			
			<tr>
			
				<td></td>
				<td></td>
			</tr>
		</table>
		<p><input id="option-save" class="button-primary" type="submit" name="option-save" value="Save options"/></p>
	</form>

 <?php
  }


  
if (!is_admin())
  {
  
  		//wp_deregister_script('jquery'); 
		wp_enqueue_script('jquery');
		
    wp_enqueue_script('Advancedlazyload', plugins_url('Advanced_lazyload.js', __FILE__), "", "advacned");
    $ifd_pixel = get_option('ifd_pixel');
	 $duration_advanced = get_option('ifd_duration');
	
  //wp_localize_script('Advancedlazyload', 'ifd_pixel', $ifd_pixel,'duration_advanced',$duration_advanced);
	
	wp_localize_script('Advancedlazyload', 'obj_lz', array(
	 
        'ifd_pixel' => get_option('ifd_pixel'),
        'ifd_duration' => get_option('ifd_duration')
   
));
	
	
	
	
    function Advaced_lazyload($buffer)
      {
        // Change all non-lazy-load images to a different tag
	$ignore_pattern	 = '/(?:\<img(.*nolazy))/';
	$buffer          = preg_replace($ignore_pattern, "<ignorelazy$1", $buffer);

        // Change all images
        $plugin_dir_path = plugin_dir_url(__FILE__);
        $pattern         = '/((?:\<img).*)(src)/';
        $buffer          = preg_replace($pattern, "$1 src='" . $plugin_dir_path . "shade.gif' ImageHolder", $buffer);

        // Change all non-lazy-load images back to image tags
	$buffer          = preg_replace('/(?:\<ignorelazy(.*))/', "<img$1", $buffer);

        return $buffer;
      }
    function DOMReady()
      {
        ob_start("Advaced_lazyload");
      }
 
    add_action('wp_head', 'DOMReady');
 
  }
?>
