<?php
/**
 * @package   The_Grid
 * @author    Themeone <themeone.master@gmail.com>
 * @copyright 2015 Themeone
 */

// Exit if accessed directly
if (!defined('ABSPATH')) { 
	exit;
}

class Media_Taxonomies {

	private static $instance = null;

	/**
	 * Constructor
	 * @since v1.0.5
	 */
	public function __construct() {
		add_action('init', array($this, 'register_taxonomy'));
		if (is_admin()) {
			add_filter('attachment_fields_to_edit', array( $this, 'attachment_fields_to_edit'), 10, 2);
			add_filter('attachment_fields_to_save', array( $this, 'save_media_terms'), 10, 2);
			add_action('admin_head', array($this, 'media_taxonomy_styles'));
		}
	}
	
	/**
	 * Register taxonomy
	 * @since v1.0.5
	 */
	public function register_taxonomy() {

		register_taxonomy('media-category', array('attachment'), array(
			'hierarchical' => true,
			'labels' => array(
				'name' => _x('Categories', 'taxonomy general name', 'tg-text-domain'),
				'singular_name' => _x('Category', 'taxonomy singular name', 'tg-text-domain'),
				'search_items' =>  __('Search Categories', 'tg-text-domain'),
				'all_items' => __('All Categories', 'tg-text-domain'),
				'parent_item' => __('Parent Category', 'tg-text-domain'),
				'parent_item_colon' => __('Parent Category:', 'tg-text-domain'),
				'edit_item' => __('Edit Category', 'tg-text-domain'),
				'update_item' => __('Update Category', 'tg-text-domain'),
				'add_new_item' => __('Add New Category', 'tg-text-domain'),
				'new_item_name' => __('New Category Name', 'tg-text-domain'),
				'menu_name' => __('Categories', 'tg-text-domain'),
			),
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array('slug' => _x('media-category', 'Category Slug', 'tg-text-domain')),
			'show_admin_column' => true,
			'update_count_callback' => '_update_generic_term_count',
		));

		register_taxonomy('media-tag', array('attachment'), array(
			'hierarchical' => false,
			'labels' => array(
				'name' => _x('Tags', 'taxonomy general name', 'tg-text-domain'),
				'singular_name' => _x('Tag', 'taxonomy singular name', 'tg-text-domain'),
				'search_items' =>  __('Search Tags', 'tg-text-domain'),
				'all_items' => __('All Tags', 'tg-text-domain'),
				'parent_item' => __('Parent Tag', 'tg-text-domain'),
				'parent_item_colon' => __('Parent Tag:', 'tg-text-domain'),
				'edit_item' => __('Edit Tag', 'tg-text-domain'),
				'update_item' => __('Update Tag', 'tg-text-domain'),
				'add_new_item' => __('Add New Tag', 'tg-text-domain'),
				'new_item_name' => __('New Tag Name', 'tg-text-domain'),
				'menu_name' => __('Tags', 'tg-text-domain'),
			),
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array('slug' => _x('media-tag', 'Tag Slug', 'tg-text-domain')),
			'show_admin_column' => true,
			'update_count_callback' => '_update_generic_term_count',
		));

	}

	/**
	 * Add taxonomy checkboxes
	 * @since v1.0.5
	 */
	public function attachment_fields_to_edit($fields, $post) {

		$screen = get_current_screen();
		if (isset($screen->id) && 'attachment' == $screen->id) {
			return $fields;
		}
		
		$taxonomies = apply_filters('media-taxonomies', get_object_taxonomies('attachment', 'objects'));
		if (!$taxonomies) {
			return $fields;
		}
		
		foreach ($taxonomies as $taxonomyname => $taxonomy) {
			$fields[$taxonomyname] = array(
				'label' => $taxonomy->labels->singular_name,
				'input' => 'html',
				'html'  => $this->terms_checkboxes($taxonomy, $post->ID),
				'show_in_edit' => true,
			);
		}

		return $fields;

	}

