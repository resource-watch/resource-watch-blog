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

class The_Grid_Post_Type {
	
	/**
	* Post Type transient
	*
	* @since 1.0.0
	* @access private
	*
	* @var string
	*/
	private $transient_sec;
	
	/**
	* Post Type Cache
	*
	* @since 1.0.0
	* @access private
	*
	* @var string
	*/
	private $cache_date;
	
	/**
	* Ajax data
	*
	* @since 1.0.0
	* @access private
	*
	* @var array
	*/
	private $ajax_data;
	
	/**
	* Grid base class helper
	*
	* @since 1.0.0
	* @access private
	*
	* @var string
	*/
	private $base;
	
	/**
	* Grid Data
	*
	* @since 1.0.0
	* @access public
	*
	* @var array
	*/
	public $grid_data;
	
	/**
	* Grid items
	*
	* @since 1.0.0
	* @access public
	*
	* @var array
	*/
	public $grid_items;
	
	/**
	* Post type query args
	*
	* @since 1.0.0
	* @access private
	*
	* @var array
	*/
	private $grid_query_args;
	
	/**
	* Post type query
	*
	* @since 1.0.0
	* @access private
	*
	* @var array
	*/
	private $grid_query;
	
	/**
	* Post ID
	*
	* @since 1.0.0
	* @access private
	*
	* @var integer
	*/
	private $post_ID;
	
	/**
	* Post Type
	*
	* @since 1.0.0
	* @access private
	*
	* @var string
	*/
	private $post_type;
	
	/**
	* Post format
	*
	* @since 1.0.0
	* @access private
	*
	* @var string
	*/
	private $post_format = 'standard';
	
	/**
	* Post format data
	*
	* @since 1.0.0
	* @access private
	*
	* @var array
	*/
	private $image   = array();
	private $gallery = array();
	private $audio   = array();
	private $video   = array();
	private $link    = array();
	private $quote   = array();
	
	/**
	* Post meta data
	*
	* @since 1.0.0
	* @access private
	*
	* @var array
	*/
	private $meta_data;
	
	/**
	* Post Data
	*
	* @since 1.0.0
	* @access private
	*
	* @var string
	*/
	private $post_date;
	private $post_sticky;
	private $post_title;
	private $post_url;
	private $post_target;
	private $post_terms;
	private $post_excerpt;
	private $post_author_ID;
	private $post_author_name;
	private $post_author_link;
	private $post_author_avatar;
	private $post_comments_number;
	private $post_likes_number;
	
	/**
	* Product Data (Woocommerce)
	*
	* @since 1.0.0
	* @access private
	*
	* @var string
	*/
	private $product_price;
	private $product_full_price;
	private $product_regular_price;
	private $product_sale_price;
	private $product_rating;
	private $product_text_rating;
	private $product_on_sale;
	private $product_add_cart_url;
	private $product_cart_button;
	private $product_wishlist;
	private $product_image;

	/**
	* Initialize the class and set its properties.
	* @since 1.0.0
	*/
	public function __construct($grid_data) {
		
		$this->base = new The_Grid_Base();
		$this->get_transient_expiration();
		$this->ajax_data = (isset($_POST['grid_ajax']) && !empty($_POST['grid_ajax'])) ? $_POST['grid_ajax'] : null;
		$this->grid_data = $grid_data;
		$this->get_content();

	}
	
	/**
	* Return array of item data
	* @since 1.0.0
	*/
	public function get_grid_items() {
		
		return $this->grid_items;

	}
	
	/**
	* Return array of grid data
	* @since: 1.0.0
	*/
	public function get_grid_data(){

		return $this->grid_data;
		
	}
	
	/**
	* Set post_type transient expiration
	* @since 1.0.0
	*/
	public function get_transient_expiration() {
		
		$this->transient_sec = apply_filters('tg_transient_expiration', 60*60*24*7);
		
	}
	
	/**
	* Save cache
	* @since 1.0.0
	*/
	public function get_content() {
		
		global $tg_grid_preview;

		$cache     = get_option('the_grid_caching', false);
		$orderby   = $this->grid_data['orderby'];
		
		// if cache enable and not in grid preview mode (backend), get cache
		if ($cache && !in_array('rand', $orderby) && !$tg_grid_preview && !$this->grid_data['is_template'] && !$this->ajax_data) {
		
			$ID   = str_replace('grid-', '', $this->grid_data['ID']);
			$page = (get_query_var('paged')) ? max(1, get_query_var('paged')) : max(1, get_query_var('page'));
			$page = (isset($_POST['grid_page'])) ? $_POST['grid_page']+1 : $page;
			$transient_name = 'tg_grid_'.$ID.'_page_'.$page;
			
			if ($this->transient_sec > 0 && ($response = get_transient($transient_name)) !== false) {
				$this->get_cache_response($response);
			} else {
				// get post data if no cache response
				$this->get_posts();
				$response = $this->set_cache_response();
				if (isset($response) && !empty($response)){
					set_transient($transient_name, $response, $this->transient_sec);
				}
			}
		
		} else {
			
			// get post data
			$this->get_posts();
			
		}	
		
	}
	
	/**
	* Set cache response
	* @since 1.0.0
	*/
	public function set_cache_response() {
		
		// store main data in cache
		return array(
			'item_skins'    => $this->grid_data['item_skins'],
			'grid_items'    => $this->grid_items,
			'cache_date'    => date('m/d/y, h:i:s A'),
			'item_total'    => (int) $this->grid_query->found_posts,
			'max_num_pages' => (int) $this->grid_query->max_num_pages,
			'ajax_data'     => $this->grid_data['ajax_data'],
			
		);
		
	}
	
