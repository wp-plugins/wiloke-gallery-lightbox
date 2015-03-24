<div class="pi-gallery">
	<li class="attachment img-item width-300" data-id="">
		<?php 
			if ( isset($this->aOptions['video_placeholder']) && !empty($this->aOptions['video_placeholder']) ) : 
		?>
		<img src="<?php echo $this->aOptions['video_placeholder']; ?>">
		<?php else :
		?>
		<img src="<?php echo PIG_MD_URL . 'images/video-placeholder.png'; ?>">
		<?php 
			endif;
		?> 
	</li>
</div>
<div class="bg-action">
	<input class="pi_pretty_input medium" type="text" name="pi_lightbox[video_link]" placeholder="Enter Youtube or Vimeo Url" value="<?php echo isset($this->aOptions['video_link']) ? $this->aOptions['video_link'] : ''; ?>">
	<button class="button button-primary js_add_video">Add Video</button>
	<input class="video_type" type="hidden" name="pi_lightbox[video_type]" value="<?php echo isset($this->aOptions['video_type']) ? $this->aOptions['video_type'] : ''; ?>">
	<input class="video_id" type="hidden" name="pi_lightbox[video_id]" value="<?php echo isset($this->aOptions['video_id']) ? $this->aOptions['video_id'] : ''; ?>">
	<input class="video_placeholder" type="hidden" name="pi_lightbox[video_placeholder]" value="<?php echo isset($this->aOptions['video_placeholder']) ? $this->aOptions['video_placeholder'] : ''; ?>">
</div>