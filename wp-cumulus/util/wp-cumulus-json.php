<?php
if( isset($_GET['max']) && isset($_GET['mode']) ) {
  $max = (int)$_GET['max'];
  $mode = (string)$_GET['mode'];
} else {
  $max = 100;
  $mode = "tags";
}

// max = maximale aantal tags om terug te geven in de json
// mode = tags of cats of both.
// bij mode both dien ik tags en cats samen te nemen en de weging mee te geven om deze in 
// 1 json terug te geven.

//clean input

if (!is_int($max)) $max = 100;
if ($mode != "tags" or $mode != "cats") $mode = "both";



if (!function_exists('add_action'))
	{
        require_once("../../../../wp-config.php");
	}	 

	$args['format'] = 'array';
	$args['number'] = $max;

    // get the tag cloud...
	if($mode == "cats"){	
		 $args['taxonomy'] = 'category'; 
	}
	// get categories
	else if( $mode == "tags" ){
		$args['taxonomy'] = 'post_tag'; 
	}
	else {  // get both
		$args['taxonomy'] = array('post_tag','category');
	}
   
   	
	$tags = '<tags>' .  implode(" \n",wp_tag_cloud($args ))  . '</tags>' ; 	

header ("Content-Type:text/xml");  
echo $tags;
?>