	/**
	 * Save media terms
	 * @since v1.0.5
	 */
	public function save_media_terms($post, $attachment) {

		//$attachment_id = intval($_REQUEST['id']);
		$attachment_id = intval($post['ID']);
		
		if(empty($attachment_id)){
			wp_send_json_error();
		}
		
		// insert new term to each taxonomy if exist
		$taxo_names = array('media-category', 'media-tag');
		foreach ($taxo_names as $taxo_name) {
			if (isset($_REQUEST['new-media-term'][$taxo_name]) && !empty($_REQUEST['new-media-term'][$taxo_name])) {
				$new_term = $_REQUEST['new-media-term'][$taxo_name];
				wp_insert_term($new_term, $taxo_name, '');
			}
		}
		
		// add terms to attachment cat or tag
		foreach (get_attachment_taxonomies($post) as $taxonomy) {
			if (isset($_REQUEST['tax_input'][$taxonomy])){
				$terms = $_REQUEST['tax_input'][$taxonomy];
				if (is_array($terms)) {
					$terms = array_filter(array_map('intval', $terms));
					wp_set_object_terms($attachment_id, $terms, $taxonomy, false);
				}
			} else {
				wp_set_object_terms($attachment_id, array(), $taxonomy, false);
			}
		}
		
		return $post;
		
	}

	/**
	 * Create a terms box
	 * @since v1.0.5
	 */
	protected function terms_checkboxes($taxonomy, $post_id) {

		if (!is_object($taxonomy)) {
			$taxonomy = get_taxonomy($taxonomy);
		}
		
		$terms = get_terms($taxonomy->name, array('hide_empty' => FALSE));
		$attachment_terms = wp_get_object_terms($post_id, $taxonomy->name, array('fields' => 'ids'));

		$output  = '<div class="media-term-section">';
			
			$cats  = __( 'All Categories', 'tg-text-domain');
			$tags  = __( 'All Tags', 'tg-text-domain');
			$label = ($taxonomy->name == 'media-category') ? $cats : $tags;
			$output .= '<ul class="media-category-tabs category-tabs">';
				$output .= '<li class="tabs" data-tab="media-terms-all"><span>'. $label .'</span></li>';
				$output .= '<li class="hide-if-no-js" data-tab="media-terms-popular"><span>'. __( 'Most Used', 'tg-text-domain') .'</span></li>';
			$output .= '</ul>';
			
			$output .= '<div class="media-terms" data-id="'. $post_id .'" data-taxonomy="'. $taxonomy->name .'">';
				$output .= '<ul>';	
							ob_start();			
							wp_terms_checklist($post_id, array(
								'selected_cats' => $attachment_terms,
								'taxonomy'      => $taxonomy->name,
								'checked_ontop' => true,
								'walker'        => new Walker_WP_Media_Taxonomy_Checklist($post_id)
							));	
							$terms_list = ob_get_contents();
							ob_end_clean();	
							$output .= $terms_list;	
				$output .= '</ul>';
			$output .= '</div>';
			
			$output .= '<h4><span class="toggle-add-media-term">+ '. $taxonomy->labels->add_new_item .'</span></h4>';
			
			$output .= '<div class="add-new-term">';
				$output .= '<p class="category-add wp-hidden-child">';
				$output .= '<input type="text" class="text form-required" autocomplete="off" id="new-media-term" name="new-media-term['.$taxonomy->name.']" value="">';
				$output .= '<button class="button save-media-term" name="current-taxonomy">'.$taxonomy->labels->add_new_item.'</button>';
				$output .= '</p>';
			$output .= '</div>';
			
		$output .= '</div>';
		
		return apply_filters('media-checkboxes', $output, $taxonomy, $terms);
		
	}
	
	/**
	 * Create a terms box
	 * @since v1.0.5
	 */
	public function media_taxonomy_styles() {
		// only load in media library upload page
		//if (strpos($_SERVER["REQUEST_URI"], "upload.php")) {
			
			$styles  = '<style type="text/css">';
			$styles .= '.media-terms ul ul {padding: 10px 0 5px 10px;}';
			$styles .= '.media-term-section .add-new-term {display:none}';
			$styles .= '.media-term-section .toggle-add-media-term {color: #0073aa;font-size:13px;font-weight:600;text-decoration: underline;cursor:pointer}';
			$styles .= '.media-term-section ul li {margin: 0;padding: 0;line-height: 22px;word-wrap: break-word;}';
			$styles .= '.media-term-section ul li input[type=checkbox] {margin: -4px 6px 0 0 !important;}';
			$styles .= '.media-term-section ul ul.children {padding: 0!important; margin-left: 18px!important;}';
			$styles .= '.save-waiting .media-term-section .media-terms li {opacity: 0.5; pointer-events:none;}';
			$styles .= '.media-term-section .media-terms {position: relative;min-height: 42px;max-height: 200px;overflow: auto;padding: 0 .9em;border: 1px solid #dfdfdf;background-color: #fdfdfd;}';
			$styles .= '.media-term-section .media-terms.media-terms-popular li{display:none}';
			$styles .= '.media-term-section .media-terms.media-terms-popular li.popular-category{display:list-item}';
			$styles .= '.media-term-section ul.media-category-tabs {margin: 8px 0 0 0;line-height: 22px;}';
			$styles .= '.media-term-section ul.media-category-tabs.category-tabs li {z-index: 2;padding:3px 7px 4px;cursor:pointer;font-family:"Open Sans",sans-serif;font-size:13px;}';
			$styles .= '.media-term-section ul.media-category-tabs.category-tabs li.tabs span {color: #32373c;}';
			$styles .= '.media-term-section .form-required {margin: 0 0 1em !important;padding: 3px 5px;}';
			$styles .= '</style>';
			echo $styles;
			
			$script  = '<script type="text/javascript">';
			$script .= 'jQuery(document).on("click", ".toggle-add-media-term", function(e){
				e.preventDefault();
				jQuery(this).closest(".media-term-section").find(".add-new-term").toggle();
			});
			jQuery(document).on("click", ".media-term-section .media-category-tabs li", function(e){
				e.preventDefault();
				var tab = jQuery(this).data("tab");
				jQuery(this).parent("ul").find("li").addClass("hide-if-no-js").removeClass("tabs");
				jQuery(this).removeClass("hide-if-no-js").addClass("tabs");
				jQuery(this).closest(".media-term-section").find(".media-terms").toggleClass("media-terms-popular");
			});';
			$script .= '</script>';
			echo $script;
			
		//}
	}

}

