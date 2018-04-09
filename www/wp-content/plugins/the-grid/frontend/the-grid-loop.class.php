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

class The_Grid_Loop {
	
	/**
	* Grid skins
	*
	* @since 1.0.0
	* @access public
	*
	* @var array
	*/
	protected $grid_skins;
		
	/**
	* Skin slug
	*
	* @since 1.0.0
	* @access public
	*
	* @var string
	*/
	protected $skin_slug;
	
	/**
	* Skin php file
	*
	* @since 1.0.0
	* @access public
	*
	* @var string
	*/
	protected $skin_php;
	
	/**
	* Grid item
	*
	* @since 1.0.0
	* @access public
	*
	* @var array
	*/
	public $grid_item;
	
	/**
	* Grid Items
	*
	* @since 1.0.0
	* @access public
	*
	* @var array
	*/
	public $grid_items;
	
	/**
	* item number
	*
	* @since 1.0.0
	* @access public
	*
	* @var integer
	*/
	protected $item_count;
	
	/**
	* The singleton instance
	*
	* @since 1.0.0
	* @access private
	*
	* @var objet
	*/
	static private $instance = null;
	
	/**
	* Base class
	*
	* @since 1.0.0
	* @access private
	*
	* @var objet
	*/
	private $base;
	
	/**
	* To initialize a The_Grid_Loop object
	* @since 1.0.0
	*/
	static public function getInstance() {
		
		if(self::$instance == null) {
			self::$instance = new self;
		}
		
		return self::$instance;
		
	}
	
	/**
	* Output Items
	* @since 1.0.0
	*/
	public function output($grid_data, $grid_items) {
		
		// set grid item
		$this->grid_items = $grid_items;
		
		// set grid data
		$this->grid_data  = $grid_data;
		
		// set grid skins
		$this->grid_skins = $this->grid_data['grid_skins'];
		
		// set the grid base helper class
		$this->base = new The_Grid_Base();
		
		// set item number
		$this->item_count = $this->grid_data['offset']+1;
		
		// run the loop to retrieve items
		$this->loop();

	}
	
	/**
	* Custom loop through items in The Grid
	* @since 1.0.0
	*/
	public function loop() {
		
		if (!empty($this->grid_items)) {
		
			foreach ($this->grid_items as $item) {
				$this->grid_item = $item;		
				$this->item_comment_number();
				$this->item_output();
				$this->update_count_item();	
			}
			
		}
	
	}
	
	/**
	* Display comment number item in the loop
	* @since 1.0.0
	*/
	public function item_comment_number() {
		
		echo '<!-- The Grid item #'.$this->item_count.' -->';
				
	}
	
	/**
	* Output item in the grid
	* @since 1.0.0
	*/
	public function item_output() {
		
		// get current item skin
		$this->item_skin();
	
		// prepare data for current item
		$args = array(
			'grid_data' => $this->grid_data,
			'class'     => $this->item_classes(),
			'attr'      => $this->item_attributes()
		);
		
		$args = array_merge($this->grid_item, $args);
		$args = apply_filters('tg_grid_item_data', $args);

		// build item markup
		echo '<article class="tg-item'.esc_attr($args['class']).'"'.$args['attr'].'>';
		
			echo apply_filters('tg_after_grid_item_start', '', $args);
			
			echo '<div class="tg-item-inner">';
				echo $this->skin_php;
			echo '</div>';	
			
			echo apply_filters('tg_before_grid_item_end', '', $args);
			
		echo '</article>';

	}
	
	/**
	* Count number of item in the loop
	* @since 1.0.0
	*/
	public function update_count_item() {
		
		$this->item_count++;
		
	}

