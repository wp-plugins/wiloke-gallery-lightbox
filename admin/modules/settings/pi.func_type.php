<?php

global $typenow, $post;

if( $typenow != self::PIG_POST_TYPE) return;

$selected = isset($this->aOptions['mode']) && !empty($this->aOptions['mode']) ? $this->aOptions['mode'] : 'images';

?>
<div  class="postbox pi-settings" style="margin-top: 20px">

	<div class="handlediv" title="Click to toggle">
	<br>
	</div>
	
	<h3 class="hndle">
		<span>What do you want the content when click popup ?</span>
	</h3>

	<div class="inside">
		<select class="widefat js_popup_contenttype" name="pi_lightbox[mode]">
			<option value="linkto" data-hide="#pi_images, #pi_video,  #pi_project, #pi_googlemap, #pi_popup_max_width, .pi-notice" data-target="#pi_linkto" <?php selected($selected, 'linkto') ?>>Link To</option>
			<option value="images" data-hide="#pi_linkto, #pi_project, #pi_video, #pi_googlemap, #pi_popup_max_width, .pi-notice" data-target="#pi_images" <?php selected($selected, 'images') ?>>Image/Gallery</option>
			<option value="video" data-hide="#pi_linkto, #pi_project, #pi_images, #pi_googlemap, #pi_popup_max_width, .pi-notice" data-target="#pi_video" <?php selected($selected, 'video') ?>>Video - Youtube/Vimeo</option>
			<!-- <option value="googlemap" data-hide="#pi_linkto, #pi_images, #pi_video, #pi_project, #pi_popup_max_width, .pi-notice" data-target="#pi_googlemap" <?php selected($selected, 'googlemap') ?>>Googlemap</option> -->
			<option value="project" data-hide="#pi_linkto,  #pi_googlemap, #pi_popup_max_width" data-target="#pi_project,#pi_images, #pi_video, .pi-notice" <?php selected($selected, 'project') ?>>Project</option>
		</select>
	
	
		<div class="pi-notice">
			<p class="pi-alert danger">Notice that, The <strong>youtube/vimeo</strong> will be  prioritized first. It's mean, if you set the both  <strong>youtube/vimeo</strong> and <strong>image/gallery</strong>, the <strong>youtube/vimeo</strong>  will be displayed instead of <strong>image/gallery</strong></p>
		</div>
	</div>

</div>

<div id="postdivrich_wrap">
