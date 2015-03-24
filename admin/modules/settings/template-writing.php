<?php
	$id = get_the_ID();

	$aData 	   	= get_post_meta($id, '_pi_lightbox', true);
	$content    = isset($aData['project_intro']) && !empty($aData['project_intro']) ?  $aData['project_intro'] : '';

?>

<div class="pi_display_writting">
	<!-- <div class="container-fluid"> -->
		<div class="pi-wrap-content pi-button">
			<a href="javascript:" class="mfp-close">×</a>
			<div class="pi-html clearfix">
				
				<?php 
					if ( $aData && !empty($aData) ) :
				?>
				<div class="pi-media-block">
					<?php 
						if ( isset( $aData['video_link'] )  && $aData['video_link'] !='' )
						{

						}elseif( (isset($aData['gallery_id']) &&  $aData['gallery_id'] != '' ) || (isset($aData['flickr_id']) &&  $aData['flickr_id'] != '' ) )
						{
							$isFlickr = isset($aData['use']) && $aData['use'] == 'flickr' ? true : false;
							
							$imgID = $isFlickr ? $aData['flickr_get_data'] : $aData['gallery_id'];

							?>
							<div  class="owl-demo pi_owl_one no-autoheight">
								<?php 
									$parseId = explode(",", $imgID);
									foreach ( $parseId as $k => $v ) 
									{
										?>
										<img src="<?php echo !$isFlickr ? wp_get_attachment_url($v) : esc_url($v); ?>" alt="<?php the_title() ?>">
										<?php
									}
								?>
							</div>
							<?php
						}
					?>
				</div>
				<?php
					endif;
				?>

				<div class="pi-right-block">
				<?php 
					if ( !empty($content) ) :
						echo stripslashes($content);
					endif;
				?>
				</div>
			</div>
		</div>
	<!-- </div> -->
</div>
<script>

			jQuery('.pi-html').perfectScrollbar();
		
		
</script>