	/**
	* Get item skin
	* @since 1.0.0
	*/
	public function item_skin() {
		
		// current source type (post, instagram,...)
		$source_type = $this->grid_data['source_type'];
		
		// current grid style
		$grid_style  = ($this->grid_data['style'] === 'justified') ? 'grid' : $this->grid_data['style'];
		
		// skin(s) set for the current grid
		$item_skins = $this->grid_data['skins'];
		$item_skins = json_decode($item_skins, true);
		
		// social skin
		$social_skin = $this->grid_data['social_skin'];
		
		// check if current post have a skin set in metadata
		if ($source_type == 'post_type') {
		
			// current post type info
			$post_type = $this->base->getVar($this->grid_item, 'post_type');
			// current post skin
			$meta_data = $this->base->getVar($this->grid_item, 'meta_data', array());
			$item_skin = $this->base->getVar($meta_data, 'the_grid_item_skin');
			// if current post have a skin set and if it exists in registered skins
			if (!empty($item_skin) && isset($this->grid_skins[$item_skin]) && $this->grid_skins[$item_skin]['type'] == $grid_style) {
				// then reassign right skin for current post
				$item_skins[$post_type] = $item_skin;
			}
		
		} else {
			
			// recreate items skin for social media only
			$post_type = 'social';
			$item_skins = array();
			$item_skins[$post_type] = $social_skin;
			
		}

		// if current skin do not exist then assign default skin available from registered skins
		if (!isset($item_skins[$post_type]) || !array_key_exists($item_skins[$post_type], $this->grid_skins) || $this->grid_skins[$item_skins[$post_type]]['type'] != $grid_style) {

			$skin = $this->base->default_skin($grid_style);
			if (!$skin) {
				return false;
			}
			
		} else {
			
			$skin = $item_skins[$post_type];
			
		}
		
		// get slug & content
		$this->skin_slug = $this->grid_skins[$skin]['slug'];
		if ($this->grid_skins[$skin]['php'] == 'is_custom_skin') {
			$this->skin_php = The_Grid_Item($this->skin_slug, $grid_style);
		} else {
			$this->skin_php = include $this->grid_skins[$skin]['php'];
		}
		
	}
	
	/**
	* Build item classes
	* @since 1.0.0
	*/
	public function item_classes() {
		
		// current source type (post, instagram,...)
		$source_type = $this->grid_data['source_type'];
		$preloader   = $this->grid_data['preloader'];
		
		// set post number class
		$post_ID     = $this->base->getVar($this->grid_item, 'ID');
		$post_ID     = (strstr($post_ID, '_', true)) ? strstr($post_ID, '_', true) : $post_ID;
		$post_class  = ' tg-post-'.$post_ID;
		$post_sticky = ($this->base->getVar($this->grid_item, 'sticky')) ? ' sticky' : null;
		$skin_slug   = ' '.$this->skin_slug;
		$preloader   = ($preloader) ? ' tg-item-reveal' : '';
		
		// retrieve item terms
		$terms = $this->item_terms();
		
		return $post_class.$post_sticky.$skin_slug.$terms.$preloader;

	}
	
	/**
	* Build item data attributes
	* @since 1.0.0
	*/
	public function item_attributes() {
		
		// get data attr for sorter
		$meta_data = $this->item_sort_attribute();
		// get data attr col/row item size
		$size_data = $this->item_size();
		
		return $meta_data.$size_data;
		
	}
	
	/**
	* Get item terms
	* @since 1.0.0
	*/
	public function item_terms() {
		
		// retrieve all taxonomies for current post type
		$terms = $this->base->getVar($this->grid_item, 'terms');
		
		$categories = null;
		
		if (!empty($terms)) {
			// loop throught each tax
			foreach($terms as $term) {
				$categories .= ' f'.$term['ID'];
			}
		}
		
		return $categories;
		
	}
	
	/**
	* Get item size
	* @since 1.0.0
	*/
	public function item_size() {
		
		// main vars to retrieve item sizes
		$post_ID     = $this->base->getVar($this->grid_item, 'ID');
		$source_type = $this->grid_data['source_type'];
		$grid_style  = $this->grid_data['style'];
		$force_size  = $this->grid_data['item_force_size'];
		
		// if each in item have same size forced
		if ($force_size && $grid_style != 'justified') {
			
			$item_col  = $this->grid_data['items_col'];
			$item_row  = $this->grid_data['items_row'];

		// check if each item have a custom size
		} else if ($source_type == 'post_type' && !$force_size && $grid_style != 'justified') {
		
			// get meta
			$meta_data = $this->base->getVar($this->grid_item, 'meta_data', array());
			$item_col  = $this->base->getVar($meta_data, 'the_grid_item_col', 1);
			$item_row  = $this->base->getVar($meta_data, 'the_grid_item_row', 1);
			// set col/row number
			$item_col = (!empty($item_col)) ? $item_col : 1;
			$item_row = (!empty($item_row)) ? $item_row : 1;
			
		} else {
			
			// assign default data
			$item_col  = 1;
			$item_row  = 1;
			
		}
		
		// set row/column attribute
		$data_row  = ' data-row="'.esc_attr($item_row).'"';
		$data_col  = ' data-col="'.esc_attr($item_col).'"';
		$data_size = $data_row.$data_col;
		
		return $data_size;
		
	}
	