	/**
	* Set response from transient
	* @since 1.0.0
	*/
	public function get_cache_response($response) {
		
		// retrieve main data from cache
		$this->grid_items                 = $response['grid_items'];
		$this->grid_data['item_skins']    = $response['item_skins'];
		$this->grid_data['cache_date']    = $response['cache_date'];
		$this->grid_data['item_total']    = $response['item_total'];
		$this->grid_data['max_num_pages'] = $response['max_num_pages'];
		$this->grid_data['ajax_data']     = $response['ajax_data'];	

	}
	
	/**
	* Run the query
	* @since: 1.0.0
	*/
	public function get_posts(){
		
		global $tg_is_ajax;
		
		// build and run the query
		if ($this->grid_data['is_template'] == true || $this->ajax_data == 'is_template') {
			$this->run_template_query();
		} else {
			$this->run_custom_query();
		}
		
		// get all skin posts
		if (!$tg_is_ajax) {
			$this->get_skins();
		}
		
		// run custom post query
		$this->post_loop();
		
		// Restore original Post Data 
		wp_reset_postdata();
			
	}
	
	public function run_template_query(){
		
		//get the global query var object
		global $wp_query, $tg_is_ajax;

		$main_query = (isset($_POST['main_query']) && !empty($_POST['main_query'])) ? $_POST['main_query'] : null;
		$this->grid_data['ajax_data'] = 'is_template';

		if ($tg_is_ajax) {
			
			$pagination = array_filter($this->grid_data, function($s){ return (is_string($s)) ? strpos($s, 'get_pagination') : false;});
			$ajax_item_nb = ($pagination) ? $main_query['posts_per_page'] : $this->grid_data['ajax_item_number'];
			$main_query['offset'] = $main_query['posts_per_page']+$ajax_item_nb*($_POST['grid_page']-1);
			$main_query['posts_per_page'] = $ajax_item_nb;
			$main_query['nopaging'] = '';
			$status = ! is_user_logged_in() ? array( 'publish' ) : array( 'publish', 'private' );
			$main_query['post_status'] = ! isset( $main_query['post_status'] ) ? $status : $main_query['post_status'];
			$this->grid_data['offset'] = $main_query['offset'];
			$this->grid_query_args = $main_query;		
			$this->grid_query = new WP_Query($main_query);

		} else if (is_main_query() && !is_admin()) {

			$this->grid_data['item_number']   = $wp_query->query_vars['posts_per_page'];
			$this->grid_data['item_total']    = $wp_query->found_posts;
			$this->grid_data['max_num_pages'] = $wp_query->max_num_pages;
			$this->grid_query = $wp_query;
			
		}
		
	}

	/**
	* Run custom post_type WP_query
	* @since: 1.0.0
	*/
	public function run_custom_query(){

		global $tg_is_ajax;
		
		$this->grid_query = new WP_Query($this->post_query());
				
		if ($this->grid_query->post_count == 0 && !$tg_is_ajax) {
			$error_msg  = __( 'No post was found with your current grid settings.', 'tg-text-domain' );
			$error_msg .= '&#xa;';
			$error_msg .= __( 'You should verify if you have posts inside the current selected post type(s) and if the meta key filter is not too much restrictive.', 'tg-text-domain' );
			throw new Exception($error_msg);
		}
		
		// store total number of post  & max nb page for load more/pagination
		$this->grid_data['item_total'] = $this->grid_query->found_posts;
		$this->grid_data['max_num_pages'] = $this->grid_query->max_num_pages;

	}
	
