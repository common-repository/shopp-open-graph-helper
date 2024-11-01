<?php
/*
Plugin Name: Shopp Open Graph Helper
Description: Prints Open Graph headers for Shopp products.
Author: Tyson LT
Version: 1.5
*/

/**
 * Defaults
 */
global $defaultOptions;
$defaultOptions['shopp-og-helper-show-like-button'] = 1;
$defaultOptions['shopp-og-helper-og-type-value'] = 'product';
$defaultOptions['shopp-og-helper-like-button-code'] = <<<EOD
<!-- Use this if you want a comments field.
<div id="fb-root"></div>
<script src="http://connect.facebook.net/en_US/all.js#appId=<?=get_option('shopp-og-helper-fb-app-id')?>&amp;xfbml=1"></script>
<fb:comments></fb:comments>
-->

<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
<fb:like href="<?=shopp("product","link","return=1")?>"></fb:like>
EOD;

/**
 * Activation hook.
 */
register_activation_hook( __FILE__, 'print_shopp_og_headers_activate' );

/**
 * Some upgrade code.
 */
do_upgrade();

/**
 * Create custom plugin settings menu.
 */ 
add_action('admin_menu', 'shopp_og_helper_create_menu');

/**
 * Main hook.
 */
add_action('wp_head', 'print_shopp_og_headers');

/**
 * Filter to print the FB like button.
 */
add_filter('the_content', 'shopp_og_helper_print_like_button');

/**
 * Prints og headers if a Shopp product page is detected. 
 */
function print_shopp_og_headers() {

  //always print the app_id and admins headers if available		
  print_meta_tag("fb:app_id", get_option('shopp-og-helper-fb-app-id', ''));
  print_meta_tag("fb:admins", get_option('shopp-og-helper-fb-admins', ''));
      
  //print product headers for shopp products
  if (shopp('catalog','is-product')) {

    $pname = trim(shopp('product','name','return=1')); 
    $plink = shopp('product','link','return=1'); 

    //code from Jonathan Davis, http://forums.shopplugin.net/topic/how-to-get-url-of-product-image
    preg_match('/src="(.*?)"/', shopp('product','coverimage','return=1'), $src);
    $pimage = $src[1];

    //get summary, or use description if no summary is set.
    $description = trim(strip_tags(shopp('product','summary','return=1'))); 
    $long_description = trim(strip_tags(shopp('product','description','return=1')));     
    if ('' == $description) {
      $description = $long_description;
    }

    //truncate description to 300
    if (strlen($description) > 300) {
      $description = substr($description, 0, 297) . '...';
    }

    //the header tags
    print_meta_tag("og:title",  $pname );
    print_meta_tag("og:image",  $pimage );
    print_meta_tag("og:url",  $plink );
    print_meta_tag("og:description",  $description );
    print_meta_tag("og:site_name",  get_bloginfo('name') );
   	print_meta_tag("og:type", get_option('shopp-og-helper-og-type-value', ''));

  }

}

/**
 * Echo a meta tag.
 * 
 * Does nothing if value is empty.
 */
function print_meta_tag($key, $value) {
	if ('' != trim($value)) {
		//clean up output of value (charset only specified to allow adding of fourth param)
		$value = htmlspecialchars($value, ENT_COMPAT, 'ISO-8859-1', false);
		echo '<meta property="'.$key.'" content="'. $value .'"/>';
	}
}

/**
 * Print the Like button at the bottom of product pages.
 */
function shopp_og_helper_print_like_button($content) {

  //see if user wants button code printed
  if ('1' == trim(get_option('shopp-og-helper-show-like-button')) && shopp('catalog','is-product')) {

  	//get the button code
    global $defaultOptions;
    $button_code = trim(get_option('shopp-og-helper-like-button-code'));
    if ('' == $button_code) {
      $button_code = $defaultOptions['shopp-og-helper-like-button-code'];
    } 	

    //print the button code with PHP 
    ob_start();
    eval('?>' . $button_code . '<?php ');
    $content .= ob_get_clean();

  }

  return $content;

}

/**
 * Register the admin menu.
 */
function shopp_og_helper_create_menu() {

	//settings page
	add_options_page('Shopp Open Graph Helper', 'Shopp Open Graph', 'administrator', 'shopp-og-helper-settings-page', 'shopp_og_helper_settings_page');
	
	//call register settings function
	add_action( 'admin_init', 'shopp_og_helper_register_settings' );
}