new Media_Taxonomies();


// extend walker for wp_terms_checklist
class Walker_WP_Media_Taxonomy_Checklist extends Walker {
	
	public $tree_type = 'category';
	public $db_fields = array ('parent' => 'parent', 'id' => 'term_id');
	public $post_id   = 0;
	
	
	public function __construct($post_id = false) {
		if(empty($post_id)){
			return false;
		}
		$this->post_id = $post_id;
	}
	
	/**
	 * Starts the list before the elements are added
	 * @since 1.0.5
	 */
	public function start_lvl( &$output, $depth = 0, $args = array()) {
		$indent = str_repeat("\t", $depth);
		$output .= "$indent<ul class='children'>\n";
	}
	
	/**
	 * Ends the list of after the elements are added
	 * @since 1.0.5
	 */
	public function end_lvl( &$output, $depth = 0, $args = array()) {
		$indent = str_repeat("\t", $depth);
		$output .= "$indent</ul>\n";
	}
	
	/**
	 * Start the element output
	 * wp-includes/category-template.php
	 * @since 1.0.5
	 */
	public function start_el(&$output, $category, $depth = 0, $args = array(), $id = 0) {
		
		if (empty($args['taxonomy'])) {
			$taxonomy = 'category';
		} else {
			$taxonomy = $args['taxonomy'];
		}

		$name  = 'tax_input['.esc_attr($taxonomy).']['.esc_attr($category->slug).']';
		$class = in_array($category->term_id, $args['popular_cats']) ? ' class="popular-category"' : '';
		
		$args['popular_cats']  = empty( $args['popular_cats'] ) ? array() : $args['popular_cats'];
		$args['selected_cats'] = empty( $args['selected_cats'] ) ? array() : $args['selected_cats'];
		
		if (!empty($args['list_only'])) {
			$aria_cheched = 'false';
			$inner_class  = 'category';
			if (in_array($category->term_id, $args['selected_cats'])) {
				$inner_class .= ' selected';
				$aria_cheched = 'true';
			}
			$output .= '<li'.$class.'>';
			$output .= '<div class="'.$inner_class.'" data-term-id='.$category->term_id.' tabindex="0" role="checkbox" aria-checked="'.$aria_cheched.'">';
			$output .= esc_html(apply_filters('the_category', $category->name));
			$output .= '</div>';		
		} else {
			$checked  = checked(in_array( $category->term_id, $args['selected_cats']), true, false);
			$disabled = disabled(empty($args['disabled']), false, false);
			$output .= '<li id="'.$taxonomy.'-'.$category->term_id.'"'.$class.'>';
			$output .= '<label class="selectit"><input value="'.$category->term_id.'" type="checkbox" name="'.$name.'" id="in-'.$taxonomy.'-'.$category->term_id.'" '.$checked.' '.$disabled.'/>';
			$output .= esc_html(apply_filters('the_category', $category->name));
			$output .= '</label>';
		}
		
	}
	
	/**
	 * Ends the element output, if needed
	 * @since 1.0.5
	 */
	public function end_el( &$output, $category, $depth = 0, $args = array() ) {
		$output .= "</li>\n";
	}
	
}