	/**
	* Build the WP_query args array
	* @since 1.0.0
	*/
	public function post_query() {
		
		// item number on load
		$posts_per_page = $this->grid_data['item_number'];
		// item offset
		$offset = $this->grid_data['offset'];
		// post type and associated categories
		$post_type = $this->grid_data['post_type'];
		// Attachment images ID for gallery
		$gallery_img  = explode(',', $this->grid_data['gallery']);
		$gallery_img  = (count($gallery_img) == 1 && $gallery_img[0] == 0) ? null : $gallery_img;
		if (array_search('rand', $this->grid_data['orderby']) !== false && $gallery_img) {
			shuffle($gallery_img);
		}
		// post type status
		$post_status = $this->grid_data['post_status'];
		// associated categories
		$post_cats = $this->grid_data['categories'];
		$post_cats_child = $this->grid_data['categories_child'];
		
		// prepare taxonomy array
		$taxonomies = array();
		// Build taxonomy query from selected cats/tags for post types
		$i = 0;
		if ($post_cats) {
			foreach($post_cats as $taxonomy) {
				$key   = explode(':', $taxonomy);
				$tax   = $key[0];
				$terms = $key[1];
				$taxonomies[$tax]['include_children'] = $post_cats_child;
				$taxonomies[$tax]['taxonomy'] = $tax;
				if (function_exists('icl_get_languages')) {
					$taxonomies[$tax]['field'] = 'term_id';
				}
				$taxonomies[$tax]['terms'][]  = $terms;
				$taxonomies[$tax]['operator'] = 'IN';
				$i++;
			}
		}
			
		// Add tax query and relation or for everything
		$tax_query['relation'] = 'OR';
		foreach($taxonomies as $query) {
			$tax_query[] = $query;
		}
		
		// Get post order and orderby key
		$post_order = $this->grid_data['order'];
		$post_orderby = $this->grid_data['orderby'];
		$post_orderby_val = ($this->grid_data['orderby'] && is_array($this->grid_data['orderby'])) ? implode(' ', $this->grid_data['orderby']) : null;

		// Grab custom post ID to preserve post orderby
		$post_orderby_id = $this->grid_data['orderby_id'];
		$post_orderby_id = !empty($post_orderby_id) ? explode(',', $post_orderby_id) : array();
	
		// prepare post__in/post_not__in query paramaters
		$post_in     = array();
		$post_not_in = array();
		
		//get all page ids if more than one post type
		$all_page    = array();
		$pages_id = $this->grid_data['pages_id'];
		if (in_array('page', $post_type) && count($post_type) > 1 && $pages_id) {
			
			$all_pages = $this->base->get_all_page_id();
			$all_page  = array();
			
			foreach ($all_pages as $ID => $pages) {
				$all_page[] = $ID;
			}
			
			$post_not_in = array_diff($all_page, $pages_id);
			
		} else if (in_array('page', $post_type) && $pages_id) {
			
			$post_in = $pages_id;

		}
		
		// excluded items
		$excluded_items = $this->grid_data['post_not_in'];
		$excluded_items = (!empty($excluded_items)) ? explode(', ', $excluded_items) : array();
		$post_not_in    = array_merge($post_not_in, $excluded_items);

		// preserve post ID order : merge existing page IDs with selected post IDs
		if (!empty($post_orderby_id) && in_array('post__in', $post_orderby)) {
			$post_in = array_merge($post_orderby_id, $pages_id);
		}
		
		// most recently viewed woocommerce product from use cookie
		if (class_exists('WooCommerce') && strpos($post_orderby_val, 'woocommerce_recently_viewed') !== false) {
			$viewed_products = !empty($_COOKIE['woocommerce_recently_viewed']) ? (array) explode('|', $_COOKIE['woocommerce_recently_viewed']) : array();
			$viewed_products = array_filter(array_map('absint', $viewed_products ));
			$post_in = array_merge($post_in, $viewed_products);
			if (empty($post_in)) {
				throw new Exception(__( 'You have not viewed any product yet!', 'tg-text-domain' ));
			}
		}

		// If Attachment force post order and image order from drag & drop image gallery field
		if (in_array('attachment', $post_type)) {
			
			// add post statut inherit to post_statut options to retrieve attachments
			array_push($post_status, 'inherit');
			
			if (sizeof($post_type) > 1 && $gallery_img) {
				
				$ids = get_posts(array(
					'post_type'        => 'attachment', 
					'post_status'      => 'inherit', 
					'posts_per_page'   => -1,
					'fields'           => 'ids',
					'suppress_filters' => true,
					'no_found_rows'    => true,
					'cache_results'    => false
				));
				
				$img_ids = array();
				foreach ($ids as $id) {
					$img_ids[] = $id;
				}
				
				$img_ids = array_diff($img_ids, $gallery_img);
				$post_not_in = array_merge($post_not_in, $img_ids);
				
			} else {
				
				$post_orderby_val .= ($post_orderby_val) ? ' post__in' : 'post__in';
				$post_in = $gallery_img;
				
			}
			
		}

		// remove filter category and force post_in ids of all cat to keep selected page
		if ($post_cats && in_array('page', $post_type) && $pages_id) {
			$post_ids_cat = $this->base->get_post_ids_by_cat($post_type, $tax_query, $post_cats_child, $terms,$tax);
			$post_in      = array_merge($pages_id, $post_orderby_id, $post_ids_cat);
			$tax_query    = null;
		}

		// remove post ID from post in if complex query with pages
		$post_in = (!empty($post_in) && !empty($post_not_in)) ? array_diff($post_in, $post_not_in) : $post_in;

		//retrieve meta_key to order by meta_value
		$meta_key = null;
		if ($this->base->strpos_array($post_orderby_val,array('meta_value','meta_value_num')) !== false) {
			$meta_key = $this->grid_data['meta_key'];
		}
	
		// get authors filter
		$author  = null;
		$authors = $this->grid_data['author'];
		$author  = (is_array($authors) && count($authors) > 1) ? implode(',', array_map(function($item) { return $item; }, $authors)) : $authors[0];
		
		// get meta query
		$meta_query = $this->meta_query();
		
		// retrieve current page
		$pagination = array_filter($this->grid_data, function($s){ return (is_string($s)) ? strpos($s, 'get_pagination') : false;});
		$paged  = (get_query_var('paged')) ? max(1, get_query_var('paged')) : max(1, get_query_var('page'));
		$paged  = $pagination ? $paged : 0;
		// unassign offset if page equal to 1
		$offset = ($paged > 1 && !$offset) ? '' : $offset;
		
		// check if a pagination exist (ajax method load more & pagination (ajax also))
		$pagination_method = $this->check_for_pagination();
		
		// if not pagination or load more button then suppress SQL_CALC_FOUND_ROWS
		$no_found_rows = ($pagination_method) ? false : true;

		// setup the query args
		// WordPress already takes care of the necessary sanitization in querying the database
		$this->grid_query_args = array( 
			'post_type'        => $post_type, 
			'posts_per_page'   => $posts_per_page, 
			'post_status'      => $post_status,
			'author'           => $author,
			'paged'            => $paged,
			'offset'           => $offset,
			'post__in'         => $post_in,
			'post__not_in'     => $post_not_in,
			'order'            => $post_order,
			'orderby'          => $post_orderby_val,
			'tax_query'        => $tax_query,
			'meta_key'         => $meta_key,
			'meta_query'       => $meta_query,
			'no_found_rows'    => $no_found_rows,
			'suppress_filters' => false
		);

		// add filter to modify WP_query for any grid post (with grid name)
		return apply_filters('tg_wp_query_args', $this->grid_query_args, $this->grid_data['name']);
		
	}
	
	/**
	* Build the meta query args array
	* @since 1.0.0
	*/
	public function meta_query() {
		
		// get meta query info
		$meta_query = $this->grid_data['meta_query'];
		$meta_query = json_decode($meta_query, TRUE);
		
		// loop options and rebuild query array logic
		if ($meta_query && count($meta_query) > 1) {
			$i = 0;
			$y = 0;
			$meta = array();
			$relation = false;
			foreach ($meta_query as $meta_keys=>$meta_key) {
				if (isset($meta_key['relation']) && $i == 0) {
					$meta['relation'] = $meta_key['relation'];
				} else if (isset($meta_key['relation'])) {
					$meta[$i] = array();
					$meta[$i]['relation'] = $meta_key['relation'];
					$relation = true;
					$y = 0;
					$i++;
				} else {
					if ($relation == true) {
						$meta[$i-1][$y]['key']      = $meta_key['key'];
						$meta[$i-1][$y]['value']    = $meta_key['value'];
						$meta[$i-1][$y]['compare']  = $meta_key['compare'];
						if (isset($meta_key['type'])) {
							$meta[$i-1][$y]['type'] = $meta_key['type'];
						}
						$y++;
					} else {
						$meta[$i]['key']      = $meta_key['key'];
						$meta[$i]['value']    = $meta_key['value'];
						$meta[$i]['compare']  = $meta_key['compare'];
						if (isset($meta_key['type'])) {
							$meta[$i]['type'] = $meta_key['type'];
						}
						$i++;
					}				
				}			
			}
		} else {
			$meta = null;
		}

		return $meta;
		
	}

