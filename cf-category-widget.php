<?php
/*
Plugin Name: CF Category Widget
Description: Widget that allows selection of specific categories
Author: Crowd Favorite
Author URI: http://crowdfavorite.com
Version: 1.6
*/

load_plugin_textdomain('cfcw');

class CF_Category_Selection extends WP_Widget {
	function __construct() {
		parent::__construct(
			'cf_category_selection',
			'CF Category Selection',
			array( 
				'description' => __( 'Select specific categories for display', 'cfcw'),
			)
		);
	}

	// Front end
	public function widget( $args, $instance ) {
	
	}

	/**
	 * Form
	 */
	public function form( $instance ) {
?>	
	<script type="text/javascript">
	(function($) {
		$('.cfcw-select').chosen();
		
	})(jQuery);
	</script>
	<select style="width:100%;" class="cfcw-select" multiple>
		<option>Space</option>
		<option>Nature</option>
		<option>Another thing</option>
		<option>Hi World</option>
		<option>One more test thats a bit longer</option>
	</select>
	<br /><br />
<?php 
	}

	
	// Update
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;
	}

}

function cfcw_widgit_init() {
	 register_widget('CF_Category_Selection');
}
add_action('widgets_init', 'cfcw_widgit_init');

function cfcw_enqueue_scripts($hook) {
	if ($hook == 'widgets.php') {
		error_log(plugins_url('lib/chosen/chosen/chosen.jquery.min.js', __FILE__));
		wp_enqueue_script('chosen', plugins_url('lib/chosen/public/chosen.jquery.min.js', __FILE__), array('jquery'));
		wp_enqueue_script('cfcw', plugins_url('cf-category-widget.js', __FILE__), array('jquery', 'chosen'));
		wp_enqueue_style('chosen', plugins_url('lib/chosen/public/chosen.css', __FILE__));
	}
}
add_action('admin_enqueue_scripts', 'cfcw_enqueue_scripts');