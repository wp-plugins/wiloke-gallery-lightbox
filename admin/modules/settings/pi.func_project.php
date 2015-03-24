<div class="pi_block">	
	<div class="pi-label">
		<h4>Small Intro</h4>
	</div>
	<div class="pi-settings">
		<textarea name="pi_lightbox[project_intro]"><?php echo isset($this->aOptions['project_intro']) ? esc_textarea($this->aOptions['project_intro']) : ''; ?></textarea>
	</div>
</div>


<div class="pi_block">	
	<div class="pi-label">
		<h4>Project Link</h4>
	</div>
	<div class="pi-settings">
		<input type="text" class="pi_pretty_input" name="pi_lightbox[project_link]" value="<?php echo isset($this->aOptions['project_link']) ? esc_url($this->aOptions['project_link']) : ''; ?>">
	</div>
</div>