<?php  if ( ! defined('AVIA_FW')) exit('No direct script access allowed');
/**
 * This file holds the class needed to create dynamic option pages and clone, create and remove option arrays from the option pages
 *
 * @author		Christian "Kriesi" Budschedl
 * @copyright	Copyright (c) Christian Budschedl
 * @link		http://kriesi.at
 * @link		http://aviathemes.com
 * @since		Version 1.0
 * @package 	AviaFramework
 */


/**
 * 
 *
 * 
 * @package AviaFramework
 * 
 */
 
if( ! class_exists( 'avia_database_set' ) )
{
	class avia_database_set
	{	
		/**
		 * avia superobject
		 * @var obj
		 */
		var $avia_superobject;
		
		/**
		 * array we should use for iteratetion
		 * @var array
		 */
		var $elements;
		
		
		/**
		 *  The constructor sets the default element for iteration
		 */
		function __construct($avia_superobject = false)
		{
			if(!$avia_superobject)
			{ 
				$this->avia_superobject = $GLOBALS['avia']; 
			}
			else
			{
				$this->avia_superobject = $avia_superobject; 
			}
			
			$this->elements = $this->avia_superobject->option_page_data; 
		}
		
	
		/**
		 *  The recursive get function retrieves a unqiue array by array key that was requested within an array of choice
		 *  If no array is defined the global unmodified option array will be checked. The values returned is a
		 *  direct reference to this option array, therefore editing the value later will also modify the option array
		 *  The function will call itself with a subarray when an element of type "group" is encountered
		 */
		function get($slug, $elements = false)
		{
			if(!$elements) $elements = $this->elements;
			
			foreach( $elements as $element)
			{
				if($element['type'] == 'group')
				{
					$option = $this->get($slug, $element['subelements']);
					if($option) return $option;
				}
			
				if(isset($element['id']) && $element['id'] == $slug)
				{	
					return $element;
				}
			}
		}
		
				
		
		function add_option_page($data)
		{
			$data['slug'] = avia_backend_safe_string( trim( $data['name'] ));
			$data_to_check = array($data['parent'], $data['name'], $data['slug'] );
		
			//check for invalid data
			foreach($data_to_check as $input)
			{
				if(!avia_backend_check_by_regex($input, 'safe_data'))
				{
					return 'invalid_data';
				}
			}
			
			//check if the name already exists
			foreach($this->avia_superobject->option_pages as $existing_page)
			{
				if($existing_page['title'] == trim($data['name']) || $existing_page['slug'] == $data['slug'])
				{
					return 'name_already_exists';
				}
			}

			
		
			$page_key = $data['prefix']."_dynamic_pages";
							
			$current_options = get_option($page_key);
			if($current_options == "") $current_options = array();
			
			 $result = array( 'slug' => avia_backend_safe_string( $data['slug'] ), 
										'parent'=> $data['parent'], 
										'icon'=> $data['icon'] , 
										'title' => trim($data['name']), 
										'removable' => $data['remove_label'], 
										);
										
			if(isset($data['sortable'])) $result['sortable'] = $data['sortable'];
	
			$current_options[]	= $result;			
			update_option($page_key, $current_options);
			

			return $result;
		}
				
		/**
		 * Function that checks if an element already exists and if so creates a new id for the element  
		 *  
		 */
 
		function create_unqiue_element_id($element, $options)
		{
			$modifier = "";
			while($this->get($element['id'].$modifier, $options))
			{
				if($modifier == "") 
				{ 
					$modifier = 1;
				}
				else
				{
					$modifier++;
				}
			}

			$element['id'] = $element['id'].$modifier;
			return $element;
		}
		


		function add_element_to_db(&$element, $data)
		{
			$option_index = $data['prefix'].'_dynamic_elements';
		
			//get the set of elements saved in the database
			$current_options = get_option($option_index);
			
			//create a new element id and check if it doesnt interfere with the existing elements
			$element = $this->create_unqiue_element_id($element, $current_options);
			
			//update the database: add the new element
			$current_options[$element['id']]	= $element;
			update_option($option_index , $current_options);
						
		}
		
		
		function remove_dynamic_page($data)
		{
			$page_key = $data['prefix']."_dynamic_pages";
			$option_index = $data['prefix']."_dynamic_elements";
			$pages = get_option($page_key);
			$current_options = get_option($option_index);
			
			//delete option page
			foreach($pages as $index => $page)
			{
				if($page['slug'] == $data['elementSlug'])
				{
					unset($pages[$index]);
					break;
				}
			}
			update_option($page_key, $pages);
			
			//delete elements

			foreach($current_options as $index => $element)
			{
				if($element['slug'] == $data['elementSlug'])
				{	
					unset($current_options[$index]);
				}
			}
			update_option($option_index, $current_options);

		}
		
		
		
		function remove_element_from_db($data)
		{
			$option_index = $data['prefix'].'_dynamic_elements';
			
			//get the set of elements saved in the database
			$current_options = get_option($option_index);
			
			foreach($current_options as $index => $element)
			{
				if($element['id'] == $data['elementSlug'])
				{	
					unset($current_options[$index]);
				}
			}

			update_option($option_index , $current_options);


		}

	}
}