/**
 * Our settings.
 */
function shopp_og_helper_register_settings() {

	//register our settings
	register_setting( 'shopp-og-helper-settings-group', 'shopp-og-helper-og-type-value');
	register_setting( 'shopp-og-helper-settings-group', 'shopp-og-helper-fb-app-id' );
	register_setting( 'shopp-og-helper-settings-group', 'shopp-og-helper-fb-admins' );
	register_setting( 'shopp-og-helper-settings-group', 'shopp-og-helper-show-like-button' );
    register_setting( 'shopp-og-helper-settings-group', 'shopp-og-helper-like-button-code' );

}

/**
 * Activate plugin.
 */
function print_shopp_og_headers_activate() {
	global $defaultOptions;
	foreach ($defaultOptions as $key => $value) {
		if ('' == trim(get_option($key))) {
			add_option($key, $value);
		}
	}	
}

/**
 * Upgrade settings.
 */
function do_upgrade() {
	$val = trim(get_option('shopp-og-helper-og-app-id'));
	if ('' != $val) {
		update_option('shopp-og-helper-fb-app-id', $val);
	}
}

/**
 * Print the admin page.
 */
function shopp_og_helper_settings_page() {
?>
<div class="wrap">
<h2>Shopp Open Graph Helper</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'shopp-og-helper-settings-group' ); ?>

    <table class="form-table">
        <tr valign="top">
            <th scope="row">Open Graph 'Object Type'</th>
    	    <td>
		<select name="shopp-og-helper-og-type-value" id="shopp-og-helper-og-type-value">
			<option value="">(Disabled)</option>
			<option value="album">album</option>
			<option value="book">book</option>
			<option value="drink">drink</option>
			<option value="food">food</option>
			<option value="game">game</option>
			<option value="movie">movie</option>
			<option value="product">product</option>
			<option value="song">song</option>
			<option value="tv_show">tv_show</option>
		</select>
                <script>jQuery("#shopp-og-helper-og-type-value").val('<?=get_option('shopp-og-helper-og-type-value')?>');</script>
	    </td>
	    <td>Set the Open Graph 'Object Type' parameter.<br/>Probably best set to 'product'.</td> 	
        </tr>

        <tr valign="top">
            <th scope="row">Facebook App ID<br/><small>(leave blank to disable)</small></th>
    	    <td><input type="text" name="shopp-og-helper-fb-app-id" value="<?php echo get_option('shopp-og-helper-fb-app-id'); ?>" /></td>
	    <td>Set your Facebook App ID if you have one.</td> 	
        </tr>

        <tr valign="top">
            <th scope="row">Facebook Admins<br/><small>(leave blank to disable)</small></th>
    	    <td><input type="text" name="shopp-og-helper-fb-admins" value="<?php echo get_option('shopp-og-helper-fb-admins'); ?>" /></td>
	    <td>Set your Facebook Admins list if you have one.</td> 	
        </tr>
        
        <tr valign="top">
            <th scope="row">Print Facebook Like Button</th>
    	    <td>
		<label for="shopp-og-helper-show-like-button">

		<input type="checkbox" id="shopp-og-helper-show-like-button" name="shopp-og-helper-show-like-button" value="1" 
		  <?php echo (('1' == get_option('shopp-og-helper-show-like-button')) ? ' checked="checked"' : ''); ?>
                  onclick="document.getElementById('shopp-og-helper-like-button-code').disabled = !this.checked;"
		/>
		Print Facebook 'Like Button' code
		</label>
	    </td>
	    <td>Choose whether to automatically add a 'Like' button to the bottom of Shopp product pages.<!--<br/>The code used to print the Like button is edited below.-->
	    </td> 	
        </tr>

        <tr valign="top">
            <th scope="row">Facebook Like Button code</th>
	    <?php 
		$button_code = trim(get_option('shopp-og-helper-like-button-code'));
		if ('' == $button_code) {
		  global $defaultOptions;
  		  $button_code = $defaultOptions['shopp-og-helper-like-button-code'];
 		}
	    ?>    	    
	  <td colspan="2"><textarea rows=12 cols=75 id="shopp-og-helper-like-button-code" name="shopp-og-helper-like-button-code" <?php echo (('1' != get_option('shopp-og-helper-show-like-button')) ? ' disabled="disabled"' : ''); ?>><?=htmlspecialchars($button_code)?></textarea></td>
        </tr>

    </table>

    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>

</form>
</div>
<?php } ?>