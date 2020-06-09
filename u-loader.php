<?php
/**
 * @package ULoader
 */
/*
Plugin Name:       U Loader
Description:       A Simple easy-to-use preloader for Wordpress. Just get a cool animation with your logo while loading your site. This plugin is made with HTML, CSS and JS.
Version:           1.0.0
Requires at least: 5.2
Requires PHP:      7.2
Author:            Utpal Barman
Author URI:        https://utpal-barman.github.io/
License:           GPL v2 or later
License URI:       https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:       u-loader
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.


*/

// restrict direct access here
defined( 'ABSPATH' ) or die('Can\t Access on this on');

// load css : funtion
function u_loader_wp_enqueue_scripts(){
    wp_enqueue_style( 'u-loader-style', plugins_url ( 'css/style.css', __FILE__ ) );
}

// load css : action
add_action('wp_enqueue_scripts', 'u_loader_wp_enqueue_scripts');
// ---------------------------------------------------------



// add html to head : function
function u_loader_add_head_div() {
    $options = get_option( 'u_loader_image_url' );

    if ( !isset($options['url']) ) {
        $options['url'] = plugins_url('u-loader/assets/u-loader-default.gif');
    }
    ?>
    <div class="u-loader u-grayscale" style="background: url( <?php print $options['url']; ?> ) center no-repeat #fff;"></div>
    <?php

  }

// add to head : action
add_action('wp_head', 'u_loader_add_head_div');
// ---------------------------------------------------------


// add to footer : funtion
function u_loader_script() {
    ?>
        <script>
            (function ($) {
                $(window).load(function () {
                    $(".u-loader").animate({ zoom: '150%' }).removeClass("u-grayscale").fadeOut("slow");
                });               
            })(jQuery);
        </script>
    <?php

  }

// add to footer : funtion
add_action('wp_footer', 'u_loader_script');
// ---------------------------------------------------------

// 


// plugin action Links

add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'u_loader_plugin_action_links', 10, 5 );

function u_loader_plugin_action_links( $actions, $plugin_file ){
	static $plugin;
	if ( !isset($plugin) ){
		$plugin = plugin_basename(__FILE__);
	}
		
	if ($plugin == $plugin_file) {
		
		if ( is_ssl() ) {
			$settings_link = '<a href="'.admin_url('admin.php?page=u-loader-settings', 'https' ).'">Settings</a>';
		}else{
			$settings_link = '<a href="'.admin_url('admin.php?page=u-loader-settings', 'http' ).'">Settings</a>';
		}
		
		$settings = array($settings_link);
		
		$actions = array_merge($settings, $actions);
			
	}
	return $actions;
}
// ---------------------------------------------------------



// Add to admin menu

add_action('admin_menu','u_loader_menu_options');

function u_loader_menu_options (){
    add_menu_page('U Loader', 'U Loader', 'manage_options', 'u-loader-settings', 'u_loader_settings_view', 'dashicons-image-rotate', 99);

}

function u_loader_settings_view(){
    ?>
    <div class="wrap">
        <h1 style="font-weight: 700"> U Loader Settings </h1>

        <br/>
        <script>
            function getDefaultURL() {
                
                jQuery(".toplevel_page_u-loader-settings .form-table input").val("<?php print plugins_url('u-loader/assets/u-loader-default.gif'); ?>");
                jQuery("#u-loader-preview").attr("src", jQuery(".u-loader-input").val());
            }

            
        </script>
        
        <button class="button-secondary" onclick="getDefaultURL()"> Restore Default </button>
        <br/>
        <br/>
        <hr/>

        <h2>Preview</h2>
        <?php
        $options = get_option( 'u_loader_image_url' );

        if ( !isset($options['url']) ) {
            $options['url'] = plugins_url('u-loader/assets/u-loader-default.gif');
        }
        ?>

        <img src="<?php print $options['url']; ?>" id="u-loader-preview" style="height: 200px; width: 300px; object-fit: contain;"/>


           

        <form action="options.php" method="post">
            <?php 
            // security field
            settings_fields( 'u_loader_settings_group' );

            // output settings section here
            do_settings_sections('u_loader_settings_group');

            
            // save settings button
            submit_button( 'Save Settings' );
            ?>
        </form>
    </div>

    <?php
}
// ---------------------------------------------------------



add_action( 'admin_init', 'u_loader_admin_init' );

function u_loader_admin_init() { 
    register_setting( 'u_loader_settings_group', 'u_loader_image_url' );

    add_settings_section(
        'u_loader_section_id',
        'Insert Your Logo',
        'u_loader_section_callback',
        'u_loader_settings_group'
    );
    
    add_settings_field(
        'url', 
        'Paste a valid icon URL', 
        'u_loader_cb',
        'u_loader_settings_group',
        'u_loader_section_id'
    ); 
}
// ---------------------------------------------------------




function u_loader_section_callback(){
    
}

function u_loader_cb(){
    $options = get_option( 'u_loader_image_url' );

    if ( !isset($options['url']) ) {
        $options['url'] = plugins_url('u-loader/assets/u-loader-default.gif');
    }
    ?>

    <input class="u-loader-input" type='text' name='u_loader_image_url[url]' value='<?php echo $options['url']; ?>'>


    <script>
            jQuery(".u-loader-input").on("input", function () {
                jQuery("#u-loader-preview").attr("src", jQuery(".u-loader-input").val());
            });
            
    </script>
<?php
}
