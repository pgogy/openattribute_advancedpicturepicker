<?PHP

	include "../../../wp-admin/admin.php";

	$home_url = site_url();  	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
			<html xmlns="http://www.w3.org/1999/xhtml"  dir="ltr" lang="en-US"> 
			<head> 
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
			<title>Advanced Picture Picker</title> 
			<script type="text/javascript"> 
			//<![CDATA[
			addLoadEvent = function(func){if(typeof jQuery!="undefined")jQuery(document).ready(func);else if(typeof wpOnload!='function'){wpOnload=func;}else{var oldonload=wpOnload;wpOnload=function(){oldonload();func();}}};
			var userSettings = {'url':'','uid':'<?PHP
				
					get_currentuserinfo();
					echo $current_user->user_ID;
				
				?>','time':'<?PHP echo time(); ?>'};
			var ajaxurl = '<?PHP echo $home_url; ?>/wp-admin/admin-ajax.php', pagenow = 'media-upload-popup', adminpage = 'media-upload-popup';
			//]]>
			</script> 
			<link rel='stylesheet' href='<?PHP echo $home_url; ?>/wp-admin/load-styles.php?c=1&amp;dir=ltr&amp;load=global,wp-admin,media&amp;ver=a5819c5a976216f1c44e5e55418aee0b' type='text/css' media='all' /> 
			<link rel='stylesheet' id='imgareaselect-css'  href='<?PHP echo $home_url; ?>/wp-includes/js/imgareaselect/imgareaselect.css?ver=0.9.1' type='text/css' media='all' /> 
			<link rel='stylesheet' id='colors-css'  href='<?PHP echo $home_url; ?>/wp-admin/css/colors-fresh.css?ver=20100610' type='text/css' media='all' /> 
			<!--[if lte IE 7]>
			<link rel='stylesheet' id='ie-css'  href='<?PHP echo $home_url; ?>/wp-admin/css/ie.css?ver=20100610' type='text/css' media='all' />
			<![endif]-->			
			<script type='text/javascript' src='<?PHP echo $home_url; ?>/wp-admin/load-scripts.php?c=1&amp;load=jquery,utils,swfupload-all,swfupload-handlers,json2&amp;ver=733749dfc00359ca23b46b29e2f304d2'></script> 
			</head> 
			<body id="media-upload"> <?PHP

			if ( !empty($_POST) && check_admin_referer('picture_finder_nonce','picture_finder_nonce_form_name') ){
			
				$upload = $_POST['upload'];
				$pictures_to_get = $_POST['pictures_return'];
				$upload_url = $_POST['upload_url'];
				$post_edited = $_POST['post_edited'];
				$short_dir = $_POST['short_dir'];
				$search_term = $_POST['search_term'];
				$content_type = $_POST['content_type'];
				
				$current_user = wp_get_current_user();
			
				add_user_meta($current_user->ID, "advanced_picture_picker_pictures_total", $_POST['pictures_return'], true);
				add_user_meta($current_user->ID, "advanced_picture_picker_search_term", $_POST['search_term'], true);
				add_user_meta($current_user->ID, "advanced_picture_picker_content_type", $_POST['content_type'], true);

				if($_POST['commons']=="true"){
				
					$commons="true";
				
				}else{
				
					$commons="false";
				
				}
				
				add_user_meta($current_user->ID, "advanced_picture_picker_pictures_commons", $_POST['commons'], true);
				
				$license = array();
				
				if(isset($_POST['attribution'])){
				
					array_push($license,"4");				
				
				} 
				if(isset($_POST['attribution-noderivs'])){
				
					array_push($license,"6");
				
				}
				if(isset($_POST['attribution-noncommercial-noderivs'])){
				
					array_push($license,"3");
				
				}
				if(isset($_POST['attribution-noncommercial'])){
				
					array_push($license,"2");
				
				}
				if(isset($_POST['attribution-sharealike-noncommercial'])){
				
					array_push($license,"1");
				
				}
				if(isset($_POST['attribution-sharealike'])){
				
					array_push($license,"5");
				
				}
				if(isset($_POST['none'])){
				
					array_push($license,"7");
				
				}
				
				if(count($license)==0){
				
					 array_push($license,"7");
				
				}
				
				add_user_meta($current_user->ID, "advanced_picture_picker_pictures_licenses", implode(",",$license), true);
		
		$images_returned = 0;
		
		while($license_search = array_pop($license)){		
			
			if($images_returned<$pictures_to_get){
					
				$params = array(
						
					'per_page'	=> $pictures_to_get,
					'content_type' => $content_type,
					'is_commons' => $commons,
					'safe_search'	=> 1,
					'privacy_filter'	=> 1,
					'license'		=> $license_search,
					'text'		=> $search_term,
					'api_key'	=> '96990460a0675f30f3f7d4205672dce3',
					'method'	=> 'flickr.photos.search',
					'format'	=> 'php_serial'
				);
			
				$encoded_params = array();
			
				foreach ($params as $k => $v){
			
					$encoded_params[] = urlencode($k).'='.urlencode($v);
					
				}
			
				$url = "https://api.flickr.com/services/rest/?".implode('&', $encoded_params);
			
				$rsp = file_get_contents($url);
				
				$rsp_obj = unserialize($rsp);
				
				$pictures = $rsp_obj['photos']['photo'];
				
				$total = count($pictures);
				
				?>
					<h3 class="media-title" style="padding:10px 0 0 10px; margin:0px;">
								Triton - Picture search and attribution
					</h3>
				<?PHP
				
				if($total!=0){

					$nonce = wp_create_nonce('picture-picker-nonce');
						
					while($pic = array_shift($pictures)){
					
						echo "<div style=\"float:left; position:relative; width:140px; height:200px; background-color:#eee; border:1px solid #aaa; margin:10px\">";
						echo "<form action=\"advanced_picture_details.php?_wpnonce=$nonce\" style=\"width:120px; margin:0 auto; text-align:center;\" method=\"POST\">";		
						echo "<img style=\"margin-top:15px\" src=\"http://farm" . $pic['farm'] . ".static.flickr.com/" . $pic['server'] . "/" . $pic['id'] . "_" . $pic['secret'] . "_t.jpg\" /><br />";
						echo "<a target=\"_blank\" href=\"http://www.flickr.com/" . $pic['owner'] . "/" . $pic['id'] . "/\" />View on FlickR</a>";
						echo "<input name=\"pic_data\" type=\"hidden\" value=\"" . urlencode(serialize($pic)) . "\" />";
						echo "<input type=\"hidden\" name=\"upload\" value=\"" . str_replace("\\\\","\\",$upload) . "\" />";
						echo "<input type=\"hidden\" name=\"upload_url\" value=\"" . $upload_url . "\" />";
						echo "<input type=\"hidden\" name=\"post_edited\" value=\"" . $post_edited . "\" />";
						echo "<input type=\"hidden\" name=\"short_dir\" value=\"" . $short_dir . "\" />";
						echo "<input type=\"hidden\" name=\"flickr_id\" value=\"" . $pic['id'] . "\" />";
						echo "<input type=\"hidden\" name=\"flickr_license\" value=\"" . $license_search . "\" />";
						echo "<input type=\"submit\" value=\"choose\" style=\"margin-top:20px\" />";
						echo "</form></div>";	
							
						$images_returned++;			
					
					}
				
				}else{
				
					?><h2 class="media-title" style="padding:10px 0 0 10px; margin:0px;">Sorry, no matching pictures found</h2><?PHP
				
				}
			
			}else{
			
				break;
			
			}
		
		}
		
	?><form class="media-upload-form type-form validate" action="" method="post">
					<p style="clear:left;">
						Search again
					</p>
					<input type="text" size="120" name="search_term" value='<?PHP echo stripcslashes($search_term); ?>' />
					<input type="text" size="60" name="pictures_return" value="<?PHP echo $_POST['pictures_return']; ?>" />
					<p>Licenses to search within</p>
					<input type="checkbox" value="4" <?PHP if(isset($_POST['attribution'])){ echo "checked"; } ?>	 name="attribution" /> Attribution License - http://creativecommons.org/licenses/by/2.0/ <br />
					<input type="checkbox" value="6" <?PHP if(isset($_POST['attribution-noderivs'])){ echo "checked"; } ?> name="attribution-noderivs" /> Attribution-NoDerivs License - http://creativecommons.org/licenses/by-nd/2.0/ <br />
					<input type="checkbox" value="3" <?PHP if(isset($_POST['attribution-noncommercial-noderivs'])){ echo "checked"; } ?> name="attribution-noncommercial-noderivs" /> Attribution-NonCommercial-NoDerivs License - http://creativecommons.org/licenses/by-nc-nd/2.0/ <br />
					<input type="checkbox" value="2" <?PHP if(isset($_POST['attribution-noncommercial'])){ echo "checked"; } ?> name="attribution-noncommercial" /> Attribution-NonCommercial License - http://creativecommons.org/licenses/by-nc/2.0/ <br /> 
					<input type="checkbox" value="1" <?PHP if(isset($_POST['attribution-sharealike-noncommercial'])){ echo "checked"; } ?> name="attribution-sharealike-noncommercial" /> Attribution-NonCommercial-ShareAlike License - http://creativecommons.org/licenses/by-nc-sa/2.0/ <br />
					<input type="checkbox" value="5" <?PHP if(isset($_POST['attribution-sharealike'])){ echo "checked"; } ?> name="attribution-sharealike" /> Attribution-ShareAlike License - http://creativecommons.org/licenses/by-sa/2.0/ <br />
					<input type="checkbox" value="7" <?PHP if(isset($_POST['none'])){ echo "checked"; } ?> name="none" /> No known copyright restrictions - http://flickr.com/commons/usage/ <br />
					<p>Content types to search for</p>
					<input type="radio" value="1" <?PHP if($_POST['content_type']==1){ echo "checked"; } ?> name="content_type" />Photos only <br />
					<input type="radio" value="2" <?PHP if($_POST['content_type']==2){ echo "checked"; } ?> name="content_type" />Screenshots only <br />
					<input type="radio" value="3" <?PHP if($_POST['content_type']==3){ echo "checked"; } ?> name="content_type" />"other" only <br />
					<input type="radio" value="4" <?PHP if($_POST['content_type']==4){ echo "checked"; } ?> name="content_type" />Photos and screeshots <br />
					<input type="radio" value="5" <?PHP if($_POST['content_type']==5){ echo "checked"; } ?> name="content_type" />Screenshots and "other"<br />
					<input type="radio" value="6" <?PHP if($_POST['content_type']==6){ echo "checked"; } ?> name="content_type" />Photos and "other"<br />
					<input type="radio" value="7" <?PHP if($_POST['content_type']==7){ echo "checked"; } ?> name="content_type" />All <br />
					<p>Search only within FlickR Commons</p>
					<input type="radio" value="true" name="commons" <?PHP if($_POST['commons']=="true"){ echo "checked"; } ?> />Yes <br />
					<input type="radio" value="false" name="commons" <?PHP if($_POST['commons']!="true"){ echo "checked"; } ?> />No <br />
					<input type="hidden" name="upload" value="<?PHP echo $upload; ?>" />
					<input type="hidden" name="upload_url" value="<?PHP echo $upload_url; ?>" />
					<input type="hidden" name="post_edited" value="<?PHP echo $post_edited; ?>" />
					<input type="hidden" name="short_dir" value="<?PHP echo $short_dir; ?>" /><br />
					<input type="submit" class="button" value="Search for pictures" />
				</form><?PHP

		}
		
?>