	/**
	* Add data for sort/filter/search field
	* @since 1.0.0
	*/
	public function item_sort_attribute() {

		$post_ID    = $this->base->getVar($this->grid_item, 'ID');
		$source     = $this->grid_data['source_type'];
		$sort_by    = array_filter(array_merge((array) $this->grid_data['sort_by'], (array) $this->grid_data['sort_by_onload']));
		$meta_data  = $this->base->getVar($this->grid_item, 'meta_data', array());

		// remove "none" and "excerpt" from data attribute (because already present in items)
		if(($key = array_search('excerpt', $sort_by)) !== false) { unset($sort_by[$key]); }
		if(($key = array_search('none', $sort_by)) !== false) { unset($sort_by[$key]); }
		
		// retrieve each data set in sort dropdown list
		if (isset($sort_by) && !empty($sort_by)) {
			foreach ($sort_by as $sort) {
				switch($sort){
					case 'id':
						$data_attr[$sort] = (strstr($post_ID, '_', true)) ? strstr($post_ID, '_', true) : $post_ID;
						break;
					case 'title':
						$data_attr[$sort] = substr($this->base->getVar($this->grid_item, 'title'), 0, 12);
						break;
					case 'author':
						$author_data = $this->base->getVar($this->grid_item, 'author');
						$data_attr[$sort] = $this->base->getVar($author_data, 'name');
						break;
					case 'date':
						$data_attr[$sort] = $this->base->getVar($this->grid_item, 'date');
						break;
					case 'comment':
						$data_attr[$sort] = $this->base->getVar($this->grid_item, 'comments_number');
						break;
					case 'popular_post':
						$data_attr['popular-post'] = $this->base->getVar($meta_data, '_post_like_count', 0);
						break;
					case 'woo_total_sales':
						$data_attr['total-sales'] = $this->base->getVar($meta_data, 'meta_num_total_sales', 0);
						break;
					case 'woo_regular_price':
						$data_attr['regular-price'] = floatval( $this->base->getVar($meta_data, '_regular_price', 0) );
						break;
					case 'woo_sale_price':
						$data_attr['sale-price'] =  floatval( $this->base->getVar($meta_data, '_sale_price', 0) );
						break;
					case 'woo_featured':
						$data_attr[str_replace('woo_','', $sort)] = $this->base->getVar($meta_data, '_featured', 'no');
						break;
					case 'woo_SKU':
						$data_attr[str_replace('woo_','', $sort)] = $this->base->getVar($meta_data, '_sku');
						break;
					case 'woo_stock':
						$data_attr[str_replace('woo_','', $sort)] = $this->base->getVar($meta_data, '_stock');
						break;
					default:
						$meta_data = $this->base->getVar($meta_data, $sort);
						$name = str_replace('_','-',$sort);
						$name = strtolower($name[0] == '-') ? substr($name, 1) : $name;
						$data_attr[esc_attr($name)] = (!empty($meta_data)) ? $meta_data : 0;
						break;
				}
			}
		}
		
		// automatically add attributes from sorters
		$attr = null;
		if (isset($data_attr) && !empty($data_attr)) {
			foreach ($data_attr as $key => $val ) {
				$attr .= ' data-'.esc_attr($key).'="'.esc_attr($val).'"';
			}
		}
		
		return $attr;
		
	}
}

if (!function_exists('The_Grid_Loop')) {
	
	/**
	* Tiny wrappers functions
	* @since 1.0.0
	*/
	function The_Grid_Loop($grid_data, $grid_items) {
		
		$the_grid_loop = The_Grid_Loop::getInstance();	
		return $the_grid_loop->output($grid_data, $grid_items);
		
	}
	
	function tg_get_grid_item() {
		
		$data = The_Grid_Loop::getInstance();
		return (isset($data->grid_item)) ? $data->grid_item : null;
		
	}
	
	function tg_get_grid_data() {
		
		$data = The_Grid_Loop::getInstance();
		return (isset($data->grid_data)) ?  $data->grid_data : null;
		
	}
	
}