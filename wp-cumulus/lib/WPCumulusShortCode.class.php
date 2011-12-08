<?php
/**
*	Copyright, Roy Tanck
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

class WPCumulusShortCode {
// todo fix this
  function wpc_shortcode( $atts, $content = null ){
    // override default settings if specified as shortcode attributes
    $displaysettings = get_option('wpc-displaysettings');
    if( !isset( $displaysettings['trans'] ) ){ $displaysettings['trans'] = false; } // the 'trans' setting (checkbox) cannot be empty for comparison
    $settings = shortcode_atts( $displaysettings, $atts );
    // check against global defaults for all instances
    global $defaultsettings;
    $settings = shortcode_atts( $defaultsettings, $settings );
    // create instance and render
    $cumulus = new WPCumulus( $settings );
    $cumulus->render();
  }
  
  
}


?>
