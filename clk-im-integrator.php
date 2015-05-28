<?php
/**
 * Plugin Name: Clk.im Integrator
 * Plugin URI: clk.im
 * Description: Clk.im Link Shortner And Interstitial Adserver Integration Plugin
 * Version: 1.2
 * Author: Clk.im
 * Author URI: clk.im
 * License: GPL2
 */

# Define text domain
define('CLK_TEXTDOMAIN','clk-im-generator');

# Register Clk.im menu in WP
add_action('admin_menu', 'clk_create_menu') ;

/**
 * Plugin Activation
 */
function clk_activate() {

	update_option( 'selector', 'a' );
	$options = get_option( 'type' );
	$options['site'] = 1;
	update_option('type', $options);

}
register_activation_hook( __FILE__, 'clk_activate' );


/**
 * Load Textdomain
 * 
 * @return void
 */
function clk_load_textdomain() {
    load_plugin_textdomain(CLK_TEXTDOMAIN, FALSE, dirname( plugin_basename(__FILE__) ) . '/lang/' );
}
add_action('plugins_loaded', 'clk_load_textdomain');



/**
 * Set Admin menu
 * 
 * @return void
 */
function clk_create_menu() {
	add_menu_page( __('Clk.im Integration Plugin Settings',CLK_TEXTDOMAIN), __('Clk.im Settings',CLK_TEXTDOMAIN), 'administrator', __FILE__, 'clk_settings_page',plugins_url('link.png', __FILE__));
	add_action( 'admin_init', 'register_clksettings' );
}


/**
 * Register Settings
 * 
 * @return void
 */
function register_clksettings() {

	register_setting( 'clk-settings-group', 'api_key' );
	register_setting( 'clk-settings-group', 'selector' );
	register_setting( 'clk-settings-group', 'type' );
    register_setting( 'clk-settings-group', 'clkim_links_type' );
    register_setting( 'clk-settings-group', 'clkim_specific_domains' );

}

