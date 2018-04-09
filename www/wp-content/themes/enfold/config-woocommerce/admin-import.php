<?php
/**
 * Functions for handling WordPress import to make it compatable with WooCommerce
 *
 * WordPress import should work - however, it fails to import custom product attribute taxonomies.
 * This code grabs the file before it is imported and ensures the taxonomies are created.

 */
 
function avia_woocommerce_import_start() {
	
	global $wpdb;
	
	if(isset($_POST['import_id'])) $id = (int) $_POST['import_id'];
	if(isset($id)) $file = get_attached_file( $id );
	
	if(empty($file)) $file = get_template_directory() ."/includes/admin/dummy.xml";
	
	$parser = new WXR_Parser();
	$import_data = $parser->parse( $file );

	if (isset($import_data['posts'])) :
		$posts = $import_data['posts'];
		
		if ($posts && sizeof($posts)>0) foreach ($posts as $post) :
			
			if ($post['post_type']=='product') :
				
				if ($post['terms'] && sizeof($post['terms'])>0) :
					
					foreach ($post['terms'] as $term) :
						
						$domain = $term['domain'];
						
						if (strstr($domain, 'pa_')) :
							
							// Make sure it exists!
							if (!taxonomy_exists( $domain )) :
								
								$nicename = strtolower(sanitize_title(str_replace('pa_', '', $domain)));
								
								$exists_in_db = $wpdb->get_var("SELECT attribute_id FROM ".$wpdb->prefix . "woocommerce_attribute_taxonomies WHERE attribute_name = '".$nicename."';");
								
								if (!$exists_in_db) :
								
									// Create the taxonomy
									$wpdb->insert( $wpdb->prefix . "woocommerce_attribute_taxonomies", array( 'attribute_name' => $nicename, 'attribute_type' => 'select' ), array( '%s', '%s' ) );
									
								endif;
								
								// Register the taxonomy now so that the import works!
								register_taxonomy( $domain,
							        array('product'),
							        array(
							            'hierarchical' => true,
							            'labels' => array(
							                    'name' => $nicename,
							                    'singular_name' => $nicename,
							                    'search_items' =>  __( 'Search', 'woothemes') . ' ' . $nicename,
							                    'all_items' => __( 'All', 'woothemes') . ' ' . $nicename,
							                    'parent_item' => __( 'Parent', 'woothemes') . ' ' . $nicename,
							                    'parent_item_colon' => __( 'Parent', 'woothemes') . ' ' . $nicename . ':',
							                    'edit_item' => __( 'Edit', 'woothemes') . ' ' . $nicename,
							                    'update_item' => __( 'Update', 'woothemes') . ' ' . $nicename,
							                    'add_new_item' => __( 'Add New', 'woothemes') . ' ' . $nicename,
							                    'new_item_name' => __( 'New', 'woothemes') . ' ' . $nicename
							            ),
							            'show_ui' => false,
							            'query_var' => true,
							            'rewrite' => array( 'slug' => strtolower(sanitize_title($nicename)), 'with_front' => false, 'hierarchical' => true ),
							        )
							    );
								
							endif;
							
						endif;
						
					endforeach;
					
				endif;
				
			endif;
			
		endforeach;
		
	endif;

}


function avia_clear_import()
{
	remove_all_actions( 'import_start', 10);
	remove_all_actions( 'import_end', 	10);
	avia_temp_products();
	add_action('import_start', 'avia_woocommerce_import_start');
}

add_action('avia_import_hook','avia_clear_import');


