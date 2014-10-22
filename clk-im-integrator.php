<?php
/**
 * Plugin Name: Clk.im Integrator
 * Plugin URI: clk.im
 * Description: Clk.im Link Shortner And Interstitial Adserver Integration Plugin
 * Version: 1.0
 * Author: Clk.im
 * Author URI: clk.im
 * License: GPL2
 */
 
add_action('admin_menu', 'clk_create_menu');


function clk_activate() {
	update_option( 'selector', 'a' );
	$options = get_option( 'type' );
	$options['site'] = 1;
	update_option('type', $options);
}
register_activation_hook( __FILE__, 'clk_activate' );

function clk_create_menu() {


	add_menu_page('Clk.im Integration Plugin Settings', 'Clk.im Settings', 'administrator', __FILE__, 'clk_settings_page',plugins_url('link.png', __FILE__));


	add_action( 'admin_init', 'register_clksettings' );
}


function register_clksettings() {

	register_setting( 'clk-settings-group', 'api_key' );
	register_setting( 'clk-settings-group', 'selector' );
	register_setting( 'clk-settings-group', 'type' );
	
}

function clk_settings_page() {
?>
<div class="wrap">
<h2>Clk.im Integration Plugin Settings</h2>

<form method="post" action="options.php">
<script language="JavaScript">
function toggle(source) {
  checkboxes = document.getElementsByTagName("input");
  for(var i=0, n=checkboxes.length;i<n;i++) {
    checkboxes[i].checked = source.checked;
  }
}
</script>
    <?php settings_fields( 'clk-settings-group' ); ?>
    <?php do_settings_sections( 'clk-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">API Key</th>
        <td><input type="text" name="api_key" value="<?php echo esc_attr( get_option('api_key') ); ?>" /> Get this from <a href="http://clk.im/user">http://clk.im/user</a></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Selector</th>
        <td><input type="text" name="selector" value="<?php echo esc_attr( get_option('selector') ); ?>" /> JQuery Selector to use. Default is 'a' which is all links. Leave as defualt to shorten and track all links.</td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Types of page to use plugin on:</th>
        <td><?php $options = get_option( 'type' ); ?>
				<table>
				<tr>
				<td>Entire Site:</td> <td><input type="checkbox" onClick="toggle(this)" name="type[site]" value="1"<?php checked( isset( $options['site'] ) ); ?> />  </td></tr>
				<td>Home Page: </td> <td><input type="checkbox" name="type[home]" value="1"<?php checked( isset( $options['home'] ) ); ?> /> </td></tr>
				<td>Pages:</td> <td> <input type="checkbox" name="type[page]" value="1"<?php checked( isset( $options['page'] ) ); ?> /> </td></tr>
				<td>Posts: </td> <td><input type="checkbox" name="type[posts]" value="1"<?php checked( isset( $options['posts'] ) ); ?> /> </td></tr>
				<td>Category Pages: </td> <td><input type="checkbox" name="type[category]" value="1"<?php checked( isset( $options['category'] ) ); ?> /></td></tr>
				<td>Blog Page:</td> <td> <input type="checkbox" name="type[blog]" value="1"<?php checked( isset( $options['blog'] ) ); ?> /></td></tr>
				<td>Tag Page:</td> <td> <input type="checkbox" name="type[tag]" value="1"<?php checked( isset( $options['tag'] ) ); ?> /></td></tr>
				</table>
				</td>

        </tr>
    </table>
    
    <?php submit_button(); ?>

</form>

</div>
<?php }

add_action('wp_head', clk_header);

function clk_header() {

	echo "<script src='//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js'></script>
<script src='http://clk.im/urlshortener.js'></script>
";

}

add_action('wp_footer', clk_footer);

function clk_footer() {
$options = get_option('type');
if(isset($options['site'])) {
	echo "<script type='text/javascript'>
	$('".get_option('selector')."').shorten({ 
		url:'http://clk.im',
		key:'".get_option('api_key')."'
	});
</script>";
} if(isset($options['home']) && is_home() ) {
	echo "<script type='text/javascript'>
	$('".get_option('selector')."').shorten({ 
		url:'http://clk.im',
		key:'".get_option('api_key')."'
	});
</script>";
} if(isset($options['page']) && is_page() ) {
	echo "<script type='text/javascript'>
	$('".get_option('selector')."').shorten({ 
		url:'http://clk.im',
		key:'".get_option('api_key')."'
	});
</script>";
} if(isset($options['posts']) && is_single() ) {
	echo "<script type='text/javascript'>
	$('".get_option('selector')."').shorten({ 
		url:'http://clk.im',
		key:'".get_option('api_key')."'
	});
</script>";
} if(isset($options['category']) && is_category() ) {
	echo "<script type='text/javascript'>
	$('".get_option('selector')."').shorten({ 
		url:'http://clk.im',
		key:'".get_option('api_key')."'
	});
</script>";
} if(isset($options['blog']) && is_front_page() && is_home() ) {
	echo "<script type='text/javascript'>
	$('".get_option('selector')."').shorten({ 
		url:'http://clk.im',
		key:'".get_option('api_key')."'
	});
</script>";
}

if(isset($options['tag']) && is_tag() ) {
	echo "<script type='text/javascript'>
	$('".get_option('selector')."').shorten({ 
		url:'http://clk.im',
		key:'".get_option('api_key')."'
	});
</script>";
}

}

?>