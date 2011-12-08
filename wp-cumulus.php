<?php
/*
Plugin Name: WP-Cumulus
Plugin URI: http://www.roytanck.com
Description: Flash based Tag Cloud for WordPress
Version: 2.0 beta1
Author: <a href="http://www.roytanck.com">Roy Tanck</a>, <a href="http://burobjorn.nl">Bjorn Wijers</a>
Author URI: http://www.roytanck.com
License: GPL
*/


/**
*	Copyright, Roy Tanck <roy.tanck@gmail.com>, Bjorn Wijers <burobjorn@burobjorn.nl>
*
*	This program is free software: you can redistribute it and/or modify
*	it under the terms of the GNU General Public License as published by
*	the Free Software Foundation, either version 3 of the License, or
*	(at your option) any later version.
*
*	This program is distributed in the hope that it will be useful,
*	but WITHOUT ANY WARRANTY; without even the implied warranty of
*	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
*	GNU General Public License for more details.
*
*	You should have received a copy of the GNU General Public License
*	along with this program. If not, see <http://www.gnu.org/licenses/>.
**/

// check for WP context
if ( ! defined('ABSPATH') ){ die('You naughty you'); }


function wp_cumulus_text_domain() {
	if (function_exists('load_plugin_textdomain')) {
		$lang = load_plugin_textdomain('wp-cumulus',false, 'wp-cumulus/languages/');
		}
}
add_action( 'init', 'wp_cumulus_text_domain' );