function avia_temp_products()
{
		if(post_type_exists( 'product' )) return false;
		$product_base = $base_slug = $category_base = "";
		
		
		
		register_taxonomy( 'product_cat',
        array('product'),
        array(
            'hierarchical' => true,
            'update_count_callback' => '_update_post_term_count',
            'label' => __( 'Categories', 'woothemes'),
            'labels' => array(
                    'name' => __( 'Categories', 'woothemes'),
                    'singular_name' => __( 'Product Category', 'woothemes'),
                    'search_items' =>  __( 'Search Product Categories', 'woothemes'),
                    'all_items' => __( 'All Product Categories', 'woothemes'),
                    'parent_item' => __( 'Parent Product Category', 'woothemes'),
                    'parent_item_colon' => __( 'Parent Product Category:', 'woothemes'),
                    'edit_item' => __( 'Edit Product Category', 'woothemes'),
                    'update_item' => __( 'Update Product Category', 'woothemes'),
                    'add_new_item' => __( 'Add New Product Category', 'woothemes'),
                    'new_item_name' => __( 'New Product Category Name', 'woothemes')
            ),
            'show_ui' => true,
            'query_var' => true,
            'rewrite' => array( 'slug' => $category_base . _x('product-category', 'slug', 'woothemes'), 'with_front' => false ),
        )
    );
    
    register_taxonomy( 'product_tag',
        array('product'),
        array(
            'hierarchical' => false,
            'label' => __( 'Tags', 'woothemes'),
            'labels' => array(
                    'name' => __( 'Tags', 'woothemes'),
                    'singular_name' => __( 'Product Tag', 'woothemes'),
                    'search_items' =>  __( 'Search Product Tags', 'woothemes'),
                    'all_items' => __( 'All Product Tags', 'woothemes'),
                    'parent_item' => __( 'Parent Product Tag', 'woothemes'),
                    'parent_item_colon' => __( 'Parent Product Tag:', 'woothemes'),
                    'edit_item' => __( 'Edit Product Tag', 'woothemes'),
                    'update_item' => __( 'Update Product Tag', 'woothemes'),
                    'add_new_item' => __( 'Add New Product Tag', 'woothemes'),
                    'new_item_name' => __( 'New Product Tag Name', 'woothemes')
            ),
            'show_ui' => true,
            'query_var' => true,
            'rewrite' => array( 'slug' => $category_base . _x('product-tag', 'slug', 'woothemes'), 'with_front' => false ),
        )
    );
    
    
		register_post_type( "product",
		array(
			'labels' => array(
				'name' => __( 'Products', 'woothemes' ),
				'singular_name' => __( 'Product', 'woothemes' ),
				'add_new' => __( 'Add Product', 'woothemes' ),
				'add_new_item' => __( 'Add New Product', 'woothemes' ),
				'edit' => __( 'Edit', 'woothemes' ),
				'edit_item' => __( 'Edit Product', 'woothemes' ),
				'new_item' => __( 'New Product', 'woothemes' ),
				'view' => __( 'View Product', 'woothemes' ),
				'view_item' => __( 'View Product', 'woothemes' ),
				'search_items' => __( 'Search Products', 'woothemes' ),
				'not_found' => __( 'No Products found', 'woothemes' ),
				'not_found_in_trash' => __( 'No Products found in trash', 'woothemes' ),
				'parent' => __( 'Parent Product', 'woothemes' )
			),
			'description' => __( 'This is where you can add new products to your store.', 'woothemes' ),
			'public' => true,
			'show_ui' => true,
			'capability_type' => 'post',
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'hierarchical' => true,
			'rewrite' => array( 'slug' => $product_base, 'with_front' => false ),
			'query_var' => true,			
			'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'comments'/*, 'page-attributes'*/ ),
			'has_archive' => $base_slug,
			'show_in_nav_menus' => false,
		)
	);
	
	register_post_type( "product_variation",
		array(
			'labels' => array(
				'name' => __( 'Variations', 'woothemes' ),
				'singular_name' => __( 'Variation', 'woothemes' ),
				'add_new' => __( 'Add Variation', 'woothemes' ),
				'add_new_item' => __( 'Add New Variation', 'woothemes' ),
				'edit' => __( 'Edit', 'woothemes' ),
				'edit_item' => __( 'Edit Variation', 'woothemes' ),
				'new_item' => __( 'New Variation', 'woothemes' ),
				'view' => __( 'View Variation', 'woothemes' ),
				'view_item' => __( 'View Variation', 'woothemes' ),
				'search_items' => __( 'Search Variations', 'woothemes' ),
				'not_found' => __( 'No Variations found', 'woothemes' ),
				'not_found_in_trash' => __( 'No Variations found in trash', 'woothemes' ),
				'parent' => __( 'Parent Variation', 'woothemes' )
			),
			'public' => true,
			'show_ui' => false,
			'capability_type' => 'post',
			'publicly_queryable' => true,
			'exclude_from_search' => true,
			'hierarchical' => true,
			'rewrite' => false,
			'query_var' => true,			
			'supports' => array( 'title', 'editor', 'custom-fields', 'page-attributes', 'thumbnail' ),
			'show_in_nav_menus' => false,
			//'show_in_menu' => 'edit.php?post_type=product'
		)
	);
	
	  register_taxonomy( 'product_type',
        array('product'),
        array(
            'hierarchical' => false,
            'show_ui' => false,
            'query_var' => true,
            'show_in_nav_menus' => false,
        )
    );
    
    register_post_type( "shop_order",
		array(
			'labels' => array(
				'name' => __( 'Orders', 'woothemes' ),
				'singular_name' => __( 'Order', 'woothemes' ),
				'add_new' => __( 'Add Order', 'woothemes' ),
				'add_new_item' => __( 'Add New Order', 'woothemes' ),
				'edit' => __( 'Edit', 'woothemes' ),
				'edit_item' => __( 'Edit Order', 'woothemes' ),
				'new_item' => __( 'New Order', 'woothemes' ),
				'view' => __( 'View Order', 'woothemes' ),
				'view_item' => __( 'View Order', 'woothemes' ),
				'search_items' => __( 'Search Orders', 'woothemes' ),
				'not_found' => __( 'No Orders found', 'woothemes' ),
				'not_found_in_trash' => __( 'No Orders found in trash', 'woothemes' ),
				'parent' => __( 'Parent Orders', 'woothemes' )
			),
			'description' => __( 'This is where store orders are stored.', 'woothemes' ),
			'public' => true,
			'show_ui' => true,
			'capability_type' => 'post',
			'publicly_queryable' => false,
			'exclude_from_search' => true,
			'show_in_menu' => 'woocommerce',
			'hierarchical' => false,
			'show_in_nav_menus' => false,
			'rewrite' => false,
			'query_var' => true,			
			'supports' => array( 'title', 'comments', 'custom-fields' ),
			'has_archive' => false
		)
	);
	
    register_taxonomy( 'shop_order_status',
        array('shop_order'),
        array(
            'hierarchical' => true,
            'update_count_callback' => '_update_post_term_count',
            'labels' => array(
                    'name' => __( 'Order statuses', 'woothemes'),
                    'singular_name' => __( 'Order status', 'woothemes'),
                    'search_items' =>  __( 'Search Order statuses', 'woothemes'),
                    'all_items' => __( 'All  Order statuses', 'woothemes'),
                    'parent_item' => __( 'Parent Order status', 'woothemes'),
                    'parent_item_colon' => __( 'Parent Order status:', 'woothemes'),
                    'edit_item' => __( 'Edit Order status', 'woothemes'),
                    'update_item' => __( 'Update Order status', 'woothemes'),
                    'add_new_item' => __( 'Add New Order status', 'woothemes'),
                    'new_item_name' => __( 'New Order status Name', 'woothemes')
            ),
            'show_ui' => false,
            'show_in_nav_menus' => false,
            'query_var' => true,
            'rewrite' => false,
        )
    );
    
    register_post_type( "shop_coupon",
		array(
			'labels' => array(
				'name' => __( 'Coupons', 'woothemes' ),
				'singular_name' => __( 'Coupon', 'woothemes' ),
				'add_new' => __( 'Add Coupon', 'woothemes' ),
				'add_new_item' => __( 'Add New Coupon', 'woothemes' ),
				'edit' => __( 'Edit', 'woothemes' ),
				'edit_item' => __( 'Edit Coupon', 'woothemes' ),
				'new_item' => __( 'New Coupon', 'woothemes' ),
				'view' => __( 'View Coupons', 'woothemes' ),
				'view_item' => __( 'View Coupon', 'woothemes' ),
				'search_items' => __( 'Search Coupons', 'woothemes' ),
				'not_found' => __( 'No Coupons found', 'woothemes' ),
				'not_found_in_trash' => __( 'No Coupons found in trash', 'woothemes' ),
				'parent' => __( 'Parent Coupon', 'woothemes' )
			),
			'description' => __( 'This is where you can add new coupons that customers can use in your store.', 'woothemes' ),
			'public' => true,
			'show_ui' => true,
			'capability_type' => 'post',
			'publicly_queryable' => true,
			'exclude_from_search' => true,
			'show_in_menu' => 'woocommerce',
			'hierarchical' => false,
			'rewrite' => false,
			'query_var' => false,			
			'supports' => array( 'title' ),
			'show_in_nav_menus' => false,
		)
	);
	
		if (!taxonomy_exists('product_type')) :
		register_taxonomy( 'product_type', array('post'));
		register_taxonomy( 'shop_order_status', array('post'));
	endif;
	
	$product_types = array(
		'simple',
		'grouped',
		'variable',
		'downloadable',
		'virtual'
	);
	
	foreach($product_types as $type) {
		if (!get_term_by( 'slug', sanitize_title($type), 'product_type')) {
			wp_insert_term($type, 'product_type');
		}
	}
	
	$order_status = array(
		'pending',
		'failed',
		'on-hold',
		'processing',
		'completed',
		'refunded',
		'cancelled'
	);
	
	foreach($order_status as $status) {
		if (!get_term_by( 'slug', sanitize_title($status), 'shop_order_status')) {
			wp_insert_term($status, 'shop_order_status');
		}
	}

}
