<?php
/*
+----------------------------------------------------------------+
|																							|
|	WordPress 2.1 Plugin: WP-PostRatings 1.20								|
|	Copyright (c) 2007 Lester "GaMerZ" Chan									|
|																							|
|	File Written By:																	|
|	- Lester "GaMerZ" Chan															|
|	- http://www.lesterchan.net													|
|																							|
|	File Information:																	|
|	- Configure Post Ratings Options												|
|	- wp-content/plugins/postratings/postratings-options.php			|
|																							|
+----------------------------------------------------------------+
*/


### Check Whether User Can Manage Ratings
if(!current_user_can('manage_ratings')) {
	die('Access Denied');
}


### Ratings Variables
$base_name = plugin_basename('postratings/postratings-manager.php');
$base_page = 'admin.php?page='.$base_name;


### If Form Is Submitted
if($_POST['Submit']) {
	$postratings_customrating = intval($_POST['postratings_customrating']);
	$postratings_image = strip_tags(trim($_POST['postratings_image']));
	$postratings_max = intval($_POST['postratings_max']);
	$postratings_ratingstext_array = $_POST['postratings_ratingstext'];
	$postratings_ratingstext = array();
	foreach($postratings_ratingstext_array as $ratingstext) {
		$postratings_ratingstext[] = trim(addslashes($ratingstext));
	}
	$postratings_ratingsvalue_array = $_POST['postratings_ratingsvalue'];
	$postratings_ratingsvalue = array();
	foreach($postratings_ratingsvalue_array as $ratingsvalue) {
		$postratings_ratingsvalue[] =intval($ratingsvalue);
	}
	$postratings_ajax_style = array('loading' => intval($_POST['postratings_ajax_style_loading']), 'fading' => intval($_POST['postratings_ajax_style_fading']));
	$postratings_template_vote = trim($_POST['postratings_template_vote']);
	$postratings_template_text = trim($_POST['postratings_template_text']);
	$postratings_template_none = trim($_POST['postratings_template_none']);
	$postratings_template_highestrated = trim($_POST['postratings_template_highestrated']);
	$postratings_logging_method = intval($_POST['postratings_logging_method']);
	$postratings_allowtorate = intval($_POST['postratings_allowtorate']);
	$update_ratings_queries = array();
	$update_ratings_text = array();
	$update_ratings_queries[] = update_option('postratings_customrating', $postratings_customrating);
	$update_ratings_queries[] = update_option('postratings_image', $postratings_image);
	$update_ratings_queries[] = update_option('postratings_max', $postratings_max);
	$update_ratings_queries[] = update_option('postratings_ratingstext', $postratings_ratingstext);
	$update_ratings_queries[] = update_option('postratings_ratingsvalue', $postratings_ratingsvalue);
	$update_ratings_queries[] = update_option('postratings_ajax_style', $postratings_ajax_style);
	$update_ratings_queries[] = update_option('postratings_template_vote', $postratings_template_vote);
	$update_ratings_queries[] = update_option('postratings_template_text', $postratings_template_text);
	$update_ratings_queries[] = update_option('postratings_template_none', $postratings_template_none);
	$update_ratings_queries[] = update_option('postratings_template_highestrated', $postratings_template_highestrated);
	$update_ratings_queries[] = update_option('postratings_logging_method', $postratings_logging_method);
	$update_ratings_queries[] = update_option('postratings_allowtorate', $postratings_allowtorate);
	$update_ratings_text[] = __('Ratings Image', 'wp-postratings');
	$update_ratings_text[] = __('Max Ratings', 'wp-postratings');
	$update_ratings_text[] = __('Individual Rating Text', 'wp-postratings');
	$update_ratings_text[] = __('Individual Rating Value', 'wp-postratings');
	$update_ratings_text[] = __('Ratings AJAX Style', 'wp-postratings');
	$update_ratings_text[] = __('Ratings Template Vote', 'wp-postratings');
	$update_ratings_text[] = __('Ratings Template Voted', 'wp-postratings');
	$update_ratings_text[] = __('Ratings Template For No Ratings', 'wp-postratings');
	$update_ratings_text[] = __('Ratings Template For Highest Rated', 'wp-postratings');
	$update_ratings_text[] = __('Logging Method', 'wp-postratings');
	$update_ratings_text[] = __('Allow To Vote Option', 'wp-postratings');
	$i=1;
	$text = '';
	foreach($update_ratings_queries as $update_ratings_query) {
		if($update_ratings_query) {
			$text .= '<font color="green">'.$update_ratings_text[$i].' '.__('Updated', 'wp-postratings').'</font><br />';
		}
		$i++;
	}
	if(empty($text)) {
		$text = '<font color="red">'.__('No Ratings Option Updated', 'wp-postratings').'</font>';
	}
}