/**
* Admin Settings Page
*/
function clk_settings_page() {
?>
<div class="wrap">
<h2>Clk.im Integration Plugin Settings</h2>

<form method="post" action="options.php">

<script>

jQuery(document).ready(function($){
    $('input[name="clkim_links_type"]').on('click',function(e){
       if ( $(this).val() == 'specific' ) {
           $('tr#tr-specific-domains').show();
       } else {
           $('tr#tr-specific-domains').hide();
       }
    });
});

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
            <th scope="row"><?php echo __('API Key',CLK_TEXTDOMAIN);?> </th>
            <td><input type="text" name="api_key" value="<?php echo esc_attr( get_option('api_key') ); ?>" /><?php echo __('Get this from',CLK_TEXTDOMAIN);?>  <a href="http://clk.im/user">http://clk.im/user</a></td>
        </tr>

        <tr valign="top">
            <th scope="row"><?php echo __('Selector',CLK_TEXTDOMAIN);?></th>
            <td><input type="text" name="selector" value="<?php echo esc_attr( get_option('selector') ); ?>" /> <?php echo __("JQuery Selector to use. Default is 'a' which is all links. Leave as defualt to shorten and track all links.",CLK_TEXTDOMAIN);?>
            </td>
        </tr>

        <?php $options = get_option( 'type' ); ?>
        <tr valign="top">
            <th scope="row"><?php echo __('Types of page to use plugin on',CLK_TEXTDOMAIN);?>:</th>
            <td>
                <table>
                    <tr>
                        <td><input type="checkbox" onClick="toggle(this)" name="type[site]" value="1"<?php checked( isset( $options['site'] ) ); ?> /></td>
                        <td><?php echo __('Entire Site',CLK_TEXTDOMAIN);?></td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" name="type[home]" value="1"<?php checked( isset( $options['home'] ) ); ?> />
                        <td><?php echo __('Home Page',CLK_TEXTDOMAIN);?> </td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" name="type[page]" value="1"<?php checked( isset( $options['page'] ) ); ?> /></td>
                        <td><?php echo __('Pages',CLK_TEXTDOMAIN);?></td>
                    </tr>
                    </tr>
                    <tr>
                        <td><input type="checkbox" name="type[posts]" value="1"<?php checked( isset( $options['posts'] ) ); ?> /> </td>
                        <td><?php echo __('Posts',CLK_TEXTDOMAIN);?> </td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" name="type[category]" value="1"<?php checked( isset( $options['category'] ) ); ?> /></td>
                        <td><?php echo __('Category Pages',CLK_TEXTDOMAIN);?> </td>
                    </tr>
                    <tr>
                        <td> <input type="checkbox" name="type[blog]" value="1"<?php checked( isset( $options['blog'] ) ); ?> /></td>
                        <td><?php echo __('Blog Page',CLK_TEXTDOMAIN);?></td>
                    </tr>
                    <tr>
                        <td> <input type="checkbox" name="type[tag]" value="1"<?php checked( isset( $options['tag'] ) ); ?> /></td>
                        <td><?php echo __('Tag Page',CLK_TEXTDOMAIN);?></td>
                    </tr>
                </table>
            </td>

        </tr>

        <?php $links = get_option( 'clkim_links_type' ); ?>
        <tr valign="top">
            <th scope="row"><?php echo __('Shorten Links',CLK_TEXTDOMAIN);?></th>
            <td>

                <table>
                    <tr>
                        <td><input type="radio" name="clkim_links_type" value="all"<?php checked( isset( $links ) && $links == 'all' ); ?>/></td>
                        <td><?php echo __('All Links',CLK_TEXTDOMAIN);?></td>
                    </tr>
                    <tr>
                        <td><input type="radio" name="clkim_links_type" value="external"<?php checked( isset( $links ) && $links == 'external' ); ?>/></td>
                        <td><?php echo __('External Links',CLK_TEXTDOMAIN);?></td>
                    </tr>
                    <tr>
                        <td><input type="radio" name="clkim_links_type" value="internal"<?php checked( isset( $links ) && $links == 'internal' ); ?>/></td>
                        <td><?php echo __('Internal Links',CLK_TEXTDOMAIN);?></td>
                    </tr>
                    <tr>
                        <td><input type="radio" name="clkim_links_type" value="specific"<?php checked( isset( $links ) && $links == 'specific' ); ?>/></td>
                        <td><?php echo __('Specific Domain Links (comma seperated)',CLK_TEXTDOMAIN);?></td>
                    </tr>
                    <tr id="tr-specific-domains" style="<?= ( isset($links) AND $links == 'specific' ) ? '' : 'display:none;' ?>">
                        <td colspan="2">
                            <textarea name="clkim_specific_domains" id="" cols="30" rows="10" placeholder="example.com,example2.com"><?= get_option('clkim_specific_domains') ?></textarea>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>

    </table>
    
    <?php submit_button(); ?>

</form>

</div>
<?php }


/**
 * Footer code
 * 
 * @return void
 */
function clk_footer() {

    $options = get_option('type');

    $show_code = FALSE;

    if( isset($options['site']) ) {

        # Global
        $show_code = TRUE;

    }
    elseif ( isset($options['home']) && is_home() ) {
        $show_code = TRUE;
    }
    elseif ( isset($options['page']) && is_page() ) {
        $show_code = TRUE;
    }
    elseif ( isset($options['posts']) && is_single() ) {
        $show_code = TRUE;
    }
    elseif ( isset($options['category']) && is_category() ) {
        $show_code = TRUE;
    }
    elseif ( isset($options['blog']) && is_front_page() && is_home() ) {
        $show_code = TRUE;
    }
    elseif( isset($options['tag']) && is_tag() ) {
        $show_code = TRUE;
    }


    if ( $show_code ) {

        $file = plugins_url('/js/clkim.js',__FILE__);

        $selector = get_option('selector');
        $api_key = get_option('api_key');
        $links_type = get_option('clkim_links_type');
        $specific_domains = get_option('clkim_specific_domains');

        echo <<<HTML

            <!-- Clk.im Shortner -->
<script type='text/javascript'>
        var clkim = {
            selector: '{$selector}',
            api: '{$api_key}',
            links_type: '{$links_type}',
            links_domains: '{$specific_domains}'
        }
    </script>
            <script src='{$file}'></script>
            <!-- end Clk.im Shortner -->

HTML;

    }
}

add_action('wp_footer', 'clk_footer');
