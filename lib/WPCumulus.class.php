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
 * WPCumulus 
 * 
 * @package WPCumulus 
 * @version 2
 * @copyright Roy Tanck, Bjorn Wijers 
 * @author Roy Tanck <roy.tanck@gmail.com>, Bjorn Wijers <burobjorn@burobjorn.nl> 
 * @license GPL
 */
class WPCumulus {

  
  /**
   * Settings for rendering a wp-cumulus tagcloud 
   * 
   * @var array
   * @access public
   */
  var $settings;

  /**
   * holds the movie's absolute url  
   * 
   * @var string
   * @access public
   */
  var $movie_url;

  /**
   * holds the movie's unique id  
   * 
   * @var string
   * @access public
   */
  var $movie_id;

  /**
   * holds the Flash express install url  
   * 
   * @var string
   * @access public
   */
  var $express_url;



  /**
   * PHP4 compatible constructor 
   */
  function WPCumulus( $settings = null )
  { 
    $this->__construct( $settings);
  }
  
  /**
   * PHP5 Constructor 
   * 
   * @param array $settings 
   * @access protected
   * @return void or int -1 upon error
   */
  function __construct( $settings = null )
  {
    if( is_array($settings) && sizeof($settings) > 0 ) { 
      $this->settings = $settings;
      $this->setClassVars();
    } else { 
      return -1;
    }
  }

  
  /**
   * setClassVars sets some variables we need
   * for the flash renderer 
   * 
   * @access public
   * @return void
   */
  function setClassVars() 
  {
    $this->movie_url   = plugins_url( 'wp-cumulus/swf/tagcloud.swf' );
    $this->movie_id    = "wp-cumulus" . rand(0,9999999);
    $this->express_url =  plugins_url( 'wp-cumulus/flash/expressInstall.swf' );
  } 

	function outPutTagClouds()
	{
	
	return "HELLO WORLD222";
	
	}


  /**
   * renderFlash this will output the necessary html 
   * and javascript and is depended on <a href="http://code.google.com/p/swfobject">SWFObject</a> version
   * 2 or higher.  
   * 
   * @access public
   * @return string Flash Javascript embed code and alternate content
   */
  function renderFlash() 
  {
    //$tags = urlencode('<tags><a href="http://www.nu.nl" style="font-size:24px;">WordPress</a><a href="http://www.nu.nl" style="font-size:12px;">WP-Cumulus</a></tags>');
    //$tags = urlencode('<tags><a href="http://www.tuxebo.co.uk">Tuxebo</a><a href="http://www.tuxebo.com">Tuxebo.com</a></tags>');
    //print implode(", ",$this->settings);
   
    extract($this->settings, EXTR_SKIP);
    $transparency = ($trans == "true" ) ? "transparent" : "opaque";
    
	
    $html .= "<div id=\"$this->movie_id\"><p>$alternate_content</p></div>\n";
    $html .= "<script type=\"text/javascript\">\n";
		$html .= "var flashvars = {\n";
		$html .= " mode: '$mode',\n";
		$html .= " tcolor: '0x$tcolor',\n";
		$html .= " tcolor2: '0x$tcolor2',\n";
    	$html .= " hicolor: '0x$hicolor',\n";
   		$html .= " tspeed: '$tspeed',\n";
    	$html .= " versnr: '$versnr',\n";
		$html .= " distr: '$distr',\n";
    	//$html .= " fontname: '$fontname, $fontfallback',\n";
    	$html .= " fontname: '$fontname',\n";
   		$html .= " max: '$max',\n";
		$html .= " tagcloud: '',\n};\n";
		$html .= "var params = {\n";
		$html .= " menu: 'false',\n";
		$html .= " bgcolor: '#$bgcolor',\n";
		$html .= " allowScriptAccess: 'always'\n";
    $html .= " 	};\n";
    $html .= "var attributes = {\n";
		$html .= " wmode: '$transparency',\n";
		$html .= " id: '$this->movie_id',\n";
		$html .= " name: '$this->movie_id'\n";
		$html .= "};\n";
		$html .= "console.log(flashvars); console.log(params);  console.log(attributes);   \n";
		$html .= "swfobject.embedSWF('$this->movie_url', '$this->movie_id', '$width', '$height', '10.0.0', '$this->express_url', flashvars, params, attributes );";
    $html .= "</script>\n"; 
    echo $html;
  }
  


  /**
   * renderHtml5 is not being used at the moment
   * feel free to surprise us with your implementation :) 
   * 
   * @access public
   * @return void
   * @todo needs implementation
   */
  function renderHtml5() 
  {
    /* somebody pretty please, write this */
  }

	
  /**
   * Render the html needed for the Flash movie
   * using the settings received 
   * 
   * @access public
   * @return string html
   */
  function render( $renderer = null )
  {
    
    switch($renderer) {
    
      case 'flash' :
        return $this->renderFlash();
      break;

      /* make it happen nr 1 */  
      case 'html5' :
        return $this->renderHtml5();
      break;  

      default :
        return $this->renderFlash();
      break;     
   
    }
	}

}
?>
