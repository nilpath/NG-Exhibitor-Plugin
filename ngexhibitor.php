<?php

class NGExhibitor {

	var $meta_fields = array( "ngexhibitor-searchwords", "ngexhibitor-availableforOpt1", "ngexhibitor-availableforOpt2", "ngexhibitor-availableforOpt3"); //för varje option måste det läggas till ett metafält?

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
			'rewrite' => array("slug" => "exhibitor"),
			'capability_type' => 'post',
			'hierarchical' => false,
			'menu_position' => null,
			'supports' => array('title','editor','thumbnail'),
			'taxonomies' => array('post_tag', 'category')
  		);
		register_post_type('ngexhibitor',$args);
		
		add_filter("manage_edit-ngexhibitor_columns", array(&$this, "edit_columns"));
		
		add_action("manage_posts_custom_column", array(&$this, "custom_columns"));
		
		add_action("admin_init", array(&$this, "ngexhibitor_admin_init"));
		
		add_action("wp_insert_post", array(&$this, "ngexhibitor_wp_insert_post"), 10, 2);
		
		add_action("admin menu", array(&$this, $ngexhibitor_add_pages));
		
	}
	
	function edit_columns( $columns ) {
		$columns = array(
			"cb" => "<input type=\"checkbox\" />",
			"title" => "Rubrik",
			//"nge-date" => "Datum",
			"ngexhibitor-searchwords" => __('search words'),
			"ngexhibitor-availablefor" => __('Available For(other word needed'),
		);
		
		return $columns;
	}
	
	function custom_columns( $column ) {
		global $post;
		global $wpdb;
		switch ($column)
		{
			case "ngexhibitor-searchwords":
				$custom = get_post_custom();
				echo @$custom["ngexhibitor-searchwords"][0];
				break;
			case "ngexhibitor-availablefor":
				$custom = get_post_custom();
				echo @$custom["ngexhibitor-availableforOpt1"][0];
				echo ", " . @$custom["ngexhibitor-availableforOpt2"][0];
				echo ", " . @$custom["ngexhibitor-availableforOpt3"][0];
				break;
		}
	}
	
	function ngexhibitor_add_pages(){
		global $_registered_pages;
	
		$code_pages = array('exhibitors.php');
		foreach($code_pages as $code_page) {
			$hookname = get_plugin_page_hookname("ngexhibitor/" . $code_page, '' );
			$_registered_pages[$hookname] = true;
		}
	}
	
	function ngexhibitor_admin_init() {
		add_meta_box("ngexhibitor-meta", __('Exhibitor settings'), array(&$this, "meta_options"), "ngexhibitor", "normal", "high");
	}
	
	function meta_options()
	{
		global $post;
		$custom = get_post_custom($post->ID);
		$ngexhibitor_searchwords = @$custom["ngexhibitor-searchwords"][0];
		//Måste anpassas för varje option.
		$ngexhibitor_availableforOpt1 = @$custom["ngexhibitor-availableforOpt1"][0];
		$ngexhibitor_availableforOpt2 = @$custom["ngexhibitor-availableforOpt2"][0];
		$ngexhibitor_availableforOpt3 = @$custom["ngexhibitor-availableforOpt3"][0];
		
		?>
		
			<label for="ngexhibitor-searchwords"><?php echo __('Search Words');?>: <input id="ngexhibitor-searchwords" name="ngexhibitor-searchwords" type="text" value="<?php echo $ngexhibitor_searchwords; ?>" /> (<?php echo __('Comma seperated'); ?>)</label> <br />
			<!-- To be optional, decided with the plugin in a plugin -->
			<p><?php echo __('Something decided in options');?>:</p><input type="checkbox" name="ngexhibitor-availableforOpt1" <?php echo (!empty($ngexhibitor_availableforOpt1) ? " checked=\"checked\"" : ""); ?> value="Option 1" /> Option 1 <input type="checkbox" <?php echo (!empty($ngexhibitor_availableforOpt2) ? " checked=\"checked\"" : ""); ?> name="ngexhibitor-availableforOpt2" value="Option 2" /> Option 2 <input type="checkbox" <?php echo (!empty($ngexhibitor_availableforOpt3) ? " checked=\"checked\"" : ""); ?> name="ngexhibitor-availableforOpt3" value="Option 3" /> Option 3 <br /></label>
		
		<?php
	}
	
	function ngexhibitor_wp_insert_post($post_id, $post = Null)
	{
		if($post->post_type == "ngexhibitor")
		{
		
		// Copypasse!
			foreach ($this->meta_fields as $key) {
				$value = @$_POST[$key];
				var_dump($value);
				if (empty($value)) {
					delete_post_meta($post_id, $key);
					continue;
				}

				// Om värdet är en sträng så uppdatera till värdet om det finns, annars lägg till
				if (!is_array($value)) {
					if (!update_post_meta($post_id, $key, $value)) {
						add_post_meta($post_id, $key, $value);
					}
				}
				else {
					// Om värdet är en array så ta bort alla tidigare och lägg till alla värden från arrayen
					delete_post_meta($post_id, $key);
					
					// Loopar igenom array och lägger till
					foreach ($value as $entry)
						add_post_meta($post_id, $key, $entry);
				}
			}
		}
	}
}

?>	