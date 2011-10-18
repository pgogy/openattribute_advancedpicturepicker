<?PHP

	/*
	Plugin Name: Advanced Picture finder
	Plugin URI: http://openattribute.com
	Description: Adds the ability to search for Flickr content, bring it bak into WordPress and attribute it.
	Version: 0.9
	Author: Pat Lockley
	Author URI: http://politicsinspires.org
	*/

	function add_advanced_picture_finder_action() {
	
		global $wpdb;
	
		$url = WP_PLUGIN_URL . '/' . str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
  
  		$details = wp_upload_dir();
  		
  		$home_url = get_bloginfo("siteurl");
  		  		
  		$path = $details['path'];
  		
  		$url_of_site = $details['url'];
  		
  		$short_dir = $details['subdir'];
  		
  		?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
			<html xmlns="http://www.w3.org/1999/xhtml"  dir="ltr" lang="en-US"> 
			<head> 
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 			
			<link rel='stylesheet' href='<?PHP echo $home_url; ?>/wp-admin/load-styles.php?c=1&amp;dir=ltr&amp;load=global,wp-admin,media&amp;ver=a5819c5a976216f1c44e5e55418aee0b' type='text/css' media='all' /> 
			<link rel='stylesheet' id='imgareaselect-css'  href='<?PHP echo $home_url; ?>/wp-includes/js/imgareaselect/imgareaselect.css?ver=0.9.1' type='text/css' media='all' /> 
			<link rel='stylesheet' id='colors-css'  href='<?PHP echo $home_url; ?>/wp-admin/css/colors-fresh.css?ver=20100610' type='text/css' media='all' /> 
			<!--[if lte IE 7]>
			<link rel='stylesheet' id='ie-css'  href='<?PHP echo $home_url; ?>/wp-admin/css/ie.css?ver=20100610' type='text/css' media='all' />
			<![endif]-->			
			<script type='text/javascript' src='<?PHP echo $home_url; ?>/wp-admin/load-scripts.php?c=1&amp;load=jquery,utils,swfupload-all,swfupload-handlers,json2&amp;ver=733749dfc00359ca23b46b29e2f304d2'></script> 
			</head> 
			<body id="media-upload"> 
			<form class="media-upload-form type-form validate" action="<?PHP echo $url; ?>advanced_picture_finder.php" method="POST">
				<h3 class="media-title">
					Triton - Picture search and attribution
				</h3>
				<p>
					Enter a search term in the box below and then click on 'search for pictures'
				</p>
				<?PHP

					$current_user = wp_get_current_user();
					
					$search_total = get_user_meta($current_user->ID, "advanced_picture_picker_pictures_total", true);
					$search_term = get_user_meta($current_user->ID, "advanced_picture_picker_search_term", true);
					$content_type = get_user_meta($current_user->ID, "advanced_picture_picker_content_type", true);
					$commons = get_user_meta($current_user->ID, "advanced_picture_picker_pictures_commons", true);		
					
					$licenses = explode(",",get_user_meta($current_user->ID, "advanced_picture_picker_pictures_licenses", true));
				
				?>
				<?PHP
								
					wp_nonce_field('picture_finder_nonce','picture_finder_nonce_form_name');
				
				?>
				<input type="text" size="120" name="search_term" value="<?PHP if(isset($search_term)){ echo $search_term; }else{ echo "Please enter your search term";} ?>" />
				<p>Number of pictures to return</p>
				<input type="text" size="60" name="pictures_return" value="<?PHP if(isset($search_total)){ echo $search_total; }else{ echo "Please enter the number of pictures to be returned"; } ?>" />
				<p>Licenses to search within</p>
				<input type="checkbox" value="4" <?PHP if(in_array("4",$licenses)){ echo "checked"; } ?> name="attribution" /> Attribution License - http://creativecommons.org/licenses/by/2.0/ <br />
				<input type="checkbox" value="6" <?PHP if(in_array("6",$licenses)){ echo "checked"; } ?> name="attribution-noderivs" /> Attribution-NoDerivs License - http://creativecommons.org/licenses/by-nd/2.0/ <br />
				<input type="checkbox" value="3" <?PHP if(in_array("3",$licenses)){ echo "checked"; } ?> name="attribution-noncommercial-noderivs" /> Attribution-NonCommercial-NoDerivs License - http://creativecommons.org/licenses/by-nc-nd/2.0/ <br />
				<input type="checkbox" value="2" <?PHP if(in_array("2",$licenses)){ echo "checked"; } ?> name="attribution-noncommercial" /> Attribution-NonCommercial License - http://creativecommons.org/licenses/by-nc/2.0/ <br /> 
				<input type="checkbox" value="1" <?PHP if(in_array("1",$licenses)){ echo "checked"; } ?> name="attribution-sharealike-noncommercial" /> Attribution-NonCommercial-ShareAlike License - http://creativecommons.org/licenses/by-nc-sa/2.0/ <br />
				<input type="checkbox" value="5" <?PHP if(in_array("5",$licenses)){ echo "checked"; } ?> name="attribution-sharealike" /> Attribution-ShareAlike License - http://creativecommons.org/licenses/by-sa/2.0/ <br />
				<input type="checkbox" value="7" <?PHP if(in_array("7",$licenses)){ echo "checked"; } ?> name="none" /> No known copyright restrictions - http://flickr.com/commons/usage/ <br />
				<p>Content types to search for</p>
				<input type="radio" value="1" <?PHP if($content_type=="1"){ echo "checked"; } ?> name="content_type" />Photos only <br />
				<input type="radio" value="2" <?PHP if($content_type=="2"){ echo "checked"; } ?> name="content_type" />Screenshots only <br />
				<input type="radio" value="3" <?PHP if($content_type=="3"){ echo "checked"; } ?> name="content_type" />"other" only <br />
				<input type="radio" value="4" <?PHP if($content_type=="4"){ echo "checked"; } ?> name="content_type" />Photos and screeshots <br />
				<input type="radio" value="5" <?PHP if($content_type=="5"){ echo "checked"; } ?> name="content_type" />Screenshots and "other"<br />
				<input type="radio" value="6" <?PHP if($content_type=="6"){ echo "checked"; } ?> name="content_type" />Photos and "other"<br />
				<input type="radio" value="7" <?PHP if($content_type=="7"){ echo "checked"; } ?> name="content_type" />All <br />
				<p>Search only within FlickR Commons</p>
				<input type="radio" value="true" name="commons" />Yes <br />
				<input type="radio" value="false" name="commons" checked />No <br />
				<input type="hidden" name="upload" value="<?PHP echo $path; ?>" />
				<input type="hidden" name="upload_url" value="<?PHP echo $url_of_site; ?>" />
				<input type="hidden" name="post_edited" value="<?PHP echo $_GET['post_id']; ?>" />
				<input type="hidden" name="short_dir" value="<?PHP echo $short_dir; ?>" />
				<br />
				<input type="submit" class="button" value="Search for pictures" />
			</form></body></html>
		
		<?php
	
	}

	function advanced_picture_finder_button($context) {
	
		global $post_ID;
	
    		$icon_url = WP_PLUGIN_URL . '/' . str_replace(basename( __FILE__),"",plugin_basename(__FILE__)) . 'triton.png';
    
	    	$href = '<a href="media-upload.php?&amp;post_id=' . $post_ID . '&amp;type=advanced_picture_finder&amp;TB_iframe=1" class="thickbox" title="' . __('Triton Advanced Picture Search and Attribution') . '"><img src="'.$icon_url.'" alt="'. __('Find and attribute pictures') .'" title="'. __('Find and attribute pictures') .'" /></a>';
       
	    	return $context . $href;
    	
	}

	add_filter('media_buttons_context', 'advanced_picture_finder_button');
	add_filter('media_upload_advanced_picture_finder', 'add_advanced_picture_finder_action');

?>