	/**
	* Retrieve all skins usued in the current grid
	* @since 1.0.0
	*/
	public function get_skins() {
		
		// check if an ajax method is used in the current grid
		$ajax_method = $this->check_for_pagination(true);
	
		// if new item(s) can be appended with ajax
		if ($ajax_method) {
			$this->grid_data['item_skins'] = $this->get_meta_values();
		// else only fetch item(s) in the grid
		} else {
			$posts = $this->grid_query->posts;
			$this->grid_data['item_skins'] = !empty($posts) ? wp_list_pluck($posts, 'the_grid_item_skin') : null;
		}

	}
	
	/**
	* Retrieve grid skins metadata value from current query
	* @since 2.1.2
	*/
	public function get_meta_values() {
		
		global $wpdb;

		$post_types  = $this->grid_data['post_type'] ? $this->grid_data['post_type'] : array('post');
		$post_types  = "'".implode("', '", $post_types)."'";
		
		$post_status = $this->grid_data['post_status'] ? $this->grid_data['post_status'] : array('publish');
		$post_status = "'".implode("', '", $post_status)."'";
		
		if ($post_types && $post_types) {

			$response = $wpdb->get_col($wpdb->prepare("
				SELECT DISTINCT pm.meta_value FROM {$wpdb->postmeta} pm
				LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
				WHERE pm.meta_key = %s
				AND p.post_status IN (".$post_status.")
				AND p.post_type IN (".$post_types.")
			", 'the_grid_item_skin'));

			return (is_array($response)) ? array_filter($response) : array();
		
		}
		
	}
	
	/**
	* Check if a pagination exist (load more or pagination (ajax optional))
	* @since 1.0.0
	*/
	public function check_for_pagination($ajax = false) {

		$pagination  = false;
		$load_more   = false;
		$ajax_pages  = ($ajax) ? $this->grid_data['ajax_pagination'] : true;
		$ajax_scroll = ($ajax && $this->grid_data['ajax_method'] == 'on_scroll') ? true : false;
		$areas = preg_grep('/area_/i', array_keys($this->grid_data));
			
		// loop through each area
		foreach($areas as $area) {
				
			$area_content = array();
			$data = $this->grid_data[$area];
			$data = json_decode($data, true);
				
			if (isset($data['functions']) && !empty($data['functions'])) {
				foreach($data['functions'] as $function) {
					if (strpos($function, 'get_pagination')) {
						$pagination = true;
						break;
					} else if (strpos($function, 'get_ajax_button')) {
						$load_more = true;
						break;
					}
				}
			}
				
			if ($pagination || $load_more) {
				break;
			}

		}
		
		$ajax_method = ($load_more || ($pagination && $ajax_pages) || $ajax_scroll) ? true : false;
		
		return $ajax_method;
		
	}
	
	/**
	* Custom post_type loop
	* @since: 1.0.0
	*/
	public function post_loop(){

		if ($this->grid_query && $this->grid_query->have_posts()) { 
			while ($this->grid_query->have_posts()) {
				$this->grid_query->the_post();
				$this->get_post_data();
				$this->get_meta_data();
				$this->get_product_data();		
				$this->get_post_format();
				$this->get_terms();
				$this->get_media_content();
				$this->build_posts_array();			
			}
		} else if ($this->grid_data['is_template'] == true && !$this->ajax_data == 'is_template') {
			$error_msg  = __('Sorry, no results were found.', 'tg-text-domain' );
			$error_msg .= '&#xa;';
			$error_msg .= __( 'Please try to search again...', 'tg-text-domain' );
			throw new Exception($error_msg);
		}

	}

	/**
	* Retrieve all meta data
	* @since: 1.0.0
	*/
	public function get_meta_data(){
		
		// reset meta data
		$this->meta_data = array();
		
		// get all meta data
		$meta_keys = get_metadata('post', $this->post_ID);
		if (empty($meta_keys)) {
			return;
		}
		
		// assign meta data
		foreach ($meta_keys as $key => $val) {
			$val = (is_array($val)) ? $val[0] : $val;
			$this->meta_data[$key] = maybe_unserialize($val);
		}
		
		// normalize meta data
		$this->normalize_meta_data();

	}
	
	/**
	* Normalize meta data (if empty only)
	* @since 1.0.0
	*/
	public function normalize_meta_data() {
		
		$default_url  = ($this->post_type != 'attachment') ? get_the_permalink($this->post_ID) : null;
		
		$options = array(
			'the_grid_item_custom_link'        => $default_url,
			'the_grid_item_custom_link_target' => '_self',
			'the_grid_item_row'                => 1,
			'the_grid_item_col'                => 1,
			'the_grid_item_video_ratio'        => '4:3',
			'the_grid_item_youtube_ratio'      => '4:3',
			'the_grid_item_vimeo_ratio'        => '4:3',
			'the_grid_item_wistia_ratio'       => '4:3'
		);
		
		// loop through each default values
		foreach($options as $option => $value) {
			$this->meta_data[$option] = $this->base->getVar($this->meta_data, $option, $value);
		}
		
		// set post url and target for custom meta data
		$this->post_url    = $this->meta_data['the_grid_item_custom_link'];
		$this->post_target = $this->meta_data['the_grid_item_custom_link_target'];

	}	

	/**
	* Retrieve main post content
	* @since: 1.0.0
	*/
	public function get_post_data() {
		
		$this->post_ID              = get_the_ID();
		$this->post_type            = get_post_type();
		$this->post_sticky          = is_sticky($this->post_ID);
		$this->post_date            = get_the_date('U');
		$this->post_title           = get_the_title($this->post_ID);
		$this->post_excerpt         = get_the_excerpt();
		$this->post_comments_number = get_comments_number();
		$this->post_likes_number    = TO_get_post_like();
		$this->post_author_ID       = get_the_author_meta('ID');
		$this->post_author_name     = get_the_author_meta('display_name');
		$this->post_author_link     = get_author_posts_url($this->post_author_ID);
		$this->post_author_avatar   = get_avatar_data($this->post_author_ID, array('size'=>'46', 'default'=>''));
		$this->post_author_avatar   = $this->post_author_avatar['url'];
				
	}
	
	/**
	* Retrieve main product content
	* @since: 1.0.0
	*/
	public function get_product_data() {
		
		$this->product_price         = $this->get_product_price();
		$this->product_full_price    = $this->get_product_full_price();
		$this->product_regular_price = $this->get_product_regular_price();
		$this->product_sale_price    = $this->get_product_sale_price();
		$this->product_rating        = $this->get_product_rating();
		$this->product_text_rating   = $this->get_product_text_rating();
		$this->product_on_sale       = $this->get_product_on_sale();
		$this->product_add_cart_url  = $this->get_product_add_to_cart_url();
		$this->product_cart_button   = $this->get_product_cart_button();
		$this->product_wishlist      = $this->get_product_wishlist();
		$this->product_image         = $this->get_product_image();
		
	}
	
	/**
	* Retrieve Woocommerce regular price
	* @since: 1.0.0
	*/
	public function get_product_price() {
			
		if (class_exists('WooCommerce') && $this->post_type == 'product') {
			
			global $product;
			$price = $product->get_price();
			return ($price > 0) ? wc_price($price) : null;
				
		}
		
	}
	
	/**
	* Retrieve Woocommerce full price
	* @since: 1.0.0
	*/
	public function get_product_full_price() {
			
		if (class_exists('WooCommerce') && $this->post_type == 'product') {
			
			global $product;
			return $product->get_price_html();
				
		}
		
	}
	
	/**
	* Retrieve Woocommerce regular price
	* @since: 1.0.0
	*/
	public function get_product_regular_price() {
			
		if (class_exists('WooCommerce') && $this->post_type == 'product') {
			
			global $product;
			$regular_price = $product->get_regular_price();
			return ($regular_price > 0) ? wc_price($regular_price) : null;
				
		}
		
	}
	
	/**
	* Retrieve Woocommerce sale price
	* @since: 1.0.0
	*/
	public function get_product_sale_price() {
			
		if (class_exists('WooCommerce') && $this->post_type == 'product') {
			
			global $product;

			$sale_price = $product->get_sale_price();
			return ($sale_price > 0) ? wc_price($sale_price) : null;
				
		}
		
	}
	
	/**
	* Retrieve Woocommerce star rating
	* @since: 1.0.0
	*/
	public function get_product_rating() {

		if ( class_exists( 'WooCommerce' ) && $this->post_type == 'product' ) {
		
			global $woocommerce, $product;

			if( version_compare( $woocommerce->version, '3.0.0', '>=' ) ) {

				$rating = $product->get_average_rating();
				$rating = wc_get_rating_html( $rating );
				return preg_replace( '#(<span.*?>).*?(</span>)#', '$1$2', $rating );

			} else {
				return preg_replace( '#(<span.*?>).*?(</span>)#', '$1$2', $product->get_rating_html() );
			}

		} 
		
	}
	
	/**
	* Retrieve Woocommerce text rating
	* @since: 1.0.0
	*/
	public function get_product_text_rating() {
		
		if (class_exists('WooCommerce') && $this->post_type == 'product') {
		
			global $product;

			return $product->get_average_rating();
			
		} 
		
	}
	
	/**
	* Retrieve Woocommerce sale status
	* @since: 1.0.0
	*/
	public function get_product_on_sale() {

		if (class_exists('WooCommerce') && $this->post_type == 'product') {
		
			global $post, $product;
			
			if ($product->is_on_sale()) {
				return apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . __( 'Sale!', 'woocommerce' ) . '</span>', $post, $product );			
			}
			
		}
		
	}
	
	/**
	* Retrieve Woocommerce add to cart button
	* @since: 1.0.0
	*/
	public function get_product_add_to_cart_url() {
		
		if (class_exists('WooCommerce') && $this->post_type == 'product') {
			
			global $product;

			return $product->add_to_cart_url();
		
		}
	
	}
	
	/**
	* Retrieve Woocommerce add to cart button
	* @since: 1.0.0
	*/
	public function get_product_cart_button() {

		if (class_exists('WooCommerce') && $this->post_type == 'product') {
		
			global $product;
			
			$ajax_add_to_cart = $product->supports('ajax_add_to_cart') ? ' ajax_add_to_cart' : '';

			ob_start();

			$cart_button = apply_filters( 'woocommerce_loop_add_to_cart_link',
				sprintf( '<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" data-quantity="%s" class="button %s product_type_%s">%s</a>',
					esc_url( $product->add_to_cart_url() ),
					esc_attr( $product->get_id() ),
					esc_attr( $product->get_sku() ),
					esc_attr( isset( $quantity ) ? $quantity : 1 ),
					$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button'.$ajax_add_to_cart : '',
					esc_attr( $product->get_type() ),
					esc_html( $product->add_to_cart_text() )
				),
			$product);

			$content = ob_get_contents();
			ob_end_clean();

			// in case the filter echo if modified by a theme
			$cart = ($cart_button) ? $cart_button : $content;
			
			return $cart;

		}
		
	}
	
	/**
	* Retrieve Woocommerce YITH Whislist
	* @since: 1.0.0
	*/
	public function get_product_wishlist() {
		

		if (class_exists('WooCommerce') && $this->post_type == 'product') {
		
			global $yith_wcwl;

			if ($yith_wcwl) {
				$html = do_shortcode('[yith_wcwl_add_to_wishlist]');
				$html = preg_replace('#<div class="clear">(.*?)</div>#', '', $html);
				return $html;
			}
			
		}
	
	}
	
	/**
	* Retrieve first gallery product image
	* @since: 1.0.0
	*/
	public function get_product_image() {
		
		if (class_exists('WooCommerce') && $this->post_type == 'product') {
			
			$meta_data = $this->meta_data;
			$product_image = $this->base->getVar($meta_data, '_product_image_gallery');
			$product_image = ($product_image) ? explode(',', $product_image) : null;
			
			return (isset($product_image[0]) && !empty($product_image[0])) ? $this->image_data($product_image[0]) : null;
			
		}
		
	}

	/**
	* Retrieve post terms
	* @since: 1.0.0
	*/
	public function get_terms() {
		
		$this->post_terms = null;
		$taxonomies = get_object_taxonomies($this->post_type, 'objects');
		
		if (!empty($taxonomies)) {
			
			foreach ($taxonomies as $taxonomy_slug => $taxonomy){
				
				$terms = (array) get_the_terms($this->post_ID, $taxonomy_slug);
				
				foreach ($terms as $term){
					
					if(!empty($term) && $taxonomy_slug != 'product_type'){
						
						$term_options = get_option($term->taxonomy.'_'.$term->term_id);
						$this->post_terms[] = array(
							'ID'       => $term->term_id,
							'slug'     => $term->slug,
							'name'     => $term->name,
							'taxonomy' => $term->taxonomy,
							'url'      => get_term_link($term->term_id),
							'color'    => isset($term_options['the_grid_term_color']) ? $term_options['the_grid_term_color'] : null
						);
						
					}
				}
			}

		}
	
	}
	
	/**
	* Get Current Post Format
	* @since 1.0.0
	*/
	public function get_post_format() {
		
		$native_post_format = get_post_format();
		$altern_post_format = $this->base->getVar($this->meta_data, 'the_grid_item_format');
		$post_format  = (empty($altern_post_format)) ? $native_post_format : $altern_post_format;
		$post_format  = (empty($post_format)) ? 'image' : $post_format;
		$this->post_format = (in_array($post_format, $this->grid_data['items_format'])) ? $post_format : 'image';
		
	}
	
	/**
	* Get Post Media Content (according to the post format)
	* @since 1.0.0
	*/
	public function get_media_content() {
		
		// reset post format data
		$this->image   = null;
		$this->gallery = null;
		$this->video   = null;
		$this->audio   = null;
		$this->link    = null;
		$this->quote   = null;

		$format = $this->post_format;

		if ($format != 'image') {
			$this->post_format = $this->fetch_media_content($format);	
		}
		
		$this->fetch_media_content('image');
		
		// if quote or link not set or have an image
		if (isset($this->{$this->post_format}) && !is_array($this->{$this->post_format}) && in_array($this->post_format, array('quote','link')) ) {
			$this->post_format = $this->{$this->post_format} ? 'image' : 'standard';
		}
		
		// if format embed audio/video, fecth embed image if missing	
		if (empty($this->image) && isset($this->{$this->post_format}['type']) && in_array($this->{$this->post_format}['type'], array('youtube','vimeo','wistia','soundcloud'))) {
			$image = $this->embed_image($this->{$this->post_format});
			$image = $this->image_data($image);
			$this->image = $image;
		// if no image set default image if available (from grid settings)
		} else if (empty($this->image)) {
			$image = $this->grid_data['default_image'];
			$image = $this->image_data($image);
			$this->image = $image;
		}
		// if format image and no image in the source then set to standard
		if (empty($this->image) && $this->post_format == 'image') {
			$this->post_format = 'standard';
		}

	}
	
	/**
	* Fetch content
	* @since: 1.0.0
	*/
	public function fetch_media_content($format) {
		
		$sources = array(
			'alternative_'.$format, // meta data the grid
			'first_content_media'   // first media content
		);
		
		// don't fetch first image if video/audio format
		if ($format == 'image' && in_array($this->post_format, array('audio','video'))) {
			unset($sources[1]);
		}

		foreach($sources as $source) {
			if (method_exists($this, $source)) {
				$source = $this->$source($format);
				if (!empty($source)) {
					$this->$format = $source;
					break;
				}
			}
		}

		// get image(s) from ID
		if ($format == 'image' && !empty($source)) {
			$this->$format = $this->image_data($this->$format);
		} else if ($format == 'gallery' && !empty($source)) {
			$gallery_IDs   = $this->$format;
			$this->$format = null;
			if (isset($gallery_IDs) && !empty($gallery_IDs) && is_array($gallery_IDs)) {
				foreach($gallery_IDs as $gallery_ID) {
					$this->{$format}[] = $this->image_data($gallery_ID);
				}
			}
		}
		
		return (empty($source)) ? 'standard' : $format;
	
	}
	
	/**
	* Get first media content
	* @since: 1.0.0
	*/
	public function first_content_media($format) {
		
		$media = TO_First_Media($format);
		
		// set default image if no image found in content and grid item settings
		if (empty($media)) {
			$media = $this->grid_data['default_image'];
		}

		return $media;
		
	}
	
	/**
	* get alternative image (post type metadata)
	* @since: 1.0.0
	*/
	public function alternative_image() {
		
		$image = $this->base->getVar($this->meta_data, 'the_grid_item_image');

		// if format video prevent fetching featured image
		if (empty($image) && !in_array($this->post_format,array('audio','video'))) {
			$image = ($this->post_type == 'attachment') ? $this->post_ID : get_post_thumbnail_id($this->post_ID);
			//$image = ($this->post_type == 'attachment') ? $this->post_ID : $this->base->getVar($this->meta_data, '_thumbnail_id');
		}

		return $image;
		
	}
	
	/**
	* Get Youtube/Vimeo/Wistia/Soundcloud image (if not image set)
	* @since: 1.0.0
	*/
	public function embed_image($source) {
		
		$poster     = null;
		$embed_type = $source['type'];
		$embed_ID   = $source['source']['ID'];

		switch ($embed_type) {
			case 'vimeo':
				$response = wp_remote_get('https://vimeo.com/api/v2/video/'.esc_attr($embed_ID).'.json');
				if (!is_wp_error($response)) {
					$data = wp_remote_retrieve_body($response);
					if (!is_wp_error($data)) {
						$data   = json_decode($data);
						$poster = $data[0]->thumbnail_large;
					}
				}
				break;
			case 'wistia':
				$response = wp_remote_get('https://fast.wistia.com/oembed?url=http%3A%2F%2Fhome.wistia.com%2Fmedias%2F'.esc_attr($embed_ID).'.json');
				if (!is_wp_error($response)) {
					$data = wp_remote_retrieve_body($response);
					if (!is_wp_error($data)) {
						$data   = json_decode($data);
						$poster = $data->thumbnail_url;
					}
				}
				break;
			case 'youtube':
				$poster = 'https://img.youtube.com/vi/'.$embed_ID.'/sddefault.jpg';
				break;
			case 'soundcloud':
				$client_ID = '226a27261125c8452c8b002d5731f5ca'; // general & public client ID from Themeone
				$response = wp_remote_get('https://api.soundcloud.com/tracks/'.esc_attr($embed_ID).'.json?client_id='.esc_attr($client_ID), array('decompress' => false));
				if (!is_wp_error($response)) {
					$data = wp_remote_retrieve_body($response);
					if (!is_wp_error($data)) {
						$data   = json_decode($data);
						if (isset($data->artwork_url)) {
							$poster = $data->artwork_url;
							$poster = str_replace('large', 't500x500', $poster);
						}
					}
				}
		}

		return $poster;
		
	}
	
	/**
	* get alternative gallery (post type metadata)
	* @since: 1.0.0
	*/
	public function alternative_gallery() {
		
		$gallery_IDs = $this->base->getVar($this->meta_data, 'the_grid_item_gallery');
		$gallery_IDs = (!empty($gallery_IDs)) ? explode(',', $gallery_IDs) : null;
		return $gallery_IDs;
		
	}
	
	/**
	* get alternative audio (post type metadata)
	* @since: 1.0.0
	*/
	public function alternative_audio() {
		
		$audio = null;
		$mp3   = $this->base->getVar($this->meta_data, 'the_grid_item_mp3');
		$ogg   = $this->base->getVar($this->meta_data, 'the_grid_item_ogg');
		$sdc   = $this->base->getVar($this->meta_data, 'the_grid_item_soundcloud');
		
		if (!empty($mp3) || !empty($ogg)) {
			$audio = array(
				'type'   => 'audio',
				'source' => array(
					'mp3' => !empty($mp3) ? $mp3 : null,
					'ogg' => !empty($ogg) ? $ogg : null
				)
			);
		} else if (!empty($sdc)) {
			$audio = array(
				'type'   => 'soundcloud',
				'source' => array(
					'ID' => $sdc,
				)
			);
		}
		
		return $audio;
	}

	/**
	* get alternative audio (post type metadata)
	* @since: 1.0.0
	*/
	public function alternative_video() {
		
		$video   = null;
		$mp4     = $this->base->getVar($this->meta_data, 'the_grid_item_mp4');
		$ogv     = $this->base->getVar($this->meta_data, 'the_grid_item_ogv');
		$webm    = $this->base->getVar($this->meta_data, 'the_grid_item_webm');
		$youtube = $this->base->getVar($this->meta_data, 'the_grid_item_youtube');
		$vimeo   = $this->base->getVar($this->meta_data, 'the_grid_item_vimeo');
		$wistia  = $this->base->getVar($this->meta_data, 'the_grid_item_wistia');
	
		if (!empty($mp4) || !empty($ogv) || !empty($webm)) {
			$video = array(
				'type'   => 'video',
				'source' => array(
					'mp4'  => !empty($mp4) ? $mp4 : null,
					'ogv'  => !empty($ogv) ? $ogv : null,
					'webm' => !empty($webm) ? $webm : null
				)
			);
		} else if (!empty($youtube)) {
			$video = array(
				'type'   => 'youtube',
				'source' => array(
					'ID' => $youtube
				)
			);
		} else if (!empty($vimeo)) {
			$video = array(
				'type'   => 'vimeo',
				'source' => array(
					'ID' => $vimeo
				)
			);
		} else if (!empty($wistia)) {
			$video = array(
				'type'   => 'wistia',
				'source' => array(
					'ID' => $wistia
				)
			);
		}
		
		return $video;
	}

	/**
	* get alternative link (post type metadata)
	* @since: 1.0.0
	*/
	public function alternative_link() {
		
		$link    = null;
		$content = $this->base->getVar($this->meta_data, 'the_grid_item_link_content');
		$url     = $this->base->getVar($this->meta_data, 'the_grid_item_link_url');

		if (!empty($url)) {
			$link = array(
				'type'   => 'link',
				'source' => array(
					'content'  => (!empty($content)) ? $content : null,
					'url'      => (!empty($url)) ? $url : null
				)
			);
		}
		
		return $link;
	}
	
	/**
	* get alternative quote (post type metadata)
	* @since: 1.0.0
	*/
	public function alternative_quote() {
		
		$quote   = null;
		$content = $this->base->getVar($this->meta_data, 'the_grid_item_quote_content');
		$author  = $this->base->getVar($this->meta_data, 'the_grid_item_quote_author');
		
		if (!empty($content)) {
			$quote = array(
				'type'   => 'quote',
				'source' => array(
					'content'  => (!empty($content)) ? $content : null,
					'author'   => (!empty($author)) ? $author : null
				)
			);
		}
		
		return $quote;
	}
	
	/**
	* Get image data (url,width,height,type,alt,title) for html5/SEO
	* @since: 1.0.0
	*/
	public function image_data($img_ID) {
		
		if (empty($img_ID)) {
			return false;
		}

		$aq_resizer = $this->grid_data['aqua_resizer'];

		if (is_numeric($img_ID)) {

			if ($aq_resizer == true) {
				
				$img_full = wp_get_attachment_url($img_ID, 'full');
				$col_size = $this->column_size();
				
				$grid_style      = $this->grid_data['style'];
				$item_force_size = $this->grid_data['item_force_size'];
				
				if ($item_force_size == true) {
					$img_height = $col_size['height'] * $this->grid_data['items_row'];
					$img_width  = $col_size['width']  * $this->grid_data['items_col'];
				} else {
					$img_height = $col_size['height'] * $this->base->getVar($this->meta_data, 'the_grid_item_row');
					$img_width  = $col_size['width']  * $this->base->getVar($this->meta_data, 'the_grid_item_col');
				}
				
				// use aqua_resizer to resize on fly
				if ($grid_style == 'grid') {
					$img_info = tgaq_resize($img_full, $img_width, $img_height, true, false, true);
				} else {
					$img_info = tgaq_resize($img_full, $img_width, 99999, false, false);
				}
				
				if (empty($img_info)) {
					$img_info = wp_get_attachment_image_src($img_ID, 'full');
				}

			} else {
				$time_start = microtime(true);
				$img_size = $this->grid_data['image_size'];
				$img_info = wp_get_attachment_image_src($img_ID, $img_size); 
				
				
			}
			$img_original      = wp_get_attachment_image_src($img_ID, 'full');
			$img_original      = $img_original[0];
			$img_data['alt']   = get_post_meta($img_ID, '_wp_attachment_image_alt', true);
			$img_data['title'] = get_the_title($img_ID);
		} else {
			$img_original = $img_ID;
			$img_info[0]  = $img_ID;
			$img_info[1]  = 500;
			$img_info[2]  = 500;
			$img_data['alt'] = null;
			$img_data['title'] = null;
		}

		// format array info media
		if (!empty($img_info[0])) {
			$img_data['type']   = pathinfo($img_info[0], PATHINFO_EXTENSION);
			$img_data['url']    = $img_info[0];
			$img_data['lb_url'] = $img_original;
			$img_data['width']  = $img_info[1];
			$img_data['height'] = $img_info[2];
		} else {
			$img_data = null;
		}
		
		return $img_data;

	}
	
	/**
	* Smart image size detection based on max value for column_width/window_width
	* @since: 1.0.0
	*/
	public function column_size() {
		
		// build width/col array
		$grid_cols = array(
			$this->grid_data['columns'][0][0]/$this->grid_data['columns'][0][1],
			$this->grid_data['columns'][1][0]/$this->grid_data['columns'][2][1],
			$this->grid_data['columns'][2][0]/$this->grid_data['columns'][3][1],
			$this->grid_data['columns'][3][0]/$this->grid_data['columns'][4][1],
			$this->grid_data['columns'][4][0]/$this->grid_data['columns'][5][1],
			1920/$this->grid_data['columns'][5][1],
		);
		
		// get maximum width based on colNb and window width
		$col_width = round(max($grid_cols));
		
		// Get image ratio
		$item_x_ratio = $this->grid_data['item_x_ratio'];
		$item_y_ratio = $this->grid_data['item_y_ratio'];
		$item_ratio   = number_format((float)$item_x_ratio/$item_y_ratio, 2, '.', '');
		
		// calculate height based on width & ratio
		$col_height   = round($col_width/$item_ratio);
		
		$col_size['height'] = $col_height;
		$col_size['width']  = $col_width;
		
		return $col_size;
	}
	
	/**
	* Build data array for the grid
	* @since 1.0.0
	*/
	public function build_posts_array() {

		return $this->grid_items[] = array(
			'ID'              => $this->post_ID,
			'date'            => $this->post_date,
			'post_type'       => $this->post_type,
			'sticky'          => $this->post_sticky,
			'format'          => $this->post_format,
			'url'             => $this->post_url,
			'url_target'      => $this->post_target,
			'title'           => $this->post_title,
			'excerpt'         => $this->post_excerpt,
			'terms'           => $this->post_terms,
			'author'          => array(
				'ID'     => $this->post_author_ID,				
				'name'   => $this->post_author_name,
				'url'    => $this->post_author_link,
				'avatar' => $this->post_author_avatar,
			),
			'likes_number'    => TO_get_post_like(),
			'comments_number' => $this->post_comments_number,
			'views_number'    => null,
			'image'           => $this->image,
			'product_image'   => $this->product_image,
			'gallery'         => $this->gallery,
			'video'           => $this->video,
			'audio'           => $this->audio,
			'quote'           => $this->quote,
			'link'            => $this->link,
			'meta_data'       => $this->meta_data,
			'product' => array(
				'price'           => $this->product_price,
				'full_price'      => $this->product_full_price,
				'regular_price'   => $this->product_regular_price,
				'sale_price'      => $this->product_sale_price,
				'rating'          => $this->product_rating,
				'text_rating'     => $this->product_text_rating,
				'on_sale'         => $this->product_on_sale,
				'add_to_cart_url' => $this->product_add_cart_url,
				'cart_button'     => $this->product_cart_button,
				'wishlist'        => $this->product_wishlist
			)
		);	

	}
	
}