<?php
/*
Plugin Name:  Gallery Lightbox
Plugin URI: http://demo.wiloke.net/wordpress/wp-content/uploads/2014/11/gallery-lightbox.zip
Author URI: wikoke.com
Author: wiloke
Version: 1.1
Description: Easy to create a gallery
License: Under GPL2

Copyright 2014 wiloke (email : piratesmorefun@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define('PIG_MD_DIR',  dirname(__FILE__) . '/admin/modules/');
define('PIG_MD_URL',  plugin_dir_url(__FILE__) . 'admin/assets/');
define('PIG_POST_TYPE', 'pi_gallery');

class piGallery
{ 
	const PIG_POST_TYPE = 'pi_gallery';
	const LANG  = 'pi_gallery';
	const PI_SHORTCODENAME = 'pilb_lightbox';

	public $aOptions = "";
  
	public function __construct()
	{	
		add_action('init', array($this, 'pi_includes'));
		// $this->pi_includes(); 
 
		add_action('init', array($this, 'piReferesh'));
		add_action('init', array($this, 'pi_create_post'));
		add_action('edit_form_after_title', array($this, 'pi_choose_type'));
		add_action('edit_form_after_editor', array($this, 'pi_close_div'));
		add_action('add_meta_boxes', array($this, 'pi_settings_builder'), 10, 2);

		add_action('save_post', array($this, 'pi_update_data'));

		// scripts
		add_action('admin_enqueue_scripts', array($this, 'pi_admin_enqueue_scripts'));

		 
		/* ------------------------- */ 
		/* Tinymce Button
		/* ------------------------- */
		add_filter('mce_buttons_2', array($this, 'pi_add_buttons'));
		add_filter('mce_external_plugins', array($this, 'pi_add_js'));


		/* ------------------------- */
		/* Include Popup Into Footer
		/* ------------------------- */  
		add_action('admin_footer', array($this, 'pi_dialog'));

		/* ------------------------- */
		/* frontend enqueue scripts
		/* ------------------------- */
		add_action('wp_enqueue_scripts', array($this, 'pi_frontend_enqueue_scripts'), 999);


		/* ------------------------- */
		/* ajax
		/* ------------------------- */
		add_action('wp_ajax_hello_ajax', array($this, 'pi_ajax_machine'));
		add_action('wp_ajax_parse_shortcode', array($this, 'pi_ajax_parse_shortcodes'));

		/* ------------------------ */
		/* Filter posts
		/* ------------------------ */	
		// add_filter('the_content', array($this,  'pi_filter_content'));


		add_filter( 'query_vars',  array($this,'portfolio_livepreview'));


		add_filter('single_template',  array($this,'portfolio_live_preview_template'));

		add_action('wp_ajax_pi_lightbox', array($this, 'pi_query_post'));

		add_action('wp_ajax_loadmore', array($this, 'pi_loadmore'));

		add_action('wp_ajax_pi_edit_gallery', array($this, 'pi_edit_gallery'));
		add_action('wp_ajax_pi_change_gallery_settings', array($this, 'pi_change_gallery_settings'));
	}	

	public function pi_change_gallery_settings()
	{
		if ( empty($_POST['pi_post_id']) || empty($_POST['pi_gallery']) || empty($_POST['pi_post_type']) || empty($_POST['numberid']) ) 
		{
			echo "error";
			die();
		}
      	
		$data = json_encode($_POST);
		$data = urlencode($data);

		$args = array("post_type"=>$_POST['pi_post_type'], "post__in"=>array($_POST['pi_post_id']));

		$query = new WP_Query($args);

		while ( $query->have_posts() ) : $query->the_post();
			$content = get_the_content();
		endwhile;wp_reset_postdata();

		$numbderID = $_POST['numberid'];
		

		$pattern = "/\[pilb_lightbox(!?[^data_shortcodes]*)data_shortcodes='([^']*)'(!?[^data_numberid]*)data_numberid='$numbderID'([^\]]*)\]/";

		$newContent = preg_replace($pattern, "[pilb_lightbox data_shortcodes='$data' data_numberid='$numbderID']", $content);

		wp_update_post(array('ID'=>$_POST['pi_post_id'],'post_content'=>$newContent));
		echo "success";
		die();
	}

	public function pi_includes()
	{
		include ( plugin_dir_path(__FILE__) . 'pi.shortcode.php' );
		$initShortcodes = new piGLShortcodes();
	}

	public static function pi_gallery_builder($aRaw, $editButton=true) 
	{
		/* ------------------------ */
		/* If the current section is edit.
		/*  Do not allow i include button edit
		/* ------------------------ */	
		if ( empty($aRaw) ) return "<p>There are no gallery</p>";
			$aData = urldecode($aRaw);
			$aData = json_decode($aData, true);


			// If current user is logged in 
			$piEdit="";

	 		// $piEdit.="<button class='pi-gallery-edit edit-link' data-currentslider='".$aData['pi_display']."' data-id='".$aData['pi_post_id']."' data-shortcodes='".$aRaw."' data-numberid='".$aData['numberid']."'>";
	 		// 	$piEdit .= '<span href="javascript:void(0)" class="dashicons dashicons-edit post-edit-link"></span>';
	 		// $piEdit.='</button>';

			  

	 		$numberOfImagePerpage 	= !empty($aData['pi_number_of_images_perpage']) ? $aData['pi_number_of_images_perpage'] : 6;
			// run query
			$args = array
			( 
			    'post_type' 		=> 'pi_gallery',
			    'post_status'		=> 'publish',
			    'posts_per_page'	=> $numberOfImagePerpage,
			    'tax_query' 		=> array
			    (
			        array
			        (
			            'taxonomy' 	=> 'pi_albums',
			            'terms' 	=> $aData['pi_gallery'],
			            'field' 	=> 'term_id'
			        )
			    )
		    );
			
			
			$piQuery 	= new WP_Query($args);

			$aTerms 	= get_term( $aData['pi_gallery'], 'pi_albums', ARRAY_A);

			$countTerm 	= 0;
			if ( !is_wp_error($aTerms) && !empty($aTerms) )
			{
				$countTerm	= $aTerms['count']; 
			}
			
			

			$numberOfColumns 	= $aData['pi_number_of_columns'];
			$gutter 		 	= $aData['pi_filter_gutter']; 
			$quantityLoadmore	= $numberOfColumns*2;

			
			$createId 				= uniqid("pi_query_");
			$buttonLoadMore 		= $numberOfImagePerpage < $countTerm ? '<div class="clearfix pi-loadmorewrap" data-terms="'.$aData['pi_gallery'].'" data-id="'.$createId.'"  data-quantity="'.$quantityLoadmore.'" data-total="'.$countTerm.'" data-showmore="'.$numberOfColumns.'"><button class="btn btn-success pi-loadmore">'.__('Load More', 'pi_lightbox_').'</button></div>' : '';

			$getFilterIds = array();
			
			global $post;

			$gallery = '';

			$before = microtime(true);

			$countItem = 0;
			while ( $piQuery->have_posts()  ) : $piQuery->the_post();
				$countItem++;
				$filterId = wp_get_post_terms($post->ID, 'pi_filters');
				$filter   = "pirates";

				$keyFilter = "";
				$aKeyFilters = array();

				if ( !is_wp_error($filterId) )
				{
					foreach ( $filterId as $termId )
					{
						if ( !array_key_exists($termId->term_id, $getFilterIds) )
						{
							$filter 						= $termId->term_id;
							$new[$termId->term_id] 			= $termId->name; 
							$getFilterIds[$termId->term_id] = $termId->name;
							$new 							= array();
						}

						$keyFilter .= isset($keyFilter) ? " pi_lightbox_" . $termId->term_id : '';
					}
				}

				/* -------------------- */
				/* Content Builder
				/* -------------------- */

				/* Switch */
				$galleryID = "";
				$getIds = get_post_meta($post->ID, '_pi_lightbox', true);
				$sImgs = "";

				$getMedia = isset($getIds['mode']) ? $getIds['mode']: '';
				$wrapBy = isset($aData['pi_display']) && $aData['pi_display'] == 'scattered_palaroinds' ? 'figure' : 'div';

				$captionTextColor = isset($aData['pi_caption_text_color']) && !empty($aData['pi_caption_text_color']) ? $aData['pi_caption_text_color'] : '#939393';

				if ( $getMedia != '' ) :	
					switch (  $getMedia ) :
						case 'images':
							if ( ( isset($getIds['gallery_id']) && !empty($getIds['gallery_id']) ) || (  isset($getIds['flickr_get_data']) && !empty( $getIds['flickr_get_data']) ) )
							{
								$isFlickr = isset($getIds['use']) && $getIds['use'] == 'flickr' ? true : false;
								

								if ( !$isFlickr ) :
									$parseId 	= explode(",", $getIds['gallery_id']);
									foreach ( $parseId as $k => $id )
									{	
										if ( $k == 0 ) 
										{
											$first = wp_get_attachment_url( $id );
										}else{
											$sImgs .= '<a href="'.wp_get_attachment_url( $id ).'" class="js_magific" title="'.get_the_title($post->ID).'"></a>';
										}
									}
								else :
									$parseId  = explode(",", $getIds['flickr_get_data']);

									foreach ( $parseId as $k => $link )
									{	
										if ( $k == 0 )  
										{
											$first = $link;
										}else{
											$sImgs .= '<a href="'.$link.'" class="js_magific" title="'.get_the_title($post->ID).'"></a>';
										}
									}
								endif;

								if ( has_post_thumbnail() )
								{
									$gallery .= '<'.$wrapBy.' class="popup-gallery mix_all pirates mix '.$keyFilter.'">';
										$gallery .= '<a href="'.$first.'" class="js_magific pi_image_wrap_thumbnail" title="'.get_the_title($post->ID).'">';
											$gallery .= get_the_post_thumbnail($post->ID);
										$gallery .= '</a>';
										if ( $wrapBy == 'figure' ) :
											$gallery .= '<figcaption><h2 class="photostack-title" style="color:'.$captionTextColor.'">'.get_the_title($post->ID).'</h2></figcaption>';
										endif;
										$gallery .= $sImgs;
									$gallery .= '</'.$wrapBy.'>';
								}

							}
						break;

						case 'video':
							$getVideoType = $getIds['video_type'] ? $getIds['video_type'] : '';
							$link 		  = $getIds['video_link'] ? str_replace("https", "http", $getIds['video_link']) : '';


							if ( !empty($getVideoType) && !empty($link) ) :
								$target = 'popup-'.$getVideoType;
						
								if ( has_post_thumbnail() )
								{
									$gallery .= '<'.$wrapBy.' class=" mix_all pirates mix pi_lightbox_'.$filter.'">';
									
									$gallery .= '<a href="'.$link.'"  class="popup-'.$getVideoType.'">';
										$gallery .= get_the_post_thumbnail($post->ID);
									$gallery .= '</a>';
									if ( $wrapBy == 'figure' ) :
										$gallery .= '<figcaption><h2 class="photostack-title" style="color:'.$captionTextColor.'">'.get_the_title($post->ID).'</h2></figcaption>';
									endif;
									$gallery .= '</'.$wrapBy.'>';
								}

							endif; 
						break;

						case 'project':
							$liveurl = "data-url=\"".home_url()."/?pi_gallery=".$post->post_name."&amp;pi_livepreview=true"."\"";
							$gallery .= '<'.$wrapBy.' class="popup-ajax mix_all pirates mix pi_lightbox_'.$filter.'" '.$liveurl.'>';
								$gallery .= '<a href="'.get_permalink($post->ID).'">';
									$gallery .= get_the_post_thumbnail($post->ID);
								$gallery .= '</a>';
								if ( $wrapBy == 'figure' ) :
									$gallery .= '<figcaption><h2 class="photostack-title" style="color:'.$captionTextColor.'">'.get_the_title($post->ID).'</h2></figcaption>';
								endif;
							$gallery .= '</'.$wrapBy.'>';
						break; 

						case 'googlemap':
							$gallery .= '<'.$wrapBy.' class="mix_all pirates mix pi_lightbox_'.$filter.'">';
								$gallery .= '<a class="popup-googlemap" href="'.esc_url($getIds['map_link']).'">';
									$gallery .= get_the_post_thumbnail($post->ID);
								$gallery .= '</a>';
								if ( $wrapBy == 'figure' ) :
									$gallery .= '<figcaption><h2 class="photostack-title" style="color:'.$captionTextColor.'">'.get_the_title($post->ID).'</h2></figcaption>';
								endif;
							$gallery .= '</'.$wrapBy.'>';
						break;

						case 'linkto':
							$gallery .= '<'.$wrapBy.' class="mix_all pirates mix pi_lightbox_'.$filter.'">';
								$gallery .= '<a target="_blank" href="'.esc_url($getIds['linkto']).'">';
									$gallery .= get_the_post_thumbnail($post->ID);
								$gallery .= '</a>';
								if ( $wrapBy == 'figure' ) :
									$gallery .= '<figcaption><h2 class="photostack-title" style="color:'.$captionTextColor.'">'.get_the_title($post->ID).'</h2></figcaption>';
								endif;
							$gallery .= '</'.$wrapBy.'>';
						break;
					endswitch;
				endif;
			endwhile; wp_reset_postdata();


			$after = microtime(true);
			
			$galleryWidth = isset($aData['pi_width_of_wrapper']) && !empty($aData['pi_width_of_wrapper']) ? $aData['pi_width_of_wrapper'] : '100%';

			$html = "";
			$html .= '<div id="'.$aData['pi_custom_id'].'" data-width="'.$galleryWidth.'" class="pi-gallery-lighbox pi_custom_class pi_custom_id '.$aData['pi_custom_class'].'">';

			if ( !empty($aData['pi_title']) || !empty($aData['pi_des']) ) 
			{

				$html .= '<div class="pi-gallery-header">';
					if ( !empty($aData['pi_title']) ) : 
						$html .=  '<h2 class="pi-gallery-title pi_title pi_title_color pi_textalign_title"  data-color="'.$aData['pi_title_color'].'" data-textalign="'.$aData['pi_textalign_title'].'">';
							$html .= sprintf( __('%s', 'pi_lightbox'),  $aData['pi_title']); 
						$html .=  '</h2>';
					endif;
					if ( !empty($aData['pi_des']) ) :
						$html .=  '<p class="pi-gallery-des pi_des pi_des_color pi_textalign_des"   data-color="'.$aData['pi_des_color'].'" data-textalign="'.$aData['pi_textalign_des'].'" >';
					 		$html .= sprintf( __('%s', 'pi_lightbox'),  $aData['pi_des']);  
					 	$html .=  '</p>';
					endif;
				$html .=  '</div>';
			}

			$navFilters = "";
			
			$navFilters .= '<li class="filter transition pi_filter_all active" data-filter="*">';
	      	$navFilters .= __('All', 'pi_lightbox');
			$navFilters .= '</li>';
			$navFilters .= self::pi_create_button($getFilterIds);
		

			$numberOfColumns = $countItem <  $numberOfColumns ? $countItem : $numberOfColumns;

			$html .= '<div  class="pi_lightbox_wrapper">';
					$html .= '<div class="js_contain_data pi_width_of_wrapper pi_filter_gutter" style="width: 100%;" data-columns="'.$numberOfColumns.'" data-gutter="'.$gutter.'">';
				if ( $aData['pi_display'] == 'masory' )
				{
						$uniqidId = uniqid("pi_masony_");
						$html .= '<div  class="js_init_slider pi_container_mixup clearfix" data-id="#'.$uniqidId.'">';
							$html .= '<div  class="nav_filter clear">';
								$html .= '<ul  class="clearfix pi_filters">' . $navFilters . '</ul>';
							$html .= '</div>';
							$html .= '<div id="'.$uniqidId.'" class="clearfix masonryContainer pi_center pi_filter_margin_bottom" data-marginbottom="'.$aData['pi_filter_margin_bottom'].'">';
								$html .= $gallery;
							$html .= '</div>';
							$html .= $buttonLoadMore;
						$html .= '</div>';
				}elseif(  $aData['pi_display'] == 'one_item' || $aData['pi_display'] == 'menu_slider' )
				{		
						$showMoreClass = "";
						$autoPlay =  isset($aData['pi_autoplay']) ? $aData['pi_autoplay']  : 'false';
						$sliderSpeed =  isset($aData['pi_slider_speed']) ? $aData['pi_slider_speed']  : 300;
						$pagSpeed =  isset($aData['pi_pagination_speed']) ? $aData['pi_pagination_speed']  : 400;
					 	$setHeight = isset($aData['pi_height_of_row']) ? $aData['pi_height_of_row'] : '';

						if ( isset($aData['pi_show_more']) )
						{
							switch ($aData['pi_show_more']) {
								case 'nav_pagi':
									$showMoreClass = 'pi_nav_pagi';
									break;

								case 'pagi':
									$showMoreClass = 'pi_pagi';
									break;
								
								case 'nav':
									$showMoreClass = 'pi_nav';
									break;
							}
						}

						if ( $aData['pi_display'] == 'one_item' )
						{
							$html .= '<div class="js_init_slider owl-demo pi-owltheme pi_owl_one pi_center '.$showMoreClass.'" data-autoplay="'.$autoPlay.'" data-sliderspeed="'.$sliderSpeed.'" data-pagspeed="'.$pagSpeed.'">';
						}else{
							$html .= '<div class="js_init_slider owl-demo pi-owltheme pi_owl_nav_pag pi_center '.$showMoreClass.'" data-autoplay="'.$autoPlay.'" data-height="'.$setHeight.'px"  data-items="'.$aData['pi_number_of_columns'].'" data-gutter="'.$gutter.'">';
						}
				 			
								$html .= $gallery;
							$html .= '</div>';	
				}elseif( $aData['pi_display'] == 'scattered_palaroinds' )
				{
					$createId  = uniqid("pi_photostack");
					$html .= '<section id="'.$createId.'" class="pi-photostack photostack photostack-start">';
						$html .= '<div>';
							$html .= $gallery;
						$html .= '</div>';
					$html .= '</section>';
				}
				$html .= '</div>';
			$html .= '</div>';

			
			$html .= '</div>';
			
			
			return $html;
	}	


	public static function pi_create_button($aIds="")
	{
		if ( empty($aIds) ) return;

		
		$sButton = "";

		foreach ( $aIds as $k => $name )
		{
				$sButton .= '<li class="filter transition" data-filter=".pi_lightbox_'.$k.'">';
				   $sButton .= __($name, 'pi_lightbox');
				$sButton .= '</li>';
		}

		return $sButton;
	}	

	public function pi_edit_gallery()
	{
		if ( empty($_POST['postID'] ) || empty($_POST['shortcodes'])  )  die();

		$parse 		= urldecode($_POST['shortcodes']);

		$aOldData 	= json_decode($parse, true);

		include ( plugin_dir_path(__FILE__) . 'admin/modules/settings/popup-settings.php' );

		die(); 
	}
	

	public function pi_loadmore()
	{
		global $post;

		$currentQuantity = $_POST['currentquantity'];
		$quantity 		 = $_POST['info']['quantity'];
		$termId 		 = $_POST['info']['terms'];
		$showMore 		 = $_POST['info']['showmore'];

		$showMore        = !empty($showMore) ? (int)$showMore : 4;

		$args = array
		( 
		    'post_type' 	 => 'pi_gallery',
		    'post_status'	 => 'publish',
		    'posts_per_page' => $showMore,
		    'offset'		=> $currentQuantity,
		    'tax_query' 	=> array
		    (
		        array
		        (
		            'taxonomy' 	=> 'pi_albums',
		            'terms' 	=> $termId,
		            'field' 	=> 'term_id'
		        )
		    )
	    );

		$keyFilter = "pi_lightbox_" . $termId;

	    $piQuery = new WP_Query($args, $keyFilter);

	    $gallery = "";

	    while ( $piQuery->have_posts()  ) : $piQuery->the_post();
	    	$gallery .= $this->pi_create_gallery($post, $keyFilter);
	    endwhile;wp_reset_postdata();	
	    echo $gallery;
		die();
	}


	public function pi_create_gallery($post, $keyFilter)
	{		
			
			$gallery = "";
			/* Switch */
			$galleryID = "";
			$getIds = get_post_meta($post->ID, '_pi_lightbox', true);
			$sImgs = "";

			$getMedia = isset($getIds['mode']) ? $getIds['mode']: '';

			if ( $getMedia != '' ) :	
				switch (  $getMedia ) :
					case 'images':
						if ( isset($getIds['gallery_id']) && !empty($getIds['gallery_id']) )
						{
							$parseId 	= explode(",", $getIds['gallery_id']);
							foreach ( $parseId as $k => $id )
							{	
								if ( $k == 0 ) 
								{
									$first = wp_get_attachment_url( $id );
								}else{
									$sImgs .= '<a href="'.wp_get_attachment_url( $id ).'" class="js_magific" title="'.get_the_title($post->ID).'"></a>';
								}
							}
							if ( has_post_thumbnail() )
							{
								$gallery .= '<div class="popup-gallery mix_all pirates mix '.$keyFilter.'">';
									$gallery .= '<a href="'.$first.'" class="js_magific" title="'.get_the_title($post->ID).'">';
										$gallery .= get_the_post_thumbnail($post->ID);
									$gallery .= '</a>';
									$gallery .= $sImgs;
								$gallery .= '</div>';
							}

						}
					break;
					case 'video':
						$getVideoType = $getIds['video_type'] ? $getIds['video_type'] : '';
						$link 		  = $getIds['video_link'] ? str_replace("https", "http", $getIds['video_link']) : '';


						if ( !empty($getVideoType) && !empty($link) ) :
							$target = 'popup-'.$getVideoType;
					
							if ( has_post_thumbnail() )
							{
								$gallery .= '<div class=" mix_all pirates mix '.$keyFilter.'">';
								
								$gallery .= '<a href="'.$link.'"  class="popup-'.$getVideoType.'">';
									$gallery .= get_the_post_thumbnail($post->ID);
								$gallery .= '</a>';
								$gallery .= '</div>';
							}

						endif;
					break;
					case 'writesomething':
						$liveurl = "data-url=\"".home_url()."/?pi_gallery=".get_the_title()."&amp;pi_livepreview=true"."\"";
						$gallery .= '<div class="popup-ajax mix_all pirates mix '.$keyFilter.'" '.$liveurl.'>';
							$gallery .= '<a href="'.get_permalink($post->ID).'">';
								$gallery .= get_the_post_thumbnail($post->ID);
							$gallery .= '</a>';
						$gallery .= '</div>';
					break;
				endswitch;
			endif;

			return '<div class="pi-gallery-lighbox">'.$gallery.'</div>';
	}

	public function pi_query_post()
	{
		die();
	}

	/**
	 * Loading live preview portfolio project template when has livepreview query var
	 * @param $template
	 *
	 * @return string
	 */
	function portfolio_live_preview_template($template) 
	{
	    if ( get_query_var("pi_livepreview") ) 
	    {
	        return plugin_dir_path(__FILE__) . 'admin/modules/settings/template-writing.php';
	    }
	    else {
	        return $template;
	    }
	}

	/**
	 * Register livepreview into query var
	 * @param $public_query_vars
	 *
	 * @return array
	 */
	function portfolio_livepreview( $public_query_vars ) 
	{
	    $public_query_vars[] = 'pi_livepreview';
	    return $public_query_vars;
	}

	public function pi_ajax_parse_shortcodes()
	{	
		$data  = $this->pi_stripslashes_deep($_POST);

		if ( !isset($_POST['numberid']) || empty($_POST['numberid']) )
		{
			$createID['numberid'] = uniqid("pilb_numbderid");
			$id =  $createID['numberid'];
			$data    			  = array_merge($data, $createID);
		}else{
			$id = $_POST['numberid'];
		}

		// echo "<pre>";
		// 	var_dump($jData);
		// echo "</pre>";

		$jData = json_encode($data);
		$jData = urlencode($jData);
		
        
		if ( !isset($_POST['edit']) || empty($_POST['edit']) )
		{
			$jData = '[pilb_lightbox data_shortcodes=\''.$jData.'\' data_shortcodeid="pi_lightbox" data_numberid=\''.$id.'\']';
			echo $jData;
		}else{
			echo self::pi_gallery_builder($jData, false);
		}

	
		die();
	}

	public function pi_filter_content($content)
	{
		global $post;

		return $content;
		// var_dump($post);
	}

	public function pi_ajax_machine()
	{	
		$aOldData = isset($_POST['edit']) ? $_POST['data'] : '';
		
		if ( !empty($aOldData) && !is_array($aOldData) )
		{
			$aOldData = urldecode($aOldData);
			$aOldData = json_decode($aOldData, true);
		}

		// echo '<pre>';
		// 	var_dump($aOldData);
		// echo "</pre>";

		include ( plugin_dir_path(__FILE__) . 'admin/modules/settings/popup-settings.php' );
		die();
	}

	public function pi_dialog()
	{
		include ( plugin_dir_path(__FILE__) . 'admin/modules/popup/popup-travel.php' );
	}

	/* ------------------------- */
	/* Add Buttons
	/* ------------------------- */
	public function pi_add_buttons($buttons)
	{
		$aMyButton 	= array("pilb_lightbox");
		$buttons 	= array_merge($buttons, $aMyButton);

		return $buttons;
	}


	/* ------------------------- */
	/* Add Js
	/* ------------------------- */
	public function pi_add_js($aJs)
	{
		$aJs['pi_lightbox_gallery'] = plugin_dir_url(__FILE__) . 'admin/assets/js/pi.lightbox.js';
		return $aJs;
	}



	public function piReferesh()
	{
		$postID = isset($_REQUEST['post']) ? $_REQUEST['post'] : '';
		if ( isset($_POST['pi_lightbox']) && !empty($_POST['pi_lightbox']) )
		{
			$this->aOptions = $_POST['pi_lightbox'];
			$this->aLighboxWidth = $_POST['pi_lightbox_width'];
		}else{
			if ( !empty($postID) )
			{
				$this->aOptions = get_post_meta($postID, "_pi_lightbox", true);
				$this->aLighboxWidth = get_post_meta($postID, "_pi_lightbox_width", true);
			}else{
				$this->aOptions = array();
				$this->aLighboxWidth = "";
			}
		}
	}



	/* ------------------------- */
	/* Save data
	/* ------------------------- */
	public function pi_update_data($postID) 
	{
 
		if (!current_user_can('edit_post', $postID) ) return;

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

        if ( !isset($_POST['post_type']) || empty($_POST['post_type']) ) return;
         
        
        if  ( $_POST['post_type'] != self::PIG_POST_TYPE )  return;
        	
    	$data  = isset($_POST['pi_lightbox']) ? $_POST['pi_lightbox'] : array();
    	$lightboxWidth = isset($_POST['pi_lightbox_width']) ? $_POST['pi_lightbox_width'] : '';

		$aData = $this->pi_stripslashes_deep($data);

		update_post_meta($postID, '_pi_lightbox', $aData );
		update_post_meta($postID, '_pi_lightbox_width', $lightboxWidth );
	}

	public function pi_stripslashes_deep($data)
	{
		if ( is_array($data) )
		{
			$data  = array_map(array($this,'pi_stripslashes_deep'), $data);
		}else{
			$data  = wp_unslash($data);
		} 

		return $data;
	}	
	

	public function pi_frontend_enqueue_scripts()
	{
		global $post;

		if ( !empty($post) ) :
			if ( has_shortcode( stripslashes($post->post_content), self::PI_SHORTCODENAME ) ||   self::PI_SHORTCODENAME == $post->post_type  ) :


				$url = plugin_dir_url(__FILE__) . 'assets/';

	 			wp_register_style('plugin-bootstrap', $url . 'css/bootstrap.min.css', array(), '1.0');
				wp_enqueue_style('plugin-bootstrap');

				wp_register_style('plugin-component', $url . 'css/component.css', array(), '1.0');
				wp_enqueue_style('plugin-component');

				wp_register_style('plugin-normalize', $url . 'css/normalize.css', array(), '1.0');
				wp_enqueue_style('plugin-normalize');

	 			wp_register_style('plugin-magnific-popup', $url . 'css/magnific-popup.css', array(), '1.0');
				wp_enqueue_style('plugin-magnific-popup');

				wp_register_style('plugin-isotopfilters', $url . 'css/isotop-filters.css', array(), '1.0');
				wp_enqueue_style('plugin-isotopfilters');

				wp_register_style('plugin-carousel', $url . 'css/owl.carousel.css', array(), '1.0');
				wp_enqueue_style('plugin-carousel');


				wp_register_style('plugin-perfect-scrollbar', $url . 'css/perfect-scrollbar.css', array(), '1.0');
				wp_enqueue_style('plugin-perfect-scrollbar');

				// wp_register_style('plugin-carouseltheme', $url . 'css/owl.theme.css', array(), '1.0');
				// wp_enqueue_style('plugin-carouseltheme');

				wp_register_style('plugin-carouseltransitions', $url . 'css/owl.transitions.css', array(), '1.0');
				wp_enqueue_style('plugin-carouseltransitions');

				wp_register_style('pi-custom', $url . 'css/custom.css', array(), '1.0');
				wp_enqueue_style('pi-custom');


				wp_register_style('plugin-togglebootstrapbutton', $url . 'css/togglebootstrapbutton.min.css', array(), '1.0');

				wp_register_script('plugin-isotope', $url . 'js/jquery-isotope.js', array(), '1.0', true);

				

				wp_register_script('plugin-modernizr', $url . 'js/modernizr.min.js', array(), '1.0', false);
				wp_register_script('plugin-photostack', $url . 'js/photostack.js', array(), '1.0', true);
				wp_register_script('plugin-classie', $url . 'js/classie.js', array(), '1.0', true);
				wp_register_script('plugin-magnific-popup', $url . 'js/jquery.magnific-popup.js', array(), '1.0', true);
				wp_register_script('plugin-perfectscrollbar', $url . 'js/jquery.perfect-scorllbar.min.js', array(), '1.0', true);
				wp_register_script('plugin-imageloaded', $url . 'js/jquery.imageloaded.min.js', array(), '1.0', true);
				wp_register_script('plugin-carousel', $url . 'js/owl.carousel.min.js', array(), '1.0', true);

				wp_register_script('pi-main-js', $url . 'js/pi.main.js', array(), '1.0', true);


				wp_enqueue_script('jquery');
				wp_enqueue_script('plugin-modernizr');
				wp_enqueue_script('plugin-classie');
				wp_enqueue_script('plugin-photostack');
				wp_enqueue_script('plugin-imageloaded');
				wp_enqueue_script('plugin-perfectscrollbar');
				wp_enqueue_script('plugin-isotope');
				wp_enqueue_script('plugin-magnific-popup');
				wp_enqueue_script('plugin-carousel');
				wp_enqueue_script('pi-main-js');

				wp_localize_script('pi-main-js', 'PILB_AJAX', array('ajaxurl'=> admin_url('admin-ajax.php'), 'loading'=>plugin_dir_url(__FILE__) . 'admin/assets/images/tinymce/'));
				// wp_localize_script('pi-customjs', 'PIGIMGURL', plugin_dir_url(__FILE__) . 'admin/assets/images/tinymce/');

	 		endif;
 		endif;
	}

	public function pi_admin_enqueue_scripts()
	{
		// global $typenow;
		$screen = get_current_screen();

		$url = plugin_dir_url(__FILE__) . 'admin/assets/';

		wp_dequeue_script('autosave');

		wp_enqueue_style('wp-color-picker');

		wp_register_style('pi-customjs', $url . 'css/pi.upload.css', array(), '1.0');
		wp_enqueue_style('pi-customjs');

		wp_register_style('pi-popupstyle', $url . 'css/stype-popup.css', array(), '1.0');
		wp_enqueue_style('pi-popupstyle');

	
		wp_register_style('pi-custom', $url . 'css/custom.css', array(), '1.0');
		wp_enqueue_style('pi-custom');

		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-accordion');

		wp_enqueue_script('jquery-form');
		wp_enqueue_script('jquery-ui-dialog');
		wp_enqueue_script('media_upload');
		wp_enqueue_media(); 
		wp_enqueue_script('wp-color-picker');

		wp_register_script('pi-flickr', plugin_dir_url(__FILE__) . 'assets/js/jflickrfeed.min.js', array(), '1.0', true);
		wp_enqueue_script('pi-flickr');


		if ( $screen->id == 'pi_gallery' ) :
			wp_register_script('pi-settings', $url . 'js/pi.main.js', array(), '1.0', true);
			wp_enqueue_script('pi-settings');
		endif;

		wp_register_script('pi-customjs', $url . 'js/pi.custom.js', array(), '1.0', true);
		wp_enqueue_script('pi-customjs');


		/* ------------------------- */
		/* piIMGURL
		/* ------------------------- */
		wp_localize_script('pi-customjs', 'PIGIMGURL', plugin_dir_url(__FILE__) . 'admin/assets/images/tinymce/');
	}


	public function pi_rewrite_flush()
	{
		flush_rewrite_rules();
	}

	public function pi_create_post()
	{
		include( PIG_MD_DIR . 'gallery/func.register_post.php' );
	}

	public function pi_close_div()
	{
		global $typenow;

		if ($typenow != self::PIG_POST_TYPE ) return;
		echo '</div>';
	}


	/* List Builders */
	public function pi_settings_builder()
	{
		include( PIG_MD_DIR . 'settings/pi.func_listbuilder.php' );
	}

	public function pi_choose_type()
	{
		include( PIG_MD_DIR . 'settings/pi.func_type.php' );
	}

	public function pi_upload_images()
	{
		include( PIG_MD_DIR . 'settings/pi.func_images.php' ); 
	}

	public function pi_project()
	{
		include( PIG_MD_DIR . 'settings/pi.func_project.php' ); 
	}

	public function pi_googlemap()
	{
		include( PIG_MD_DIR . 'settings/pi.func_googlemap.php' ); 
	}

	public function pi_embed_video()
	{
		include( PIG_MD_DIR . 'settings/pi.func_video.php' ); 
	}

	public function pi_link_to()
	{
		include( PIG_MD_DIR . 'settings/pi.func_linkto.php' ); 
	}
 
	

	public function pi_popup_width()
	{
		include( PIG_MD_DIR . 'settings/pi.func_popup_width.php' ); 
	}


}

$initGallery = new piGallery;
register_activation_hook( __FILE__, array($initGallery, 'pi_rewrite_flush') );


/* ------------- */
/* Shortcode
/* ------------- */
// include ( "pi.shortcode.php" );