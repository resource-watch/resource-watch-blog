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

class The_Grid_Nextgen {
				
	/**
	* Grid data
	*
	* @since 2.0.0
	* @access private
	*
	* @var array
	*/
	private $grid_data;

	/**
	* Grid media
	*
	* @since 2.0.0
	* @access private
	*
	* @var array
	*/
	private $media = array();
	
	/**
	* Initialize the class and set its properties.
	* @since 2.0.0
	*/
	public function __construct($grid_data = '') {
		
		$this->grid_data = $grid_data;
		
	}
	
	/**
	* Return array of grid data
	* @since: 2.0.0
	*/
	public function get_grid_data(){

		return $this->grid_data;
		
	}
	
	/**
	* Return array of data
	* @since 2.0.0
	*/
	public function get_grid_items() {

		switch ($this->grid_data['nextgen_source']) {
			case 'gallery':
				$images = $this->get_gallery_images();
				break;
			case 'album':
				$images = $this->get_album_images();
				break;
			case 'single_images':
				$images = $this->get_single_images();
				break;
			case 'recent_images':
				$images = $this->get_recent_images();
				break;
			case 'random_images':
				$images = $this->get_random_images();
				break;
			case 'search_images':
				$images = $this->get_search_images();
				break;
			case 'tags_gallery':
				$images = $this->get_tags_gallery();
				break;
			case 'tags_album':
				$images = $this->get_tags_album();
				break;
			default:
				$error_msg  = __( 'No NextGen source was set in The Grid settings.', 'tg-text-domain' );
				throw new Exception($error_msg);
		}
		
		$this->build_media_array($images);

		return $this->media;

	}

	/**
	* Get gallery list
	* @since 2.0.5
	*/
	public static function get_gallery_list() {
		
		global $nggdb;
		
		if ($nggdb) {
		
			$galleries = $nggdb->find_all_galleries();
			
			$array = array();
			foreach ($galleries as $gallery) {
				$array[$gallery->gid] = $gallery->title;  
				
			}
	
			return $array;
		
		
		}
		
	}
	
	/**
	* Get album list
	* @since 2.0.5
	*/
	public static function get_album_list() {
		
		global $nggdb;
		
		if ($nggdb) {
		
			$albums = $nggdb->find_all_album();
			
			$array = array();
			$array[0] = __( 'All albums', 'tg-text-domain' ); 
			foreach ($albums as $album) {
				$album_details = $nggdb->find_album($album->id);
				$array[$album_details->id] = $album_details->name;  
				
			}
	
			return $array;
		
		}
		
	}
	
	/**
	* Get tag list
	* @since 2.0.5
	*/
	public static function get_tag_list(){
		
		if (class_exists('nggTags')) {
		
			$tags = nggTags::find_all_tags(); 
			
			$array = array();
			foreach ($tags as $tag) {
				$array[$tag->term_id] = $tag->name;  
				
			}
	
			return $array;
		
		}
		
	}
	
	/**
	* Get gallery images from gallery id
	* @since 2.0.5
	*/
	public function get_gallery_images() {
		
		global $nggdb;
		
		if ($nggdb) {
		
			// Parameters: $id, $order_by, $order_dir, $exclude, $limit, $start, $json
			return $nggdb->get_gallery($this->grid_data['nextgen_gallery_id'], 'sortorder', 'ASC', true, $this->grid_data['item_number'], $this->grid_data['offset'], false);
		
		}
		
	}
	
