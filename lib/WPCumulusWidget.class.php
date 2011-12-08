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


/**
 * WPCumulusWidget 
 * 
 * @uses WP_Widget
 * @package WPCumulus 
 * @version 2
 * @copyright Roy Tanck, Bjorn Wijers 
 * @author Roy Tanck <roy.tanck@gmail.com>, Bjorn Wijers <burobjorn@burobjorn.nl> 
 * @license GPL
 */
class WPCumulusWidget extends WP_Widget {
    
  /**
   * defaults 
   * 
   * @var array Will be set with the class defaults
   * @access public
   */
  var $defaults; 
  
  
  /**
   * WPCumulusWidget PHP4 Constructor 
   * 
   * @access public
   * @return void
   */
  function WPCumulusWidget()
  {
    $this->__construct();
  }

  /**
   * WPCumulusWidget PHP5 constructor
   * 
   * @access protected
   * @return void
   */
  function __construct() 
  {
    parent::WP_Widget(false, $name = 'WPCumulusWidget');	 
    $this->setDefaults(); // sets the defaults settings for all instances
  }


  /**
   * setDefaults sets the class defaults 
   * used for every instance
   * 
   * @access public
   * @return void
   * @todo Max groote en min groote van de size in de json tbv tag size
   */
  function setDefaults() 
  {
	  $this->defaults = array(
		  "mode" => "tags",
		  "width" => 250,
		  "height" => 250,
		  "max" => 10,
		  "tcolor" => "000000",
		  "tcolor2" => "666666",
		  "hicolor" => "990000",
		  "bgcolor" => "eeeeee",
		  "trans" => "false",
		  "tspeed" => 100,
		  "distr" => "true",
		  "fontname" => "Helvetica,  Arial",
		  "fontfallback" => "_sans"
	  );
  }


  /**
   * widget 
   * 
   * @param mixed $args 
   * @param mixed $instance 
   * @access public
   * @return void
   */
  function widget( $args, $instance ) 
  {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title']);
    
    if( $title ){ 
      echo $before_title . $title . $after_title; 
    }
    
    $this->setDefaults();
    
    /* Before widget (defined by themes). */
    echo $before_widget;
    		   
    $settings = shortcode_atts( $this->defaults, $instance );
    
    // create instance and render
    require_once('WPCumulus.class.php');
    if( class_exists('WPCumulus') ) {
		  $cumulus = new WPCumulus( $settings );
		  $cumulus->render();
    }
    /* After widget (defined by themes). */
    echo $after_widget;
  }


  function update( $new_instance, $old_instance ) 
  {  
  		$instance = $old_instance;
  		
		$instance['title']   = strip_tags( $new_instance['title'] );
		$instance['mode']    = strip_tags( $new_instance['mode'] );
		$instance['width']   = strip_tags( $new_instance['width'] );
		$instance['height']  = strip_tags( $new_instance['height'] );
		$instance['tcolor']  = strip_tags( $new_instance['tcolor'] );
		$instance['tcolor2'] = strip_tags( $new_instance['tcolor2'] );
		$instance['hicolor'] = strip_tags( $new_instance['hicolor'] );
		$instance['bgcolor'] = strip_tags( $new_instance['bgcolor'] );
		$instance['trans']   = strip_tags( $new_instance['trans'] );
		$instance['max']   = strip_tags( $new_instance['max'] );
		$instance['fontname']   = strip_tags( $new_instance['fontname'] );
		

		return $instance;
  }


  function form( $instance ) 
  {			
  		$this->setDefaults();
  		extract( $this->defaults );
  
   	 	$title   = esc_attr($instance['title']);
		$mode    = esc_attr($instance['mode']);
   	 	$width   = intval(esc_attr($instance['width']));
    	$height  = intval(esc_attr($instance['height']));
    	$tcolor  = esc_attr($instance['tcolor']);
    	$tcolor2 = esc_attr($instance['tcolor2']);
    	$hicolor = esc_attr($instance['hicolor']);
    	$bgcolor = esc_attr($instance['bgcolor']);
    	$trans   = esc_attr($instance['trans']);
    	$max   = esc_attr($instance['max']);
    	$fontname   = esc_attr($instance['fontname']);
    	
    
        ?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
            <p><label for="<?php echo $this->get_field_id('mode'); ?>"><?php _e('Mode:'); ?> 
				<select class="widefat" id="<?php echo $this->get_field_id('mode'); ?>" name="<?php echo $this->get_field_name('mode'); ?>">
					<option value="tags" <?php selected( 'tags', $this->get_field_name('mode') ); ?>>Tags</option>
					<option value="cats" <?php selected( 'cats', $this->get_field_name('mode') ); ?>>Categories</option>
					<option value="both" <?php selected( 'both', $this->get_field_name('mode') ); ?>>Both</option>
				</select>
			</label></p>
			<p><label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Width (pixels):'); ?> <input class="widefat" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo $width; ?>" /></label></p>
            <p><label for="<?php echo $this->get_field_id('height'); ?>"><?php _e('Height (pixels):'); ?> <input class="widefat" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo $height; ?>" /></label></p>
            <p><label for="<?php echo $this->get_field_id('tcolor'); ?>"><?php _e('Tag color (hex values without "#"):'); ?>
				<input class="widefat" style="width: 80px;" id="<?php echo $this->get_field_id('tcolor'); ?>" name="<?php echo $this->get_field_name('tcolor'); ?>" type="text" value="<?php echo $tcolor; ?>" /> to
				<input class="widefat" style="width: 80px;" id="<?php echo $this->get_field_id('tcolor2'); ?>" name="<?php echo $this->get_field_name('tcolor2'); ?>" type="text" value="<?php echo $tcolor2; ?>" />
			</label></p>
            <p><label for="<?php echo $this->get_field_id('hicolor'); ?>"><?php _e('Tag hover color:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('hicolor'); ?>" name="<?php echo $this->get_field_name('hicolor'); ?>" type="text" value="<?php echo $hicolor; ?>" /></label></p>
            <p><label for="<?php echo $this->get_field_id('bgcolor'); ?>"><?php _e('Background color:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('bgcolor'); ?>" name="<?php echo $this->get_field_name('bgcolor'); ?>" type="text" value="<?php echo $bgcolor; ?>" /></label></p>
            <p><label for="<?php echo $this->get_field_id('trans'); ?>"><?php _e('Transparent background'); ?><input class="widefat" id="<?php echo $this->get_field_id('trans'); ?>" name="<?php echo $this->get_field_name('trans'); ?>" type="text" value="<?php echo $trans; ?>" /></label></p>
            <p><label for="<?php echo $this->get_field_id('max'); ?>"><?php _e('Maximum Tags'); ?> <input class="widefat" id="<?php echo $this->get_field_id('max'); ?>" name="<?php echo $this->get_field_name('max'); ?>" type="text" value="<?php echo $max; ?>" /></label></p>
            <p><label for="<?php echo $this->get_field_id('fontname'); ?>"><?php _e('Font Family'); ?> <input class="widefat" id="<?php echo $this->get_field_id('fontname'); ?>" name="<?php echo $this->get_field_name('fontname'); ?>" type="text" value="<?php echo $fontname; ?>" /></label></p>
<?php
 
    }

}
?>