### Needed Variables
$postratings_max = intval(get_option('postratings_max'));
$postratings_customrating = intval(get_option('postratings_customrating'));
$postratings_url = get_option('siteurl').'/wp-content/plugins/postratings/images';
$postratings_path = ABSPATH.'/wp-content/plugins/postratings/images';
$postratings_ratingstext = get_option('postratings_ratingstext');
$postratings_ratingsvalue = get_option('postratings_ratingsvalue');
$postratings_image = get_option('postratings_image');
?>
<script language="JavaScript" type="text/javascript">
/* <![CDATA[*/
	function ratings_updown_templates(template, print) {
		var default_template;
		switch(template) {
			case "vote":
				default_template = "%RATINGS_IMAGES_VOTE% (<strong>%RATINGS_SCORE%</strong> <?php _e('rating', 'wp-postratings'); ?>, <strong>%RATINGS_USERS%</strong> <?php _e('votes', 'wp-postratings'); ?>)<br />%RATINGS_TEXT%";
				break;
			case "text":
				default_template = "%RATINGS_IMAGES% (<em><strong>%RATINGS_SCORE%</strong> <?php _e('rating', 'wp-postratings'); ?>, <strong>%RATINGS_USERS%</strong> <?php _e('votes', 'wp-postratings'); ?>, <strong><?php _e('rated', 'wp-postratings'); ?></strong></em>)";
				break;
			case "none":
				default_template = "%RATINGS_IMAGES_VOTE% (<?php _e('No Ratings Yet', 'wp-postratings'); ?>)<br />%RATINGS_TEXT%";
				break;
			case "highestrated":
				default_template = "<li><a href=\"%POST_URL%\" title=\"%POST_TITLE%\">%POST_TITLE%</a> (%RATINGS_SCORE% <?php _e('rating', 'wp-postratings'); ?>, %RATINGS_USERS% <?php _e('votes', 'wp-postratings'); ?>)</li>";
				break;
		}
		if(print) {
			document.getElementById("postratings_template_" + template).value = default_template;
		} else {
			return default_template;
		}
	}
	function ratings_default_templates(template, print) {
		var default_template;
		switch(template) {
			case "vote":
				default_template = "%RATINGS_IMAGES_VOTE% (<strong>%RATINGS_USERS%</strong> <?php _e('votes', 'wp-postratings'); ?>, <?php _e('average', 'wp-postratings'); ?>: <strong>%RATINGS_AVERAGE%</strong> <?php _e('out of', 'wp-postratings'); ?> %RATINGS_MAX%)<br />%RATINGS_TEXT%";
				break;
			case "text":
				default_template = "%RATINGS_IMAGES% (<em><strong>%RATINGS_USERS%</strong> <?php _e('votes', 'wp-postratings'); ?>, <?php _e('average', 'wp-postratings'); ?>: <strong>%RATINGS_AVERAGE%</strong> <?php _e('out of', 'wp-postratings'); ?> %RATINGS_MAX%, <strong><?php _e('rated', 'wp-postratings'); ?></strong></em>)";
				break;
			case "none":
				default_template = "%RATINGS_IMAGES_VOTE% (<?php _e('No Ratings Yet', 'wp-postratings'); ?>)<br />%RATINGS_TEXT%";
				break;
			case "highestrated":
				default_template = "<li><a href=\"%POST_URL%\" title=\"%POST_TITLE%\">%POST_TITLE%</a> %RATINGS_IMAGES% (%RATINGS_AVERAGE% <?php _e('out of', 'wp-postratings'); ?> %RATINGS_MAX%)</li>";
				break;
		}
		if(print) {
			document.getElementById("postratings_template_" + template).value = default_template;
		} else {
			return default_template;
		}
	}
	function set_custom(custom, max) {
		if(custom == 1) {
			document.getElementById('postratings_max').value = max;
			document.getElementById('postratings_max').readOnly = true;
			if(max == 2) {
				document.getElementById('postratings_template_vote').value = ratings_updown_templates('vote', false);
				document.getElementById('postratings_template_text').value = ratings_updown_templates('text', false);
				document.getElementById('postratings_template_none').value = ratings_updown_templates('none', false);
				document.getElementById('postratings_template_highestrated').value = ratings_updown_templates('highestrated', false);
			} else {
				document.getElementById('postratings_template_vote').value = ratings_default_templates('vote', false);
				document.getElementById('postratings_template_text').value = ratings_default_templates('text', false);
				document.getElementById('postratings_template_none').value = ratings_default_templates('none', false);
				document.getElementById('postratings_template_highestrated').value = ratings_default_templates('highestrated', false);
			}
		} else {
			document.getElementById('postratings_max').value = <?php echo $postratings_max; ?>;
			document.getElementById('postratings_max').readOnly = false;
			document.getElementById('postratings_template_vote').value = ratings_default_templates('vote', false);
			document.getElementById('postratings_template_text').value = ratings_default_templates('text', false);
			document.getElementById('postratings_template_none').value = ratings_default_templates('none', false);
			document.getElementById('postratings_template_highestrated').value = ratings_default_templates('highestrated', false);
		}
		document.getElementById('postratings_customrating').value = custom;
	}