	/**
	* Get album images from album id
	* @since 2.0.5
	*/
	public function get_album_images() {
		
		// Parameters: $album, $order_by, $order_dir, $exclude
		/*$images = nggdb::find_images_in_album($this->grid_data['nextgen_album_id']);*/
		
		/*$album = nggdb::find_album($this->grid_data['nextgen_album_id']);
		$gallery_ids = $album->gallery_ids;
		
		foreach ($gallery_ids as $gallery_id) {
			
		}
		
		print_r($images);
		return $images;*/
		
		// $_GET from wp_query
		$gallery = get_query_var('gallery');
		$album   = get_query_var('album');
		
		// in the case somebody uses the '0', it should be 'all' to show all galleries
		$albumID  = $this->grid_data['nextgen_album_id'];
		$albumID  = ($albumID == 0) ? 'all' : $albumID;
		
		// first look for gallery variable 
		if (!empty($gallery))  {
        
			// subalbum support only one instance, you can't use more of them in one post
			//TODO: causes problems with SFC plugin, due to a second filter callback
			if (isset($GLOBALS['subalbum']) || isset($GLOBALS['nggShowGallery'])) {
                return;
			}
                
			// if gallery is submit, then show the gallery instead 
			$out = nggShowGallery($gallery);
			$GLOBALS['nggShowGallery'] = true;
        
			return $out;
			
		}
    
		if (empty($gallery) && isset($GLOBALS['subalbum'])) {
        	return;
		}
		
		//redirect to subalbum only one time        
		if (!empty($album)) {
			$GLOBALS['subalbum'] = true;
			$albumID = $album;          
		}
		
		// lookup in the database
		$album = nggdb::find_album($albumID);
		
		// still no success ? , die !
		if(!$album) {
        	return __('[Album not found]', 'nggallery');
		}
    
		if (is_array($album->gallery_ids)) {
			$images = $this->creatalbum($album->gallery_ids);
		}
		
		return $images;
			
	}
	