if( ! class_exists('WPCumulusManager') ) {
  class WPCumulusManager 
  {
    /**
     * wpc 
     * 
     * @var WPCumulus object
     * @access public
     */
    var $wpc = Null;
    
    
    /**
     * PHP 4 Compatible Constructor
    */
    function WPCumulusManager(){ $this->__construct(); }
    
    /**
     * PHP 5 Constructor
    */        
    function __construct()
    {
      // Include the necessary classes 
      require_once('lib/WPCumulus.class.php');
      require_once('lib/WPCumulusWidget.class.php');
      require_once('lib/WPCumulusShortCode.class.php');
      $this->wpc = new WPCumulus();
     
      /* Intialise on first activation */
      register_activation_hook( __FILE__, 'wpc_activate' );

      // enqueue SWFObject
      wp_enqueue_script('swfobject');

      // register WPCumulusWidget widget
      add_action( 'widgets_init', create_function( '', 'register_widget("WPCumulusWidget");' ) );
      
      // register shortcode
      add_shortcode('wp-cumulus', 'wpc_shortcode');//WPCumulusShortCode

      // create custom plugin settings menu
      add_action('admin_menu', array($this, 'create_menu') );

      //add hook to include settings link on plugins page
      $plugin = plugin_basename(__FILE__); 
      add_filter("plugin_action_links_$plugin", array($this, 'settings_link'), 10, 2);
      
      add_filter('widget_text', 'do_shortcode');
      
      //setup translations
      //add_action( 'init', 'cumulus_load_i18n' );
      //load_plugin_textdomain('wp-cumulus', false, basename( dirname( __FILE__ ) ) . '/languages' );
       
    }
    
    

    /**
     * Adds options page for WP Cumulus to the settings
     * called by WP admin_menu action 
     * 
     * @access public
     * @return void
     */
    function create_menu()
    {
	    add_submenu_page( "options-general.php", "WP-Cumulus options", "WP-Cumulus", "manage_options", "wp-cumulus", array($this, "options_page") );
	    add_action( 'admin_init', array($this, 'settings') );
    }


    /**
     * Settings 
     * 
     * @access public
     * @return void
     */
    function settings() 
    {
	    //register settings
	    register_setting( 'wpc-settings-group', 'wpc-displaysettings' );
    }


	function outPutTagCloud()
	{
	
	return "HELLO WORLD";
	
	}

    /**
    * Adds the Settings link to the plugins activate/deactivate page
    * Called by WP filter 'plugin_action_links'
    *
    * @param array links
    * @param file 
    * @return array links
    */
    function settings_link($links, $file) 
    { 
      // If your plugin is under a different top-level menu than 
      // Settiongs (IE - you changed the function above to something other than add_options_page)
      // Then you're going to want to change options-general.php below to the name of your top-level page
      $settings_link = '<a href="options-general.php?page=' . basename(__FILE__) . '">' . __('Settings') . '</a>';
      array_unshift( $links, $settings_link ); // before other links
      return $links;
    }
 


    /**
     * Show the options page 
     * 
     * @access public
     * @return void
     */
    function options_page() 
    {
    
    
      ?>
      <div class="wrap">
        <h2>WP-Cumulus options</h2>
        <form method="post" action="options.php">
          <?php 
            settings_fields( 'wpc-settings-group' );
            $displaysettings = get_option('wpc-displaysettings');
          ?>
          <h3>Cumulus <? _e('Settings'); ?></h3>
          <table class="form-table">
            <tr valign="top">
              <th scope="row"><?php _e('Mode:'); ?></th>
              <td>
                <select name="wpc-displaysettings[mode]">
                  <option value="tags" <?php selected( 'tags', $displaysettings['mode'] ); ?>>Tags</option>
                  <option value="cats" <?php selected( 'cats', $displaysettings['mode'] ); ?>>Categories</option>
                  <option value="both" <?php selected( 'both', $displaysettings['mode'] ); ?>>Both</option>
                </select>
              </td>
            </tr>
            <tr valign="top">
              <th scope="row"><?php _e('Width (pixels):'); ?></th>
              <td><input type="text" name="wpc-displaysettings[width]" value="<?php echo $displaysettings['width']; ?>" /></td>
            </tr>
            <tr valign="top">
              <th scope="row"><?php _e('Height (pixels):'); ?></th>
              <td><input type="text" name="wpc-displaysettings[height]" value="<?php echo $displaysettings['height']; ?>" /></td>
            </tr>
            <tr valign="top">
              <th scope="row"><?php _e('Tag color (hex values without "#"):'); ?></th>
              <td><input type="text" name="wpc-displaysettings[tcolor]" value="<?php echo $displaysettings['tcolor']; ?>" /> ( gradient to <input type="text" name="wpc-displaysettings[tcolor2]" value="<?php echo $displaysettings['tcolor2']; ?>" /> )</td>
            </tr>
            <tr valign="top">
              <th scope="row"><?php _e('Tag hover color:'); ?></th>
              <td><input type="text" name="wpc-displaysettings[hicolor]" value="<?php echo $displaysettings['hicolor']; ?>" /></td>
            </tr>
            <tr valign="top">
              <th scope="row"><?php _e('Background color:'); ?></th>
              <td><input type="text" name="wpc-displaysettings[bgcolor]" value="<?php echo $displaysettings['bgcolor']; ?>" /></td>
            </tr>
            <tr valign="top">
              <th scope="row"><?php _e('Transparent background:'); ?></th>
              <td><input type="checkbox" name="wpc-displaysettings[trans]" value="true" <?php checked( 'true', $displaysettings['trans'] ); ?> /> (set Flash's 'wmode' setting to 'transparent')</td>
            </tr>
            <tr valign="top">
              <th scope="row"><?php _e('Font name:'); ?></th>
              <td>
                <input type="text" name="wpc-displaysettings[fontname]" value="<?php echo $displaysettings['fontname']; ?>" />
                <select name="wpc-displaysettings[fontfallback]">
                  <option value="_sans" <?php selected( '_sans', $displaysettings['fontfallback'] ); ?>>Sans serif</option>
                  <option value="_serif" <?php selected( '_serif', $displaysettings['fontfallback'] ); ?>>Serif</option>
                  <option value="_typewriter" <?php selected( '_typewriter', $displaysettings['fontfallback'] ); ?>>Monospace</option>
                </select>
              </td>
            </tr>
          </table>
          <p class="submit">
            <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
          </p>
        </form>
      </div>
      <?php
    }
  

  }
}

$wpc_manager = new WPCumulusManager();
?>