/* ]]> */
</script>
<?php if(!empty($text)) { echo '<!-- Last Action --><div id="message" class="updated fade"><p>'.$text.'</p></div>'; } ?>
<div class="wrap"> 
	<h2><?php _e('Post Rating Options', 'wp-postratings'); ?></h2> 
	<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>"> 
	<input type="hidden" id="postratings_customrating" name="postratings_customrating" value="<?php echo $postratings_customrating; ?>" />
		<fieldset class="options">
			<legend><?php _e('Ratings Settings', 'wp-postratings'); ?></legend>
			<table width="100%"  border="0" cellspacing="3" cellpadding="3">
				 <tr valign="top">
					<th align="left" width="20%"><?php _e('Ratings Image:', 'wp-postratings'); ?></th>
					<td align="left">
						<?php
							$postratings_images_array = array();
							if($handle = @opendir($postratings_path)) {     
								while (false !== ($filename = readdir($handle))) {  
									if ($filename != '.' && $filename != '..') {
										if(is_dir($postratings_path.'/'.$filename)) {
											$postratings_images_array[$filename] = ratings_images_folder($filename);
										}
									} 
								} 
								closedir($handle);
							}
							foreach($postratings_images_array as $key => $value) {
								if($value['custom'] == 0) {
									if($postratings_image == $key) {
										echo '<input type="radio" name="postratings_image" onclick="set_custom('.$value['custom'].', '.$value['max'].');" value="'.$key.'" checked="checked" />';
									} else {
										echo '<input type="radio" name="postratings_image" onclick="set_custom('.$value['custom'].', '.$value['max'].');" value="'.$key.'" />';
									}
									echo '&nbsp;&nbsp;&nbsp;';
									if(file_exists($postratings_path.'/'.$key.'/rating_start.gif')) {
										echo '<img src="'.$postratings_url.'/'.$key.'/rating_start.gif" alt="rating_start.gif" class="post-ratings-image" />';
									}
									echo '<img src="'.$postratings_url.'/'.$key.'/rating_over.gif" alt="rating_over.gif" class="post-ratings-image" />';
									echo '<img src="'.$postratings_url.'/'.$key.'/rating_on.gif" alt="rating_on.gif" class="post-ratings-image" />';
									echo '<img src="'.$postratings_url.'/'.$key.'/rating_on.gif" alt="rating_on.gif" class="post-ratings-image" />';
									echo '<img src="'.$postratings_url.'/'.$key.'/rating_half.gif" alt="rating_half.gif" class="post-ratings-image" />';
									echo '<img src="'.$postratings_url.'/'.$key.'/rating_off.gif" alt="rating_off.gif" class="post-ratings-image" />';
								} else {
									if($postratings_image == $key) {
										echo '<input type="radio" name="postratings_image" onclick="set_custom('.$value['custom'].', '.$value['max'].');" value="'.$key.'" checked="checked" />';
									} else {
										echo '<input type="radio" name="postratings_image" onclick="set_custom('.$value['custom'].', '.$value['max'].');" value="'.$key.'" />';
									}
									echo '&nbsp;&nbsp;&nbsp;';
									if(file_exists($postratings_path.'/'.$key.'/rating_start.gif')) {
										echo '<img src="'.$postratings_url.'/'.$key.'/rating_start.gif" alt="rating_start.gif" class="post-ratings-image" />';
									}
									for($i = 1; $i <= $value['max']; $i++) {
											if(file_exists($postratings_path.'/'.$key.'/rating_'.$i.'_off.gif')) {
												echo '<img src="'.$postratings_url.'/'.$key.'/rating_'.$i.'_off.gif" alt="rating_'.$i.'_off.gif" class="post-ratings-image" />';
											}
									}
								}
								if(file_exists($postratings_path.'/'.$key.'/rating_end.gif')) {
									echo '<img src="'.$postratings_url.'/'.$key.'/rating_end.gif" alt="rating_end.gif" class="post-ratings-image" />';
								}
								echo '&nbsp;&nbsp;&nbsp;('.$key.')';
								echo '<br /><br />'."\n";
							}
						?>
					</td>
				</tr>
				<tr valign="top">
					<th align="left" width="20%"><?php _e('Max Ratings:', 'wp-postratings'); ?></th>
					<td align="left"><input type="text" id="postratings_max" name="postratings_max" value="<?php echo $postratings_max; ?>" size="3" <?php if($postratings_customrating) { echo 'readonly="readonly"'; } ?> /></td>
				</tr>
				<tr valign="top">
					<td colspan="2" align="center"><input type="button" name="update" value="<?php _e('Update \'Individual Rating Text/Value\' Display', 'wp-postratings'); ?>" onclick="update_rating_text_value();" class="button" /><br /><img id="postratings_loading" src="<?php echo $postratings_url; ?>/loading.gif" alt="" style="display: none;" /></td>
				</tr>
			</table>
		</fieldset>
		<fieldset class="options">
			<legend><?php _e('Individual Rating Text/Value', 'wp-postratings'); ?></legend>
			<div id="rating_text_value">
				<table width="80%"  border="0" cellspacing="3" cellpadding="3">
					<tr>
						<td><strong>Rating Image</strong></td>
						<td><strong>Rating Text</strong></td>
						<td><strong>Rating Value</strong></td>
					</tr>
					<?php
						for($i = 1; $i <= $postratings_max; $i++) {
							echo '<tr>'."\n";
							echo '<td>'."\n";
							if(file_exists($postratings_path.'/'.$postratings_image.'/rating_start.gif')) {
								echo '<img src="'.$postratings_url.'/'.$postratings_image.'/rating_start.gif" alt="rating_start.gif" class="post-ratings-image" />';
							}
							if($postratings_customrating) {
								if($postratings_max == 2) {
									echo '<img src="'.$postratings_url.'/'.$postratings_image.'/rating_'.$i.'_on.gif" alt="rating_'.$i.'_on.gif" class="post-ratings-image" />';
								} else {
									for($j = 1; $j < ($i+1); $j++) {
										echo '<img src="'.$postratings_url.'/'.$postratings_image.'/rating_'.$j.'_on.gif" alt="rating_on.gif" class="post-ratings-image" />';
									}
								}
							} else {
								for($j = 1; $j < ($i+1); $j++) {
									echo '<img src="'.$postratings_url.'/'.$postratings_image.'/rating_on.gif" alt="rating_on.gif" class="post-ratings-image" />';
								}
							}
							if(file_exists($postratings_path.'/'.$postratings_image.'/rating_end.gif')) {
								echo '<img src="'.$postratings_url.'/'.$postratings_image.'/rating_end.gif" alt="rating_end.gif" class="post-ratings-image" />';
							}
							echo '</td>'."\n";
							echo '<td>'."\n";
							echo '<input type="text" id="postratings_ratingstext_'.$i.'" name="postratings_ratingstext[]" value="'.stripslashes($postratings_ratingstext[$i-1]).'" size="20" maxlength="50" />'."\n";
							echo '</td>'."\n";
							echo '<td>'."\n";
							echo '<input type="text" id="postratings_ratingsvalue_'.$i.'" name="postratings_ratingsvalue[]" value="';
							if($postratings_ratingsvalue[$i-1] > 0 && $postratings_customrating) {
								echo '+';
							}
							echo $postratings_ratingsvalue[$i-1].'" size="2" maxlength="2" />'."\n";
							echo '</td>'."\n";
							echo '</tr>'."\n";
						}								
					?>
				</table>
			</div>
		</fieldset>
		<?php $postratings_ajax_style = get_option('postratings_ajax_style'); ?>
		<fieldset class="options">
			<legend><?php _e('Ratings AJAX Style', 'wp-postratings'); ?></legend>
			<table width="100%"  border="0" cellspacing="3" cellpadding="3">
				 <tr valign="top">
					<th align="left" width="30%"><?php _e('Show Loading Image With Text', 'wp-postratings'); ?></th>
					<td align="left">
						<select name="postratings_ajax_style_loading" size="1">
							<option value="0"<?php selected('0', $postratings_ajax_style['loading']); ?>><?php _e('No', 'wp-postratings'); ?></option>
							<option value="1"<?php selected('1', $postratings_ajax_style['loading']); ?>><?php _e('Yes', 'wp-postratings'); ?></option>
						</select>
					</td>
				</tr>
				<tr valign="top"> 
					<th align="left" width="30%"><?php _e('Show Fading In And Fading Out Of Ratings', 'wp-postratings'); ?></th>
					<td align="left">
						<select name="postratings_ajax_style_fading" size="1">
							<option value="0"<?php selected('0', $postratings_ajax_style['fading']); ?>><?php _e('No', 'wp-postratings'); ?></option>
							<option value="1"<?php selected('1', $postratings_ajax_style['fading']); ?>><?php _e('Yes', 'wp-postratings'); ?></option>
						</select>
					</td> 
				</tr>
			</table>
		</fieldset>
		<fieldset class="options">
		<legend><?php _e('Allow To Rate', 'wp-postratings'); ?></legend>
		<table width="100%"  border="0" cellspacing="3" cellpadding="3">
			 <tr valign="top">
				<th align="left" width="30%"><?php _e('Who Is Allowed To Rate?', 'wp-postratings'); ?></th>
				<td align="left">
					<select name="postratings_allowtorate" size="1">
						<option value="0"<?php selected('0', get_option('postratings_allowtorate')); ?>><?php _e('Guests Only', 'wp-postratings'); ?></option>
						<option value="1"<?php selected('1', get_option('postratings_allowtorate')); ?>><?php _e('Registered Users Only', 'wp-postratings'); ?></option>
						<option value="2"<?php selected('2', get_option('postratings_allowtorate')); ?>><?php _e('Registered Users And Guests', 'wp-postratings'); ?></option>
					</select>
				</td>
			</tr>
		</table>
	</fieldset>
	<fieldset class="options">
		<legend><?php _e('Logging Method', 'wp-postratings'); ?></legend>
		<table width="100%"  border="0" cellspacing="3" cellpadding="3">
			 <tr valign="top">
				<th align="left" width="30%"><?php _e('Ratings Logging Method:', 'wp-postratings'); ?></th>
				<td align="left">
					<select name="postratings_logging_method" size="1">
						<option value="0"<?php selected('0', get_option('postratings_logging_method')); ?>><?php _e('Do Not Log', 'wp-postratings'); ?></option>
						<option value="1"<?php selected('1', get_option('postratings_logging_method')); ?>><?php _e('Logged By Cookie', 'wp-postratings'); ?></option>
						<option value="2"<?php selected('2', get_option('postratings_logging_method')); ?>><?php _e('Logged By IP', 'wp-postratings'); ?></option>
						<option value="3"<?php selected('3', get_option('postratings_logging_method')); ?>><?php _e('Logged By Cookie And IP', 'wp-postratings'); ?></option>
						<option value="4"<?php selected('4', get_option('postratings_logging_method')); ?>><?php _e('Logged By Username', 'wp-postratings'); ?></option>
					</select>
				</td>
			</tr>
		</table>
	</fieldset>
	<fieldset class="options">
		<legend><?php _e('Template Variables', 'wp-postratings'); ?></legend>
		<table width="100%"  border="0" cellspacing="3" cellpadding="3">
			<tr>
				<td><strong>%RATINGS_IMAGES%</strong> - <?php _e('Display the ratings images', 'wp-postratings'); ?></td>
				<td><strong>%RATINGS_IMAGES_VOTE%</strong> - <?php _e('Display the ratings voting image', 'wp-postratings'); ?></td>
			</tr>
			<tr>
				<td><strong>%RATINGS_AVERAGE%</strong> - <?php _e('Display the average ratings', 'wp-postratings'); ?></td>
				<td><strong>%RATINGS_USERS%</strong> - <?php _e('Display the total number of users rated for the post', 'wp-postratings'); ?></td>						
			</tr>
			<tr>
				<td><strong>%RATINGS_MAX%</strong> - <?php _e('Display the max number of ratings', 'wp-postratings'); ?></td>
				<td><strong>%RATINGS_PERCENTAGE%</strong> - <?php _e('Display the ratings percentage', 'wp-postratings'); ?></td>
			</tr>
			<tr>
				<td><strong>%RATINGS_SCORE%</strong> - <?php _e('Display the total score of the ratings', 'wp-postratings'); ?></td>
				<td><strong>%RATINGS_TEXT%</strong> - <?php _e('Display the individual rating text. Eg: 1 Star, 2 Stars, etc', 'wp-postratings'); ?></td>
			</tr>
		</table>
	</fieldset>
	<fieldset class="options">
		<legend><?php _e('Ratings Templates', 'wp-postratings'); ?></legend>
		<table width="100%"  border="0" cellspacing="3" cellpadding="3">
			 <tr valign="top">
				<td align="left" width="30%">
					<strong><?php _e('Ratings Vote Text:', 'wp-postratings'); ?></strong><br /><br />
					<?php _e('Allowed Variables:', 'wp-postratings'); ?><br />
					- %RATINGS_IMAGES_VOTE%<br />
					- %RATINGS_MAX%<br />
					- %RATINGS_SCORE%<br />
					- %RATINGS_TEXT%<br />
					- %RATINGS_USERS%<br />							
					- %RATINGS_AVERAGE%<br />
					- %RATINGS_PERCENTAGE%<br /><br />
					<input type="button" name="RestoreDefault" value="<?php _e('Restore Default Template', 'wp-postratings'); ?>" onclick="javascript: ratings_default_templates('vote', true);" class="button" />
				</td>
				<td align="left"><textarea cols="80" rows="10" id="postratings_template_vote" name="postratings_template_vote"><?php echo htmlspecialchars(stripslashes(get_option('postratings_template_vote'))); ?></textarea></td>
			</tr>
			 <tr valign="top">
				<td align="left" width="30%">
					<strong><?php _e('Ratings Voted Text:', 'wp-postratings'); ?></strong><br /><br />
					<?php _e('Allowed Variables:', 'wp-postratings'); ?><br />
					- %RATINGS_IMAGES%<br />
					- %RATINGS_MAX%<br />
					- %RATINGS_SCORE%<br />
					- %RATINGS_USERS%<br />							
					- %RATINGS_AVERAGE%<br />
					- %RATINGS_PERCENTAGE%<br /><br />
					<input type="button" name="RestoreDefault" value="<?php _e('Restore Default Template', 'wp-postratings'); ?>" onclick="javascript: ratings_default_templates('text', true);" class="button" />
				</td>
				<td align="left"><textarea cols="80" rows="10" id="postratings_template_text" name="postratings_template_text"><?php echo htmlspecialchars(stripslashes(get_option('postratings_template_text'))); ?></textarea></td>
			</tr>
			 <tr valign="top">
				<td align="left" width="30%">
					<strong><?php _e('Ratings None:', 'wp-postratings'); ?></strong><br /><br />
					<?php _e('Allowed Variables:', 'wp-postratings'); ?><br />
					- %RATINGS_IMAGES_VOTE%<br />
					- %RATINGS_MAX%<br />
					- %RATINGS_SCORE%<br />
					- %RATINGS_TEXT%<br />
					- %RATINGS_USERS%<br />							
					- %RATINGS_AVERAGE%<br />
					- %RATINGS_PERCENTAGE%<br /><br />
					<input type="button" name="RestoreDefault" value="<?php _e('Restore Default Template', 'wp-postratings'); ?>" onclick="javascript: ratings_default_templates('none', true);" class="button" />
				</td>
				<td align="left"><textarea cols="80" rows="10" id="postratings_template_none" name="postratings_template_none"><?php echo htmlspecialchars(stripslashes(get_option('postratings_template_none'))); ?></textarea></td>
			</tr>
			 <tr valign="top">
				<td align="left" width="30%">
					<strong><?php _e('Highest Rated:', 'wp-postratings'); ?></strong><br /><br />
					<?php _e('Allowed Variables:', 'wp-postratings'); ?><br />
					- %RATINGS_IMAGES<br />
					- %RATINGS_MAX%<br />
					- %RATINGS_SCORE%<br />
					- %RATINGS_USERS%<br />							
					- %RATINGS_AVERAGE%<br />
					- %POST_TITLE%<br />
					- %POST_URL%<br /><br />
					<input type="button" name="RestoreDefault" value="<?php _e('Restore Default Template', 'wp-postratings'); ?>" onclick="javascript: ratings_default_templates('highestrated', true);" class="button" />
				</td>
				<td align="left"><textarea cols="80" rows="10" id="postratings_template_highestrated" name="postratings_template_highestrated"><?php echo htmlspecialchars(stripslashes(get_option('postratings_template_highestrated'))); ?></textarea></td>
			</tr>
		</table>
	</fieldset>
	<div align="center">
		<input type="submit" name="Submit" class="button" value="<?php _e('Update Options', 'wp-postratings'); ?>" />&nbsp;&nbsp;<input type="button" name="cancel" value="<?php _e('Cancel', 'wp-postratings'); ?>" class="button" onclick="javascript:history.go(-1)" />
	</div>
	</form>
</div>