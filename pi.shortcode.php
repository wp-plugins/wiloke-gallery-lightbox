<?php
class piGLShortcodes extends piGallery
{

	public function __construct()
	{
		add_shortcode( 'pilb_lightbox', array($this, 'pi_lightbox_gallery_builder') );
	}

	
	// The horizontal space between item elements.
	// To set vertical space between elements, use margin CSS.
	//  Column Width
	public function pi_lightbox_gallery_builder($atts)
	{	 

		$atts = shortcode_atts( array
				(
			      'data_shortcodes' => '',
	  			), $atts );
	 	
		return self::pi_gallery_builder($atts['data_shortcodes']);
	}

	


	public function wt_get_category_count($input = '') 
	{
		global $wpdb;
 
		// $userpost_count = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts
		// LEFT JOIN $wpdb->term_relationships ON($wpdb->posts.ID = $wpdb->term_relationships.object_id)
		// LEFT JOIN $wpdb->term_taxonomy ON($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
		// WHERE $wpdb->term_taxonomy.term_id = CATEGORY_ID
		// AND $wpdb->term_taxonomy.taxonomy = 'category'
		// AND $wpdb->posts.post_status = 'publish'
		// AND post_author = '".$curauth->ID."'");

		if($input == '')
		{
			$category = get_the_category();
			return $category[0]->category_count;
		}
		elseif(is_numeric($input))
		{
			$SQL = "SELECT COUNT(ID) FROM $wpdb->posts WHERE post_parent=".$input;
			return $wpdb->get_var($SQL);
		}
		else
		{
			$SQL = "SELECT $wpdb->posts.count FROM $wpdb->posts, $wpdb->term_taxonomy WHERE $wpdb->terms.term_id=$wpdb->term_taxonomy.term_id AND $wpdb->terms.slug='$input'";
			return $wpdb->get_var($SQL);
		}
	}

	/* Edit */
	// add_action('wp_footer', 'pi_add_something');
	public function pi_add_something()
	{
		include ( plugin_dir_path(__FILE__) . 'admin/modules/popup/popup-travel.php' );
	}

} 