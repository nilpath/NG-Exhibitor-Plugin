<?php

class NGExhibitor {

	function NGExhibitor() {
		/* Skapa custom posttype ngevent */
		$labels = array(
 			'name' => __('Exhibitors'),
			'singular_name' => __('Exhibitor'),
			'add_new' => __('Add'), 'ngexhibitor',
			'add_new_item' => __('Add New Exhibitor'),
			'edit_item' => __('Edit Exhibitor'),
			'edit' => __('Edit'),
			'new_item' => __('New Exhibitor'),
			'view_item' => __('View Exhibitor'),
			'search_items' => __('Search Exhibitor'),
			'not_found' =>  __('No Exhibitor Found'),
			'not_found_in_trash' => __('No Exhibitor Found in Trash'),
			'view' =>  __('View Exhibitor'),
			'parent_item_colon' => '');

		/* Argument för custom posttype */
		$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array("slug" => "event"),
			'capability_type' => 'post',
			'hierarchical' => false,
			'menu_position' => null,
			'supports' => array('title','editor','thumbnail'),
			'taxonomies' => array('post_tag', 'category')
  		);
		register_post_type('ngexhibitor',$args);
		
		add_action("admin menu", array(&$this, $ngexhibitor_add_pages));
		
		/*add_filter("manage_edit-ngevent_columns", array(&$this, "edit_columns")); // Kolumnrubriker under eventlistan
		add_action("manage_posts_custom_column", array(&$this, "custom_columns")); // Kolumninnehåll under eventlistan

		add_filter('pre_get_posts',array(&$this, "rss_include_events")); // Inkluderar events i rss-flödet

		add_action("admin_init", array(&$this, "nge_admin_init")); // Skapar custom data-box under Lägg till event
		add_action("wp_insert_post", array(&$this, "nge_wp_insert_post"), 10, 2); // Hantera custom data när man lägger till
		//$this->insert_to_googleCal(10, 2); //Lägg till i googlecalendern

		add_action("admin_menu", array(&$this, "nge_add_pages")); // Registrerar undersidor till admin
		
		if (get_option("rss_use_excerpt")) add_filter("the_excerpt_rss", array(&$this, "embed_rssfooter")); // Lägger till footer med länk till formuläret i rss
		else add_filter("the_content", array(&$this, "embed_rssfooter"));

		if (get_option("rss_use_excerpt")) add_filter("the_excerpt_rss", array(&$this, "strip_tags_from_rss")); // Rensar bort en vissa taggar från rss
		else add_filter("the_content", array(&$this, "strip_tags_from_rss"));
		
		add_filter('posts_where', array(&$this, 'publish_later_on_feed')); // Ger 5 min marginal för ändringar innan det släpps i RSS*/
	}

	function ngexhibitor_add_pages(){
		global $_registered_pages;
	
		$code_pages = array('exhibitors.php');
		foreach($code_pages as $code_page) {
			$hookname = get_plugin_page_hookname("ngexhibitor/" . $code_page, '' );
			$_registered_pages[$hookname] = true;
		}
	}
	
}	