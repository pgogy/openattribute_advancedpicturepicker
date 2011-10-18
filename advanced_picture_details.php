<?PHP

	include "../../../wp-admin/admin.php";

	$home_url = get_bloginfo("siteurl"); 

	$home_url = get_bloginfo("siteurl"); 
		
	$nonce=$_REQUEST['_wpnonce'];
						
	if(!wp_verify_nonce($nonce, "picture-picker-nonce")){
	
			die("Nonce fail");
		
	} 	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
			<html xmlns="http://www.w3.org/1999/xhtml"  dir="ltr" lang="en-US"> 
			<head> 
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
			<title>Politics in Spires &rsaquo; Uploads &#8212; WordPress</title> 
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
				
	$params = array(
			
		'photo_id'	=> $_POST['flickr_id'],
		'api_key'	=> '728ee0bc70c3fc45c03790b209889847',
		'method'	=> 'flickr.photos.getSizes',
		'format'	=> 'php_serial'
	);

	$encoded_params = array();

	foreach ($params as $k => $v){

		$encoded_params[] = urlencode($k).'='.urlencode($v);
		
	}

	$url = "http://api.flickr.com/services/rest/?".implode('&', $encoded_params);

	$rsp = file_get_contents($url);
	
	$rsp_obj = unserialize($rsp);
		
	$pictures = $rsp_obj['sizes']['size'];
	
	?>
		<h3 class="media-title" style="padding:10px 0 0 10px; margin:0px;">
					Triton - Picture search and attribution
		</h3>
	<?PHP
	
	$upload = $_POST['upload'];
	$upload_url = $_POST['upload_url'];
	$post_edited = $_POST['post_edited'];
	$short_dir = $_POST['short_dir'];
			
	while($pic = array_shift($pictures)){
	
		echo "<form action=\"advanced_picture_copy.php?_wpnonce=$nonce\" style=\":float:left; position:relative; margin:10px; width:200px; border:1px solid #aaa; padding:10px;\" method=\"POST\">";		
		echo "Size - " . $pic['label'] . " (" . $pic['width'] . " by " . $pic['height'] . ")<br />";
		echo "<input name=\"pic_data\" type=\"hidden\" value=\"" . $_POST['pic_data'] . "\" />";
		echo "<input type=\"hidden\" name=\"file_url\" value=\"" . $pic['source'] . "\" />";
		echo "<input type=\"hidden\" name=\"upload\" value=\"" . str_replace("\\\\","\\",$upload) . "\" />";
		echo "<input type=\"hidden\" name=\"upload_url\" value=\"" . $upload_url . "\" />";
		echo "<input type=\"hidden\" name=\"post_edited\" value=\"" . $post_edited . "\" />";
		echo "<input type=\"hidden\" name=\"short_dir\" value=\"" . $short_dir . "\" />";
		echo "<input type=\"hidden\" name=\"flickr_id\" value=\"" . $_POST['flickr_id'] . "\" />";
		echo "<input type=\"hidden\" name=\"flickr_license\" value=\"" . $_POST['flickr_license'] . "\" />";
		echo "<input type=\"submit\" value=\"choose\" style=\"margin-top:20px\" />";
		echo "</form>";	
	
	}

?>