	public function creatalbum($galleriesID) {
		
		global $wpdb, $nggRewrite, $nggdb;
		
		// $_GET from wp_query
		$nggpage     = get_query_var('nggpage');   
		$ngg_options = nggGallery::get_option('ngg_options');
		$maxElement  = (int) $ngg_options['galPagedGalleries'];
		$sortorder   = $galleriesID;
		$galleries   = array();
		
		// get the galleries information    
		foreach ($galleriesID as $i => $value) {
			$galleriesID[$i] = addslashes($value);
		}
			
		$unsort_galleries = $wpdb->get_results('
			SELECT *
			FROM '.$wpdb->nggallery.'
			WHERE gid IN (\''.implode('\',\'', $galleriesID).'\')',
		OBJECT_K); 
		
		$picturesCounter = $wpdb->get_results('
			SELECT galleryid,
			COUNT(*) as counter
			FROM '.$wpdb->nggpictures.'
			WHERE galleryid IN (\''.implode('\',\'', $galleriesID).'\')
			AND exclude != 1
			GROUP BY galleryid',
		OBJECT_K);
		
		if (is_array($picturesCounter)) {
			foreach ($picturesCounter as $key => $value) {
				$unsort_galleries[$key]->counter = $value->counter;
			}
		}
		
		// get the id's of the preview images
		$imagesID = array();
		if (is_array($unsort_galleries)) {
			foreach ($unsort_galleries as $gallery_row) {
				$imagesID[] = $gallery_row->previewpic;
			}
		} 
		  
		$albumPreview = $wpdb->get_results('
			SELECT pid, filename
			FROM '.$wpdb->nggpictures.'
			WHERE pid IN (\''.implode('\',\'', $imagesID).'\')',
		OBJECT_K);
		
		// re-order them and populate some 
		foreach ($sortorder as $key) {
				   
			//if we have a prefix 'a' then it's a subalbum, instead a gallery
			if (substr($key, 0, 1) == 'a') { 
			
				// get the album content
				if (!$subalbum = $nggdb->find_album(substr($key, 1))) {
					continue;
				}
				
				//populate the sub album values
				$galleries[$key]->counter = 0;
				$image = ($subalbum->previewpic > 0) ? $nggdb->find_image($subalbum->previewpic) : null;
				
				//link to the subalbum
				$args['album']   = ($ngg_options['usePermalinks']) ? $subalbum->slug : $subalbum->id;
				$args['gallery'] = false; 
				$args['nggpage'] = false;
				$pageid = isset($subalbum->pageid) ? $subalbum->pageid : 0;
				
				$galleries[$key]->previewpic  = $subalbum->previewpic;
				$galleries[$key]->imageURL    = isset($image->thumbURL) ? $image->thumbURL : '';
				$galleries[$key]->previewname = $subalbum->name;
				$galleries[$key]->pagelink    = ($pageid > 0) ? get_permalink($pageid) : null;
				$galleries[$key]->description = html_entity_decode(nggGallery::i18n($subalbum->albumdesc));
				$galleries[$key]->alttext     = html_entity_decode(nggGallery::i18n($subalbum->name)); 
				
				// apply a filter on gallery object before the output
				$galleries[$key] = apply_filters('ngg_album_galleryobject', $galleries[$key]);
				
				continue;
				
			}
			
			// If a gallery is not found it should be ignored
			if (!$unsort_galleries[$key]) {
				continue;
			}
			
			// Add the counter value if avaible
			$galleries[$key] = $unsort_galleries[$key];
			
			// add the file name and the link 
			if ($galleries[$key]->previewpic  != 0) {
				
				$galleries[$key]->previewname = $albumPreview[$galleries[$key]->previewpic]->filename;
				$galleries[$key]->imageURL    = site_url().'/' . str_replace('\\', '/', $galleries[$key]->path) . '/thumbs/thumbs_' . $albumPreview[$galleries[$key]->previewpic]->filename;
				
			} else {
				
				$first_image = $wpdb->get_row('
					SELECT *
					FROM '. $wpdb->nggpictures .'
					WHERE exclude != 1
					AND galleryid = '. $key .'
					ORDER by pid DESC
					limit 0,1'
				);
				
				$galleries[$key]->previewpic  = $first_image->pid;
				$galleries[$key]->previewname = $first_image->filename;
				$galleries[$key]->imageURL    = site_url() . '/' . str_replace('\\', '/', $galleries[$key]->path) . '/thumbs/thumbs_' . $first_image->filename;
				
			}
			// choose between variable and page link
			if ($ngg_options['galNoPages']) {
				
				$args['album']   = ($ngg_options['usePermalinks']) ? $album->slug : $album->id; 
				$args['gallery'] = ($ngg_options['usePermalinks']) ? $galleries[$key]->slug : $key;
				$args['nggpage'] = false;
				$galleries[$key]->pagelink = get_permalink($args);
				
			} else {
				
				$galleries[$key]->pagelink = get_permalink($galleries[$key]->pageid);
				
			}
			
			// description can contain HTML tags
			$galleries[$key]->description = html_entity_decode(stripslashes($galleries[$key]->galdesc)) ;
			// i18n
			$galleries[$key]->alttext = html_entity_decode(nggGallery::i18n(stripslashes($galleries[$key]->title))) ;
			// apply a filter on gallery object before the output
			$galleries[$key] = apply_filters('ngg_album_galleryobject', $galleries[$key]);
			
		}	
		
		return $galleries;
		
	}
	
	/**
	* Get single images from ids
	* @since 2.0.5
	*/
	public function get_single_images() {
		
		global $nggdb;
		
		if ($nggdb) {

			// get ids
			/*$ids = $this->grid_data['nextgen_image_ids'];
			// replace range ids
			$ids = preg_replace_callback('/(\d+)-(\d+)/', function($id) {
				return implode(',', range($id[1], $id[2]));
			}, $ids);
			// prepare array of ids
			$ids = array_map('trim', explode(',', $ids));
	
			// Parameters: $pids, $exclude, $order
			return $nggdb->find_images_in_list($ids);*/
			
			global $wpdb;
			
			$ids = $this->grid_data['nextgen_image_ids'];
			// replace range ids
			$ids = preg_replace_callback('/(\d+)-(\d+)/', function($id) {
				return implode(',', range($id[1], $id[2]));
			}, $ids);

			$order_clause = 'ORDER BY FIELD(p.pid, '. $ids .')';
			$limit_by     = ($this->grid_data['item_number'] > 0) ? 'LIMIT '. $this->grid_data['offset'] .', ' . $this->grid_data['item_number'] : '';

			return $wpdb->get_results('
				SELECT p.* , g.*
				FROM '. $wpdb->nggallery .' AS g
				INNER JOIN '. $wpdb->nggpictures .' AS p
				ON g.gid = p.galleryid
				WHERE p.pid	IN ('. $ids .')'.
				$order_clause.
				$limit_by,
			OBJECT_K);
		
		}

		
	}
	
	/**
	* Get recent images
	* @since 2.0.5
	*/
	public function get_recent_images(){
		
		global $nggdb;
		
		if ($nggdb) {
		
			// $page = 0, $limit = 30, $exclude = true, $galleryId = 0, $orderby = "id"
			//return $nggdb->find_last_images(1, $this->grid_data['item_number'], true, 0);
			
			global $wpdb;
			
			return $wpdb->get_results('
				SELECT p.pid, g.*, p.*
				FROM '. $wpdb->nggallery .' AS g
				INNER JOIN '. $wpdb->nggpictures .' AS p
				ON g.gid = p.galleryid
				WHERE p.exclude <> 1
				ORDER BY p.pid DESC
				LIMIT '.$this->grid_data['offset'].',' . $this->grid_data['item_number'],
			OBJECT_K);

		}
		
	}
	
	/**
	* Get random images
	* @since 2.0.5
	*/
	public function get_random_images(){
		
		// $number = 1 (of imgs), $galleryID = 0 (optional )
		//return nggdb::get_random_images($this->grid_data['item_number']);
		
		global $wpdb, $tg_is_ajax;
		
		
		session_start();
		
		$seed = -1;
		if (isset($_SESSION['tg_nextgen_ids']) && $tg_is_ajax) {
           	$seed = implode(',', (array) $_SESSION['tg_nextgen_ids']);
        } else {
			$_SESSION['tg_nextgen_ids'] = null;
		}
        
        $galleryID = 0;

        // Query database
        if ($galleryID == 0) {
			
            $images = $wpdb->get_results('
				SELECT p.pid, g.*, p.*
				FROM '. $wpdb->nggallery .' AS g
				INNER JOIN '. $wpdb->nggpictures .' AS p
				ON g.gid = p.galleryid
				WHERE p.exclude <> 1
				AND pid NOT IN ('. $seed .')
				ORDER by rand()
				LIMIT '.$this->grid_data['offset'].',' . $this->grid_data['item_number'],
			OBJECT_K);
			
		} else {
			
            $images = $wpdb->get_results('
				SELECT p.pid, g.*, p.*
				FROM '. $wpdb->nggallery .' AS g
				INNER JOIN '. $wpdb->nggpictures .' AS p
				ON g.gid = p.galleryid
				WHERE t.gid = $galleryID
				AND p.exclude <> 1
				ORDER by rand()
				LIMIT '.$this->grid_data['offset'].',' . $this->grid_data['item_number'],
			OBJECT_K);
			
		}
		
        return $images;	
			
	}
	
	/**
	* Search for images
	* @since 2.0.5
	*/
	public function get_search_images(){
		
		/*global $nggdb;
		
		if ($nggdb) {
		
			// $request, $limit = 0
			return $nggdb->search_for_images($this->grid_data['nextgen_search_request'], $this->grid_data['item_number']);
		
		}*/
		
		
		global $wpdb;
		
		$request = $this->grid_data['nextgen_search_request'];
        
        // If a search pattern is specified, load the posts that match
        if (!empty($request)) {
			
			$search    = null;
            $searchand = null; 
			
            // split the words it a array if seperated by a space or comma
            preg_match_all('/".*?("|$)|((?<=[\\s",+])|^)[^\\s",+]+/', stripslashes($request), $matches);
            $search_terms = (array) array_map(create_function('$a', 'return trim($a, "\\"\'\\n\\r ");'), $matches[0]);
           
            foreach($search_terms as $term) {
                $term      = addslashes_gpc($term);
                $search   .= "{$searchand}((tt.description LIKE '%{$term}%') OR (tt.alttext LIKE '%{$term}%') OR (tt.filename LIKE '%{$term}%'))";
                $searchand = ' AND ';
            }
            
            $term = $wpdb->escape($request);
			
            if (count($search_terms) > 1 && $search_terms[0] != $request) {
                $search .= " OR (tt.description LIKE '%{$term}%') OR (tt.alttext LIKE '%{$term}%') OR (tt.filename LIKE '%{$term}%')";
			}
			
			$search = !empty($search) ? ' AND ('. $search .') ' : $search;
			
        } else {
			
            $error_msg  = __( 'Please enter a Search Query.', 'tg-text-domain' );
			throw new Exception($error_msg);
			
		}
            
        $images = $wpdb->get_results('
			SELECT t.*, tt.*
			FROM '. $wpdb->nggallery .' AS t
			INNER JOIN '. $wpdb->nggpictures .' AS tt
			ON t.gid = tt.galleryid
			WHERE 1=1 '. $search .'
			ORDER BY tt.pid ASC
			LIMIT '.$this->grid_data['offset'].',' . $this->grid_data['item_number']
		);
		
		if (!$images) {
			
			$error_msg  = __( 'No result was found!', 'tg-text-domain' );
			throw new Exception($error_msg);
			
		}
		
        return $images;
		
	}
	
	/**
	* Get album tags images
	* @since 2.0.5
	*/
	public function get_tags_album() {
		
		// $taglist list of tags as csv
		$taglist = $this->arrayToCsv(array('lolo'));
		$picturelist = nggTags::get_album_images($taglist);
		
	}
	
	/**
	* Get album tags images
	* @since 2.0.5
	*/
	public function get_tags_gallery() {
		
		// $taglist list of tags as csv
		$taglist = $this->arrayToCsv(array('lolo'));
		
    	return nggTags::find_images_for_tags($taglist , 'ASC');
	
	}
	
	/**
  * Formats a line (passed as a fields  array) as CSV and returns the CSV as a string.
  * Adapted from http://us3.php.net/manual/en/function.fputcsv.php#87120
  */
	public function arrayToCsv($array) {
	
		$csv = array();
		foreach ($array as $item) {
			if (is_array($item)) {
				$csv[] = array_2_csv($item);
			} else {
				$csv[] = $item;
			}
		}
		return implode(',', $csv);
	
	}
	 
	/**
	* Build data array for the grid
	* @since 2.0.0
	*/
	public function build_media_array($response) {
		
		if (isset($response) && !empty($response)) {

			foreach ($response as $image) {
				//print_r($image);
				
				$image = (isset($image->_ngiw) && isset($image->_ngiw->_cache)) ? (object) $image->_ngiw->_cache : $image;
				
				$image->path     = str_replace('\\', '/', $image->path);
				$image->imageURL = (!$image->imageURL) ? site_url().'/'.$image->path.'/'.$image->filename : $image->imageURL;

				$terms = array();
				$image->terms = wp_get_object_terms($image->pid, 'ngg_tag', 'fields=all');
				if ($image->terms) {
					foreach ($image->terms as $term) {
						$terms[] = array(
							'ID'       => $term->term_id,
							'slug'     => $term->slug,
							'name'     => $term->name,
							'taxonomy' => $term->taxonomy,
							'url'      => null,
							'color'    => null
						);
					}
				}
				
				//print_r(json_decode(BASE64_DECODE($image->meta_data), true));
				$this->media[] = array(
					'ID'              => $image->pid,//$image->extras_post_id,
					'date'            => strtotime($image->imagedate),
					'post_type'       => null,
					'format'          => 'standard',
					'url'             => (isset($image->pagelink)) ? $image->pagelink : null,
					'url_target'      => '_self',
					'title'           => $image->alttext,
					'excerpt'         => $image->description,
					'terms'           => $terms,
					'author'          => null,
					'likes_number'    => null,
					'likes_title'     => null,
					'comments_number' => null,
					'views_number'    => null,
					'image'           => array(
						'alt'    => null,
						'title'  => $image->alttext,
						'url'    => $image->imageURL,
						'lb_url' => $image->imageURL,
						'width'  => $image->meta_data['width'],
						'height' => $image->meta_data['height']
					),
					'gallery'         => null,
					'video'           => null,
					'audio'           => null,
					'quote'           => null,
					'link'            => null,
					'meta_data'       => null
				);
				
				$_SESSION['tg_nextgen_ids'][] = isset($image->pid) ? $image->pid : null;

			}

		}
		
	}
	
}