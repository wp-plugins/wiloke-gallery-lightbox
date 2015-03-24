<div class="pi_block">	
	<div class="pi-label">
		<h4>Get Image From</h4>
	</div>
	<div class="pi-settings">
		<select name="pi_lightbox[use]" class="pi_change_settings">
			<option value="default" data-show=".pi-media-upload" data-hide=".pi-flickr" <?php echo isset($this->aOptions['use']) && $this->aOptions['use'] == 'default' ? 'selected' : ''; ?>>Use media upload</option>
			<option value="flickr" data-hide=".pi-media-upload" data-show=".pi-flickr" <?php echo isset($this->aOptions['use']) && $this->aOptions['use'] == 'flickr' ? 'selected' : ''; ?>>Flickr</option>
		</select>
	</div>
</div>

<div class="pi_block pi-media-upload">
	<ul class="pi-gallery">
		<?php 
			if ( isset($this->aOptions['gallery_id']) && !empty($this->aOptions['gallery_id']) )
			{
				$aIds = explode(",", $this->aOptions['gallery_id']);

				foreach ( $aIds as $id ) :
				?>
				<li class="attachment img-item width-300" data-id="<?php echo $id ?>">
					<?php echo wp_get_attachment_image($id); ?>
					<a class="pi_remove" href="#">
	                    <span class="dashicons dashicons-no-alt del-image"></span>
	                </a>
				</li>
				<?php 
				endforeach;
			}else{
				?>
				<li class="attachment img-item width-300" data-id="">
					<img src="<?php echo PIG_MD_URL . 'images/image-placeholder.png'; ?>">
				</li>	
				<?php 
			}
		?>
	</ul>

	<div class="bg-action">
		<button class="button button-primary js_upload multiple" data-func="prev" data-method="html" data-target=".pi-gallery">Upload</button>
		<input type="hidden" class="pi_pretty_input" name="pi_lightbox[gallery_id]" value="<?php echo isset($this->aOptions['gallery_id']) ? $this->aOptions['gallery_id'] : ''; ?>">
	</div>
</div>

<div class="pi-flickr">
	<div class="pi_block">
		<div class="pi-label">
			<h4>Flickr Id</h4>
			<p><a href="http://idgettr.com/" target="_blank">Get Flickr Id</a></p>
		</div>
		<div class="pi-settings">
			<input type="text" class="pi_flickr_info" data-key="flickr_id" name="pi_lightbox[flickr_id]" value="<?php echo isset($this->aOptions['flickr_id']) ? $this->aOptions['flickr_id'] : ''; ?>">
		</div>
	</div>
	<div class="pi_block">
		<div class="pi-label">
			<h4>Limit</h4>
			<p>How many items you want to loop through</p>
		</div>
		<div class="pi-settings">
			<input type="text" class="pi_flickr_info" data-key="flickr_limit" name="pi_lightbox[flickr_limit]" value="<?php echo isset($this->aOptions['flickr_limit']) ? $this->aOptions['flickr_limit'] : ''; ?>">
		</div>
	</div>
	<div class="pi_block">
		<div class="pi-label">
			<h4>Image Size</h4>
		</div>
		<select name="pi_lightbox[flickr_image_size]" class="pi_flickr_changesize pi_flickr_info" data-key="flickr_image_size">
			<?php 
				$aFlickrSize = array("image_s"=>"Small", "image_t"=>"Thumbnail", "image_m"=>"Medium", "image"=>"Original", "image_b"=>"Big");

				foreach ( $aFlickrSize as $size => $name ) : 
					$selected = isset($this->aOptions['flickr_image_size']) && $size ==  $this->aOptions['flickr_image_size'] ? 'selected' : '';
			?>
				<option value="<?php echo $size; ?>" <?php echo $selected; ?>><?php echo $name ?></option>
			<?php 
				endforeach;
			?>
		</select>
	</div>
	<div class="pi_block">
		<div class="bg-action">
			<button class="button button-primary js_get_flickr">Get</button>
			<button class="button button-primary js_toggle_image">Hide Image</button>
		</div>
	</div>
	<div  class="pi-gallery">
		<ul id="pi-show-image"></ul>
	</div>
	<div  class="hidden">
		<input type="hidden" name="pi_lightbox[flickr_get_data]" id="flickr_re_data" value="<?php echo isset($this->aOptions['flickr_get_data']) ? esc_attr($this->aOptions['flickr_get_data']) : ''; ?>">
	</div>
</div>