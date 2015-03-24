<div id="pi_accordion" class="">
	<h3 class="hndle">Customize Gallery Wrapper</h3>
	<div class="md-row-item pi_setting">

		<div class="row-inner">
			<div class="md-name">
				<label>Width Of Gallery - unit px or percent</label>
				<p>Maximum value of gallery width</p>
			</div>
			<div class="md-setting"> 
				<input type="text" name="pi_width_of_wrapper" value="<?php echo isset($aOldData['pi_width_of_wrapper']) ? $aOldData['pi_width_of_wrapper'] : '';  ?>" placeholder="Ex: 100% or 1024px" data-type="css" data-style="width">
			</div>
		</div>

		<div class="row-inner">
			<div class="md-name">
				<label>Extra Class</label>
				<p>Add a class to wrap Html element</p> 
			</div>
			<div class="md-setting"> 
				<input type="text" name="pi_custom_class" value="<?php echo isset($aOldData['pi_custom_class']) ? $aOldData['pi_custom_class'] : '';  ?>" data-type="css" data-style="addClass">
			</div>
		</div>
		<div class="row-inner"> 
			<div class="md-name"> 
				<label>Extra ID</label> 
				<p>Add an id to wrap Html element</p>
			</div>
			<div class="md-setting">
				<input type="text" name="pi_custom_id" value="<?php echo isset($aOldData['pi_custom_id']) ? $aOldData['pi_custom_id'] : '';  ?>" data-type="css" data-style="addID">
			</div>
		</div>
	</div> 

	<h3 class="hndle">Title &amp; Description</h3> 
	<div class="md-row-item pi_setting">
		<div class="row-inner">
			<div class="md-name">
				<label>Title</label>
				<p>Leave blank if no title is needed</p>
			</div>
			<div class="md-setting">
				<input type="text" name="pi_title" value="<?php echo isset($aOldData['pi_title']) ? $aOldData['pi_title'] : '';  ?>" data-type="html">
			</div>
		</div>

		<!-- <div class="row-inner">
			<div class="md-name">
				<label>Font size - Unit px </label>
				<p>Leave it blank if you want to use default</p>
			</div>
			<div class="md-setting">
				<input type="text" name="pi_fontsize_title" value="<?php echo isset($aOldData['pi_fontsize_title']) ? $aOldData['pi_fontsize_title'] : '';  ?>">
			</div>
		</div> -->


		<div class="row-inner">
			<div class="md-name">
				<label>Title Color</label>
			</div>
			<div class="md-setting picolor">
				<input type="text" class="js_color_picker" name="pi_title_color" value="<?php echo isset($aOldData['pi_title_color']) ? $aOldData['pi_title_color'] : '';  ?>" data-type="css" data-style="color">
			</div>
		</div>



		<div class="row-inner">
			<div class="md-name">
				<label>Text align</label>
				<p>Set text alignment for title</p>
			</div>
			<div class="md-setting">
				<span class="container-select">
					<select class="element-select white pi_change" name="pi_textalign_title" data-add="blockquote" data-name="context" data-type="css" data-style="text-align">
						<option value="left" <?php echo isset($aOldData['pi_textalign_title']) && $aOldData['pi_textalign_title'] == 'left' ? 'selected' : '';  ?> >Left</option>
						<option value="center" <?php echo isset($aOldData['pi_textalign_title']) && $aOldData['pi_textalign_title'] == 'center' ? 'selected' : '';  ?>>Center</option>
						<option value="right" <?php echo isset($aOldData['pi_textalign_title']) && $aOldData['pi_textalign_title'] == 'right' ? 'selected' : '';  ?>>Right</option>
					</select>
				</span>
			</div>
		</div>	

		<div class="row-inner">
			<div class="md-name">
				<label>Description</label>
				<p>Set text alignment for description. Leave blank if no description is needed</p>
			</div>
			<div class="md-setting">
				<input type="text" name="pi_des" value="<?php echo isset($aOldData['pi_des']) ? $aOldData['pi_des'] :   '';  ?>" data-type="html">
			</div>
		</div>

	<!-- 	<div class="row-inner">
			<div class="md-name">
				<label>Font Size - Unit px</label>
				<p>Leave it blank if you want to use default</p>
			</div>
			<div class="md-setting">
				<input type="text" name="pi_fontsize_des" value="<?php echo isset($aOldData['pi_fontsize_des']) ? $aOldData['pi_fontsize_des'] :   '';  ?>">
			</div>
		</div>
 -->
		<div class="row-inner">
			<div class="md-name">
				<label>Description Color</label>
			</div>
			<div class="md-setting picolor">
				<input type="text" class="js_color_picker" name="pi_des_color" value="<?php echo isset($aOldData['pi_des_color']) ? $aOldData['pi_des_color'] :   '';  ?>" data-type="css" data-style="color">
			</div>
		</div>

		<div class="row-inner">
			<div class="md-name">
				<label>Text align</label>
				<p>Set text alignment for description</p>
			</div>
			<div class="md-setting">
				<span class="container-select">
					<select class="element-select white pi_change" name="pi_textalign_des" data-add="blockquote" data-name="context" data-type="css" data-style="text-align">
						<option value="left" <?php echo isset($aOldData['pi_textalign_des']) && $aOldData['pi_textalign_des'] == 'left' ?   'selected' : '';  ?>>Left</option>
						<option value="center" <?php echo isset($aOldData['pi_textalign_des']) && $aOldData['pi_textalign_des'] == 'center' ?   'selected' : '';  ?>>Center</option>
						<option value="right" <?php echo isset($aOldData['pi_textalign_des']) && $aOldData['pi_textalign_des'] == 'right' ?   'selected' : '';  ?>>Right</option>
					</select>
				</span>
			</div>
		</div>

		
	</div>


	<h3 class="hndle">Gallery Settings</h3>
	<div class="pi_owl_not_one pi_setting">

		<div class="md-row-item">
			<div class="md-name">
				<label>Display</label>
			</div>
			<div class="md-setting">
				<span class="container-select">
					<select class="element-select white pi_change pi_change_settings" name="pi_display">
						<option value="one_item" data-show=".pi_owl, .pi_one" data-hide=".pi_filter, .pi_menu_slider, .scattered_palaroinds" <?php echo isset($aOldData['pi_display']) && $aOldData['pi_display'] == 'one_item' ?   'selected' : '';  ?>>Simple Slideshow</option>
						<option value="menu_slider" data-show=".pi_owl, .pi_menu_slider" data-hide=".pi_filter, .pi_one, .scattered_palaroinds" <?php echo isset($aOldData['pi_display']) && $aOldData['pi_display'] == 'menu_slider' ?   'selected' : '';  ?>>Carousel Slider</option>
						<option value="masory" data-show=".pi_filter" data-hide=".pi_owl, .pi_menu_slider, .pi_one, .scattered_palaroinds" <?php echo isset($aOldData['pi_display']) && $aOldData['pi_display'] == 'masory' ?   'selected' : '';  ?>>Masory</option> 
						<option value="scattered_palaroinds" data-show=".scattered_palaroinds"  data-hide=".pi_owl, .pi_menu_slider, .pi_one, .pi_filter" <?php echo isset($aOldData['pi_display']) && $aOldData['pi_display'] == 'scattered_palaroinds' ?   'selected' : '';  ?>>Scattered Polaroids</option> 
					</select>
				</span>
			</div>
		</div>

	
		<div class="md-row-item  pi_menu_slider">
			<div class="md-name">
				<label>The height of row - unit px</label>
				<p>Leave it blank if you want auto height</p>
			</div>
			<div class="md-setting">
				<input type="text" placeholder="200" name="pi_height_of_row" value="<?php echo isset($aOldData['pi_height_of_row']) ? $aOldData['pi_height_of_row'] :   '';  ?>">
			</div>
		</div>

		<div class="md-row-item">
			<div class="md-name">
				<label>Select Album</label>
			</div>

			<div class="md-setting">
				<span class="container-select">
					<select class="element-select white pi_change" name="pi_gallery">
						<option value="">---------</option>
						<?php 
							$getTerms = get_terms('pi_albums');
							$i = 0;
							if ( !empty($getTerms) && !is_wp_error($getTerms) )
							{
								
							  	foreach ( $getTerms as $term ) 
							  	{
							  		$selected = isset($aOldData['pi_gallery']) && $aOldData['pi_gallery'] == $term->term_id ?   'selected' : ''; 
								
									if( empty($selected) && $i == 0 )
									{
										$selected = 'selected';
									}

							  		?>
							  		<option value="<?php echo $term->term_id ?>" <?php echo $selected; ?>><?php echo $term->name ?></option>
							  		<?php
							  		$i++;
							  	}
							}
						?>
					</select>
				</span>
			</div>
		</div>

		<div class="md-row-item pi_slide pi_menu_slider pi_filter">
			<div class="md-name">
				<label>Number of columns display</label>
			</div>
			<div class="md-setting">
				<input type="text" name="pi_number_of_columns" value="<?php echo isset($aOldData['pi_number_of_columns']) ? $aOldData['pi_number_of_columns'] :   '';  ?>" data-type="data" data-name="items">
			</div>
		</div>

		<div class="md-row-item pi_slide pi_filter">
			<div class="md-name">
				<label>Number of images per page</label>
			</div>
			<div class="md-setting">
				<input type="text"  name="pi_number_of_images_perpage" value="<?php echo isset($aOldData['pi_number_of_images_perpage']) ? $aOldData['pi_number_of_images_perpage'] :   '';  ?>">
			</div>
		</div>

		<div class="md-row-item  pi_filter">
			<div class="md-name">
				<label>Gutter - unit px</label>
				<p>The horizontal space between item elements</p>
			</div>
			<div class="md-setting">
				<input type="text" placeholder="10"  name="pi_filter_gutter" value="<?php echo isset($aOldData['pi_filter_gutter']) ? $aOldData['pi_filter_gutter'] :   '';  ?>" data-type="data" data-name="gutter">
			</div>
		</div>

		<div class="md-row-item  pi_filter">
			<div class="md-name">
				<label>Margin Bottom - unit px</label>
				<p>The vertical space between elements</p>
			</div>
			<div class="md-setting">
				<input type="text" placeholder="10"  name="pi_filter_margin_bottom" value="<?php echo isset($aOldData['pi_filter_margin_bottom']) ? $aOldData['pi_filter_margin_bottom'] :   '';  ?>" data-type="data" data-name="marginbottom">
			</div>
		</div>
		
		<div class="md-row-item pi_slide  pi_owl">
			<div class="md-name">
				<label>Autoplay</label>
			</div>
			<div class="md-setting">
				<span class="container-select">
					<select class="element-select js_one_target white pi_change" name="pi_autoplay">
						<option value="false" data-hide=".pi_speed"  <?php echo isset($aOldData['pi_autoplay']) && $aOldData['pi_autoplay'] == 'false' ? 'selected' :   '';  ?>>False</option>
						<option value="true"  data-show=".pi_speed" <?php echo isset($aOldData['pi_autoplay']) && $aOldData['pi_autoplay'] == 'true' ? 'selected' :   '';  ?>>True</option>
					</select>
				</span>
			</div>
		</div>

		<div class="md-row-item  pi_slide   pi_owl">
			<div class="md-name">
				<label>Slider Speed - unit: miliseconds</label>
			</div>
			<div class="md-setting">
				<input type="text"  placeholder="Speed" name="pi_slider_speed" value="<?php echo isset($aOldData['pi_slider_speed']) ? $aOldData['pi_slider_speed'] :   '';  ?>">
			</div>
		</div>

		<div class="md-row-item pi_speed pi_slide   pi_owl">
			<div class="md-name">
				<label>Pagination Speed - unit: miliseconds</label>
			</div>
			<div class="md-setting">
				<input type="text"  placeholder="Speed" name="pi_pagination_speed" value="<?php echo isset($aOldData['pi_pagination_speed']) ? $aOldData['pi_pagination_speed'] :   '';  ?>">
			</div>
		</div>

		<div class="md-row-item pi_setting scattered_palaroinds">
			<div class="row-inner">
				<div class="md-name">
					<label>Caption Text Color</label>
				</div>
				<div class="md-setting picolor">
					<input type="text" class="js_color_picker" name="pi_caption_text_color" value="<?php echo isset($aOldData['pi_caption_text_color']) ? $aOldData['pi_caption_text_color'] : '';  ?>">
				</div>
			</div>
		</div>
		
		<input type="hidden" id="pi_post_id" name="pi_post_id" value="<?php echo isset($aOldData['pi_post_id']) ? $aOldData['pi_post_id'] : '';  ?>">
		<input type="hidden" id="pi_post_type" name="pi_post_type" value="<?php echo isset($aOldData['pi_post_type']) ? $aOldData['pi_post_type'] : '';  ?>">
		<input type="hidden" id="pi_number_id" name="numberid" value="<?php echo isset($aOldData['numberid']) ? $aOldData['numberid'] : '';  ?>">
		
	</div>	
</div>