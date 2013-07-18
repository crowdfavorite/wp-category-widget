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
			),
			array(
				'width' => '300px'
			)
		);
	}

	// Front end
	public function widget($args, $instance) {
		$category_ids = isset($instance['categories']) && is_array($instance['categories']) ? $instance['categories'] : array();
		$title = !empty($instance['title']) ? $instance['title'] : false;
		
		$html = '<div id="'.esc_attr($this->id).'" class="widget clearfix">';

		if (!empty($title)) {
			$html .= '<h2 class="widget-title section-title">'.esc_html($title).'</h2>';
		}

		if (!empty($category_ids)) {
			$html .= '<ul class="cfcw-category-list">';
			$cat_args = array(
			    'orderby' => 'none', 
				'hide_empty' => false, 
				'include' => $category_ids,
				'fields' => 'all', 
			);
			$categories = get_terms('category', $cat_args);
			if (!is_wp_error($categories) && !empty($categories)) {
				foreach ($categories as $category) {
					$html .= '<li><a href="'.esc_url(get_term_link($category, 'category')).'">'.esc_html($category->name).'</a></li>';
				}
			}
			$html .= '</ul>';
		}

		$html .= '</div>';
		echo $html;
	}

	/**
	 * Form
	 */
	public function form($instance) {
		$categories = get_terms('category');
		$title = !empty($instance['title']) ? $instance['title'] : '';
		// This javascript only should fire on save, chosen acts a little strange otherwise
		if (isset($_POST['widget-id'])) :
?>	
	<script type="text/javascript">
	(function($) {
		$('div[id$="<?php echo esc_js($this->id); ?>"] .cfcw-select').chosen( {width: '100%'});
		$('div[id*="cf_category_selection"]').css('overflow', 'visible');
	})(jQuery);
	</script>
	
		<?php endif; ?>
	<p>
		<label for="<?php echo esc_attr($this->get_field_name('title')); ?>"><?php _e('Title:', 'cfcw'); ?> <input id="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" class="widefat" placeholder="<?php _e('Title', 'cfcw'); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($title); ?>" /></label>
	</p>
		<label for="<?php echo esc_attr($this->get_field_name('categories')); ?>"><?php _e('Categories:', 'cfcw'); ?>
		<select id="<?php echo esc_attr($this->get_field_name('categories')); ?>" data-placeholder="<?php _e('Categories...', 'cfcw'); ?>" style="width:300px;" class="cfcw-select" multiple name="<?php echo esc_attr($this->get_field_name('categories')); ?>[]">
			<?php 
				foreach ($categories as $category) {
					echo '<option value="'.esc_attr($category->term_id).'"'.selected(1, in_array($category->term_id, (array) $instance['categories']), false).'>'.esc_html($category->name).'</option>';
				}
			 ?>
		</select>
		</label>
	</p>
<?php 
	}

	
	// Update
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = !empty($new_instance['title']) ? $new_instance['title'] : '';
		$instance['categories'] = ( !empty( $new_instance['categories'] ) ) ? ( $new_instance['categories'] ) : array();
		return $instance;
	}
}

function cfcw_widgit_init() {
	 register_widget('CF_Category_Selection');
}
add_action('widgets_init', 'cfcw_widgit_init');

function cfcw_enqueue_scripts($hook) {
	if ($hook == 'widgets.php') {
		wp_enqueue_script('chosen', plugins_url('lib/chosen/public/chosen.jquery.min.js', __FILE__), array('jquery'));
		wp_enqueue_script('cfcw', plugins_url('cf-category-widget.js', __FILE__), array('jquery', 'chosen'));
		wp_enqueue_style('chosen', plugins_url('lib/chosen/public/chosen.css', __FILE__));
	}
}
add_action('admin_enqueue_scripts', 'cfcw_enqueue_scripts');
